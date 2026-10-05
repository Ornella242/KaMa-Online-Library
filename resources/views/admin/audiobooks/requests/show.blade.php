@extends('layouts.admin')

@section('title', 'Demande d’audiobook')
@section('page-title', 'Demande d’audiobook')

@section('admin-content')

@php
    /*
    |--------------------------------------------------------------------------
    | Données de la demande
    |--------------------------------------------------------------------------
    */

    $book = $audiobookRequest->book;
    $author = $audiobookRequest->author;
    $payment = $audiobookRequest->payment;
    $audiobook = $audiobookRequest->audiobook;

    /*
    |--------------------------------------------------------------------------
    | Statut de la demande
    |--------------------------------------------------------------------------
    */

    $requestStatus = $audiobookRequest->status;

    $requestStatusLabels = [
        'pending_payment' => 'Paiement en attente',
        'paid' => 'Payée',
        'queued' => 'En attente de génération',
        'generating' => 'Génération en cours',
        'assembling' => 'Assemblage en cours',
        'completed' => 'Terminé',
        'failed' => 'Échec',
        'cancelled' => 'Annulée',
    ];

    $requestStatusLabel =
        $requestStatusLabels[$requestStatus]
        ?? ucfirst(str_replace('_', ' ', $requestStatus));

    $requestStatusClass = match ($requestStatus) {
        'pending_payment' => 'pending',
        'paid' => 'paid',
        'queued' => 'queued',
        'generating' => 'generating',
        'assembling' => 'assembling',
        'completed' => 'completed',
        'failed' => 'failed',
        'cancelled' => 'cancelled',
        default => 'pending',
    };

    /*
    |--------------------------------------------------------------------------
    | Statut de l'audiobook
    |--------------------------------------------------------------------------
    */

    $audioStatus = $audiobook?->status ?? 'pending';

    $audioStatusLabels = [
        'draft' => 'En attente',
        'queued' => 'En attente',
        'generating' => 'Génération en cours',
        'assembling' => 'Assemblage en cours',
        'completed' => 'Terminé',
        'failed' => 'Échec',
        'cancelled' => 'Annulé',
    ];

    $audioStatusLabel =
        $audioStatusLabels[$audioStatus]
        ?? ucfirst(str_replace('_', ' ', $audioStatus));

    /*
    |--------------------------------------------------------------------------
    | Statistiques
    |--------------------------------------------------------------------------
    */

    $characterCount = (int) (
        $audiobookRequest->characters
        ?? $audiobook?->total_characters
        ?? 0
    );

    $wordCount = (int) (
        $audiobookRequest->words
        ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | Progression
    |--------------------------------------------------------------------------
    */

    $totalChunks = (int) ($audiobook?->total_chunks ?? 0);
    $completedChunks = (int) ($audiobook?->completed_chunks ?? 0);

    $totalCharacters = (int) ($audiobook?->total_characters ?? 0);
    $generatedCharacters = (int) ($audiobook?->generated_characters ?? 0);

    $chunkProgress = $totalChunks > 0
        ? min(
            100,
            round(($completedChunks / $totalChunks) * 100)
        )
        : 0;

    $characterProgress = $totalCharacters > 0
        ? min(
            100,
            round(($generatedCharacters / $totalCharacters) * 100)
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Voix
    |--------------------------------------------------------------------------
    */

    $selectedVoiceName =
        $voiceNames[$audiobookRequest->voice_id]
        ?? 'Voix inconnue';

@endphp


<div class="admin-audiobook-page">

    <div class="container-fluid px-0">

        {{-- =========================================================
             BACK
        ========================================================== --}}
        <div class="mb-4">

            <a
                href="{{ route('admin.audiobooks.requests') }}"
                class="audiobook-back">

                <i class="bi bi-arrow-left"></i>

                Retour aux demandes audiobooks

            </a>

        </div>


        {{-- =========================================================
             HERO
        ========================================================== --}}
        <section class="audiobook-hero">

            <div class="row g-4 g-xl-5 align-items-center">

                {{-- COVER --}}
                <div class="col-lg-3 col-md-4 text-center">

                    <div class="audiobook-cover">

                        <img
                            src="{{ $book->cover_image
                                ? asset('storage/' . $book->cover_image)
                                : asset('assets/images/book/01.jpg') }}"
                            alt="{{ $book->title }}">

                        <div class="audiobook-cover-overlay">

                            <i class="bi bi-headphones"></i>

                        </div>

                    </div>

                </div>


                {{-- BOOK INFORMATION --}}
                <div class="col-lg-9 col-md-8">

                    <div class="audiobook-tags">

                        <span class="audiobook-tag type">

                            <i class="bi bi-headphones"></i>

                            Audiobook

                        </span>


                        @if ($book->category)

                            <span class="audiobook-tag">

                                {{ $book->category->name }}

                            </span>

                        @endif


                        @if ($book->language)

                            <span class="audiobook-tag">

                                <i class="bi bi-translate"></i>

                                {{ $book->language }}

                            </span>

                        @endif


                        <span
                            class="audiobook-tag status {{ $requestStatusClass }}">

                            {{ $requestStatusLabel }}

                        </span>

                    </div>


                    <h1 class="audiobook-title">

                        {{ $book->title }}

                    </h1>


                    <div class="audiobook-author">

                        <i class="bi bi-person-circle"></i>

                        <span>

                            {{ $author?->firstname }}
                            {{ $author?->lastname }}

                        </span>

                    </div>


                    <div class="audiobook-purpose">

                        <div class="purpose-icon">

                            <i class="bi bi-headphones"></i>

                        </div>

                        <div>

                            <strong>
                                Demande d’audiobook
                            </strong>

                            <p>

                                Cette demande a été soumise par l’auteur
                                pour transformer son livre en audiobook
                                avec ElevenLabs.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             REQUEST INFORMATION
        ========================================================== --}}
        <section class="audiobook-panel mt-4">

            <div class="audiobook-panel-header">

                <div>

                    <span class="audiobook-section-kicker">

                        ÉTAPE 01

                    </span>

                    <h2>

                        <i class="bi bi-file-earmark-check"></i>

                        Informations de la demande

                    </h2>

                    <p>

                        Informations relatives à la commande
                        d’audiobook de l’auteur.

                    </p>

                </div>


                <div class="analysis-status">

                    @if ($audiobookRequest->status === 'paid')

                        <i class="bi bi-check-circle-fill"></i>

                        Paiement confirmé

                    @elseif ($audiobookRequest->status === 'pending_payment')

                        <i class="bi bi-clock-fill"></i>

                        Paiement en attente

                    @else

                        <i class="bi bi-info-circle-fill"></i>

                        {{ $requestStatusLabel }}

                    @endif

                </div>

            </div>


            <div class="audiobook-stats-grid">

                {{-- MONTANT PAYÉ --}}
                <div class="audiobook-stat-card featured">

                    <div class="stat-icon">

                        <i class="bi bi-currency-euro"></i>

                    </div>

                    <div>

                        <span>
                            Montant payé
                        </span>

                        <strong>

                            {{ number_format(
                                (float) $audiobookRequest->total_amount,
                                2,
                                ',',
                                ' '
                            ) }}
                            €

                        </strong>

                        <small>
                            montant facturé à l’auteur
                        </small>

                    </div>

                </div>


                {{-- VOIX --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-mic"></i>

                    </div>

                    <div>

                        <span>
                            Voix sélectionnée
                        </span>

                        <strong class="text-value">

                            {{ $selectedVoiceName }}

                        </strong>

                        <small>
                            voix de narration
                        </small>

                    </div>

                </div>


                {{-- DATE --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-calendar3"></i>

                    </div>

                    <div>

                        <span>
                            Demande faite le
                        </span>

                        <strong class="text-value">

                            {{ $audiobookRequest->created_at?->format('d/m/Y') }}

                        </strong>

                        <small>

                            à
                            {{ $audiobookRequest->created_at?->format('H:i') }}

                        </small>

                    </div>

                </div>


                {{-- PAIEMENT --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-credit-card"></i>

                    </div>

                    <div>

                        <span>
                            Paiement
                        </span>

                        <strong class="text-value">

                            {{ $payment?->status === 'success'
                                ? 'Payé'
                                : 'En attente' }}

                        </strong>

                        <small>

                            {{ $payment?->payment_method
                                ? ucfirst($payment->payment_method)
                                : '—' }}

                        </small>

                    </div>

                </div>

            </div>


            {{-- PAYMENT DETAILS --}}
            @if ($payment)

                <div class="audiobook-cost-card">

                    <div class="cost-icon">

                        <i class="bi bi-receipt"></i>

                    </div>


                    <div class="cost-content">

                        <span>
                            RÉFÉRENCE DU PAIEMENT
                        </span>

                        <strong style="font-size: 16px;">

                            {{ $payment->reference }}

                        </strong>

                        <p>

                            Paiement enregistré le
                            {{ $payment->created_at?->format('d/m/Y à H:i') }}.

                        </p>

                    </div>


                    <div class="cost-model">

                        <span>
                            Modèle
                        </span>

                        <strong>
                            Eleven v3
                        </strong>

                    </div>

                </div>

            @endif

        </section>


        {{-- =========================================================
             CONTENT ANALYSIS
        ========================================================== --}}
        <section class="audiobook-panel mt-4">

            <div class="audiobook-panel-header">

                <div>

                    <span class="audiobook-section-kicker">

                        ANALYSE

                    </span>

                    <h2>

                        <i class="bi bi-file-earmark-text"></i>

                        Contenu à convertir

                    </h2>

                    <p>

                        Résumé du contenu qui sera transformé
                        en audiobook.

                    </p>

                </div>


                <div class="analysis-status">

                    <i class="bi bi-check-circle-fill"></i>

                    Analyse disponible

                </div>

            </div>


            <div class="audiobook-stats-grid">

                {{-- CHARACTERS --}}
                <div class="audiobook-stat-card featured">

                    <div class="stat-icon">

                        <i class="bi bi-fonts"></i>

                    </div>

                    <div>

                        <span>
                            Caractères
                        </span>

                        <strong>

                            {{ number_format(
                                $characterCount,
                                0,
                                ',',
                                ' '
                            ) }}

                        </strong>

                        <small>
                            caractères à convertir
                        </small>

                    </div>

                </div>


                {{-- WORDS --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-chat-left-text"></i>

                    </div>

                    <div>

                        <span>
                            Mots
                        </span>

                        <strong>

                            {{ number_format(
                                $wordCount,
                                0,
                                ',',
                                ' '
                            ) }}

                        </strong>

                        <small>
                            mots détectés
                        </small>

                    </div>

                </div>


                {{-- CHAPTERS --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-list-ol"></i>

                    </div>

                    <div>

                        <span>
                            Chunks
                        </span>

                        <strong>

                            {{ number_format(
                                $totalChunks,
                                0,
                                ',',
                                ' '
                            ) }}

                        </strong>

                        <small>
                            passages à générer
                        </small>

                    </div>

                </div>


                {{-- LANGUAGE --}}
                <div class="audiobook-stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-translate"></i>

                    </div>

                    <div>

                        <span>
                            Langue
                        </span>

                        <strong class="text-value">

                            {{ $book->language ?: '—' }}

                        </strong>

                        <small>
                            langue du livre
                        </small>

                    </div>

                </div>

            </div>


            {{-- COST BREAKDOWN --}}
            <div class="audiobook-cost-card">

                <div class="cost-icon">

                    <i class="bi bi-calculator"></i>

                </div>


                <div class="cost-content">

                    <span>
                        COÛT ELEVENLABS
                    </span>

                    <strong>

                        {{ number_format(
                            (float) $audiobookRequest->elevenlabs_cost,
                            2,
                            ',',
                            ' '
                        ) }}
                        €

                    </strong>

                    <p>

                        Coût estimé de la génération
                        selon le volume de caractères.

                    </p>

                </div>


                <div class="cost-model">

                    <span>
                        Marge KaMa
                    </span>

                    <strong>

                        {{ number_format(
                            (float) $audiobookRequest->kama_fee,
                            2,
                            ',',
                            ' '
                        ) }}
                        €

                    </strong>

                </div>

            </div>

        </section>


        {{-- =========================================================
             GENERATION
        ========================================================== --}}
        <section class="audiobook-panel mt-4">

            <div class="audiobook-panel-header">

                <div>

                    <span class="audiobook-section-kicker">

                        ÉTAPE 02

                    </span>

                    <h2>

                        <i class="bi bi-sliders"></i>

                        Génération

                    </h2>

                    <p>

                        La génération sera effectuée avec
                        la voix sélectionnée lors de la commande.

                    </p>

                </div>

            </div>


            {{-- SELECTED VOICE --}}
            <div class="row g-4">

                <div class="col-lg-12">

                    <div class="audiobook-config-card">

                        <div class="audiobook-field-header">

                            <div>

                                <label class="audiobook-field-label pb-1">

                                    <i
                                        class="bi bi-mic"
                                        style="color: #b30000;">
                                    </i>

                                    <strong>
                                        Voix sélectionnée
                                    </strong>

                                </label>

                            </div>

                        </div>


                        <div class="audiobook-voice-selected">

                            <div class="audiobook-voice-details-header">

                                <div class="audiobook-voice-details-icon">

                                    <i class="bi bi-mic-fill"></i>

                                </div>

                                <div>

                                    <strong>
                                        {{ $selectedVoiceName }}
                                    </strong>

                                    <span>
                                        Voix de narration
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- GENERATION INFO --}}
            <div class="audiobook-generation-info">

                <div class="generation-info-icon">

                    <i class="bi bi-info-circle"></i>

                </div>

                <div>

                    <strong>
                        Génération chapitre par chapitre
                    </strong>

                    <p>

                        Chaque passage sera généré séparément
                        avec ElevenLabs v3. En cas d’échec,
                        seul le passage concerné pourra être
                        relancé.

                    </p>

                </div>

            </div>


            {{-- GENERATE BUTTON --}}
            <div class="audiobook-generate-wrapper">

                @if ($requestStatus === 'paid')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.audiobooks.requests.generate',
                            $audiobookRequest
                        ) }}">

                        @csrf

                        <button
                            type="submit"
                            class="audiobook-generate-btn">

                            <i class="bi bi-headphones"></i>

                            <span>
                                Générer l’audiobook
                            </span>

                        </button>

                    </form>


                    <small>

                        Le paiement a été confirmé.
                        Vous pouvez lancer la génération.

                    </small>


                @elseif (
                    in_array(
                        $requestStatus,
                        [
                            'queued',
                            'generating',
                            'assembling'
                        ]
                    )
                )

                    <button
                        type="button"
                        class="audiobook-generate-btn"
                        disabled>

                        <i class="bi bi-arrow-repeat"></i>

                        <span>
                            Génération en cours
                        </span>

                    </button>

                    <small>

                        La production de l’audiobook est actuellement
                        en cours.

                    </small>


                @elseif ($requestStatus === 'completed')

                    <button
                        type="button"
                        class="audiobook-generate-btn"
                        disabled>

                        <i class="bi bi-check-circle"></i>

                        <span>
                            Audiobook terminé
                        </span>

                    </button>


                @elseif ($requestStatus === 'failed')

                    <button
                        type="button"
                        class="audiobook-generate-btn"
                        disabled>

                        <i class="bi bi-exclamation-circle"></i>

                        <span>
                            Génération échouée
                        </span>

                    </button>


                @else

                    <button
                        type="button"
                        class="audiobook-generate-btn"
                        disabled>

                        <i class="bi bi-lock"></i>

                        <span>
                            Paiement requis
                        </span>

                    </button>

                    <small>

                        La génération sera disponible une fois
                        le paiement confirmé.

                    </small>

                @endif

            </div>

        </section>


        {{-- =========================================================
             GENERATION PROGRESS
        ========================================================== --}}
        <section class="audiobook-panel mt-4">

            @if ($audiobook)

                <div
                    id="audiobook-progress"
                    data-status-url="{{ route(
                        'admin.audiobooks.status',
                        $audiobook
                    ) }}">

            @endif


            <div class="audiobook-panel-header">

                <div>

                    <span class="audiobook-section-kicker">

                        ÉTAPE 03

                    </span>

                    <h2>

                        <i class="bi bi-activity"></i>

                        Progression

                    </h2>

                    <p>

                        Suivi de la génération de l’audiobook.

                    </p>

                </div>


                <span
                    id="audiobook-generation-status"
                    class="generation-status {{ $audioStatus }}">

                    {{ $audioStatusLabel }}

                </span>

            </div>


            @if (!$audiobook)

                <div class="audiobook-progress-empty">

                    <div class="empty-progress-icon">

                        <i class="bi bi-headphones"></i>

                    </div>

                    <h3>
                        Génération non lancée
                    </h3>

                    <p>

                        La progression apparaîtra ici une fois
                        que l’administrateur aura lancé la génération.

                    </p>

                </div>


            @else

                <div class="audiobook-progress-content">

                    {{-- STATISTICS --}}
                    <div class="audiobook-progress-stats">

                        <div class="audiobook-progress-stat">

                            <span class="progress-stat-label">

                                Chunks générés

                            </span>

                            <strong id="audiobook-completed-chunks">

                                {{ number_format(
                                    $completedChunks,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                /

                                {{ number_format(
                                    $totalChunks,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                            </strong>

                        </div>


                        <div class="audiobook-progress-stat">

                            <span class="progress-stat-label">

                                Caractères générés

                            </span>

                            <strong id="audiobook-generated-characters">

                                {{ number_format(
                                    $generatedCharacters,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                /

                                {{ number_format(
                                    $totalCharacters,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                            </strong>

                        </div>

                    </div>


                    {{-- CHUNK PROGRESS --}}
                    <div class="audiobook-progress-block">

                        <div class="audiobook-progress-heading">

                            <span>
                                Progression des passages
                            </span>

                            <strong id="audiobook-chunk-progress-value">

                                {{ $chunkProgress }}%

                            </strong>

                        </div>


                        <div class="audiobook-progress-track">

                            <div
                                id="audiobook-chunk-progress-bar"
                                class="audiobook-progress-bar"
                                style="width: {{ $chunkProgress }}%;">

                            </div>

                        </div>

                    </div>


                    {{-- CHARACTER PROGRESS --}}
                    <div class="audiobook-progress-block">

                        <div class="audiobook-progress-heading">

                            <span>
                                Progression du texte
                            </span>

                            <strong id="audiobook-character-progress-value">

                                {{ $characterProgress }}%

                            </strong>

                        </div>


                        <div class="audiobook-progress-track">

                            <div
                                id="audiobook-character-progress-bar"
                                class="audiobook-progress-bar"
                                style="width: {{ $characterProgress }}%;">

                            </div>

                        </div>

                    </div>


                    {{-- STATUS MESSAGE --}}
                    @if ($audioStatus === 'draft' || $audioStatus === 'queued')

                        <div class="audiobook-progress-message">

                            <i class="bi bi-hourglass-split"></i>

                            <span>

                                L’audiobook est prêt à être généré.

                            </span>

                        </div>


                    @elseif ($audioStatus === 'generating')

                        <div class="audiobook-progress-message">

                            <i class="bi bi-arrow-repeat"></i>

                            <span>

                                La génération de l’audiobook
                                est en cours.

                            </span>

                        </div>


                    @elseif ($audioStatus === 'assembling')

                        <div class="audiobook-progress-message">

                            <i class="bi bi-soundwave"></i>

                            <span>

                                Tous les passages ont été générés.
                                L’assemblage du fichier audio final
                                est en cours.

                            </span>

                        </div>


                    @elseif ($audioStatus === 'completed')

                        <div class="audiobook-progress-message success">

                            <i class="bi bi-check-circle"></i>

                            <span>

                                L’audiobook est prêt à être écouté.

                            </span>

                        </div>


                    @elseif ($audioStatus === 'failed')

                        <div class="audiobook-progress-message error">

                            <i class="bi bi-exclamation-circle"></i>

                            <span>

                                La génération de l’audiobook
                                a rencontré une erreur.

                            </span>

                        </div>


                    @elseif ($audioStatus === 'cancelled')

                        <div class="audiobook-progress-message error">

                            <i class="bi bi-x-circle"></i>

                            <span>

                                La génération de l’audiobook
                                a été annulée.

                            </span>

                        </div>

                    @endif

                </div>

            @endif


            @if ($audiobook)

                </div>

            @endif

        </section>

{{-- =========================================================
     FINAL AUDIOBOOK
========================================================== --}}
<section class="audiobook-panel audiobook-final-panel mt-4">

    <div class="audiobook-panel-header">

        <div>

            <span class="audiobook-section-kicker">
                ÉTAPE 04
            </span>

            <h2>
                <i class="bi bi-music-note-beamed"></i>
                Audiobook final
            </h2>

            <p>
                Écoutez, téléchargez et publiez l’audiobook
                une fois sa génération terminée.
            </p>

        </div>

        @if (
            $audiobook &&
            $audiobook->status === 'completed' &&
            $audiobook->final_audio_path
        )

            @if ($audiobookRequest->published_book_id)

                <span class="audiobook-final-status published">

                    <i class="bi bi-check-circle-fill"></i>

                    Publié sur KaMa

                </span>

            @else

                <span class="audiobook-final-status ready">

                    <i class="bi bi-check-circle-fill"></i>

                    Prêt à publier

                </span>

            @endif

        @endif

    </div>


    {{-- =====================================================
         AUDIOBOOK COMPLETED
    ====================================================== --}}
    @if (
        $audiobook &&
        $audiobook->status === 'completed' &&
        $audiobook->final_audio_path
    )

        <div class="audiobook-final-result">

            {{-- Hero --}}
            <div class="audiobook-final-hero">

                <div class="audiobook-final-icon">

                    <i class="bi bi-headphones"></i>

                </div>

                <div class="audiobook-final-heading">

                    @if ($audiobookRequest->published_book_id)

                        <span class="audiobook-final-eyebrow">
                            AUDIOBOOK PUBLIÉ
                        </span>

                        <h3>
                            L’audiobook est disponible sur KaMa
                        </h3>

                        <p>
                            La génération est terminée et cet
                            audiobook est maintenant disponible
                            comme livre audio sur la plateforme.
                        </p>

                    @else

                        <span class="audiobook-final-eyebrow">
                            GÉNÉRATION TERMINÉE
                        </span>

                        <h3>
                            Audiobook prêt
                        </h3>

                        <p>
                            Le fichier audio final a été généré
                            avec succès. Vous pouvez vérifier
                            l’audio avant de le publier sur KaMa.
                        </p>

                    @endif

                </div>

            </div>


            {{-- Informations --}}
            <div class="audiobook-final-meta">

                <div class="audiobook-final-meta-item">

                    <span>
                        <i class="bi bi-file-earmark-music"></i>
                        Fichier
                    </span>

                    <strong>
                        {{ basename($audiobook->final_audio_path) }}
                    </strong>

                </div>


                @if ($audiobook->generated_characters)

                    <div class="audiobook-final-meta-item">

                        <span>
                            <i class="bi bi-text-paragraph"></i>
                            Caractères générés
                        </span>

                        <strong>
                            {{ number_format(
                                $audiobook->generated_characters,
                                0,
                                ',',
                                ' '
                            ) }}
                        </strong>

                    </div>

                @endif


                @if ($audiobook->completed_at)

                    <div class="audiobook-final-meta-item">

                        <span>
                            <i class="bi bi-calendar-check"></i>
                            Généré le
                        </span>

                        <strong>
                            {{ $audiobook->completed_at->format('d/m/Y à H:i') }}
                        </strong>

                    </div>

                @endif

            </div>


            {{-- Lecteur --}}
            <div class="audiobook-final-player-card">

                <div class="audiobook-player-heading">

                    <div class="audiobook-player-icon">

                        <i class="bi bi-play-fill"></i>

                    </div>

                    <div>

                        <strong>
                            Écouter l’audiobook
                        </strong>

                        <span>
                            Vérifiez le fichier audio avant publication.
                        </span>

                    </div>

                </div>


                <audio
                    controls
                    preload="metadata"
                    src="{{ route(
                        'admin.audiobooks.stream',
                        $audiobook
                    ) }}"
                    class="audiobook-player">
                </audio>

            </div>


            {{-- Actions --}}
            <div class="audiobook-final-actions">

                <a
                    href="{{ route(
                        'admin.audiobooks.download',
                        $audiobook
                    ) }}"
                    class="audiobook-secondary-action">

                    <i class="bi bi-download"></i>

                    Télécharger

                </a>


                @if ($audiobookRequest->published_book_id)

                    @if ($audiobookRequest->publishedBook)

                        <a
                            href="{{ route(
                                'admin.books.show',
                                $audiobookRequest->publishedBook
                            ) }}"
                            class="audiobook-published-action">

                            <i class="bi bi-box-arrow-up-right"></i>

                            Voir le livre audio

                        </a>

                    @endif

                @else

                   <button
                        type="button"
                        class="audiobook-publish-action"
                        data-bs-toggle="modal"
                        data-bs-target="#publishAudiobookModal"
                    >
                        <i class="bi bi-cloud-arrow-up"></i>
                        Publier sur KaMa
                    </button>

                @endif

            </div>


            {{-- Publication notice --}}
            @if ($audiobookRequest->published_book_id)

                <div class="audiobook-publication-success">

                    <div class="audiobook-publication-success-icon">

                        <i class="bi bi-check-lg"></i>

                    </div>

                    <div>

                        <strong>
                            Audiobook publié avec succès
                        </strong>

                        <p>
                            Le livre audio a été créé comme un
                            nouveau contenu KaMa. Son prix peut
                            maintenant être modifié depuis
                            l’espace auteur.
                        </p>

                    </div>

                </div>

            @else

                <div class="audiobook-publication-notice">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        La publication créera un nouveau livre
                        audio à partir des informations de
                        l’ebook original.
                    </span>

                </div>

            @endif

        </div>


    {{-- =====================================================
         ASSEMBLING
    ====================================================== --}}
    @elseif (
        $audiobook &&
        $audiobook->status === 'assembling'
    )

        <div class="audiobook-final-empty assembling">

            <div class="audiobook-final-state-icon">

                <i class="bi bi-soundwave"></i>

            </div>

            <span class="audiobook-state-label">
                DERNIÈRE ÉTAPE
            </span>

            <h3>
                Assemblage de l’audiobook
            </h3>

            <p>
                Tous les passages ont été générés.
                Le fichier audio final est actuellement
                en cours d’assemblage.
            </p>

            <div class="audiobook-loading-line">
                <span></span>
            </div>

        </div>


    {{-- =====================================================
         GENERATING
    ====================================================== --}}
    @elseif (
        $audiobook &&
        $audiobook->status === 'generating'
    )

        <div class="audiobook-final-empty generating">

            <div class="audiobook-final-state-icon">

                <i class="bi bi-hourglass-split"></i>

            </div>

            <span class="audiobook-state-label">
                PRODUCTION EN COURS
            </span>

            <h3>
                Audiobook en cours de génération
            </h3>

            <p>
                ElevenLabs génère actuellement les différents
                passages audio. Le résultat final apparaîtra ici
                automatiquement.
            </p>

            <div class="audiobook-loading-line">
                <span></span>
            </div>

        </div>


    {{-- =====================================================
         FAILED
    ====================================================== --}}
    @elseif (
        $audiobook &&
        $audiobook->status === 'failed'
    )

        <div class="audiobook-final-empty error">

            <div class="audiobook-final-state-icon">

                <i class="bi bi-exclamation-triangle"></i>

            </div>

            <span class="audiobook-state-label">
                ERREUR DE GÉNÉRATION
            </span>

            <h3>
                La génération a échoué
            </h3>

            <p>
                {{ $audiobook->error_message
                    ?: 'Une erreur est survenue pendant la génération de l’audiobook.'
                }}
            </p>

        </div>


    {{-- =====================================================
         DEFAULT
    ====================================================== --}}
    @else

        <div class="audiobook-final-empty">

            <div class="audiobook-final-state-icon">

                <i class="bi bi-music-note-list"></i>

            </div>

            <span class="audiobook-state-label">
                EN ATTENTE
            </span>

            <h3>
                Aucun audiobook disponible
            </h3>

            <p>
                Lancez la génération pour créer l’audiobook
                final. Une fois terminé, vous pourrez le vérifier
                puis le publier sur KaMa.
            </p>

        </div>

    @endif

</section>

    </div>

</div>


{{-- =========================================================
     MODAL — PUBLICATION AUDIOBOOK
========================================================== --}}
<div
    class="modal fade audiobook-publish-modal"
    id="publishAudiobookModal"
    tabindex="-1"
    aria-labelledby="publishAudiobookModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            {{-- Close --}}
            <button
                type="button"
                class="audiobook-modal-close"
                data-bs-dismiss="modal"
                aria-label="Fermer"
            >
                <i class="bi bi-x-lg"></i>
            </button>


            <div class="modal-body">

                {{-- Icon --}}
                <div class="audiobook-modal-icon">

                    <i class="bi bi-cloud-arrow-up"></i>

                </div>


                {{-- Content --}}
                <div class="audiobook-modal-content">

                    <span class="audiobook-modal-kicker">
                        PUBLICATION AUDIOBOOK
                    </span>

                    <h3 id="publishAudiobookModalLabel">
                        Publier l’audiobook ?
                    </h3>

                    <p>
                        L’audiobook sera publié sur KaMa comme
                        un nouveau livre audio à partir des
                        informations de l’ebook original.
                    </p>

                </div>


                {{-- Summary --}}
                <div class="audiobook-modal-summary">

                    <div class="audiobook-modal-summary-item">

                        <div class="audiobook-modal-summary-icon">
                            <i class="bi bi-book"></i>
                        </div>

                        <div>

                            <span>
                                Livre
                            </span>

                            <strong>
                                {{ $audiobookRequest->book->title }}
                            </strong>

                        </div>

                    </div>


                    <div class="audiobook-modal-summary-item">

                        <div class="audiobook-modal-summary-icon">
                            <i class="bi bi-currency-euro"></i>
                        </div>

                        <div>

                            <span>
                                Prix initial
                            </span>

                            <strong>
                                {{ number_format(
                                    $audiobookRequest->book->price,
                                    2,
                                    ',',
                                    ' '
                                ) }} €
                            </strong>

                        </div>

                    </div>


                    <div class="audiobook-modal-summary-item">

                        <div class="audiobook-modal-summary-icon">
                            <i class="bi bi-file-earmark-music"></i>
                        </div>

                        <div>

                            <span>
                                Format
                            </span>

                            <strong>
                                Audiobook MP3
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Information --}}
                <div class="audiobook-modal-notice">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Le prix de l’audiobook sera initialement
                        identique à celui de l’ebook. L’auteur
                        pourra ensuite le modifier depuis son
                        espace auteur.
                    </span>

                </div>


                {{-- Actions --}}
                <div class="audiobook-modal-actions">

                    <button
                        type="button"
                        class="audiobook-modal-cancel"
                        data-bs-dismiss="modal"
                    >
                        Annuler
                    </button>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.audiobooks.requests.publish',
                            $audiobookRequest
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="audiobook-modal-confirm"
                        >

                            <i class="bi bi-cloud-arrow-up"></i>

                            Publier l’audiobook

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>

@push('scripts')
    {{-- =========================================================
     PROGRESS POLLING
========================================================= --}}
@if ($audiobook)

<script>

document.addEventListener('DOMContentLoaded', function () {

    const progressContainer =
        document.getElementById('audiobook-progress');

    if (!progressContainer) {
        return;
    }

    const statusUrl =
        progressContainer.dataset.statusUrl;

    if (!statusUrl) {
        return;
    }

    const statusElement =
        document.getElementById(
            'audiobook-generation-status'
        );

    const completedChunksElement =
        document.getElementById(
            'audiobook-completed-chunks'
        );

    const generatedCharactersElement =
        document.getElementById(
            'audiobook-generated-characters'
        );

    const chunkProgressValue =
        document.getElementById(
            'audiobook-chunk-progress-value'
        );

    const chunkProgressBar =
        document.getElementById(
            'audiobook-chunk-progress-bar'
        );

    const characterProgressValue =
        document.getElementById(
            'audiobook-character-progress-value'
        );

    const characterProgressBar =
        document.getElementById(
            'audiobook-character-progress-bar'
        );

    let pollingInterval = null;

    let pollingStopped = false;


    function formatNumber(number) {

        return new Intl.NumberFormat('fr-FR')
            .format(number);

    }


    function getStatusLabel(status) {

        const labels = {
            draft: 'En attente',
            queued: 'En attente',
            generating: 'Génération en cours',
            assembling: 'Assemblage en cours',
            completed: 'Terminé',
            failed: 'Échec',
            cancelled: 'Annulé'
        };

        return labels[status] || status;

    }


    function stopPolling() {

        if (pollingStopped) {
            return;
        }

        pollingStopped = true;

        if (pollingInterval) {

            clearInterval(
                pollingInterval
            );

            pollingInterval = null;

        }

    }


    async function updateStatus() {

        if (pollingStopped) {
            return;
        }

        try {

            const response =
                await fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            if (!data.success) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | COUNTERS
            |--------------------------------------------------------------------------
            */

            if (completedChunksElement) {

                completedChunksElement.textContent =
                    formatNumber(data.completed_chunks)
                    + ' / '
                    + formatNumber(data.total_chunks);

            }


            if (generatedCharactersElement) {

                generatedCharactersElement.textContent =
                    formatNumber(data.generated_characters)
                    + ' / '
                    + formatNumber(data.total_characters);

            }


            /*
            |--------------------------------------------------------------------------
            | CHUNK PROGRESS
            |--------------------------------------------------------------------------
            */

            if (chunkProgressValue) {

                chunkProgressValue.textContent =
                    data.chunk_progress + '%';

            }


            if (chunkProgressBar) {

                chunkProgressBar.style.width =
                    data.chunk_progress + '%';

            }


            /*
            |--------------------------------------------------------------------------
            | CHARACTER PROGRESS
            |--------------------------------------------------------------------------
            */

            if (characterProgressValue) {

                characterProgressValue.textContent =
                    data.character_progress + '%';

            }


            if (characterProgressBar) {

                characterProgressBar.style.width =
                    data.character_progress + '%';

            }


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if (statusElement) {

                statusElement.textContent =
                    getStatusLabel(data.status);

                statusElement.className =
                    'generation-status ' + data.status;

            }


            /*
            |--------------------------------------------------------------------------
            | STOP POLLING
            |--------------------------------------------------------------------------
            */

            if (
                data.status === 'completed'
                || data.status === 'failed'
                || data.status === 'cancelled'
            ) {

                stopPolling();

            }

        } catch (error) {

            console.error(
                'Erreur lors de la récupération du statut audiobook:',
                error
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL UPDATE
    |--------------------------------------------------------------------------
    */

    updateStatus();


    /*
    |--------------------------------------------------------------------------
    | POLLING
    |--------------------------------------------------------------------------
    */

    pollingInterval =
        setInterval(
            updateStatus,
            3000
        );

});

</script>

@endif
@endpush



@push('styles')
    <style>

    /* =========================================================
       ADMIN AUDIOBOOK PAGE
    ========================================================== */

    .admin-audiobook-page {
        color: #171717;
        padding-bottom: 50px;
    }


    /* =========================================================
       BACK
    ========================================================== */

   .audiobook-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 999px;
        border: 1px solid #ddd;
        color: #222;
        text-decoration: none;
        font-weight: 600;
        background: #fff;
    }

    .audiobook-back:hover {
        color: #b30000;
    }

    .audiobook-back i {
        font-size: 16px;
    }


    /* =========================================================
       HERO
    ========================================================== */

    .audiobook-hero {
        position: relative;
        overflow: hidden;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 22px;
        padding: 32px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .04);
    }


    .audiobook-cover {
        position: relative;
        width: 100%;
        max-width: 220px;
        aspect-ratio: 2 / 3;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 14px;
        background: #f4f4f4;
        box-shadow: 0 15px 35px rgba(0, 0, 0, .14);
    }

    .audiobook-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .audiobook-cover-overlay {
        position: absolute;
        right: 14px;
        bottom: 14px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #b30000;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(179, 0, 0, .28);
    }

    .audiobook-cover-overlay i {
        font-size: 20px;
    }


    /* =========================================================
       TAGS
    ========================================================== */

    .audiobook-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
    }

    .audiobook-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #f5f5f5;
        color: #555;
        font-size: 12px;
        font-weight: 700;
    }

    .audiobook-tag.type {
        background: rgba(179, 0, 0, .08);
        color: #b30000;
    }

    .audiobook-tag.status {
        background: #f3f3f3;
        color: #555;
    }

    .audiobook-tag.status.paid {
        background: #eaf8ef;
        color: #218739;
    }

    .audiobook-tag.status.queued {
        background: #fff7e6;
        color: #9a6700;
    }

    .audiobook-tag.status.generating {
        background: #fff3e8;
        color: #b65c00;
    }

    .audiobook-tag.status.assembling {
        background: #f1edff;
        color: #6845c5;
    }

    .audiobook-tag.status.completed {
        background: #eaf8ef;
        color: #218739;
    }

    .audiobook-tag.status.failed,
    .audiobook-tag.status.cancelled {
        background: #fff0f0;
        color: #c62828;
    }

    .audiobook-tag.status.pending {
        background: #f5f5f5;
        color: #777;
    }


    /* =========================================================
       HERO TEXT
    ========================================================== */

    .audiobook-title {
        margin: 0 0 12px;
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.6px;
        color: #171717;
    }

    .audiobook-author {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 24px;
    }

    .audiobook-author i {
        color: #b30000;
        font-size: 18px;
    }


    /* =========================================================
       PURPOSE
    ========================================================== */

    .audiobook-purpose {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        max-width: 760px;
        padding: 17px 18px;
        background: #fafafa;
        border: 1px solid #ededed;
        border-radius: 14px;
    }

    .purpose-icon {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(179, 0, 0, .08);
        color: #b30000;
    }

    .purpose-icon i {
        font-size: 19px;
    }

    .audiobook-purpose strong {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
        font-weight: 800;
    }

    .audiobook-purpose p {
        margin: 0;
        color: #707070;
        font-size: 13px;
        line-height: 1.6;
    }


    /* =========================================================
       PANELS
    ========================================================== */

    .audiobook-panel {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 7px 26px rgba(0, 0, 0, .035);
    }

    .audiobook-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }

    .audiobook-section-kicker {
        display: block;
        margin-bottom: 6px;
        color: #b30000;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .audiobook-panel-header h2 {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 7px;
        font-size: 21px;
        font-weight: 800;
        color: #171717;
    }

    .audiobook-panel-header h2 i {
        color: #b30000;
    }

    .audiobook-panel-header p {
        margin: 0;
        color: #777;
        font-size: 13px;
        line-height: 1.5;
    }


    /* =========================================================
       ANALYSIS STATUS
    ========================================================== */

    .analysis-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eaf8ef;
        color: #218739;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }


    /* =========================================================
       STATS
    ========================================================== */

    .audiobook-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
    }

    .audiobook-stat-card {
        min-height: 125px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 19px;
        border: 1px solid #ededed;
        border-radius: 15px;
        background: #fff;
    }

    .audiobook-stat-card.featured {
        border-color: rgba(179, 0, 0, .15);
        background: linear-gradient(
            135deg,
            rgba(179, 0, 0, .035),
            #fff
        );
    }

    .stat-icon {
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(179, 0, 0, .08);
        color: #b30000;
    }

    .stat-icon i {
        font-size: 18px;
    }

    .audiobook-stat-card span {
        display: block;
        margin-bottom: 5px;
        color: #777;
        font-size: 12px;
        font-weight: 600;
    }

    .audiobook-stat-card strong {
        display: block;
        color: #171717;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
    }

    .audiobook-stat-card strong.text-value {
        font-size: 17px;
    }

    .audiobook-stat-card small {
        display: block;
        margin-top: 5px;
        color: #999;
        font-size: 11px;
    }


    /* =========================================================
       COST CARD
    ========================================================== */

    .audiobook-cost-card {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 18px;
        padding: 18px 20px;
        border: 1px solid #ededed;
        border-radius: 15px;
        background: #fafafa;
    }

    .cost-icon {
        flex: 0 0 45px;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #171717;
        color: #fff;
    }

    .cost-icon i {
        font-size: 18px;
    }

    .cost-content {
        flex: 1;
    }

    .cost-content > span {
        display: block;
        margin-bottom: 4px;
        color: #999;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .cost-content strong {
        display: block;
        color: #171717;
        font-size: 23px;
        font-weight: 800;
    }

    .cost-content p {
        margin: 4px 0 0;
        color: #777;
        font-size: 12px;
    }

    .cost-model {
        padding-left: 25px;
        border-left: 1px solid #ddd;
        min-width: 130px;
    }

    .cost-model span {
        display: block;
        color: #999;
        font-size: 11px;
        margin-bottom: 4px;
    }

    .cost-model strong {
        color: #171717;
        font-size: 14px;
        font-weight: 800;
    }


    /* =========================================================
       CONFIGURATION
    ========================================================== */

    .audiobook-config-card {
        padding: 20px;
        border: 1px solid #ededed;
        border-radius: 15px;
        background: #fff;
    }

    .audiobook-field-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .audiobook-field-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        font-size: 14px;
        color: #171717;
    }


    /* =========================================================
       SELECTED VOICE
    ========================================================== */

    .audiobook-voice-selected {
        padding: 17px;
        border: 1px solid rgba(179, 0, 0, .14);
        border-radius: 14px;
        background: rgba(179, 0, 0, .025);
    }

    .audiobook-voice-details-header {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .audiobook-voice-details-icon {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(179, 0, 0, .09);
        color: #b30000;
    }

    .audiobook-voice-details-icon i {
        font-size: 19px;
    }

    .audiobook-voice-details-header strong {
        display: block;
        color: #171717;
        font-size: 15px;
        font-weight: 800;
    }

    .audiobook-voice-details-header span {
        display: block;
        margin-top: 3px;
        color: #888;
        font-size: 12px;
    }


    /* =========================================================
       GENERATION INFO
    ========================================================== */

    .audiobook-generation-info {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-top: 20px;
        padding: 16px 18px;
        border-radius: 14px;
        background: #fafafa;
        border: 1px solid #ededed;
    }

    .generation-info-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f1f1f1;
        color: #555;
    }

    .audiobook-generation-info strong {
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
        font-weight: 800;
    }

    .audiobook-generation-info p {
        margin: 0;
        color: #777;
        font-size: 12px;
        line-height: 1.6;
    }


    /* =========================================================
       GENERATE BUTTON
    ========================================================== */

    .audiobook-generate-wrapper {
        margin-top: 25px;
        text-align: center;
    }

    .audiobook-generate-btn {
        min-width: 250px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 13px 24px;
        border: 0;
        border-radius: 11px;
        background: #b30000;
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(179, 0, 0, .18);
        transition: all .2s ease;
    }

    .audiobook-generate-btn:hover:not(:disabled) {
        background: #950000;
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(179, 0, 0, .23);
    }

    .audiobook-generate-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        box-shadow: none;
    }

    .audiobook-generate-btn i {
        font-size: 17px;
    }

    .audiobook-generate-wrapper small {
        display: block;
        margin-top: 10px;
        color: #999;
        font-size: 11px;
    }


    /* =========================================================
       PROGRESS
    ========================================================== */

    .generation-status {
        display: inline-flex;
        align-items: center;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .generation-status.draft,
    .generation-status.queued {
        background: #fff7e6;
        color: #9a6700;
    }

    .generation-status.generating {
        background: #fff3e8;
        color: #b65c00;
    }

    .generation-status.assembling {
        background: #f1edff;
        color: #6845c5;
    }

    .generation-status.completed {
        background: #eaf8ef;
        color: #218739;
    }

    .generation-status.failed,
    .generation-status.cancelled {
        background: #fff0f0;
        color: #c62828;
    }


    .audiobook-progress-content {
        margin-top: 5px;
    }

    .audiobook-progress-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 28px;
    }

    .audiobook-progress-stat {
        padding: 17px;
        border: 1px solid #ededed;
        border-radius: 14px;
        background: #fafafa;
    }

    .progress-stat-label {
        display: block;
        margin-bottom: 6px;
        color: #888;
        font-size: 12px;
        font-weight: 600;
    }

    .audiobook-progress-stat strong {
        color: #171717;
        font-size: 20px;
        font-weight: 800;
    }


    .audiobook-progress-block {
        margin-bottom: 23px;
    }

    .audiobook-progress-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 9px;
        font-size: 12px;
        color: #666;
    }

    .audiobook-progress-heading strong {
        color: #b30000;
        font-weight: 800;
    }

    .audiobook-progress-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        border-radius: 999px;
        background: #eeeeee;
    }

    .audiobook-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: #b30000;
        transition: width .5s ease;
    }


    /* =========================================================
       PROGRESS MESSAGE
    ========================================================== */

    .audiobook-progress-message {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding: 13px 15px;
        border-radius: 12px;
        background: #fafafa;
        border: 1px solid #ededed;
        color: #666;
        font-size: 12px;
    }

    .audiobook-progress-message i {
        color: #b30000;
        font-size: 17px;
    }

    .audiobook-progress-message.success {
        background: #eaf8ef;
        border-color: #d5efdc;
        color: #27743a;
    }

    .audiobook-progress-message.success i {
        color: #218739;
    }

    .audiobook-progress-message.error {
        background: #fff0f0;
        border-color: #f4d4d4;
        color: #a52a2a;
    }

    .audiobook-progress-message.error i {
        color: #c62828;
    }


    /* =========================================================
       EMPTY PROGRESS
    ========================================================== */

    .audiobook-progress-empty {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-progress-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f7f7f7;
        color: #aaa;
    }

    .empty-progress-icon i {
        font-size: 25px;
    }

    .audiobook-progress-empty h3 {
        margin: 0 0 7px;
        font-size: 17px;
        font-weight: 800;
    }

    .audiobook-progress-empty p {
        margin: 0;
        color: #888;
        font-size: 13px;
    }


    /* =========================================================
       FINAL AUDIOBOOK
    ========================================================== */

    .audiobook-result-content {
        text-align: center;
        padding: 25px 10px 10px;
    }

    .result-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f5f5f5;
        color: #888;
    }

    .result-icon.success {
        background: #eaf8ef;
        color: #218739;
    }

    .result-icon i {
        font-size: 27px;
    }

    .audiobook-result-info h3 {
        margin: 0 0 7px;
        font-size: 20px;
        font-weight: 800;
    }

    .audiobook-result-info p {
        max-width: 560px;
        margin: 0 auto;
        color: #777;
        font-size: 13px;
        line-height: 1.6;
    }

    .audiobook-result-player {
        max-width: 700px;
        margin: 25px auto 20px;
    }

    .audiobook-player {
        width: 100%;
    }

    .audiobook-result-actions {
        display: flex;
        justify-content: center;
    }

    .audiobook-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 10px;
        background: #171717;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .audiobook-download-btn:hover {
        background: #333;
        color: #fff;
        transform: translateY(-1px);
    }


    /* =========================================================
       EMPTY RESULT
    ========================================================== */

    .audiobook-empty-result {
        text-align: center;
        padding: 55px 20px;
    }

    .audiobook-empty-result.error .result-icon {
        background: #fff0f0;
        color: #c62828;
    }

    .audiobook-empty-result h3 {
        margin: 0 0 7px;
        font-size: 18px;
        font-weight: 800;
    }

    .audiobook-empty-result p {
        max-width: 650px;
        margin: 0 auto;
        color: #777;
        font-size: 13px;
        line-height: 1.7;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1199px) {

        .audiobook-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 767px) {

        .audiobook-hero,
        .audiobook-panel {
            padding: 20px;
            border-radius: 16px;
        }

        .audiobook-panel-header {
            flex-direction: column;
        }

        .audiobook-stats-grid {
            grid-template-columns: 1fr;
        }

        .audiobook-progress-stats {
            grid-template-columns: 1fr;
        }

        .audiobook-cost-card {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .cost-model {
            width: 100%;
            padding: 15px 0 0;
            border-left: 0;
            border-top: 1px solid #ddd;
        }

        .audiobook-title {
            font-size: 28px;
        }

        .audiobook-generate-btn {
            width: 100%;
            min-width: 0;
        }

    }


    @media (max-width: 575px) {

        .audiobook-cover {
            max-width: 180px;
        }

        .audiobook-purpose {
            padding: 14px;
        }

        .audiobook-panel-header h2 {
            font-size: 18px;
        }

        .analysis-status {
            font-size: 11px;
        }

        .audiobook-stat-card {
            min-height: auto;
        }

    }

    /* =========================================================
   FINAL AUDIOBOOK
========================================================= */

.audiobook-final-panel {
    overflow: hidden;
}

.audiobook-final-panel .audiobook-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
}


