@extends('layouts.admin')

@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('admin-content')
    <div class="categories-page">
        <header class="categories-heading">
            <div>
                <span>Organisation du catalogue</span>
                <h2>Gestion des catégories</h2>
                <p>Structurez la bibliothèque en catégories et sous-catégories pour faciliter la découverte des livres.</p>
            </div>
            <button type="button" class="categories-add js-categories-open" data-overlay-target="createCategoryOverlay">
                <i class="bi bi-plus-lg"></i> Ajouter une catégorie
            </button>
        </header>

        <section class="categories-stats">
            <article>
                <span class="total"><i class="bi bi-tags-fill"></i></span>
                <div><small>Catégories</small><strong>{{ number_format($totalCategories) }}</strong></div>
            </article>
            <article>
                <span class="sub"><i class="bi bi-diagram-3-fill"></i></span>
                <div><small>Sous-catégories</small><strong>{{ number_format($totalSubcategories) }}</strong></div>
            </article>
            <article>
                <span class="books"><i class="bi bi-book-half"></i></span>
                <div><small>Livres classés</small><strong>{{ number_format($totalBooksClassified) }}</strong></div>
            </article>
            <article>
                <span class="empty"><i class="bi bi-inbox"></i></span>
                <div><small>Sans livre</small><strong>{{ number_format($emptyCategories) }}</strong></div>
            </article>
        </section>

        <div class="categories-panel">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="categories-toolbar">
                <div class="categories-search">
                    <i class="bi bi-search"></i>
                    <input type="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher une catégorie ou sous-catégorie…"
                           aria-label="Rechercher une catégorie">
                </div>
                <button type="submit" class="categories-filter-btn">Rechercher</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.categories.index') }}" class="categories-reset" title="Réinitialiser">
                        <i class="bi bi-x-lg"></i>
                        <span>Réinitialiser</span>
                    </a>
                @endif
            </form>

            <header class="categories-panel-header">
                <div>
                    <strong>{{ number_format($categories->total()) }} catégorie(s)</strong>
                    <span>
                        @if($categories->total())
                            Affichage de {{ $categories->firstItem() }} à {{ $categories->lastItem() }}
                        @else
                            Aucune catégorie trouvée
                        @endif
                    </span>
                </div>
            </header>

            <div class="table-responsive">
                <table class="table categories-table align-middle">
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th>Livres</th>
                            <th>Sous-catégories</th>
                            <th>Créée le</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="categories-name-cell">
                                        <span><i class="bi bi-tag-fill"></i></span>
                                        <div>
                                            <strong>{{ $category->name }}</strong>
                                            <small>{{ $category->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="categories-count-badge {{ $category->books_count ? 'filled' : '' }}">
                                        <i class="bi bi-book"></i>
                                        {{ number_format($category->books_count) }} livre(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="categories-sub-cell">
                                        @forelse($category->subcategories->take(4) as $subcategory)
                                            <span>{{ $subcategory->name }}</span>
                                        @empty
                                            <em>Aucune sous-catégorie</em>
                                        @endforelse
                                        @if($category->subcategories_count > 4)
                                            <span class="more">+{{ $category->subcategories_count - 4 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="categories-date">
                                        <strong>{{ $category->created_at?->format('d/m/Y') }}</strong>
                                        <small>{{ $category->created_at?->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="categories-row-actions">
                                        <button type="button"
                                                class="edit js-categories-open"
                                                data-overlay-target="editCategory-{{ $category->id }}"
                                                title="Modifier la catégorie">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </button>
                                        <button type="button"
                                                class="delete js-categories-open"
                                                data-overlay-target="deleteCategory-{{ $category->id }}"
                                                title="Supprimer la catégorie">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="categories-empty">
                                        <span><i class="bi bi-tags"></i></span>
                                        <h3>Aucune catégorie trouvée</h3>
                                        <p>Aucune catégorie ne correspond à votre recherche.</p>
                                        @if(request()->filled('search'))
                                            <a href="{{ route('admin.categories.index') }}">Réinitialiser la recherche</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <footer class="categories-pagination">
                    <span>Page {{ $categories->currentPage() }} sur {{ $categories->lastPage() }}</span>
                    {{ $categories->onEachSide(1)->links() }}
                </footer>
            @endif
        </div>

        {{-- Overlay : nouvelle catégorie --}}
        <div class="categories-overlay d-none"
             id="createCategoryOverlay"
             role="dialog"
             aria-modal="true"
             aria-hidden="true"
             aria-labelledby="createCategoryTitle">
            <div class="categories-overlay-container">
                <header class="categories-overlay-header">
                    <div>
                        <span>Organisation du catalogue</span>
                        <h2 id="createCategoryTitle">Nouvelle catégorie</h2>
                    </div>
                    <button type="button" class="js-categories-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                </header>
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="categories-form-body">
                        <div class="categories-form-intro">
                            <span><i class="bi bi-tags-fill"></i></span>
                            <div>
                                <small>Catégorie</small>
                                <strong>Ajoutez une catégorie et ses sous-catégories</strong>
                                <span>Elle sera immédiatement proposée aux auteurs lors de l’ajout d’un livre.</span>
                            </div>
                        </div>

                        <label for="createCategoryName">Nom de la catégorie <span>*</span></label>
                        <input type="text"
                               id="createCategoryName"
                               name="name"
                               value="{{ old('name') }}"
                               maxlength="255"
                               required
                               placeholder="Ex : Romance">

                        <label for="createCategorySubs">Sous-catégories</label>
                        <textarea id="createCategorySubs"
                                  name="subcategories[]"
                                  rows="4"
                                  placeholder="Ex : Thriller, Policier, Mystère">{{ old('subcategories.0') }}</textarea>
                        <small class="categories-form-help">Séparez les sous-catégories par des virgules. Vous pourrez les modifier à tout moment.</small>
                    </div>
                    <footer class="categories-overlay-footer">
                        <button type="button" class="secondary js-categories-close">Annuler</button>
                        <button type="submit" class="primary"><i class="bi bi-check2-circle"></i> Enregistrer la catégorie</button>
                    </footer>
                </form>
            </div>
        </div>

        @foreach($categories as $category)
            {{-- Overlay : modifier --}}
            <div class="categories-overlay d-none"
                 id="editCategory-{{ $category->id }}"
                 role="dialog"
                 aria-modal="true"
                 aria-hidden="true"
                 aria-labelledby="editCategoryTitle-{{ $category->id }}">
                <div class="categories-overlay-container">
                    <header class="categories-overlay-header dark">
                        <div>
                            <span>Catégorie #{{ str_pad((string) $category->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <h2 id="editCategoryTitle-{{ $category->id }}">Modifier « {{ $category->name }} »</h2>
                        </div>
                        <button type="button" class="js-categories-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        <div class="categories-form-body">
                            <div class="categories-form-intro">
                                <span><i class="bi bi-pencil-square"></i></span>
                                <div>
                                    <small>Catégorie concernée</small>
                                    <strong>{{ $category->name }}</strong>
                                    <span>{{ $category->books_count }} livre(s) · {{ $category->subcategories_count }} sous-catégorie(s)</span>
                                </div>
                            </div>

                            <label for="editCategoryName-{{ $category->id }}">Nom de la catégorie <span>*</span></label>
                            <input type="text"
                                   id="editCategoryName-{{ $category->id }}"
                                   name="name"
                                   value="{{ $category->name }}"
                                   maxlength="255"
                                   required>

                            <label for="editCategorySubs-{{ $category->id }}">Sous-catégories</label>
                            <textarea id="editCategorySubs-{{ $category->id }}"
                                      name="subcategories"
                                      rows="4"
                                      placeholder="Ex : Thriller, Policier, Mystère">{{ $category->subcategories->pluck('name')->implode(', ') }}</textarea>
                            <small class="categories-form-help">Séparez les sous-catégories par des virgules. La liste ci-dessus remplacera l’existante.</small>
                        </div>
                        <footer class="categories-overlay-footer">
                            <button type="button" class="secondary js-categories-close">Annuler</button>
                            <button type="submit" class="primary"><i class="bi bi-check2-circle"></i> Enregistrer les modifications</button>
                        </footer>
                    </form>
                </div>
            </div>

            {{-- Overlay : supprimer --}}
            <div class="categories-overlay d-none"
                 id="deleteCategory-{{ $category->id }}"
                 role="dialog"
                 aria-modal="true"
                 aria-hidden="true"
                 aria-labelledby="deleteCategoryTitle-{{ $category->id }}">
                <div class="categories-overlay-container">
                    <header class="categories-overlay-header danger">
                        <div>
                            <span>Action irréversible</span>
                            <h2 id="deleteCategoryTitle-{{ $category->id }}">Supprimer cette catégorie ?</h2>
                        </div>
                        <button type="button" class="js-categories-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>
                    <div class="categories-confirm-body">
                        <span class="danger"><i class="bi bi-trash3"></i></span>
                        <h3>{{ $category->name }}</h3>
                        <p>La catégorie et ses {{ $category->subcategories_count }} sous-catégorie(s) seront définitivement supprimées.</p>
                        @if($category->books_count)
                            <div class="warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Cette catégorie est utilisée par {{ $category->books_count }} livre(s) : la suppression sera refusée tant qu’ils ne seront pas reclassés.
                            </div>
                        @else
                            <div><i class="bi bi-info-circle"></i> Aucun livre n’utilise cette catégorie, la suppression est possible.</div>
                        @endif
                    </div>
                    <footer class="categories-overlay-footer">
                        <button type="button" class="secondary js-categories-close">Annuler</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger"><i class="bi bi-trash"></i> Confirmer la suppression</button>
                        </form>
                    </footer>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    @include('admin.partials.catalog-admin-styles')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openOverlays = () => document.querySelectorAll('.categories-overlay:not(.d-none)');

            const closeOverlay = (overlay) => {
                if (!overlay) return;
                overlay.classList.add('d-none');
                overlay.setAttribute('aria-hidden', 'true');
                if (!openOverlays().length) document.body.style.overflow = '';
            };

            const openOverlay = (id) => {
                const overlay = document.getElementById(id);
                if (!overlay) return;
                overlay.classList.remove('d-none');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                overlay.querySelector('input[type=text], textarea, .js-categories-close')?.focus();
            };

            document.querySelectorAll('.js-categories-open').forEach((button) => {
                button.addEventListener('click', () => openOverlay(button.dataset.overlayTarget));
            });

            document.querySelectorAll('.categories-overlay').forEach((overlay) => {
                overlay.querySelectorAll('.js-categories-close').forEach((button) => {
                    button.addEventListener('click', () => closeOverlay(overlay));
                });
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) closeOverlay(overlay);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeOverlay(document.querySelector('.categories-overlay:not(.d-none)'));
            });

            @if($errors->any() && old('name') !== null)
                openOverlay('createCategoryOverlay');
            @endif
        });
    </script>
@endpush
