<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\AudiobookRequest;
use App\Models\Book;
use App\Models\Payment;
use App\Services\Audiobook\AudiobookAnalyzer;
use App\Services\Audiobook\ElevenLabsService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewAudiobookRequestNotification;

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
        /*
        * 1. Vérifier que le livre appartient bien à l'auteur connecté
        */
        abort_unless(
            $book->user_id === auth()->id(),
            403
        );

        /*
        * 2. Le livre doit être publié
        */
        abort_unless(
            $book->status === Book::STATUS_PUBLISHED,
            404
        );

        /*
        * 3. Valider la voix sélectionnée
        */
        $validated = $request->validate([
            'voice_id' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'voice_id.required' => 'Veuillez sélectionner une voix.',
        ]);

        /*
        * 4. Récupérer les voix ElevenLabs
        */
        $voicesResponse = $this->elevenLabsService->getVoices();

        $voices = $voicesResponse['voices'] ?? [];

        /*
        * 5. Vérifier que la voix sélectionnée existe
        */
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

        /*
        * 6. Refaire l'analyse du livre côté serveur
        */
        $analysis = $this->analyzer->analyze(
            $book->file_path
        );

        $statistics = $analysis['statistics'] ?? [];

        /*
        * 7. Statistiques du livre
        */
        $characters = (int) (
            $statistics['characters'] ?? 0
        );

        $words = (int) (
            $statistics['words'] ?? 0
        );

        /*
        * 8. Coût estimé ElevenLabs
        */
        $elevenLabsCost = (float) (
            $statistics['elevenlabs_cost'] ?? 0
        );

        /*
        * 9. Frais KaMa
        *
        * 20 % du prix de l'ebook.
        */
        $kamaFee = round(
            ((float) $book->price) * 0.20,
            2
        );

        /*
        * 10. Total à payer
        */
        $totalAmount = round(
            $elevenLabsCost + $kamaFee,
            2
        );

        /*
        * 11. Vérifier si une demande active existe déjà
        */
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
            return redirect()
                ->route(
                    'writer.books.audiobook',
                    $book
                )
                ->with(
                    'info',
                    'Une demande d’audiobook existe déjà pour ce livre.'
                );
        }

        /*
        * 12. Créer la demande
        */
        $audiobookRequest = $book->audiobookRequests()->create([
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
            ->route(
                'writer.books.audiobook',
                $book
            )
            ->with(
                'success',
                'Votre demande d’audiobook a été enregistrée. Vous pouvez maintenant procéder à la commande.'
            );
    }

    /**
     * Simulation du paiement audiobook.
     * Pour le moment, le paiement est directement considéré
     * comme réussi.
     */
    public function pay(Book $book)
    {
       
        abort_unless(
            $book->user_id === auth()->id(),
            403
        );

        abort_unless(
            $book->status === Book::STATUS_PUBLISHED,
            404
        );

        $audiobookRequest = $book->audiobookRequests()
            ->where('author_id', auth()->id())
            ->latest()
            ->firstOrFail();

        abort_unless(
            $audiobookRequest->status === AudiobookRequest::STATUS_PENDING_PAYMENT,
            409,
            'Cette demande d’audiobook ne peut plus être payée.'
        );

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,

            'reference' => 'KAMA-AUDIO-' . strtoupper(
                str()->random(12)
            ),

            'amount' => $audiobookRequest->total_amount,
            'currency' => 'EUR',

            'status' => 'success',
            'type' => 'audiobook',

            'payment_method' => 'simulation',
            'transaction_id' => null,
        ]);

       $audiobookRequest->update([
            'status' => AudiobookRequest::STATUS_PAID,
            'payment_id' => $payment->id,
        ]);

        $this->notifyAudiobookAdmins($audiobookRequest);

        return redirect()
            ->route(
                'writer.books.audiobook',
                $book
            )
            ->with(
                'success',
                'Paiement confirmé. Votre demande d’audiobook a été transmise à KaMa.'
            );
    }

    private function notifyAudiobookAdmins(
        AudiobookRequest $audiobookRequest
        ): void {
        /*
        * Admin principal KaMa
        * OU
        * administrateur possédant la permission
        * books.audio.generate
        */
        $admins = User::query()
            ->whereHas(
                'role',
                fn ($query) => $query->where('name', 'admin')
            )
            ->where(function ($query) {
                $query
                    ->where('is_main_admin', true)
                    ->orWhereHas(
                        'adminRoles.permissions',
                        fn ($permissionQuery) => $permissionQuery->where(
                            'name',
                            'books.audio.generate'
                        )
                    );
            })
            ->get();

        $admins->each(function (User $admin) use ($audiobookRequest) {
            $admin->notify(
                new NewAudiobookRequestNotification(
                    $audiobookRequest
                )
            );
        });
    }
}