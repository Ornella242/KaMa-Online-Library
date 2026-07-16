@extends('layouts.writer')

@section('writer-content')

<div class="kama-writer-library">

    <div class="container-fluid">
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
                            {{ $books->total() }}
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
                            {{ $books->where('status','published')->count() }}
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
                            {{ $books->whereIn('status',['waiting_review','under_review'])->count() }}
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
                            {{ $books->where('status','draft')->count() }}
                        </strong>

                        <span>
                            Brouillons
                        </span>
                    </div>
                </div>
            </div>

			<div class="card-body">
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


            {{-- FILTERS --}}
            <form method="GET"
                  action="{{ route('writer.books') }}"
                  class="library-search-panel">

                <div class="search-input">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher un ouvrage..."
                    >

                </div>




                <select name="status">

                    <option value="">
                        Tous les statuts
                    </option>

                    <option value="published">
                        Publié
                    </option>

                    <option value="draft">
                        Brouillon
                    </option>

                    <option value="waiting_review">
                        En attente
                    </option>

                    <option value="under_review">
                        Vérification
                    </option>

                    <option value="revision_required">
                        Correction demandée
                    </option>

                </select>




                <select name="type">

                    <option value="">
                        Tous les formats
                    </option>

                    <option value="ebook">
                        Ebook
                    </option>

                    <option value="audio">
                        Audio
                    </option>

                </select>

                <button type="submit">
                    <i class="bi bi-funnel"></i>
                    Filtrer
                </button>
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

                                <div class="card-actions">
									{{-- VOIR --}}
									<a href="{{ route('writer.books.show',$book) }}"
									class="action-view">

										<i class="bi bi-eye"></i>

									</a>



									{{-- MODIFIER --}}
									@if($book->status != 'published')

										<a href="{{ route('writer.books.edit',$book) }}"
										class="action-edit">

											<i class="bi bi-pencil"></i>

											@if($book->status == 'revision_required')
												Corriger
											@else
												Modifier
											@endif

										</a>


										@if($book->status == 'revision_required')

											<form method="POST"
												action="{{ route('writer.books.resubmit',$book) }}"
												class="d-inline">

												@csrf

												<button type="submit"
														class="action-resubmit">
													<i class="bi bi-send-check"></i>
													Ressoumettre
												</button>
											</form>

										@endif


									@endif


									{{-- PAIEMENT DEPOT --}}
									@if(in_array($book->status,['draft','pending_payment']))
										<form method="POST"
											action="{{ route('books.payment.publication',$book) }}">
											@csrf
											<button class="action-pay">
												<i class="bi bi-credit-card"></i>
												Dépôt
											</button>
										</form>

									@endif

									{{-- ACTIONS APRES PUBLICATION --}}
									@if($book->status == 'published')

										<a href="{{ route('writer.books.boost',$book) }}"
										class="action-boost">
											<i class="bi bi-share-fill"></i>
											Booster
										</a>

										<a href="{{ route('writer.books.sponsor',$book) }}"
										class="action-sponsor">
											<i class="bi bi-megaphone-fill"></i>
											Sponsoriser
										</a>
									@endif


									{{-- SUPPRESSION BROUILLON --}}
									@if($book->status == 'draft')
										<form
											action="{{ route('writer.books.destroy',$book->id) }}"
											method="POST">
											@csrf
											@method('DELETE')

											<button type="button"
													class="action-delete delete-book-btn">
												<i class="bi bi-trash3"></i>
											</button>
										</form>
									@endif

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




@endsection