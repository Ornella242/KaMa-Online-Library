<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\AudiobookRequest;
use App\Models\Book;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewAudiobookRequestNotification;
use App\Services\Audiobook\AudiobookAnalyzer;
use App\Services\Audiobook\ElevenLabsService;
use App\Services\PaymentFulfillmentService;
use App\Services\PawaPayService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class AudiobookController extends Controller
{
    public function __construct(
        private AudiobookAnalyzer $analyzer,
        private ElevenLabsService $elevenLabsService
    ) {
    }

    public function index(Book $book)
    {
        abort_unless($book->user_id === auth()->id(), 403);

        abort_unless(
            $book->status === Book::STATUS_PUBLISHED,
            404
        );

        $analysis = $this->analyzer->analyze(
            $book->file_path
        );

        $statistics = $analysis['statistics'] ?? [];

        $estimatedCost = $statistics['elevenlabs_cost'] ?? 0;

        $kamaFee = round(
            ((float) $book->price) * 0.20,
            2
        );

        $totalAmount = round(
            $estimatedCost + $kamaFee,
            2
        );

        $audiobookRequest = $book->audiobookRequests()
            ->where('author_id', auth()->id())
            ->latest()
            ->first();

        $voicesResponse = $this->elevenLabsService->getVoices();

        $voices = $voicesResponse['voices'] ?? [];

        return view('writer.books.audiobook', compact(
            'book',
            'statistics',
            'voices',
            'audiobookRequest',
            'estimatedCost',
            'kamaFee',
            'totalAmount'
        ));
    }

    public function store(Request $request, Book $book)
    {
        abort_unless(
            $book->user_id === auth()->id(),
            403
        );

        abort_unless(
            $book->status === Book::STATUS_PUBLISHED,
            404
        );

        $validated = $request->validate([
            'voice_id' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'voice_id.required' => 'Veuillez sélectionner une voix.',
        ]);

        $voicesResponse = $this->elevenLabsService->getVoices();
        $voices = $voicesResponse['voices'] ?? [];

        $selectedVoice = collect($voices)->firstWhere(
            'voice_id',
            $validated['voice_id']
        );

        if (!$selectedVoice) {
            return back()
                ->withErrors([
                    'voice_id' => 'La voix sélectionnée n’est pas disponible.',
                ])
                ->withInput();
        }

        $analysis = $this->analyzer->analyze(
            $book->file_path
        );

        $statistics = $analysis['statistics'] ?? [];

        $characters = (int) ($statistics['characters'] ?? 0);
        $words = (int) ($statistics['words'] ?? 0);
        $elevenLabsCost = (float) ($statistics['elevenlabs_cost'] ?? 0);

        $kamaFee = round(
            ((float) $book->price) * 0.20,
            2
        );

        $totalAmount = round(
            $elevenLabsCost + $kamaFee,
            2
        );

        $existingRequest = $book->audiobookRequests()
            ->where('author_id', auth()->id())
            ->whereIn('status', [
                AudiobookRequest::STATUS_PENDING_PAYMENT,
                AudiobookRequest::STATUS_PAID,
                AudiobookRequest::STATUS_QUEUED,
                AudiobookRequest::STATUS_GENERATING,
                AudiobookRequest::STATUS_ASSEMBLING,
            ])
            ->latest()
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === AudiobookRequest::STATUS_PENDING_PAYMENT) {
                $existingRequest->update([
                    'voice_id' => $selectedVoice['voice_id'],
                    'characters' => $characters,
                    'words' => $words,
                    'elevenlabs_cost' => $elevenLabsCost,
                    'kama_fee' => $kamaFee,
                    'total_amount' => $totalAmount,
                ]);

                return redirect()
                    ->route('writer.books.audiobook.payment', $book)
                    ->with('success', 'Votre demande d’audiobook a été mise à jour. Procédez maintenant au paiement pour la valider.');
            }

            return redirect()
                ->route('writer.books.audiobook', $book)
                ->with('info', 'Une demande d’audiobook est déjà en cours de traitement pour ce livre.');
        }

        $book->audiobookRequests()->create([
            'author_id' => auth()->id(),
            'voice_id' => $selectedVoice['voice_id'],
            'characters' => $characters,
            'words' => $words,
            'elevenlabs_cost' => $elevenLabsCost,
            'kama_fee' => $kamaFee,
            'total_amount' => $totalAmount,
            'status' => AudiobookRequest::STATUS_PENDING_PAYMENT,
        ]);

        return redirect()
            ->route('writer.books.audiobook.payment', $book)
            ->with('success', 'Votre demande d’audiobook a été enregistrée. Procédez maintenant au paiement pour finaliser votre commande.');
    }

    /**
     * Page de paiement de l'audiobook.
     */
    public function payment(Book $book, StripeService $stripe, PawaPayService $pawaPay)
    {
        abort_unless($book->user_id === auth()->id(), 403);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        $audiobookRequest = $book->audiobookRequests()
            ->where('author_id', auth()->id())
            ->where('status', AudiobookRequest::STATUS_PENDING_PAYMENT)
            ->latest()
            ->firstOrFail();

        $voicesResponse = $this->elevenLabsService->getVoices();
        $voices = $voicesResponse['voices'] ?? [];
        $selectedVoice = collect($voices)->firstWhere('voice_id', $audiobookRequest->voice_id);
        $voiceName = $selectedVoice['name'] ?? $audiobookRequest->voice_id;

        $author = auth()->user();
        $author->loadMissing('country');

        $momoCountry = $pawaPay->resolveCountry($author->country?->code);
        $momoQuote = ($pawaPay->isConfigured() && $momoCountry)
            ? $pawaPay->convertFromEur(
                (float) $audiobookRequest->total_amount,
                $momoCountry['currency'],
                $momoCountry['decimals']
            )
            : null;

        return view('writer.books.audiobook-payment', [
            'book' => $book,
            'audiobookRequest' => $audiobookRequest,
            'voiceName' => $voiceName,
            'stripeConfigured' => $stripe->isConfigured(),
            'stripeTestMode' => $stripe->isTestMode(),
            'pawaPayConfigured' => $pawaPay->isConfigured(),
            'pawaPaySandbox' => $pawaPay->isSandbox(),
            'momoCountry' => $momoCountry,
            'momoQuote' => $momoQuote,
            'momoAvailable' => $momoCountry !== null,
        ]);
    }

    /**
     * Préparation du paiement en ligne (Stripe ou PawaPay).
     */
    public function pay(Request $request, Book $book, StripeService $stripe, PawaPayService $pawaPay)
    {
        abort_unless($book->user_id === auth()->id(), 403);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        $audiobookRequest = $book->audiobookRequests()
            ->where('author_id', auth()->id())
            ->where('status', AudiobookRequest::STATUS_PENDING_PAYMENT)
            ->latest()
            ->firstOrFail();

        $method = $request->input('method', 'card');

        if ($method === 'momo' || $method === 'pawapay') {
            abort_unless($pawaPay->isConfigured(), 503, 'PawaPay n’est pas encore configuré.');

            $author = auth()->user();
            $author->loadMissing('country');
            $country = $pawaPay->resolveCountry($author->country?->code);

            if (! $country) {
                return response()->json([
                    'message' => 'Le Mobile Money n’est pas disponible pour votre pays (' . ($author->country?->name ?? 'inconnu') . '). Veuillez payer par carte bancaire.',
                ], 422);
            }

            try {
                $quote = $pawaPay->convertFromEur(
                    (float) $audiobookRequest->total_amount,
                    $country['currency'],
                    $country['decimals']
                );
            } catch (Throwable $exception) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            $depositId = (string) Str::uuid();

            try {
                $session = $pawaPay->createPaymentPage(
                    $quote['amount'],
                    $quote['currency'],
                    $country['iso3'],
                    route('writer.books.audiobook.success', $book).'?deposit_id='.$depositId,
                    'Audiobook KaMa '.$book->id,
                    [
                        'type' => 'audiobook',
                        'audiobook_request_id' => (string) $audiobookRequest->id,
                        'book_id' => (string) $book->id,
                        'user_id' => (string) auth()->id(),
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
                'deposit_id' => $session['deposit_id'],
                'payment' => [
                    'reference' => 'AUDIO-'.$audiobookRequest->id,
                    'amount' => $quote['numeric'],
                    'currency' => $quote['currency'],
                    'amount_eur' => (float) $audiobookRequest->total_amount,
                    'label' => $quote['label'],
                ],
            ]);
        }

        // Par défaut: Stripe (Carte bancaire)
        abort_unless($stripe->isConfigured(), 503, 'Stripe n’est pas encore configuré.');

        try {
            $checkout = $stripe->createCheckout(
                (float) $audiobookRequest->total_amount,
                [
                    'name' => trim(auth()->user()->firstname . ' ' . auth()->user()->lastname),
                    'email' => auth()->user()->email,
                    'custom' => [
                        'type' => 'audiobook',
                        'audiobook_request_id' => (string) $audiobookRequest->id,
                        'book_id' => (string) $book->id,
                        'user_id' => (string) auth()->id(),
                    ],
                ],
                route('writer.books.audiobook.success', $book) . '?session_id={CHECKOUT_SESSION_ID}',
                route('writer.books.audiobook.payment', $book),
                'Commande Audiobook KaMa — ' . $book->title
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
                'reference' => 'AUDIO-' . $audiobookRequest->id,
                'amount' => (float) $audiobookRequest->total_amount,
                'currency' => 'EUR',
            ],
        ]);
    }

    /**
     * Confirmation de paiement suite au retour Stripe / PawaPay.
     */
    public function success(
        Request $request,
        Book $book,
        StripeService $stripe,
        PawaPayService $pawaPay,
        PaymentFulfillmentService $fulfillment
    ) {
        abort_unless($book->user_id === auth()->id(), 403);

        $sessionId = $request->query('session_id');
        $depositId = $request->query('deposit_id');

        if ($sessionId && $stripe->isConfigured()) {
            $session = $stripe->retrieveCheckout($sessionId);
            $paymentStatus = strtolower((string) ($session['payment_status'] ?? ''));

            if ($paymentStatus === 'paid') {
                $custom = (array) ($session['metadata'] ?? []);
                $transactionId = (string) (
                    ($session['payment_intent'] ?? null)
                    ?: ($session['id'] ?? null)
                    ?: ('stripe-' . uniqid())
                );

                $fulfillment->handlePaidCheckout($custom, $transactionId, 'stripe');
            }
        } elseif ($depositId && $pawaPay->isConfigured()) {
            $check = $pawaPay->checkDeposit($depositId);
            $depositStatus = strtoupper((string) data_get($check, 'data.status', ''));

            if ($check['status'] === 'FOUND' && $depositStatus === 'COMPLETED') {
                $custom = (array) data_get($check, 'data.metadata', []);
                if (empty($custom['type'])) {
                    $custom['type'] = 'audiobook';
                    $custom['book_id'] = (string) $book->id;
                }
                $fulfillment->handlePaidCheckout($custom, $depositId, 'pawapay');
            }
        }

        return redirect()
            ->route('writer.books.audiobook', $book)
            ->with('success', 'Paiement confirmé avec succès ! Votre commande d’audiobook a été transmise à KaMa.');
    }
}