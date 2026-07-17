@extends('layouts.admin')

@section('admin-content')

 <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Bibliothèque KaMa
            </h3>

            <p class="text-black mb-0">
                Gérez tous les livres ajouter sur KaMa.
            </p>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>
                    </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Veuillez corriger les erreurs suivantes :</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        @endif
    </div>

    <!-- Counter START -->
    <div class="row g-4 mb-5 kama-allbooks-stats">
        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body shadow p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Number -->
                    <div class="me-2">
                        <span class="fw-semibold text-black">Livres au total</span>
                        <h3 class="mb-0 mt-2">{{ $totalBooks }}</h3>
                    </div>
                    <!-- Icon -->
                    <div class="icon-lg rounded-circle flex-shrink-0 bg-primary bg-opacity-10 text-primary mb-0">
                        <i class="bi bi-book fa-fw"></i>
                    </div>
                </div>
            </div>	
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body shadow p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Number -->
                    <div class="me-2">
                        <span class="fw-semibold text-black">Sous vérification</span>
                        <h3 class="mb-0 mt-2">{{ $reviewBooks }}</h3>
                    </div>
                    <!-- Icon -->
                    <div class="icon-lg rounded-circle flex-shrink-0 bg-danger bg-opacity-10 text-danger mb-0">
                        <i class="bi bi-hourglass fa-fw"></i>
                    </div>
                </div>
            </div>	
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body shadow p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Number -->
                    <div class="me-2">
                        <span class="fw-semibold text-black">Publiés</span>
                        <h3 class="mb-0 mt-2">{{ $publishedBooks}}</h3>
                    </div>
                    <!-- Icon -->
                    <div class="icon-lg rounded-circle flex-shrink-0 bg-success bg-opacity-10 text-success mb-0">
                        <i class="bi bi-check-circle fa-fw"></i>
                    </div>
                </div>
            </div>	
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body shadow p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Number -->
                    <div class="me-2">
                        <span class="fw-semibold text-black">En attente</span>
                        <h3 class="mb-0 mt-2">{{ $pendingBooks}}</h3>
                    </div>
                    <!-- Icon -->
                    <div class="icon-lg rounded-circle flex-shrink-0 bg-warning bg-opacity-10 text-warning mb-0">
                        <i class="bi bi-journal-text fa-fw"></i>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!-- Counter END -->

    <div class="kama-toolbar mb-5">

        <div class="kama-filter-group">

            <a href="{{ route('admin.books.all') }}"
            class="kama-filter {{ !request('status') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Tous
            </a>

            <a href="{{ route('admin.books.all',['status'=>'waiting_review']) }}"
            class="kama-filter {{ request('status')=='waiting_review' ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i>
                En attente
            </a>

            <a href="{{ route('admin.books.all',['status'=>'under_review']) }}"
            class="kama-filter {{ request('status')=='under_review' ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i>
                En vérification
            </a>

            <a href="{{ route('admin.books.all',['status'=>'published']) }}"
            class="kama-filter {{ request('status')=='published' ? 'active' : '' }}">
                <i class="bi bi-patch-check-fill"></i>
                Publiés
            </a>

            <a href="{{ route('admin.books.all',['status'=>'draft']) }}"
            class="kama-filter {{ request('status')=='draft' ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i>
                Brouillons
            </a>

        </div>


        <form method="GET"
            action="{{ route('admin.books.all') }}"
            class="kama-search">

            @if(request('status'))
                <input type="hidden"
                    name="status"
                    value="{{ request('status') }}">
            @endif

            <i class="bi bi-search"></i>

            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Rechercher un livre...">

        </form>

    </div>

    @if($books->count())
        <div class="kama-books-grid desktop-books">


            @foreach($books as $book)

            <div class="kama-library-card">


                <div class="library-cover">

                    <img 
                    src="{{ asset('storage/'.$book->cover_image) }}"
                    alt="{{ $book->title }}">


                    <span class="library-status {{ $book->status }}">

                        @if($book->status == 'under_review')
                            Sous vérification

                        @elseif($book->status == 'waiting_review')
                            En attente de vérification

                        @elseif($book->status == 'published')
                            Publié

                        @elseif($book->status == 'draft')
                            Brouillon

                        @elseif($book->status == 'revision_required')
                            Modifications requises
                        @endif

                    </span>

                </div>



                <div class="library-content">


                    <h5>
                        {{ $book->title }}
                    </h5>


                    <p class="author">

                        <i class="bi bi-person"></i>

                        {{ $book->author->firstname }}
                        {{ $book->author->lastname }}

                    </p>



                    <div class="library-tags">

                        <span>
                            {{ $book->category->name }}
                        </span>


                        <span>
                            {{ ucfirst($book->type) }}
                        </span>

                    </div>



                    <div class="library-footer">


                        <strong>
                            {{ $book->price }} $
                        </strong>


                        <a href="{{route('admin.books.show',$book)}}">

                            Voir

                        </a>


                    </div>


                </div>


            </div>


            @endforeach


        </div>



        <div class="mobile-books-slider-wrapper">
            <div class="tiny-slider arrow-round arrow-blur">
                <div class="tiny-slider-inner admin-kama-mobile-slider"

                    data-autoplay="false"
                    data-arrow="true"
                    data-dots="true"
                    data-items="1">

                    @foreach($books as $book)
                        <div>
                            <div class="kama-library-card mobile-card">

                                <div class="library-cover">
                                    <img 
                                    src="{{asset('storage/'.$book->cover_image)}}">

                                    <span class="library-status {{ $book->status }}">

                                        @if($book->status == 'under_review')
                                            Sous vérification
                                        @elseif($book->status == 'waiting_review')
                                        En attente de vérification

                                        @elseif($book->status == 'published')
                                            Publié

                                        @elseif($book->status == 'draft')
                                            Brouillon

                                        @endif

                                    </span>
                                </div>

                                <div class="library-content">
                                    <h5>
                                    {{$book->title}}
                                    </h5>

                                    <p>
                                    <i class="bi bi-person"></i>
                                    {{$book->author->firstname}}
                                    </p>


                                    <div class="library-tags">
                                        <span>
                                        {{$book->category->name}}
                                        </span>
                                    </div>

                                    <a class="library-view-btn"
                                    href="{{route('admin.books.show',$book)}}">

                                        Voir le livre
                                    </a>
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
            </div>  
        </div>
        
        <div class="card-footer bg-white border-0">

            <div class="category-pagination">

                {{ $books->onEachSide(1)->links() }}

            </div>

        </div>
    @else

    {{-- ETAT VIDE --}}
    <div class="kama-empty-books">

        <div class="empty-icon">

            <i class="bi bi-journal-x"></i>

        </div>

        <h3>
            Aucun livre trouvé
        </h3>

        <p>
            Aucun livre ne correspond à votre recherche ou au filtre sélectionné.
        </p>

        @if(request()->filled('status') || request()->filled('search'))

            <a href="{{ route('admin.books.all') }}"
               class="btn btn-danger rounded-pill px-4">

                <i class="bi bi-arrow-repeat me-2"></i>

                Réinitialiser les filtres

            </a>

        @endif

    </div>

    

@endif

@endsection