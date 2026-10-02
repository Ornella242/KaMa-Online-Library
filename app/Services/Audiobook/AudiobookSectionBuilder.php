<?php

namespace App\Services\Audiobook;

class AudiobookSectionBuilder
{
    /**
     * Construit les sections audio à partir
     * des pages et des sections détectées.
     */
    public function build(array $pages, array $sections): array
    {
        $documentPages = [];

        foreach ($pages as $key => $page) {

            if (!is_array($page)) {
                continue;
            }

            /*
            * _structure_analysis est une métadonnée,
            * pas une page du document.
            */
            if ($key === '_structure_analysis') {
                continue;
            }

            /*
            * Une vraie page possède les informations
            * produites par PdfTextExtractor.
            */
            if (!array_key_exists('page', $page)) {
                continue;
            }

            $documentPages[] = $page;
        }

        $result = [];

        foreach ($sections as $sectionIndex => $section) {

            $startPageIndex = $this->findPageIndex(
                $documentPages,
                $section['page'] ?? null
            );

            if ($startPageIndex === null) {
                continue;
            }

            $nextSection = $sections[$sectionIndex + 1] ?? null;

            if ($nextSection !== null) {

                $endPageIndex = $this->findPageIndex(
                    $documentPages,
                    $nextSection['page'] ?? null
                );

                if ($endPageIndex === null) {
                    $endPageIndex = count($documentPages) - 1;
                }

            } else {

                $endPageIndex = count($documentPages) - 1;
            }

            $text = $this->extractSectionText(
                $documentPages,
                $section,
                $startPageIndex,
                $endPageIndex,
                $nextSection
            );

            $text = trim($text);

            $result[] = [
                'position' => $sectionIndex + 1,
                'type' => $section['type'] ?? 'section',
                'number' => $section['number'] ?? null,
                'title' => $section['title'] ?? null,
                'text' => $text,
                'characters' => mb_strlen($text),
                'words' => str_word_count($text),
                'detection_method' => $section['detection_method'] ?? null,
                'confidence' => $section['confidence'] ?? null,
                'start_page' => $section['page'] ?? null,
                'end_page' => $nextSection !== null
                            ? (
                                ($nextSection['page'] ?? null) !== null
                                    ? $nextSection['page'] - 1
                                    : null
                            )
                            : ($documentPages[$endPageIndex]['page'] ?? null),
            ];
        }

        return $result;
    }

    /**
     * Trouve l'index interne d'une page
     * à partir de son numéro logique.
     */
    private function findPageIndex(
        array $pages,
        $pageNumber
        ): ?int {

        if ($pageNumber === null) {
            return null;
        }

        foreach ($pages as $index => $page) {

            if (($page['page'] ?? null) == $pageNumber) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Extrait le texte correspondant à une section.
     */
    private function extractSectionText(
            array $pages,
            array $section,
            int $startPageIndex,
            int $endPageIndex,
            ?array $nextSection
        ): string {

        $parts = [];

        for (
            $pageIndex = $startPageIndex;
            $pageIndex <= $endPageIndex;
            $pageIndex++
        ) {

            if (!isset($pages[$pageIndex]['lines'])) {
                continue;
            }

            $lines = $pages[$pageIndex]['lines'];

            $startLine = 0;
            $endLine = count($lines) - 1;

            /*
             * Sur la page de début :
             * commencer après la section détectée.
             */
            if ($pageIndex === $startPageIndex) {
                $startLine = ($section['line_index'] ?? -1) + 1;

                /*
                 * Si le titre est sur la ligne suivante,
                 * on commence après le titre.
                 */
               if (
                    in_array($section['detection_method'] ?? null, [
                        'explicit_label_next_line',
                        'numeric_structural_candidate',
                    ], true)
                ) {
                    $startLine++;
                }
            }

            /*
             * Sur la page de fin :
             * arrêter avant la prochaine section.
             */
            if (
                $nextSection !== null &&
                $pageIndex === $endPageIndex
            ) {
                $nextLineIndex = $nextSection['line_index'] ?? null;

                if ($nextLineIndex !== null) {
                    $endLine = $nextLineIndex - 1;

                    if (
                        ($nextSection['detection_method'] ?? null)
                        === 'explicit_label_next_line'
                    ) {
                        $endLine = $nextLineIndex - 1;
                    }
                }
            }

            if ($startLine > $endLine) {
                continue;
            }

            $pageLines = [];

                for ($lineIndex = $startLine; $lineIndex <= $endLine; $lineIndex++) {

                    if ($this->isPaginationLine(
                        $pages[$pageIndex],
                        $lineIndex
                    )) {
                        continue;
                    }

                    $pageLines[] = $lines[$lineIndex];
                }

                if (!empty($pageLines)) {
                    $parts[] = implode("\n", $pageLines);
                }
        }

        return implode("\n\n", $parts);
    }

    private function isPaginationLine(array $page, int $lineIndex): bool
    {
        $candidates = $page['structure_candidates'] ?? [];

        foreach ($candidates as $candidate) {

            if (
                ($candidate['line_index'] ?? null) === $lineIndex
                && ($candidate['signals']['pagination_sequence'] ?? false) === true
            ) {
                return true;
            }
        }

        return false;
    }
}