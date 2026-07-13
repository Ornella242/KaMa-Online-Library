@extends('layouts.writer')

@section('writer-content')

<section class="pt-0">
	<div class="container vstack gap-4">
		<div class="row">
			<div class="col-12">
				{{-- ALERT SUCCESS --}}
				@if(session('success'))

					<div class="alert alert-success alert-dismissible fade show mb-4">

						<i class="bi bi-check-circle-fill me-2"></i>

						{{ session('success') }}


						<button 
							type="button"
							class="btn-close"
							data-bs-dismiss="alert">
						</button>

					</div>

				@endif
				{{-- ERRORS --}}
				@if($errors->any())

					<div class="alert alert-danger mb-4">

						<strong>
							Veuillez corriger les erreurs suivantes :
						</strong>


						<ul class="mb-0 mt-2">

							@foreach($errors->all() as $error)

								<li>
									{{ $error }}
								</li>

							@endforeach

						</ul>

					</div>

				@endif

				<div class="library-card">

					{{-- HEADER --}}

					<div class="library-header">
						<div>
							<h3 class="text-white">
								Ma bibliothèque
							</h3>
						</div>

						<a href="{{ route('writer.books.create') }}"
						class="library-add-btn">
							<i class="bi bi-plus-lg me-2"></i>
							Ajouter un livre
						</a>
					</div>

					{{-- STATS --}}
					<div class="library-stats">
						<div class="library-stat active">
							<i class="bi bi-book"></i>
							<div>
								<strong>
									{{ $books->total() }}
								</strong>

								<span>
									Tous
								</span>

							</div>

						</div>

						<div class="library-stat">

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


						<div class="library-stat">
							<i class="bi bi-pencil"></i>
							<div>
								<strong>
									{{ $books->where('status','draft')->count() }}
								</strong>

								<span>
									Brouillons
								</span>
							</div>
						</div>

						<div class="library-stat">
							<i class="bi bi-credit-card"></i>
							<div>
								<strong>
									{{ $books->where('status','pending_payment')->count() }}
								</strong>

								<span>
									Paiement
								</span>

							</div>
						</div>

					</div>

					{{-- FILTERS --}}
					<form method="GET"
						action="{{ route('writer.books') }}"
						class="library-filter">
						<div class="search-box">
							<i class="bi bi-search"></i>
							<input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un livre...">
						</div>

						<select name="status" class="form-select">
							<option value="">
								Tous les statuts
							</option>


							<option value="published">
								Publiés
							</option>

							<option value="draft">
								Brouillons
							</option>

							<option value="pending_payment">
								Paiement en attente
							</option>

							<option value="unpublished">
								Non publiés
							</option>
						</select>

						<select name="type"
								class="form-select">
							<option value="">
								Tous les types
							</option>
							<option value="ebook">
								Ebook
							</option>
							<option value="audio">
								Audio
							</option>
						</select>

						<select name="sort"
								class="form-select">
							<option value="">
								Plus récent
							</option>
							<option value="oldest">
								Plus ancien
							</option>
							<option value="price_high">
								Prix élevé
							</option>
							<option value="price_low">
								Prix bas
							</option>
						</select>

						<button class="filter-btn">
							<i class="bi bi-funnel"></i>
						</button>
					</form>


					{{-- BOOKS --}}
						<div class="books-list">

							@forelse($books as $book)
								<div class="book-item">
									<div class="book-cover">
										<img 
										src="{{ asset('storage/'.$book->cover_image) }}"
										alt="{{ $book->title }}">

										<span class="book-type">
											@if($book->type == 'ebook')
												<i class="bi bi-file-earmark-text"></i>
												Ebook
											@else
												<i class="bi bi-headphones"></i>
												Audio
											@endif

										</span>
									</div>

									<div class="book-content">
										<div class="book-top">
											<div>
												<h5>
													{{ $book->title }}
												</h5>

												<small>
													<i class="bi bi-person"></i>
													{{ Auth::user()->firstname }}
												</small>
											</div>


											@if($book->status == 'published')
												<span class="status published">
													Publié
												</span>

											@elseif($book->status == 'pending_payment')
												<span class="status pending">
													Paiement en attente
												</span>

											@elseif($book->status == 'draft')
												<span class="status draft">
													Brouillon - Paiement de depot requis 
												</span>
											@elseif ($book->status == 'under_review')
												<span class="status under_review">
													Sous vérification
												</span>
											@else
												<span class="status unpublished">
													Non publié
												</span>
											@endif
										</div>


										<div class="book-details">
											<span>
												<i class="bi bi-bookmark"></i>
												{{ $book->category->name }}
											</span>

											<span>
												<i class="bi bi-tag"></i>
												{{ $book->subcategory->name }}
											</span>
											@if($book->type == 'ebook')
												<span>
													{{ $book->pages }} pages
												</span>
											@else
												<span>
													{{ $book->duration }}
												</span>
											@endif
											<span>
												{{ $book->price }} $
											</span>
										</div>

										<div class="book-actions">
											<a href="{{ route('writer.books.show',$book->id) }}"
											class="btn-view">
												<i class="bi bi-eye"></i>
												Voir
											</a>

											@if($book->status != 'published')
												<a href="{{ route('writer.books.edit',$book) }}"
												class="btn-edit">
													<i class="bi bi-pencil"></i>
													Modifier
												</a>
											@endif

											@if(in_array($book->status,['draft','pending_payment']))
												<a href="{{ route('writer.books.deposit',$book->id) }}"
												class="btn-pay">
													<i class="bi bi-credit-card"></i>
													Payer dépôt
												</a>
											@endif

											<a href="{{ route('writer.books.boost',$book) }}"
												class="btn-boost">
												<i class="bi bi-share-fill"></i>
												Booster
											</a>

											<a href="{{ route('writer.books.sponsor',$book) }}"
												class="btn btn-warning">

													<i class="bi bi-megaphone-fill"></i>
													Sponsoriser sur KaMa

											</a>


											<!-- DELETE -->
												<form 
													action="{{ route('writer.books.destroy', $book->id) }}"
													method="POST"
													class="delete-book-form d-inline">

													@csrf
													@method('DELETE')

													<button 
														type="button"
														class="btn btn-sm btn-danger delete-book-btn">

														<i class="bi bi-trash3 me-1"></i>
														Supprimer

													</button>

												</form>
										</div>
									</div>
								</div>
							@empty
								<div class="empty-books">
									<i class="bi bi-exclamation-circle"></i>
									<h5>
										Aucun livre trouvé
									</h5>

								</div>
							@endforelse
						</div>
					<div class="mt-4">
						<div class="kama-pagination">
							{{ $books->links() }}
						</div>
							
					</div>
				</div>
			</div>
		</div>
		<!-- Listing table END -->
	</div>
</section>

@endsection