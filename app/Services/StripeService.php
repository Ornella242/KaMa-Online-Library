<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeService
{
    public function isConfigured(): bool
    {
        return filled(config('services.stripe.secret'));
    }

    public function isTestMode(): bool
    {
        $secret = (string) config('services.stripe.secret', '');

        return str_starts_with($secret, 'sk_test_')
            || (bool) config('services.stripe.test_mode', true);
    }

    public function toCents(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    /**
     * Create a Stripe Checkout Session (USD) and return its URL + id.
     *
     * @param  array{name?:string,email?:string,custom?:array<string,string>}  $customer
     * @return array{id:string,url:string}
     */
    public function createCheckout(
        float|string $amount,
        array $customer = [],
        ?string $successUrl = null,
        ?string $cancelUrl = null,
        ?string $productName = null
    ): array {
        abort_unless($this->isConfigured(), 503, 'Stripe n’est pas encore configuré.');

        $cents = $this->toCents($amount);
        abort_if($cents < 50, 422, 'Le montant minimum Stripe est de 0,50 EUR.');

        $metadata = [];
        foreach (($customer['custom'] ?? []) as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $metadata[(string) $key] = (string) $value;
        }

        $payload = [
            'mode' => 'payment',
            'success_url' => $successUrl ?: url('/'),
            'cancel_url' => $cancelUrl ?: url('/'),
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $cents,
                    'product_data' => [
                        'name' => $productName ?: 'Paiement KaMa',
                    ],
                ],
            ]],
            'payment_method_types' => ['card'],
        ];

        if (! empty($customer['email'])) {
            $payload['customer_email'] = $customer['email'];
        }

        if ($metadata !== []) {
            $payload['metadata'] = $metadata;
            $payload['payment_intent_data'] = [
                'metadata' => $metadata,
            ];
        }

        try {
            $response = Http::withToken((string) config('services.stripe.secret'))
                ->asForm()
                ->acceptJson()
                ->timeout(30)
                ->post('https://api.stripe.com/v1/checkout/sessions', $this->flatten($payload))
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            report($exception);

            $message = data_get($exception->response?->json(), 'error.message')
                ?: 'Impossible de créer le checkout Stripe. Vérifiez la clé secrète.';

            throw new RuntimeException($message);
        }

        $id = (string) ($response['id'] ?? '');
        $url = (string) ($response['url'] ?? '');

        if ($id === '' || $url === '') {
            throw new RuntimeException('Stripe n’a pas renvoyé d’URL de paiement.');
        }

        return [
            'id' => $id,
            'url' => $url,
        ];
    }

    public function verifyWebhookSignature(string $payload, ?string $signatureHeader): bool
    {
        $secret = (string) config('services.stripe.webhook_secret', '');

        if ($secret === '' || ! filled($signatureHeader)) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $item) {
            [$key, $value] = array_pad(explode('=', trim($item), 2), 2, null);
            if ($key && $value) {
                $parts[$key][] = $value;
            }
        }

        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];

        if (! $timestamp || $signatures === []) {
            return false;
        }

        // Reject timestamps older than 5 minutes.
        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $signedPayload = $timestamp.'.'.$payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Flatten nested arrays for Stripe application/x-www-form-urlencoded API.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, scalar>
     */
    private function flatten(array $data, string $prefix = ''): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $formKey = $prefix === '' ? (string) $key : $prefix.'['.$key.']';

            if (is_array($value)) {
                $result += $this->flatten($value, $formKey);
                continue;
            }

            if (is_bool($value)) {
                $result[$formKey] = $value ? 'true' : 'false';
                continue;
            }

            if ($value === null) {
                continue;
            }

            $result[$formKey] = $value;
        }

        return $result;
    }
}
