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



            <!-- ACTIONS -->
            <div class="actions">


                <a href="#full-description"
                class="primary-btn">

                    <i class="bi bi-book"></i>

                    Lire un extrait

                </a>



                <button class="cart-btn">

                    <i class="bi bi-cart"></i>

                    Ajouter

                </button>



                <button class="wishlist-btn">

                    <i class="bi bi-heart"></i>

                </button>


            </div>


        </div>

    </div>
</section>

<section class="book-preview-section" id="full-description">


    <div class="preview-card">


        <!-- HEADER -->

        <div class="preview-title">
             <div class="row mb-2">
                <div class="col-12 text-center">
                    <span class="section-subtitle"> <i class="bi bi-book-half"></i>Aperçu</span>
                    <h2 class="section-title">
                        @if ($book->preview_type == 'pages')
                          Feuilletez <span>quelques pages</span>
                        @else
                            Découvrez <span>le livre</span>

                        @endif  
                    </h2>
                </div>
            </div>



            @if($book->preview_type == 'pages')

                <p>
                    Parcourez un extrait avant de commencer votre lecture.
                </p>

            @endif


        </div>




        <!-- CONTENT -->


        @if($book->preview_type == 'pages')


            <div class="flipbook-zone">


                <div class="reading-tip">

                    <i class="bi bi-hand-index"></i>

                    Tournez les pages pour lire l'extrait

                </div>



                <div class="flipbook-frame">

                    <div id="book-preview"></div>

                </div>


            </div>



        @else



            <article class="book-text-preview">

                {!! $book->long_description !!}

            </article>



        @endif



    </div>


</section>

<section class="same-author-section">
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <span class="section-subtitle"> <i class="bi bi-person"></i>Du même auteur</span>
                    <h2 class="section-title">
                        Autres livres <span>de  {{ $book->author->firstname }}
                        {{ $book->author->lastname }}
                    </h2>
                </div>
            </div>


            <!-- Slider START -->
            <div class="tiny-slider arrow-round arrow-blur arrow-hover">
                <div class="tiny-slider-inner"
                data-autoplay="true"
                data-arrow="true"
                data-dots="false"
                data-edge="0"
                data-items-xl="4"
                data-items-lg="3"
                data-items-md="2"
                data-items-sm="1"
                data-items="1">

                    @foreach($sameAuthorBooks as $sameBook)
                    <!-- Slider item -->
                    <div>
                        <div class="same-card">
                            <div class="same-image">
                                <img src="{{ asset('storage/'.$sameBook->cover_image) }}"
                                    alt="{{ $sameBook->title }}">
                            </div>
                            <div class="same-info">
                                <h4>
                                    {{ $sameBook->title }}
                                </h4>
                                <a href="{{ route('books.show',$sameBook) }}"
                                  class="see-more">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Slider END -->
        </div>
</section>



<section class="reviews-section">
     <div class="row mb-2">
            <div class="col-12 text-center">
                <span class="section-subtitle"> <i class="bi bi-chat-square-text"></i>Témoignages</span>
                <h2 class="section-title">
                    Avis <span>des lecteurs</span>
                </h2>
            </div>
        </div>

    <div class="reviews-container">
        <!-- AVIS -->
        <div class="reviews-carousel">
            <div class="carousel-track">
                @foreach($book->reviews as $review)
                <div class="review-card">
                    <div class="avatar">
                        <img src="{{ $review->user->avatar 
                            ? asset('storage/'.$review->user->avatar)
                            : asset('assets/images/avatar/01.jpg') }}"
                            alt="">
                    </div>


                    <div class="review-content">
                        <h4>
                            {{ $review->user->firstname }}
                            {{ $review->user->lastname }}
                        </h4>
                        <div class="stars">
                            @for($i=1;$i<=5;$i++)
                                @if($i <= $review->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>

                        <p>
                            {{ $review->comment }}
                        </p>
                    </div>
                </div>

                @endforeach
            </div>
        </div>

        <!-- RATING SUMMARY -->
        <div class="rating-summary">
            <h3>
                Note moyenne
            </h3>

            <div class="big-rating">
                {{ number_format($book->reviews_avg_rating ?? 0,1) }}
                <span>/5</span>
            </div>

            <div class="stars large">
                ★★★★★
            </div>

            <p>
                Basé sur 
                {{ $book->reviews_count }}
                avis lecteurs
            </p>

            @auth
                <a href="#"
                   class="review-btn">
                    Donner mon avis
                </a>
            @endauth
        </div>
    </div>
</section>


<div class="modal fade" id="authorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $book->author->firstname }}
                    {{ $book->author->lastname }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @if($book->author->bio)
                    {!! nl2br(e($book->author->bio)) !!}
                @else
                    <p class="text-muted mb-0">
                        Cet auteur n'a pas encore ajouté de biographie.
                    </p>
                @endif

            </div>

        </div>
    </div>
</div>

@if($book->preview_type == 'pages')
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>


<script>


    let pages = [];



    // =============================
    // COUVERTURE
    // =============================

    const cover = document.createElement("div");

    cover.className="page cover";


    cover.innerHTML = `

    <img src="/storage/{{ $book->cover_image }}">

    `;


    pages.push(cover);




    // =============================
    // PAGES PREVIEW IMAGES
    // =============================


    @for(
    $i=$previewStart;
    $i<=$previewEnd;
    $i++
    )


    const page{{ $i }} = document.createElement("div");


    page{{ $i }}.className="page";


    page{{ $i }}.innerHTML = `

    <img src="{{ route('book.preview.page',[$book->id,$i]) }}">

    `;

    pages.push(page{{ $i }});

    @endfor

    // =============================
    // PAGE FIN
    // =============================


    const finalPage=document.createElement("div");


    finalPage.className="page preview-end-page";


    finalPage.innerHTML=`

    <div class="preview-end-content">


        <!-- ICON -->
        <div class="end-book-icon">

            <i class="bi bi-book"></i>

        </div>



        <!-- LABEL -->
        <span class="end-label">

            Aperçu terminé

        </span>



        <!-- TITLE -->
        <h2>

            Fin de l'aperçu

        </h2>



        <!-- DESCRIPTION -->
        <p>

            Vous venez de découvrir un extrait de ce livre.
            Continuez votre lecture complète et plongez dans toute l’histoire.

        </p>



        <!-- PRICE -->
        <div class="end-price">

            {{ number_format($book->price,2) }} $

        </div>



        <!-- ACTION -->
        <a href="#"
        class="end-buy-btn">

            <i class="bi bi-cart"></i>

            Acheter le livre

        </a>


    </div>

    `;



    pages.push(finalPage);


    // =============================
    // FLIPBOOK
    // =============================


    const isMobile = window.innerWidth <= 992;



    const flipBook = new St.PageFlip(

    document.getElementById("book-preview"),

    {

        width: isMobile ? 320 : 400,

        height: isMobile ? 480 : 560,


        size: "fixed",


        minWidth: 280,

        maxWidth: 800,


        minHeight: 400,

        maxHeight: 1000,


        showCover: true,


        usePortrait: isMobile,


        drawShadow: true,


        maxShadowOpacity: 0.4,


        flippingTime: 900,


        mobileScrollSupport: true

    }

);

    flipBook.loadFromHTML(pages);



</script>

@endif

@endsection