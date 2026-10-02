<?php

namespace App\Services\Audiobook;

use App\Models\Audiobook;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use App\Jobs\GenerateAudiobookChunkJob;


class AudiobookGenerationService
{
    public function __construct(
        private AudiobookAnalyzer $analyzer,
        private AudiobookTextChunker $chunker,
        private ElevenLabsService $elevenLabs,
    ) {
    }

    /**
     * Prépare l'audiobook sans générer d'audio.
     *
     * Cette étape :
     * - analyse le PDF
     * - détecte les sections
     * - prépare les textes
     * - découpe les textes en chunks
     * - calcule les statistiques
     * - estime le coût
     *
     * Aucun crédit ElevenLabs n'est consommé.
     */
    public function prepare(
        Book $book,
        ?string $voiceId = null
    ): Audiobook {
        if ($book->type !== 'ebook') {
            throw new RuntimeException(
                'Seuls les ebooks peuvent être transformés en audiobook.'
            );
        }

        if (!$book->file_path) {
            throw new RuntimeException(
                'Aucun fichier PDF n’est associé à ce livre.'
            );
        }

        $existing = Audiobook::where(
            'book_id',
            $book->id
        )->first();

        if ($existing) {
            throw new RuntimeException(
                'Un audiobook existe déjà pour ce livre.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Analyse du PDF
        |--------------------------------------------------------------------------
        */

        $analysis = $this->analyzer->analyze(
            $book->file_path
        );

        $sections = $analysis['sections'] ?? [];

        if (empty($sections)) {
            throw new RuntimeException(
                'Aucune section n’a été détectée dans le PDF.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Voix
        |--------------------------------------------------------------------------
        */

        $voiceId = $voiceId
            ?? config('services.elevenlabs.voice_id');

        if (!$voiceId) {
            throw new RuntimeException(
                'Aucune voix ElevenLabs n’est configurée.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Modèle
        |--------------------------------------------------------------------------
        */

        $model = config(
            'services.elevenlabs.model',
            'eleven_v3'
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Préparation des sections et chunks
        |--------------------------------------------------------------------------
        */

        $preparedSections = [];

        $totalCharacters = 0;
        $totalChunks = 0;

        foreach ($sections as $section) {

            $narrationText =
                $section['narration_text'] ?? '';

            if ($narrationText === '') {
                continue;
            }

            $chunks = $this->chunker->chunk(
                $narrationText
            );

            if (empty($chunks)) {
                continue;
            }

            $preparedChunks = [];

            foreach ($chunks as $position => $chunkText) {

                $characters = mb_strlen($chunkText);

                $preparedChunks[] = [
                    'position' => $position + 1,
                    'text' => $chunkText,
                    'characters' => $characters,
                ];

                $totalCharacters += $characters;
                $totalChunks++;
            }

            $preparedSections[] = [
                'section' => $section,
                'chunks' => $preparedChunks,
            ];
        }

        if (empty($preparedSections)) {
            throw new RuntimeException(
                'Aucun contenu exploitable n’a été préparé.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Estimation du coût
        |--------------------------------------------------------------------------
        |
        | ElevenLabs v3 :
        | environ $0.10 / 1 000 caractères
        |
        */

        $pricePerThousandCharacters = 0.10;

        $estimatedCost =
            ($totalCharacters / 1000)
            * $pricePerThousandCharacters;

        /*
        |--------------------------------------------------------------------------
        | 6. Création en base
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $book,
            $voiceId,
            $model,
            $totalCharacters,
            $totalChunks,
            $estimatedCost,
            $preparedSections
        ) {

            $audiobook = Audiobook::create([
                'book_id' => $book->id,

                'status' => 'draft',

                'voice_id' => $voiceId,

                'model' => $model,

                'total_characters' => $totalCharacters,

                'generated_characters' => 0,

                'total_chunks' => $totalChunks,

                'completed_chunks' => 0,

                'estimated_cost' => round(
                    $estimatedCost,
                    4
                ),

                'actual_cost' => 0,

                'final_audio_path' => null,

                'error_message' => null,

                'started_at' => null,

                'completed_at' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Sections
            |--------------------------------------------------------------------------
            */

            foreach ($preparedSections as $sectionData) {

                $section = $sectionData['section'];

                $audiobookSection =
                    $audiobook->sections()->create([

                        'position' =>
                            $section['position'] ?? 0,

                        'type' =>
                            $section['type'] ?? 'section',

                        'number' =>
                            $section['number'] ?? null,

                        'title' =>
                            $section['title'] ?? null,

                        'text' =>
                            $section['text'] ?? null,

                        'narration_text' =>
                            $section['narration_text'] ?? null,

                        'characters' =>
                            $section['narration_characters']
                            ?? mb_strlen(
                                $section['narration_text'] ?? ''
                            ),

                        'words' =>
                            $section['narration_words']
                            ?? str_word_count(
                                $section['narration_text'] ?? ''
                            ),

                        'start_page' =>
                            $section['start_page'] ?? null,

                        'end_page' =>
                            $section['end_page'] ?? null,

                        'detection_method' =>
                            $section['detection_method'] ?? null,

                        'confidence' =>
                            $section['confidence'] ?? null,

                        'status' => 'pending',

                        'audio_path' => null,

                        'duration_seconds' => null,

                        'error_message' => null,
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Chunks
                |--------------------------------------------------------------------------
                */

                foreach (
                    $sectionData['chunks']
                    as $chunk
                ) {

                    $audiobookSection
                        ->chunks()
                        ->create([

                            'position' =>
                                $chunk['position'],

                            'text' =>
                                $chunk['text'],

                            'characters' =>
                                $chunk['characters'],

                            'character_cost' => 0,

                            'status' => 'pending',

                            'audio_path' => null,

                            'request_id' => null,

                            'trace_id' => null,

                            'duration_seconds' => null,

                            'error_message' => null,

                            'started_at' => null,

                            'completed_at' => null,
                        ]);
                }
            }

            return $audiobook->load(
                'sections.chunks'
            );
        });
    }

    /**
     * Vérifie si le compte ElevenLabs possède
     * suffisamment de caractères disponibles.
     *
     * Cette méthode est utile avant de lancer
     * réellement la génération.
     */
   public function checkQuota(
    Audiobook $audiobook
    ): array {
    $remaining =
        $this->elevenLabs->getRemainingCharacters();

    $required =
        $audiobook->total_characters;

    if ($remaining === null) {
        return [
            'allowed' => true,
            'remaining' => null,
            'required' => $required,
        ];
    }

    return [
        'allowed' => $remaining >= $required,
        'remaining' => $remaining,
        'required' => $required,
    ];
}

  public function generateOneChunk(
    Audiobook $audiobook,
    int $chunkId
    ): void {
    if ($audiobook->status === 'cancelled') {
        throw new RuntimeException(
            'La génération de cet audiobook a été annulée.'
        );
    }

    if ($audiobook->status === 'completed') {
        throw new RuntimeException(
            'Cet audiobook est déjà terminé.'
        );
    }

    $chunk = $audiobook->chunks()
        ->where('audiobook_chunks.id', $chunkId)
        ->first();

    if (!$chunk) {
        throw new RuntimeException(
            'Le chunk demandé n’appartient pas à cet audiobook.'
        );
    }

    if ($chunk->status === 'completed') {
        throw new RuntimeException(
            'Ce chunk a déjà été généré.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Vérification du quota pour CE chunk
    |--------------------------------------------------------------------------
    */

    $remaining =
        $this->elevenLabs->getRemainingCharacters();

    $required =
        $chunk->characters;

    if (
        $remaining !== null &&
        $remaining < $required
    ) {
        throw new RuntimeException(
            'Quota ElevenLabs insuffisant pour ce chunk. '
            . 'Caractères nécessaires : '
            . number_format(
                $required,
                0,
                ',',
                ' '
            )
            . '. Caractères disponibles : '
            . number_format(
                $remaining,
                0,
                ',',
                ' '
            )
            . '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Statut audiobook
    |--------------------------------------------------------------------------
    */

    if ($audiobook->status === 'draft') {
        $audiobook->update([
            'status' => 'generating',
            'started_at' => now(),
            'error_message' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Envoi du chunk dans la queue
    |--------------------------------------------------------------------------
    */

    GenerateAudiobookChunkJob::dispatch(
        $chunk->id
    );
}

}