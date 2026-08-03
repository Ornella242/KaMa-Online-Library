<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LemonSqueezyService
{
    public function isConfigured(): bool
    {
        return filled(config('services.lemonsqueezy.api_key'))
            && filled(config('services.lemonsqueezy.store_id'))
            && filled(config('services.lemonsqueezy.variant_id'));
    }

    public function isTestMode(): bool
    {
        return (bool) config('services.lemonsqueezy.test_mode', true);
    }

    /**
     * Convert a decimal currency amount (e.g. 12.50 USD) to the smallest unit (cents).
     */
    public function toCents(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    /**
     * Create a Lemon Squeezy checkout and return its URL + id.
     *
     * @param  array{name?:string,email?:string,custom?:array<string,mixed>}  $customer
     * @return array{id:string,url:string}
     */
    public function createCheckout(
        float|string $amount,
        array $customer = [],
        ?string $redirectUrl = null,
        ?string $productName = null
    ): array {
        abort_unless($this->isConfigured(), 503, 'Lemon Squeezy n’est pas encore configuré.');

        $cents = $this->toCents($amount);
        abort_if($cents < 1, 422, 'Le montant de paiement est invalide.');

        $checkoutData = array_filter([
            'email' => $customer['email'] ?? null,
            'name' => $customer['name'] ?? null,
            'custom' => $customer['custom'] ?? [],
        ], fn ($value) => $value !== null && $value !== '');

        if (! empty($customer['custom'] ?? [])) {
            $checkoutData['custom'] = $customer['custom'];
        }

        $attributes = [
            'custom_price' => $cents,
            'checkout_options' => [
                'embed' => true,
                'media' => false,
                'logo' => true,
                'desc' => true,
                'discount' => false,
                'button_color' => '#b30000',
            ],
            'checkout_data' => $checkoutData,
            'product_options' => array_filter([
                'name' => $productName,
                'redirect_url' => $redirectUrl,
                'receipt_button_text' => 'Retourner sur KaMa',
                'receipt_thank_you_note' => 'Merci pour votre achat sur KaMa Online Library.',
            ]),
        ];

        try {
            $response = Http::withToken(config('services.lemonsqueezy.api_key'))
                ->accept('application/vnd.api+json')
                ->contentType('application/vnd.api+json')
                ->timeout(30)
                ->post('https://api.lemonsqueezy.com/v1/checkouts', [
                    'data' => [
                        'type' => 'checkouts',
                        'attributes' => $attributes,
                        'relationships' => [
                            'store' => [
                                'data' => [
                                    'type' => 'stores',
                                    'id' => (string) config('services.lemonsqueezy.store_id'),
                                ],
                            ],
                            'variant' => [
                                'data' => [
                                    'type' => 'variants',
                                    'id' => (string) config('services.lemonsqueezy.variant_id'),
                                ],
                            ],
                        ],
                    ],
                ])
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            report($exception);

            throw new RuntimeException(
                'Impossible de créer le checkout Lemon Squeezy. Vérifiez la configuration (API key, store, variant).'
            );
        }

        $id = (string) data_get($response, 'data.id', '');
        $url = (string) data_get($response, 'data.attributes.url', '');

        if ($id === '' || $url === '') {
            throw new RuntimeException('Lemon Squeezy n’a pas renvoyé d’URL de paiement.');
        }

        return [
            'id' => $id,
            'url' => $url,
        ];
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        $secret = (string) config('services.lemonsqueezy.webhook_secret', '');

        if ($secret === '' || ! filled($signature)) {
            return false;
        }

        $digest = hash_hmac('sha256', $payload, $secret);

        return hash_equals($digest, $signature);
    }
}
