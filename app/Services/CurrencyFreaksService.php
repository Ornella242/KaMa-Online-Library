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
        $cacheKey = 'currencyfreaks.eur_rates.'.md5(implode(',', $currencies));
        $ttl = max(60, (int) config('services.currencyfreaks.cache_ttl', 3600));

        if (! $forceRefresh) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached) && ! empty($cached['rates'])) {
                return $cached;
            }
        }

        $fresh = $this->fetchEurRates($currencies);
        Cache::put($cacheKey, $fresh, $ttl);

        // Dernier bon snapshot (fallback si l’API tombe).
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
        $symbols = ['EUR'];

        foreach ($currencies as $currency) {
            $symbols[] = $currency;
            if (isset($aliases[$currency])) {
                $symbols[] = strtoupper((string) $aliases[$currency]);
            }
        }

        $symbols = array_values(array_unique($symbols));

        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get('https://api.currencyfreaks.com/v2.0/rates/latest', [
                    'apikey' => (string) config('services.currencyfreaks.api_key'),
                    'symbols' => implode(',', $symbols),
                ])
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            Log::warning('CurrencyFreaks API error', [
                'message' => $exception->getMessage(),
                'body' => $exception->response?->json(),
            ]);

            $fallback = Cache::get('currencyfreaks.eur_rates.last_good');
            if (is_array($fallback) && ! empty($fallback['rates'])) {
                $fallback['source'] = 'cache_fallback';

                return $fallback;
            }

            $message = data_get($exception->response?->json(), 'message')
                ?: 'Impossible de récupérer les taux CurrencyFreaks.';

            throw new RuntimeException((string) $message);
        }

        $usdRates = (array) ($response['rates'] ?? []);
        $usdToEur = (float) ($usdRates['EUR'] ?? 0);

        if ($usdToEur <= 0) {
            throw new RuntimeException('CurrencyFreaks n’a pas renvoyé le taux EUR.');
        }

        $rates = [];
        foreach ($currencies as $currency) {
            $usdToLocal = $this->usdRateFor($currency, $usdRates, $aliases);
            if ($usdToLocal === null || $usdToLocal <= 0) {
                continue;
            }
            $rates[$currency] = round($usdToLocal / $usdToEur, 6);
        }

        if ($rates === []) {
            throw new RuntimeException('Aucun taux Mobile Money reçu depuis CurrencyFreaks.');
        }

        return [
            'rates' => $rates,
            'date' => isset($response['date']) ? (string) $response['date'] : null,
            'source' => 'currencyfreaks',
        ];
    }

    /**
     * @param  array<string, mixed>  $usdRates
     * @param  array<string, string>  $aliases
     */
    private function usdRateFor(string $currency, array $usdRates, array $aliases): ?float
    {
        if (isset($usdRates[$currency]) && is_numeric($usdRates[$currency])) {
            return (float) $usdRates[$currency];
        }

        $alias = strtoupper((string) ($aliases[$currency] ?? ''));
        if ($alias !== '' && isset($usdRates[$alias]) && is_numeric($usdRates[$alias])) {
            return (float) $usdRates[$alias];
        }

        return null;
    }
}
