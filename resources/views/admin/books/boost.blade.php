@extends('layouts.admin')
@php
    $bookUrl = config('app.url').'/books/'.$book->id;
    $coverUrl = config('app.url').'/storage/'.$book->cover_image;
@endphp
@section('admin-content')


<div class="container py-5 kama-boost-page">
    <!-- BACK BUTTON -->
    <div class="mb-4">

        <a href="{{ url('admin/books') }}"
           class="btn kama-back-btn">

            <i class="bi bi-arrow-left me-2"></i>
            Retour à mes livres

        </a>

    </div>
    <!-- HERO -->
    <div class="kama-boost-hero">
        <div class="row align-items-center g-5">
            <!-- COVER -->
            <div class="col-lg-4 text-center">
                <div class="kama-boost-cover">
                    <img 
                    src="{{ asset('storage/'.$book->cover_image) }}"
                    alt="{{ $book->title }}">

                </div>

            </div>



            <!-- INFO -->

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

                    {{ $book->author->firstname }}
                    {{ $book->author->lastname }}

                </p>



                <p class="description">

                    {{ Str::limit(strip_tags($book->short_description),250) }}

                </p>



                <div class="book-info">


                    <div>

                        <small>
                            Prix
                        </small>

                        <strong>
                            {{ number_format($book->price,2) }} $
                        </strong>

                    </div>



                    <div>

                        <small>
                            Catégorie
                        </small>

                        <strong>
                            {{ $book->category->name }}
                        </strong>

                    </div>



                    <div>

                        <small>
                            Type
                        </small>

                        <strong>
                            {{ ucfirst($book->type) }}
                        </strong>

                    </div>


                </div>


            </div>


        </div>

    </div>

    <!-- SHARE AREA -->

    <div class="row g-4 mt-5">
        <!-- PREVIEW -->
        <div class="col-lg-7">
            <div class="kama-card">
                <div class="kama-card-header">
                    <i class="bi bi-phone"></i>
                    Aperçu de votre publication
                </div>


                <div class="post-preview">
                    <img 
                    src="{{ asset('storage/'.$book->cover_image) }}">

                    <h3>
                        {{ $book->title }}
                    </h3>

                    <p>
                        {{ Str::limit(strip_tags($book->short_description),180) }}
                    </p>

                    <strong>
                        ✍️ 
                        {{ $book->author->firstname }}
                        {{ $book->author->lastname }}
                    </strong>

                    <span>
                        Disponible sur KaMa 📚
                    </span>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="col-lg-5">
            <div class="kama-card">
                <div class="kama-card-header">
                    <i class="bi bi-share-fill"></i>
                    Partager votre livre
                </div>

                <div class="share-buttons">
                    <!-- WHATSAPP -->
                    <a target="_blank"

                    href="https://wa.me/?text={{ urlencode(
                    'Découvrez ce livre sur KaMa Online Library'
                    .$book->title.''
                    .'Auteur : '
                    .$book->author->firstname.' '
                    .$book->author->lastname.' '
                    .Str::limit(strip_tags($book->short_description),200).' '.'Lire ici :'.$bookUrl) }}"
                    class="share whatsapp">
                        <i class="bi bi-whatsapp"></i>
                        WhatsApp
                    </a>

                    <!-- LINKEDIN -->
                    <a target="_blank"
                    href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('books.show',$book)) }}"
                    class="share linkedin">
                        <i class="bi bi-linkedin"></i>
                        LinkedIn
                    </a>

                    <!-- FACEBOOK -->
                    <a target="_blank"
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('books.show',$book)) }}"
                    class="share facebook">
                        <i class="bi bi-facebook"></i>
                        Facebook
                    </a>

                    <!-- X -->
                    <a target="_blank"

                    href="https://twitter.com/intent/tweet?text={{ urlencode(
                    $book->title.' - Disponible sur KaMa '.route('books.show',$book)
                    ) }}"
                    class="share twitter">
                        <i class="bi bi-twitter-x"></i>
                        X
                    </a>

                    <!-- COPY -->
                    <button onclick="copyText()" class="share copy">


                        <i class="bi bi-copy"></i>

                        Copier le lien


                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function copyText() {

        const text = `📚 Découvrez ce livre sur KaMa Online Library

        {{ $book->title }}

        ✍️ Auteur : {{ $book->author->firstname }} {{ $book->author->lastname }}

        {{ Str::limit(strip_tags($book->short_description),200) }}

        Lire ici :
        {{ config('app.url') }}/books/{{ $book->id }}`;


        navigator.clipboard.writeText(text)
        .then(() => {

            const button = document.querySelector('.copy');

            button.innerHTML = `
                <i class="bi bi-check-circle"></i>
                Copié !
            `;


            setTimeout(() => {

                button.innerHTML = `
                    <i class="bi bi-copy"></i>
                    Copier le texte
                `;

            },2000);


        })
        .catch(() => {

            alert('Impossible de copier le texte');

        });

    }
</script>
@endsection 