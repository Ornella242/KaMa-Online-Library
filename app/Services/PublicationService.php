<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PublicationService
{
    /**
     * Retourne la limite des soumissions gratuites.
     */
    public function freeSubmissionLimit(): int
    {
        return (int) Setting::getValue('free_submission_limit', 1000);
    }

    /**
     * Nombre de livres déjà soumis.
     */
    public function submittedBooksCount(): int
    {
        
        return Book::query()->where('status', '!=', 'draft')->count();
    }

    /**
     * Indique si le paiement est requis.
     */
    public function shouldRequirePayment(): bool
    {
        return $this->submittedBooksCount() >= $this->freeSubmissionLimit();
    }

    public function submit(Book $book): void
    {
        $book->update([
            'status' => 'waiting_review'
        ]);
    }

    public function submitWithoutPayment(Book $book,PublicationService $publicationService)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === 'draft', 409);
        abort_if(
            $publicationService->shouldRequirePayment(),
            403,
            'Le paiement est désormais obligatoire.'
        );

        DB::transaction(function () use ($book) {
            $lockedBook = Book::lockForUpdate()->findOrFail($book->id);
            abort_unless($lockedBook->status === 'draft',409);
            $lockedBook->update([
                'status'=>'waiting_review'
            ]);
        });

        $this->notifyAdmins($book);

        return response()->json([
            'redirect'=>$this->publicationSuccessRedirect(),
            'message'=>'Votre livre a été soumis avec succès.'
        ]);
    }
    
}