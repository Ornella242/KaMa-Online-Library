<?php

namespace App\Services\Audiobook;

class AudiobookTextChunker
{
    /**
     * Limite maximale de sécurité par requête ElevenLabs.
     */
    private int $maxCharacters = 4500;

    /**
     * Taille minimale normalement souhaitée pour un chunk.
     *
     * Ce n'est pas une limite absolue :
     * un dernier chunk peut être plus petit si le texte
     * ne permet pas une meilleure coupure naturelle.
     */
    private int $preferredMinimumCharacters = 3000;

    /**
     * Taille minimale acceptable pour le dernier chunk
     * lorsqu'un meilleur rééquilibrage est possible.
     */
    private int $minimumFinalChunkCharacters = 1500;

    /**
     * Marge utilisée pour rechercher une meilleure coupure
     * lorsque le dernier chunk serait trop petit.
     */
    private int $rebalanceSearchCharacters = 1800;

    /**
     * Découpe un texte destiné à la narration audio.
     *
     * Garanties :
     * - aucun caractère perdu ;
     * - aucun caractère ajouté ;
     * - aucun mot coupé volontairement ;
     * - priorité aux paragraphes ;
     * - priorité aux dialogues complets ;
     * - priorité aux fins de phrases ;
     * - rééquilibrage des derniers chunks si nécessaire.
     */
    public function chunk(string $text): array
    {
        if ($text === '') {
            return [];
        }

        $length = mb_strlen($text);

        if ($length <= $this->maxCharacters) {
            return [$text];
        }

        $chunks = [];
        $offset = 0;

        while ($offset < $length) {

            $remainingLength = $length - $offset;

            /*
             * Le dernier morceau tient entièrement dans la limite.
             */
            if ($remainingLength <= $this->maxCharacters) {
                $chunks[] = mb_substr($text, $offset);

                break;
            }

            /*
             * Texte restant pouvant tenir dans un chunk.
             */
            $candidate = mb_substr(
                $text,
                $offset,
                $this->maxCharacters
            );

            /*
             * Recherche du meilleur point de coupure.
             */
            $cutPosition = $this->findBestCutPosition($candidate);

            if (
                $cutPosition <= 0 ||
                $cutPosition > $this->maxCharacters
            ) {
                $cutPosition = $this->maxCharacters;
            }

            /*
             * -----------------------------------------------------
             * RÉÉQUILIBRAGE
             * -----------------------------------------------------
             *
             * Si cette coupure laisse un dernier chunk trop petit,
             * on cherche une meilleure coupure narrative un peu
             * plus tôt.
             */
            $remainingAfterCut = $length - ($offset + $cutPosition);

            if (
                $remainingAfterCut > 0 &&
                $remainingAfterCut < $this->minimumFinalChunkCharacters
            ) {
                $betterCut = $this->findRebalancedCutPosition(
                    $text,
                    $offset,
                    $cutPosition,
                    $remainingAfterCut
                );

                if ($betterCut !== null) {
                    $cutPosition = $betterCut;
                }
            }

            /*
             * Extrait directement du texte original.
             *
             * Aucun trim().
             */
            $chunks[] = mb_substr(
                $text,
                $offset,
                $cutPosition
            );

            $offset += $cutPosition;
        }

        /*
         * Garantie absolue d'intégrité.
         */
        if (implode('', $chunks) !== $text) {
            throw new \RuntimeException(
                'Erreur critique : le découpage audiobook a modifié ou perdu du texte.'
            );
        }

        return $chunks;
    }

