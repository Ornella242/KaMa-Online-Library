<?php

namespace App\Services\Audiobook;

class TextCleaner
{
    /**
     * Nettoie le texte extrait d'une page PDF.
     */
    public function cleanPage(string $text): string
    {
        // Normaliser les retours à la ligne
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Remplacer les espaces insécables
        $text = str_replace("\xC2\xA0", ' ', $text);

        // Supprimer les espaces en début/fin de ligne
        $text = preg_replace('/^[ \t]+|[ \t]+$/m', '', $text);

        // Réduire les espaces multiples
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Réduire les lignes vides multiples
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }

    /**
     * Nettoie plusieurs pages.
     */
    public function cleanPages(array $pages): array
    {
        return array_map(function ($page) {

            $cleanText = $this->cleanPage($page['text']);

            return [
                ...$page,
                'text' => $cleanText,
                'characters' => mb_strlen($cleanText),
                'words' => str_word_count($cleanText),
                'lines' => $this->getLines($cleanText),
            ];

        }, $pages);
    }

    /**
     * Retourne les lignes non vides du texte.
     */
    private function getLines(string $text): array
    {
        return array_values(
            array_filter(
                preg_split('/\n+/', $text),
                fn ($line) => trim($line) !== ''
            )
        );
    }
}