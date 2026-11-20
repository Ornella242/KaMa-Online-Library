<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CurrencyFreaksService
{
    public function isConfigured(): bool
    {
        return filled(config('services.currencyfreaks.api_key'));
    }

    /**
     * Taux 1 EUR → devises demandées (cache TTL configurable).
     * Aucune marge / markup : taux bruts CurrencyFreaks uniquement.
     *
     * @param  list<string>  $currencies
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    public function eurRates(array $currencies, bool $forceRefresh = false): array
    {
        $currencies = array_values(array_unique(array_map(
            fn ($c) => strtoupper(trim((string) $c)),
            $currencies
        )));
        $currencies = array_values(array_filter($currencies, fn ($c) => $c !== '' && $c !== 'EUR'));

        sort($currencies);
        $cacheKey = 'currencyfreaks.eur_rates.v2.'.md5(implode(',', $currencies));
        $ttl = max(60, (int) config('services.currencyfreaks.cache_ttl', 3600));

        if (! $forceRefresh) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached) && ! empty($cached['rates'])) {
                return $cached;
            }
        }

        $fresh = $this->fetchEurRates($currencies);
        Cache::put($cacheKey, $fresh, $ttl);
        Cache::forever('currencyfreaks.eur_rates.last_good', $fresh);

        return $fresh;
    }

    /**
     * @param  list<string>  $currencies
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    private function fetchEurRates(array $currencies): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException(
                'CurrencyFreaks n’est pas configuré. Ajoutez CURRENCYFREAKS_API_KEY dans le .env.'
            );
        }

        $aliases = (array) config('services.currencyfreaks.aliases', []);
        $symbols = [];

        foreach ($currencies as $currency) {
            $symbols[] = $currency;
            if (isset($aliases[$currency])) {
                $symbols[] = strtoupper((string) $aliases[$currency]);
            }
        }

        $symbols = array_values(array_unique($symbols));

        try {
            // Plans payants : base=EUR = même référentiel que le convertisseur CurrencyFreaks.
            $direct = $this->requestLatest($symbols, 'EUR');

            if ($direct['ok']) {
                return $this->mapDirectEurRates($currencies, $direct['json'], $aliases);
            }

            // Plan gratuit : base USD uniquement → croisement mathématique EUR→devise
            // (USD→devise) / (USD→EUR). Aucune marge ajoutée.
            if (($direct['status'] ?? 0) === 402) {
                $usd = $this->requestLatest(array_values(array_unique(array_merge(['EUR'], $symbols))), null);

                if (! $usd['ok']) {
                    return $this->fallbackOrFail($usd);
                }

                return $this->mapUsdCrossRates($currencies, $usd['json'], $aliases);
            }

            return $this->fallbackOrFail($direct);
        } catch (RuntimeException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::warning('CurrencyFreaks unexpected error', ['message' => $exception->getMessage()]);

            $fallback = Cache::get('currencyfreaks.eur_rates.last_good');
            if (is_array($fallback) && ! empty($fallback['rates'])) {
                $fallback['source'] = 'cache_fallback';

                return $fallback;
            }

            throw new RuntimeException('Impossible de récupérer les taux CurrencyFreaks.');
        }
    }

    /**
     * @param  list<string>  $symbols
     * @return array{ok:bool,status:int,json:array<string,mixed>,message:?string}
     */
    private function requestLatest(array $symbols, ?string $base): array
    {
        $query = [
            'apikey' => (string) config('services.currencyfreaks.api_key'),
            'symbols' => implode(',', $symbols),
        ];

        if ($base) {
            $query['base'] = $base;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get('https://api.currencyfreaks.com/v2.0/rates/latest', $query);

            $json = $response->json() ?? [];

            if ($response->successful()) {
                return ['ok' => true, 'status' => $response->status(), 'json' => $json, 'message' => null];
            }

            return [
                'ok' => false,
                'status' => $response->status(),
                'json' => $json,
                'message' => (string) (data_get($json, 'message') ?: 'Erreur CurrencyFreaks HTTP '.$response->status()),
            ];
        } catch (RequestException $exception) {
            $json = $exception->response?->json() ?? [];

            return [
                'ok' => false,
                'status' => (int) ($exception->response?->status() ?: 0),
                'json' => $json,
                'message' => (string) (data_get($json, 'message') ?: $exception->getMessage()),
            ];
        }
    }

    /**
     * @param  list<string>  $currencies
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $aliases
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    private function mapDirectEurRates(array $currencies, array $payload, array $aliases): array
    {
        $raw = (array) ($payload['rates'] ?? []);
        $rates = [];

        foreach ($currencies as $currency) {
            $value = $this->pickRate($currency, $raw, $aliases);
            if ($value !== null && $value > 0) {
                $rates[$currency] = $value;
            }
        }

        if ($rates === []) {
            throw new RuntimeException('Aucun taux Mobile Money reçu depuis CurrencyFreaks (base EUR).');
        }

        return [
            'rates' => $rates,
            'date' => isset($payload['date']) ? (string) $payload['date'] : null,
            'source' => 'currencyfreaks_eur',
        ];
    }

    /**
     * @param  list<string>  $currencies
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $aliases
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    private function mapUsdCrossRates(array $currencies, array $payload, array $aliases): array
    {
        $raw = (array) ($payload['rates'] ?? []);
        $usdToEur = $this->pickRate('EUR', $raw, []);

        if ($usdToEur === null || $usdToEur <= 0) {
            throw new RuntimeException('CurrencyFreaks n’a pas renvoyé le taux EUR.');
        }

        $rates = [];
        foreach ($currencies as $currency) {
            $usdToLocal = $this->pickRate($currency, $raw, $aliases);
            if ($usdToLocal === null || $usdToLocal <= 0) {
                continue;
            }
            // Exact float — pas d’arrondi anticipé qui décale le montant.
            $rates[$currency] = $usdToLocal / $usdToEur;
        }

        if ($rates === []) {
            throw new RuntimeException('Aucun taux Mobile Money reçu depuis CurrencyFreaks.');
        }

        return [
            'rates' => $rates,
            'date' => isset($payload['date']) ? (string) $payload['date'] : null,
            'source' => 'currencyfreaks_usd_cross',
        ];
    }

    /**
     * @param  array{ok:bool,status:int,json:array<string,mixed>,message:?string}  $failed
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    private function fallbackOrFail(array $failed): array
    {
        Log::warning('CurrencyFreaks API error', [
            'status' => $failed['status'] ?? null,
            'message' => $failed['message'] ?? null,
            'body' => $failed['json'] ?? null,
        ]);

        $fallback = Cache::get('currencyfreaks.eur_rates.last_good');
        if (is_array($fallback) && ! empty($fallback['rates'])) {
            $fallback['source'] = 'cache_fallback';

            return $fallback;
        }

        throw new RuntimeException((string) ($failed['message'] ?: 'Impossible de récupérer les taux CurrencyFreaks.'));
    }

    /**
     * @param  array<string, mixed>  $rates
     * @param  array<string, string>  $aliases
     */
    private function pickRate(string $currency, array $rates, array $aliases): ?float
    {
        if (isset($rates[$currency]) && is_numeric($rates[$currency])) {
            return (float) $rates[$currency];
        }

        $alias = strtoupper((string) ($aliases[$currency] ?? ''));
        if ($alias !== '' && isset($rates[$alias]) && is_numeric($rates[$alias])) {
            return (float) $rates[$alias];
        }

        return null;
    }
}
