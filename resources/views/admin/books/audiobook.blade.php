@extends('layouts.admin')

@section('title', 'Audiobook — '.$book->title)
@section('page-title', 'Gestion de l’audiobook')

@section('admin-content')

@php
$statusMeta = match ($book->status) {
    'draft' => ['Brouillon', 'draft'],
    'waiting_review' => ['En attente', 'waiting'],
    'under_review' => ['En vérification', 'review'],
    'published' => ['Publié', 'published'],
    'revision_required' => ['À corriger', 'revision'],
    'rejected' => ['Rejeté', 'rejected'],
    default => ['Inconnu', 'unknown'],
};

$statusLabel = $statusMeta[0];
$statusClass = $statusMeta[1];

$characterCount = $statistics['characters'] ?? 0;
$wordCount = $statistics['words'] ?? 0;
$chapterCount = $statistics['section_count'] ?? 0;
$pageCount = $statistics['page_count'] ?? 0;
$estimatedCost = $estimatedCost ?? 0;
$estimatedCost = $statistics['estimated_cost'] ?? 0;
@endphp

<div class="admin-audiobook-page">

<div class="container-fluid px-0">

    {{-- BACK --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="audiobook-back">
            <i class="bi bi-arrow-left"></i>
            Retour au livre
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
                            ? asset('storage/'.$book->cover_image)
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
                        <i class="bi bi-book"></i>
                        Ebook
                    </span>

                    @if($book->category)
                        <span class="audiobook-tag">
                            {{ $book->category->name }}
                        </span>
                    @endif

                    @if($book->language)
                        <span class="audiobook-tag">
                            <i class="bi bi-translate"></i>
                            {{ $book->language }}
                        </span>
                    @endif

                    <span class="audiobook-tag status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <h1 class="audiobook-title">
                    {{ $book->title }}
                </h1>

                <div class="audiobook-author">

                    <i class="bi bi-person-circle"></i>

                    <span>
                        {{ $book->author?->firstname }}
                        {{ $book->author?->lastname }}
                    </span>

                </div>

                <div class="audiobook-purpose">

                    <div class="purpose-icon">
                        <i class="bi bi-headphones"></i>
                    </div>

                    <div>
                        <strong>Créer l’audiobook</strong>

                        <p>
                            Transformez le contenu de ce livre en audiobook
                            grâce à la synthèse vocale ElevenLabs.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONTENT ANALYSIS
    ========================================================== --}}
    <section class="audiobook-panel mt-4">

        <div class="audiobook-panel-header">

            <div>

                <span class="audiobook-section-kicker">
                    ÉTAPE 01
                </span>

                <h2>
                    <i class="bi bi-file-earmark-text"></i>
                    Analyse du contenu
                </h2>

                <p>
                    Résumé du contenu qui sera converti en audio.
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

                    <span>Caractères</span>

                    <strong>
                        {{ number_format($characterCount, 0, ',', ' ') }}
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

                    <span>Mots</span>

                    <strong>
                        {{ number_format($wordCount, 0, ',', ' ') }}
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

                    <span>Chapitres</span>

                    <strong>
                        {{ number_format($chapterCount, 0, ',', ' ') }}
                    </strong>

                    <small>
                        chapitres détectés
                    </small>

                </div>

            </div>


            {{-- LANGUAGE --}}
            <div class="audiobook-stat-card">

                <div class="stat-icon">
                    <i class="bi bi-translate"></i>
                </div>

                <div>

                    <span>Langue</span>

                    <strong class="text-value">
                        {{ $book->language ?: '—' }}
                    </strong>

                    <small>
                        langue du livre
                    </small>

                </div>

            </div>

        </div>


        {{-- COST ESTIMATE --}}
        <div class="audiobook-cost-card">

            <div class="cost-icon">
                <i class="bi bi-calculator"></i>
            </div>

            <div class="cost-content">

                <span>
                    ESTIMATION DU COÛT
                </span>

                <strong>
                    ${{ number_format((float) $estimatedCost, 2, '.', ',') }}
                </strong>

                <p>
                    Estimation basée sur le nombre de caractères
                    à traiter.
                </p>

            </div>

            <div class="cost-model">

                <span>Modèle</span>

                <strong>
                    Eleven v3
                </strong>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONFIGURATION
    ========================================================== --}}
    <section class="audiobook-panel mt-4">

        <div class="audiobook-panel-header">

            <div>

                <span class="audiobook-section-kicker">
                    ÉTAPE 02
                </span>

                <h2>
                    <i class="bi bi-sliders"></i>
                    Configuration
                </h2>

                <p>
                    Configurez les paramètres de génération.
                </p>

            </div>

        </div>


        <div class="row g-4">

            {{-- VOICE --}}

            <div class="col-lg-12 col-md-6">

                <div class="audiobook-config-card audiobook-voice-selector">

                    <div class="audiobook-field-header">

                        <div>
                            <label
                                for="voice_id"
                                class="audiobook-field-label pb-1">

                                <i
                                    class="bi bi-mic"
                                    style="color: #b30000;">
                                </i>

                                <strong>Voix</strong>

                            </label>
                        </div>

                    </div>


                    <select
                        id="voice_id"
                        name="voice_id"
                        class="form-select audiobook-voice-select">

                        <option value="">
                            Sélectionner une voix
                        </option>

                        @foreach ($voices as $voice)

                            @php
                                $labels = $voice['labels'] ?? [];
                            @endphp

                            <option
                                value="{{ $voice['voice_id'] }}"
                                data-preview-url="{{ $voice['preview_url'] ?? '' }}"
                                data-description="{{ $voice['description'] ?? '' }}"
                                data-language="{{ $labels['language'] ?? '' }}"
                                data-gender="{{ $labels['gender'] ?? '' }}"
                                data-accent="{{ $labels['accent'] ?? '' }}"
                                data-age="{{ $labels['age'] ?? '' }}"
                                data-use-case="{{ $labels['use_case'] ?? '' }}"
                            >
                                {{ $voice['name'] }}
                            </option>

                        @endforeach

                    </select>


                    {{-- VOICE DETAILS --}}

                    <div
                        id="voice-details-empty"
                        class="audiobook-voice-details-empty">

                        <i class="bi bi-mic"></i>

                        <span>
                            Sélectionnez une voix pour afficher ses caractéristiques.
                        </span>

                    </div>


                    <div
                        id="voice-details-content"
                        class="audiobook-voice-details-content"
                        style="display: none;">

                        <div class="audiobook-voice-details-header">

                            <div class="audiobook-voice-details-icon">
                                <i class="bi bi-mic-fill"></i>
                            </div>

                            <div>
                                <strong id="details-voice-name"></strong>

                                <span>
                                    Voix de narration
                                </span>
                            </div>

                        </div>


                        <p
                            id="details-voice-description"
                            class="audiobook-voice-details-description">
                        </p>


                        <div
                            id="details-voice-labels"
                            class="audiobook-voice-details-labels">
                        </div>


                        <button
                            type="button"
                            id="details-preview-voice-btn"
                            class="audiobook-voice-preview-btn"
                            disabled>

                            <i class="bi bi-play-fill"></i>

                            <span>
                                Écouter un aperçu
                            </span>

                        </button>

                        <button
                            type="button"
                            id="save-voice-btn"
                            class="audiobook-voice-save-btn"
                            data-save-url="{{ $audiobook ? route('admin.audiobooks.voice.save', $audiobook->id) : '' }}"
                            {{ !$audiobook ? 'disabled' : '' }}>

                            <i class="bi bi-check2"></i>

                            <span>
                                Utiliser cette voix
                            </span>

                        </button>

                        <div
                            id="voice-save-message"
                            class="audiobook-voice-save-message">
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
                    Chaque chapitre sera généré séparément.
                    En cas d’échec, seul le chapitre concerné
                    pourra être relancé.
                </p>

            </div>

        </div>


        {{-- GENERATE BUTTON --}}
        <div class="audiobook-generate-wrapper">

            <button
                type="button"
                class="audiobook-generate-btn"
                id="generate-audiobook-btn"
                data-url="{{ $audiobook ? route('admin.audiobooks.generate', $audiobook->id) : '' }}"
                {{ !$audiobook
                    || $audiobook->status === 'completed'
                    || !$audiobook->voice_id
                    ? 'disabled'
                    : '' }}>

                <i class="bi bi-headphones"></i>

                <span>
                    {{ $audiobook?->status === 'completed'
                        ? 'Audiobook terminé'
                        : 'Générer l’audiobook' }}
                </span>

            </button>

            <small>
                Sélectionnez une voix pour commencer.
            </small>

        </div>

    </section>


    {{-- =========================================================
        GENERATION PROGRESS
    ========================================================== --}}
    <section class="audiobook-panel mt-4">

        @if ($audiobook)
            <div
                id="audiobook-progress"
                data-status-url="{{ route('admin.audiobooks.status', $audiobook) }}">
        @endif

        @php
            $status = $audiobook?->status ?? 'pending';

            $statusLabels = [
                'draft' => 'En attente',
                'queued' => 'En attente',
                'generating' => 'Génération en cours',
                'assembling' => 'Assemblage en cours',
                'completed' => 'Terminé',
                'failed' => 'Échec',
                'cancelled' => 'Annulé',
            ];

            $statusLabel = $statusLabels[$status] ?? 'En attente';

            $totalChunks = (int) ($audiobook?->total_chunks ?? 0);
            $completedChunks = (int) ($audiobook?->completed_chunks ?? 0);

            $totalCharacters = (int) ($audiobook?->total_characters ?? 0);
            $generatedCharacters = (int) ($audiobook?->generated_characters ?? 0);

            $chunkProgress = $totalChunks > 0
                ? min(100, round(($completedChunks / $totalChunks) * 100))
                : 0;

            $characterProgress = $totalCharacters > 0
                ? min(100, round(($generatedCharacters / $totalCharacters) * 100))
                : 0;
        @endphp

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
                    Suivi de la génération de votre audiobook.
                </p>

            </div>

            <span
                id="audiobook-generation-status"
                class="generation-status {{ $status }}">
                {{ $statusLabel }}
            </span>

        </div>


        @if (!$audiobook)

            {{-- Aucun audiobook --}}
            <div class="audiobook-progress-empty">

                <div class="empty-progress-icon">
                    <i class="bi bi-headphones"></i>
                </div>

                <h3>
                    Aucun audiobook généré
                </h3>

                <p>
                    La progression apparaîtra ici une fois
                    la génération lancée.
                </p>

            </div>


        @else

            {{-- Progression --}}
            <div class="audiobook-progress-content">

                {{-- Statistiques --}}
                <div class="audiobook-progress-stats">

                    <div class="audiobook-progress-stat">

                        <span class="progress-stat-label">
                            Chunks générés
                        </span>

                       <strong id="audiobook-completed-chunks">
                            {{ number_format($completedChunks, 0, ',', ' ') }}
                            /
                            {{ number_format($totalChunks, 0, ',', ' ') }}
                        </strong>

                    </div>


                    <div class="audiobook-progress-stat">

                        <span class="progress-stat-label">
                            Caractères générés
                        </span>

                        <strong id="audiobook-generated-characters">
                            {{ number_format($generatedCharacters, 0, ',', ' ') }}
                            /
                            {{ number_format($totalCharacters, 0, ',', ' ') }}
                        </strong>

                    </div>

                </div>


                {{-- Progression chunks --}}
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


                {{-- Progression caractères --}}
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


                {{-- Message selon le statut --}}
                @if ($status === 'draft' || $status === 'queued')

                    <div class="audiobook-progress-message">

                        <i class="bi bi-hourglass-split"></i>

                        <span>
                            L'audiobook est prêt à être généré.
                        </span>

                    </div>


                @elseif ($status === 'generating')

                    <div class="audiobook-progress-message">

                        <i class="bi bi-arrow-repeat"></i>

                        <span>
                            La génération de votre audiobook est en cours.
                        </span>

                    </div>


                @elseif ($status === 'assembling')

                    <div class="audiobook-progress-message">

                        <i class="bi bi-soundwave"></i>

                        <span>
                            Tous les passages ont été générés.
                            L'assemblage du fichier audio final est en cours.
                        </span>

                    </div>


                @elseif ($status === 'completed')

                    <div class="audiobook-progress-message success">

                        <i class="bi bi-check-circle"></i>

                        <span>
                            Votre audiobook est prêt à être écouté.
                        </span>

                    </div>


                @elseif ($status === 'failed')

                    <div class="audiobook-progress-message error">

                        <i class="bi bi-exclamation-circle"></i>

                        <span>
                            La génération de l'audiobook a rencontré une erreur.
                        </span>

                    </div>


                @elseif ($status === 'cancelled')

                    <div class="audiobook-progress-message error">

                        <i class="bi bi-x-circle"></i>

                        <span>
                            La génération de l'audiobook a été annulée.
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
    <section class="audiobook-panel mt-4">

        <div class="audiobook-panel-header">

            <div>

                <span class="audiobook-section-kicker">
                    ÉTAPE 04
                </span>

                <h2>
                    <i class="bi bi-music-note-beamed"></i>
                    Audiobook
                </h2>

                <p>
                    Votre audiobook généré apparaîtra ici.
                </p>

            </div>

        </div>


        @if (
            $audiobook &&
            $audiobook->status === 'completed' &&
            $audiobook->final_audio_path
        )

            <div class="audiobook-result-content">

                <div class="result-icon success">
                    <i class="bi bi-headphones"></i>
                </div>


                <div class="audiobook-result-info">

                    <h3>
                        Votre audiobook est prêt
                    </h3>

                    <p>
                        La génération est terminée.
                        Vous pouvez maintenant écouter
                        ou télécharger votre audiobook.
                    </p>

                </div>


                <div class="audiobook-result-player">

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


                <div class="audiobook-result-actions">

                    <a
                        href="{{ route(
                            'admin.audiobooks.download',
                            $audiobook
                        ) }}"
                        class="audiobook-download-btn">

                        <i class="bi bi-download"></i>

                        <span>
                            Télécharger l’audiobook
                        </span>

                    </a>

                </div>

            </div>


        @elseif (
            $audiobook &&
            $audiobook->status === 'assembling'
        )

            <div class="audiobook-empty-result">

                <div class="result-icon">
                    <i class="bi bi-soundwave"></i>
                </div>

                <h3>
                    Assemblage de votre audiobook
                </h3>

                <p>
                    Tous les chapitres ont été générés.
                    Nous assemblons maintenant les fichiers audio
                    pour créer votre audiobook final.
                </p>

            </div>


        @elseif (
            $audiobook &&
            $audiobook->status === 'generating'
        )

            <div class="audiobook-empty-result">

                <div class="result-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <h3>
                    Audiobook en cours de génération
                </h3>

                <p>
                    L’audiobook apparaîtra ici une fois
                    que tous les chapitres auront été générés.
                </p>

            </div>


        @elseif (
            $audiobook &&
            $audiobook->status === 'failed'
        )

            <div class="audiobook-empty-result error">

                <div class="result-icon">
                    <i class="bi bi-exclamation-circle"></i>
                </div>

                <h3>
                    La génération a échoué
                </h3>

                <p>
                    {{ $audiobook->error_message
                        ?: 'Une erreur est survenue pendant la génération.' }}
                </p>

            </div>


        @else

            <div class="audiobook-empty-result">

                <div class="result-icon">
                    <i class="bi bi-music-note-list"></i>
                </div>

                <h3>
                    Aucun audiobook disponible
                </h3>

                <p>
                    Générez votre audiobook pour pouvoir
                    l’écouter et le télécharger.
                </p>

            </div>

        @endif

    </section>

