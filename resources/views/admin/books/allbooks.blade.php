@extends('layouts.admin')

@section('title', 'Bibliothèque')
@section('page-title', 'Bibliothèque')

@section('admin-content')
    <div class="admin-books-page">
        <div class="admin-books-heading">
            <div>
                <span class="admin-books-eyebrow">Gestion du catalogue</span>
                <h2>Tous les livres</h2>
                <p>Consultez les livres et suivez leur progression dans le cycle éditorial.</p>
            </div>
            <a href="{{ route('admin.books.create') }}" class="admin-books-add">
                <i class="bi bi-plus-lg"></i>
                Ajouter un livre
            </a>
        </div>

        <div class="admin-books-stats">
            <a href="{{ route('admin.books.all') }}" class="admin-books-stat {{ !request('status') ? 'active' : '' }}">
                <span class="admin-books-stat-icon total"><i class="bi bi-book-half"></i></span>
                <div><small>Total</small><strong>{{ number_format($totalBooks) }}</strong></div>
            </a>
            <a href="{{ route('admin.books.all', ['status' => 'waiting_review']) }}"
                class="admin-books-stat {{ request('status') === 'waiting_review' ? 'active' : '' }}">
                <span class="admin-books-stat-icon waiting"><i class="bi bi-clock-history"></i></span>
                <div><small>En attente</small><strong>{{ number_format($pendingBooks) }}</strong></div>
            </a>
            <a href="{{ route('admin.books.all', ['status' => 'under_review']) }}"
                class="admin-books-stat {{ request('status') === 'under_review' ? 'active' : '' }}">
                <span class="admin-books-stat-icon review"><i class="bi bi-search"></i></span>
                <div><small>En vérification</small><strong>{{ number_format($reviewBooks) }}</strong></div>
            </a>
            <a href="{{ route('admin.books.all', ['status' => 'published']) }}"
                class="admin-books-stat {{ request('status') === 'published' ? 'active' : '' }}">
                <span class="admin-books-stat-icon published"><i class="bi bi-check2-circle"></i></span>
                <div><small>Publiés</small><strong>{{ number_format($publishedBooks) }}</strong></div>
            </a>
            <a href="{{ route('admin.books.all', ['status' => 'revision_required']) }}"
                class="admin-books-stat {{ request('status') === 'revision_required' ? 'active' : '' }}">
                <span class="admin-books-stat-icon revision"><i class="bi bi-arrow-counterclockwise"></i></span>
                <div><small>À corriger</small><strong>{{ number_format($revisionBooks) }}</strong></div>
            </a>
        </div>

        <section class="admin-books-panel">
            <form method="GET" action="{{ route('admin.books.all') }}" class="admin-books-toolbar">
                <div class="admin-books-search">
                    <i class="bi bi-search"></i>
                    <input type="search" name="search" value="{{ request('search') }}"
                        placeholder="Titre ou nom de l’auteur…" aria-label="Rechercher un livre">
                </div>

                <div class="admin-books-filter">
                    <i class="bi bi-funnel"></i>
                    <select name="status" aria-label="Filtrer par statut">
                        <option value="">Tous les statuts</option>
                        <option value="draft" @selected(request('status') === 'draft')>Brouillons</option>
                        <option value="waiting_review" @selected(request('status') === 'waiting_review')>En attente</option>
                        <option value="under_review" @selected(request('status') === 'under_review')>En vérification</option>
                        <option value="published" @selected(request('status') === 'published')>Publiés</option>
                        <option value="revision_required" @selected(request('status') === 'revision_required')>À corriger
                        </option>
                    </select>
                </div>

                <button type="submit" class="admin-books-filter-btn">Filtrer</button>

                @if(request()->filled('status') || request()->filled('search'))
                    <a href="{{ route('admin.books.all') }}" class="admin-books-reset" title="Réinitialiser">
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
                            <th>Auteur</th>
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
                                            [$statusLabel, $statusClass] = match ($book->status) {
                                                'draft' => ['Brouillon', 'draft'],
                                                'waiting_review' => ['En attente', 'waiting'],
                                                'under_review' => ['En vérification', 'review'],
                                                'published' => ['Publié', 'published'],
                                                'revision_required' => ['À corriger', 'revision'],
                                                'rejected' => ['Rejeté', 'rejected'],
                                                default => ['Inconnu', 'unknown'],
                                            };
                                            $authorName = trim(
                                                ($book->author?->firstname ?? '') . ' ' . ($book->author?->lastname ?? '')
                                            ) ?: 'Auteur inconnu';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="admin-books-title-cell">
                                                    <img src="{{ $book->cover_image
                            ? asset('storage/' . $book->cover_image)
                            : asset('assets/images/book/01.jpg') }}" alt="">
                                                    <div>
                                                        <button type="button" class="admin-books-title-button js-open-book-detail"
                                                            data-overlay-target="bookDetailOverlay-{{ $book->id }}">
                                                            {{ $book->title }}
                                                        </button>
                                                        <small>#{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-books-author">
                                                    <strong>{{ $authorName }}</strong>
                                                    <small>{{ $book->author?->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-books-classification">
                                                    <strong>{{ $book->category?->name ?? 'Sans catégorie' }}</strong>
                                                    <small>
                                                        <i
                                                            class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-text' }}"></i>
                                                        {{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td><strong class="admin-books-price">{{ number_format($book->price, 2, ',', ' ') }} $</strong>
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
                                                    <button type="button" class="js-open-book-detail"
                                                        data-overlay-target="bookDetailOverlay-{{ $book->id }}" title="Voir le détail"
                                                        aria-label="Voir le détail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @if($book->status === 'waiting_review')
                                                        <button type="button" data-overlay-target="bookDetailOverlay-{{ $book->id }}"
                                                            class="primary js-open-book-detail" title="Démarrer la vérification"
                                                            aria-label="Démarrer la vérification">
                                                            <i class="bi bi-search"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="admin-books-empty">
                                        <span><i class="bi bi-journal-x"></i></span>
                                        <h3>Aucun livre trouvé</h3>
                                        <p>Aucun livre ne correspond aux critères sélectionnés.</p>
                                        @if(request()->filled('status') || request()->filled('search'))
                                            <a href="{{ route('admin.books.all') }}">Réinitialiser les filtres</a>
                                        @endif
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

        @foreach($books as $book)
            @php
                [$modalStatusLabel, $modalStatusClass] = match ($book->status) {
                    'draft' => ['Brouillon', 'draft'],
                    'waiting_review' => ['En attente', 'waiting'],
                    'under_review' => ['En vérification', 'review'],
                    'published' => ['Publié', 'published'],
                    'revision_required' => ['À corriger', 'revision'],
                    'rejected' => ['Rejeté', 'rejected'],
                    default => ['Inconnu', 'unknown'],
                };
                $modalAuthorName = trim(
                    ($book->author?->firstname ?? '') . ' ' . ($book->author?->lastname ?? '')
                ) ?: 'Auteur inconnu';
            @endphp
            <div class="book-preview-overlay admin-book-detail-overlay d-none" id="bookDetailOverlay-{{ $book->id }}"
                role="dialog" aria-modal="true" aria-labelledby="bookDetailTitle-{{ $book->id }}" aria-hidden="true">
                <div class="book-preview-container admin-book-detail-container">
                    <div class="book-preview-header admin-book-detail-header">
                        <div>
                            <span class="admin-book-modal-eyebrow">Fiche du livre
                                #{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <h2 id="bookDetailTitle-{{ $book->id }}">
                                <i class="bi bi-book me-2"></i>{{ $book->title }}
                            </h2>
                        </div>
                        <button type="button" class="book-preview-close js-close-book-detail" aria-label="Fermer">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="book-preview-body admin-book-detail-body">
                        <div class="admin-book-modal-layout">
                            <aside class="admin-book-modal-aside">
                                <div class="admin-book-modal-cover">
                                    <img src="{{ $book->cover_image
                ? asset('storage/' . $book->cover_image)
                : asset('assets/images/book/01.jpg') }}"
                                        alt="Couverture de {{ $book->title }}">
                                </div>
                                <span class="admin-books-status {{ $modalStatusClass }}">
                                    <span></span>{{ $modalStatusLabel }}
                                </span>
                                <strong class="admin-book-modal-price">{{ number_format($book->price, 2, ',', ' ') }} $</strong>

                                @if($book->type === 'ebook')
                                    <a href="{{ route('admin.books.preview.file', $book) }}" target="_blank" rel="noopener"
                                        class="admin-book-preview-btn">
                                        <i class="bi bi-file-earmark-pdf"></i> Ouvrir le document
                                    </a>
                                @else
                                    <div class="admin-book-audio">
                                        <span><i class="bi bi-headphones"></i> Écouter l’extrait audio</span>
                                        <audio controls preload="none" src="{{ route('admin.books.audio', $book) }}"></audio>
                                    </div>
                                @endif
                            </aside>

                            <div class="admin-book-modal-content">
                                <section class="admin-book-modal-section">
                                    <div class="admin-book-modal-section-title">
                                        <span><i class="bi bi-info-lg"></i></span>
                                        <div><small>Présentation</small>
                                            <h3>Informations générales</h3>
                                        </div>
                                    </div>
                                    <div class="admin-book-info-grid">
                                        <div>
                                            <small>Auteur</small><strong>{{ $modalAuthorName }}</strong><span>{{ $book->author?->email }}</span>
                                        </div>
                                        <div>
                                            <small>Catégorie</small><strong>{{ $book->category?->name ?? 'Non renseignée' }}</strong><span>{{ $book->subcategory?->name }}</span>
                                        </div>
                                        <div>
                                            <small>Format</small><strong>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</strong><span>{{ strtoupper($book->file_type ?? '') }}</span>
                                        </div>
                                        <div>
                                            <small>Langue</small><strong>{{ $book->language ?: 'Non renseignée' }}</strong><span>Année
                                                {{ $book->publication_year ?: '—' }}</span></div>
                                        <div>
                                            <small>{{ $book->type === 'audio' ? 'Durée' : 'Nombre de pages' }}</small><strong>{{ $book->type === 'audio' ? ($book->duration ?: '—') : ($book->pages ?: '—') }}</strong><span>{{ $book->type === 'audio' ? 'HH:MM:SS' : 'pages' }}</span>
                                        </div>
                                        <div><small>Ajout sur
                                                KaMa</small><strong>{{ $book->created_at?->format('d/m/Y') }}</strong><span>{{ $book->created_at?->diffForHumans() }}</span>
                                        </div>
                                        <div><small>Fichier
                                                original</small><strong>{{ $book->original_file_name ?: 'Non renseigné' }}</strong><span>{{ $book->file_size ? number_format($book->file_size / 1048576, 2, ',', ' ') . ' Mo' : 'Taille inconnue' }}</span>
                                        </div>
                                        <div><small>Aperçu
                                                lecteur</small><strong>{{ $book->preview_type === 'pages' ? 'Pages sélectionnées' : 'Extrait texte' }}</strong><span>{{ $book->preview_type === 'pages' ? 'Pages ' . $book->preview_start_page . ' à ' . $book->preview_end_page : 'Description détaillée' }}</span>
                                        </div>
                                        <div><small>Droits
                                                d’auteur</small><strong>{{ $book->copyright_accepted ? 'Acceptés' : 'Non confirmés' }}</strong><span>{{ $book->copyright_accepted_at ? 'Le ' . \Illuminate\Support\Carbon::parse($book->copyright_accepted_at)->format('d/m/Y') : '—' }}</span>
                                        </div>
                                    </div>
                                </section>

                                <section class="admin-book-modal-section">
                                    <div class="admin-book-modal-section-title">
                                        <span><i class="bi bi-card-text"></i></span>
                                        <div><small>Contenu</small>
                                            <h3>Description du livre</h3>
                                        </div>
                                    </div>
                                    @if($book->short_description)
                                        <p class="admin-book-short-description">{{ $book->short_description }}</p>
                                    @endif
                                    <div class="admin-book-long-description">
                                        {{ trim(strip_tags($book->long_description ?? '')) ?: 'Aucune description détaillée disponible.' }}
                                    </div>
                                </section>

                                @if($book->rejection_reason)
                                    <div class="admin-book-revision-note">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <div><strong>Corrections demandées</strong>
                                            <p>{{ $book->rejection_reason }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($book->publicationPayment)
                                    <section class="admin-book-modal-section">
                                        <div class="admin-book-modal-section-title">
                                            <span><i class="bi bi-receipt"></i></span>
                                            <div><small>Publication</small>
                                                <h3>Paiement des frais</h3>
                                            </div>
                                        </div>
                                        <div class="admin-book-info-grid">
                                            <div><small>État du
                                                    paiement</small><strong>{{ match ($book->publicationPayment->status) { 'success' => 'Validé', 'pending' => 'En attente', 'failed' => 'Échoué', default => ucfirst($book->publicationPayment->status)} }}</strong><span>{{ $book->publicationPayment->payment_method ?: 'Mode non renseigné' }}</span>
                                            </div>
                                            <div><small>Montant</small><strong>{{ number_format($book->publicationPayment->amount, 2, ',', ' ') }}
                                                    {{ $book->publicationPayment->currency }}</strong><span>{{ $book->publicationPayment->reference }}</span>
                                            </div>
                                            <div>
                                                <small>Date</small><strong>{{ $book->publicationPayment->updated_at?->format('d/m/Y à H:i') }}</strong><span>Dernière
                                                    mise à jour</span></div>
                                        </div>
                                    </section>
                                @endif

                                @if($book->status === 'waiting_review')
                                    <div class="admin-book-workflow">
                                        <div><small>Étape suivante</small><strong>Démarrer la vérification éditoriale</strong></div>
                                        <form method="POST" action="{{ route('admin.books.review', $book) }}">
                                            @csrf
                                            <button type="submit"><i class="bi bi-search me-2"></i>Commencer</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="admin-book-detail-footer">
                        <button type="button" class="admin-book-modal-close js-close-book-detail">Fermer</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        .admin-books-page {
            display: grid;
            gap: 22px
        }

        .admin-books-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px
        }

        .admin-books-eyebrow {
            display: block;
            margin-bottom: 4px;
            color: #b30000;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .admin-books-heading h2 {
            margin: 0;
            font-size: 1.55rem
        }

        .admin-books-heading p {
            margin: 5px 0 0;
            color: #777b83;
            font-size: .82rem
        }

        .admin-books-add {
            display: inline-flex;
            min-height: 43px;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border-radius: 10px;
            background: #b30000;
            color: #fff;
            font-size: .78rem;
            font-weight: 700
        }

        .admin-books-add:hover {
            background: #8f0000;
            color: #fff
        }

        .admin-books-stats {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px
        }

        .admin-books-stat {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 16px;
            border: 1px solid #e6e7ea;
            border-radius: 14px;
            background: #fff;
            color: #22242a;
            transition: .2s ease
        }

        .admin-books-stat:hover,
        .admin-books-stat.active {
            border-color: #b30000;
            box-shadow: 0 8px 24px rgba(179, 0, 0, .08)
        }

        .admin-books-stat-icon {
            display: grid;
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            place-items: center;
            border-radius: 10px
        }

        .admin-books-stat-icon.total {
            background: #f0f1f3;
            color: #24262b
        }

        .admin-books-stat-icon.waiting {
            background: #fff5d9;
            color: #a86b00
        }

        .admin-books-stat-icon.review {
            background: #e8f5ff;
            color: #157bb5
        }

        .admin-books-stat-icon.published {
            background: #eaf8ef;
            color: #138443
        }

        .admin-books-stat-icon.revision {
            background: #fff0f0;
            color: #b30000
        }

        .admin-books-stat small,
        .admin-books-stat strong {
            display: block
        }

        .admin-books-stat small {
            color: #878b93;
            font-size: .67rem;
            font-weight: 700
        }

        .admin-books-stat strong {
            margin-top: 1px;
            font-size: 1.15rem
        }

        .admin-books-panel {
            overflow: hidden;
            border: 1px solid #e5e7ea;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .admin-books-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 17px 19px;
            border-bottom: 1px solid #eceef0;
            background: #fafafa
        }

        .admin-books-search,
        .admin-books-filter {
            position: relative;
            display: flex;
            align-items: center
        }

        .admin-books-search {
            min-width: 240px;
            max-width: 430px;
            flex: 1
        }

        .admin-books-search i,
        .admin-books-filter i {
            position: absolute;
            left: 13px;
            color: #8c9097;
            font-size: .85rem
        }

        .admin-books-search input,
        .admin-books-filter select {
            width: 100%;
            height: 41px;
            padding: 8px 13px 8px 38px;
            border: 1px solid #dfe1e5;
            border-radius: 10px;
            background: #fff;
            color: #33363b;
            font-size: .76rem;
            outline: none
        }

        .admin-books-search input:focus,
        .admin-books-filter select:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .admin-books-filter {
            width: 200px
        }

        .admin-books-filter-btn {
            height: 41px;
            padding: 8px 16px;
            border: 0;
            border-radius: 10px;
            background: #1c1d20;
            color: #fff;
            font-size: .75rem;
            font-weight: 700
        }

        .admin-books-reset {
            display: inline-flex;
            height: 41px;
            align-items: center;
            gap: 6px;
            padding: 8px 11px;
            border: 1px solid #e0e2e5;
            border-radius: 10px;
            color: #777b82;
            font-size: .72rem;
            font-weight: 700
        }

        .admin-books-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 19px 8px
        }

        .admin-books-table-header strong,
        .admin-books-table-header span {
            display: block
        }

        .admin-books-table-header strong {
            font-size: .8rem
        }

        .admin-books-table-header span {
            margin-top: 2px;
            color: #989ba2;
            font-size: .66rem
        }

        .admin-books-table {
            min-width: 1050px;
            margin: 0
        }

        .admin-books-table thead th {
            padding: 11px 14px;
            border-color: #eceef0;
            background: #fff;
            color: #8e9299;
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap
        }

        .admin-books-table tbody td {
            padding: 12px 14px;
            border-color: #eff0f2;
            color: #45484e;
            font-size: .73rem
        }

        .admin-books-table tbody tr:hover {
            background: #fcfcfd
        }

        .admin-books-title-cell {
            display: flex;
            min-width: 220px;
            align-items: center;
            gap: 11px
        }

        .admin-books-title-cell img {
            width: 39px;
            height: 51px;
            flex: 0 0 39px;
            border-radius: 6px;
            background: #f0f1f2;
            object-fit: cover
        }

        .admin-books-title-cell a,
        .admin-books-title-cell small {
            display: block
        }

        .admin-books-title-cell a {
            max-width: 200px;
            overflow: hidden;
            color: #23252a;
            font-size: .77rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .admin-books-title-cell a:hover {
            color: #b30000
        }

        .admin-books-title-cell small {
            margin-top: 3px;
            color: #9a9da4;
            font-size: .62rem
        }

        .admin-books-author,
        .admin-books-classification,
        .admin-books-date {
            min-width: 125px
        }

        .admin-books-author strong,
        .admin-books-author small,
        .admin-books-classification strong,
        .admin-books-classification small,
        .admin-books-date strong,
        .admin-books-date small {
            display: block
        }

        .admin-books-author strong,
        .admin-books-classification strong,
        .admin-books-date strong {
            color: #3a3d42;
            font-size: .71rem
        }

        .admin-books-author small {
            max-width: 155px;
            margin-top: 3px;
            overflow: hidden;
            color: #989ba2;
            font-size: .61rem;
            text-overflow: ellipsis
        }

        .admin-books-classification small,
        .admin-books-date small {
            margin-top: 4px;
            color: #9699a0;
            font-size: .62rem
        }

        .admin-books-classification small i {
            margin-right: 4px
        }

        .admin-books-price {
            color: #222429;
            font-size: .72rem;
            white-space: nowrap
        }

        .admin-books-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: .62rem;
            font-weight: 700;
            white-space: nowrap
        }

        .admin-books-status>span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor
        }

        .admin-books-status.draft {
            background: #eff0f2;
            color: #666a71
        }

        .admin-books-status.waiting {
            background: #fff5d9;
            color: #9a6500
        }

        .admin-books-status.review {
            background: #e8f5ff;
            color: #157bb5
        }

        .admin-books-status.published {
            background: #eaf8ef;
            color: #138443
        }

        .admin-books-status.revision,
        .admin-books-status.rejected {
            background: #fff0f0;
            color: #b30000
        }

        .admin-books-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px
        }

        .admin-books-actions a {
            display: grid;
            width: 31px;
            height: 31px;
            place-items: center;
            border: 1px solid #e2e4e7;
            border-radius: 8px;
            background: #fff;
            color: #62666d
        }

        .admin-books-actions a:hover,
        .admin-books-actions a.primary {
            border-color: #b30000;
            background: #fff0f0;
            color: #b30000
        }

        .admin-books-empty {
            display: grid;
            min-height: 300px;
            place-items: center;
            align-content: center;
            text-align: center
        }

        .admin-books-empty>span {
            display: grid;
            width: 58px;
            height: 58px;
            place-items: center;
            border-radius: 16px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.4rem
        }

        .admin-books-empty h3 {
            margin: 13px 0 4px;
            font-size: 1rem
        }

        .admin-books-empty p {
            margin: 0;
            color: #8c9097;
            font-size: .75rem
        }

        .admin-books-empty a {
            margin-top: 12px;
            color: #b30000;
            font-size: .73rem;
            font-weight: 700
        }

        .admin-books-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px 19px;
            border-top: 1px solid #eceef0
        }

        .admin-books-pagination>span {
            color: #8b8f96;
            font-size: .68rem
        }

        .admin-books-pagination nav {
            margin-left: auto
        }

        .admin-books-pagination .pagination {
            margin: 0
        }

        .admin-books-title-button {
            display: block;
            max-width: 200px;
            padding: 0;
            overflow: hidden;
            border: 0;
            background: none;
            color: #23252a;
            font-size: .77rem;
            font-weight: 700;
            text-align: left;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .admin-books-title-button:hover {
            color: #b30000
        }

        .admin-books-actions button {
            display: grid;
            width: 31px;
            height: 31px;
            place-items: center;
            padding: 0;
            border: 1px solid #e2e4e7;
            border-radius: 8px;
            background: #fff;
            color: #62666d
        }

        .admin-books-actions button:hover,
        .admin-books-actions button.primary {
            border-color: #b30000;
            background: #fff0f0;
            color: #b30000
        }

        .admin-book-modal .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 28px 80px rgba(15, 23, 42, .25)
        }

        .admin-book-modal .modal-header {
            align-items: flex-start;
            padding: 20px 24px;
            border-bottom: 1px solid #eceef0;
            background: #fff
        }

        .admin-book-modal-eyebrow {
            display: block;
            margin-bottom: 3px;
            color: #b30000;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .admin-book-modal .modal-title {
            font-size: 1.25rem
        }

        .admin-book-modal .modal-body {
            padding: 0;
            background: #f7f7f8
        }

        .admin-book-modal-layout {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            min-height: 570px
        }

        .admin-book-modal-aside {
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 13px;
            padding: 24px;
            border-right: 1px solid #e7e8eb;
            background: #fff
        }

        .admin-book-modal-cover {
            display: grid;
            width: 100%;
            height: 305px;
            place-items: center;
            overflow: hidden;
            border-radius: 13px;
            background: #f0f1f3
        }

        .admin-book-modal-cover img {
            width: 100%;
            height: 100%;
            padding: 8px;
            object-fit: contain
        }

        .admin-book-modal-price {
            font-size: 1.1rem
        }

        .admin-book-preview-btn {
            display: flex;
            width: 100%;
            min-height: 41px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            background: #1c1d20;
            color: #fff;
            font-size: .72rem;
            font-weight: 700
        }

        .admin-book-preview-btn:hover {
            background: #000;
            color: #fff
        }

        .admin-book-audio {
            width: 100%;
            padding: 12px;
            border-radius: 11px;
            background: #f4f4f5
        }

        .admin-book-audio span {
            display: block;
            margin-bottom: 8px;
            color: #4f5258;
            font-size: .66rem;
            font-weight: 700
        }

        .admin-book-audio audio {
            width: 100%;
            height: 36px
        }

        .admin-book-modal-content {
            display: grid;
            align-content: start;
            gap: 16px;
            padding: 24px
        }

        .admin-book-modal-section {
            padding: 20px;
            border: 1px solid #e5e6e9;
            border-radius: 14px;
            background: #fff
        }

        .admin-book-modal-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px
        }

        .admin-book-modal-section-title>span {
            display: grid;
            width: 35px;
            height: 35px;
            place-items: center;
            border-radius: 9px;
            background: #fff0f0;
            color: #b30000
        }

        .admin-book-modal-section-title small,
        .admin-book-modal-section-title h3 {
            display: block;
            margin: 0
        }

        .admin-book-modal-section-title small {
            color: #999ca3;
            font-size: .61rem;
            text-transform: uppercase
        }

        .admin-book-modal-section-title h3 {
            margin-top: 2px;
            font-size: .88rem
        }

        .admin-book-info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 11px
        }

        .admin-book-info-grid>div {
            padding: 11px;
            border-radius: 9px;
            background: #f7f7f8
        }

        .admin-book-info-grid small,
        .admin-book-info-grid strong,
        .admin-book-info-grid span {
            display: block
        }

        .admin-book-info-grid small {
            color: #9699a0;
            font-size: .59rem;
            text-transform: uppercase
        }

        .admin-book-info-grid strong {
            margin-top: 4px;
            color: #34363b;
            font-size: .72rem
        }

        .admin-book-info-grid span {
            margin-top: 2px;
            overflow: hidden;
            color: #8b8e95;
            font-size: .61rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .admin-book-short-description {
            margin: 0 0 12px;
            padding-left: 12px;
            border-left: 3px solid #b30000;
            color: #3d4045;
            font-size: .75rem;
            font-weight: 600
        }

        .admin-book-long-description {
            max-height: 130px;
            overflow: auto;
            color: #686b72;
            font-size: .72rem;
            line-height: 1.75;
            white-space: pre-line
        }

        .admin-book-revision-note {
            display: flex;
            gap: 11px;
            padding: 14px;
            border: 1px solid #ffd2d2;
            border-radius: 12px;
            background: #fff4f4;
            color: #9d0000
        }

        .admin-book-revision-note strong {
            font-size: .72rem
        }

        .admin-book-revision-note p {
            margin: 3px 0 0;
            font-size: .68rem
        }

        .admin-book-workflow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px 17px;
            border-radius: 12px;
            background: #1d1e21;
            color: #fff
        }

        .admin-book-workflow small,
        .admin-book-workflow strong {
            display: block
        }

        .admin-book-workflow small {
            color: #aeb1b7;
            font-size: .59rem;
            text-transform: uppercase
        }

        .admin-book-workflow strong {
            margin-top: 3px;
            font-size: .75rem
        }

        .admin-book-workflow button,
        .admin-book-editorial-actions button {
            padding: 9px 13px;
            border: 0;
            border-radius: 9px;
            background: #b30000;
            color: #fff;
            font-size: .69rem;
            font-weight: 700
        }

        .admin-book-editorial-actions {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 10px;
            padding: 15px;
            border-radius: 12px;
            background: #1d1e21
        }

        .admin-book-editorial-actions .publish {
            height: 100%;
            background: #138443
        }

        .admin-book-editorial-actions .revision-form {
            display: flex;
            gap: 8px
        }

        .admin-book-editorial-actions textarea {
            min-height: 54px;
            flex: 1;
            padding: 8px 10px;
            border: 1px solid #44474d;
            border-radius: 8px;
            background: #2a2c30;
            color: #fff;
            font-size: .68rem;
            resize: vertical
        }

        .admin-book-modal .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #eceef0
        }

        .admin-book-modal-close,
        .admin-book-modal-edit {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 9px;
            font-size: .7rem;
            font-weight: 700
        }

        .admin-book-modal-close {
            border: 1px solid #dedfe2;
            background: #fff;
            color: #666970
        }

        .admin-book-modal-edit {
            background: #b30000;
            color: #fff
        }

        .admin-book-modal-edit:hover {
            background: #8f0000;
            color: #fff
        }

        .admin-book-detail-overlay {
            z-index: 10050;
            padding: 24px
        }

        .admin-book-detail-container {
            display: flex;
            width: min(100%, 1120px);
            max-height: 94vh;
            flex-direction: column;
            border-radius: 18px
        }

        .admin-book-detail-header {
            flex-shrink: 0;
            padding: 17px 21px;
            background: #111827;
            color: #fff
        }

        .admin-book-detail-header>div {
            min-width: 0
        }

        .admin-book-detail-header .admin-book-modal-eyebrow {
            color: #fca5a5
        }

        .admin-book-detail-header h2 {
            max-width: 900px;
            margin: 0;
            overflow: hidden;
            color: #fff;
            font-size: 1rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .admin-book-detail-body {
            min-height: 0;
            flex: 1;
            padding: 0;
            background: #f7f7f8
        }

        .admin-book-detail-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 14px 20px;
            border-top: 1px solid #e7e8eb;
            background: #fff
        }

        /* Agrandissement global des polices de la page */
        .admin-books-eyebrow { font-size: .8rem }
        .admin-books-heading h2 { font-size: 1.75rem }
        .admin-books-heading p { font-size: .95rem }
        .admin-books-add { font-size: .88rem }
        .admin-books-stat small { font-size: .78rem }
        .admin-books-stat strong { font-size: 1.35rem }
        .admin-books-stat-icon { font-size: 1.15rem }
        .admin-books-search input,
        .admin-books-filter select { font-size: .88rem }
        .admin-books-filter-btn { font-size: .85rem }
        .admin-books-reset { font-size: .82rem }
        .admin-books-table-header strong { font-size: .92rem }
        .admin-books-table-header span { font-size: .78rem }
        .admin-books-table thead th { font-size: .74rem }
        .admin-books-table tbody td { font-size: .86rem }
        .admin-books-title-button { font-size: .9rem }
        .admin-books-title-cell small { font-size: .73rem }
        .admin-books-author strong,
        .admin-books-classification strong,
        .admin-books-date strong { font-size: .85rem }
        .admin-books-author small { font-size: .73rem }
        .admin-books-classification small,
        .admin-books-date small { font-size: .74rem }
        .admin-books-price { font-size: .86rem }
        .admin-books-status { font-size: .74rem }
        .admin-books-empty h3 { font-size: 1.12rem }
        .admin-books-empty p { font-size: .88rem }
        .admin-books-empty a { font-size: .85rem }
        .admin-books-pagination>span { font-size: .8rem }
        .admin-book-modal-eyebrow { font-size: .75rem }
        .admin-book-detail-header h2 { font-size: 1.15rem }
        .admin-book-preview-btn { font-size: .84rem }
        .admin-book-audio span { font-size: .78rem }
        .admin-book-modal-price { font-size: 1.25rem }
        .admin-book-modal-section-title small { font-size: .72rem }
        .admin-book-modal-section-title h3 { font-size: 1rem }
        .admin-book-info-grid small { font-size: .7rem }
        .admin-book-info-grid strong { font-size: .85rem }
        .admin-book-info-grid span { font-size: .73rem }
        .admin-book-short-description { font-size: .88rem }
        .admin-book-long-description { font-size: .85rem }
        .admin-book-revision-note strong { font-size: .85rem }
        .admin-book-revision-note p { font-size: .8rem }
        .admin-book-workflow small { font-size: .7rem }
        .admin-book-workflow strong { font-size: .88rem }
        .admin-book-workflow button { font-size: .8rem }
        .admin-book-modal-close { font-size: .82rem }

        @media(max-width:1100px) {
            .admin-books-stats {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:767px) {
            .admin-books-heading {
                align-items: flex-start;
                flex-direction: column
            }

            .admin-books-add {
                width: 100%;
                justify-content: center
            }

            .admin-books-stats {
                grid-template-columns: repeat(2, 1fr)
            }

            .admin-books-toolbar {
                align-items: stretch;
                flex-direction: column
            }

            .admin-books-search,
            .admin-books-filter {
                width: 100%;
                max-width: none
            }

            .admin-books-filter-btn {
                width: 100%
            }

            .admin-books-reset {
                justify-content: center
            }

            .admin-books-pagination {
                align-items: flex-start;
                flex-direction: column
            }

            .admin-books-pagination nav {
                margin-left: 0
            }

            .admin-book-modal-layout {
                grid-template-columns: 1fr
            }

            .admin-book-modal-aside {
                border-right: 0;
                border-bottom: 1px solid #e7e8eb
            }

            .admin-book-modal-cover {
                max-width: 250px
            }

            .admin-book-info-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .admin-book-editorial-actions {
                grid-template-columns: 1fr
            }

            .admin-book-editorial-actions .revision-form {
                flex-direction: column
            }
        }

        @media(max-width:420px) {
            .admin-books-stats {
                grid-template-columns: 1fr
            }

            .admin-book-info-grid {
                grid-template-columns: 1fr
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeBookOverlay = (overlay) => {
                if (!overlay) return;
                overlay.classList.add('d-none');
                overlay.setAttribute('aria-hidden', 'true');
                overlay.querySelectorAll('audio').forEach((audio) => audio.pause());

                if (!document.querySelector('.admin-book-detail-overlay:not(.d-none)')) {
                    document.body.style.overflow = '';
                }
            };

            document.querySelectorAll('.js-open-book-detail').forEach((button) => {
                button.addEventListener('click', () => {
                    const overlay = document.getElementById(button.dataset.overlayTarget);
                    if (!overlay) return;

                    overlay.classList.remove('d-none');
                    overlay.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    overlay.querySelector('.js-close-book-detail')?.focus();
                });
            });

            document.querySelectorAll('.admin-book-detail-overlay').forEach((overlay) => {
                overlay.querySelectorAll('.js-close-book-detail').forEach((button) => {
                    button.addEventListener('click', () => closeBookOverlay(overlay));
                });

                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) closeBookOverlay(overlay);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') return;
                closeBookOverlay(document.querySelector('.admin-book-detail-overlay:not(.d-none)'));
            });
        });
    </script>
@endpush