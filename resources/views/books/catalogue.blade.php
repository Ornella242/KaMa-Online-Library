@extends('layouts.app')

@section('content')

<!-- =======================
CATALOG HERO START
======================= -->
<section class="catalog-hero">

    <div class="catalog-container">

        <!-- LEFT -->
        <div class="catalog-text">

            <h1>Catalogue des livres</h1>

            <p>
                Explorez notre collection complète de livres, ebooks et audios.
                Trouvez facilement ce que vous cherchez.
            </p>

            <!-- SEARCH BAR -->
            <form class="catalog-search" method="GET" action="{{ route('catalogue') }}">

					<input 
						type="text"
						name="search"
						value="{{ request('search') }}"
						placeholder="Rechercher un livre, auteur, catégorie..."
					>

					<button type="submit">
						<i class="bi bi-search"></i>
					</button>

			</form>

            <!-- QUICK FILTERS -->
            {{-- <div class="catalog-filters">
				<a href="{{ route('catalogue') }}"
				class="filter {{ !request('category') ? 'active' : '' }}">
					Tous
				</a>
				@foreach($categories as $category)

					<a href="{{ route('catalogue',['category'=>$category->id]) }}"
					class="filter {{ request('category') == $category->id ? 'active' : '' }}">

						{{ $category->name }}

					</a>
				@endforeach
			</div> --}}

			<div class="catalog-filters">

				<a href="{{ route('catalogue') }}"
				class="filter {{ !request('category') && !request('subcategory') ? 'active' : '' }}">
					Tous
				</a>


				@foreach($categories as $category)

					<div class="filter-dropdown">

						<a href="{{ route('catalogue',['category'=>$category->id]) }}"
						class="filter {{ request('category') == $category->id ? 'active' : '' }}">

							{{ $category->name }}

							@if($category->subcategories->count())
								<i class="bi bi-chevron-down small"></i>
							@endif

						</a>


						@if($category->subcategories->count())

							<div class="subcategory-menu">

								@foreach($category->subcategories as $subcategory)

									<a href="{{ route('catalogue',['subcategory'=>$subcategory->id]) }}"
									class="{{ request('subcategory') == $subcategory->id ? 'active' : '' }}">

										{{ $subcategory->name }}

									</a>

								@endforeach

							</div>

						@endif

					</div>

				@endforeach

			</div>
        </div>

    </div>

</section>
<!-- =======================
CATALOG HERO END
======================= -->

<!-- =======================
Sponsoring START -->
@include('partials.sponsored-books-banner')
<!-- =======================
Sponsoring END -->

<!-- =======================
Title and Tabs START -->
<section class="pt-0 pb-4">
	<div class="container position-relative">

		<!-- Title and button START -->
		<div class="row">
			<div class="col-12">
				<!-- Meta START -->
				<div class="d-flex justify-content-between">
					<!-- Filter collapse button -->
					<input type="checkbox" class="btn-check" id="btn-check-soft">
					<label class="btn btn-primary-soft btn-primary-check mb-0" for="btn-check-soft" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-controls="collapseFilter">
						<i class="bi fa-fe bi-sliders me-2"></i>Filtres
					</label>
			    </div>
				<!-- Meta END -->
			</div>
		</div>
		<!-- Title and button END -->

		<!-- Collapse body START -->
		<div class="collapse" id="collapseFilter">
			<div class="card card-body bg-light p-4 mt-4 z-index-9">

				<!-- Form START -->
				<form class="row g-4" method="GET" action="{{ route('catalogue') }}">
					<!-- Input item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-control-borderless">
							<label class="form-label">Entrez le nom du livre</label>
							<input 
								type="text" 
								name="title"
								value="{{ request('title') }}"
								class="form-control form-control-lg"
								placeholder="Nom du livre">
						</div>
					</div>

					<!-- nouislider item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Prix</label>
							<select name="price" class="form-select js-choice border-0">

								<option value="">
									Sélectionnez une option
								</option>

								<option value="10-20"
								{{ request('price') == '10-20' ? 'selected' : '' }}>
								10 - 20$
								</option>


								<option value="30-40"
								{{ request('price') == '30-40' ? 'selected' : '' }}>
								30 - 40$
								</option>


								<option value="50+"
								{{ request('price') == '50+' ? 'selected' : '' }}>
								+50$
								</option>

								</select>
						</div>
					</div>

					<!-- Select item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Categorie</label>
							<select name="category" class="form-select js-choice border-0">

								<option value="">
								Sélectionnez une option
								</option>

								@foreach($categories as $category)
									<option value="{{ $category->id }}"
										{{ request('category') == $category->id ? 'selected' : '' }}>
										{{ $category->name }}
									</option>
								@endforeach
							</select>
						</div>
					</div>

					<!-- Customer rating -->
						<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Auteurs</label>
							<select name="author" class="form-select js-choice border-0">

								<option value="">
								Sélectionnez une option
								</option>

								@foreach($authors as $author)
									<option value="{{ $author->id }}"
									{{ request('author') == $author->id ? 'selected' : '' }}>

									{{ $author->firstname }}
									{{ $author->lastname }}
									</option>
								@endforeach

							</select>
						</div>
					</div>

					<!-- Star rating -->
					<div class="col-md-6 col-lg-4">
						<div class="form-control-borderless">
							<label class="form-label">Nombre d'etoiles</label>
							<ul class="list-inline mb-0 g-3">
								@for($i=1;$i<=5;$i++)
									<li class="list-inline-item">
										<input type="radio" class="btn-check" name="rating" value="{{ $i }}" id="rating{{ $i }}" {{ request('rating') == $i ? 'checked':'' }}>
										<label class="btn btn-white btn-primary-soft-check" for="rating{{ $i }}">
										{{ $i }}
										<i class="bi bi-star-fill"></i>
										</label>
									</li>
								@endfor
							</ul>
						</div>	
					</div>

					<!-- Select item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Type du livre</label>
							<select name="type" class="form-select js-choice border-0">

								<option value="">
								Sélectionnez une option
								</option>

								<option value="ebook"
								{{ request('type')=='ebook'?'selected':'' }}>
								Ebook
								</option>

								<option value="audio"
								{{ request('type')=='audio'?'selected':'' }}>
								Audio
								</option>

							</select>
						</div>
					</div>

					<!-- Button -->
					<div class="text-end align-items-center">
						<a href="{{ route('catalogue') }}" class="btn btn-link p-0 mb-0">
							Tout effacer
						</a>
						<button class="btn btn-dark mb-0 ms-3">Appliquer le filtre</button>
					</div>
				</form>
				<!-- Form END -->
			</div>
		</div>
		<!-- Collapse body END -->

	</div>
