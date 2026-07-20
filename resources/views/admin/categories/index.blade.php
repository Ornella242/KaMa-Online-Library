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

        <section class="categories-panel">
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
        </section>

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
    <style>
        .categories-page {
            display: grid;
            gap: 22px
        }

        .categories-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            background: transparent;
            z-index: auto;
        }

        .categories-heading>div>span {
            display: block;
            margin-bottom: 4px;
            color: #b30000;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .categories-heading h2 {
            margin: 0;
            font-size: 1.75rem
        }

        .categories-heading p {
            margin: 5px 0 0;
            color: #777b83;
            font-size: .95rem
        }

        .categories-add {
            display: inline-flex;
            min-height: 43px;
            flex-shrink: 0;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border: 0;
            border-radius: 10px;
            background: #b30000;
            color: #fff;
            font-size: .88rem;
            font-weight: 700
        }

        .categories-add:hover {
            background: #8f0000;
            color: #fff
        }

        .categories-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 13px
        }

        .categories-stats article {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border: 1px solid #e6e7ea;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .categories-stats article>span {
            display: grid;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            place-items: center;
            border-radius: 12px;
            font-size: 1.25rem
        }

        .categories-stats .total {
            background: #fff0f0;
            color: #b30000
        }

        .categories-stats .sub {
            background: #e8f5ff;
            color: #157bb5
        }

        .categories-stats .books {
            background: #eaf8ef;
            color: #138443
        }

        .categories-stats .empty {
            background: #f0f1f3;
            color: #4d5056
        }

        .categories-stats small,
        .categories-stats strong {
            display: block
        }

        .categories-stats small {
            color: #858991;
            font-size: .78rem;
            font-weight: 700
        }

        .categories-stats strong {
            margin-top: 2px;
            font-size: 1.4rem
        }

        .categories-panel {
            overflow: hidden;
            border: 1px solid #e5e7ea;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .categories-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 17px 19px;
            border-bottom: 1px solid #eceef0;
            background: #fafafa
        }

        .categories-search {
            position: relative;
            display: flex;
            min-width: 240px;
            max-width: 460px;
            flex: 1;
            align-items: center
        }

        .categories-search i {
            position: absolute;
            left: 13px;
            color: #8c9097;
            font-size: .95rem
        }

        .categories-search input {
            width: 100%;
            height: 42px;
            padding: 8px 13px 8px 38px;
            border: 1px solid #dfe1e5;
            border-radius: 10px;
            background: #fff;
            color: #33363b;
            font-size: .88rem;
            outline: none
        }

        .categories-search input:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .categories-filter-btn {
            height: 42px;
            padding: 8px 18px;
            border: 0;
            border-radius: 10px;
            background: #1c1d20;
            color: #fff;
            font-size: .85rem;
            font-weight: 700
        }

        .categories-reset {
            display: inline-flex;
            height: 42px;
            align-items: center;
            gap: 6px;
            padding: 8px 11px;
            border: 1px solid #e0e2e5;
            border-radius: 10px;
            color: #777b82;
            font-size: .82rem;
            font-weight: 700
        }

        .categories-panel-header {
            padding: 16px 19px 9px;
            background: transparent;
            z-index: auto;
        }

        .categories-panel-header strong,
        .categories-panel-header span {
            display: block
        }

        .categories-panel-header strong {
            font-size: .95rem
        }

        .categories-panel-header span {
            margin-top: 2px;
            color: #989ba2;
            font-size: .78rem
        }

        .categories-table {
            min-width: 900px;
            margin: 0
        }

        .categories-table thead th {
            padding: 11px 14px;
            border-color: #eceef0;
            color: #8e9299;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap
        }

        .categories-table tbody td {
            padding: 13px 14px;
            border-color: #eff0f2;
            color: #45484e;
            font-size: .86rem
        }

        .categories-table tbody tr:hover {
            background: #fcfcfd
        }

        .categories-name-cell {
            display: flex;
            min-width: 190px;
            align-items: center;
            gap: 11px
        }

        .categories-name-cell>span {
            display: grid;
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            place-items: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1rem
        }

        .categories-name-cell strong,
        .categories-name-cell small {
            display: block
        }

        .categories-name-cell strong {
            color: #23252a;
            font-size: .9rem
        }

        .categories-name-cell small {
            margin-top: 2px;
            color: #989ba2;
            font-size: .73rem
        }

        .categories-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f0f1f3;
            color: #4d5056;
            font-size: .76rem;
            font-weight: 700;
            white-space: nowrap
        }

        .categories-count-badge.filled {
            background: #fff0f0;
            color: #b30000
        }

        .categories-sub-cell {
            display: flex;
            max-width: 380px;
            flex-wrap: wrap;
            gap: 5px
        }

        .categories-sub-cell span {
            padding: 4px 9px;
            border: 1px solid #e6e7ea;
            border-radius: 999px;
            background: #fafafa;
            color: #55585e;
            font-size: .73rem;
            font-weight: 600
        }

        .categories-sub-cell span.more {
            border-color: #b30000;
            background: #fff0f0;
            color: #b30000
        }

        .categories-sub-cell em {
            color: #9a9da4;
            font-size: .78rem
        }

        .categories-date strong,
        .categories-date small {
            display: block
        }

        .categories-date strong {
            color: #3a3d42;
            font-size: .85rem
        }

        .categories-date small {
            margin-top: 3px;
            color: #989ba2;
            font-size: .73rem
        }

        .categories-row-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px
        }

        .categories-row-actions button {
            display: inline-flex;
            min-height: 33px;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 11px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 700
        }

        .categories-row-actions .edit {
            border-color: #e2e4e7;
            background: #fff;
            color: #3f4248
        }

        .categories-row-actions .edit:hover {
            border-color: #1c1d20;
            background: #1c1d20;
            color: #fff
        }

        .categories-row-actions .delete {
            width: 33px;
            padding: 0;
            background: #fff0f0;
            color: #b30000
        }

        .categories-row-actions .delete:hover {
            background: #b30000;
            color: #fff
        }

        .categories-empty {
            display: grid;
            min-height: 280px;
            place-items: center;
            align-content: center;
            text-align: center
        }

        .categories-empty>span {
            display: grid;
            width: 60px;
            height: 60px;
            place-items: center;
            border-radius: 17px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.5rem
        }

        .categories-empty h3 {
            margin: 14px 0 4px;
            font-size: 1.12rem
        }

        .categories-empty p {
            margin: 0;
            color: #8c9097;
            font-size: .88rem
        }

        .categories-empty a {
            margin-top: 12px;
            color: #b30000;
            font-size: .85rem;
            font-weight: 700
        }

        .categories-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 19px;
            border-top: 1px solid #eceef0;
            background: transparent;
            z-index: auto;
        }

        .categories-pagination>span {
            color: #8b8f96;
            font-size: .8rem
        }

        .categories-pagination .pagination {
            margin: 0
        }

        /* Overlays (même famille que la file éditoriale) */
        .categories-overlay {
            position: fixed;
            inset: 0;
            z-index: 10060;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(8, 10, 14, .72);
            backdrop-filter: blur(6px)
        }

        .categories-overlay-container {
            display: flex;
            width: min(100%, 590px);
            max-height: 94vh;
            overflow: hidden;
            flex-direction: column;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .36)
        }

        .categories-overlay-container>form {
            display: flex;
            min-height: 0;
            flex-direction: column
        }

        .categories-overlay-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 17px 21px;
            background: #8f0000;
            color: #fff;
            z-index: auto;
        }

        .categories-overlay-header.dark {
            background: #111827
        }

        .categories-overlay-header.danger {
            background: #8f0000
        }

        .categories-overlay-header span {
            display: block;
            margin-bottom: 3px;
            color: #fca5a5;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .categories-overlay-header h2 {
            max-width: 440px;
            margin: 0;
            overflow: hidden;
            color: #fff;
            font-size: 1.15rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .categories-overlay-header>button {
            display: grid;
            width: 35px;
            height: 35px;
            flex: 0 0 35px;
            place-items: center;
            border: 0;
            border-radius: 9px;
            background: rgba(255, 255, 255, .12);
            color: #fff
        }

        .categories-form-body {
            overflow: auto;
            padding: 24px
        }

        .categories-form-intro {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 11px;
            background: #f7f7f8
        }

        .categories-form-intro>span {
            display: grid;
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            place-items: center;
            border-radius: 11px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.1rem
        }

        .categories-form-intro small,
        .categories-form-intro strong,
        .categories-form-intro>div>span {
            display: block
        }

        .categories-form-intro small {
            color: #9699a0;
            font-size: .7rem;
            text-transform: uppercase
        }

        .categories-form-intro strong {
            margin-top: 2px;
            font-size: .88rem
        }

        .categories-form-intro>div>span {
            margin-top: 2px;
            color: #878b92;
            font-size: .76rem
        }

        .categories-form-body label {
            display: block;
            margin-bottom: 7px;
            color: #36383d;
            font-size: .84rem;
            font-weight: 700
        }

        .categories-form-body label span {
            color: #b30000
        }

        .categories-form-body input[type=text],
        .categories-form-body textarea {
            width: 100%;
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #dfe1e5;
            border-radius: 11px;
            color: #36383d;
            font-size: .88rem;
            line-height: 1.55;
            outline: none
        }

        .categories-form-body textarea {
            margin-bottom: 0;
            resize: vertical
        }

        .categories-form-body input[type=text]:focus,
        .categories-form-body textarea:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .categories-form-help {
            display: block;
            margin-top: 7px;
            color: #92959c;
            font-size: .75rem
        }

        .categories-confirm-body {
            overflow: auto;
            padding: 29px;
            text-align: center
        }

        .categories-confirm-body>span {
            display: grid;
            width: 65px;
            height: 65px;
            margin: 0 auto 14px;
            place-items: center;
            border-radius: 18px;
            font-size: 1.65rem
        }

        .categories-confirm-body>span.danger {
            background: #fff0f0;
            color: #b30000
        }

        .categories-confirm-body h3 {
            margin: 0 0 7px;
            font-size: 1.2rem
        }

        .categories-confirm-body p {
            max-width: 440px;
            margin: 0 auto;
            color: #71757d;
            font-size: .88rem;
            line-height: 1.6
        }

        .categories-confirm-body>div {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 19px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f5f6f7;
            color: #666a71;
            font-size: .8rem;
            text-align: left
        }

        .categories-confirm-body>div.warning {
            background: #fff8ec;
            color: #8a6512
        }

        .categories-overlay-footer {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 14px 20px;
            border-top: 1px solid #e7e8eb;
            background: #fff
        }

        .categories-overlay-footer button {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 14px;
            border: 0;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700
        }

        .categories-overlay-footer .secondary {
            border: 1px solid #dedfe2;
            background: #fff;
            color: #666970
        }

        .categories-overlay-footer .primary {
            background: #b30000;
            color: #fff
        }

        .categories-overlay-footer .primary:hover {
            background: #8f0000
        }

        .categories-overlay-footer .danger {
            background: #b30000;
            color: #fff
        }

        @media(max-width:1050px) {
            .categories-stats {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:767px) {
            .categories-heading {
                align-items: flex-start;
                flex-direction: column
            }

            .categories-add {
                width: 100%;
                justify-content: center
            }

            .categories-toolbar {
                align-items: stretch;
                flex-direction: column
            }

            .categories-search {
                width: 100%;
                max-width: none
            }

            .categories-filter-btn {
                width: 100%
            }

            .categories-reset {
                justify-content: center
            }

            .categories-overlay {
                padding: 10px
            }

            .categories-overlay-footer {
                flex-wrap: wrap
            }

            .categories-overlay-footer button {
                flex: 1
            }

            .categories-overlay-footer form {
                flex: 1;
                display: flex
            }

            .categories-overlay-footer form button {
                width: 100%
            }

            .categories-pagination {
                align-items: flex-start;
                flex-direction: column
            }
        }

        @media(max-width:520px) {
            .categories-stats {
                grid-template-columns: 1fr
            }
        }
    </style>
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
