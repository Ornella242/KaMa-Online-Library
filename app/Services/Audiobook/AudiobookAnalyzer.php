<?php

namespace App\Services\Audiobook;

class AudiobookAnalyzer
{
    public function __construct(
        private PdfTextExtractor $extractor,
        private TextCleaner $cleaner,
        private PageStructureAnalyzer $structureAnalyzer,
        private ChapterDetector $chapterDetector,
        private AudiobookSectionBuilder $sectionBuilder,
        private AudiobookTextPreparer $textPreparer,
    ) {
    }

    public function analyze(string $pdfPath): array
    {
        /*
        * 1. Extraction du PDF
        */
        $extraction = $this->extractor->extract($pdfPath);

        $pages = $extraction['pages'] ?? [];

        /*
        * 2. Nettoyage
        */
        $pages = $this->cleaner->cleanPages($pages);

        /*
        * 3. Analyse de la structure
        */
        $pages = $this->structureAnalyzer->analyze($pages);

        /*
        * 4. Détection des chapitres / sections
        */
        $sections = $this->chapterDetector->detect($pages);

        /*
        * 5. Construction du contenu de chaque section
        */
        $sections = $this->sectionBuilder->build(
            $pages,
            $sections
        );

        /*
        * 6. Préparation du texte pour la narration
        */
        $sections = array_map(
            fn ($section) => $this->textPreparer->prepare($section),
            $sections
        );

        /*
        * 7. Statistiques globales
        */
        $characters = 0;
        $words = 0;

        foreach ($sections as $section) {
            $characters += $section['narration_characters'] ?? 0;
            $words += $section['narration_words'] ?? 0;
        }

        /*
        * 8. Calcul du coût estimé
        */
        $cost = $this->calculateEstimatedCost($characters);

        return [
            'pages' => $pages,
            'sections' => $sections,

            'statistics' => [
                'page_count' => $extraction['page_count'] ?? 0,
                'section_count' => count($sections),
                'characters' => $characters,
                'words' => $words,

                'elevenlabs_cost' => $cost['elevenlabs_cost'],
                'kama_margin' => $cost['kama_margin'],
                'estimated_cost' => $cost['total_cost'],
            ],
        ];
    }

    private function calculateEstimatedCost(int $characters): array
    {
        // Calcul du coût estimé basé sur le nombre de caractères
        // Le coût est calculé en fonction du nombre de caractères, avec un prix par tranche de 1000 caractères et une marge pour l'entreprise.
        // Kama Library prend une marge de 10% sur le coût de narration.

        $pricePerThousandCharacters = 0.10;
        $kamaMarginRate = 0.10;

        $elevenLabsCost = ($characters / 1000) * $pricePerThousandCharacters;
        $kamaMargin = $elevenLabsCost * $kamaMarginRate;
        $totalCost = $elevenLabsCost + $kamaMargin;

        return [
            'elevenlabs_cost' => round($elevenLabsCost, 2),
            'kama_margin' => round($kamaMargin, 2),
            'total_cost' => round($totalCost, 2),
        ];
    }
}