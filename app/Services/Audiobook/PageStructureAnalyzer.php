<?php

namespace App\Services\Audiobook;

class PageStructureAnalyzer
{
    /**
     * Analyse les lignes de chaque page,
     * puis analyse les séquences numériques du document.
     */
    public function analyze(array $pages): array
    {
        foreach ($pages as &$page) {

            $page['structure_candidates'] = [];

            foreach ($page['lines'] as $index => $line) {

                $line = trim($line);

                if (!$this->looksLikeNumber($line)) {
                    continue;
                }

                $candidate = [
                    'line_index' => $index,
                    'value' => $line,
                    'type' => 'numeric_candidate',
                    'position' => 'unknown',
                ];

               $nextLine = $page['lines'][$index + 1] ?? null;

                if ($nextLine !== null) {

                    $nextLine = trim($nextLine);

                    /*
                    * Si le nombre est immédiatement suivi
                    * d'un autre nombre, on ne considère pas
                    * encore qu'il est suivi d'un titre.
                    */
                    if (
                        !$this->looksLikeNumber($nextLine) &&
                        $this->looksLikeSectionTitle($nextLine)
                    )  {

                        $candidate['next_line'] = $nextLine;
                        $candidate['context'] = 'followed_by_heading';

                        $afterHeading = $page['lines'][$index + 2] ?? null;

                        if ($afterHeading !== null) {
                            $candidate['after_heading'] = trim($afterHeading);
                        }
                    }
                }

                $page['structure_candidates'][] = $candidate;
            }
        }

        unset($page);

        /*
         * Deuxième phase :
         * analyser les candidats à l'échelle du document.
         */
        $pages = $this->analyzePaginationSequences($pages);
        $pages = $this->calculateStructureScores($pages);
        $pages = $this->analyzeStructuralPatterns($pages);
        return $pages;
    }

    private function calculateStructureScores(array $pages): array
    {
        foreach ($pages as &$page) {

            foreach ($page['structure_candidates'] as &$candidate) {

                $candidate['scores'] = [
                    'pagination' => 0,
                    'structural' => 0,
                ];

                /*
                * Signal très fort :
                * le nombre appartient à une séquence
                * de pagination.
                */
                if (
                    isset($candidate['signals']['pagination_sequence']) &&
                    $candidate['signals']['pagination_sequence'] === true
                ) {
                    $candidate['scores']['pagination'] += 50;
                }

                /*
                * Signal :
                * le nombre est directement suivi
                * d'un titre probable.
                */
                if (
                    isset($candidate['context']) &&
                    $candidate['context'] === 'followed_by_heading'
                ) {
                    $candidate['scores']['structural'] += 30;
                }

                /*
                * Un nombre qui est suivi d'un titre
                * mais qui n'appartient pas à une séquence
                * de pagination devient un candidat
                * structurel intéressant.
                */
                if (
                    isset($candidate['context']) &&
                    $candidate['context'] === 'followed_by_heading' &&
                    empty($candidate['signals']['pagination_sequence'])
                ) {
                    $candidate['scores']['structural'] += 20;
                }
            }

            unset($candidate);
        }

        unset($page);

        return $pages;
    }

