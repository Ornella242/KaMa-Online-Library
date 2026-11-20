
@extends('layouts.app')

@php
    $bookUrl = route('books.show', $book);

    $coverUrl = asset('storage/' . ltrim($book->cover_image ?? '', '/'));

    $authorName = trim(
        ($book->author->firstname ?? '') . ' ' .
        ($book->author->lastname ?? '')
    );

    $description = \Illuminate\Support\Str::limit(
        trim(strip_tags($book->short_description ?? '')),
        180
    );

    $shareText = "Découvrez ce livre sur KaMa Afrika \n\n"
        . $bookUrl;
@endphp

@section('content')

<div class="container py-5 kama-boost-page">

    <!-- RETOUR -->
    <div class="mb-4">
        <a href="{{ route('writer.books') }}" class="btn kama-back-btn">
            <i class="bi bi-arrow-left me-2"></i>
            Retour à mes livres
        </a>
    </div>

    <!-- HERO DU LIVRE -->
    <div class="kama-boost-hero">
        <div class="row align-items-center g-5">

            <div class="col-lg-4 text-center">
                <div class="kama-boost-cover">
                    <img
                        src="{{ $coverUrl }}"
                        alt="Couverture de {{ $book->title }}"
                    >
                </div>
            </div>

            <div class="col-lg-8">

                <span class="kama-boost-label">
                    <i class="bi bi-megaphone-fill"></i>
                    Promotion du livre
                </span>

                <h1 class="text-white">
                    {{ $book->title }}
                </h1>

                <p class="author">
                    <i class="bi bi-person-circle"></i>
                    {{ $authorName }}
                </p>

                <p class="description">
                    {{ \Illuminate\Support\Str::limit(
                        strip_tags($book->short_description ?? ''),
                        250
                    ) }}
                </p>

                <div class="book-info">

                    <div>
                        <small>Prix</small>
                        <strong>
                            {{ number_format($book->price, 2, ',', ' ') }} €
                        </strong>
                    </div>

                    <div>
                        <small>Catégorie</small>
                        <strong>
                            {{ $book->category->name ?? 'Non classé' }}
                        </strong>
                    </div>

                    <div>
                        <small>Type</small>
                        <strong>{{ ucfirst($book->type) }}</strong>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ZONE DE PARTAGE -->
    <div class="row g-4 mt-4 kama-share-section">

        <!-- APERÇU -->
        <div class="col-lg-7">

            <div class="kama-card h-100">

                <div class="kama-card-header">
                    <i class="bi bi-eye-fill"></i>
                    Aperçu de votre publication
                </div>

                <div class="kama-post-preview">

                    <div class="kama-preview-top">

                        <div class="kama-brand-mark">K</div>

                        <div>
                            <strong>KaMa Afrika</strong>
                            <span>La littérature africaine à portée de main</span>
                        </div>

                        <i class="bi bi-globe2 ms-auto"></i>

                    </div>

                    <div class="kama-preview-cover">
                        <img
                            src="{{ $coverUrl }}"
                            alt="Couverture de {{ $book->title }}"
                            loading="lazy"
                        >
                    </div>

                    <div class="kama-preview-content">

                        <span class="kama-preview-badge">
                            <i class="bi bi-book-half"></i>
                            {{ ucfirst($book->type) }}
                        </span>

                        <h3>{{ $book->title }}</h3>

                        <p class="kama-preview-author">
                            <i class="bi bi-person-circle"></i>
                            {{ $authorName }}
                        </p>

                        <p class="kama-preview-description">
                            {{ $description }}
                        </p>

                        <div class="kama-preview-footer">

                            <div>
                                <small>Disponible sur KaMa</small>
                                <strong>
                                    {{ number_format($book->price, 2, ',', ' ') }} €
                                </strong>
                            </div>

                            <a
                                href="{{ $bookUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Découvrir le livre
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>
                </div>

                <p class="kama-preview-note">
                    <i class="bi bi-info-circle"></i>
                    Cet aperçu est une simulation. Chaque réseau social
                    peut afficher différemment la couverture et le texte.
                </p>

            </div>
        </div>

        <!-- BOUTONS DE PARTAGE -->
        <div class="col-lg-5">

            <div class="kama-card kama-share-card h-100">

                <div class="kama-card-header">
                    <i class="bi bi-megaphone-fill"></i>
                    Faites connaître votre livre
                </div>

                <div class="kama-share-intro">

                    <div class="kama-share-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h3>Votre prochain lecteur est peut-être ici.</h3>

                    <p>
                        Partagez le lien de votre livre avec votre
                        communauté et invitez de nouveaux lecteurs
                        à le découvrir.
                    </p>

                </div>

                <div class="kama-share-buttons">

                    <!-- WHATSAPP -->
                    <a
                        class="kama-share-btn whatsapp"
                        href="https://wa.me/?text={{ urlencode($shareText) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi bi-whatsapp"></i>

                        <span>
                            <strong>WhatsApp</strong>
                            <small>Partager avec vos contacts</small>
                        </span>

                        <i class="bi bi-arrow-up-right ms-auto"></i>
                    </a>

                    <!-- FACEBOOK -->
                    <a
                        class="kama-share-btn facebook"
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($bookUrl) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi bi-facebook"></i>

                        <span>
                            <strong>Facebook</strong>
                            <small>Publier sur votre fil</small>
                        </span>

                        <i class="bi bi-arrow-up-right ms-auto"></i>
                    </a>

                    <!-- LINKEDIN -->
                    <a
                        class="kama-share-btn linkedin"
                        href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($bookUrl) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi bi-linkedin"></i>

                        <span>
                            <strong>LinkedIn</strong>
                            <small>Partager avec votre réseau</small>
                        </span>

                        <i class="bi bi-arrow-up-right ms-auto"></i>
                    </a>

                    <!-- X -->
                    <a
                        class="kama-share-btn twitter"
                        href="https://twitter.com/intent/tweet?text={{ urlencode('Découvrez ce livre sur KaMa Afrika 📚') }}&url={{ urlencode($bookUrl) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi bi-twitter-x"></i>

                        <span>
                            <strong>X</strong>
                            <small>Publier un message</small>
                        </span>

                        <i class="bi bi-arrow-up-right ms-auto"></i>
                    </a>

                    <!-- COPIER LE TEXTE -->
                    <button
                        type="button"
                        class="kama-share-btn kama-copy-btn"
                        id="copyBookShare"
                        data-share-text="{{ $shareText }}"
                    >
                        <i class="bi bi-copy"></i>

                        <span>
                            <strong>Copier le texte</strong>
                            <small>Avec le lien de votre livre</small>
                        </span>

                        <i class="bi bi-chevron-right ms-auto"></i>
                    </button>

                </div>

                <div
                    class="kama-copy-feedback"
                    id="copyFeedback"
                    role="status"
                    aria-live="polite"
                ></div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('copyBookShare');
    const feedback = document.getElementById('copyFeedback');

    if (!button || !feedback) return;

    const originalContent = button.innerHTML;

    button.addEventListener('click', async function () {
        const text = button.dataset.shareText;

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
            } else {
                const area = document.createElement('textarea');

                area.value = text;
                area.setAttribute('readonly', '');
                area.style.position = 'fixed';
                area.style.opacity = '0';

                document.body.appendChild(area);
                area.select();

                const copied = document.execCommand('copy');
                area.remove();

                if (!copied) {
                    throw new Error('Copy failed');
                }
            }

            button.innerHTML =
                '<i class="bi bi-check-circle-fill"></i>' +
                '<span><strong>Texte copié !</strong>' +
                '<small>Prêt à être partagé</small></span>' +
                '<i class="bi bi-check-lg ms-auto"></i>';

            feedback.textContent =
                'Le message et le lien du livre ont été copiés.';

        } catch (error) {
            feedback.textContent =
                'Copie impossible. Veuillez sélectionner et copier le lien du livre.';
        }

        setTimeout(function () {
            button.innerHTML = originalContent;
        }, 2500);
    });
});
</script>

@endsection
