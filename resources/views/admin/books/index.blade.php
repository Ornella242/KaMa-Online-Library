@extends('layouts.admin')

@section('title', 'Mes livres')
@section('page-title', 'Mes livres')

@section('admin-content')
<div class="admin-books-page">
    <div class="admin-books-heading">
        <div>
            <span class="admin-books-eyebrow">Mon espace auteur</span>
            <h2>Mes livres</h2>
            <p>Gérez vos ouvrages, réglez les frais de publication et suivez leur statut éditorial.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="admin-books-add">
            <i class="bi bi-plus-lg"></i>
            Ajouter un livre
        </a>
    </div>

    @if(session('book_created'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <strong><i class="bi bi-check-circle-fill me-2"></i>Livre ajouté</strong>
                    <p class="mb-0 mt-1">{{ session('book_created.message') }}</p>
                </div>
                <a href="{{ route('admin.books.deposit', session('book_created.book_id')) }}" class="admin-books-add">
                    <i class="bi bi-credit-card"></i> Payer les frais
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="admin-books-stats">
        <a href="{{ route('admin.books.index') }}" class="admin-books-stat {{ !request('status') ? 'active' : '' }}">
            <span class="admin-books-stat-icon total"><i class="bi bi-book-half"></i></span>
            <div><small>Total</small><strong>{{ number_format($totalBooks) }}</strong></div>
        </a>
        <a href="{{ route('admin.books.index', ['status' => 'draft']) }}"
           class="admin-books-stat {{ request('status') === 'draft' ? 'active' : '' }}">
            <span class="admin-books-stat-icon waiting"><i class="bi bi-pencil-square"></i></span>
            <div><small>Brouillons</small><strong>{{ number_format($draftBooks) }}</strong></div>
        </a>
        <a href="{{ route('admin.books.index', ['status' => 'waiting_review']) }}"
           class="admin-books-stat {{ request('status') === 'waiting_review' ? 'active' : '' }}">
            <span class="admin-books-stat-icon review"><i class="bi bi-clock-history"></i></span>
            <div><small>En attente</small><strong>{{ number_format($pendingBooks) }}</strong></div>
        </a>
        <a href="{{ route('admin.books.index', ['status' => 'published']) }}"
           class="admin-books-stat {{ request('status') === 'published' ? 'active' : '' }}">
            <span class="admin-books-stat-icon published"><i class="bi bi-check2-circle"></i></span>
            <div><small>Publiés</small><strong>{{ number_format($publishedBooks) }}</strong></div>
        </a>
        <a href="{{ route('admin.books.index', ['status' => 'revision_required']) }}"
           class="admin-books-stat {{ request('status') === 'revision_required' ? 'active' : '' }}">
            <span class="admin-books-stat-icon revision"><i class="bi bi-arrow-counterclockwise"></i></span>
            <div><small>À corriger</small><strong>{{ number_format($revisionBooks) }}</strong></div>
        </a>
    </div>

    <section class="admin-books-panel">
        <form method="GET" action="{{ route('admin.books.index') }}" class="admin-books-toolbar">
            <div class="admin-books-search">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un titre…" aria-label="Rechercher un livre">
            </div>

            <div class="admin-books-filter">
                <i class="bi bi-funnel"></i>
                <select name="status" aria-label="Filtrer par statut">
                    <option value="">Tous les statuts</option>
                    <option value="draft" @selected(request('status') === 'draft')>Brouillons</option>
                    <option value="waiting_review" @selected(request('status') === 'waiting_review')>En attente</option>
                    <option value="under_review" @selected(request('status') === 'under_review')>En vérification</option>
                    <option value="published" @selected(request('status') === 'published')>Publiés</option>
                    <option value="revision_required" @selected(request('status') === 'revision_required')>À corriger</option>
                </select>
            </div>

            <div class="admin-books-filter">
                <i class="bi bi-collection"></i>
                <select name="type" aria-label="Filtrer par type">
                    <option value="">Tous les types</option>
                    <option value="ebook" @selected(request('type') === 'ebook')>Ebook</option>
                    <option value="audio" @selected(request('type') === 'audio')>Audio</option>
                </select>
            </div>

            <button type="submit" class="admin-books-filter-btn">Filtrer</button>

            @if(request()->filled('status') || request()->filled('search') || request()->filled('type'))
                <a href="{{ route('admin.books.index') }}" class="admin-books-reset" title="Réinitialiser">
                    <i class="bi bi-x-lg"></i>
                    <span>Réinitialiser</span>
                </a>
            @endif
        </form>

        <div class="admin-books-table-header">
            <div>
                <strong>{{ number_format($books->total()) }} résultat(s)</strong>
                <span>
                    @if($books->total())
                        Affichage de {{ $books->firstItem() }} à {{ $books->lastItem() }}
                    @else
                        Aucun livre trouvé
                    @endif
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-books-table align-middle">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Classification</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Ajouté le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        @php
                            $statusMeta = match ($book->status) {
                                'draft' => ['Brouillon', 'draft'],
                                'waiting_review' => ['En attente', 'waiting'],
                                'under_review' => ['En vérification', 'review'],
                                'published' => ['Publié', 'published'],
                                'revision_required' => ['À corriger', 'revision'],
                                'rejected' => ['Rejeté', 'rejected'],
                                default => ['Inconnu', 'unknown'],
                            };
                            $statusLabel = $statusMeta[0];
                            $statusClass = $statusMeta[1];
                            $fee = $publicationFees->get($book->type);
                        @endphp
                        <tr>
                            <td>
                                <div class="admin-books-title-cell">
                                    <img src="{{ $book->cover_image
                                        ? asset('storage/'.$book->cover_image)
                                        : asset('assets/images/book/01.jpg') }}" alt="">
                                    <div>
                                        <a href="{{ route('admin.books.show', $book) }}" class="admin-books-title-button">
                                            {{ $book->title }}
                                        </a>
                                        <small>#{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-books-classification">
                                    <strong>{{ $book->category?->name ?? 'Sans catégorie' }}</strong>
                                    <small>
                                        <i class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-text' }}"></i>
                                        {{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}
                                        @if($book->subcategory)
                                            · {{ $book->subcategory->name }}
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td>
                                <strong class="admin-books-price">{{ number_format($book->price, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>
                                <span class="admin-books-status {{ $statusClass }}">
                                    <span></span>{{ $statusLabel }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-books-date">
                                    <strong>{{ $book->created_at?->format('d/m/Y') }}</strong>
                                    <small>{{ $book->created_at?->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="admin-books-actions">
                                    <a href="{{ route('admin.books.show', $book) }}" title="Voir" aria-label="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($book->status === 'draft')
                                        <a href="{{ route('admin.books.deposit', $book) }}" class="primary"
                                           title="Payer le dépôt" aria-label="Payer le dépôt">
                                            <i class="bi bi-credit-card"></i>
                                        </a>
                                        <a href="{{ route('admin.books.edit', $book) }}" title="Modifier" aria-label="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce brouillon ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer" aria-label="Supprimer">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @elseif($book->status === 'revision_required')
                                        <a href="{{ route('admin.books.edit', $book) }}" class="primary"
                                           title="Corriger" aria-label="Corriger">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.books.resubmit', $book) }}" method="POST">
                                            @csrf
                                            <button type="submit" title="Renvoyer" aria-label="Renvoyer">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    @elseif($book->status === 'published')
                                        <a href="{{ route('admin.books.boost', $book) }}" title="Booster" aria-label="Booster">
                                            <i class="bi bi-share-fill"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($book->status === 'draft' && $fee)
                            <tr class="admin-books-inline-hint">
                                <td colspan="6">
                                    <div class="admin-author-inline-pay">
                                        <span>
                                            <i class="bi bi-shield-lock"></i>
                                            Frais de publication : {{ number_format($fee->amount, 0, ',', ' ') }} {{ $fee->currency }}
                                        </span>
                                        <a href="{{ route('admin.books.deposit', $book) }}">Régler maintenant</a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="admin-books-empty">
                                    <span><i class="bi bi-journal-x"></i></span>
                                    <h3>Aucun livre trouvé</h3>
                                    <p>Ajoutez votre premier ouvrage pour commencer la publication.</p>
                                    <a href="{{ route('admin.books.create') }}">Ajouter un livre</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($books->hasPages())
            <div class="admin-books-pagination">
                <span>Page {{ $books->currentPage() }} sur {{ $books->lastPage() }}</span>
                {{ $books->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
</div>
@endsection

@include('admin.partials.admin-books-styles')

@push('styles')
<style>
.admin-books-actions form {
    display: inline;
    margin: 0;
}
.admin-books-actions a,
.admin-books-actions button {
    display: inline-grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border: 1px solid #e6e7ea;
    border-radius: 9px;
    background: #fff;
    color: #5f636b;
}
.admin-books-actions a.primary,
.admin-books-actions button.primary {
    background: #fff0f0;
    border-color: #f0c8c8;
    color: #b30000;
}
.admin-books-actions button {
    padding: 0;
}
.admin-books-title-button {
    border: 0;
    background: transparent;
    color: inherit;
    font: inherit;
    font-weight: 700;
    text-align: left;
    text-decoration: none;
}
.admin-books-title-button:hover {
    color: #b30000;
}
.admin-author-inline-pay {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin: -6px 0 8px;
    padding: 10px 14px;
    border: 1px solid #ffe2b8;
    border-radius: 10px;
    background: #fff8ee;
    color: #8a5a00;
    font-size: .82rem;
}
.admin-author-inline-pay a {
    color: #b30000;
    font-weight: 700;
}
.admin-books-inline-hint td {
    border-top: 0 !important;
    padding-top: 0 !important;
}
</style>
@endpush
