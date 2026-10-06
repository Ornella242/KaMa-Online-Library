<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AudiobookRequest extends Model
{
    /*
    | Statuts
    */

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_GENERATING = 'generating';
    public const STATUS_ASSEMBLING = 'assembling';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING_PAYMENT,
        self::STATUS_PAID,
        self::STATUS_QUEUED,
        self::STATUS_GENERATING,
        self::STATUS_ASSEMBLING,
        self::STATUS_COMPLETED,
        self::STATUS_FAILED,
        self::STATUS_CANCELLED,
    ];


    protected $fillable = [
        'book_id',
        'author_id',
        'voice_id',
        'characters',
        'words',
        'elevenlabs_cost',
        'kama_fee',
        'total_amount',
        'status',
        'payment_id',
        'audiobook_id',
        'published_book_id',
    ];

    /**
     * Ebook source de la demande.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Auteur qui a effectué la demande.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Paiement associé à la demande.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Audiobook généré à partir de cette demande.
     */
    public function audiobook(): BelongsTo
    {
        return $this->belongsTo(Audiobook::class);
    }

    public function publishedBook(): BelongsTo
    {
        return $this->belongsTo(
            Book::class,
            'published_book_id'
        );
    }

    public function isPendingPayment(): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canBePaid(): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT;
    }

    public function canBeGenerated(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

   
}