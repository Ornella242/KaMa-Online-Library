<?php

namespace App\Services\Audiobook;

class ChapterDetector
{
    /**
     * Détecte les sections explicites du document.
     *
     * Première stratégie :
     * - ACTE
     * - CHAPITRE
     * - PARTIE
     * - PROLOGUE
     * - ÉPILOGUE
     */
   public function detect(array $pages): array
{
    $sections = [];

    foreach ($pages as $page) {

        /*
         * Ignorer les entrées spéciales de l'analyseur
         * comme _structure_analysis.
         */
        if (!isset($page['lines'])) {
            continue;
        }

        foreach ($page['lines'] as $lineIndex => $line) {

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            /*
             * 1. Détection explicite sur une seule ligne.
             */
            $section = $this->detectExplicitSection(
                $line,
                $page,
                $lineIndex
            );

            /*
             * 2. Détection explicite avec titre sur la ligne suivante.
             */
            if ($section === null) {
                $section = $this->detectExplicitSectionWithNextLine(
                    $line,
                    $page,
                    $lineIndex
                );
            }

            /*
             * 3. Détection numérique structurelle.
             */
            if ($section === null) {
                $section = $this->detectNumericSectionCandidate(
                    $page,
                    $lineIndex,
                    $line
                );
            }

            if ($section !== null) {
                $sections[] = $section;
            }
        }
    }

    return $sections;
}

   private function detectExplicitSectionWithNextLine(
            string $line,
            array $page,
            int $lineIndex
        ): ?array {

    if (!preg_match(
        '/^(ACTE|CHAPITRE|PARTIE)\b\s*'
        . '(?:(\d+|[IVXLCDM]+)\s*)?$/iu',
        $line,
        $matches
    )) {
        return null;
    }

    $nextLine = $page['lines'][$lineIndex + 1] ?? null;

    if ($nextLine === null) {
        return null;
    }

    $nextLine = trim($nextLine);

    if ($nextLine === '') {
        return null;
    }

    if ($this->isStructuralLabel($nextLine)) {
        return null;
    }

    return [
        'type' => $this->normalizeType(
            mb_strtolower($matches[1], 'UTF-8')
        ),
        'number' => $matches[2] ?? null,
        'title' => $nextLine,
        'page' => $page['page'] ?? null,
        'line_index' => $lineIndex,
        'raw' => $line . "\n" . $nextLine,
        'detection_method' => 'explicit_label_next_line',
        'confidence' => 0.95,
    ];
   }

    private function isStructuralLabel(string $line): bool
    {
        return preg_match(
            '/^(ACTE|CHAPITRE|PARTIE|PROLOGUE|ÉPILOGUE)\b/iu',
            trim($line)
        ) === 1;
    }

    private function detectNumericSectionCandidate(
    array $page,
    int $lineIndex,
    string $line
): ?array {

    if (!preg_match('/^(\d{1,5}|[IVXLCDM]+)[.)]?$/iu', $line)) {
        return null;
    }

    $candidate = collect($page['structure_candidates'] ?? [])
        ->firstWhere('line_index', $lineIndex);

    if ($candidate === null) {
        return null;
    }

    $paginationScore = $candidate['scores']['pagination'] ?? 0;
    $structuralScore = $candidate['scores']['structural'] ?? 0;

    if ($paginationScore > $structuralScore) {
        return null;
    }

    if ($structuralScore <= 0) {
        return null;
    }

    if (
        ($candidate['context'] ?? null) !== 'followed_by_heading'
    ) {
        return null;
    }

    

    return [
        'type' => 'chapter',
        'number' => $candidate['value'],
        'title' => $candidate['next_line'] ?? null,
        'page' => $page['page'] ?? null,
        'line_index' => $lineIndex,
        'raw' => $line . "\n" . ($candidate['next_line'] ?? ''),
        'detection_method' => 'numeric_structural_candidate',
        'confidence' => 0.85,
    ];
}

    /**
     * Détecte une section dont la structure est explicite.
     */
    private function detectExplicitSection(
        string $line,
        array $page,
        int $lineIndex
    ): ?array {

        /*
         * ACTE / CHAPITRE / PARTIE
         *
         * Exemples :
         *
         * ACTE 41 — Les portes d’URA
         * CHAPITRE 3 — La rencontre
         * CHAPITRE IV — La cité
         * PARTIE 2 : La guerre
         */
        if (preg_match(
            '/^(ACTE|CHAPITRE|PARTIE)\b\s*'
            . '(?:(\d+|[IVXLCDM]+)\s*)?'
            . '(?:[—–:-]\s*(.+))?$/iu',
            $line,
            $matches
        )) {

            $type = mb_strtolower($matches[1], 'UTF-8');

            $number = $matches[2] ?? null;

            $title = isset($matches[3])
                ? trim($matches[3])
                : null;

            return [
                'type' => $this->normalizeType($type),
                'number' => $number,
                'title' => $title,
                'page' => $page['page'] ?? null,
                'line_index' => $lineIndex,
                'raw' => $line,
                'detection_method' => 'explicit_label',
                'confidence' => 1.0,
            ];
        }

        /*
         * PROLOGUE / ÉPILOGUE
         *
         * Exemples :
         *
         * PROLOGUE
         * ÉPILOGUE
         * Épilogue
         */
        if (preg_match(
            '/^(PROLOGUE|ÉPILOGUE)$/iu',
            $line,
            $matches
        )) {

            return [
                'type' => $this->normalizeType(
                    mb_strtolower($matches[1], 'UTF-8')
                ),
                'number' => null,
                'title' => null,
                'page' => $page['page'] ?? null,
                'line_index' => $lineIndex,
                'raw' => $line,
                'detection_method' => 'explicit_label',
                'confidence' => 1.0,
            ];
        }

        return null;
    }

    /**
     * Normalise les types de sections.
     */
    private function normalizeType(string $type): string
    {
        return match ($type) {
            'acte' => 'act',
            'chapitre' => 'chapter',
            'partie' => 'part',
            'prologue' => 'prologue',
            'épilogue' => 'epilogue',
            default => 'section',
        };
    }
}