/* ---------------------------------------------------------
   STATUS
--------------------------------------------------------- */

.audiobook-final-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.audiobook-final-status.ready {
    color: #8a5a00;
    background: #fff7df;
    border: 1px solid #f3df9e;
}

.audiobook-final-status.published {
    color: #176b42;
    background: #edf9f2;
    border: 1px solid #bfe8d0;
}


/* ---------------------------------------------------------
   RESULT
--------------------------------------------------------- */

.audiobook-final-result {
    margin-top: 28px;
    padding: 28px;
    border: 1px solid #ece7e4;
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 12px 35px rgba(25, 20, 18, 0.05);
}


/* ---------------------------------------------------------
   HERO
--------------------------------------------------------- */

.audiobook-final-hero {
    display: flex;
    align-items: center;
    gap: 20px;
}

.audiobook-final-icon {
    width: 64px;
    height: 64px;
    flex: 0 0 64px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 18px;

    background: linear-gradient(
        135deg,
        #b30000 0%,
        #8f0000 100%
    );

    color: #fff;
    font-size: 28px;

    box-shadow:
        0 10px 25px rgba(179, 0, 0, 0.20);
}

.audiobook-final-heading {
    min-width: 0;
}

.audiobook-final-eyebrow {
    display: block;
    margin-bottom: 5px;

    color: #b30000;

    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.14em;
}

