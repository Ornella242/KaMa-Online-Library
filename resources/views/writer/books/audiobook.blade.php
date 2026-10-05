@extends('layouts.app')

@section('meta')

@section('title', 'Demander un audiobook — '.$book->title)
@section('page-title', 'Demander un audiobook')

@php
    $characterCount = $statistics['characters'] ?? 0;
    $wordCount = $statistics['words'] ?? 0;
    $chapterCount = $statistics['section_count'] ?? 0;
    $pageCount = $statistics['page_count'] ?? 0;

    /*
     * Coût réel estimé de génération ElevenLabs.
     * On n'utilise pas estimated_cost car celui-ci contient
     * également l'ancienne marge de 10 % du service Analyzer.
     */
    $estimatedCost = (float) ($statistics['elevenlabs_cost'] ?? 0);

    /*
     * Frais KaMa = 20 % du prix de l'ebook.
     */
    $kamaFee = round(
        (float) $book->price * 0.20,
        2
    );

    /*
     * Total de la demande.
     */
    $totalAmount = round(
        $estimatedCost + $kamaFee,
        2
    );
@endphp
@section('content')
    <div class="author-audiobook-page">

        <div class="container py-4 py-lg-5">

            {{-- =====================================================
                BACK
            ====================================================== --}}
            <div class="mb-4">
                <a href="{{ route('writer.books') }}" class="audiobook-back">
                    <i class="bi bi-arrow-left"></i>
                    Retour à mes livres
                </a>
            </div>


            {{-- =====================================================
                HERO
            ====================================================== --}}
            <section class="audiobook-hero">

                <div class="row g-4 g-xl-5 align-items-center">

                    {{-- COVER --}}
                    <div class="col-lg-3 col-md-4 text-center">

                        <div class="audiobook-cover">

                            <img
                                src="{{ $book->cover_image
                                    ? asset('storage/'.$book->cover_image)
                                    : asset('assets/images/book/01.jpg') }}"
                                alt="{{ $book->title }}"
                            >

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

                            <span class="audiobook-tag status published">

                                <i class="bi bi-check-circle"></i>

                                Publié

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

                                <strong>
                                    Demander la version audio
                                </strong>

                                <p>
                                    Demandez la version audio de votre livre.
                                    Après paiement, l'équipe KaMa se chargera
                                    de la génération de votre audiobook et vous
                                    serez informé de son avancement.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>



            {{-- =====================================================
                STEP 01 — ANALYSIS
            ====================================================== --}}
            <section class="audiobook-panel mt-4">

                <div class="audiobook-panel-header">

                    <div>

                        <span class="audiobook-section-kicker">
                            ÉTAPE 01
                        </span>

                        <h2>
                            <i class="bi bi-file-earmark-text"></i>
                            Résumé et coût
                        </h2>

                        <p>
                            Consultez les informations utilisées pour
                            calculer le coût de votre demande.
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
                                Chapitres
                            </span>

                            <strong>
                                {{ number_format(
                                    $chapterCount,
                                    0,
                                    ',',
                                    ' '
                                ) }}
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


                {{-- =================================================
                    PRICING
                ================================================== --}}
                <div class="audiobook-pricing-card">

                    <div class="pricing-header">

                        <div>

                            <span>
                                ESTIMATION DE LA COMMANDE
                            </span>

                            <strong>
                                Coût de la version audio
                            </strong>

                        </div>

                        <div class="pricing-icon">
                            <i class="bi bi-calculator"></i>
                        </div>

                    </div>


                    <div class="pricing-lines">

                        {{-- ELEVENLABS --}}
                        <div class="pricing-line">

                            <div>

                                <span>
                                    Coût de génération
                                </span>

                                <small>
                                    ElevenLabs v3 ·
                                    {{ number_format(
                                        $characterCount,
                                        0,
                                        ',',
                                        ' '
                                    ) }}
                                    caractères
                                </small>

                            </div>

                            <strong>
                                {{ number_format(
                                    $estimatedCost,
                                    2,
                                    ',',
                                    ' '
                                ) }}
                                $
                            </strong>

                        </div>


                        {{-- KAMA --}}
                        <div class="pricing-line">

                            <div>

                                <span>
                                    Frais de publication KaMa
                                </span>

                                <small>
                                    20 % du prix de l'ebook
                                    ({{ number_format(
                                        (float) $book->price,
                                        2,
                                        ',',
                                        ' '
                                    ) }} $)
                                </small>

                            </div>

                            <strong>
                                {{ number_format(
                                    $kamaFee,
                                    2,
                                    ',',
                                    ' '
                                ) }}
                                $
                            </strong>

                        </div>

                    </div>


                    {{-- TOTAL --}}
                    <div class="pricing-total">

                        <div>

                            <span>
                                Total à payer
                            </span>

                            <small>
                                Montant total de votre demande
                            </small>

                        </div>

                        <strong>
                            {{ number_format(
                                $totalAmount,
                                2,
                                ',',
                                ' '
                            ) }}
                            $
                        </strong>

                    </div>

                </div>

            </section>



            {{-- =====================================================
                STEP 02 — VOICE
            ====================================================== --}}
            <section class="audiobook-panel mt-4">

                <div class="audiobook-panel-header">

                    <div>

                        <span class="audiobook-section-kicker">
                            ÉTAPE 02
                        </span>

                        <h2>
                            <i class="bi bi-mic"></i>
                            Choisir la voix
                        </h2>

                        <p>
                            Choisissez la voix qui sera utilisée
                            pour la narration de votre audiobook.
                        </p>

                    </div>

                </div>


                <div class="audiobook-config-card audiobook-voice-selector">

                    <div class="audiobook-field-header">

                        <label
                            for="voice_id"
                            class="audiobook-field-label"
                        >

                            <i class="bi bi-mic"></i>

                            <strong>
                                Voix de narration
                            </strong>

                        </label>

                    </div>


                    <select
                        id="voice_id"
                        name="voice_id"
                        class="form-select audiobook-voice-select"
                    >

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


                    {{-- EMPTY --}}
                    <div
                        id="voice-details-empty"
                        class="audiobook-voice-details-empty"
                    >

                        <i class="bi bi-mic"></i>

                        <span>
                            Sélectionnez une voix pour afficher
                            ses caractéristiques.
                        </span>

                    </div>


                    {{-- DETAILS --}}
                    <div
                        id="voice-details-content"
                        class="audiobook-voice-details-content"
                        style="display:none;"
                    >

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
                            class="audiobook-voice-details-description"
                        ></p>


                        <div
                            id="details-voice-labels"
                            class="audiobook-voice-details-labels"
                        ></div>


                        <div class="voice-actions">

                            <button
                                type="button"
                                id="details-preview-voice-btn"
                                class="audiobook-voice-preview-btn"
                                disabled
                            >

                                <i class="bi bi-play-fill"></i>

                                <span>
                                    Écouter un aperçu
                                </span>

                            </button>


                            <button
                                type="button"
                                id="select-voice-btn"
                                class="audiobook-voice-save-btn"
                                disabled
                            >

                                <i class="bi bi-check2"></i>

                                <span>
                                    Sélectionner cette voix
                                </span>

                            </button>

                        </div>


                        <div
                            id="voice-selection-message"
                            class="audiobook-voice-save-message"
                        ></div>

                    </div>

                </div>


                {{-- INFORMATION --}}
                <div class="audiobook-generation-info">

                    <div class="generation-info-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>

                        <strong>
                            La génération est effectuée par KaMa
                        </strong>

                        <p>
                            Après votre paiement, votre demande sera
                            transmise à l'équipe KaMa. La génération
                            de l'audiobook sera ensuite effectuée
                            par l'administration.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                    SAVE REQUEST
                ================================================== --}}
                <div class="audiobook-request-wrapper">

                    <form
                        method="POST"
                        action="{{ route(
                            'writer.books.audiobook.store',
                            ['book' => $book->id]
                        ) }}"
                        id="audiobook-request-form"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="voice_id"
                            id="selected_voice_id"
                        >

                        <button
                            type="submit"
                            id="save-audiobook-request-btn"
                            class="audiobook-generate-btn"
                            disabled
                        >

                            <i class="bi bi-send"></i>

                            <span>
                                Enregistrer la demande
                            </span>

                        </button>

                    </form>


                    <small>
                        Sélectionnez une voix avant d'enregistrer
                        votre demande.
                    </small>

                </div>


                {{-- =================================================
                    EXISTING REQUEST
                ================================================== --}}
                @if(isset($audiobookRequest) && $audiobookRequest)

                    @if($audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_PENDING_PAYMENT)

                        <div class="audiobook-request-saved">

                            <div class="request-saved-icon">

                                <i class="bi bi-check-circle-fill"></i>

                            </div>

                            <div class="request-saved-content">

                                <h3>
                                    Demande enregistrée
                                </h3>

                                <p>
                                    Votre demande d'audiobook a été
                                    enregistrée. Procédez maintenant
                                    au paiement pour transmettre votre
                                    commande à KaMa.
                                </p>

                                {{-- Stripe et Pawapay àajouter --}}
                                <form
                                        method="POST"
                                        action="{{ route(
                                            'writer.books.audiobook.pay',
                                            ['book' => $book->id]
                                        ) }}"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="audiobook-generate-btn"
                                        >
                                            <i class="bi bi-credit-card"></i>
                                            <span>Procéder à la commande</span>
                                        </button>
                                </form>

                            </div>

                        </div>

                    @elseif(
                        $audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_PAID ||
                        $audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_QUEUED ||
                        $audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_GENERATING ||
                        $audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_ASSEMBLING
                    )

                        <div class="audiobook-request-saved">

                            <div class="request-saved-icon">

                                <i class="bi bi-hourglass-split"></i>

                            </div>

                            <div class="request-saved-content">

                                <h3>
                                    Demande en cours
                                </h3>

                                <p>
                                    Votre demande d'audiobook a bien été
                                    prise en compte. Son traitement est
                                    actuellement en cours.
                                </p>

                            </div>

                        </div>

                    @elseif($audiobookRequest->status === \App\Models\AudiobookRequest::STATUS_COMPLETED)

                        <div class="audiobook-request-saved">

                            <div class="request-saved-icon">

                                <i class="bi bi-check-circle-fill"></i>

                            </div>

                            <div class="request-saved-content">

                                <h3>
                                    Audiobook disponible
                                </h3>

                                <p>
                                    Votre audiobook est maintenant disponible.
                                </p>

                            </div>

                        </div>

                    @endif

                @endif

            </section>

        </div>

    </div>