</div>

</div>

@endsection

@push('styles')

<style>



/* =========================================================
   PAGE
========================================================= */

.admin-audiobook-page {
    border-radius: 20px;
    padding: 8px 4px 40px;
    min-height: 100%;
}


/* =========================================================
   BACK
========================================================= */

.audiobook-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 18px;

    border-radius: 999px;

    border: 1px solid #ddd;

    background: #fff;

    color: #222;

    font-size: .9rem;
    font-weight: 700;

    text-decoration: none;

    transition: .2s ease;
}

.audiobook-back:hover {
    border-color: #b30000;
    color: #b30000;
    transform: translateX(-2px);
}


/* =========================================================
   HERO
========================================================= */

.audiobook-hero {
    position: relative;

    padding: 34px;

    border-radius: 28px;

    overflow: hidden;

    color: #fff;

    background:
        radial-gradient(
            circle at 90% 10%,
            rgba(255,255,255,.12),
            transparent 30%
        ),
        linear-gradient(
            145deg,
            #171717 0%,
            #300707 45%,
            #b30000 100%
        );

    box-shadow:
        0 24px 50px rgba(0,0,0,.20);
}

.audiobook-cover {
    position: relative;

    width: min(100%, 240px);

    margin: auto;
}

.audiobook-cover img {
    width: 100%;
    height: 330px;

    object-fit: cover;

    border-radius: 18px;

    box-shadow:
        0 25px 45px rgba(0,0,0,.45);
}