.audiobook-final-heading h3 {
    margin: 0;

    color: #191919;

    font-size: 22px;
    font-weight: 750;
}

.audiobook-final-heading p {
    margin: 6px 0 0;

    color: #777;

    font-size: 14px;
    line-height: 1.6;
}


/* ---------------------------------------------------------
   META
--------------------------------------------------------- */

.audiobook-final-meta {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;

    margin-top: 25px;
}

.audiobook-final-meta-item {
    min-width: 0;

    padding: 15px 17px;

    background: #faf9f8;
    border: 1px solid #eeeae7;
    border-radius: 14px;
}

.audiobook-final-meta-item span {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 7px;

    color: #8a8581;

    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.audiobook-final-meta-item span i {
    color: #b30000;
}

.audiobook-final-meta-item strong {
    display: block;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;

    color: #292625;

    font-size: 13px;
    font-weight: 700;
}


/* ---------------------------------------------------------
   PLAYER
--------------------------------------------------------- */

.audiobook-final-player-card {
    margin-top: 18px;
    padding: 20px;

    border: 1px solid #ece7e4;
    border-radius: 17px;

    background: #fbfaf9;
}

.audiobook-player-heading {
    display: flex;
    align-items: center;
    gap: 12px;

    margin-bottom: 15px;
}

.audiobook-player-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #f6e8e8;
    color: #b30000;

    font-size: 18px;
}

