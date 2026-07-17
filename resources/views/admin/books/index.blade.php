@extends('layouts.admin')

@section('admin-content')

<div class="col-lg-12 mb-3">

    <div class="d-flex justify-content-between align-items-center">

        <h3 class="h3 fw-bold mb-0">
            Ma 
            <span class="text-red">
                bibliothèque
            </span>
        </h3>

        <a href="{{ route('admin.books.create') }}"
           class="library-add-btn">
            <i class="bi bi-plus-lg me-2"></i>
            Ajouter un livre
        </a>

    </div>

</div>

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}

                    <button 
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>
                </div>

            @endif
            {{-- ERRORS --}}
            @if($errors->any())

                <div class="alert alert-danger mb-4">

                    <strong>
                        Veuillez corriger les erreurs suivantes :
                    </strong>


                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="admin-books-stats">
                <div class="library-card">
                {{-- STATS --}}
                <div class="library-stats">
                    <div class="library-stat active">
                        <i class="bi bi-book"></i>
                        <div>
                            <strong>
                                {{ $totalBooks}}
                            </strong>

                            <span>
                                Tous
                            </span>

                        </div>

                    </div>

                    <div class="library-stat">

                        <i class="bi bi-check-circle"></i>
                        <div>

                            <strong>
                                {{ $publishedBooks }}
                            </strong>

                            <span>
                                Publiés
                            </span>

                        </div>

                    </div>


                    <div class="library-stat">
                        <i class="bi bi-pencil"></i>
                        <div>
                            <strong>
                                {{ $books->where('status','draft')->count() }}
                            </strong>

                            <span>
                                Brouillons
                            </span>
                        </div>
                    </div>

                    <div class="library-stat">
                        <i class="bi bi-credit-card"></i>
                        <div>
                            <strong>
                                {{ $soldBooks }}
                            </strong>

                            <span>
                                Vendus
                            </span>

                        </div>
                    </div>

                </div>

                {{-- FILTERS --}}
                <form method="GET"
                    action="{{ route('admin.books.index') }}"
                    class="library-filter">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un livre...">
                    </div>

                    <select name="status" class="form-select">
                        <option value="">
                            Tous les statuts
                        </option>

                        <option value="published">
                            Publiés
                        </option>

                        <option value="draft">
                            Brouillons
                        </option>

                        <option value="unpublished">
                            Non publiés
                        </option>
                    </select>

                    <select name="type"
                            class="form-select">
                        <option value="">
                            Tous les types
                        </option>
                        <option value="ebook">
                            Ebook
                        </option>
                        <option value="audio">
                            Audio
                        </option>
                    </select>

                    <select name="sort"
                            class="form-select">
                        <option value="">
                            Plus récent
                        </option>
                        <option value="oldest">
                            Plus ancien
                        </option>
                        <option value="price_high">
                            Prix élevé
                        </option>
                        <option value="price_low">
                            Prix bas
                        </option>
                    </select>

                    <button class="filter-btn">
                        <i class="bi bi-funnel"></i>
                    </button>
                </form>


                {{-- BOOKS --}}
                    <div class="books-list admin-books-slider desktop-books-list">

                        @forelse($books as $book)
                            <div class="book-item">
                                <div class="book-cover">
                                    <img 
                                    src="{{ asset('storage/'.$book->cover_image) }}"
                                    alt="{{ $book->title }}">

                                    <span class="book-type">
                                        @if($book->type == 'ebook')
                                            <i class="bi bi-file-earmark-text"></i>
                                            Ebook
                                        @else
                                            <i class="bi bi-headphones"></i>
                                            Audio
                                        @endif

                                    </span>
                                </div>

                                <div class="book-content">
                                    <div class="book-top">
                                        <div>
                                            <h5>
                                                {{ $book->title }}
                                            </h5>

                                            <small>
                                                <i class="bi bi-person"></i>
                                                {{ Auth::user()->firstname }}
                                            </small>
                                        </div>


                                        @if($book->status == 'published')
                                            <span class="status published">
                                                Publié
                                            </span>

                                        @elseif($book->status == 'draft')
                                            <span class="status draft">
                                                Brouillon - Paiement de depot requis 
                                            </span>
                                        @elseif ($book->status == 'under_review')
                                            <span class="status under_review">
                                                Sous vérification
                                            </span>
                                        @elseif ($book->status == 'waiting_review')
                                            <span class="status pending">
                                                En attente de vérification
                                            </span>
                                        @elseif ($book->status == 'revision_required')
                                            <span class="status unpublished">
                                                Corrections demandées
                                            </span>
                                        @else
                                            <span class="status unpublished">
                                                Non publié
                                            </span>
                                        @endif
                                    </div>


                                    <div class="book-details">
                                        <span>
                                            <i class="bi bi-bookmark"></i>
                                            {{ $book->category->name }}
                                        </span>

                                        <span>
                                            <i class="bi bi-tag"></i>
                                            {{ $book->subcategory->name }}
                                        </span>
                                        @if($book->type == 'ebook')
                                            <span>
                                                {{ $book->pages }} pages
                                            </span>
                                        @else
                                            <span>
                                                {{ $book->duration }}
                                            </span>
                                        @endif
                                        <span>
                                            {{ $book->price }} $
                                        </span>
                                    </div>

                                    <div class="book-actions">
                                        <a href="{{ route('admin.books.show',$book->id) }}"
                                        class="btn-view">
                                            <i class="bi bi-eye"></i>
                                            Voir
                                        </a>

                                        
                                            <a href="{{ route('admin.books.edit',$book) }}"
                                            class="btn-edit">
                                                <i class="bi bi-pencil"></i>
                                                Modifier
                                            </a>

                                        <a href="{{ route('admin.books.boost',$book) }}"
                                            class="btn-boost">
                                            <i class="bi bi-share-fill"></i>
                                            Booster
                                        </a>

                                        @if($book->activeSponsorship)

                                            <div class="alert alert-success mb-0">

                                                <i class="bi bi-megaphone-fill"></i>

                                                Livre mis en avant jusqu'au :

                                                <strong>
                                                    {{ $book->activeSponsorship->ends_at->format('d/m/Y') }}
                                                </strong>

                                            </div>


                                        @else

                                        <form action="{{ route('admin.books.sponsor',$book) }}" method="POST">

                                            @csrf

                                            <button class="btn btn-warning">

                                                <i class="bi bi-megaphone-fill"></i>

                                                Mettre en avant sur KaMa

                                            </button>

                                        </form>

                                        @endif


                                        <!-- DELETE -->
                                            <form 
                                                action=""
                                                method="POST"
                                                class="delete-book-form d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button 
                                                    type="button"
                                                    class="btn btn-sm btn-danger delete-book-btn">

                                                    <i class="bi bi-trash3 me-1"></i>
                                                    Supprimer

                                                </button>

                                            </form>
                                    </div>
                                </div>
                            </div>

                           
                        @empty
                            <div class="empty-books">
                                <i class="bi bi-exclamation-circle"></i>
                                <h5>
                                    Aucun livre trouvé
                                </h5>

                            </div>
                        @endforelse
                    </div>

                     <!-- MOBILE BOOK SLIDER START -->
                            <div class="mobile-books-slider-wrapper">

                                <div class="mobile-books-slider">

                                    @forelse($books as $book)

                                    <div class="mobile-book-card">

                                        <div class="mobile-book-cover">

                                            <img 
                                                src="{{ asset('storage/'.$book->cover_image) }}"
                                                alt="{{ $book->title }}"
                                            >

                                            @if($book->status == 'published')

                                                <span class="mobile-status published">
                                                    Publié
                                                </span>

                                            @elseif($book->status == 'draft')

                                                <span class="mobile-status draft">
                                                    Brouillon
                                                </span>

                                            @elseif($book->status == 'under_review')

                                                <span class="mobile-status review">
                                                    Vérification
                                                </span>
                                            @elseif($book->status == 'waiting_review')

                                                <span class="mobile-status pending">
                                                    En attente
                                                </span>
                                            @elseif($book->status == 'revision_required')

                                                <span class="mobile-status unpublished">
                                                    À corriger
                                                </span>

                                            @endif

                                        </div>


                                        <div class="mobile-book-content">


                                            <h4>
                                                {{ $book->title }}
                                            </h4>


                                            <div class="mobile-book-info">

                                                <span>
                                                    <i class="bi bi-bookmark"></i>
                                                    {{ $book->category->name }}
                                                </span>


                                                @if($book->type == 'ebook')

                                                <span>
                                                    <i class="bi bi-file-earmark-text"></i>
                                                    {{ $book->pages }} pages
                                                </span>

                                                @else

                                                <span>
                                                    <i class="bi bi-headphones"></i>
                                                    {{ $book->duration }}
                                                </span>

                                                @endif


                                                <span>
                                                    {{ $book->price }} $
                                                </span>

                                            </div>



                                            <div class="mobile-book-actions">

                                                <a href="{{ route('admin.books.show',$book->id) }}"
                                                class="mobile-btn-view">

                                                    <i class="bi bi-eye"></i>
                                                    Voir

                                                </a>


                                                <div class="mobile-actions-menu">

                                                    <button class="mobile-more-btn">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>


                                                    <div class="mobile-dropdown">

                                                        @if($book->status != 'published')

                                                        <a href="{{ route('admin.books.edit',$book) }}">
                                                            <i class="bi bi-pencil"></i>
                                                            Modifier
                                                        </a>

                                                        @endif


                                                        <a href="{{ route('admin.books.boost',$book) }}">
                                                            <i class="bi bi-share-fill"></i>
                                                            Booster
                                                        </a>


                                                        @if($book->activeSponsorship)

                                                            <div class="alert alert-success mb-0">

                                                                <i class="bi bi-megaphone-fill"></i>

                                                                Livre mis en avant jusqu'au :

                                                                <strong>
                                                                    {{ $book->activeSponsorship->ends_at->format('d/m/Y') }}
                                                                </strong>

                                                            </div>


                                                        @else

                                                        <form action="{{ route('admin.books.sponsor',$book) }}" method="POST">

                                                            @csrf

                                                            <button class="btn btn-warning">

                                                                <i class="bi bi-megaphone-fill"></i>

                                                                Mettre en avant sur KaMa

                                                            </button>

                                                        </form>

                                                        @endif


                                                        <button class="delete-mobile-book">
                                                            <i class="bi bi-trash"></i>
                                                            Supprimer
                                                        </button>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                    </div>


                                    @empty

                                    <div class="empty-books">
                                        Aucun livre trouvé
                                    </div>

                                    @endforelse


                                </div>


                            </div>
                            <!-- MOBILE BOOK SLIDER END -->

                


                <div class="mt-4">
                    <div class="kama-pagination">
                        {{ $books->links() }}
                    </div>
                    {{-- <div class="admin-books-pagination mt-5">

                        {{ $books->onEachSide(1)->links() }}

                    </div> --}}
                        
                </div>
            </div>
            </div>
            
        </div>
    </div>
    <!-- Listing table END -->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){

    if(document.querySelector('.mobile-books-slider')){

        tns({

            container: '.mobile-books-slider',

            items:1,

            slideBy:1,

            gutter:20,

            mouseDrag:true,

            controls:true,

            nav:true,

            speed:500,

            controlsText:[
                '<i class="bi bi-chevron-left"></i>',
                '<i class="bi bi-chevron-right"></i>'
            ]

        });

    }

});

document.addEventListener('DOMContentLoaded',function(){


document.querySelectorAll('.mobile-more-btn')
.forEach(button=>{


    button.addEventListener('click',function(e){

        e.stopPropagation();


        let parent=this.closest('.mobile-actions-menu');


        document.querySelectorAll('.mobile-actions-menu')
        .forEach(menu=>{

            if(menu !== parent){
                menu.classList.remove('active');
            }

        });


        parent.classList.toggle('active');


    });


});


document.addEventListener('click',function(){

    document.querySelectorAll('.mobile-actions-menu')
    .forEach(menu=>{

        menu.classList.remove('active');

    });

});


});
</script>
@endsection