<?php

namespace App\Services\Audiobook;

class AudiobookTextPreparer
{
    public function prepare(array $section): array
    {
        $text = trim($section['text'] ?? '');

        $title = trim($section['title'] ?? '');
        $number = trim((string) ($section['number'] ?? ''));
        $type = $section['type'] ?? 'section';

        $narration = $this->buildNarrationHeader(
            $type,
            $number,
            $title
        );

        if ($text !== '') {
            $narration .= $text;
        }

        $narration = $this->normalizeForNarration($narration);

        return [
            ...$section,
            'narration_text' => $narration,
            'narration_characters' => mb_strlen($narration),
            'narration_words' => str_word_count($narration),
        ];
    }

    private function buildNarrationHeader(
            string $type,
            string $number,
            string $title
        ): string {
       $parts = [];

        if ($number !== '') {

            $label = match ($type) {
                'chapter' => 'Chapitre',
                'part' => 'Partie',
                'act' => 'Acte',
                'prologue' => 'Prologue',
                'epilogue' => 'Épilogue',
                default => 'Section',
            };

            /*
            * Prologue et épilogue n'ont généralement
            * pas de numéro.
            */
            if (!in_array($type, ['prologue', 'epilogue'], true)) {

                /*
                * Le numéro original peut être :
                * 4.
                * 5)
                * IV
                *
                * On retire uniquement la ponctuation
                * utilisée comme séparateur.
                *
                * Le numéro original de la section reste
                * inchangé dans $section['number'].
                */
                $spokenNumber = rtrim($number, '.)');

                $parts[] = $label . ' ' . $spokenNumber;
            }

        } else {

            $label = match ($type) {
                'prologue' => 'Prologue',
                'epilogue' => 'Épilogue',
                default => 'Section',
            };

            $parts[] = $label;
        }

        if ($title !== '') {
            $parts[] = $title;
        }

        if (empty($parts)) {
            return '';
        }

        return implode('. ', $parts) . ".\n\n";
    }

    private function normalizeForNarration(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        /*
         * Espaces insécables.
         */
        $text = str_replace("\xC2\xA0", ' ', $text);

        /*
         * Nettoyage des espaces en trop.
         */
        $text = preg_replace('/[ \t]+/', ' ', $text);

        /*
         * Nettoyage des espaces autour des retours à la ligne.
         */
        $text = preg_replace('/[ \t]*\n[ \t]*/', "\n", $text);

        /*
         * Pas plus de deux retours à la ligne consécutifs.
         */
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }
}