@extends('layouts.reader')

@section('reader-content')
<div class="reader-workspace">
    <div class="d-grid mb-3 d-lg-none">
        <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <header class="reader-page-hero">
        <div>
            <span class="eyebrow">Envies</span>
            <h1>Ma liste de souhaits</h1>
            <p>{{ $items->count() }} livre(s) sauvegardé(s) pour plus tard.</p>
        </div>
        @if($items->isNotEmpty())
            <form method="POST" action="{{ route('reader.wishlist.clear') }}" onsubmit="return confirm('Vider toute la liste ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Tout supprimer
                </button>
            </form>
        @endif
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="reader-empty">
            <span><i class="bi bi-heart"></i></span>
            <h2>Votre wishlist est vide</h2>
            <p>Ajoutez des livres depuis le catalogue ou la fiche d’un ouvrage avec l’icône cœur.</p>
            <a href="{{ route('catalogue') }}" class="btn btn-danger">Parcourir le catalogue</a>
        </div>
    @else
        <div class="reader-wishlist-list">
            @foreach($items as $item)
                @php
                    $book = $item->book;
                    $avg = $book->reviews->avg('rating');
                @endphp
                <article class="reader-wishlist-item">
                    <a href="{{ route('books.show', $book) }}" class="cover">
                        <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                    </a>
                    <div class="body">
                        <span class="type">{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</span>
                        <h3><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></h3>
                        <p>
                            {{ trim(($book->author?->firstname ?? '').' '.($book->author?->lastname ?? '')) ?: 'Auteur inconnu' }}
                            @if($book->category) · {{ $book->category->name }} @endif
                        </p>
                        <div class="meta">
                            <strong>${{ number_format($book->price, 2, '.', ',') }}</strong>
                            @if($avg)
                                <span><i class="bi bi-star-fill"></i> {{ number_format($avg, 1) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="actions">
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-light" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('reader.wishlist.cart', $book) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" title="Ajouter au panier">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('reader.wishlist.destroy', $book) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
