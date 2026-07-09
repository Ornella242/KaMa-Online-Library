@extends('layouts.app')


@section('content')

<div class="container py-5">

    <!-- BACK BUTTON -->
    <div class="mb-4">
        <a href="{{ route('writer.books') }}" 
           class="btn btn-outline-dark rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>
            Retour à mes livres
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


                    <a href="{{ asset('storage/'.$book->file_path) }}"
                       target="_blank"
                       class="btn btn-danger btn-lg rounded-pill px-4">

                        <i class="bi bi-book-half me-2"></i>
                        Lire le livre

                    </a>


                    @else


                    <audio controls class="audio-player">

                        <source 
                        src="{{ asset('storage/'.$book->file_path) }}"
                        type="audio/mpeg">

                    </audio>


                    @endif



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

            {!! $book->long_description !!}

        </div>


    </div>



</div>


@endsection