    private function analyzeStructuralPatterns(array $pages): array
    {
        $structuralCandidates = [];

        /*
        * Première passe :
        * collecter les candidats qui ont un signal structurel.
        */
        foreach ($pages as $pageIndex => $page) {

            /*
            * Ignorer les entrées spéciales qui ne sont pas
            * de vraies pages.
            */
            if (!isset($page['structure_candidates'])) {
                continue;
            }

            foreach ($page['structure_candidates'] as $candidateIndex => $candidate) {

                $paginationScore = $candidate['scores']['pagination'] ?? 0;
                $structuralScore = $candidate['scores']['structural'] ?? 0;

                /*
                * On s'intéresse principalement aux nombres
                * qui ont un indice structurel et qui ne sont
                * pas identifiés comme pagination.
                */
                if (
                    $structuralScore > 0 &&
                    $paginationScore === 0
                ) {
                    $structuralCandidates[] = [
                        'page_index' => $pageIndex,
                        'candidate_index' => $candidateIndex,
                        'value' => $candidate['value'],
                        'structural_score' => $structuralScore,
                        'next_line' => $candidate['next_line'] ?? null,
                        'after_heading' => $candidate['after_heading'] ?? null,
                    ];
                }
            }
        }

        /*
        * Deuxième passe :
        * déterminer combien de candidats structurels
        * utilisent la même valeur.
        */
        $valueOccurrences = [];

        foreach ($structuralCandidates as $candidate) {

            $value = $candidate['value'];

            if (!isset($valueOccurrences[$value])) {
                $valueOccurrences[$value] = 0;
            }

            $valueOccurrences[$value]++;
        }

        /*
        * Troisième passe :
        * ajouter les informations globales
        * à chaque candidat concerné.
        */
        foreach ($structuralCandidates as $candidate) {

            $pageIndex = $candidate['page_index'];
            $candidateIndex = $candidate['candidate_index'];

            $value = $candidate['value'];

            $pages[$pageIndex]['structure_candidates'][$candidateIndex]
                ['signals']['structural_occurrences'] =
                    $valueOccurrences[$value];

            /*
            * Si une même valeur apparaît plusieurs fois
            * dans un contexte structurel, on garde cette
            * information comme indice.
            */
            if ($valueOccurrences[$value] >= 2) {

                $pages[$pageIndex]['structure_candidates'][$candidateIndex]
                    ['scores']['structural'] += 10;
            }
        }

        /*
        * Ajouter un résumé global au résultat.
        */
        $pages['_structure_analysis'] = [
            'structural_candidates' => $structuralCandidates,
            'value_occurrences' => $valueOccurrences,
        ];

        return $pages;
    }

    /**
     * Recherche des séquences numériques qui ressemblent
     * à une pagination.
     */
    private function analyzePaginationSequences(array $pages): array
    {
        $candidateMap = [];

        foreach ($pages as $pageIndex => $page) {

            if (!isset($page['structure_candidates'])) {
                continue;
            }

            foreach ($page['structure_candidates'] as $candidateIndex => $candidate) {

                $value = $this->numericValue($candidate['value']);

                if ($value === null) {
                    continue;
                }

                $candidateMap[] = [
                    'page_index' => $pageIndex,
                    'candidate_index' => $candidateIndex,
                    'value' => $value,
                ];
            }
        }

        /*
        * Détection des séquences de pagination.
        *
        * On cherche une suite :
        *
        * page X   → valeur N
        * page X+1 → valeur N+1
        * page X+2 → valeur N+2
        *
        * Les autres candidats présents sur les mêmes pages
        * ne doivent pas casser la séquence.
        */

        foreach ($candidateMap as $currentIndex => $current) {

            $sequence = [$current];

            $expectedPageIndex = $current['page_index'] + 1;
            $expectedValue = $current['value'] + 1;

            for (
                $nextIndex = $currentIndex + 1;
                $nextIndex < count($candidateMap);
                $nextIndex++
            ) {

                $next = $candidateMap[$nextIndex];

                /*
                * On ignore les candidats qui appartiennent
                * à une page déjà parcourue.
                */
                if ($next['page_index'] < $expectedPageIndex) {
                    continue;
                }

                /*
                * Si on trouve la bonne valeur sur la page attendue,
                * elle appartient à la séquence de pagination.
                */
                if (
                    $next['page_index'] === $expectedPageIndex &&
                    $next['value'] === $expectedValue
                ) {
                    $sequence[] = $next;

                    $expectedPageIndex++;
                    $expectedValue++;

                    continue;
                }

                /*
                * Si on arrive à une page future sans trouver
                * la valeur attendue, la séquence est terminée.
                */
                if ($next['page_index'] > $expectedPageIndex) {
                    break;
                }
            }

            /*
            * Une séquence de 3 pages consécutives constitue
            * un signal suffisamment fort pour identifier
            * une pagination.
            */
            if (count($sequence) >= 3) {

                foreach ($sequence as $item) {

                    $pages[
                        $item['page_index']
                    ]['structure_candidates'][
                        $item['candidate_index']
                    ]['signals']['pagination_sequence'] = true;

                    $pages[
                        $item['page_index']
                    ]['structure_candidates'][
                        $item['candidate_index']
                    ]['signals']['pagination_sequence_length'] =
                        count($sequence);
                }
            }
        }

        return $pages;
    }