.audiobook-cover-overlay {
    position: absolute;

    right: 12px;
    bottom: 12px;

    width: 48px;
    height: 48px;

    display: grid;
    place-items: center;

    border-radius: 50%;

    background: #fff;
    color: #b30000;

    font-size: 1.25rem;

    box-shadow: 0 8px 20px rgba(0,0,0,.3);
}


/* =========================================================
   HERO CONTENT
========================================================= */

.audiobook-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;

    margin-bottom: 15px;
}

.audiobook-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 6px 12px;

    border-radius: 999px;

    background: rgba(255,255,255,.11);

    border: 1px solid rgba(255,255,255,.12);

    color: #fff;

    font-size: .76rem;
    font-weight: 700;
}

.audiobook-tag.type {
    background: rgba(255,255,255,.19);
}

.audiobook-tag.status.published {
    background: #1a7f4b;
}

.audiobook-tag.status.waiting,
.audiobook-tag.status.review {
    background: #c47a00;
}

.audiobook-tag.status.revision,
.audiobook-tag.status.rejected {
    background: #8a1f1f;
}

.audiobook-tag.status.draft {
    background: #5f636b;
}

.audiobook-title {
    margin: 0 0 8px;

    font-size: clamp(1.8rem, 3vw, 2.65rem);

    line-height: 1.12;

    font-weight: 800;

    color: #fff;
}

.audiobook-author {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-bottom: 14px;

    font-weight: 600;

    opacity: .92;
}

.audiobook-description {
    max-width: 720px;

    margin-bottom: 20px;

    color: rgba(255,255,255,.84);

    line-height: 1.6;
}


/* =========================================================
   PURPOSE
========================================================= */

.audiobook-purpose {
    display: flex;
    align-items: center;
    gap: 14px;

    max-width: 650px;

    padding: 15px 17px;

    border-radius: 16px;

    background: rgba(255,255,255,.09);

    border: 1px solid rgba(255,255,255,.12);
}

.purpose-icon {
    flex: 0 0 44px;

    width: 44px;
    height: 44px;

    display: grid;
    place-items: center;

    border-radius: 12px;

    background: #fff;

    color: #b30000;

    font-size: 1.15rem;
}

.audiobook-purpose strong {
    display: block;

    margin-bottom: 3px;

    font-size: .95rem;
}

.audiobook-purpose p {
    margin: 0;

    color: rgba(255,255,255,.72);

    font-size: .84rem;
}


/* =========================================================
   PANEL
========================================================= */

.audiobook-panel {
    background: #fff;

    border: 1px solid #ece7e5;

    border-radius: 22px;

    padding: 25px;

    box-shadow:
        0 10px 28px rgba(30,20,16,.045);
}

.audiobook-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;
}

.audiobook-section-kicker {
    display: block;

    margin-bottom: 5px;

    color: #b30000;

    font-size: .68rem;
    font-weight: 800;

    letter-spacing: .1em;
}

.audiobook-panel-header h2 {
    display: flex;
    align-items: center;
    gap: 9px;

    margin: 0 0 5px;

    font-size: 1.2rem;

    color: #222;
}

.audiobook-panel-header h2 i {
    color: #b30000;
}

.audiobook-panel-header p {
    margin: 0;

    color: #888;

    font-size: .85rem;
}


/* =========================================================
   ANALYSIS STATUS
========================================================= */

.analysis-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 7px 11px;

    border-radius: 999px;

    background: #edf8f1;

    color: #197044;

    font-size: .76rem;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   STATS
========================================================= */

.audiobook-stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;
}

.audiobook-stat-card {
    display: flex;
    align-items: center;
    gap: 13px;

    padding: 17px;

    border-radius: 16px;

    background: #faf8f7;

    border: 1px solid #eee9e7;
}

.audiobook-stat-card.featured {
    background: #fff5f5;

    border-color: #f1d2d2;
}

.stat-icon {
    flex: 0 0 45px;

    width: 45px;
    height: 45px;

    display: grid;
    place-items: center;

    border-radius: 12px;

    background: #fff;

    color: #b30000;

    border: 1px solid #eee;

    font-size: 1.1rem;
}

.audiobook-stat-card span {
    display: block;

    margin-bottom: 2px;

    color: #888;

    font-size: .72rem;
    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .04em;
}

.audiobook-stat-card strong {
    display: block;

    color: #222;

    font-size: 1.25rem;

    line-height: 1.2;
}

.audiobook-stat-card strong.text-value {
    font-size: 1rem;
}

.audiobook-stat-card small {
    display: block;

    margin-top: 3px;

    color: #aaa;

    font-size: .7rem;
}


/* =========================================================
   COST
========================================================= */

.audiobook-cost-card {
    display: flex;
    align-items: center;
    gap: 15px;

    margin-top: 15px;

    padding: 17px 19px;

    border-radius: 16px;

    background:
        linear-gradient(
            110deg,
            #fff8f8,
            #fff
        );

    border: 1px solid #f0dddd;
}

.cost-icon {
    flex: 0 0 46px;

    width: 46px;
    height: 46px;

    display: grid;
    place-items: center;

    border-radius: 12px;

    background: #b30000;

    color: #fff;

    font-size: 1.1rem;
}

.cost-content {
    flex: 1;
}

.cost-content > span {
    display: block;

    color: #999;

    font-size: .67rem;
    font-weight: 800;

    letter-spacing: .08em;
}

.cost-content strong {
    display: block;

    margin-top: 2px;

    color: #b30000;

    font-size: 1.35rem;
}

.cost-content p {
    margin: 2px 0 0;

    color: #888;

    font-size: .75rem;
}

.cost-model {
    padding-left: 20px;

    border-left: 1px solid #eadede;
}

.cost-model span,
.cost-model strong {
    display: block;
}