.audiobook-player-heading strong {
    display: block;

    color: #252322;

    font-size: 14px;
    font-weight: 700;
}

.audiobook-player-heading span {
    display: block;
    margin-top: 2px;

    color: #88827e;

    font-size: 12px;
}

.audiobook-player {
    width: 100%;
    height: 48px;
}


/* ---------------------------------------------------------
   ACTIONS
--------------------------------------------------------- */

.audiobook-final-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;

    margin-top: 20px;
}

.audiobook-final-actions form {
    margin: 0;
}

.audiobook-secondary-action,
.audiobook-publish-action,
.audiobook-published-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    min-height: 44px;
    padding: 0 18px;

    border-radius: 12px;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        background .2s ease;
}

.audiobook-secondary-action {
    color: #4e4946;

    background: #fff;

    border: 1px solid #ded9d5;
}

.audiobook-secondary-action:hover {
    color: #292624;
    background: #f8f6f5;
    transform: translateY(-1px);
}

.audiobook-publish-action {
    color: #fff;

    background: #b30000;

    border: 1px solid #b30000;

    box-shadow:
        0 8px 20px rgba(179, 0, 0, 0.18);
}

.audiobook-publish-action:hover {
    color: #fff;

    background: #970000;
    border-color: #970000;

    transform: translateY(-1px);

    box-shadow:
        0 11px 24px rgba(179, 0, 0, 0.23);
}