</section>
<!-- =======================
Title and Tabs END -->

<!-- =======================
Book list START -->
<section class="pt-0">
	<div class="container">
		<div class="row g-4">

			@forelse($books as $book)

				<div class="col-md-6 col-xl-4">
					<div class="card shadow p-2 pb-0 h-100">

						<img src="{{ asset('storage/'.$book->cover_image) }}"
						class="rounded-2 catalog-img"
						alt="{{ $book->title }}">

						<div class="card-body px-3 pb-0">

							<div class="d-flex justify-content-between mb-3">

								<a class="badge bg-dark text-white">
									<i class="bi fa-fw bi-star-fill me-2 text-warning"></i>
									{{ number_format($book->reviews_avg_rating ?? 0,1) }}
								</a>

								@auth
									@if(in_array($book->id, $wishlistIds ?? [], true))
										<form method="POST" action="{{ route('wishlist.destroy', $book) }}" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="h6 mb-0 btn btn-link p-0 text-danger" title="Retirer de la wishlist">
												<i class="bi bi-heart-fill"></i>
											</button>
										</form>
									@else
										<form method="POST" action="{{ route('wishlist.store', $book) }}" class="d-inline">
											@csrf
											<button type="submit" class="h6 mb-0 btn btn-link p-0 text-body" title="Ajouter à la wishlist">
												<i class="bi bi-heart"></i>
											</button>
										</form>
									@endif
								@else
									<a class="h6 mb-0" href="{{ route('login') }}" title="Connectez-vous pour sauvegarder">
										<i class="bi bi-heart"></i>
									</a>
								@endauth

							</div>


							<h5 class="card-title">
								<a href="{{ route('books.show',$book) }}">
									{{ $book->title }}
								</a>
							</h5>


							<p class="author">
								Par {{ $book->author->firstname }} {{ $book->author->lastname }}
							</p>


							<ul class="nav nav-divider mb-2 mb-sm-3">

								@if($book->type == 'ebook')
									<li class="nav-item">
										{{ $book->pages }} pages
									</li>
								@else
									<li class="nav-item">
										{{ $book->duration }}
									</li>
								@endif

								<li class="nav-item">
									{{ $book->language }}
								</li>

								<li class="nav-item">
									{{ $book->publication_year }}
								</li>

							</ul>

						</div>


						<div class="card-footer pt-0">

							<div class="d-flex justify-content-between align-items-center">

								<h5 class="price mb-0">
									{{ number_format($book->price,2) }}$
								</h5>


								<a href="{{ route('books.show',$book) }}"
								class="btn btn-sm btn-primary-soft">

									Voir plus
									<i class="bi bi-arrow-right ms-2"></i>

								</a>

							</div>

						</div>

					</div>
				</div>

			@empty

				<div class="col-12">

					<div class="text-center py-5">

						<div class="mb-3">
							<i class="bi bi-search fs-1 text-red"></i>
						</div>

						<h4 class="mb-2">
							Oops ! Aucun livre trouvé
						</h4>

						<p class="text-black">
							Aucun ouvrage ne correspond à vos critères de recherche.
							Essayez avec d'autres mots-clés ou modifiez vos filtres.
						</p>

						<a href="{{ route('catalogue') }}" 
						class="btn btn-primary-soft">

							Réinitialiser les filtres

						</a>

					</div>

				</div>

			@endforelse

        </div>

		<!-- Pagination -->
		<div class="row">
			<div class="col-12">
				<div class="d-flex justify-content-center mt-4">
					<div class="kama-pagination">
						{{ $books->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- =======================
Book list END -->

<section class="author-cta">

  <div class="author-cta-container">

    <div class="author-cta-text">

      <h2>Vous êtes écrivain ?</h2>

      <p>
        Faites découvrir vos œuvres au monde entier. Publiez vos livres sur KaMa
        et rejoignez une communauté d’écrivains passionnés.
      </p>

      <p class="highlight">
        Inscrivez-vous aujourd’hui et commencez à partager vos histoires.
      </p>

      <a href="/register" class="cta-btn">
        S’inscrire sur KaMa
      </a>

    </div>

    <div class="author-cta-card">

      <div class="floating-badge">Auteurs KaMa</div>

      <h3>Publiez. Partagez. Inspirez.</h3>

      <ul>
        <li>Publication rapide</li>
        <li>Audience internationale</li>
        <li>Statistiques de lecture</li>
      </ul>

    </div>

  </div>

</section>

@endsection