.cost-model span {
    color: #999;

    font-size: .7rem;
}

.cost-model strong {
    margin-top: 3px;

    color: #222;

    font-size: .9rem;
}


/* =========================================================
   CONFIGURATION
========================================================= */

.audiobook-config-card {
    height: 100%;

    padding: 18px;

    border-radius: 16px;

    background: #faf8f7;

    border: 1px solid #eee9e7;
}

.audiobook-config-card > label {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 9px;

    color: #333;

    font-size: .85rem;
    font-weight: 800;
}

.audiobook-config-card > label i {
    color: #b30000;
}

.audiobook-select {
    min-height: 48px;

    border-color: #ddd5d2;

    border-radius: 12px;

    box-shadow: none;
}

.audiobook-select:focus {
    border-color: #b30000;

    box-shadow:
        0 0 0 3px rgba(179,0,0,.08);
}

.audiobook-config-card > small {
    display: block;

    margin-top: 8px;

    color: #999;

    font-size: .74rem;
}


/* =========================================================
   MODEL
========================================================= */

.model-display {
    display: flex;
    align-items: center;
    gap: 12px;

    min-height: 48px;

    padding: 7px 10px;

    border-radius: 12px;

    background: #fff;

    border: 1px solid #e5dfdc;
}

.model-icon {
    width: 36px;
    height: 36px;

    display: grid;
    place-items: center;

    border-radius: 10px;

    background: #fff0f0;

    color: #b30000;
}

.model-display strong,
.model-display small {
    display: block;
}

.model-display strong {
    font-size: .86rem;
}

.model-display small {
    margin-top: 2px;

    color: #999;

    font-size: .7rem;
}

.model-lock {
    margin-left: auto;

    color: #aaa;

    font-size: .75rem;
}


/* =========================================================
   GENERATION INFO
========================================================= */

.audiobook-generation-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;

    margin-top: 18px;

    padding: 14px 16px;

    border-radius: 14px;

    background: #f7f9fc;

    border: 1px solid #e6ebf2;
}

.generation-info-icon {
    color: #58677a;

    font-size: 1rem;
}

.audiobook-generation-info strong {
    display: block;

    margin-bottom: 3px;

    font-size: .82rem;
}

.audiobook-generation-info p {
    margin: 0;

    color: #777;

    font-size: .75rem;

    line-height: 1.5;
}


/* =========================================================
   GENERATE
========================================================= */

.audiobook-generate-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;

    margin-top: 25px;
}

.audiobook-generate-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;

    min-width: 245px;

    padding: 13px 22px;

    border: 0;

    border-radius: 999px;

    background: #b30000;

    color: #fff;

    font-size: .88rem;
    font-weight: 800;

    box-shadow:
        0 8px 20px rgba(179,0,0,.18);

    transition: .2s ease;
}

.audiobook-generate-btn:hover:not(:disabled) {
    background: #920000;

    transform: translateY(-1px);

    box-shadow:
        0 11px 25px rgba(179,0,0,.25);
}

.audiobook-generate-btn:disabled {
    opacity: .45;

    cursor: not-allowed;

    box-shadow: none;
}

.audiobook-generate-wrapper > small {
    margin-top: 8px;

    color: #aaa;

    font-size: .7rem;
}


/* =========================================================
   PROGRESS
========================================================= */

.generation-status {
    display: inline-flex;

    padding: 7px 11px;

    border-radius: 999px;

    font-size: .72rem;
    font-weight: 800;
}

.generation-status.pending {
    background: #f1f1f1;

    color: #666;
}

.audiobook-progress-empty,
.audiobook-empty-result {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    min-height: 190px;

    padding: 30px;

    text-align: center;

    border-radius: 16px;

    background: #faf9f8;

    border: 1px dashed #ddd6d3;
}

.empty-progress-icon,
.result-icon {
    width: 58px;
    height: 58px;

    display: grid;
    place-items: center;

    margin-bottom: 12px;

    border-radius: 50%;

    background: #fff0f0;

    color: #b30000;

    font-size: 1.35rem;
}

.audiobook-progress-empty h3,
.audiobook-empty-result h3 {
    margin: 0 0 5px;

    font-size: .95rem;
}

.audiobook-progress-empty p,
.audiobook-empty-result p {
    max-width: 420px;

    margin: 0;

    color: #999;

    font-size: .78rem;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1199px) {

    .audiobook-stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 991px) {

    .audiobook-hero {
        padding: 25px 20px;
    }

    .audiobook-cover img {
        height: 320px;
    }

    .audiobook-panel {
        padding: 20px;
    }

}

@media (max-width: 767px) {

    .audiobook-panel-header {
        flex-direction: column;
    }

    .audiobook-stats-grid {
        grid-template-columns: 1fr;
    }

    .audiobook-cost-card {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .cost-model {
        width: 100%;

        padding-left: 0;
        padding-top: 12px;

        border-left: 0;
        border-top: 1px solid #eadede;
    }

}

@media (max-width: 575px) {

    .admin-audiobook-page {
        padding-left: 0;
        padding-right: 0;
    }

    .audiobook-hero {
        border-radius: 20px;
    }

    .audiobook-panel {
        border-radius: 18px;
    }

    .audiobook-cover {
        width: 200px;
    }

    .audiobook-cover img {
        height: 280px;
    }

}

.audiobook-voice-details-empty {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
    padding: 12px 14px;
    background: #fafafa;
    border: 1px solid #eeeeee;
    border-radius: 8px;
    color: #888;
    font-size: 12px;
}

.audiobook-voice-details-empty i {
    color: #b30000;
    font-size: 15px;
}

.audiobook-voice-details-content {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #eeeeee;
}

.audiobook-voice-details-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.audiobook-voice-details-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff0f0;
    color: #b30000;
    border-radius: 9px;
}

.audiobook-voice-details-header strong {
    display: block;
    color: #18191c;
    font-size: 14px;
    font-weight: 700;
}

.audiobook-voice-details-header span {
    display: block;
    margin-top: 2px;
    color: #888;
    font-size: 11px;
}

.audiobook-voice-details-description {
    margin: 0 0 12px;
    color: #666;
    font-size: 12px;
    line-height: 1.55;
}

.audiobook-voice-details-labels {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
}

.audiobook-voice-label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    background: #f8f8f8;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    color: #555;
    font-size: 10px;
}

.audiobook-voice-label i {
    color: #b30000;
}

.audiobook-voice-preview-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 13px;
    background: #b30000;
    border: 1px solid #b30000;
    border-radius: 7px;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    transition: 0.2s ease;
}

.audiobook-voice-preview-btn:hover:not(:disabled) {
    background: #920000;
    border-color: #920000;
}

.audiobook-voice-preview-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.audiobook-voice-save-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 7px;

    margin-top: 8px;
    padding: 8px 13px;

    background-color: #18191c !important;
    border: 1px solid #18191c !important;
    border-radius: 7px;

    color: #ffffff !important;

    font-family: inherit;
    font-size: 11px;
    font-weight: 600;

    cursor: pointer;

    transition:
        background-color 0.2s ease,
        border-color 0.2s ease,
        transform 0.2s ease;
}

.audiobook-voice-save-btn:hover:not(:disabled) {
    background-color: #b30000 !important;
    border-color: #b30000 !important;
    color: #ffffff !important;
}

.audiobook-voice-save-btn:disabled {
    background-color: #18191c !important;
    border-color: #18191c !important;
    color: #ffffff !important;

    opacity: 0.45;
    cursor: not-allowed;
}

.audiobook-voice-save-btn i {
    font-size: 13px;
    line-height: 1;
}


/* Message de sauvegarde */