.audiobook-published-action {
    color: #176b42;

    background: #edf9f2;

    border: 1px solid #bfe8d0;
}

.audiobook-published-action:hover {
    color: #125a36;
    background: #e3f5ea;
    transform: translateY(-1px);
}


/* ---------------------------------------------------------
   PUBLICATION NOTICE
--------------------------------------------------------- */

.audiobook-publication-notice,
.audiobook-publication-success {
    display: flex;
    align-items: flex-start;
    gap: 11px;

    margin-top: 18px;
    padding: 14px 16px;

    border-radius: 13px;

    font-size: 12px;
    line-height: 1.55;
}

.audiobook-publication-notice {
    color: #756b61;

    background: #fffaf0;
    border: 1px solid #f0e1bd;
}

.audiobook-publication-notice > i {
    margin-top: 1px;
    color: #b07a00;
}

.audiobook-publication-success {
    color: #286b49;

    background: #f0faf4;
    border: 1px solid #c9e8d5;
}

.audiobook-publication-success-icon {
    width: 23px;
    height: 23px;
    flex: 0 0 23px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    color: #fff;
    background: #29945d;

    font-size: 12px;
}

.audiobook-publication-success strong {
    display: block;
    margin-bottom: 2px;

    font-size: 12px;
}

.audiobook-publication-success p {
    margin: 0;

    font-size: 12px;
}


