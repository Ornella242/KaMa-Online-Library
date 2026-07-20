@extends('layouts.app')

@section('content')

<!-- =======================
CATALOG HERO START
======================= -->
<section class="catalog-hero">

    <div class="kama-hero-container">

        <div class="catalog-hero-content">


            <!-- LEFT CONTENT -->
            <div class="catalog-text">


                <span class="catalog-badge">
                    <i class="bi bi-book"></i>
                    Bibliothèque africaine numérique
                </span>


                <h1>
                    Découvrez les œuvres littéraires
                    <span>d'Afrique</span>
                    en un seul endroit
                </h1>


                <p>
                    Explorez une collection unique de livres, ebooks et audiolivres
                    créés par des auteurs africains. Plongez dans des récits,
                    des cultures et des imaginaires venus de tout le continent.
                </p>



                <!-- SEARCH -->

                <form class="catalog-search" 
                      method="GET" 
                      action="{{ route('catalogue') }}">


                    <input 
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher un livre, auteur..."
                    >


                    <button type="submit">

                        <i class="bi bi-search"></i>

                    </button>


                </form>



                <!-- MINI INFOS -->

                <div class="hero-mini-info">
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Lecture instantanée
                    </div>


                    <div>
                        <i class="bi bi-headphones"></i>
                        Audiobooks disponibles
                    </div>


                    <div>
                        <i class="bi bi-phone"></i>
                        Accessible partout
                    </div>
                </div>
            </div>



            <!-- RIGHT VISUAL -->

            <div class="catalog-visual">
                <div class="book-decoration">
                    <i class="bi bi-book-half"></i>
                </div>

                <div class="floating-card">
                    <i class="bi bi-globe"></i>
                    <div>
                        <strong>{{ $representedCountries }}</strong>
                        <span>Pays africains</span>
                    </div>
                </div>

                <div class="floating-card second">
                    <i class="bi bi-people"></i>
                    <div>
                        <strong>{{ $authors->count() }}+</strong>
                        <span>Auteurs</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="catalog-stats">
            <div class="catalog-stat-item">
                <i class="bi bi-book"></i>
                <div>
                    <strong>{{ $books->total() }}+</strong>
                    <span>Livres disponibles</span>
                </div>
            </div>

            <div class="catalog-stat-item">
                <i class="bi bi-person"></i>
                <div>
                    <strong>{{ $authors->count() }}+</strong>
                    <span>Auteurs africains</span>
                </div>
            </div>

            <div class="catalog-stat-item">
                <i class="bi bi-globe"></i>
                <div>
                    <strong>54</strong>
                    <span>Pays représentés</span>
                </div>
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
<section class="catalog-filter-section">

    <div class="kama-hero-container">

        <!-- FILTER BUTTON -->
        <div class="filter-header">

            <button class="filter-toggle"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFilter">

                <i class="bi bi-sliders"></i>
                Filtrer les livres

            </button>

            <a href="{{ route('catalogue') }}" class="clear-filter">
                <i class="bi bi-arrow-counterclockwise"></i>
                Réinitialiser
            </a>

        </div>



        <!-- FILTER BOX -->

        <div class="collapse" id="collapseFilter">
            <div class="premium-filter-box">
                <form method="GET" action="{{ route('catalogue') }}#books-list" 
                      class="row g-4">

                    <!-- TITLE -->
                    <div class="col-lg-4">
                        <label>
                            <i class="bi bi-book"></i>
                            Nom du livre
                        </label>

                        <input 
                            type="text"
                            name="title"
                            value="{{ request('title') }}"
                            placeholder="Rechercher un titre..."
                        >

                    </div>



                    <!-- CATEGORY -->

                    <div class="col-lg-4">

                        <label>
                            <i class="bi bi-grid"></i>
                            Catégorie
                        </label>


                        <select name="category">

                            <option value="">
                                Toutes les catégories
                            </option>


                            @foreach($categories as $category)

                                <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected':'' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach


                        </select>


                    </div>




                    <!-- AUTHOR -->

                    <div class="col-lg-4">

                        <label>
                            <i class="bi bi-person"></i>
                            Auteur
                        </label>


                        <select name="author">

                            <option value="">
                                Tous les auteurs
                            </option>


                            @foreach($authors as $author)

                                <option value="{{ $author->id }}"
                                {{ request('author') == $author->id ? 'selected':'' }}>

                                    {{ $author->firstname }}
                                    {{ $author->lastname }}

                                </option>


                            @endforeach

                        </select>


                    </div>





                    <!-- PRICE -->

                    <div class="col-lg-4">

                        <label>
                            <i class="bi bi-currency-dollar"></i>
                            Prix
                        </label>


                        <select name="price">


                            <option value="">
                                Tous les prix
                            </option>


                            <option value="10-20">
                                10 - 20 $
                            </option>


                            <option value="30-40">
                                30 - 40 $
                            </option>


                            <option value="40-50">
                                40 - 50 $
                            </option>


                            <option value="50+">
                                Plus de 50 $
                            </option>


                        </select>

                    </div>





                    <!-- TYPE -->

                    <div class="col-lg-4">

                        <label>
                            <i class="bi bi-headphones"></i>
                            Format
                        </label>


                        <select name="type">


                            <option value="">
                                Tous les formats
                            </option>


                            <option value="ebook">
                                Ebook
                            </option>


                            <option value="audio">
                                Audiobook
                            </option>


                        </select>


                    </div>




                    <!-- RATING -->

                    <div class="col-lg-4">

                        <label>
                            <i class="bi bi-star"></i>
                            Note minimale
                        </label>


                        <div class="rating-filter">


                            @for($i=1;$i<=5;$i++)

                            <input 
                            type="radio"
                            name="rating"
                            value="{{ $i }}"
                            id="star{{$i}}">


                            <label for="star{{$i}}">
                                {{ $i }}
                                <i class="bi bi-star-fill"></i>
                            </label>


                            @endfor


                        </div>


                    </div>



                    <!-- BUTTON -->

                    <div class="col-12 text-end">


                        <button class="apply-filter">

                            <i class="bi bi-search"></i>
                            Appliquer les filtres

                        </button>


                    </div>


                </form>


            </div>

        </div>


    </div>

