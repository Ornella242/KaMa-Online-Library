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
            <span class="eyebrow">Bibliothèque</span>
            <h1>Mes livres</h1>
            <p>Tous les ouvrages que vous avez achetés — téléchargeables à tout moment.</p>
        </div>
        <a href="{{ route('catalogue') }}" class="btn btn-outline-danger">
            <i class="bi bi-plus-lg"></i> Explorer le catalogue
        </a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="reader-stat-grid">
        <article>
            <span>Total</span>
            <strong>{{ $stats['total'] }}</strong>
        </article>
        <article>
            <span>Ebooks</span>
            <strong>{{ $stats['ebooks'] }}</strong>
        </article>
        <article>
            <span>Audio</span>
            <strong>{{ $stats['audio'] }}</strong>
        </article>
        <article>
            <span>Dépensé</span>
            <strong>{{ number_format($stats['spent'], 0, ',', ' ') }} <small>XOF</small></strong>
        </article>
    </div>

    @if($purchases->isEmpty())
        <div class="reader-empty">
            <span><i class="bi bi-journal-bookmark"></i></span>
            <h2>Aucun livre pour le moment</h2>
            <p>Achetez un ouvrage dans le catalogue — même sans compte au départ, vos achats apparaissent ici dès que vous vous connectez avec le même email.</p>
            <a href="{{ route('catalogue') }}" class="btn btn-danger">Voir le catalogue</a>
        </div>
    @else
        <div class="reader-table-card">
            <div class="table-responsive">
                <table class="table reader-books-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Auteur</th>
                            <th>Type</th>
                            <th>Prix payé</th>
                            <th>Acheté le</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            @php $book = $purchase->book; @endphp
                            <tr>
                                <td>
                                    <div class="reader-book-cell">
                                        <img
                                            src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}"
                                            alt="">
                                        <div>
                                            <a href="{{ route('books.show', $book) }}" class="title">{{ $book->title }}</a>
                                            @if($book->category)
                                                <small>{{ $book->category->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ trim(($book->author?->firstname ?? '').' '.($book->author?->lastname ?? '')) ?: '—' }}
                                </td>
                                <td>
                                    <span class="reader-type-badge {{ $book->type === 'audio' ? 'audio' : 'ebook' }}">
                                        {{ $book->type === 'audio' ? 'Audio' : 'Ebook' }}
                                    </span>
                                </td>
                                <td class="price">{{ number_format($purchase->amount, 0, ',', ' ') }} XOF</td>
                                <td>{{ $purchase->created_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="reader-table-actions">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-light" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('reader.books.download', $book) }}" class="btn btn-sm btn-danger" title="Télécharger">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
