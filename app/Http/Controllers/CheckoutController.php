<?php

namespace App\Http\Controllers;

use App\Mail\OrderPurchaseMail;
use App\Models\Book;
use App\Models\Country;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Kkiapay\Kkiapay;
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
                'currency' => 'XOF',
                'status' => Order::STATUS_PENDING,
                'payment_method' => 'kkiapay',
            ]);

            foreach ($orderItems as $line) {
                $order->items()->create($line);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.payment', $order);
    }

    public function payment(Order $order)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');

        $order->load(['items.book', 'country']);

        $kkiapayConfigured = $this->kkiapayIsConfigured();
        $kkiapayPublicKey = config('services.kkiapay.public_key');
        $kkiapaySandbox = (bool) config('services.kkiapay.sandbox', true);

        return view('checkout.payment', compact(
            'order',
            'kkiapayConfigured',
            'kkiapayPublicKey',
            'kkiapaySandbox'
        ));
    }

    public function preparePayment(Order $order)
    {
        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande n’est plus en attente de paiement.');
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');
        abort_unless(
            strtoupper($order->currency) === 'XOF',
            409,
            'KKiaPay exige un paiement en XOF.'
        );

        return response()->json([
            'payment' => [
                'reference' => $order->reference,
                'amount' => (float) $order->amount,
                'currency' => $order->currency,
            ],
            'customer' => [
                'name' => $order->fullName(),
                'email' => $order->email,
                'phone' => $order->phone,
            ],
        ]);
    }

    public function verify(Request $request, Order $order)
    {
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');

        $validated = $request->validate([
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        if ($order->status === Order::STATUS_PAID && $order->transaction_id === $validated['transaction_id']) {
            return response()->json([
                'redirect' => route('checkout.success', $order),
                'message' => 'Paiement déjà confirmé.',
            ]);
        }

        abort_unless($order->status === Order::STATUS_PENDING, 409, 'Cette commande ne peut plus être payée.');
        abort_if(
            Order::query()
                ->where('transaction_id', $validated['transaction_id'])
                ->where('id', '!=', $order->id)
                ->exists(),
            409,
            'Cette transaction a déjà été utilisée.'
        );

        try {
            $kkiapay = new Kkiapay(
                config('services.kkiapay.public_key'),
                config('services.kkiapay.private_key'),
                config('services.kkiapay.secret'),
                (bool) config('services.kkiapay.sandbox', true)
            );
            $verification = $kkiapay->verifyTransaction($validated['transaction_id']);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'La vérification KKiaPay est momentanément indisponible. Réessayez sans effectuer un nouveau paiement.',
            ], 502);
        }

        abort_unless(
            is_object($verification)
                && strtoupper((string) ($verification->status ?? '')) === 'SUCCESS',
            422,
            'KKiaPay n’a pas confirmé cette transaction.'
        );
        abort_unless(
            abs((float) ($verification->amount ?? -1) - (float) $order->amount) < 0.01,
            422,
            'Le montant confirmé par KKiaPay ne correspond pas à la commande.'
        );

        $partnerId = trim((string) ($verification->partnerId ?? ''));
        abort_unless(
            $partnerId === '' || hash_equals($order->reference, $partnerId),
            422,
            'La référence KKiaPay ne correspond pas à cette commande.'
        );

        DB::transaction(function () use ($order, $validated, $verification) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            abort_unless($lockedOrder->status === Order::STATUS_PENDING, 409, 'Cette commande a déjà été traitée.');
            abort_if(
                Order::query()
                    ->where('transaction_id', $validated['transaction_id'])
                    ->where('id', '!=', $lockedOrder->id)
                    ->exists(),
                409,
                'Cette transaction a déjà été utilisée.'
            );

            $method = strtolower((string) ($verification->source ?? 'kkiapay'));

            $lockedOrder->update([
                'status' => Order::STATUS_PAID,
                'payment_method' => $method,
                'transaction_id' => $validated['transaction_id'],
            ]);

            $lockedOrder->load('items');

            foreach ($lockedOrder->items as $item) {
                Payment::query()->updateOrCreate(
                    [
                        'order_id' => $lockedOrder->id,
                        'book_id' => $item->book_id,
                        'type' => 'purchase',
                    ],
                    [
                        'user_id' => $lockedOrder->user_id,
                        'guest_email' => $lockedOrder->user_id ? null : $lockedOrder->email,
                        'reference' => $lockedOrder->reference . '-' . $item->book_id,
                        'amount' => $item->unit_price,
                        'currency' => $lockedOrder->currency,
                        'status' => 'success',
                        'payment_method' => $method,
                        'transaction_id' => $validated['transaction_id'] . '-' . $item->book_id,
                    ]
                );
            }
        });

        $order->refresh()->load('items.book');
        $this->sendPurchaseEmail($order);

        return response()->json([
            'redirect' => route('checkout.success', $order),
            'message' => 'Paiement confirmé. Vos livres ont été envoyés par email.',
        ]);
    }

    public function success(Order $order)
    {
        abort_unless($order->status === Order::STATUS_PAID, 404);

        $order->load(['items.book', 'country']);

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

    private function sendPurchaseEmail(Order $order): void
    {
        try {
            Mail::to($order->email)->send(new OrderPurchaseMail($order));
        } catch (Throwable $exception) {
            report($exception);
        }
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

    private function kkiapayIsConfigured(): bool
    {
        return filled(config('services.kkiapay.public_key'))
            && filled(config('services.kkiapay.private_key'))
            && filled(config('services.kkiapay.secret'));
    }
}
