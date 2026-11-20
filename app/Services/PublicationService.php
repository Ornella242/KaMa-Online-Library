<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Setting;

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
            'status' => 'waiting_review',
        ]);
    }
}
