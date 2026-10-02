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
            <div class="col-lg-7">

                <div class="audiobook-config-card">

                    <label for="voice_id">
                        <i class="bi bi-mic"></i>
                        Voix
                    </label>

                    <select
                        id="voice_id"
                        name="voice_id"
                        class="form-select audiobook-select">

                        <option value="">
                            Sélectionner une voix
                        </option>

                    </select>

                    <small>
                        La voix utilisée pour la narration de
                        l’ensemble de l’audiobook.
                    </small>

                </div>

            </div>


            {{-- MODEL --}}
            <div class="col-lg-5">

                <div class="audiobook-config-card fixed">

                    <label>
                        <i class="bi bi-cpu"></i>
                        Modèle
                    </label>

                    <div class="model-display">

                        <div class="model-icon">
                            <i class="bi bi-soundwave"></i>
                        </div>

                        <div>

                            <strong>
                                Eleven v3
                            </strong>

                            <small>
                                Modèle de génération audio
                            </small>

                        </div>

                        <i class="bi bi-lock-fill model-lock"></i>

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
                disabled>

                <i class="bi bi-headphones"></i>

                <span>
                    Générer l’audiobook
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

            <span class="generation-status pending">
                En attente
            </span>

        </div>


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

</style>

@endpush
