@extends('layouts.app')

@section('content')

<section class="details-hero">

  <!-- BACKGROUND LAYERS -->
  <div class="hero-bg-circle c1"></div>
  <div class="hero-bg-circle c2"></div>

  <!-- CONTENT -->
  <div class="hero-content">

    <div class="breadcrumb">
      <a href="{{ url('/catalogue') }}">Catalogue</a> / <span>{{ $book->title }}</span>
    </div>

    <h1>{{ $book->title }}</h1>

  </div>

</section>

<!-- =======================
Advertisement START -->
@foreach($sponsoredBooks as $sponsored)
    <section class="pb-2 pb-lg-5">
        <div class="container">
            <!-- Slider START -->
            <div class="tiny-slider arrow-round arrow-blur arrow-hover">
                <div class="tiny-slider-inner" data-autoplay="true" data-arrow="true" data-edge="2" data-dots="false" data-items-xl="3" data-items-lg="2" data-items-md="1">
                    <!-- Slider item -->
                    <div>
                        <div class="card border rounded-3 overflow-hidden">
                                <span class="ad-badge">Sponsorisé</span>
                            <div class="row g-0 align-items-center">

                                <!-- Image -->
                                <div class="col-sm-6">
                                    <img src="{{ asset('storage/'.$sponsored->book->cover_image) }}" class="card-img rounded-0" alt="{{ $sponsored->book->title }}">
                                </div>

                                <!-- Title and content -->
                                <div class="col-sm-6">
                                    <div class="card-body px-3">
                                        <h6 class="card-title"><a href="{{ route('books.show', $book) }}" class="stretched-link">{{ $sponsored->book->title }}</a></h6>
                                        <p class="mb-0 author">Par {{ $sponsored->book->author->firstname }}
                                            {{ $sponsored->book->author->lastname }}
                                         </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    
                </div>
            </div>	
            <!-- Slider END -->
        </div>
    </section>
