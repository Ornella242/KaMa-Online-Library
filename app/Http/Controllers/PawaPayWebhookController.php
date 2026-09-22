<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentFulfillmentService;
use App\Services\PawaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PawaPayWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        PawaPayService $pawaPay,
        PaymentFulfillmentService $fulfillment
    ) {
        $payload = $request->all();

        // Deposit callbacks may be nested under data or top-level.
        $deposit = (array) (data_get($payload, 'data') ?: $payload);
        $status = strtoupper((string) ($deposit['status'] ?? data_get($payload, 'status') ?? ''));
        $depositId = (string) ($deposit['depositId'] ?? data_get($payload, 'depositId') ?? '');

        if ($depositId === '') {
            Log::warning('PawaPay webhook sans depositId', ['payload' => $payload]);

            return response()->json(['message' => 'Missing depositId'], 400);
        }

        if ($status !== 'COMPLETED') {
            return response()->json([
                'received' => true,
                'ignored' => $status ?: 'unknown_status',
            ]);
        }

        $custom = $this->extractMetadata($deposit);
        if ($custom === [] || empty($custom['type'])) {
            $order = Order::query()->where('transaction_id', $depositId)->first();
            if ($order) {
                $custom = [
                    'type' => 'order',
                    'order_id' => (string) $order->id,
                    'order_reference' => $order->reference,
                ];
            }
        }

        try {
            $result = $fulfillment->handlePaidCheckout($custom, $depositId, 'pawapay');
        } catch (\Throwable $exception) {
            report($exception);
            Log::error('PawaPay fulfillment failed', [
                'message' => $exception->getMessage(),
                'depositId' => $depositId,
                'custom' => $custom,
            ]);

            return response()->json(['message' => 'Fulfillment failed'], 500);
        }

        return response()->json([
            'received' => true,
            'ok' => (bool) ($result['ok'] ?? false),
        ]);
    }

    /**
     * @param  array<string, mixed>  $deposit
     * @return array<string, string>
     */
    private function extractMetadata(array $deposit): array
    {
        $metadata = $deposit['metadata'] ?? [];
        $custom = [];

        if (! is_array($metadata)) {
            return [];
        }

        foreach ($metadata as $item) {
            if (! is_array($item)) {
                continue;
            }
            foreach ($item as $key => $value) {
                if ($key === 'isPII' || $value === null || $value === '') {
                    continue;
                }
                $custom[(string) $key] = (string) $value;
            }
        }

        // Normalize aliases used by Stripe fulfillment.
        if (isset($custom['orderId']) && ! isset($custom['order_reference'])) {
            $custom['order_reference'] = $custom['orderId'];
        }

        return $custom;
    }
}