/* ---------------------------------------------------------
   EMPTY / STATES
--------------------------------------------------------- */

.audiobook-final-empty {
    margin-top: 28px;
    padding: 52px 30px;

    text-align: center;

    border: 1px dashed #ddd7d3;
    border-radius: 20px;

    background: #fbfaf9;
}

.audiobook-final-state-icon {
    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 17px;

    border-radius: 17px;

    color: #b30000;
    background: #f8eaea;

    font-size: 24px;
}

.audiobook-state-label {
    display: block;

    margin-bottom: 7px;

    color: #b30000;

    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.14em;
}

.audiobook-final-empty h3 {
    margin: 0;

    color: #292625;

    font-size: 19px;
    font-weight: 750;
}

.audiobook-final-empty p {
    max-width: 600px;

    margin: 8px auto 0;

    color: #85807c;

    font-size: 13px;
    line-height: 1.65;
}

.audiobook-final-empty.error {
    background: #fffafa;
    border-color: #edcccc;
}

.audiobook-final-empty.error
.audiobook-final-state-icon {
    color: #b30000;
    background: #fbecec;
}


/* ---------------------------------------------------------
   LOADING LINE
--------------------------------------------------------- */

.audiobook-loading-line {
    width: 220px;
    height: 4px;

    margin: 25px auto 0;

    overflow: hidden;

    border-radius: 999px;

    background: #ece8e5;
}