</section>
<!-- =======================
Title and Tabs END -->

<!-- =======================
Book list START -->
<section class="pt-0" id="books-list">
	<div class="kama-hero-container">
		<div class="row g-4 books-grid">

			@forelse($books as $book)

				<div class="col-md-6 col-xl-4">

			<div class="book-card h-100">

				<!-- COVER -->
				<div class="book-cover-wrapper">

					<img src="{{ asset('storage/'.$book->cover_image) }}"
						class="book-cover-img"
						alt="{{ $book->title }}">


					<span class="book-type-badge">
						{{ strtoupper($book->type) }}
					</span>


					<button class="favorite-btn">
						<i class="bi bi-heart"></i>
					</button>

				</div>


				<!-- CONTENT -->
				<div class="book-card-body">


					<div class="book-rating">

						<i class="bi bi-star-fill"></i>

						{{ number_format($book->reviews_avg_rating ?? 0,1) }}

					</div>


					<h5 class="book-title">

						<a href="{{ route('books.show',$book) }}">
							{{ $book->title }}
						</a>

					</h5>


					<p class="book-author">

						Par 
						{{ $book->author->firstname }}
						{{ $book->author->lastname }}

					</p>


					<div class="book-meta">

						@if($book->type == 'ebook')

							<span>
								<i class="bi bi-file-earmark-text"></i>
								{{ $book->pages }} pages
							</span>

						@else

							<span>
								<i class="bi bi-headphones"></i>
								{{ $book->duration }}
							</span>

						@endif


						<span>
							<i class="bi bi-translate"></i>
							{{ $book->language }}
						</span>


						<span>
							<i class="bi bi-calendar"></i>
							{{ $book->publication_year }}
						</span>

					</div>

				</div>



				<!-- FOOTER -->

				<div class="book-card-footer">

					<strong>
						{{ number_format($book->price,2) }}$
					</strong>


					<a href="{{ route('books.show',$book) }}"
					class="book-btn">

						Voir plus
						<i class="bi bi-arrow-right"></i>

					</a>

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
				<div class="kama-pagination-wrapper">
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

            <span class="cta-badge">
                <i class="bi bi-pen-fill"></i>
                Espace écrivains KaMa
            </span>


            <h2>
                Faites partie des premiers écrivains à représenter votre pays
                <span>et portez haut votre drapeau !</span>
            </h2>


            <p>
                KaMa ouvre une nouvelle porte aux auteurs africains.
                Publiez vos histoires, partagez votre culture et faites découvrir
                votre univers à des lecteurs du monde entier.
            </p>


            <p class="highlight">
                Rejoignez les premiers écrivains de votre pays sur KaMa et
                contribuez à écrire la prochaine page de la littérature africaine.
            </p>


            <a href="/register" class="cta-btn">
                Devenir auteur KaMa
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>



        <div class="author-cta-card">


            <div class="floating-badge">
                Auteurs KaMa
            </div>


            <div class="cta-icon">
                <i class="bi bi-book-half"></i>
            </div>


            <h3 class="text-white">
                Votre histoire.
                <br>
                Votre voix.
                <br>
                Votre héritage.
            </h3>


            <ul>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Publiez vos livres facilement
                </li>


                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Touchez des lecteurs partout
                </li>


                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Valorisez votre culture
                </li>

            </ul>


        </div>


    </div>

</section>

@endsection