@endforeach
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

      <span class="category">{{ $book->category->name }}</span>

      <h1>{{ $book->title }}</h1>

      <!-- RATING -->
      <div class="rating">
        <i class="bi bi-star-fill star"></i>
        <span>
          {{ number_format($book->reviews_avg_rating ?? 0,1) }}
          •
          {{ $book->reviews_count }} avis
        </span>
      </div>

      <!-- DESCRIPTION -->
      <p class="summary">
        {{ $book->short_description }}
      </p>

      <!-- META INFOS -->
      <div class="meta-line">

        <span class="meta-chip">
          @if ($book->type == 'ebook')
            <i class="bi bi-file-text meta-icon"></i>
            {{ $book->pages}}pages
          @else
           <i class="bi bi-headphones meta-icon"></i>
            {{ $book->duration }} 
          @endif
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-calendar meta-icon"></i>
          {{ $book->publication_year }} 
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-globe meta-icon"></i>
          {{ $book->language }} 
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-tag meta-icon"></i>
          {{ $book->subcategory->name }} 
        </span>

        <span class="meta-chip">
          <i class="bi bi-tag meta-icon"></i>
          {{ $book->category->name }} 
        </span>


      </div>

      <!-- PRICE -->
      <div class="price">
        {{ number_format($book->price,2) }} $
      </div>

      <!-- ACTIONS -->
      <div class="actions">

        <!-- Lire un peu -->
        <a href="#full-description" class="primary-btn">
            <i class="bi bi-book"></i>
            Lire un peu
        </a>

        <!-- Add to cart -->
        <button class="cart-btn">
          <i class="bi bi-cart"></i>
          Ajouter au panier
        </button>

        <!-- Wishlist -->
        <button class="wishlist-btn">
          <i class="bi bi-heart"></i>
        </button>

      </div>

      {{-- <div class="same-author">

        <h3 class="same-title">Autres livres du même auteur</h3>

        <div class="same-grid">
          @foreach($sameAuthorBooks as $sameBook)
            <div class="same-card">

                <img src="{{ asset('storage/'.$sameBook->cover_image) }}"
                    alt="{{ $sameBook->title }}">

                <div class="same-info">

                    <h4>{{ $sameBook->title }}</h4>

                    <a href="{{ route('books.show',$sameBook) }}"
                      class="see-more">

                        <i class="bi bi-eye"></i>

                    </a>

                </div>

            </div>
          @endforeach
          
      </div> --}}
     </div>
    </div>
   

    {{-- <section class="book-full-description">
        <!-- FULL WIDTH SECTION INSIDE CARD -->
        <div class="book-full-description" id="full-description">

        <h2>Résumé détaillé</h2>

        <p>
            Atomic Habits explique comment de petits changements quotidiens peuvent produire des résultats extraordinaires.
            Le livre montre comment les habitudes se construisent et comment les transformer durablement.
        </p>

        <p>
            L’idée centrale est simple : tu n’as pas besoin de changer radicalement ta vie, mais d’améliorer ton système
            jour après jour.
            
        </p>

        <ul>
            <li>Comprendre la formation des habitudes</li>
            <li>Supprimer les mauvaises habitudes</li>
            <li>Construire de nouveaux systèmes</li>
            <li>Améliorer la discipline personnelle</li>
        </ul>

        </div>
    </section> --}}

   <section class="book-full-description" id="full-description">

    <h2>
        Extrait du livre
        @if ($book->preview_type == 'pages')
            (Cliquez sur l'image de couverture pour lire)
        @endif
    </h2>

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

</section>


     <section class="same-author-section">
        <div class="container">

            <h2 class="section-title mb-4">
                Autres livres de 
                <span>
                    {{ $book->author->firstname }}
                    {{ $book->author->lastname }}
                </span>
            </h2>


            <!-- Slider START -->
            <div class="tiny-slider arrow-round arrow-blur arrow-hover">
                <div class="tiny-slider-inner"
                    data-autoplay="true"
                    data-arrow="true"
                    data-dots="false"
                    data-edge="2"
                    data-items-xl="4"
                    data-items-lg="3"
                    data-items-md="2"
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
  </div>

</section>



<section class="reviews-section">

  <h2 class="reviews-title">Avis des lecteurs</h2>

  <div class="reviews-grid">

    <!-- LEFT: AUTO CAROUSEL -->
    <div class="reviews-carousel carousel">


        <div class="carousel-track">
            <!-- CARD 1 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>

            <div class="review-content">
                <h4>Marie K.</h4>
                <div class="stars">★★★★★</div>
                <p>Un livre incroyable qui change la façon de penser les habitudes.</p>
            </div>
            </div>

            <!-- CARD 2 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>
            <div class="review-content">
                <h4>John D.</h4>
                <div class="stars">★★★★☆</div>
                <p>Très fluide à lire, concret et utile au quotidien.</p>
            </div>
            </div>

            <!-- CARD 3 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>
            <div class="review-content">
                <h4>Amina S.</h4>
                <div class="stars">★★★★★</div>
                <p>Un must-read pour la discipline personnelle.</p>
            </div>
            </div>

            <!-- DUPLICATION POUR LOOP -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>            <div class="review-content">
                <h4>Marie K.</h4>
                <div class="stars">★★★★★</div>
                <p>Un livre incroyable qui change la façon de penser les habitudes.</p>
            </div>
            </div>

            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>            <div class="review-content">
                <h4>John D.</h4>
                <div class="stars">★★★★☆</div>
                <p>Très fluide à lire, concret et utile au quotidien.</p>
            </div>
            </div>

        </div>
    </div>

    <!-- RIGHT: FORM -->
    <div class="reviews-form-box">

      <h3>Laisser un avis</h3>

      <form class="review-form">

        <div class="form-row">
          <input type="text" placeholder="Nom">
          <input type="text" placeholder="Prénom">
        </div>

        <input type="email" placeholder="Email">

        <textarea rows="5" placeholder="Votre avis..."></textarea>
        <div class="rating-select">
                    <label>Note</label>
                    <select name="rating">
                    <option value="5">★★★★★ (5/5)</option>
                    <option value="4">★★★★☆ (4/5)</option>
                    <option value="3">★★★☆☆ (3/5)</option>
                    <option value="2">★★☆☆☆ (2/5)</option>
                    <option value="1">★☆☆☆☆ (1/5)</option>
                    </select>
        </div>

        <button type="submit">Publier</button>

      </form>

    </div>

  </div>

</section>


{{-- Code flip --}}
{{-- @if($book->preview_type == 'pages')

<script type="module">
    import * as pdfjsLib from "/js/pdfjs/pdf.mjs";

    pdfjsLib.GlobalWorkerOptions.workerSrc =
    "/js/pdfjs/pdf.worker.mjs";

   // CHARGEMENT PDF

    const pdf = await pdfjsLib.getDocument({

        url: "{{ route('book.preview',$book->id) }}"

    }).promise;

    const startPage = {{ $previewStart }};
    const pdfLastPage = {{ $previewEnd }};

    let pages = [];

    // COUVERTURE

    const cover = document.createElement("div");
    cover.className = "page cover";
    const coverImage = document.createElement("img");
    coverImage.src = "/storage/{{ $book->cover_image }}";
    coverImage.style.width = "100%";
    coverImage.style.height = "100%";
    coverImage.style.objectFit = "cover";
    cover.appendChild(coverImage);
    pages.push(cover);

   // PAGES PDF PREVIEW
    for(
        let pageNumber = startPage;
        pageNumber <= pdfLastPage;
        pageNumber++
    ){

    const pdfPage = await pdf.getPage(pageNumber);

    const viewport = pdfPage.getViewport({
        scale:3
    });

    const canvas = document.createElement("canvas");
    canvas.width = viewport.width;
    canvas.height = viewport.height;
    const context = canvas.getContext("2d");

    await pdfPage.render({
        canvasContext:context,
        viewport:viewport
    }).promise;

    const pageContainer = document.createElement("div");
    pageContainer.className = "page";
    pageContainer.appendChild(canvas);
    pages.push(pageContainer);
    }

    // PAGE FINALE DU PREVIEW

    const finalPage = document.createElement("div");

        finalPage.className =
        "page preview-end-page";

        finalPage.innerHTML = `

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



            <p>
                Par :
                <strong>
                    {{ $book->author->firstname .' '.$book->author->lastname  ?? 'Auteur' }}
                </strong>
            </p>



            <a href="#"
            class="btn btn-danger">

                Acheter le livre

            </a>


        </div>

        `;

        pages.push(finalPage);

        // CREATION FLIPBOOK

        const isMobile = window.innerWidth <= 992;

        const flipBook = new St.PageFlip(

            document.getElementById("book-preview"),
            {
                width: isMobile ? 320 : 450,
                height: isMobile ? 480 : 650,
                size:"stretch",
                minWidth: isMobile ? 280 : 315,
                maxWidth:900,
                minHeight: isMobile ? 400 : 420,
                maxHeight:1200,
                showCover:true,
                usePortrait:isMobile,
                drawShadow:true,
                maxShadowOpacity:1,
                flippingTime:1200,
                mobileScrollSupport:true
            }

        );


    // CHARGEMENT
    console.log("Pages chargées :", pages.length);
    flipBook.loadFromHTML(pages);

</script>

@endif --}}

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