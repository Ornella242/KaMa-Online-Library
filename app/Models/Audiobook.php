<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Audiobook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'status',
        'voice_id',
        'model',
        'total_characters',
        'generated_characters',
        'total_chunks',
        'completed_chunks',
        'estimated_cost',
        'actual_cost',
        'final_audio_path',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'total_characters' => 'integer',
        'generated_characters' => 'integer',
        'total_chunks' => 'integer',
        'completed_chunks' => 'integer',
        'estimated_cost' => 'decimal:4',
        'actual_cost' => 'decimal:4',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Livre auquel appartient l'audiobook.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Sections de l'audiobook.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(AudiobookSection::class)
            ->orderBy('position');
    }

    /**
     * Vérifie si l'audiobook est terminé.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifie si l'audiobook est actuellement en génération.
     */
    public function isGenerating(): bool
    {
        return in_array($this->status, [
            'queued',
            'generating',
        ], true);
    }

    public function chunks(): HasManyThrough
{
    return $this->hasManyThrough(
        AudiobookChunk::class,
        AudiobookSection::class
    );
}
}