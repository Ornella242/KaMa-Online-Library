<?php

namespace App\Services\Audiobook;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ElevenLabsService
{
    private string $baseUrl = 'https://api.elevenlabs.io/v1';

    public function __construct()
    {
        if (!config('services.elevenlabs.api_key')) {
            throw new RuntimeException(
                'La clé API ElevenLabs n’est pas configurée.'
            );
        }
    }

    /**
     * Génère un audio et retourne uniquement les données audio.
     */
    public function generateSpeech(
        string $text,
        ?string $voiceId = null
    ): string {
        $result = $this->generateSpeechWithMetadata(
            $text,
            $voiceId
        );

        return $result['audio'];
    }

    /**
     * Génère un audio et retourne également
     * les informations de consommation ElevenLabs.
     */
    public function generateSpeechWithMetadata(
        string $text,
        ?string $voiceId = null
    ): array {
        if ($text === '') {
            throw new RuntimeException(
                'Le texte à convertir en audio est vide.'
            );
        }

        $voiceId = $voiceId
            ?? config('services.elevenlabs.voice_id');

        if (!$voiceId) {
            throw new RuntimeException(
                'Aucune voix ElevenLabs n’est configurée.'
            );
        }

        $modelId = config(
            'services.elevenlabs.model',
            'eleven_v3'
        );

        $response = Http::timeout(120)->withHeaders([
            'xi-api-key' => config('services.elevenlabs.api_key'),
            'Content-Type' => 'application/json',
        ])->post(
            $this->baseUrl . '/text-to-speech/' . $voiceId,
            [
                'text' => $text,
                'model_id' => $modelId,
                'output_format' => 'mp3_44100_128',
            ]
        );

        if ($response->failed()) {

            $body = $response->json();

            $status = data_get(
                $body,
                'detail.status'
            );

            $message = data_get(
                $body,
                'detail.message'
            );

            if ($status === 'quota_exceeded') {
                throw new RuntimeException(
                    'Quota ElevenLabs insuffisant pour cette génération.'
                );
            }

            throw new RuntimeException(
                'Erreur ElevenLabs : '
                . ($message ?? $response->body())
            );
        }

        /*
         * ElevenLabs indique le coût réel de la génération
         * dans le header "character-cost".
         */
        $characterCost = $response->header(
            'character-cost'
        );

        /*
         * Fallback si le header n'est pas disponible.
         */
        if ($characterCost === null) {
            $characterCost = mb_strlen($text);
        }

        return [
            'audio' => $response->body(),

            'character_cost' => (int) $characterCost,

            'request_id' => $response->header(
                'request-id'
            ),

            'trace_id' => $response->header(
                'x-trace-id'
            ),

            'characters_sent' => mb_strlen($text),

            'voice_id' => $voiceId,

            'model_id' => $modelId,
        ];
    }

    /**
     * Récupère l'état actuel du quota ElevenLabs.
     */
    public function getSubscription(): array
    {
        $response = Http::withHeaders([
            'xi-api-key' => config('services.elevenlabs.api_key'),
        ])->get(
            $this->baseUrl . '/user/subscription'
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'Impossible de récupérer le quota ElevenLabs : '
                . $response->body()
            );
        }

        $data = $response->json();

        $characterCount = (int) (
            $data['character_count'] ?? 0
        );

        $characterLimit = $data['character_limit'] ?? null;

        $remaining = null;

        if ($characterLimit !== null) {
            $remaining = max(
                0,
                (int) $characterLimit - $characterCount
            );
        }

        return [
            'tier' => $data['tier'] ?? null,

            'status' => $data['status'] ?? null,

            'character_count' => $characterCount,

            'character_limit' => $characterLimit !== null
                ? (int) $characterLimit
                : null,

            'remaining_characters' => $remaining,

            'max_credit_limit_extension' =>
                $data['max_credit_limit_extension'] ?? null,

            'can_extend_character_limit' =>
                $data['can_extend_character_limit'] ?? false,

            'current_overage' =>
                $data['current_overage'] ?? null,

            'next_character_count_reset_unix' =>
                $data['next_character_count_reset_unix'] ?? null,
        ];
    }

    /**
     * Vérifie si le quota actuel permet
     * de générer un certain nombre de caractères.
     */
    public function hasEnoughQuota(int $characters): bool
    {
        $subscription = $this->getSubscription();

        $remaining = $subscription['remaining_characters'];

        /*
         * Si ElevenLabs n'indique pas de limite stricte,
         * on laisse l'API décider.
         */
        if ($remaining === null) {
            return true;
        }

        return $remaining >= $characters;
    }

    /**
     * Retourne le nombre de caractères encore disponibles.
     */
    public function getRemainingCharacters(): ?int
    {
        $subscription = $this->getSubscription();

        return $subscription['remaining_characters'];
    }

    /**
     * Récupère les voix partagées autorisées.
     */
    public function getFreeSharedVoices(
        array $filters = []
    ): array {
        $response = Http::withHeaders([
            'xi-api-key' => config('services.elevenlabs.api_key'),
        ])->get(
            $this->baseUrl . '/shared-voices',
            array_merge([
                'page_size' => 100,
            ], $filters)
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'Erreur ElevenLabs lors de la récupération '
                . 'des voix : '
                . $response->body()
            );
        }

        $voices = $response->json(
            'voices',
            []
        );

        return array_values(
            array_filter(
                $voices,
                fn ($voice) =>
                    ($voice['free_users_allowed'] ?? false) === true
            )
        );
    }

    /**
     * Test rapide d'une voix.
     */
    public function testVoice(
        ?string $voiceId = null
    ): string {
        return $this->generateSpeech(
            'Bonjour, ceci est un test de génération audio pour KaMa.',
            $voiceId
        );
    }

    public function downloadHistoryAudio(
            string $historyItemId
        ): string {
        $response = Http::timeout(120)
            ->withHeaders([
                'xi-api-key' => config('services.elevenlabs.api_key'),
            ])
            ->get(
                $this->baseUrl
                . '/history/'
                . $historyItemId
                . '/audio'
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Impossible de récupérer l’audio ElevenLabs : '
                . $response->body()
            );
        }

        return $response->body();
    }

    public function getVoices(
        ?string $language = null,
        ?string $search = null,
        int $pageSize = 100
        ): array {
        $query = [
            'page_size' => min($pageSize, 100),
        ];

        if ($language) {
            $query['language'] = $language;
        }

        if ($search) {
            $query['search'] = $search;
        }

        $response = Http::timeout(30)
            ->withHeaders([
                'xi-api-key' => config(
                    'services.elevenlabs.api_key'
                ),
                'Accept' => 'application/json',
            ])
            ->get(
                'https://api.elevenlabs.io/v2/voices',
                $query
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Impossible de récupérer les voix ElevenLabs. '
                . $response->body()
            );
        }

     return $response->json();
    }
}