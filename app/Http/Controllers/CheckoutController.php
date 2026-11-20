<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Country;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\PaymentFulfillmentService;
use App\Services\PawaPayService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function show()
    {
        $items = $this->cart->items();

        if ($items === []) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $books = $this->cart->books();
        $total = $this->cart->total();
        $countries = Country::query()->orderBy('name')->get();
        $user = Auth::user();

        return view('checkout.show', compact('items', 'books', 'total', 'countries', 'user'));
    }

    public function store(Request $request)
    {
        $items = $this->cart->items();

        if ($items === []) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'country_id' => ['required', 'exists:countries,id'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $books = Book::query()
            ->published()
            ->whereIn('id', array_keys($items))
            ->get()
            ->keyBy('id');

        if ($books->isEmpty()) {
            $this->cart->clear();

            return redirect()
                ->route('cart.index')
                ->with('error', 'Les livres de votre panier ne sont plus disponibles.');
        }

        $orderItems = [];
        $amount = 0.0;

        foreach ($items as $bookId => $item) {
            $book = $books->get($bookId);
            if (! $book) {
                continue;
            }

            if (Auth::check() && Auth::id() === $book->user_id) {
                continue;
            }

            if (Auth::check() && $this->userOwnsBook(Auth::id(), $book->id)) {
                continue;
            }

            $price = (float) $book->price;
            $amount += $price;
            $orderItems[] = [
                'book_id' => $book->id,
                'title' => $book->title,
                'book_type' => $book->type,
                'unit_price' => $price,
            ];
        }

        if ($orderItems === [] || $amount <= 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Aucun livre achetable dans votre panier.');
        }

        $order = DB::transaction(function () use ($validated, $orderItems, $amount) {
            $order = Order::query()->create([
                'reference' => 'ORD-'.strtoupper(Str::random(10)),
                'user_id' => Auth::id(),
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'],
                'country_id' => $validated['country_id'],
                'city' => $validated['city'],
                'amount' => $amount,
                'currency' => 'EUR',
                'status' => Order::STATUS_PENDING,
                'payment_method' => 'stripe',
            ]);

            foreach ($orderItems as $line) {
                $order->items()->create($line);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.payment', $order);
    }

    public function payment(Order $order, StripeService $stripe, PawaPayService $pawaPay)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');

        $order->load(['items.book', 'country']);

        $momoCountry = $pawaPay->resolveCountry($order->country?->code);
        $momoQuote = $momoCountry
            ? $pawaPay->convertFromEur(
                (float) $order->amount,
                $momoCountry['currency'],
                $momoCountry['decimals']
            )
            : null;

        return view('checkout.payment', [
            'order' => $order,
            'stripeConfigured' => $stripe->isConfigured(),
            'stripeTestMode' => $stripe->isTestMode(),
            'pawaPayConfigured' => $pawaPay->isConfigured(),
            'pawaPaySandbox' => $pawaPay->isSandbox(),
            'momoCountry' => $momoCountry,
            'momoQuote' => $momoQuote,
            'momoAvailable' => $momoCountry !== null,
        ]);
    }

    public function preparePayment(Request $request, Order $order, StripeService $stripe, PawaPayService $pawaPay)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');

        $method = $request->input('method', 'card');

        if ($method === 'momo' || $method === 'pawapay') {
            return $this->preparePawaPay($order, $pawaPay);
        }

        return $this->prepareStripe($order, $stripe);
    }

    private function prepareStripe(Order $order, StripeService $stripe)
    {
        abort_unless($stripe->isConfigured(), 503, 'Stripe n’est pas encore configuré.');
        abort_unless(
            strtoupper((string) $order->currency) === 'EUR',
            409,
            'Le paiement Stripe est configuré en EUR.'
        );

        try {
            $checkout = $stripe->createCheckout(
                (float) $order->amount,
                [
                    'name' => $order->fullName(),
                    'email' => $order->email,
                    'custom' => [
                        'type' => 'order',
                        'order_id' => (string) $order->id,
                        'order_reference' => $order->reference,
                    ],
                ],
                route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
                route('checkout.payment', $order),
                'Commande KaMa '.$order->reference
            );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        $order->update(['payment_method' => 'stripe']);

        return response()->json([
            'checkout_url' => $checkout['url'],
            'checkout_id' => $checkout['id'],
            'payment' => [
                'reference' => $order->reference,
                'amount' => (float) $order->amount,
                'currency' => 'EUR',
            ],
        ]);
    }

    private function preparePawaPay(Order $order, PawaPayService $pawaPay)
    {
        abort_unless($pawaPay->isConfigured(), 503, 'PawaPay n’est pas encore configuré.');

        $order->loadMissing('country');
        $country = $pawaPay->resolveCountry($order->country?->code);

        if (! $country) {
            return response()->json([
                'message' => 'Le Mobile Money n’est pas disponible pour le pays de votre commande ('.$order->country?->name.'). Choisissez la carte bancaire.',
            ], 422);
        }

        try {
            $quote = $pawaPay->convertFromEur(
                (float) $order->amount,
                $country['currency'],
                $country['decimals']
            );
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        $depositId = (string) Str::uuid();

        $order->update([
            'payment_method' => 'pawapay',
            'transaction_id' => $depositId,
        ]);

        try {
            $session = $pawaPay->createPaymentPage(
                $quote['amount'],
                $quote['currency'],
                $country['iso3'],
                route('checkout.success', $order).'?depositId='.$depositId,
                'Commande KaMa '.$order->reference,
                [
                    'type' => 'order',
                    'orderId' => $order->reference,
                    'order_id' => (string) $order->id,
                    'order_reference' => $order->reference,
                ],
                $depositId
            );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        return response()->json([
            'checkout_url' => $session['redirect_url'],
            'checkout_id' => $session['deposit_id'],
            'payment' => [
                'reference' => $order->reference,
                'amount' => $quote['numeric'],
                'currency' => $quote['currency'],
                'amount_eur' => (float) $order->amount,
                'label' => $quote['label'],
            ],
        ]);
    }

    public function verify(Order $order, PaymentFulfillmentService $fulfillment, PawaPayService $pawaPay)
    {
        if ($order->status === Order::STATUS_PAID) {
            return response()->json([
                'redirect' => route('checkout.success', $order),
                'message' => 'Paiement confirmé.',
            ]);
        }

        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande ne peut plus être payée.');

        // Soft-poll PawaPay if a deposit was started.
        if (
            $order->payment_method === 'pawapay'
            && filled($order->transaction_id)
            && $pawaPay->isConfigured()
        ) {
            $check = $pawaPay->checkDeposit((string) $order->transaction_id);
            $depositStatus = strtoupper((string) data_get($check, 'data.status', ''));

            if ($check['status'] === 'FOUND' && $depositStatus === 'COMPLETED') {
                try {
                    $fulfillment->handlePaidCheckout([
                        'type' => 'order',
                        'order_id' => (string) $order->id,
                        'order_reference' => $order->reference,
                    ], (string) $order->transaction_id, 'pawapay');
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        }

        $order->refresh();
        if ($order->status === Order::STATUS_PAID) {
            return response()->json([
                'redirect' => route('checkout.success', $order),
                'message' => 'Paiement confirmé.',
            ]);
        }

        return response()->json([
            'message' => 'Paiement en cours de confirmation…',
            'pending' => true,
        ], 202);
    }

    public function success(Order $order, PawaPayService $pawaPay, PaymentFulfillmentService $fulfillment)
    {
        $order->load(['items.book', 'country']);

        if (
            $order->status === Order::STATUS_PENDING
            && $order->payment_method === 'pawapay'
            && filled($order->transaction_id)
            && $pawaPay->isConfigured()
        ) {
            $check = $pawaPay->checkDeposit((string) $order->transaction_id);
            $depositStatus = strtoupper((string) data_get($check, 'data.status', ''));

            if ($check['status'] === 'FOUND' && $depositStatus === 'COMPLETED') {
                try {
                    $fulfillment->handlePaidCheckout([
                        'type' => 'order',
                        'order_id' => (string) $order->id,
                        'order_reference' => $order->reference,
                    ], (string) $order->transaction_id, 'pawapay');
                    $order->refresh();
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        }

        if ($order->status !== Order::STATUS_PAID) {
            return view('checkout.pending', compact('order'));
        }

        $downloads = $order->items
            ->filter(fn ($item) => $item->book && filled($item->book->file_path))
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => $item->book_type === 'audio' ? 'Livre audio' : 'Ebook',
                'url' => URL::temporarySignedRoute(
                    'checkout.download',
                    now()->addDays(14),
                    [
                        'order' => $order->reference,
                        'book' => $item->book_id,
                    ]
                ),
            ]);

        return view('checkout.success', compact('order', 'downloads'));
    }

    public function download(Request $request, Order $order, Book $book)
    {
        abort_unless($order->status === Order::STATUS_PAID, 404);
        abort_unless(
            $order->items()->where('book_id', $book->id)->exists(),
            404
        );
        abort_unless(filled($book->file_path) && Storage::disk('local')->exists($book->file_path), 404);

        $filename = $book->original_file_name ?: basename($book->file_path);

        return Storage::disk('local')->download($book->file_path, $filename);
    }

    private function userOwnsBook(int $userId, int $bookId): bool
    {
        return Payment::query()
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->exists();
    }
}