@endsection


{{-- =========================================================
     STYLES
========================================================= --}}

@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.author-audiobook-page {
    padding: 8px 4px 50px;
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


/* =========================================================
   HERO TEXT
========================================================= */

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


/* =========================================================
   PURPOSE
========================================================= */

.audiobook-purpose {
    display: flex;
    align-items: center;
    gap: 14px;

    max-width: 700px;

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

    line-height: 1.55;
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
   PRICING
========================================================= */

.audiobook-pricing-card {
    margin-top: 18px;

    border-radius: 18px;

    background:
        linear-gradient(
            110deg,
            #fff8f8,
            #fff
        );

    border: 1px solid #f0dddd;

    overflow: hidden;
}

.pricing-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 18px 20px;

    border-bottom: 1px solid #f0dddd;
}

.pricing-header span {
    display: block;

    margin-bottom: 3px;

    color: #999;

    font-size: .66rem;
    font-weight: 800;

    letter-spacing: .08em;
}

.pricing-header strong {
    display: block;

    color: #222;

    font-size: .95rem;
}

.pricing-icon {
    width: 42px;
    height: 42px;

    display: grid;
    place-items: center;

    border-radius: 11px;

    background: #b30000;

    color: #fff;
}


/* =========================================================
   PRICING LINES
========================================================= */

.pricing-lines {
    padding: 4px 20px;
}

.pricing-line {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 15px 0;

    border-bottom: 1px solid #f0e7e5;
}

.pricing-line:last-child {
    border-bottom: 0;
}

.pricing-line span {
    display: block;

    color: #333;

    font-size: .84rem;
    font-weight: 700;
}

.pricing-line small {
    display: block;

    margin-top: 3px;

    color: #999;

    font-size: .7rem;
}

.pricing-line strong {
    white-space: nowrap;

    color: #333;

    font-size: .95rem;
}


/* =========================================================
   TOTAL
========================================================= */

.pricing-total {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 18px 20px;

    background: #fff3f3;

    border-top: 1px solid #f0d5d5;
}

.pricing-total span {
    display: block;

    color: #222;

    font-size: .9rem;
    font-weight: 800;
}

.pricing-total small {
    display: block;

    margin-top: 3px;

    color: #999;

    font-size: .7rem;
}

.pricing-total strong {
    color: #b30000;

    font-size: 1.4rem;
}


/* =========================================================
   VOICE
========================================================= */

.audiobook-config-card {
    padding: 18px;

    border-radius: 16px;

    background: #faf8f7;

    border: 1px solid #eee9e7;
}

.audiobook-field-label {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 10px;

    color: #333;

    font-size: .85rem;
    font-weight: 800;
}

.audiobook-field-label i {
    color: #b30000;
}

.audiobook-voice-select {
    min-height: 48px;

    border-color: #ddd5d2;

    border-radius: 12px;

    box-shadow: none;
}

.audiobook-voice-select:focus {
    border-color: #b30000;

    box-shadow:
        0 0 0 3px rgba(179,0,0,.08);
}


/* =========================================================
   VOICE DETAILS
========================================================= */

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


/* =========================================================
   VOICE ACTIONS
========================================================= */

.voice-actions {
    display: flex;
    flex-wrap: wrap;

    gap: 8px;
}

.audiobook-voice-preview-btn,
.audiobook-voice-save-btn {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 8px 13px;

    border-radius: 7px;

    font-size: 11px;
    font-weight: 600;

    transition: .2s ease;
}

.audiobook-voice-preview-btn {
    background: #b30000;

    border: 1px solid #b30000;

    color: #fff;
}

.audiobook-voice-preview-btn:hover:not(:disabled) {
    background: #920000;

    border-color: #920000;
}

.audiobook-voice-save-btn {
    background: #18191c;

    border: 1px solid #18191c;

    color: #fff;
}

.audiobook-voice-save-btn:hover:not(:disabled) {
    background: #b30000;

    border-color: #b30000;
}

.audiobook-voice-preview-btn:disabled,
.audiobook-voice-save-btn:disabled {
    opacity: .45;

    cursor: not-allowed;
}

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

/* =========================================================
   VOICE MESSAGE
========================================================= */

.audiobook-voice-save-message {
    display: none;

    margin-top: 10px;

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
   INFO
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
   SAVE REQUEST
========================================================= */

.audiobook-request-wrapper {
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

.audiobook-request-wrapper small {
    margin-top: 8px;

    color: #aaa;

    font-size: .7rem;
}


/* =========================================================
   REQUEST SAVED
========================================================= */

.audiobook-request-saved {
    display: flex;

    align-items: center;

    gap: 16px;

    margin-top: 25px;

    padding: 20px;

    border-radius: 16px;

    background: #f5fbf7;

    border: 1px solid #d8f0df;
}

.request-saved-icon {
    flex: 0 0 48px;

    width: 48px;
    height: 48px;

    display: grid;
    place-items: center;

    border-radius: 50%;

    background: #e2f5e9;

    color: #198754;

    font-size: 1.2rem;
}

.request-saved-content h3 {
    margin: 0 0 5px;

    color: #198754;

    font-size: .95rem;
}

.request-saved-content p {
    margin: 0 0 12px;

    color: #666;

    font-size: .78rem;

    line-height: 1.55;
}

.audiobook-payment-btn {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 9px 15px;

    border-radius: 8px;

    background: #18191c;

    color: #fff;

    text-decoration: none;

    font-size: .75rem;

    font-weight: 700;

    transition: .2s ease;
}

.audiobook-payment-btn:hover {
    background: #b30000;

    color: #fff;

    transform: translateY(-1px);
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

    .audiobook-pricing-card {
        border-radius: 15px;
    }

    .pricing-line,
    .pricing-total {
        align-items: flex-start;
        flex-direction: column;
    }

    .pricing-line strong,
    .pricing-total strong {
        align-self: flex-end;
    }

    .audiobook-request-saved {
        align-items: flex-start;
    }

}

@media (max-width: 575px) {

    .author-audiobook-page {
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

    .voice-actions {
        flex-direction: column;
    }

    .audiobook-voice-preview-btn,
    .audiobook-voice-save-btn {
        width: 100%;
    }

}

</style>

@endpush


{{-- =========================================================
     SCRIPTS
========================================================= --}}

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

    const selectVoiceButton =
        document.getElementById('select-voice-btn');

    const voiceSelectionMessage =
        document.getElementById('voice-selection-message');

    const saveRequestButton =
        document.getElementById('save-audiobook-request-btn');

    const selectedVoiceInput =
        document.getElementById('selected_voice_id');


    if (!voiceSelect) {
        return;
    }


    let previewAudio = null;

    let selectedVoiceId = null;


    // =====================================================
    // CHANGEMENT DE VOIX
    // =====================================================

    voiceSelect.addEventListener('change', function () {

        const option =
            this.options[this.selectedIndex];


        selectedVoiceId = null;

        selectedVoiceInput.value = '';

        saveRequestButton.disabled = true;


        voiceSelectionMessage.classList.remove('show');

        voiceSelectionMessage.innerHTML = '';


        /*
         * Réactiver le bouton de sélection
         * lorsqu'une nouvelle voix est choisie.
         */
        selectVoiceButton.disabled = !this.value;

        selectVoiceButton.innerHTML = `
            <i class="bi bi-check2"></i>
            <span>Sélectionner cette voix</span>
        `;


        if (!this.value) {

            emptyState.style.display = 'flex';

            detailsContent.style.display = 'none';

            previewButton.disabled = true;

            return;
        }


        emptyState.style.display = 'none';

        detailsContent.style.display = 'block';


        // =================================================
        // NOM
        // =================================================

        voiceName.textContent =
            option.textContent.trim();


        // =================================================
        // DESCRIPTION
        // =================================================

        voiceDescription.textContent =
            option.dataset.description ||
            'Aucune description disponible.';


        // =================================================
        // LABELS
        // =================================================

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


        // =================================================
        // RESET PREVIEW
        // =================================================

        if (previewAudio) {

            previewAudio.pause();

            previewAudio.currentTime = 0;

            previewAudio = null;

        }


        const previewUrl =
            option.dataset.previewUrl;


        if (!previewUrl) {

            previewButton.disabled = true;

            previewButton.innerHTML = `
                <i class="bi bi-play-fill"></i>
                <span>Aucun aperçu disponible</span>
            `;

        } else {

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

                }
            );

        }

    });


    // =====================================================
    // PREVIEW
    // =====================================================

    previewButton.addEventListener(
        'click',
        async function () {

            if (!previewAudio) {
                return;
            }


            if (!previewAudio.paused) {

                previewAudio.pause();

                previewButton.innerHTML = `
                    <i class="bi bi-play-fill"></i>
                    <span>Écouter un aperçu</span>
                `;

                return;
            }


            try {

                await previewAudio.play();

                previewButton.innerHTML = `
                    <i class="bi bi-pause-fill"></i>
                    <span>Pause</span>
                `;

            } catch (error) {

                console.error(error);

            }

        }
    );


    // =====================================================
    // SELECT VOICE
    // =====================================================

    selectVoiceButton.addEventListener(
        'click',
        function () {

            const voiceId =
                voiceSelect.value;


            if (!voiceId) {
                return;
            }


            selectedVoiceId = voiceId;

            /*
             * Le formulaire POST classique utilisera
             * cette valeur.
             */
            selectedVoiceInput.value =
                voiceId;


            saveRequestButton.disabled = false;


            selectVoiceButton.innerHTML = `
                <i class="bi bi-check2"></i>
                <span>Voix sélectionnée</span>
            `;


            selectVoiceButton.disabled = true;


            voiceSelectionMessage.innerHTML = `
                <i class="bi bi-check-circle-fill"></i>
                Cette voix sera utilisée pour votre audiobook.
            `;


            voiceSelectionMessage.classList.add('show');

        }
    );

});

</script>

@endpush