.audiobook-voice-save-message {
    display: none;

    margin-top: 8px;

    color: #198754;
    font-size: 11px;
    font-weight: 600;
}

.audiobook-voice-save-message.show {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* =========================================================
   AUDIOBOOK - GENERATION PROGRESS
========================================================= */

.audiobook-progress-content {
    padding: 6px 0 4px;
}


/* ---------------------------------------------------------
   STATISTICS
--------------------------------------------------------- */

.audiobook-progress-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}

.audiobook-progress-stat {
    padding: 15px 16px;
    background: #f8f8f8;
    border: 1px solid #eeeeee;
    border-radius: 8px;
}

.progress-stat-label {
    display: block;
    margin-bottom: 5px;

    color: #777777;

    font-size: 11px;
    font-weight: 600;
}

.audiobook-progress-stat strong {
    display: block;

    color: #18191c;

    font-size: 16px;
    font-weight: 700;
}


/* ---------------------------------------------------------
   PROGRESS BLOCK
--------------------------------------------------------- */

.audiobook-progress-block {
    margin-bottom: 22px;
}

.audiobook-progress-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    margin-bottom: 8px;

    color: #555555;

    font-size: 11px;
    font-weight: 600;
}

.audiobook-progress-heading strong {
    color: #18191c;
    font-size: 11px;
    font-weight: 700;
}


/* ---------------------------------------------------------
   PROGRESS BAR
--------------------------------------------------------- */

.audiobook-progress-track {
    width: 100%;
    height: 7px;

    overflow: hidden;

    background: #eeeeee;
    border-radius: 999px;
}

.audiobook-progress-bar {
    height: 100%;
    min-width: 0;

    background: #b30000;

    border-radius: inherit;

    transition: width 0.4s ease;
}


/* ---------------------------------------------------------
   STATUS MESSAGE
--------------------------------------------------------- */

.audiobook-progress-message {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-top: 6px;
    padding: 11px 13px;

    background: #f8f8f8;
    border: 1px solid #eeeeee;
    border-radius: 7px;

    color: #555555;

    font-size: 11px;
    font-weight: 500;
}

.audiobook-progress-message i {
    flex-shrink: 0;

    color: #b30000;

    font-size: 14px;
}

.audiobook-progress-message.success {
    color: #198754;
    background: #f5fbf7;
    border-color: #d8f0df;
}

.audiobook-progress-message.success i {
    color: #198754;
}

.audiobook-progress-message.error {
    color: #b42318;
    background: #fff7f6;
    border-color: #f3d2ce;
}

.audiobook-progress-message.error i {
    color: #b42318;
}


/* ---------------------------------------------------------
   EMPTY STATE
--------------------------------------------------------- */

.audiobook-progress-empty {
    padding: 32px 20px;

    text-align: center;
}

.empty-progress-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 52px;
    height: 52px;

    margin: 0 auto 14px;

    background: #f7f7f7;
    border: 1px solid #eeeeee;
    border-radius: 50%;

    color: #b30000;

    font-size: 21px;
}

.audiobook-progress-empty h3 {
    margin: 0 0 7px;

    color: #18191c;

    font-size: 15px;
    font-weight: 700;
}

.audiobook-progress-empty p {
    max-width: 430px;

    margin: 0 auto;

    color: #777777;

    font-size: 11px;
    line-height: 1.6;
}


/* ---------------------------------------------------------
   GENERATION STATUS
--------------------------------------------------------- */

.generation-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 86px;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 10px;
    font-weight: 700;

    line-height: 1;
}

.generation-status.draft,
.generation-status.queued,
.generation-status.pending {
    color: #666666;
    background: #f1f1f1;
}

.generation-status.generating {
    color: #8a5a00;
    background: #fff6dc;
}

.generation-status.assembling {
    color: #6f42c1;
    background: #f3edff;
}

.generation-status.completed {
    color: #198754;
    background: #eaf7ef;
}

.generation-status.failed,
.generation-status.cancelled {
    color: #b42318;
    background: #fff0ee;
}


/* ---------------------------------------------------------
   RESPONSIVE
--------------------------------------------------------- */

@media (max-width: 767.98px) {

    .audiobook-progress-stats {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .audiobook-progress-stat {
        padding: 13px 14px;
    }

    .audiobook-progress-stat strong {
        font-size: 15px;
    }

    .audiobook-progress-heading {
        font-size: 10px;
    }

}

/* =========================================================
   FINAL AUDIOBOOK
========================================================= */

.audiobook-panel {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
}

.audiobook-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
}

.audiobook-section-kicker {
    display: inline-block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #b30000;
}

.audiobook-panel-header h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #171717;
}

.audiobook-panel-header h2 i {
    color: #b30000;
}

.audiobook-panel-header p {
    margin: 8px 0 0;
    color: #777;
    font-size: 14px;
}


/* =========================================================
   FINAL RESULT
========================================================= */

.audiobook-result-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 32px;
    border-radius: 16px;
    background: #fafafa;
    border: 1px solid #eeeeee;
}

.audiobook-result-content .result-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 68px;
    height: 68px;
    margin-bottom: 18px;
    border-radius: 50%;
    background: #f2f2f2;
    font-size: 28px;
    color: #555;
}

.audiobook-result-content .result-icon.success {
    background: #edf8f1;
    color: #198754;
}

.audiobook-result-info h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #181818;
}

.audiobook-result-info p {
    max-width: 600px;
    margin: 9px auto 0;
    font-size: 14px;
    line-height: 1.7;
    color: #707070;
}


/* =========================================================
   AUDIO PLAYER
========================================================= */

.audiobook-result-player {
    width: 100%;
    max-width: 720px;
    margin-top: 26px;
}

.audiobook-player {
    display: block;
    width: 100%;
    height: 52px;
}


/* =========================================================
   DOWNLOAD BUTTON
========================================================= */

.audiobook-result-actions {
    display: flex;
    justify-content: center;
    margin-top: 22px;
}

.audiobook-download-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 46px;
    padding: 0 22px;
    border-radius: 10px;
    background: #b30000;
    color: #ffffff;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition:
        background 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.audiobook-download-btn:hover {
    background: #920000;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(179, 0, 0, 0.18);
}

.audiobook-download-btn i {
    font-size: 16px;
}


/* =========================================================
   EMPTY / PROCESSING STATES
========================================================= */

.audiobook-empty-result {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 220px;
    padding: 35px 25px;
    text-align: center;
    border: 1px dashed #dddddd;
    border-radius: 16px;
    background: #fafafa;
}

.audiobook-empty-result .result-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 62px;
    height: 62px;
    margin-bottom: 16px;
    border-radius: 50%;
    background: #f1f1f1;
    color: #777;
    font-size: 25px;
}

.audiobook-empty-result h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #202020;
}

.audiobook-empty-result p {
    max-width: 620px;
    margin: 9px auto 0;
    font-size: 14px;
    line-height: 1.7;
    color: #777;
}


/* =========================================================
   ERROR STATE
========================================================= */

.audiobook-empty-result.error {
    border-color: #f0cccc;
    background: #fff8f8;
}

.audiobook-empty-result.error .result-icon {
    background: #fdeaea;
    color: #dc3545;
}

.audiobook-empty-result.error h3 {
    color: #b02a37;
}

.audiobook-empty-result.error p {
    color: #8b4a50;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .audiobook-panel {
        padding: 20px;
        border-radius: 14px;
    }

    .audiobook-panel-header h2 {
        font-size: 19px;
    }

    .audiobook-result-content {
        padding: 25px 18px;
    }

    .audiobook-result-info h3 {
        font-size: 18px;
    }

    .audiobook-download-btn {
        width: 100%;
        max-width: 320px;
    }
}



</style>

@endpush

