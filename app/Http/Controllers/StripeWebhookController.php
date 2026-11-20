<?php

namespace App\Http\Controllers;

use App\Services\PaymentFulfillmentService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        StripeService $stripe,
        PaymentFulfillmentService $fulfillment
    ) {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (! $stripe->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Stripe webhook signature invalide.');

            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = $request->all();
        $type = (string) ($event['type'] ?? '');

        if (! in_array($type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            return response()->json(['received' => true]);
        }

        $session = (array) data_get($event, 'data.object', []);
        $paymentStatus = strtolower((string) ($session['payment_status'] ?? ''));

        if ($paymentStatus !== 'paid') {
            return response()->json(['received' => true, 'ignored' => 'not_paid']);
        }

        $custom = (array) ($session['metadata'] ?? []);
        $transactionId = (string) (
            ($session['payment_intent'] ?? null)
            ?: ($session['id'] ?? null)
            ?: ('stripe-'.uniqid())
        );

        try {
            $result = $fulfillment->handlePaidCheckout($custom, $transactionId, 'stripe');
        } catch (\Throwable $exception) {
            report($exception);
            Log::error('Stripe fulfillment failed', [
                'message' => $exception->getMessage(),
                'custom' => $custom,
            ]);

            return response()->json(['message' => 'Fulfillment failed'], 500);
        }

        return response()->json([
            'received' => true,
            'ok' => (bool) ($result['ok'] ?? false),
        ]);
    }
}