.audiobook-loading-line span {
    display: block;

    width: 40%;
    height: 100%;

    border-radius: inherit;

    background: #b30000;

    animation: audiobookLoading 1.4s ease-in-out infinite;
}

@keyframes audiobookLoading {

    0% {
        transform: translateX(-120%);
    }

    50% {
        transform: translateX(120%);
    }

    100% {
        transform: translateX(260%);
    }

}


/* ---------------------------------------------------------
   RESPONSIVE
--------------------------------------------------------- */

@media (max-width: 768px) {

    .audiobook-final-panel
    .audiobook-panel-header {
        flex-direction: column;
    }

    .audiobook-final-status {
        align-self: flex-start;
    }

    .audiobook-final-result {
        padding: 20px;
    }

    .audiobook-final-hero {
        align-items: flex-start;
    }

    .audiobook-final-icon {
        width: 54px;
        height: 54px;
        flex-basis: 54px;
        font-size: 23px;
    }

    .audiobook-final-heading h3 {
        font-size: 19px;
    }

    .audiobook-final-meta {
        grid-template-columns: 1fr;
    }

    .audiobook-final-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .audiobook-final-actions form,
    .audiobook-secondary-action,
    .audiobook-publish-action,
    .audiobook-published-action {
        width: 100%;
    }

}

