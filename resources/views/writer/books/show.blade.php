@extends('layouts.app')

@section('meta')
@php
    $coverUrl = $book->cover_image
        ? config('app.url').'/storage/'.$book->cover_image
        : asset('assets/images/book/01.jpg');

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

    $fileSizeLabel = $book->file_size
        ? number_format($book->file_size / 1048576, 2).' MB'
        : null;
    $fileName = $book->original_file_name
        ?: ($book->file_path ? basename($book->file_path) : null);
@endphp
<meta property="og:title" content="{{ $book->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($book->short_description), 200) }}">
<meta property="og:image" content="{{ $coverUrl }}">
<meta property="og:image:secure_url" content="{{ $coverUrl }}">
<meta property="og:type" content="book">
<meta property="og:url" content="{{ config('app.url').'/books/'.$book->id }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $coverUrl }}">
@endsection

@section('content')
<div class="writer-book-show">
    <div class="container py-4 py-lg-5">

        <div class="mb-4">
            <a href="{{ route('writer.books') }}" class="writer-book-back">
                <i class="bi bi-arrow-left"></i>
                Retour à mes livres
            </a>
        </div>

        {{-- HERO --}}
        <section class="writer-book-hero">
            <div class="row g-4 g-xl-5 align-items-center">
                <div class="col-lg-4 text-center">
                    <div class="writer-book-cover">
                        <img
                            src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}"
                            alt="{{ $book->title }}">
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="writer-book-tags">
                        <span class="tag type">
                            <i class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-text' }}"></i>
                            {{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}
                        </span>
                        @if($book->category)
                            <span class="tag">{{ $book->category->name }}</span>
                        @endif
                        @if($book->subcategory)
                            <span class="tag">{{ $book->subcategory->name }}</span>
                        @endif
                        <span class="tag status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>

                    <h1 class="writer-book-title text-white">{{ $book->title }}</h1>

                    <div class="writer-book-author">
                        <i class="bi bi-person-circle"></i>
                        {{ $book->author?->firstname }} {{ $book->author?->lastname }}
                    </div>

                    @if($book->short_description)
                        <p class="writer-book-lead">{{ $book->short_description }}</p>
                    @endif

                    <div class="writer-book-meta-grid">
                        <div>
                            <small>Prix</small>
                            <strong>{{ number_format((float) $book->price, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div>
                            <small>Langue</small>
                            <strong>{{ $book->language ?: '—' }}</strong>
                        </div>
                        <div>
                            <small>Année</small>
                            <strong>{{ $book->publication_year ?: '—' }}</strong>
                        </div>
                        @if($book->type === 'ebook')
                            <div>
                                <small>Pages</small>
                                <strong>{{ $book->pages ?: '—' }}</strong>
                            </div>
                        @else
                            <div>
                                <small>Durée</small>
                                <strong>{{ $book->duration ?: '—' }}</strong>
                            </div>
                        @endif
                        <div>
                            <small>Ajouté le</small>
                            <strong>{{ $book->created_at?->format('d/m/Y') }}</strong>
                        </div>
                        <div>
                            <small>Mis à jour</small>
                            <strong>{{ $book->updated_at?->format('d/m/Y') }}</strong>
                        </div>
                    </div>

                    <div class="writer-book-actions">
                        @if($book->type === 'ebook' && $book->file_path)
                            <a href="{{ route('writer.books.preview.file', $book) }}"
                               target="_blank"
                               class="btn-primary-action">
                                <i class="bi bi-book-half"></i>
                                Lire le livre
                            </a>
                        @elseif($book->type === 'audio' && $book->file_path)
                            <div class="writer-book-audio">
                                <audio controls preload="metadata">
                                    <source src="{{ route('writer.books.audio', $book) }}" type="audio/mpeg">
                                </audio>
                            </div>
                        @endif

                        @if(in_array($book->status, ['draft', 'revision_required', 'waiting_review', 'under_review'], true))
                            <a href="{{ route('writer.books.edit', $book) }}" class="btn-secondary-action">
                                <i class="bi bi-pencil"></i>
                                Modifier
                            </a>
                        @endif

                        @if($book->status === 'published')
                            <a href="{{ route('writer.books.boost', $book) }}" class="btn-secondary-action">
                                <i class="bi bi-share"></i>
                                Booster
                            </a>
                            <a href="{{ route('writer.books.sponsor', $book) }}" class="btn-secondary-action">
                                <i class="bi bi-megaphone"></i>
                                Sponsoriser
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if($book->status === 'revision_required' && $book->rejection_reason)
            <div class="writer-book-alert warning mt-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Corrections demandées</strong>
                    <p>{{ $book->rejection_reason }}</p>
                </div>
            </div>
        @endif

        @if($activeSponsorship)
            <div class="writer-book-alert success mt-4">
                <i class="bi bi-stars"></i>
                <div>
                    <strong>Sponsoring actif</strong>
                    <p>Ce livre est mis en avant jusqu’au {{ $activeSponsorship->ends_at?->format('d/m/Y') }}
                        @if($activeSponsorship->plan)
                            (formule {{ $activeSponsorship->plan->name }})
                        @endif.
                    </p>
                </div>
            </div>
        @endif

        {{-- DETAILS GRID --}}
        <div class="row g-4 mt-2">
            <div class="col-lg-7">
                <section class="writer-book-panel">
                    <header>
                        <h2><i class="bi bi-text-paragraph"></i> Description</h2>
                    </header>

                    @if($book->short_description)
                        <div class="writer-book-short">
                            <h3>Résumé court</h3>
                            <p>{{ $book->short_description }}</p>
                        </div>
                    @endif

                    <div class="writer-book-preview-block">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h3 class="mb-0">Aperçu lecteur</h3>
                            <span class="preview-chip">
                                @if($book->type === 'audio')
                                    Extrait texte
                                @elseif($book->preview_type === 'pages')
                                    Pages {{ $book->preview_start_page }}–{{ $book->preview_end_page }}
                                @else
                                    Extrait texte
                                @endif
                            </span>
                        </div>

                        @if($book->preview_type === 'pages' && $book->type === 'ebook')
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="book-wrapper">
                                    <div id="book-preview"></div>
                                </div>
                            </div>
                        @elseif($book->long_description)
                            <div class="book-text-preview">
                                {!! $book->long_description !!}
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucun aperçu disponible pour ce livre.</p>
                        @endif
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="writer-book-panel">
                    <header>
                        <h2><i class="bi bi-info-circle"></i> Fiche technique</h2>
                    </header>

                    <dl class="writer-book-specs">
                        <div>
                            <dt>Référence</dt>
                            <dd>#{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                        <div>
                            <dt>Type</dt>
                            <dd>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</dd>
                        </div>
                        <div>
                            <dt>Catégorie</dt>
                            <dd>{{ $book->category?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Sous-catégorie</dt>
                            <dd>{{ $book->subcategory?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Langue</dt>
                            <dd>{{ $book->language ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Année de publication</dt>
                            <dd>{{ $book->publication_year ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Prix de vente</dt>
                            <dd>{{ number_format((float) $book->price, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        @if($book->type === 'ebook')
                            <div>
                                <dt>Nombre de pages</dt>
                                <dd>{{ $book->pages ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Type d’aperçu</dt>
                                <dd>{{ $book->preview_type === 'pages' ? 'Pages du livre' : 'Extrait texte' }}</dd>
                            </div>
                            @if($book->preview_type === 'pages')
                                <div>
                                    <dt>Pages d’aperçu</dt>
                                    <dd>{{ $book->preview_start_page }} à {{ $book->preview_end_page }}</dd>
                                </div>
                            @endif
                        @else
                            <div>
                                <dt>Durée audio</dt>
                                <dd>{{ $book->duration ?: '—' }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt>Statut éditorial</dt>
                            <dd><span class="inline-status {{ $statusClass }}">{{ $statusLabel }}</span></dd>
                        </div>
                        <div>
                            <dt>Droits d’auteur</dt>
                            <dd>
                                @if($book->copyright_accepted)
                                    Acceptés
                                    @if($book->copyright_accepted_at)
                                        <small class="d-block text-muted">{{ \Illuminate\Support\Carbon::parse($book->copyright_accepted_at)->format('d/m/Y H:i') }}</small>
                                    @endif
                                @else
                                    Non acceptés
                                @endif
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="writer-book-panel mt-4">
                    <header>
                        <h2><i class="bi bi-file-earmark-arrow-up"></i> Fichier</h2>
                    </header>

                    @if($fileName)
                        <div class="writer-book-file">
                            <div class="file-icon">
                                <i class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-pdf-fill' }}"></i>
                            </div>
                            <div>
                                <strong>{{ $fileName }}</strong>
                                <small>
                                    {{ strtoupper($book->file_type ?: ($book->type === 'audio' ? 'mp3' : 'pdf')) }}
                                    @if($fileSizeLabel)
                                        · {{ $fileSizeLabel }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun fichier associé.</p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>

@if($book->preview_type == 'pages' && $book->type === 'ebook')
    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>
    <script>
        let pages = [];

        const cover = document.createElement("div");
        cover.className = "page cover";
        cover.innerHTML = `<img src="/storage/{{ $book->cover_image }}">`;
        pages.push(cover);

        @for($i = $previewStart; $i <= $previewEnd; $i++)
            const page{{ $i }} = document.createElement("div");
            page{{ $i }}.className = "page";
            page{{ $i }}.innerHTML = `<img src="{{ route('book.preview.page', [$book->id, $i]) }}">`;
            pages.push(page{{ $i }});
        @endfor

        const finalPage = document.createElement("div");
        finalPage.className = "page preview-end-page";
        finalPage.innerHTML = `
            <div class="preview-end-content">
                <h2>Fin de l'aperçu</h2>
                <p>Vous venez de lire la dernière page sélectionnée.</p>
                <h3>{{ number_format((float) $book->price, 0, ',', ' ') }} FCFA</h3>
            </div>
        `;
        pages.push(finalPage);

        const isMobile = window.innerWidth <= 992;
        const flipBook = new St.PageFlip(document.getElementById("book-preview"), {
            width: isMobile ? 320 : 450,
            height: isMobile ? 480 : 650,
            size: "stretch",
            minWidth: 280,
            maxWidth: 900,
            minHeight: 400,
            maxHeight: 1200,
            showCover: true,
            usePortrait: isMobile,
            drawShadow: true,
            maxShadowOpacity: 1,
            flippingTime: 1200,
            mobileScrollSupport: true
        });
        flipBook.loadFromHTML(pages);
    </script>
@endif
@endsection

@push('styles')
<style>
.writer-book-show {
    background:
        radial-gradient(circle at top left, rgba(179,0,0,.06), transparent 40%),
        linear-gradient(180deg, #faf8f7 0%, #f3f1f0 100%);
    min-height: 100vh;
}
.writer-book-back {
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
.writer-book-back:hover { border-color: #b30000; color: #b30000; }

.writer-book-hero {
    padding: 36px 32px;
    border-radius: 28px;
    color: #fff;
    background: linear-gradient(145deg, #1a1a1a 0%, #3a0a0a 45%, #b30000 100%);
    box-shadow: 0 24px 50px rgba(0,0,0,.22);
}
.writer-book-cover img {
    width: min(100%, 280px);
    height: 390px;
    object-fit: cover;
    border-radius: 18px;
    box-shadow: 0 24px 40px rgba(0,0,0,.45);
}
.writer-book-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
.writer-book-tags .tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 999px; font-size: .78rem; font-weight: 700;
    background: rgba(255,255,255,.12); color: #fff;
}
.writer-book-tags .tag.type { background: rgba(255,255,255,.2); }
.writer-book-tags .tag.status.published { background: #1a7f4b; }
.writer-book-tags .tag.status.waiting,
.writer-book-tags .tag.status.review { background: #c47a00; }
.writer-book-tags .tag.status.revision,
.writer-book-tags .tag.status.rejected { background: #8a1f1f; }
.writer-book-tags .tag.status.draft { background: #5f636b; }

.writer-book-title {
    margin: 0 0 10px;
    font-size: clamp(1.7rem, 3vw, 2.4rem);
    font-weight: 800;
    line-height: 1.15;
}
.writer-book-author {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 14px; opacity: .92; font-weight: 600;
}
.writer-book-lead {
    margin: 0 0 20px;
    max-width: 640px;
    color: rgba(255,255,255,.88);
    line-height: 1.55;
}

.writer-book-meta-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 22px;
}
.writer-book-meta-grid > div {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px;
    padding: 12px 14px;
}
.writer-book-meta-grid small {
    display: block;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    opacity: .7;
    margin-bottom: 4px;
}
.writer-book-meta-grid strong { font-size: 1rem; }

.writer-book-actions {
    display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
}
.btn-primary-action,
.btn-secondary-action {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 18px; border-radius: 999px;
    font-weight: 700; text-decoration: none;
}
.btn-primary-action { background: #fff; color: #b30000; }
.btn-primary-action:hover { background: #ffe8e8; color: #910000; }
.btn-secondary-action {
    background: transparent; color: #fff;
    border: 1px solid rgba(255,255,255,.35);
}
.btn-secondary-action:hover { background: rgba(255,255,255,.12); color: #fff; }
.writer-book-audio audio { width: min(100%, 360px); }

.writer-book-alert {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 16px 18px; border-radius: 16px; background: #fff;
}
.writer-book-alert i { font-size: 1.3rem; margin-top: 2px; }
.writer-book-alert.warning { border: 1px solid #ffe2b8; background: #fff8ee; color: #8a5a00; }
.writer-book-alert.success { border: 1px solid #c8e6c9; background: #f1f8f2; color: #1a7f4b; }
.writer-book-alert strong { display: block; margin-bottom: 4px; }
.writer-book-alert p { margin: 0; }

.writer-book-panel {
    background: #fff;
    border: 1px solid #ece7e5;
    border-radius: 22px;
    padding: 22px 22px 24px;
    box-shadow: 0 10px 28px rgba(30,20,16,.04);
}
.writer-book-panel header h2 {
    margin: 0 0 18px;
    font-size: 1.15rem;
    display: flex; align-items: center; gap: 10px;
}
.writer-book-panel header h2 i { color: #b30000; }
.writer-book-short {
    margin-bottom: 20px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f0ecea;
}
.writer-book-short h3,
.writer-book-preview-block h3 {
    font-size: .95rem;
    margin: 0 0 8px;
    color: #555;
}
.writer-book-short p { margin: 0; color: #333; line-height: 1.6; }
.preview-chip {
    display: inline-flex;
    padding: 5px 10px;
    border-radius: 999px;
    background: #fff0f0;
    color: #b30000;
    font-size: .75rem;
    font-weight: 700;
}
.book-text-preview {
    color: #333;
    line-height: 1.7;
}

.writer-book-specs {
    margin: 0;
    display: grid;
    gap: 0;
}
.writer-book-specs > div {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f2eeec;
}
.writer-book-specs > div:last-child { border-bottom: 0; }
.writer-book-specs dt { margin: 0; color: #888; font-size: .86rem; }
.writer-book-specs dd { margin: 0; font-weight: 700; color: #222; text-align: right; }
.inline-status {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 700;
}
.inline-status.published { background: #e8f5e9; color: #1a7f4b; }
.inline-status.waiting,
.inline-status.review { background: #fff4e0; color: #a15c00; }
.inline-status.revision,
.inline-status.rejected { background: #ffe8e8; color: #b30000; }
.inline-status.draft { background: #f1f1f1; color: #5f636b; }

.writer-book-file {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 14px;
    border-radius: 14px;
    background: #faf7f6;
    border: 1px solid #efeae8;
}
.writer-book-file .file-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: grid; place-items: center;
    background: #fff0f0; color: #b30000; font-size: 1.3rem;
}
.writer-book-file strong { display: block; font-size: .95rem; word-break: break-word; }
.writer-book-file small { color: #888; }

@media (max-width: 991px) {
    .writer-book-hero { padding: 24px 18px; }
    .writer-book-meta-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .writer-book-cover img { height: 340px; }
}
@media (max-width: 575px) {
    .writer-book-meta-grid { grid-template-columns: 1fr 1fr; }
    .writer-book-specs > div { grid-template-columns: 1fr; text-align: left; }
    .writer-book-specs dd { text-align: left; }
}
</style>
@endpush