    /**
     * Cherche une meilleure coupure lorsqu'une coupure actuelle
     * laisserait un dernier chunk trop petit.
     */
    private function findRebalancedCutPosition(
        string $text,
        int $offset,
        int $currentCutPosition,
        int $remainingAfterCut
    ): ?int {
        /*
         * Il faut déplacer la coupure vers la gauche.
         */
        $searchStart = max(
            0,
            $currentCutPosition - $this->rebalanceSearchCharacters
        );

        $searchLength = $currentCutPosition - $searchStart;

        if ($searchLength <= 0) {
            return null;
        }

        $searchArea = mb_substr(
            $text,
            $offset + $searchStart,
            $searchLength
        );

        /*
         * On récupère uniquement les coupures narratives.
         *
         * On exclut volontairement les simples espaces :
         * le rééquilibrage ne doit pas sacrifier la qualité
         * narrative juste pour équilibrer les tailles.
         */
        $candidates = [];

        $this->addParagraphCandidates(
            $searchArea,
            $candidates
        );

        $this->addDialogueCandidates(
            $searchArea,
            $candidates
        );

        $this->addSentenceCandidates(
            $searchArea,
            $candidates
        );

        $this->addStrongPunctuationCandidates(
            $searchArea,
            $candidates
        );

        if (empty($candidates)) {
            return null;
        }

        $validCandidates = [];

        foreach ($candidates as $candidate) {

            $relativePosition = $candidate['position'];

            $absolutePosition =
                $searchStart + $relativePosition;

            /*
             * Il faut réellement déplacer la coupure.
             */
            if ($absolutePosition >= $currentCutPosition) {
                continue;
            }

            /*
             * Taille du dernier chunk après déplacement
             * de la coupure.
             */
            $newRemaining =
                mb_strlen($text)
                - ($offset + $absolutePosition);

            /*
             * On veut obtenir au moins la taille minimale
             * souhaitée pour le dernier morceau.
             */
            if (
                $newRemaining <
                $this->minimumFinalChunkCharacters
            ) {
                continue;
            }

            /*
             * On évite également de créer un chunk précédent
             * inutilement petit.
             */
            if (
                $absolutePosition <
                $this->preferredMinimumCharacters
            ) {
                continue;
            }

            /*
             * Plus le dernier chunk est proche d'une taille
             * raisonnable, plus le candidat est intéressant.
             */
            $distanceFromIdeal =
                abs(
                    $newRemaining -
                    $this->preferredMinimumCharacters
                );

            /*
             * Bonus pour les coupures de meilleure qualité.
             */
            $qualityBonus = $candidate['score'];

            /*
             * Bonus important pour une taille finale équilibrée.
             */
            $balanceBonus = max(
                0,
                100 - (int) floor(
                    $distanceFromIdeal / 20
                )
            );

            $validCandidates[] = [
                'position' => $absolutePosition,
                'score' =>
                    $qualityBonus
                    + $balanceBonus,
            ];
        }

        if (empty($validCandidates)) {
            return null;
        }

        /*
         * Meilleur candidat :
         * qualité narrative + équilibre.
         */
        usort(
            $validCandidates,
            function (array $a, array $b): int {

                if ($a['score'] === $b['score']) {
                    return $b['position'] <=> $a['position'];
                }

                return $b['score'] <=> $a['score'];
            }
        );

        return $validCandidates[0]['position'];
    }

    /**
     * Trouve le meilleur point de coupure dans un candidat.
     */
    private function findBestCutPosition(string $text): int
    {
        $candidates = [];

        /*
         * 1. Fin de paragraphe.
         */
        $this->addParagraphCandidates(
            $text,
            $candidates
        );

        /*
         * 2. Séparation entre répliques.
         */
        $this->addDialogueCandidates(
            $text,
            $candidates
        );

        /*
         * 3. Fin de phrase.
         */
        $this->addSentenceCandidates(
            $text,
            $candidates
        );

        /*
         * 4. Ponctuation forte.
         */
        $this->addStrongPunctuationCandidates(
            $text,
            $candidates
        );

        /*
         * 5. Virgule.
         */
        $this->addCommaCandidates(
            $text,
            $candidates
        );

        /*
         * 6. Espace.
         */
        $this->addSpaceCandidates(
            $text,
            $candidates
        );

        if (empty($candidates)) {
            return 0;
        }

        /*
         * On privilégie les candidats suffisamment éloignés
         * du début du chunk.
         */
        $preferredCandidates = array_filter(
            $candidates,
            fn (array $candidate) =>
                $candidate['position'] >=
                $this->preferredMinimumCharacters
        );

        if (!empty($preferredCandidates)) {
            $candidates = $preferredCandidates;
        }

        /*
         * Meilleure qualité narrative,
         * puis proximité de la limite maximale.
         */
        usort(
            $candidates,
            function (array $a, array $b): int {

                if ($a['score'] === $b['score']) {
                    return $b['position'] <=> $a['position'];
                }

                return $b['score'] <=> $a['score'];
            }
        );

        return $candidates[0]['position'];
    }

