<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Country;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\LemonSqueezyFulfillmentService;
use App\Services\LemonSqueezyService;
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
                'reference' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => Auth::id(),
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'],
                'country_id' => $validated['country_id'],
                'city' => $validated['city'],
                'amount' => $amount,
                'currency' => 'USD',
                'status' => Order::STATUS_PENDING,
                'payment_method' => 'lemonsqueezy',
            ]);

            foreach ($orderItems as $line) {
                $order->items()->create($line);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.payment', $order);
    }

    public function payment(Order $order, LemonSqueezyService $lemonSqueezy)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');

        $order->load(['items.book', 'country']);

        return view('checkout.payment', [
            'order' => $order,
            'lemonConfigured' => $lemonSqueezy->isConfigured(),
            'lemonTestMode' => $lemonSqueezy->isTestMode(),
        ]);
    }

    public function preparePayment(Order $order, LemonSqueezyService $lemonSqueezy)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');
        abort_unless($lemonSqueezy->isConfigured(), 503, 'Lemon Squeezy n’est pas encore configuré.');
        abort_unless(
            strtoupper((string) $order->currency) === 'USD',
            409,
            'Le paiement Lemon Squeezy est configuré en USD.'
        );

        try {
            $checkout = $lemonSqueezy->createCheckout(
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
                route('checkout.success', $order),
                'Commande KaMa '.$order->reference
            );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        return response()->json([
            'checkout_url' => $checkout['url'],
            'checkout_id' => $checkout['id'],
            'payment' => [
                'reference' => $order->reference,
                'amount' => (float) $order->amount,
                'currency' => 'USD',
            ],
        ]);
    }

    public function verify(Order $order, LemonSqueezyFulfillmentService $fulfillment)
    {
        if ($order->status === Order::STATUS_PAID) {
            return response()->json([
                'redirect' => route('checkout.success', $order),
                'message' => 'Paiement confirmé.',
            ]);
        }

        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande ne peut plus être payée.');

        // Soft-confirm while waiting for webhook: refresh status only.
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

    public function success(Order $order)
    {
        $order->load(['items.book', 'country']);

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
