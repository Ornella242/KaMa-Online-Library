@extends('layouts.admin')

@section('meta')
@php
    $coverUrl = config('app.url').'/storage/'.$book->cover_image;
    $coverUrl = config('app.url').'/storage/'.$book->cover_image;
@endphp
<head>

<meta property="og:title" content="{{ $book->title }}">

<meta property="og:description" content="{{ Str::limit(strip_tags($book->short_description),200) }}">

<meta property="og:image" content="{{ config('app.url').'/storage/'.$book->cover_image }}">

<meta property="og:image:secure_url" content="{{ config('app.url').'/storage/'.$book->cover_image }}">

<meta property="og:type" content="book">

<meta property="og:url" content="{{ config('app.url').'/books/'.$book->id }}">

<meta name="twitter:card" content="summary_large_image">

<meta name="twitter:image" content="{{ config('app.url').'/storage/'.$book->cover_image }}">

</head>
@endsection

@section('admin-content')

<div class="container py-5">

    <!-- BACK BUTTON -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" 
        class="btn btn-outline-dark rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>
            Retour
        </a>
    </div>

    <!-- BOOK HERO -->

    <div class="book-show-card">
        <div class="row g-5 align-items-center">
            <!-- COVER -->
            <div class="col-lg-4 text-center">
                <div class="book-cover-wrapper">
                    <img 
                    src="{{ asset('storage/'.$book->cover_image) }}"
                    class="book-cover-show"
                    alt="{{ $book->title }}">
                </div>
            </div>



            <!-- INFO -->

            <div class="col-lg-8">
                <div class="book-tags mb-3">
                    <span class="badge ebook-badge">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        {{ ucfirst($book->type) }}
                    </span>

                    <span class="badge category-badge">
                        {{ $book->category->name }}
                    </span>


                    <span class="badge status-badge">
                        {{ ucfirst($book->status) }}
                    </span>


                </div>



                <h1 class="book-title-show">
                    {{ $book->title }}
                </h1>


                <div class="author-show">

                    <i class="bi bi-person-circle"></i>

                    {{ $book->author->firstname }}
                    {{ $book->author->lastname }}

                </div>



                <!-- META -->

                <div class="show-meta">


                    <div>
                        <i class="bi bi-calendar3"></i>

                        <small>
                            Publication
                        </small>

                        <strong>
                            {{ $book->publication_year }}
                        </strong>

                    </div>



                    <div>

                        <i class="bi bi-cash"></i>

                        <small>
                            Prix
                        </small>

                        <strong>
                            {{ $book->price }} $
                        </strong>

                    </div>



                    @if($book->type == 'ebook')

                    <div>

                        <i class="bi bi-book"></i>

                        <small>
                            Pages
                        </small>

                        <strong>
                            {{ $book->pages }}
                        </strong>

                    </div>


                    @else


                    <div>

                        <i class="bi bi-headphones"></i>

                        <small>
                            Durée
                        </small>

                        <strong>
                            {{ $book->duration }}
                        </strong>

                    </div>

                    @endif
                </div>



                <!-- ACTION -->


                <div class="book-actions mt-4">
                    @if($book->type == 'ebook')
                        <a href="{{ route('admin.books.preview.file', $book->id) }}"
                            target="_blank"
                            class="btn btn-danger rounded-pill px-4">

                            <i class="bi bi-book-half me-2"></i>
                            Lire le livre
                        </a>
                    @else


                    <audio controls class="audio-player">
                        <source 
                            src="{{ route('admin.books.audio', $book->id) }}"
                            type="audio/mpeg">

                        Votre navigateur ne supporte pas la lecture audio.

                    </audio>


                    @endif

                    <div class="editorial-actions mt-4">

                        @if($book->status === 'draft')
                            @php
                                $pendingPublicationPayment = $book->payments()
                                    ->where('type', 'publication')
                                    ->where('status', 'pending')
                                    ->latest()
                                    ->first();
                            @endphp

                            @if($pendingPublicationPayment)
                                <div class="alert alert-warning d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <div>
                                        <strong>Confirmation de paiement requise</strong>
                                        <div class="small mt-1">
                                            Référence {{ $pendingPublicationPayment->reference }} —
                                            {{ number_format($pendingPublicationPayment->amount, 2, ',', ' ') }}
                                            {{ $pendingPublicationPayment->currency }}
                                        </div>
                                    </div>
                                    @if($pendingPublicationPayment->payment_method === 'manual')
                                        <form method="POST" action="{{ route('admin.payments.publication.confirm', $pendingPublicationPayment) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check2-circle me-2"></i>Confirmer le paiement
                                            </button>
                                        </form>
                                    @else
                                        <span class="small">La confirmation sera effectuée automatiquement par KKiaPay.</span>
                                    @endif
                                </div>
                            @endif
                        @endif

                        @if($book->status == 'waiting_review' && (int) $book->user_id !== (int) auth()->id())

                            @php
                                $depositPaid = $book->payments()
                                    ->where('type','publication')
                                    ->where('status','success')
                                    ->exists();
                            @endphp


                            @if($depositPaid)

                                <form method="POST" action="{{ route('admin.books.review', $book) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-warning rounded-pill px-4">
                                        <i class="bi bi-shield-check me-2"></i>
                                        Procéder à la vérification éditoriale
                                    </button>
                                </form>

                            @else


                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Le paiement du dépôt n'a pas encore été effectué.
                                    La vérification éditoriale sera disponible après paiement.
                                </div>
                            @endif

                        @endif

                        @if($book->status == 'under_review')

                            <div class="alert alert-info">

                                <i class="bi bi-hourglass-split me-2"></i>

                                Ce livre est actuellement en cours de vérification éditoriale.

                            </div>


                        @endif

                        @if($book->status == 'published')

                            <div class="alert alert-success">

                                <i class="bi bi-check-circle me-2"></i>

                                Ce livre est publié sur KaMa.

                            </div>

                        @endif


                    </div>

                </div>



            </div>


        </div>

    </div>




    <!-- DESCRIPTION -->


    <div class="description-card mt-5">


        <h3>

            <i class="bi bi-text-paragraph me-2"></i>

            Description

        </h3>


        <div class="description-content">

            @if($book->preview_type == 'pages')
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div class="book-wrapper">
                        <div id="book-preview"></div>
                    </div>
                </div>
            @else

            <div class="book-text-preview">
                {!! $book->long_description !!}
            </div>

            @endif

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


        <h2>
        Fin de l'aperçu
        </h2>


        <p>
        Vous venez de lire la dernière page sélectionnée.
        </p>


        <h3>
        {{ number_format($book->price,2) }} $
        </h3>


        <a href="#" class="btn btn-danger">
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


        width:isMobile ? 320 : 450,

        height:isMobile ? 480 : 650,


        size:"stretch",


        minWidth:280,

        maxWidth:900,


        minHeight:400,

        maxHeight:1200,


        showCover:true,


        usePortrait:isMobile,


        drawShadow:true,


        maxShadowOpacity:1,


        flippingTime:1200,


        mobileScrollSupport:true


        }


        );



        flipBook.loadFromHTML(pages);

    </script>
@endif

@endsection