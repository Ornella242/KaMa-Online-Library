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

        <div class="admin-books-panel">
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
        </div>

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

@include('admin.partials.admin-books-styles')

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
