@extends('layouts.app')

@section('content')

<section class="details-hero">

    <!-- DECORATION -->
    <div class="hero-bg-circle c1"></div>
    <div class="hero-bg-circle c2"></div>


    <div class="kama-hero-container">
        <div class="hero-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ url('/catalogue') }}">
                    <i class="bi bi-arrow-left"></i>
                    Catalogue
                </a>

                <span>
                    /
                </span>

                <span>
                    {{ $book->title }}
                </span>
            </div>

            <!-- Category -->
            @if($book->category)
                <span class="book-category-badge">
                    <i class="bi bi-bookmark-fill"></i>
                    {{ $book->category->name }}
                </span>
            @endif
{{-- 
            <h1>
                {{ $book->title }}
            </h1> --}}
        </div>
    </div>
</section>
<!-- =======================
Advertisement START -->
@include('partials.sponsored-books-banner')
<!-- =======================
Advertisement END -->

<section class="details-book">
    <div class="details-container">
        <!-- LEFT IMAGE -->
        <div class="book-image">
        <img src="{{  asset('storage/'.$book->cover_image)  }}" alt="{{ $book->title }}">
        </div>

            <!-- RIGHT CONTENT -->
        <div class="book-content">

            <!-- CATEGORY -->
            <span class="category">
                <i class="bi bi-bookmark-fill"></i>
                {{ $book->category->name }}
            </span>


            <!-- TITLE -->
            <h1>
                {{ $book->title }}
            </h1>


            <!-- AUTHOR -->
            <div class="author-box">

                <div class="author-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>


                <div class="author-text">

                    <small>
                        Écrit par
                    </small>

                    <strong>
                        {{ $book->author->firstname }}
                        {{ $book->author->lastname }}
                    </strong>

                </div>


                <button class="bio-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#authorModal">

                    Voir la biographie
                    <i class="bi bi-arrow-right"></i>

                </button>

            </div>



            <!-- RATING -->
            <div class="rating">

                <i class="bi bi-star-fill star"></i>

                <strong>
                    {{ number_format($book->reviews_avg_rating ?? 0,1) }}
                </strong>

                <span>
                    ({{ $book->reviews_count }} avis)
                </span>

            </div>



            <!-- DESCRIPTION -->
            <h5>Résumé</h5>
            <p class="summary">
                {{ $book->short_description }}
            </p>


            <!-- META -->
            <div class="meta-line">


                <div class="meta-chip">

                    <i class="bi bi-file-earmark-text meta-icon"></i>

                    @if($book->type == 'ebook')
                        {{ $book->pages }} pages
                    @else
                        {{ $book->duration }}
                    @endif

                </div>



                <div class="meta-chip">

                    <i class="bi bi-calendar meta-icon"></i>

                    {{ $book->publication_year }}

                </div>



                <div class="meta-chip">

                    <i class="bi bi-globe meta-icon"></i>

                    {{ $book->language }}

                </div>



                <div class="meta-chip">

                    <i class="bi bi-tag meta-icon"></i>

                    {{ $book->subcategory->name ?? '' }}

                </div>


            </div>



            <!-- PRICE -->
            <div class="price-box">

                <span>
                    Prix
                </span>

                <strong>
                    {{ number_format($book->price,2) }} $
                </strong>

            </div>



                        <div class="actions">
                            <a href="#extrait" class="btn-preview">
                                <i class="bi bi-book"></i> Lire un extrait
                            </a>

                            @if($alreadyOwned)
                                <a href="{{ route('reader.books.download', $book) }}" class="btn-cart">
                                    <i class="bi bi-download"></i> Télécharger
                                </a>
                            @elseif($isOwner)
                                <span class="owned"><i class="bi bi-info-circle"></i> Votre publication</span>
                            @elseif($inCart)
                                <a href="{{ route('cart.index') }}" class="btn-cart secondary">
                                    <i class="bi bi-cart-check"></i> Voir le panier
                                </a>
                            @else
                                <form method="POST" action="{{ route('cart.store', $book) }}">
                                    @csrf
                                    <button type="submit" class="btn-cart">
                                        <i class="bi bi-cart-plus"></i> Ajouter au panier
                                    </button>
                                </form>
                            @endif

                            @auth
                                @unless($isOwner)
                                    @if($inWishlist)
                                        <form method="POST" action="{{ route('wishlist.destroy', $book) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-wishlist active" title="Retirer de la wishlist">
                                                <i class="bi bi-heart-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('wishlist.store', $book) }}">
                                            @csrf
                                            <button type="submit" class="btn-wishlist" title="Ajouter à la wishlist">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endunless
                            @else
                                <a href="{{ route('login') }}" class="btn-wishlist" title="Connexion pour wishlist">
                                    <i class="bi bi-heart"></i>
                                </a>
                            @endauth
                        </div>
                        @unless($alreadyOwned || $isOwner)
                            <p class="buy-guest-hint">Achat possible sans créer de compte — paiement par carte via KKiaPay.</p>
                        @endunless
                    </div>
                </div>
            </div>

            <section class="book-detail-section" id="extrait">
                <header>
                    <h2>Extrait du livre</h2>
                    <p>
                        @if($book->preview_type === 'pages')
                            Feuilletez les pages sélectionnées par l’auteur.
                        @else
                            Découvrez un aperçu du contenu.
                        @endif
                    </p>
                </header>

                @if($book->preview_type === 'pages' && $previewStart && $previewEnd)
                    <div class="book-detail-flip">
                        <div class="book-wrapper">
                            <div id="book-preview"></div>
                        </div>
                    </div>
                @else
                    <div class="book-detail-text-preview">
                        {!! $book->long_description ?: '<p class="text-muted">Aucun extrait texte disponible pour ce livre.</p>' !!}
                    </div>
                @endif
            </section>

            @if($sameAuthorBooks->isNotEmpty())
                <section class="book-detail-section">
                    <header>
                        <h2>Du même auteur</h2>
                        <p>Autres ouvrages de {{ $book->author?->firstname }} {{ $book->author?->lastname }}</p>
                    </header>
                    <div class="book-detail-related">
                        @foreach($sameAuthorBooks as $sameBook)
                            <a href="{{ route('books.show', $sameBook) }}" class="related-card">
                                <img src="{{ asset('storage/' . $sameBook->cover_image) }}" alt="">
                                <strong>{{ $sameBook->title }}</strong>
                                <small>{{ number_format($sameBook->price, 0, ',', ' ') }} XOF</small>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="book-detail-section" id="avis">
                <header>
                    <h2>Avis des lecteurs</h2>
                    <p>{{ $reviewsCount }} avis · note moyenne {{ number_format($avgRating, 1) }}/5</p>
                </header>

                <div class="book-detail-reviews">
                    <div class="reviews-list">
                        @forelse($book->reviews as $review)
                            <article class="review-item">
                                <div class="review-top">
                                    <span class="review-avatar">
                                        {{ strtoupper(mb_substr($review->user?->firstname ?? 'L', 0, 1) . mb_substr($review->user?->lastname ?? '', 0, 1)) }}
                                    </span>
                                    <div>
                                        <strong>{{ trim(($review->user?->firstname ?? '') . ' ' . ($review->user?->lastname ?? '')) ?: 'Lecteur' }}</strong>
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small>{{ $review->created_at?->diffForHumans() }}</small>
                                </div>
                                <p>{{ $review->comment }}</p>
                            </article>
                        @empty
                            <div class="reviews-empty">
                                <i class="bi bi-chat-quote"></i>
                                <p>Aucun avis pour le moment. Soyez le premier à partager votre lecture.</p>
                            </div>
                        @endforelse
                    </div>

                    <aside class="review-form-card">
                        <h3>{{ $userReview ? 'Modifier votre avis' : 'Laisser un avis' }}</h3>

                        @guest
                            <p class="review-login-hint">
                                Vous devez être connecté pour publier un avis.
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-danger w-100">
                                Se connecter
                            </a>
                        @else
                            @if($isOwner)
                                <p class="review-login-hint">Vous ne pouvez pas noter votre propre livre.</p>
                            @else
                                <form method="POST" action="{{ route('books.reviews.store', $book) }}" class="review-form">
                                    @csrf
                                    <label>Note</label>
                                    <select name="rating" required>
                                        @for($r = 5; $r >= 1; $r--)
                                            <option value="{{ $r }}" @selected(old('rating', $userReview?->rating) == $r)>
                                                {{ str_repeat('★', $r) }}{{ str_repeat('☆', 5 - $r) }} ({{ $r }}/5)
                                            </option>
                                        @endfor
                                    </select>

                                    <label>Votre commentaire</label>
                                    <textarea name="comment" rows="5" required minlength="10" maxlength="1000"
                                        placeholder="Qu’avez-vous pensé de ce livre ?">{{ old('comment', $userReview?->comment) }}</textarea>
                                    @error('comment')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <button type="submit" class="btn btn-danger w-100">
                                        {{ $userReview ? 'Mettre à jour mon avis' : 'Publier mon avis' }}
                                    </button>
                                </form>
                            @endif
                        @endguest
                    </aside>
                </div>
            </section>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .book-detail-page {
            padding: 30px 0 70px;
            background: linear-gradient(180deg, #fff8f8 0%, #f6f7f9 28%, #f6f7f9 100%);
        }

        .book-detail-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-bottom: 22px;
            color: #888;
            font-size: .88rem;
        }

        .book-detail-breadcrumb a {
            color: #b30000;
            text-decoration: none;
            font-weight: 600;
        }

        .book-detail-breadcrumb em {
            color: #333;
            font-style: normal;
        }

        .book-detail-hero {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 36px;
            padding: 28px;
            background: #fff;
            border: 1px solid #eceef0;
            border-radius: 22px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .05);
        }

        .book-detail-cover {
            position: relative;
        }

        .book-detail-cover img {
            width: 100%;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .14);
            background: #eee;
        }

        .book-detail-type {
            position: absolute;
            left: 12px;
            top: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .72);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
        }

        .book-detail-category {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 12px;
            border-radius: 999px;
            background: #b30000;
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
        }

        .book-detail-info h1 {
            margin: 0 0 8px;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            line-height: 1.2;
        }

        .book-detail-author {
            margin: 0 0 12px;
            color: #666;
        }

        .book-detail-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 14px;
            color: #f5b50a;
        }

        .book-detail-rating span {
            margin-left: 6px;
            color: #666;
            font-size: .9rem;
        }

        .book-detail-summary {
            color: #555;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .book-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 22px;
        }

        .book-detail-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 11px;
            border-radius: 999px;
            background: #f7f7f8;
            color: #444;
            font-size: .82rem;
            font-weight: 600;
        }

        .book-detail-buy {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .book-detail-buy .price {
            font-size: 2rem;
            font-weight: 800;
            color: #111;
        }

        .book-detail-buy .price small {
            font-size: 1rem;
            color: #888;
            font-weight: 700;
        }

        .book-detail-buy .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .buy-guest-hint {
            flex-basis: 100%;
            margin: 4px 0 0;
            color: #888;
            font-size: .82rem;
        }

        .btn-preview,
        .btn-cart {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 16px;
            border: 0;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-preview {
            background: #111;
            color: #fff;
        }

        .btn-cart {
            background: #b30000;
            color: #fff;
        }

        .btn-cart.secondary {
            background: #fff0f0;
            color: #b30000;
        }

        .btn-wishlist {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            border: 1px solid #eceef0;
            background: #fff;
            color: #888;
            text-decoration: none;
        }

        .btn-wishlist.active,
        .btn-wishlist:hover {
            color: #b30000;
            border-color: #ffd0d0;
            background: #fff5f5;
        }

        .owned {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #138443;
            font-weight: 700;
        }

        .book-detail-section {
            margin-top: 28px;
            padding: 26px;
            background: #fff;
            border: 1px solid #eceef0;
            border-radius: 20px;
        }

        .book-detail-section header {
            margin-bottom: 18px;
        }

        .book-detail-section header h2 {
            margin: 0 0 4px;
            font-size: 1.35rem;
        }

        .book-detail-section header p {
            margin: 0;
            color: #888;
        }

        .book-detail-text-preview {
            color: #444;
            line-height: 1.75;
            font-size: 1.02rem;
        }

        .book-detail-flip {
            display: flex;
            justify-content: center;
        }

        .book-detail-related {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .related-card {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 10px;
            border: 1px solid #eceef0;
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            background: #fafafa;
        }

        .related-card img {
            width: 100%;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 10px;
            background: #eee;
        }

        .related-card strong {
            font-size: .9rem;
        }

        .related-card small {
            color: #b30000;
            font-weight: 700;
        }

        .book-detail-reviews {
            display: grid;
            grid-template-columns: 1.4fr .9fr;
            gap: 20px;
        }

        .reviews-list {
            display: grid;
            gap: 12px;
        }

        .review-item {
            padding: 16px;
            border: 1px solid #eceef0;
            border-radius: 14px;
            background: #fafafa;
        }

        .review-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .review-avatar {
            display: inline-flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #b30000;
            font-size: .78rem;
            font-weight: 800;
            line-height: 1;
        }

        .review-top .stars {
            color: #f5b50a;
            font-size: .85rem;
        }

        .review-top small {
            margin-left: auto;
            color: #999;
            font-size: .75rem;
        }

        .review-item p {
            margin: 0;
            color: #444;
            line-height: 1.6;
        }

        .reviews-empty {
            text-align: center;
            padding: 40px 16px;
            color: #888;
        }

        .reviews-empty i {
            font-size: 1.8rem;
            color: #b30000;
        }

        .review-form-card {
            padding: 18px;
            border-radius: 16px;
            background: #111;
            color: #fff;
            height: fit-content;
        }

        .review-form-card h3 {
            margin: 0 0 12px;
            font-size: 1.1rem;
        }

        .review-login-hint {
            color: #c9c9c9;
            margin-bottom: 14px;
        }

        .review-form label {
            display: block;
            margin: 10px 0 6px;
            font-size: .82rem;
            font-weight: 700;
        }

        .review-form select,
        .review-form textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #333;
            border-radius: 10px;
            background: #1c1c1c;
            color: #fff;
        }

        .review-form button {
            margin-top: 14px;
        }

        @media(max-width:991px) {
            .book-detail-hero {
                grid-template-columns: 1fr;
            }

            .book-detail-cover {
                max-width: 280px;
                margin: 0 auto;
            }

            .book-detail-related {
                grid-template-columns: repeat(2, 1fr);
            }

            .book-detail-reviews {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@if($book->preview_type == 'pages' && $previewStart && $previewEnd)
    @push('scripts')
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
            <h3>{{ number_format($book->price, 0, ',', ' ') }} XOF</h3>
            @unless($alreadyOwned || $isOwner || $inCart)
                <form method="POST" action="{{ route('cart.store', $book) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ajouter au panier</button>
                </form>
            @elseif($inCart)
                <a href="{{ route('cart.index') }}" class="btn btn-danger">Voir le panier</a>
            @endunless
        </div>`;
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
    @endpush
@endif