/* =========================================================
   AUDIOBOOK — PUBLICATION MODAL
========================================================= */

.audiobook-publish-modal .modal-dialog {
    max-width: 510px;
}

.audiobook-publish-modal .modal-content {
    position: relative;

    overflow: hidden;

    border: 0;
    border-radius: 24px;

    background: #fff;

    box-shadow:
        0 30px 80px rgba(20, 16, 14, 0.20);
}


/* ---------------------------------------------------------
   CLOSE
--------------------------------------------------------- */

.audiobook-modal-close {
    position: absolute;

    top: 18px;
    right: 18px;

    z-index: 5;

    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border: 0;
    border-radius: 50%;

    color: #77716d;

    background: #f7f5f4;

    font-size: 13px;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease;
}

.audiobook-modal-close:hover {
    color: #292522;
    background: #eeeae8;

    transform: rotate(5deg);
}


/* ---------------------------------------------------------
   BODY
--------------------------------------------------------- */

.audiobook-publish-modal .modal-body {
    padding: 36px;
}


/* ---------------------------------------------------------
   ICON
--------------------------------------------------------- */

.audiobook-modal-icon {
    width: 64px;
    height: 64px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 20px;

    border-radius: 19px;

    color: #fff;

    background: linear-gradient(
        135deg,
        #b30000 0%,
        #8f0000 100%
    );

    font-size: 27px;

    box-shadow:
        0 12px 28px rgba(179, 0, 0, 0.20);
}


/* ---------------------------------------------------------
   CONTENT
--------------------------------------------------------- */

.audiobook-modal-kicker {
    display: block;

    margin-bottom: 7px;

    color: #b30000;

    font-size: 10px;
    font-weight: 800;

    letter-spacing: 0.14em;
}

.audiobook-modal-content h3 {
    margin: 0;

    color: #1f1d1b;

    font-size: 24px;
    font-weight: 750;

    letter-spacing: -0.02em;
}

.audiobook-modal-content p {
    margin: 9px 0 0;

    color: #77716d;

    font-size: 13px;
    line-height: 1.65;
}


/* ---------------------------------------------------------
   SUMMARY
--------------------------------------------------------- */

.audiobook-modal-summary {
    display: flex;
    flex-direction: column;
    gap: 1px;

    margin-top: 24px;

    overflow: hidden;

    border: 1px solid #ebe7e4;
    border-radius: 15px;

    background: #ebe7e4;
}

.audiobook-modal-summary-item {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 13px 15px;

    background: #fff;
}

.audiobook-modal-summary-icon {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #b30000;

    background: #f9eded;

    font-size: 15px;
}

.audiobook-modal-summary-item span {
    display: block;

    margin-bottom: 2px;

    color: #99928d;

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.audiobook-modal-summary-item strong {
    display: block;

    max-width: 350px;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;

    color: #292522;

    font-size: 13px;
    font-weight: 700;
}


/* ---------------------------------------------------------
   NOTICE
--------------------------------------------------------- */

.audiobook-modal-notice {
    display: flex;
    align-items: flex-start;
    gap: 10px;

    margin-top: 16px;
    padding: 13px 14px;

    border: 1px solid #f0e2bf;
    border-radius: 12px;

    color: #74664f;

    background: #fffbf2;

    font-size: 11px;
    line-height: 1.55;
}

.audiobook-modal-notice > i {
    flex: 0 0 auto;

    margin-top: 1px;

    color: #ad7a00;

    font-size: 14px;
}


/* ---------------------------------------------------------
   ACTIONS
--------------------------------------------------------- */

.audiobook-modal-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;

    margin-top: 25px;
}

.audiobook-modal-actions form {
    margin: 0;
}

.audiobook-modal-cancel,
.audiobook-modal-confirm {
    min-height: 44px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 0 17px;

    border-radius: 11px;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;

    transition:
        transform .2s ease,
        background .2s ease,
        box-shadow .2s ease;
}

.audiobook-modal-cancel {
    color: #57514d;

    background: #fff;

    border: 1px solid #ddd8d4;
}

.audiobook-modal-cancel:hover {
    background: #f7f5f4;

    transform: translateY(-1px);
}

.audiobook-modal-confirm {
    color: #fff;

    background: #b30000;

    border: 1px solid #b30000;

    box-shadow:
        0 8px 20px rgba(179, 0, 0, 0.18);
}

.audiobook-modal-confirm:hover {
    color: #fff;

    background: #970000;
    border-color: #970000;

    transform: translateY(-1px);

    box-shadow:
        0 11px 25px rgba(179, 0, 0, 0.24);
}


/* ---------------------------------------------------------
   MODAL BACKDROP
--------------------------------------------------------- */

.audiobook-publish-modal + .modal-backdrop {
    background: #171311;
}

.modal-backdrop.show {
    opacity: .62;
}


/* ---------------------------------------------------------
   MOBILE
--------------------------------------------------------- */

@media (max-width: 576px) {

    .audiobook-publish-modal .modal-body {
        padding: 28px 22px;
    }

    .audiobook-modal-content h3 {
        font-size: 21px;
    }

    .audiobook-modal-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .audiobook-modal-actions form,
    .audiobook-modal-cancel,
    .audiobook-modal-confirm {
        width: 100%;
    }

}

</style>
@endpush

@endsection