    /**
     * Ajoute les fins de paragraphes.
     */
    private function addParagraphCandidates(
        string $text,
        array &$candidates
    ): void {
        $offset = 0;

        while (
            ($position = mb_strpos(
                $text,
                "\n\n",
                $offset
            )) !== false
        ) {
            $this->addCandidate(
                $candidates,
                $position + 2,
                100
            );

            $offset = $position + 2;
        }
    }

    /**
     * Ajoute les séparations entre répliques.
     */
    private function addDialogueCandidates(
        string $text,
        array &$candidates
    ): void {
        if (
            preg_match_all(
                '/\n(?=\s*[-–—])/',
                $text,
                $matches,
                PREG_OFFSET_CAPTURE
            )
        ) {
            foreach ($matches[0] as $match) {

                $bytePosition = $match[1];

                $position = mb_strlen(
                    substr($text, 0, $bytePosition)
                );

                $this->addCandidate(
                    $candidates,
                    $position + 1,
                    90
                );
            }
        }
    }

    /**
     * Ajoute les fins de phrases.
     */
    private function addSentenceCandidates(
        string $text,
        array &$candidates
    ): void {
        if (
            !preg_match_all(
                '/[.!?…]+(?:["»”\')\]]+)?(?=\s|$)/u',
                $text,
                $matches,
                PREG_OFFSET_CAPTURE
            )
        ) {
            return;
        }

        foreach ($matches[0] as $match) {

            $bytePosition =
                $match[1] + strlen($match[0]);

            $position = mb_strlen(
                substr($text, 0, $bytePosition)
            );

            $this->addCandidate(
                $candidates,
                $position,
                80
            );
        }
    }

    /**
     * Ajoute les ponctuations fortes.
     */
    private function addStrongPunctuationCandidates(
        string $text,
        array &$candidates
    ): void {
        foreach ([';', ':', '—', '–'] as $punctuation) {

            $offset = 0;

            while (
                ($position = mb_strpos(
                    $text,
                    $punctuation,
                    $offset
                )) !== false
            ) {
                $this->addCandidate(
                    $candidates,
                    $position + 1,
                    60
                );

                $offset = $position + 1;
            }
        }
    }

    /**
     * Ajoute les virgules.
     */
    private function addCommaCandidates(
        string $text,
        array &$candidates
    ): void {
        $offset = 0;

        while (
            ($position = mb_strpos(
                $text,
                ',',
                $offset
            )) !== false
        ) {
            $this->addCandidate(
                $candidates,
                $position + 1,
                40
            );

            $offset = $position + 1;
        }
    }

    /**
     * Ajoute les espaces.
     */
    private function addSpaceCandidates(
        string $text,
        array &$candidates
    ): void {
        $offset = 0;

        while (
            ($position = mb_strpos(
                $text,
                ' ',
                $offset
            )) !== false
        ) {
            $this->addCandidate(
                $candidates,
                $position + 1,
                20
            );

            $offset = $position + 1;
        }
    }

    /**
     * Ajoute un candidat de coupure.
     */
    private function addCandidate(
        array &$candidates,
        int $position,
        int $baseScore
    ): void {
        if ($position <= 0) {
            return;
        }

        if ($position > $this->maxCharacters) {
            return;
        }

        /*
         * Plus la coupure est proche de 4500,
         * plus elle est intéressante.
         */
        $distanceFromLimit =
            $this->maxCharacters - $position;

        $proximityScore = max(
            0,
            30 - (int) floor(
                $distanceFromLimit / 100
            )
        );

        /*
         * Petite pénalité pour les coupures trop précoces.
         */
        $earlyPenalty = 0;

        if (
            $position <
            $this->preferredMinimumCharacters
        ) {
            $earlyPenalty = 50;
        }

        $candidates[] = [
            'position' => $position,
            'score' =>
                $baseScore
                + $proximityScore
                - $earlyPenalty,
        ];
    }
}