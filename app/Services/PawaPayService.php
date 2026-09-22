<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class PawaPayService
{
    public function isConfigured(): bool
    {
        return filled(config('services.pawapay.api_token'));
    }

    public function isSandbox(): bool
    {
        return (bool) config('services.pawapay.sandbox', true);
    }

    public function baseUrl(): string
    {
        return $this->isSandbox()
            ? 'https://api.sandbox.pawapay.io'
            : 'https://api.pawapay.io';
    }

    /**
     * @return array<string, array{iso3:string,currency:string,decimals:bool|int,name:string}>
     */
    public function markets(): array
    {
        return (array) config('pawapay.markets', []);
    }

    /**
     * @return array{code:string,iso3:string,currency:string,decimals:bool|int,name:string}|null
     */
    public function resolveCountry(?string $iso2): ?array
    {
        $iso2 = strtoupper(trim((string) $iso2));
        $market = $this->markets()[$iso2] ?? null;

        if (! $market) {
            return null;
        }

        return [
            'code' => $iso2,
            'iso3' => (string) $market['iso3'],
            'currency' => (string) $market['currency'],
            'decimals' => $market['decimals'],
            'name' => (string) $market['name'],
        ];
    }

    /**
     * @deprecated Use resolveCountry()
     * @return array{code:string,name:string,iso3:string}|null
     */
    public function resolveXofCountry(?string $iso2): ?array
    {
        $resolved = $this->resolveCountry($iso2);

        return $resolved ? [
            'code' => $resolved['code'],
            'iso3' => $resolved['iso3'],
            'name' => $resolved['name'],
        ] : null;
    }

    /**
     * Devises locales utilisées par les marchés PawaPay.
     *
     * @return list<string>
     */
    public function marketCurrencies(): array
    {
        $currencies = [];
        foreach ($this->markets() as $market) {
            $code = strtoupper((string) ($market['currency'] ?? ''));
            if ($code !== '') {
                $currencies[$code] = $code;
            }
        }

        return array_values($currencies);
    }

    /**
     * Taux 1 EUR → devise locale via CurrencyFreaks.
     *
     * @return array<string, float>
     */
    public function rates(bool $forceRefresh = false): array
    {
        $payload = app(CurrencyFreaksService::class)->eurRates(
            $this->marketCurrencies(),
            $forceRefresh
        );

        return (array) ($payload['rates'] ?? []);
    }

    /**
     * @return array{rates: array<string, float>, date: ?string, source: string}
     */
    public function ratesMeta(bool $forceRefresh = false): array
    {
        return app(CurrencyFreaksService::class)->eurRates(
            $this->marketCurrencies(),
            $forceRefresh
        );
    }

    public function rateFor(string $currency): float
    {
        $currency = strtoupper($currency);
        $rate = $this->rates()[$currency] ?? null;

        if (! is_numeric($rate) || (float) $rate <= 0) {
            throw new RuntimeException(
                "Taux de change EUR → {$currency} indisponible via CurrencyFreaks. Vérifiez CURRENCYFREAKS_API_KEY."
            );
        }

        return (float) $rate;
    }

    /**
     * Convertit un montant EUR vers la devise du marché.
     *
     * @return array{amount:string,numeric:float,currency:string,label:string,decimals:bool|int}
     */
    public function convertFromEur(float|string $eurAmount, string $currency, bool|int $decimals = false): array
    {
        $currency = strtoupper($currency);
        $converted = ((float) $eurAmount) * $this->rateFor($currency);

        if ($decimals === false || $decimals === 0) {
            $numeric = (float) max(1, (int) round($converted));
            $amount = (string) (int) $numeric;
            $label = number_format($numeric, 0, '.', ' ').' '.$currency;
        } else {
            $places = (int) $decimals;
            $numeric = round($converted, $places);
            $numeric = max($numeric, 1 / (10 ** $places));
            $amount = number_format($numeric, $places, '.', '');
            $label = number_format($numeric, $places, '.', ' ').' '.$currency;
        }

        return [
            'amount' => $amount,
            'numeric' => $numeric,
            'currency' => $currency,
            'label' => $label,
            'decimals' => $decimals,
        ];
    }

    /**
     * @deprecated Use convertFromEur()
     */
    public function eurToXof(float|string $eurAmount): int
    {
        return (int) $this->convertFromEur($eurAmount, 'XOF', false)['numeric'];
    }

    /**
     * Create a hosted Payment Page session.
     *
     * @param  array{order_id?:string,order_reference?:string,type?:string}  $meta
     * @return array{deposit_id:string,redirect_url:string}
     */
    public function createPaymentPage(
        string $amount,
        string $currency,
        string $countryIso3,
        string $returnUrl,
        string $reason,
        array $meta = [],
        ?string $depositId = null
    ): array {
        abort_unless($this->isConfigured(), 503, 'PawaPay n’est pas encore configuré.');
        abort_if($amount === '' || (float) $amount <= 0, 422, 'Montant Mobile Money invalide.');

        $depositId = $depositId ?: (string) Str::uuid();

        $payload = [
            'depositId' => $depositId,
            'returnUrl' => $returnUrl,
            'amountDetails' => [
                'amount' => $amount,
                'currency' => strtoupper($currency),
            ],
            'country' => strtoupper($countryIso3),
            'language' => 'FR',
            'reason' => Str::limit(preg_replace('/[^a-zA-Z0-9 ]/', ' ', $reason) ?: 'Paiement KaMa', 50, ''),
        ];

        $metadata = [];
        foreach ($meta as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $metadata[] = [(string) $key => (string) $value];
        }
        if ($metadata !== []) {
            $payload['metadata'] = array_slice($metadata, 0, 10);
        }

        try {
            $http = Http::withToken((string) config('services.pawapay.api_token'))
                ->acceptJson()
                ->timeout(30)
                ->post($this->baseUrl().'/v2/paymentpage', $payload);

            $response = $http->json() ?? [];

            // Sandbox/prod peuvent renvoyer 200/201 avec failureReason (sans redirectUrl).
            if ($http->failed() || filled(data_get($response, 'failureReason')) || ($response['status'] ?? null) === 'REJECTED') {
                $code = (string) (data_get($response, 'failureReason.failureCode') ?: '');
                $message = (string) (
                    data_get($response, 'failureReason.failureMessage')
                    ?: data_get($response, 'failureMessage')
                    ?: 'Impossible de créer la page de paiement PawaPay.'
                );

                logger()->warning('PawaPay paymentpage rejected', [
                    'http_status' => $http->status(),
                    'failure_code' => $code,
                    'failure_message' => $message,
                    'country' => $payload['country'],
                    'amount' => $payload['amountDetails'],
                    'deposit_id' => $depositId,
                ]);

                throw new RuntimeException(trim($message));
            }
        } catch (RequestException $exception) {
            report($exception);

            $message = data_get($exception->response?->json(), 'failureReason.failureMessage')
                ?: data_get($exception->response?->json(), 'failureMessage')
                ?: 'Impossible de créer la page de paiement PawaPay.';

            throw new RuntimeException((string) $message);
        }

        $redirectUrl = (string) ($response['redirectUrl'] ?? $response['redirectURL'] ?? '');
        if ($redirectUrl === '') {
            logger()->warning('PawaPay paymentpage missing redirectUrl', [
                'http_status' => $http->status(),
                'body' => $response,
                'deposit_id' => $depositId,
            ]);

            throw new RuntimeException('PawaPay n’a pas renvoyé d’URL de paiement.');
        }

        return [
            'deposit_id' => $depositId,
            'redirect_url' => $redirectUrl,
        ];
    }

    /**
     * @return array{status:string,data?:array<string,mixed>}
     */
    public function checkDeposit(string $depositId): array
    {
        abort_unless($this->isConfigured(), 503, 'PawaPay n’est pas encore configuré.');

        try {
            $response = Http::withToken((string) config('services.pawapay.api_token'))
                ->acceptJson()
                ->timeout(20)
                ->get($this->baseUrl().'/v2/deposits/'.$depositId)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            report($exception);

            return ['status' => 'ERROR'];
        }

        return [
            'status' => (string) ($response['status'] ?? 'UNKNOWN'),
            'data' => (array) ($response['data'] ?? $response),
        ];
    }
}
