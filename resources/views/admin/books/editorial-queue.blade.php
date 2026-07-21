@extends('layouts.admin')

@section('title', 'File éditoriale')
@section('page-title', 'File éditoriale')

@section('admin-content')
    <div class="editorial-page">
        <header class="editorial-heading">
            <div>
                <span>Contrôle éditorial</span>
                <h2>Livres en cours de vérification</h2>
                <p>Examinez chaque œuvre, puis publiez-la ou retournez-la à son auteur avec un motif précis.</p>
            </div>
            <a href="{{ route('admin.books.all', ['status' => 'waiting_review']) }}">
                <i class="bi bi-clock-history"></i> Voir les livres en attente
            </a>
        </header>

        <section class="editorial-stats">
            <article>
                <span class="waiting"><i class="bi bi-clock-history"></i></span>
                <div><small>En attente ce mois</small><strong>{{ number_format($waitingReviewBooks) }}</strong></div>
            </article>
            <article>
                <span class="review"><i class="bi bi-search"></i></span>
                <div><small>En vérification</small><strong>{{ number_format($reviewBooks) }}</strong></div>
            </article>
            <article>
                <span class="published"><i class="bi bi-check2-circle"></i></span>
                <div><small>Publiés ce mois</small><strong>{{ number_format($publishedBooks) }}</strong></div>
            </article>
            <article>
                <span class="revision"><i class="bi bi-arrow-counterclockwise"></i></span>
                <div><small>Retournés ce mois</small><strong>{{ number_format($rejectedBooks) }}</strong></div>
            </article>
        </section>

        <section class="editorial-panel">
            <header>
                <div>
                    <strong>{{ number_format($books->total()) }} livre(s) à examiner</strong>
                    <span>Seuls les livres dont la vérification a été démarrée apparaissent ici.</span>
                </div>
            </header>

            @if($books->isEmpty())
                <div class="editorial-empty">
                    <span><i class="bi bi-check-circle-fill"></i></span>
                    <h3>La file éditoriale est à jour</h3>
                    <p>Aucun livre n’est actuellement en cours de vérification.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table editorial-table align-middle">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Auteur</th>
                            <th>Classification</th>
                            <th>Format</th>
                            <th>Soumis le</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            @php
                                $authorName = trim(
                                    ($book->author?->firstname ?? '') . ' ' . ($book->author?->lastname ?? '')
                                ) ?: 'Auteur inconnu';
                            @endphp
                            <tr>
                                <td>
                                    <div class="editorial-book-cell">
                                        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/book/01.jpg') }}"
                                            alt="">
                                        <div>
                                            <button type="button" class="js-editorial-open"
                                                data-overlay-target="editorialDetail-{{ $book->id }}">
                                                {{ $book->title }}
                                            </button>
                                            <small>#{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="editorial-author-cell">
                                        <strong>{{ $authorName }}</strong>
                                        <small>{{ $book->author?->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="editorial-classification">
                                        <strong>{{ $book->category?->name ?? 'Sans catégorie' }}</strong>
                                        <small>{{ $book->subcategory?->name ?: 'Sans sous-catégorie' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="editorial-format">
                                        <i
                                            class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-text' }}"></i>
                                        {{ $book->type === 'audio' ? 'Audio' : 'Ebook' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="editorial-date">
                                        <strong>{{ $book->updated_at?->format('d/m/Y') }}</strong>
                                        <small>{{ $book->updated_at?->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="editorial-row-actions">
                                        <button type="button" class="view js-editorial-open"
                                            data-overlay-target="editorialDetail-{{ $book->id }}"
                                            title="Voir toutes les informations">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="publish js-editorial-open"
                                            data-overlay-target="publishBook-{{ $book->id }}">
                                            <i class="bi bi-check2-circle"></i> Publier
                                        </button>
                                        <button type="button" class="revision js-editorial-open"
                                            data-overlay-target="revisionBook-{{ $book->id }}">
                                            <i class="bi bi-arrow-counterclockwise"></i> À corriger
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($books->hasPages())
                <footer class="editorial-pagination">
                    <span>Page {{ $books->currentPage() }} sur {{ $books->lastPage() }}</span>
                    {{ $books->onEachSide(1)->links() }}
                </footer>
            @endif
        </section>

        @foreach($books as $book)
            @php
                $authorName = trim(
                    ($book->author?->firstname ?? '') . ' ' . ($book->author?->lastname ?? '')
                ) ?: 'Auteur inconnu';
            @endphp

            {{-- Complete details --}}
            <div class="editorial-overlay d-none" id="editorialDetail-{{ $book->id }}" role="dialog" aria-modal="true"
                aria-hidden="true" aria-labelledby="editorialDetailTitle-{{ $book->id }}">
                <div class="editorial-overlay-container editorial-detail-container">
                    <header class="editorial-overlay-header">
                        <div>
                            <span>Fiche éditoriale #{{ str_pad((string) $book->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <h2 id="editorialDetailTitle-{{ $book->id }}">{{ $book->title }}</h2>
                        </div>
                        <button type="button" class="js-editorial-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>

                    <div class="editorial-detail-body">
                        <aside>
                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/book/01.jpg') }}"
                                alt="Couverture de {{ $book->title }}">
                            <span class="editorial-review-status"><i class="bi bi-search"></i> En vérification</span>
                            <strong>{{ number_format($book->price, 2, ',', ' ') }} $</strong>

                            @if($book->type === 'ebook')
                                <a href="{{ route('admin.books.preview.file', $book) }}" target="_blank" rel="noopener">
                                    <i class="bi bi-file-earmark-pdf"></i> Ouvrir le document
                                </a>
                            @else
                                <div class="editorial-audio">
                                    <span><i class="bi bi-headphones"></i> Écouter le livre</span>
                                    <audio controls preload="none" src="{{ route('admin.books.audio', $book) }}"></audio>
                                </div>
                            @endif
                        </aside>

                        <div class="editorial-detail-content">
                            <section>
                                <div class="editorial-section-title">
                                    <span><i class="bi bi-info-lg"></i></span>
                                    <div><small>Présentation</small>
                                        <h3>Informations générales</h3>
                                    </div>
                                </div>
                                <div class="editorial-info-grid">
                                    <div>
                                        <small>Auteur</small><strong>{{ $authorName }}</strong><span>{{ $book->author?->email }}</span>
                                    </div>
                                    <div>
                                        <small>Catégorie</small><strong>{{ $book->category?->name ?? 'Non renseignée' }}</strong><span>{{ $book->subcategory?->name ?: '—' }}</span>
                                    </div>
                                    <div>
                                        <small>Format</small><strong>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</strong><span>{{ strtoupper($book->file_type ?: '—') }}</span>
                                    </div>
                                    <div><small>Langue</small><strong>{{ $book->language ?: 'Non renseignée' }}</strong><span>Année
                                            {{ $book->publication_year ?: '—' }}</span></div>
                                    <div>
                                        <small>{{ $book->type === 'audio' ? 'Durée' : 'Nombre de pages' }}</small><strong>{{ $book->type === 'audio' ? ($book->duration ?: '—') : ($book->pages ?: '—') }}</strong><span>{{ $book->type === 'audio' ? 'HH:MM:SS' : 'pages' }}</span>
                                    </div>
                                    <div><small>Fichier</small><strong
                                            title="{{ $book->original_file_name }}">{{ $book->original_file_name ?: 'Non renseigné' }}</strong><span>{{ $book->file_size ? number_format($book->file_size / 1048576, 2, ',', ' ') . ' Mo' : 'Taille inconnue' }}</span>
                                    </div>
                                    <div><small>Aperçu
                                            public</small><strong>{{ $book->preview_type === 'pages' ? 'Pages ' . $book->preview_start_page . ' à ' . $book->preview_end_page : 'Extrait texte' }}</strong><span>Visible
                                            par les lecteurs</span></div>
                                    <div><small>Droits
                                            d’auteur</small><strong>{{ $book->copyright_accepted ? 'Acceptés' : 'Non confirmés' }}</strong><span>{{ $book->copyright_accepted_at ? \Illuminate\Support\Carbon::parse($book->copyright_accepted_at)->format('d/m/Y') : '—' }}</span>
                                    </div>
                                    <div>
                                        <small>Soumission</small><strong>{{ $book->updated_at?->format('d/m/Y à H:i') }}</strong><span>{{ $book->updated_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <div class="editorial-section-title">
                                    <span><i class="bi bi-card-text"></i></span>
                                    <div><small>Contenu</small>
                                        <h3>Description du livre</h3>
                                    </div>
                                </div>
                                @if($book->short_description)
                                    <p class="editorial-short-description">{{ $book->short_description }}</p>
                                @endif
                                <div class="editorial-long-description">
                                    {{ trim(strip_tags($book->long_description ?? '')) ?: 'Aucune description détaillée disponible.' }}
                                </div>
                            </section>
                        </div>
                    </div>

                    <footer class="editorial-overlay-footer">
                        <button type="button" class="secondary js-editorial-close">Fermer</button>
                        <button type="button" class="danger js-editorial-switch"
                            data-overlay-target="revisionBook-{{ $book->id }}">
                            <i class="bi bi-arrow-counterclockwise"></i> Demander des corrections
                        </button>
                        <button type="button" class="success js-editorial-switch"
                            data-overlay-target="publishBook-{{ $book->id }}">
                            <i class="bi bi-check2-circle"></i> Publier
                        </button>
                    </footer>
                </div>
            </div>

            {{-- Publish confirmation --}}
            <div class="editorial-overlay d-none" id="publishBook-{{ $book->id }}" role="dialog" aria-modal="true"
                aria-hidden="true" aria-labelledby="publishTitle-{{ $book->id }}">
                <div class="editorial-overlay-container editorial-confirm-container">
                    <header class="editorial-overlay-header success">
                        <div>
                            <span>Validation finale</span>
                            <h2 id="publishTitle-{{ $book->id }}">Publier ce livre ?</h2>
                        </div>
                        <button type="button" class="js-editorial-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>
                    <div class="editorial-confirm-body">
                        <span class="success"><i class="bi bi-check2-circle"></i></span>
                        <h3>{{ $book->title }}</h3>
                        <p>Le livre sera immédiatement visible dans le catalogue KaMa et l’auteur recevra une notification.</p>
                        <div><i class="bi bi-info-circle"></i> Vérifiez le document, la couverture, les descriptions et les
                            informations commerciales avant de confirmer.</div>
                    </div>
                    <footer class="editorial-overlay-footer">
                        <button type="button" class="secondary js-editorial-close">Annuler</button>
                        <form method="POST" action="{{ route('admin.books.publish', $book) }}">
                            @csrf
                            <button type="submit" class="success js-editorial-submit"><i class="bi bi-check2-circle"></i> Confirmer la
                                publication</button>
                        </form>
                    </footer>
                </div>
            </div>

            {{-- Revision request --}}
            <div class="editorial-overlay d-none" id="revisionBook-{{ $book->id }}" role="dialog" aria-modal="true"
                aria-hidden="true" aria-labelledby="revisionTitle-{{ $book->id }}">
                <div class="editorial-overlay-container editorial-confirm-container">
                    <header class="editorial-overlay-header danger">
                        <div>
                            <span>Retour à l’auteur</span>
                            <h2 id="revisionTitle-{{ $book->id }}">Demander des corrections</h2>
                        </div>
                        <button type="button" class="js-editorial-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>
                    <form method="POST" action="{{ route('admin.books.reject', $book) }}">
                        @csrf
                        <div class="editorial-revision-body">
                            <div class="editorial-revision-book">
                                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/book/01.jpg') }}"
                                    alt="">
                                <div><small>Livre
                                        concerné</small><strong>{{ $book->title }}</strong><span>{{ $authorName }}</span></div>
                            </div>
                            <label for="reason-{{ $book->id }}">Motif et corrections attendues <span>*</span></label>
                            <textarea id="reason-{{ $book->id }}" name="reason" rows="7" minlength="10" maxlength="1000"
                                required
                                placeholder="Décrivez précisément les éléments que l’auteur doit corriger…">{{ old('reason') }}</textarea>
                            <small class="editorial-revision-help">Ce message sera visible par l’auteur dans son espace
                                personnel.</small>
                        </div>
                        <footer class="editorial-overlay-footer">
                            <button type="button" class="secondary js-editorial-close">Annuler</button>
                            <button type="submit" class="danger js-editorial-submit"><i class="bi bi-send"></i> Envoyer la demande</button>
                        </footer>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        .editorial-page {
            display: grid;
            gap: 22px
        }

        .editorial-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            /* annule le fond blanc appliqué globalement aux balises header par le thème */
            background: transparent;
            z-index: auto;
        }

        .editorial-heading>div>span {
            display: block;
            margin-bottom: 4px;
            color: #b30000;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .editorial-heading h2 {
            margin: 0;
            font-size: 1.55rem
        }

        .editorial-heading p {
            margin: 5px 0 0;
            color: #777b83;
            font-size: .82rem
        }

        .editorial-heading>a {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border: 1px solid #d5d7db;
            border-radius: 10px;
            background: transparent;
            color: #3f4248;
            font-size: .73rem;
            font-weight: 700
        }

        .editorial-heading>a:hover {
            border-color: #b30000;
            color: #b30000
        }

        .editorial-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 13px
        }

        .editorial-stats article {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border: 1px solid #e6e7ea;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .editorial-stats article>span {
            display: grid;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            place-items: center;
            border-radius: 12px;
            font-size: 1.08rem
        }

        .editorial-stats .waiting {
            background: #fff5d9;
            color: #9a6500
        }

        .editorial-stats .review {
            background: #e8f5ff;
            color: #157bb5
        }

        .editorial-stats .published {
            background: #eaf8ef;
            color: #138443
        }

        .editorial-stats .revision {
            background: #fff0f0;
            color: #b30000
        }

        .editorial-stats small,
        .editorial-stats strong {
            display: block
        }

        .editorial-stats small {
            color: #858991;
            font-size: .65rem;
            font-weight: 700
        }

        .editorial-stats strong {
            margin-top: 2px;
            font-size: 1.18rem
        }

        .editorial-panel {
            overflow: hidden;
            border: 1px solid #e5e7ea;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .editorial-panel>header {
            padding: 16px 19px 9px
        }

        .editorial-panel>header strong,
        .editorial-panel>header span {
            display: block
        }

        .editorial-panel>header strong {
            font-size: .8rem
        }

        .editorial-panel>header span {
            margin-top: 2px;
            color: #989ba2;
            font-size: .66rem
        }

        .editorial-table {
            min-width: 980px;
            margin: 0
        }

        .editorial-table thead th {
            padding: 11px 14px;
            border-color: #eceef0;
            color: #8e9299;
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap
        }

        .editorial-table tbody td {
            padding: 12px 14px;
            border-color: #eff0f2;
            color: #45484e;
            font-size: .73rem
        }

        .editorial-table tbody tr:hover {
            background: #fcfcfd
        }

        .editorial-book-cell {
            display: flex;
            min-width: 210px;
            align-items: center;
            gap: 11px
        }

        .editorial-book-cell img {
            width: 39px;
            height: 51px;
            flex: 0 0 39px;
            border-radius: 6px;
            background: #f0f1f2;
            object-fit: cover
        }

        .editorial-book-cell button {
            display: block;
            max-width: 195px;
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

        .editorial-book-cell button:hover {
            color: #b30000
        }

        .editorial-book-cell small,
        .editorial-author-cell small,
        .editorial-classification small,
        .editorial-date small {
            display: block;
            margin-top: 3px;
            color: #989ba2;
            font-size: .61rem
        }

        .editorial-author-cell,
        .editorial-classification,
        .editorial-date {
            min-width: 125px
        }

        .editorial-author-cell strong,
        .editorial-classification strong,
        .editorial-date strong {
            display: block;
            color: #3a3d42;
            font-size: .71rem
        }

        .editorial-format {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            border-radius: 999px;
            background: #f0f1f3;
            color: #4d5056;
            font-size: .62rem;
            font-weight: 700
        }

        .editorial-row-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px
        }

        .editorial-row-actions button {
            display: inline-flex;
            min-height: 32px;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 10px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: .65rem;
            font-weight: 700
        }

        .editorial-row-actions .view {
            width: 32px;
            padding: 0;
            border-color: #e2e4e7;
            background: #fff;
            color: #62666d
        }

        .editorial-row-actions .publish {
            background: #eaf8ef;
            color: #138443
        }

        .editorial-row-actions .revision {
            background: #fff0f0;
            color: #b30000
        }

        .editorial-empty {
            display: grid;
            width: 100%;
            min-height: 310px;
            place-items: center;
            align-content: center;
            justify-items: center;
            padding: 40px 24px;
            text-align: center
        }

        .editorial-empty>span {
            display: grid;
            width: 60px;
            height: 60px;
            place-items: center;
            border-radius: 17px;
            background: #eaf8ef;
            color: #138443;
            font-size: 1.5rem
        }

        .editorial-empty h3 {
            margin: 14px 0 4px;
            font-size: 1rem
        }

        .editorial-empty p {
            margin: 0;
            color: #8c9097;
            font-size: .75rem
        }

        .editorial-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 19px;
            border-top: 1px solid #eceef0
        }

        .editorial-pagination>span {
            color: #8b8f96;
            font-size: .68rem
        }

        .editorial-pagination .pagination {
            margin: 0
        }

        .editorial-overlay {
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

        .editorial-overlay-container {
            display: flex;
            width: min(100%, 1120px);
            max-height: 94vh;
            overflow: hidden;
            flex-direction: column;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .36)
        }

        .editorial-overlay-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 17px 21px;
            background: #111827;
            color: #fff
        }

        .editorial-overlay-header.success {
            background: #116b39
        }

        .editorial-overlay-header.danger {
            background: #8f0000
        }

        .editorial-overlay-header span {
            display: block;
            margin-bottom: 3px;
            color: #fca5a5;
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .editorial-overlay-header.success span {
            color: #bbf7d0
        }

        .editorial-overlay-header h2 {
            max-width: 900px;
            margin: 0;
            overflow: hidden;
            color: #fff;
            font-size: 1rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .editorial-overlay-header>button {
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

        .editorial-detail-body {
            display: grid;
            min-height: 0;
            overflow: auto;
            grid-template-columns: 250px minmax(0, 1fr);
            background: #f7f7f8
        }

        .editorial-detail-body>aside {
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 13px;
            padding: 24px;
            border-right: 1px solid #e7e8eb;
            background: #fff
        }

        .editorial-detail-body>aside>img {
            width: 100%;
            height: 305px;
            padding: 8px;
            border-radius: 13px;
            background: #f0f1f3;
            object-fit: contain
        }

        .editorial-detail-body>aside>strong {
            font-size: 1.1rem
        }

        .editorial-detail-body>aside>a {
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

        .editorial-review-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e8f5ff;
            color: #157bb5;
            font-size: .63rem;
            font-weight: 700
        }

        .editorial-audio {
            width: 100%;
            padding: 11px;
            border-radius: 11px;
            background: #f4f4f5
        }

        .editorial-audio span {
            display: block;
            margin-bottom: 7px;
            font-size: .64rem;
            font-weight: 700
        }

        .editorial-audio audio {
            width: 100%;
            height: 36px
        }

        .editorial-detail-content {
            display: grid;
            align-content: start;
            gap: 16px;
            padding: 24px
        }

        .editorial-detail-content>section {
            padding: 20px;
            border: 1px solid #e5e6e9;
            border-radius: 14px;
            background: #fff
        }

        .editorial-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px
        }

        .editorial-section-title>span {
            display: grid;
            width: 35px;
            height: 35px;
            place-items: center;
            border-radius: 9px;
            background: #fff0f0;
            color: #b30000
        }

        .editorial-section-title small,
        .editorial-section-title h3 {
            display: block;
            margin: 0
        }

        .editorial-section-title small {
            color: #999ca3;
            font-size: .61rem;
            text-transform: uppercase
        }

        .editorial-section-title h3 {
            margin-top: 2px;
            font-size: .88rem
        }

        .editorial-info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 11px
        }

        .editorial-info-grid>div {
            min-width: 0;
            padding: 11px;
            border-radius: 9px;
            background: #f7f7f8
        }

        .editorial-info-grid small,
        .editorial-info-grid strong,
        .editorial-info-grid span {
            display: block
        }

        .editorial-info-grid small {
            color: #9699a0;
            font-size: .59rem;
            text-transform: uppercase
        }

        .editorial-info-grid strong {
            margin-top: 4px;
            overflow: hidden;
            color: #34363b;
            font-size: .72rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .editorial-info-grid span {
            margin-top: 2px;
            overflow: hidden;
            color: #8b8e95;
            font-size: .61rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .editorial-short-description {
            margin: 0 0 12px;
            padding-left: 12px;
            border-left: 3px solid #b30000;
            color: #3d4045;
            font-size: .75rem;
            font-weight: 600
        }

        .editorial-long-description {
            max-height: 150px;
            overflow: auto;
            color: #686b72;
            font-size: .72rem;
            line-height: 1.75;
            white-space: pre-line
        }

        .editorial-overlay-footer {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 14px 20px;
            border-top: 1px solid #e7e8eb;
            background: #fff
        }

        .editorial-overlay-footer button {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 14px;
            border: 0;
            border-radius: 9px;
            font-size: .7rem;
            font-weight: 700
        }

        .editorial-overlay-footer .secondary {
            border: 1px solid #dedfe2;
            background: #fff;
            color: #666970
        }

        .editorial-overlay-footer .success {
            background: #138443;
            color: #fff
        }

        .editorial-overlay-footer .danger {
            background: #b30000;
            color: #fff
        }

        .editorial-overlay-footer button.is-loading {
            pointer-events: none;
            opacity: .88
        }

        .editorial-overlay-footer button.is-loading .spinner-border {
            width: .95rem;
            height: .95rem;
            border-width: .14em
        }

        .editorial-confirm-container {
            width: min(100%, 590px)
        }

        .editorial-confirm-body {
            padding: 29px;
            text-align: center
        }

        .editorial-confirm-body>span {
            display: grid;
            width: 65px;
            height: 65px;
            margin: 0 auto 14px;
            place-items: center;
            border-radius: 18px;
            font-size: 1.65rem
        }

        .editorial-confirm-body>span.success {
            background: #eaf8ef;
            color: #138443
        }

        .editorial-confirm-body h3 {
            margin: 0 0 7px;
            font-size: 1.05rem
        }

        .editorial-confirm-body p {
            max-width: 460px;
            margin: 0 auto;
            color: #71757d;
            font-size: .76rem;
            line-height: 1.6
        }

        .editorial-confirm-body>div {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 19px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f5f6f7;
            color: #666a71;
            font-size: .68rem;
            text-align: left
        }

        .editorial-revision-body {
            padding: 24px
        }

        .editorial-revision-book {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 11px;
            background: #f7f7f8
        }

        .editorial-revision-book img {
            width: 43px;
            height: 56px;
            border-radius: 6px;
            object-fit: cover
        }

        .editorial-revision-book small,
        .editorial-revision-book strong,
        .editorial-revision-book span {
            display: block
        }

        .editorial-revision-book small {
            color: #9699a0;
            font-size: .58rem;
            text-transform: uppercase
        }

        .editorial-revision-book strong {
            margin-top: 2px;
            font-size: .76rem
        }

        .editorial-revision-book span {
            margin-top: 2px;
            color: #878b92;
            font-size: .64rem
        }

        .editorial-revision-body label {
            display: block;
            margin-bottom: 7px;
            color: #36383d;
            font-size: .72rem;
            font-weight: 700
        }

        .editorial-revision-body label span {
            color: #b30000
        }

        .editorial-revision-body textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dfe1e5;
            border-radius: 11px;
            color: #36383d;
            font-size: .73rem;
            line-height: 1.55;
            outline: none;
            resize: vertical
        }

        .editorial-revision-body textarea:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .editorial-revision-help {
            display: block;
            margin-top: 7px;
            color: #92959c;
            font-size: .63rem
        }

        /* Agrandissement global des polices de la page */
        .editorial-heading>div>span { font-size: .8rem }
        .editorial-heading h2 { font-size: 1.75rem }
        .editorial-heading p { font-size: .95rem }
        .editorial-heading>a { font-size: .85rem }
        .editorial-stats small { font-size: .78rem }
        .editorial-stats strong { font-size: 1.4rem }
        .editorial-stats article>span { font-size: 1.25rem }
        .editorial-panel>header strong { font-size: .95rem }
        .editorial-panel>header span { font-size: .78rem }
        .editorial-table thead th { font-size: .74rem }
        .editorial-table tbody td { font-size: .86rem }
        .editorial-book-cell button { font-size: .9rem }
        .editorial-book-cell small,
        .editorial-author-cell small,
        .editorial-classification small,
        .editorial-date small { font-size: .73rem }
        .editorial-author-cell strong,
        .editorial-classification strong,
        .editorial-date strong { font-size: .85rem }
        .editorial-format { font-size: .74rem }
        .editorial-row-actions button { font-size: .78rem }
        .editorial-empty h3 { font-size: 1.12rem }
        .editorial-empty p { font-size: .88rem }
        .editorial-pagination>span { font-size: .8rem }
        .editorial-overlay-header span { font-size: .72rem }
        .editorial-overlay-header h2 { font-size: 1.15rem }
        .editorial-detail-body>aside>a { font-size: .84rem }
        .editorial-review-status { font-size: .74rem }
        .editorial-audio span { font-size: .76rem }
        .editorial-section-title small { font-size: .72rem }
        .editorial-section-title h3 { font-size: 1rem }
        .editorial-info-grid small { font-size: .7rem }
        .editorial-info-grid strong { font-size: .85rem }
        .editorial-info-grid span { font-size: .73rem }
        .editorial-short-description { font-size: .88rem }
        .editorial-long-description { font-size: .85rem }
        .editorial-overlay-footer button { font-size: .82rem }
        .editorial-confirm-body h3 { font-size: 1.2rem }
        .editorial-confirm-body p { font-size: .88rem }
        .editorial-confirm-body>div { font-size: .8rem }
        .editorial-revision-book small { font-size: .7rem }
        .editorial-revision-book strong { font-size: .88rem }
        .editorial-revision-book span { font-size: .76rem }
        .editorial-revision-body label { font-size: .84rem }
        .editorial-revision-body textarea { font-size: .88rem }
        .editorial-revision-help { font-size: .75rem }

        @media(max-width:1050px) {
            .editorial-stats {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:767px) {
            .editorial-heading {
                align-items: flex-start;
                flex-direction: column
            }

            .editorial-heading>a {
                width: 100%;
                justify-content: center
            }

            .editorial-detail-body {
                grid-template-columns: 1fr
            }

            .editorial-detail-body>aside {
                border-right: 0;
                border-bottom: 1px solid #e7e8eb
            }

            .editorial-detail-body>aside>img {
                max-width: 250px
            }

            .editorial-info-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .editorial-overlay-footer {
                flex-wrap: wrap
            }

            .editorial-overlay-footer button {
                flex: 1
            }

            .editorial-overlay {
                padding: 10px
            }
        }

        @media(max-width:520px) {
            .editorial-stats {
                grid-template-columns: 1fr
            }

            .editorial-info-grid {
                grid-template-columns: 1fr
            }

            .editorial-row-actions .publish,
            .editorial-row-actions .revision {
                font-size: 0
            }

            .editorial-row-actions .publish i,
            .editorial-row-actions .revision i {
                font-size: .8rem
            }

            .editorial-country-sales-head {
                display: none
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const overlays = () => document.querySelectorAll('.editorial-overlay:not(.d-none)');

            const closeOverlay = (overlay) => {
                if (!overlay) return;
                overlay.classList.add('d-none');
                overlay.setAttribute('aria-hidden', 'true');
                overlay.querySelectorAll('audio').forEach((audio) => audio.pause());
                if (!overlays().length) document.body.style.overflow = '';
            };

            const openOverlay = (id) => {
                const overlay = document.getElementById(id);
                if (!overlay) return;
                overlay.classList.remove('d-none');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                overlay.querySelector('.js-editorial-close, textarea, button')?.focus();
            };

            document.querySelectorAll('.js-editorial-open').forEach((button) => {
                button.addEventListener('click', () => openOverlay(button.dataset.overlayTarget));
            });

            document.querySelectorAll('.js-editorial-switch').forEach((button) => {
                button.addEventListener('click', () => {
                    closeOverlay(button.closest('.editorial-overlay'));
                    openOverlay(button.dataset.overlayTarget);
                });
            });

            document.querySelectorAll('.editorial-overlay').forEach((overlay) => {
                overlay.querySelectorAll('.js-editorial-close').forEach((button) => {
                    button.addEventListener('click', () => closeOverlay(overlay));
                });
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) closeOverlay(overlay);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeOverlay(document.querySelector('.editorial-overlay:not(.d-none)'));
            });

            const setSubmitLoading = (button, label) => {
                if (!button || button.dataset.loading === 'true') return;
                button.dataset.loading = 'true';
                button.dataset.originalHtml = button.innerHTML;
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');
                button.classList.add('is-loading');
                button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${label}`;
            };

            document.querySelectorAll('.editorial-overlay form').forEach((form) => {
                form.addEventListener('submit', () => {
                    const submitBtn = form.querySelector('.js-editorial-submit');
                    if (!submitBtn || submitBtn.disabled) return;
                    if (!form.checkValidity()) return;

                    const loadingLabel = submitBtn.classList.contains('danger')
                        ? 'Envoi en cours…'
                        : 'Publication en cours…';

                    setSubmitLoading(submitBtn, loadingLabel);

                    form.closest('.editorial-overlay')?.querySelectorAll('.js-editorial-close, .secondary').forEach((btn) => {
                        btn.disabled = true;
                    });
                });
            });
        });
    </script>
@endpush