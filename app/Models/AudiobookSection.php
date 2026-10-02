<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AudiobookSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'audiobook_id',
        'position',
        'type',
        'number',
        'title',
        'text',
        'narration_text',
        'characters',
        'words',
        'start_page',
        'end_page',
        'detection_method',
        'confidence',
        'status',
        'audio_path',
        'duration_seconds',
        'error_message',
    ];

    protected $casts = [
        'position' => 'integer',
        'characters' => 'integer',
        'words' => 'integer',
        'start_page' => 'integer',
        'end_page' => 'integer',
        'confidence' => 'decimal:4',
        'duration_seconds' => 'integer',
    ];

    /**
     * Audiobook auquel appartient cette section.
     */
    public function audiobook(): BelongsTo
    {
        return $this->belongsTo(Audiobook::class);
    }

    /**
     * Chunks audio de cette section.
     */
    public function chunks(): HasMany
    {
        return $this->hasMany(AudiobookChunk::class)
            ->orderBy('position');
    }

    /**
     * Vérifie si la section est terminée.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}