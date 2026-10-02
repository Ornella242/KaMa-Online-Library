<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudiobookChunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'audiobook_section_id',
        'position',
        'text',
        'characters',
        'character_cost',
        'status',
        'audio_path',
        'request_id',
        'trace_id',
        'duration_seconds',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'position' => 'integer',
        'characters' => 'integer',
        'character_cost' => 'integer',
        'duration_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Section à laquelle appartient le chunk.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(
            AudiobookSection::class,
            'audiobook_section_id'
        );
    }

    /**
     * Vérifie si le chunk est terminé.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}