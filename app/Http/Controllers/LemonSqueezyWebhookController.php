<?php

namespace App\Http\Controllers;

use App\Services\LemonSqueezyFulfillmentService;
use App\Services\LemonSqueezyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LemonSqueezyWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        LemonSqueezyService $lemonSqueezy,
        LemonSqueezyFulfillmentService $fulfillment
    ) {
        $payload = $request->getContent();
        $signature = $request->header('X-Signature');

        if (! $lemonSqueezy->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Lemon Squeezy webhook signature invalide.');

            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $eventName = (string) data_get($request->all(), 'meta.event_name', '');
        if ($eventName !== 'order_created') {
            return response()->json(['received' => true]);
        }

        $status = strtolower((string) data_get($request->all(), 'data.attributes.status', ''));
        if ($status !== 'paid') {
            return response()->json(['received' => true, 'ignored' => 'not_paid']);
        }

        $custom = (array) data_get($request->all(), 'meta.custom_data', []);
        $transactionId = (string) (
            data_get($request->all(), 'data.attributes.identifier')
            ?: data_get($request->all(), 'data.id')
            ?: ('ls-'.uniqid())
        );

        try {
            $result = $fulfillment->handlePaidCheckout($custom, $transactionId, 'lemonsqueezy');
        } catch (\Throwable $exception) {
            report($exception);
            Log::error('Lemon Squeezy fulfillment failed', [
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
