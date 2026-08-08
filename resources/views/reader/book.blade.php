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
            <strong>${{ number_format($stats['spent'], 2, '.', ',') }}</strong>
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
                                <td class="price">${{ number_format($purchase->amount, 2, '.', ',') }}</td>
                                <td>{{ $purchase->created_at?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="reader-table-actions">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-light" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light"
                                            title="Commenter"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reviewModal{{ $book->id }}"
                                        >
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                        <a href="{{ route('reader.books.download', $book) }}" class="btn btn-sm btn-danger" title="Télécharger">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <div
                                class="modal fade"
                                id="reviewModal{{ $book->id }}"
                                tabindex="-1"
                                aria-labelledby="reviewModalLabel{{ $book->id }}"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header">
                                            <div>
                                                <h5
                                                    class="modal-title fw-bold"
                                                    id="reviewModalLabel{{ $book->id }}"
                                                >
                                                    Donner votre avis
                                                </h5>

                                                <small class="text-muted">
                                                    {{ $book->title }}
                                                </small>
                                            </div>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Fermer"
                                            ></button>
                                        </div>

                                        <form
                                            action="{{ route('books.reviews.store', $book) }}"
                                            method="POST"
                                        >
                                            @csrf

                                            <div class="modal-body">

                                                {{-- Note --}}
                                                <div class="mb-4">

                                                    <label class="form-label fw-semibold">
                                                        Votre note
                                                    </label>

                                                 <div class="review-stars">
                                                    @for($i = 5; $i >= 1; $i--)

                                                        <input
                                                            type="radio"
                                                            name="rating"
                                                            id="rating-{{ $book->id }}-{{ $i }}"
                                                            value="{{ $i }}"
                                                        >

                                                        <label for="rating-{{ $book->id }}-{{ $i }}">
                                                            <i class="bi bi-star-fill"></i>
                                                        </label>

                                                    @endfor

                                                </div>
                                                </div>

                                                {{-- Commentaire --}}
                                                <div class="mb-3">

                                                    <label
                                                        for="comment{{ $book->id }}"
                                                        class="form-label fw-semibold"
                                                    >
                                                        Votre commentaire
                                                    </label>

                                                    <textarea
                                                        name="comment"
                                                        id="comment{{ $book->id }}"
                                                        class="form-control"
                                                        rows="5"
                                                        minlength="10"
                                                        maxlength="1000"
                                                        placeholder="Partagez votre expérience avec ce livre..."
                                                        required
                                                    ></textarea>

                                                    <div class="form-text">
                                                        Minimum 10 caractères.
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-light"
                                                    data-bs-dismiss="modal"
                                                >
                                                    <i class="bi bi-x-lg me-1"></i>
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    <i class="bi bi-send me-1"></i>
                                                    Publier mon avis
                                                </button>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