    /**
     * Convertit une représentation numérique en entier.
     */
   private function numericValue(string $value): ?int
{
    $value = trim($value);

    /*
     * Nombres arabes :
     * 1, 2, 10, 001, 002...
     */
    $value = trim($value);

    if (preg_match('/^\d{1,5}[.)]?$/', $value)) {
        return (int) rtrim($value, '.)');
    }

    /*
     * Nombres romains :
     * I, II, III, IV, V, IX, X, XL, etc.
     */
    if (!preg_match(
        '/^(?=[MDCLXVI]+$)'
        . 'M{0,4}'
        . '(CM|CD|D?C{0,3})'
        . '(XC|XL|L?X{0,3})'
        . '(IX|IV|V?I{0,3})$/i',
        $value
    )) {
        return null;
    }

    $value = strtoupper($value);

    $values = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];

    $total = 0;
    $length = strlen($value);

    for ($i = 0; $i < $length; $i++) {

        $current = $values[$value[$i]];

        $next = $values[$value[$i + 1]] ?? 0;

        if ($current < $next) {
            $total -= $current;
        } else {
            $total += $current;
        }
    }

    return $total;
}

    /**
     * Vérifie si une ligne contient uniquement un numéro.
     */
    private function looksLikeNumber(string $line): bool
    {
        if (preg_match('/^\d{1,5}[.)]?$/', $line)) {
            return true;
        }

        /*
         * Chiffres romains.
         */
        if (preg_match(
            '/^(?=[MDCLXVI]+$)M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i',
            $line
        )) {
            return true;
        }

        return false;
    }

    private function looksLikeSectionTitle(string $line): bool
{
    $line = trim($line);

    if ($line === '') {
        return false;
    }

    if (mb_strlen($line) > 100) {
        return false;
    }

    $words = preg_split('/\s+/', $line);

    if (count($words) > 10) {
        return false;
    }

    if (preg_match('/[.!?…]$/u', $line)) {
        return false;
    }

    return true;
}

    /**
     * Détermine si une ligne ressemble à un titre.
     *
     * Ceci reste uniquement un indice.
     */
  private function looksLikeHeading(string $line): bool
{
    $line = trim($line);

    if ($line === '') {
        return false;
    }

    // Un titre ne devrait normalement pas être très long.
    if (mb_strlen($line) > 100) {
        return false;
    }

    $words = preg_split('/\s+/', $line);

    if (count($words) > 10) {
        return false;
    }

    /*
     * Une phrase narrative classique commence généralement
     * par une majuscule et contient une ponctuation finale.
     *
     * Exemple :
     * "Le président du conseil l’observa quelques instants."
     *
     * → probablement du texte narratif.
     */
    if (preg_match('/[.!?…]$/u', $line)) {
        return false;
    }

    /*
     * Une ligne qui ressemble à une localisation/date
     * n'est pas considérée comme un titre.
     */
    if ($this->looksLikeLocationOrDate($line)) {
        return false;
    }

    /*
     * Les titres sont souvent courts.
     */
    if (count($words) <= 8) {
        return true;
    }

    /*
     * Une ligne entièrement en majuscules constitue
     * un indice fort de titre.
     */
    if (
        mb_strtoupper($line, 'UTF-8') === $line &&
        preg_match('/[A-ZÀ-ÖØ-Ý]/u', $line)
    ) {
        return true;
    }

    return false;
}

    /**
     * Vérifie si une ligne ressemble à une localisation ou une date.
     */
  private function looksLikeLocationOrDate(string $line): bool
{
    $line = trim($line);

    /*
     * Heuristiques générales :
     * présence d'une heure
     */
    if (preg_match('/\b\d{1,2}\s*h\s*\d{2}\b/i', $line)) {
        return true;
    }

    /*
     * Dates comme :
     * 14 mars 2024
     * Mars 2024
     * 2024
     */
    if (preg_match(
        '/\b\d{1,2}\s+(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\b/i',
        $line
    )) {
        return true;
    }

    if (preg_match(
        '/\b(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\s+\d{4}\b/i',
        $line
    )) {
        return true;
    }

    /*
     * Coordonnées / fuseaux horaires.
     */
    if (preg_match('/\bGMT\b|\bUTC\b/i', $line)) {
        return true;
    }

    return false;
}
    
}