@push('scripts')

    <script>
       document.addEventListener('DOMContentLoaded', function () {

            const voiceSelect =
                document.getElementById('voice_id');

            const emptyState =
                document.getElementById('voice-details-empty');

            const detailsContent =
                document.getElementById('voice-details-content');

            const voiceName =
                document.getElementById('details-voice-name');

            const voiceDescription =
                document.getElementById('details-voice-description');

            const voiceLabels =
                document.getElementById('details-voice-labels');

            const previewButton =
                document.getElementById('details-preview-voice-btn');

            const saveVoiceButton =
                document.getElementById('save-voice-btn');

            const voiceSaveMessage =
                document.getElementById('voice-save-message');

            const generateAudiobookButton =
                document.getElementById('generate-audiobook-btn');


            if (!voiceSelect) {
                return;
            }


            let previewAudio = null;


            // ==========================================
            // CHANGEMENT DE VOIX
            // ==========================================

            voiceSelect.addEventListener('change', function () {

                const option =
                    this.options[this.selectedIndex];


                // ==========================================
                // AUCUNE VOIX
                // ==========================================

                if (!this.value) {

                    emptyState.style.display = 'flex';
                    detailsContent.style.display = 'none';

                    previewButton.disabled = true;

                    saveVoiceButton.disabled = true;

                    if (generateAudiobookButton) {
                        generateAudiobookButton.disabled = true;
                    }

                    voiceSaveMessage.classList.remove('show');
                    voiceSaveMessage.textContent = '';
                    voiceSaveMessage.style.color = '';


                    previewButton.innerHTML = `
                        <i class="bi bi-play-fill"></i>
                        <span>Écouter un aperçu</span>
                    `;


                    saveVoiceButton.innerHTML = `
                        <i class="bi bi-check2"></i>
                        <span>Utiliser cette voix</span>
                    `;


                    if (previewAudio) {

                        previewAudio.pause();
                        previewAudio.currentTime = 0;
                        previewAudio = null;

                    }

                    return;
                }


                // ==========================================
                // AFFICHER LES DÉTAILS
                // ==========================================

                emptyState.style.display = 'none';
                detailsContent.style.display = 'block';


                // ==========================================
                // ACTIVER ENREGISTREMENT
                // ==========================================

                saveVoiceButton.disabled = false;

                voiceSaveMessage.classList.remove('show');
                voiceSaveMessage.textContent = '';
                voiceSaveMessage.style.color = '';


                saveVoiceButton.innerHTML = `
                    <i class="bi bi-check2"></i>
                    <span>Utiliser cette voix</span>
                `;


                // ==========================================
                // NOM
                // ==========================================

                voiceName.textContent =
                    option.textContent.trim();


                // ==========================================
                // DESCRIPTION
                // ==========================================

                voiceDescription.textContent =
                    option.dataset.description ||
                    'Aucune description disponible.';


                // ==========================================
                // LABELS
                // ==========================================

                voiceLabels.innerHTML = '';

                const labels = [
                    {
                        key: 'language',
                        icon: 'bi-translate'
                    },
                    {
                        key: 'gender',
                        icon: 'bi-person'
                    },
                    {
                        key: 'accent',
                        icon: 'bi-soundwave'
                    },
                    {
                        key: 'age',
                        icon: 'bi-person-badge'
                    },
                    {
                        key: 'use-case',
                        icon: 'bi-mic'
                    }
                ];


                labels.forEach(function (item) {

                    const value =
                        option.dataset[item.key];

                    if (!value) {
                        return;
                    }


                    const badge =
                        document.createElement('span');

                    badge.className =
                        'audiobook-voice-label';


                    badge.innerHTML = `
                        <i class="bi ${item.icon}"></i>
                        ${value}
                    `;


                    voiceLabels.appendChild(badge);

                });


                // ==========================================
                // ARRÊTER ANCIEN PREVIEW
                // ==========================================

                if (previewAudio) {

                    previewAudio.pause();
                    previewAudio.currentTime = 0;
                    previewAudio = null;

                }


                // ==========================================
                // PREVIEW URL
                // ==========================================

                const previewUrl =
                    option.dataset.previewUrl;


                if (!previewUrl) {

                    previewButton.disabled = true;

                    previewButton.innerHTML = `
                        <i class="bi bi-play-fill"></i>
                        <span>Aucun aperçu disponible</span>
                    `;

                    return;
                }


                // ==========================================
                // PRÉCHARGEMENT
                // ==========================================

                previewButton.disabled = true;

                previewButton.innerHTML = `
                    <i class="bi bi-hourglass-split"></i>
                    <span>Préparation...</span>
                `;


                const audio = new Audio();

                audio.preload = 'auto';

                previewAudio = audio;

                audio.src = previewUrl;

                audio.load();


                // ==========================================
                // AUDIO PRÊT
                // ==========================================

                audio.addEventListener(
                    'canplaythrough',
                    function () {

                        if (previewAudio !== audio) {
                            return;
                        }


                        previewButton.disabled = false;

                        previewButton.innerHTML = `
                            <i class="bi bi-play-fill"></i>
                            <span>Écouter un aperçu</span>
                        `;

                    },
                    {
                        once: true
                    }
                );


                // ==========================================
                // AUDIO TERMINÉ
                // ==========================================

                audio.addEventListener(
                    'ended',
                    function () {

                        if (previewAudio !== audio) {
                            return;
                        }


                        previewButton.disabled = false;

                        previewButton.innerHTML = `
                            <i class="bi bi-play-fill"></i>
                            <span>Écouter un aperçu</span>
                        `;


                        audio.currentTime = 0;

                    }
                );


                // ==========================================
                // ERREUR AUDIO
                // ==========================================

                audio.addEventListener(
                    'error',
                    function () {

                        if (previewAudio !== audio) {
                            return;
                        }


                        previewButton.disabled = true;

                        previewButton.innerHTML = `
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Aperçu indisponible</span>
                        `;


                        previewAudio = null;


                        console.error(
                            'Impossible de charger le preview ElevenLabs :',
                            previewUrl
                        );

                    }
                );

            });


            // ==========================================
            // LECTURE DU PREVIEW
            // ==========================================

            previewButton.addEventListener(
                'click',
                async function () {

                    if (!previewAudio) {
                        return;
                    }


                    // PAUSE
                    if (!previewAudio.paused) {

                        previewAudio.pause();

                        previewButton.innerHTML = `
                            <i class="bi bi-play-fill"></i>
                            <span>Écouter un aperçu</span>
                        `;

                        return;
                    }


                    // PLAY
                    try {

                        await previewAudio.play();

                        previewButton.innerHTML = `
                            <i class="bi bi-pause-fill"></i>
                            <span>Pause</span>
                        `;

                    } catch (error) {

                        console.error(
                            'Erreur lors de la lecture du preview :',
                            error
                        );


                        previewButton.innerHTML = `
                            <i class="bi bi-exclamation-circle"></i>
                            <span>Erreur de lecture</span>
                        `;

                    }

                }
            );


            // ==========================================
            // ENREGISTRER LA VOIX
            // ==========================================

            saveVoiceButton.addEventListener(
                'click',
                async function () {

                    const voiceId =
                        voiceSelect.value;

                    const saveUrl =
                        saveVoiceButton.dataset.saveUrl;


                    if (!voiceId || !saveUrl) {
                        return;
                    }


                    saveVoiceButton.disabled = true;


                    saveVoiceButton.innerHTML = `
                        <i class="bi bi-hourglass-split"></i>
                        <span>Enregistrement...</span>
                    `;


                    voiceSaveMessage.classList.remove('show');
                    voiceSaveMessage.textContent = '';
                    voiceSaveMessage.style.color = '';


                    try {

                        const csrfToken =
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).getAttribute('content');


                        const response =
                            await fetch(saveUrl, {

                                method: 'POST',

                                headers: {
                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        csrfToken
                                },

                                body: JSON.stringify({
                                    voice_id: voiceId
                                })

                            });


                        const data =
                            await response.json();


                        if (
                            !response.ok ||
                            !data.success
                        ) {

                            throw new Error(
                                data.message ||
                                'Impossible d’enregistrer la voix.'
                            );

                        }


                        // ==========================================
                        // SUCCÈS
                        // ==========================================

                        saveVoiceButton.innerHTML = `
                            <i class="bi bi-check2"></i>
                            <span>Voix sélectionnée</span>
                        `;


                        saveVoiceButton.disabled = true;


                        voiceSaveMessage.textContent =
                            'La voix a été enregistrée avec succès.';

                        voiceSaveMessage.style.color =
                            '#198754';

                        voiceSaveMessage.classList.add('show');


                        // ==========================================
                        // ACTIVER GÉNÉRATION
                        // ==========================================

                        if (generateAudiobookButton) {

                            generateAudiobookButton.disabled =
                                false;

                        }


                        console.log(
                            'Voix enregistrée :',
                            data.voice_id
                        );


                    } catch (error) {

                        console.error(
                            'Erreur lors de l’enregistrement de la voix :',
                            error
                        );


                        saveVoiceButton.disabled = false;


                        saveVoiceButton.innerHTML = `
                            <i class="bi bi-check2"></i>
                            <span>Utiliser cette voix</span>
                        `;


                        voiceSaveMessage.textContent =
                            error.message ||
                            'Une erreur est survenue.';

                        voiceSaveMessage.style.color =
                            '#dc3545';

                        voiceSaveMessage.classList.add('show');

                    }

                }
            );


            // ==========================================
            // GÉNÉRER L'AUDIOBOOK
            // ==========================================

            if (generateAudiobookButton) {

                generateAudiobookButton.addEventListener(
                    'click',
                    async function () {

                        const url =
                            generateAudiobookButton.dataset.url;


                        if (!url) {
                            return;
                        }


                        // Vérification côté navigateur
                        if (!voiceSelect.value) {

                            alert(
                                'Veuillez sélectionner et enregistrer une voix avant de générer l’audiobook.'
                            );

                            return;
                        }


                        generateAudiobookButton.disabled = true;


                        const originalContent =
                            generateAudiobookButton.innerHTML;


                        generateAudiobookButton.innerHTML = `
                            <span
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true">
                            </span>

                            <span>
                                Préparation de la génération...
                            </span>
                        `;


                        try {

                            const csrfToken =
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute('content');


                            const response =
                                await fetch(url, {

                                    method: 'POST',

                                    headers: {
                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'Accept':
                                            'application/json',

                                        'Content-Type':
                                            'application/json',
                                    },

                                });


                            const data =
                                await response.json();


                            if (
                                !response.ok ||
                                !data.success
                            ) {

                                throw new Error(
                                    data.message ||
                                    'Une erreur est survenue.'
                                );

                            }


                            generateAudiobookButton.innerHTML = `
                                <i class="bi bi-check-circle"></i>
                                <span>Génération lancée</span>
                            `;


                            console.log(
                                'Génération lancée pour audiobook :',
                                data.audiobook_id
                            );


                        } catch (error) {

                            console.error(error);


                            generateAudiobookButton.disabled =
                                false;


                            generateAudiobookButton.innerHTML =
                                originalContent;


                            alert(
                                error.message ||
                                'Impossible de lancer la génération.'
                            );

                        }

                    }
                );

            }

        });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {

        const voiceSelect =
            document.getElementById('voice_id');

        const emptyState =
            document.getElementById('voice-details-empty');

        const detailsContent =
            document.getElementById('voice-details-content');

        const voiceName =
            document.getElementById('details-voice-name');

        const voiceDescription =
            document.getElementById('details-voice-description');

        const voiceLabels =
            document.getElementById('details-voice-labels');

        const previewButton =
            document.getElementById('details-preview-voice-btn');

        const saveVoiceButton =
            document.getElementById('save-voice-btn');

        const voiceSaveMessage =
            document.getElementById('voice-save-message');

        if (!voiceSelect) {
            return;
        }

        let previewAudio = null;


        // ==========================================
        // CHANGEMENT DE VOIX
        // ==========================================

        voiceSelect.addEventListener('change', function () {

            const option =
                this.options[this.selectedIndex];


            // ==========================================
            // AUCUNE VOIX
            // ==========================================

            if (!this.value) {

                emptyState.style.display = 'flex';
                detailsContent.style.display = 'none';

                previewButton.disabled = true;

                saveVoiceButton.disabled = true;

                voiceSaveMessage.classList.remove('show');
                voiceSaveMessage.textContent = '';

                previewButton.innerHTML = `
                    <i class="bi bi-play-fill"></i>
                    <span>Écouter un aperçu</span>
                `;

                saveVoiceButton.innerHTML = `
                    <i class="bi bi-check2"></i>
                    <span>Utiliser cette voix</span>
                `;

                if (previewAudio) {
                    previewAudio.pause();
                    previewAudio.currentTime = 0;
                    previewAudio = null;
                }

                return;
            }


            // ==========================================
            // AFFICHER INFOS DE LA VOIX
            // ==========================================

            emptyState.style.display = 'none';
            detailsContent.style.display = 'block';


            // ==========================================
            // ACTIVER BOUTON ENREGISTREMENT
            // ==========================================

            saveVoiceButton.disabled = false;

            voiceSaveMessage.classList.remove('show');
            voiceSaveMessage.textContent = '';

            saveVoiceButton.innerHTML = `
                <i class="bi bi-check2"></i>
                <span>Utiliser cette voix</span>
            `;


            // ==========================================
            // NOM
            // ==========================================

            voiceName.textContent =
                option.textContent.trim();


            // ==========================================
            // DESCRIPTION
            // ==========================================

            voiceDescription.textContent =
                option.dataset.description ||
                'Aucune description disponible.';


            // ==========================================
            // LABELS
            // ==========================================

            voiceLabels.innerHTML = '';

            const labels = [
                {
                    key: 'language',
                    icon: 'bi-translate'
                },
                {
                    key: 'gender',
                    icon: 'bi-person'
                },
                {
                    key: 'accent',
                    icon: 'bi-soundwave'
                },
                {
                    key: 'age',
                    icon: 'bi-person-badge'
                },
                {
                    key: 'use-case',
                    icon: 'bi-mic'
                }
            ];


            labels.forEach(function (item) {

                const value =
                    option.dataset[item.key];

                if (!value) {
                    return;
                }

                const badge =
                    document.createElement('span');

                badge.className =
                    'audiobook-voice-label';

                badge.innerHTML = `
                    <i class="bi ${item.icon}"></i>
                    ${value}
                `;

                voiceLabels.appendChild(badge);

            });


            // ==========================================
            // ARRÊTER ANCIEN PREVIEW
            // ==========================================

            if (previewAudio) {

                previewAudio.pause();
                previewAudio.currentTime = 0;
                previewAudio = null;

            }


            // ==========================================
            // PREVIEW URL
            // ==========================================

            const previewUrl =
                option.dataset.previewUrl;


            if (!previewUrl) {

                previewButton.disabled = true;

                previewButton.innerHTML = `
                    <i class="bi bi-play-fill"></i>
                    <span>Aucun aperçu disponible</span>
                `;

                return;
            }


            // ==========================================
            // PRÉCHARGEMENT
            // ==========================================

            previewButton.disabled = true;

            previewButton.innerHTML = `
                <i class="bi bi-hourglass-split"></i>
                <span>Préparation...</span>
            `;


            const audio = new Audio();

            audio.preload = 'auto';

            previewAudio = audio;

            audio.src = previewUrl;

            audio.load();


            // ==========================================
            // AUDIO PRÊT
            // ==========================================

            audio.addEventListener(
                'canplaythrough',
                function () {

                    if (previewAudio !== audio) {
                        return;
                    }

                    previewButton.disabled = false;

                    previewButton.innerHTML = `
                        <i class="bi bi-play-fill"></i>
                        <span>Écouter un aperçu</span>
                    `;

                },
                {
                    once: true
                }
            );


            // ==========================================
            // AUDIO TERMINÉ
            // ==========================================

            audio.addEventListener(
                'ended',
                function () {

                    if (previewAudio !== audio) {
                        return;
                    }

                    previewButton.disabled = false;

                    previewButton.innerHTML = `
                        <i class="bi bi-play-fill"></i>
                        <span>Écouter un aperçu</span>
                    `;

                    audio.currentTime = 0;

                }
            );


            // ==========================================
            // ERREUR AUDIO
            // ==========================================

            audio.addEventListener(
                'error',
                function () {

                    if (previewAudio !== audio) {
                        return;
                    }

                    previewButton.disabled = true;

                    previewButton.innerHTML = `
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Aperçu indisponible</span>
                    `;

                    previewAudio = null;

                    console.error(
                        'Impossible de charger le preview ElevenLabs :',
                        previewUrl
                    );

                }
            );

        });


        // ==========================================
        // LECTURE DU PREVIEW
        // ==========================================

        previewButton.addEventListener(
            'click',
            async function () {

                if (!previewAudio) {
                    return;
                }


                // PAUSE
                if (!previewAudio.paused) {

                    previewAudio.pause();

                    previewButton.innerHTML = `
                        <i class="bi bi-play-fill"></i>
                        <span>Écouter un aperçu</span>
                    `;

                    return;
                }


                // PLAY
                try {

                    await previewAudio.play();

                    previewButton.innerHTML = `
                        <i class="bi bi-pause-fill"></i>
                        <span>Pause</span>
                    `;

                } catch (error) {

                    console.error(
                        'Erreur lors de la lecture du preview :',
                        error
                    );

                    previewButton.innerHTML = `
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Erreur de lecture</span>
                    `;

                }

            }
        );


        // ==========================================
        // ENREGISTRER LA VOIX
        // ==========================================

        saveVoiceButton.addEventListener(
            'click',
            async function () {

                const voiceId =
                    voiceSelect.value;

                const saveUrl =
                    saveVoiceButton.dataset.saveUrl;

                if (!voiceId || !saveUrl) {
                    return;
                }

                // Désactiver pendant l'enregistrement
                saveVoiceButton.disabled = true;

                saveVoiceButton.innerHTML = `
                    <i class="bi bi-hourglass-split"></i>
                    <span>Enregistrement...</span>
                `;

                voiceSaveMessage.classList.remove('show');
                voiceSaveMessage.textContent = '';

                try {

                    const csrfToken =
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).getAttribute('content');

                    const response =
                        await fetch(saveUrl, {

                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },

                            body: JSON.stringify({
                                voice_id: voiceId
                            })

                        });


                    const data =
                        await response.json();


                    if (!response.ok || !data.success) {

                        throw new Error(
                            data.message ||
                            'Impossible d’enregistrer la voix.'
                        );

                    }


                    // ==========================================
                    // SUCCÈS
                    // ==========================================

                    saveVoiceButton.innerHTML = `
                        <i class="bi bi-check2"></i>
                        <span>Voix sélectionnée</span>
                    `;

                    saveVoiceButton.disabled = true;

                    voiceSaveMessage.textContent =
                        'La voix a été enregistrée avec succès.';

                    voiceSaveMessage.classList.add('show');


                    console.log(
                        'Voix enregistrée :',
                        data.voice_id
                    );


                } catch (error) {

                    console.error(
                        'Erreur lors de l’enregistrement de la voix :',
                        error
                    );


                    // ==========================================
                    // ERREUR
                    // ==========================================

                    saveVoiceButton.disabled = false;

                    saveVoiceButton.innerHTML = `
                        <i class="bi bi-check2"></i>
                        <span>Utiliser cette voix</span>
                    `;

                    voiceSaveMessage.textContent =
                        error.message ||
                        'Une erreur est survenue.';

                    voiceSaveMessage.style.color = '#dc3545';

                    voiceSaveMessage.classList.add('show');

                }

            }
        );

      });
    </script>

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

            const completedChunks =
                document.getElementById(
                    'audiobook-completed-chunks'
                );

            const generatedCharacters =
                document.getElementById(
                    'audiobook-generated-characters'
                );

            const chunkProgressValue =
                document.getElementById(
                    'audiobook-chunk-progress-value'
                );

            const characterProgressValue =
                document.getElementById(
                    'audiobook-character-progress-value'
                );

            const chunkProgressBar =
                document.getElementById(
                    'audiobook-chunk-progress-bar'
                );

            const characterProgressBar =
                document.getElementById(
                    'audiobook-character-progress-bar'
                );

            const statusBadge =
                document.getElementById(
                    'audiobook-generation-status'
                );

            function formatNumber(number) {
                return new Intl.NumberFormat('fr-FR')
                    .format(number);
            }

            function updateProgress(data) {

                if (completedChunks) {
                    completedChunks.textContent =
                        `${formatNumber(data.completed_chunks)} / ` +
                        `${formatNumber(data.total_chunks)}`;
                }

                if (generatedCharacters) {
                    generatedCharacters.textContent =
                        `${formatNumber(data.generated_characters)} / ` +
                        `${formatNumber(data.total_characters)}`;
                }

                if (chunkProgressValue) {
                    chunkProgressValue.textContent =
                        `${data.chunk_progress}%`;
                }

                if (characterProgressValue) {
                    characterProgressValue.textContent =
                        `${data.character_progress}%`;
                }

                if (chunkProgressBar) {
                    chunkProgressBar.style.width =
                        `${data.chunk_progress}%`;
                }

                if (characterProgressBar) {
                    characterProgressBar.style.width =
                        `${data.character_progress}%`;
                }

                if (statusBadge) {

                    statusBadge.className =
                        `generation-status ${data.status}`;

                    const labels = {
                        draft: 'En attente',
                        queued: 'En attente',
                        generating: 'Génération en cours',
                        assembling: 'Assemblage en cours',
                        completed: 'Terminé',
                        failed: 'Échec',
                        cancelled: 'Annulé',
                    };

                    statusBadge.textContent =
                        labels[data.status] ?? 'En attente';
                }
            }

            async function fetchProgress() {

                try {

                    const response =
                        await fetch(statusUrl, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                            },
                            cache: 'no-store',
                        });

                    if (!response.ok) {
                        throw new Error(
                            'Impossible de récupérer la progression.'
                        );
                    }

                    const data =
                        await response.json();

                    if (!data.success) {
                        throw new Error(
                            data.message ||
                            'Impossible de récupérer la progression.'
                        );
                    }

                    updateProgress(data);

                    return data;

                } catch (error) {

                    console.error(
                        'Audiobook progress error:',
                        error
                    );

                    return null;
                }
            }

            async function pollProgress() {

                const data = await fetchProgress();

                if (!data) {
                    return;
                }

                if (
                    data.status === 'completed' ||
                    data.status === 'failed' ||
                    data.status === 'cancelled'
                ) {
                    return;
                }

                setTimeout(
                    pollProgress,
                    3000
                );
            }

            pollProgress();
        });
    </script>

@endpush
