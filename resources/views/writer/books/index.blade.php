@extends('layouts.writer')

@section('writer-content')

<div class="kama-writer-library">

    <div class="container">
        {{-- MAIN WRAPPER --}}
        <div class="library-wrapper">

            {{-- HEADER --}}
            <div class="library-hero">
                <div>
                    <span class="library-label">
                        ESPACE AUTEUR
                    </span>

                    <h1>
                        Ma bibliothèque
                    </h1>

                    <p>
                        Gérez vos ebooks et livres audio, suivez leur validation éditoriale et développez votre catalogue.
                    </p>

                </div>



                <a href="{{ route('writer.books.create') }}"
                   class="add-book-btn">

                    <i class="bi bi-plus-lg me-2"></i>

                    Ajouter un livre

                </a>


            </div>


            {{-- STATS --}}
            <div class="library-stat-grid">
                <div class="writer-stat red">
                    <i class="bi bi-book"></i>
                    <div>
                        <strong>
                            {{ $stats['total'] }}
                        </strong>

                        <span>
                            Tous les livres
                        </span>
                    </div>
                </div>

                <div class="writer-stat black">
                    <i class="bi bi-check-circle"></i>
                    <div>
                        <strong>
                            {{ $stats['published'] }}
                        </strong>

                        <span>
                            Publiés
                        </span>
                    </div>
                </div>


                <div class="writer-stat dark">
                    <i class="bi bi-hourglass-split"></i>
                    <div>
                        <strong>
                            {{ $stats['validation'] }}
                        </strong>

                        <span>
                            En validation
                        </span>
                    </div>
                </div>

                <div class="writer-stat gray">
                    <i class="bi bi-pencil-square"></i>
                    <div>
                        <strong>
                            {{ $stats['drafts'] + $stats['revisions'] }}
                        </strong>

                        <span>
                            À finaliser
                        </span>
                    </div>
                </div>
            </div>

            <div class="library-publication-flow">
                <div>
                    <span><i class="bi bi-pencil-square"></i></span>
                    <div><strong>1. Brouillon</strong><small>Votre livre reste modifiable.</small></div>
                </div>
                <i class="bi bi-chevron-right"></i>
                <div>
                    <span><i class="bi bi-credit-card"></i></span>
                    <div><strong>2. Paiement</strong><small>Le tarif dépend du format.</small></div>
                </div>
                <i class="bi bi-chevron-right"></i>
                <div>
                    <span><i class="bi bi-hourglass-split"></i></span>
                    <div><strong>3. En attente</strong><small>L’équipe éditoriale vérifie le livre.</small></div>
                </div>
                <i class="bi bi-chevron-right"></i>
                <div>
                    <span><i class="bi bi-check-circle"></i></span>
                    <div><strong>4. Publié</strong><small>Le livre devient disponible.</small></div>
                </div>
            </div>

			@if(session('success') || $errors->any() || session('error'))
			<div class="library-feedback">
				@if(session('success'))
					<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
						<i class="bi bi-check-circle-fill me-2"></i>
						{{ session('success') }}

								<button type="button"
										class="btn-close"
										data-bs-dismiss="alert"
										aria-label="Close">
								</button>
							</div>
				@endif
				@if($errors->any())
					<div class="alert alert-danger mb-4">
						<strong>Veuillez corriger les erreurs suivantes :</strong>

						<ul class="mb-0 mt-2">
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

						<i class="bi bi-exclamation-triangle-fill me-2"></i>

						{{ session('error') }}

						<button type="button"
								class="btn-close"
								data-bs-dismiss="alert"
								aria-label="Close">
						</button>
					</div>
				@endif
			</div>
			@endif


            {{-- FILTERS --}}
            <form method="GET"
                  action="{{ route('writer.books') }}"
                  class="library-search-panel">

                <div class="search-input">
                    <i class="bi bi-search"></i>
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher un ouvrage..."
                    >

                </div>




                <select name="status">

                    <option value="">
                        Tous les statuts
                    </option>

                    <option value="published" @selected(request('status') === 'published')>
                        Publié
                    </option>

                    <option value="draft" @selected(request('status') === 'draft')>
                        Brouillon
                    </option>

                    <option value="waiting_review" @selected(request('status') === 'waiting_review')>
                        En attente
                    </option>

                    <option value="under_review" @selected(request('status') === 'under_review')>
                        Vérification
                    </option>

                    <option value="revision_required" @selected(request('status') === 'revision_required')>
                        Correction demandée
                    </option>

                </select>




                <select name="type">

                    <option value="">
                        Tous les formats
                    </option>

                    <option value="ebook" @selected(request('type') === 'ebook')>
                        Ebook
                    </option>

                    <option value="audio" @selected(request('type') === 'audio')>
                        Audio
                    </option>

                </select>

                <select name="sort" aria-label="Trier les livres">
                    <option value="">Plus récents</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Plus anciens</option>
                    <option value="price_high" @selected(request('sort') === 'price_high')>Prix décroissant</option>
                    <option value="price_low" @selected(request('sort') === 'price_low')>Prix croissant</option>
                </select>

                <button type="submit">
                    <i class="bi bi-funnel"></i>
                    Filtrer
                </button>

                @if(request()->filled('search') || request()->filled('status') || request()->filled('type') || request()->filled('sort'))
                    <a href="{{ route('writer.books') }}"
                       class="library-filter-reset"
                       title="Réinitialiser les filtres"
                       aria-label="Réinitialiser les filtres">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            {{-- BOOKS --}}
            <div class="writer-books-slider">
                <div class="writer-books-grid my-slider">
                    @forelse($books as $book)
                        <div class="writer-library-card">
                            <div class="book-cover-area">
                                <img
                                    src="{{ asset('storage/'.$book->cover_image) }}"
                                    alt="{{ $book->title }}"
                                >
                                <span>
                                    @if($book->type == 'ebook')
                                        <i class="bi bi-book"></i>
                                        Ebook
                                    @else

                                        <i class="bi bi-headphones"></i>
                                        Audio

                                    @endif

                                </span>
                            </div>



                            <div class="book-card-body">
                                <div class="book-status">
                                    @if($book->status == 'published')

                                        <span class="published">
                                            Publié
                                        </span>


                                    @elseif($book->status == 'waiting_review')

                                        <span class="waiting">
                                            En attente
                                        </span>


                                    @elseif($book->status == 'under_review')

                                        <span class="review">
                                            Vérification
                                        </span>


                                    @elseif($book->status == 'revision_required')

                                        <span class="revision">
                                            Correction
                                        </span>


                                    @elseif($book->status == 'rejected')

                                        <span class="revision">
                                            Rejeté
                                        </span>


                                    @else

                                        <span class="draft">
                                            Brouillon
                                        </span>
                                    @endif
                                </div>


                                <h3>
                                    {{ $book->title }}
                                </h3>

                                @if($book->status == 'revision_required' || $book->status == 'rejected')


                                    <div class="revision-message">

                                        <strong>

                                            <i class="bi bi-exclamation-triangle"></i>

                                            Modifications demandées

                                        </strong>


                                        <p>

                                            {{ $book->rejection_reason }}

                                        </p>


                                    </div>


                                @endif

                                <div class="book-card-actions">
                                    @if($book->status === 'draft')
                                        @php($publicationFee = $publicationFees->get($book->type))
                                        @if(optional($book->publicationPayment)->status === 'pending')
                                            <a href="{{ route('writer.books.deposit', $book) }}"
                                               class="book-payment-state pending">
                                                <span><i class="bi bi-clock-history"></i></span>
                                                <div><strong>Paiement non finalisé</strong><small>Reprendre le paiement</small></div>
                                                <i class="bi bi-chevron-right ms-auto"></i>
                                            </a>

                                        @elseif(!$paymentRequired)
                                            <form action="{{ route('writer.books.submit', $book) }}" method="POST">
                                                @csrf

                                                <button type="submit" class="book-payment-action">
                                                    <span>
                                                        <i class="bi bi-send-check"></i>
                                                        Soumettre à la vérification
                                                    </span>
                                                </button>
                                            </form>
                                        @elseif($publicationFee)

                                            <a href="{{ route('writer.books.deposit', $book) }}"
                                            class="book-payment-action">

                                                <span>
                                                    <i class="bi bi-shield-lock"></i>
                                                    Régler les frais
                                                </span>

                                                <strong>
                                                    ${{ number_format($publicationFee->amount, 2, '.', ',') }}
                                                </strong>

                                            </a>
                                        @else
                                            <div class="book-payment-state unavailable">
                                                <span><i class="bi bi-exclamation-circle"></i></span>
                                                <div><strong>Paiement indisponible</strong><small>Tarif non configuré</small></div>
                                            </div>
                                        @endif
                                    @elseif($book->status === 'revision_required')
                                        <a href="{{ route('writer.books.edit', $book) }}"
                                           class="book-resubmit-action">
                                            <i class="bi bi-pencil-square"></i> Corriger et renvoyer
                                        </a>
                                    @endif

                                    <div class="book-card-utility-actions">
                                        <a href="{{ route('writer.books.show', $book) }}">
                                            <i class="bi bi-eye"></i><span>Détails</span>
                                        </a>

                                        @if(in_array($book->status, ['draft', 'waiting_review', 'under_review'], true))
                                            <a href="{{ route('writer.books.edit', $book) }}">
                                                <i class="bi bi-pencil"></i>
                                                <span>Modifier</span>
                                            </a>
                                        @endif

                                        @if($book->status === 'published')
                                            <a href="{{ route('writer.books.boost', $book) }}">
                                                <i class="bi bi-share"></i><span>Booster</span>
                                            </a>
                                            <a href="{{ route('writer.books.sponsor', $book) }}">
                                                <i class="bi bi-megaphone"></i><span>Sponsoriser</span>
                                            </a>
                                        @endif

                                        @if($book->status === 'draft')
                                            <form action="{{ route('writer.books.destroy', $book) }}"
                                                  method="POST"
                                                  class="delete-book-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="delete-book-btn danger"
                                                        title="Supprimer le brouillon"
                                                        aria-label="Supprimer le brouillon">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-library">
                            <i class="bi bi-book"></i>
                            <h3>
                                Aucun livre trouvé
                            </h3>

                            <p>
                                Commencez à publier votre premier ouvrage.
                            </p>

                        </div>

                    @endforelse
                </div>
            </div>

            <div class="kama-pagination">
                {{ $books->links() }}
            </div>

        </div>
    </div>

</div>

@if(session('book_created'))
	@php($bookCreated = session('book_created'))
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'success',
				title: 'Livre ajouté avec succès',
				html: `
					<p class="kama-book-toast-message">${@json($bookCreated['message'])}</p>
					<a class="kama-book-toast-action"
					   href="${@json(route('writer.books.deposit', $bookCreated['book_id']))}">
						<i class="bi bi-credit-card me-2"></i>
						Payer les frais
					</a>
				`,
				showConfirmButton: false,
				showCloseButton: true,
				timer: 10000,
				timerProgressBar: true,
				customClass: {
					popup: 'kama-book-toast'
				}
			});
		});
	</script>
@endif

@endsection