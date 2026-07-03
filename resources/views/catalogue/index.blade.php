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
            <form class="catalog-search">
                <input 
                    type="text" 
                    placeholder="Rechercher un livre, auteur, catégorie..."
                >
                <button type="submit">
                     <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- QUICK FILTERS -->
            <div class="catalog-filters">

                <a href="#" class="filter active">Tous</a>
                <a href="#" class="filter">Business</a>
                <a href="#" class="filter">Développement personnel</a>
                <a href="#" class="filter">Finance</a>
                <a href="#" class="filter">Psychologie</a>
                <a href="#" class="filter">Roman</a>
                <a href="#" class="filter">Science</a>
                <a href="#" class="filter">Histoire</a>
                <a href="#" class="filter">Technologie</a>
                <a href="#" class="filter">Marketing</a>
                <a href="#" class="filter">Éducation</a>
                <a href="#" class="filter">Biographies</a>

            </div>

        </div>

    </div>

</section>
<!-- =======================
CATALOG HERO END
======================= -->

<!-- =======================
Advertisement START -->
<section class="pb-2 pb-lg-5">
	<div class="container">
		<!-- Slider START -->
		<div class="tiny-slider arrow-round arrow-blur arrow-hover">
			<div class="tiny-slider-inner" data-autoplay="true" data-arrow="true" data-edge="2" data-dots="false" data-items-xl="3" data-items-lg="2" data-items-md="1">
				<!-- Slider item -->
				<div>
					<div class="card border rounded-3 overflow-hidden">
                            <span class="ad-badge">Sponsorisé</span>
						<div class="row g-0 align-items-center">

							<!-- Image -->
							<div class="col-sm-6">
								<img src="{{ asset('assets/images/sponsor/01.jpg') }}" class="card-img rounded-0" alt="">
							</div>

							<!-- Title and content -->
							<div class="col-sm-6">
								<div class="card-body px-3">
									<h6 class="card-title"><a href="offer-detail.html" class="stretched-link">Atomic Habits</a></h6>
									<p class="mb-0 author">Par James Clear</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Slider item -->
				<div>
					<div class="card border rounded-3 overflow-hidden">
                        <span class="ad-badge">Sponsorisé</span>
						<div class="row g-0 align-items-center">
							<!-- Image -->
							<div class="col-sm-6">
								<img src="{{ asset('assets/images/sponsor/02.jpg') }}" class="card-img rounded-0" alt="">
							</div>

							<!-- Title and content -->
							<div class="col-sm-6">
								<div class="card-body px-3">
									<h6 class="card-title"><a href="offer-detail.html" class="stretched-link">The 5 AM Club</a></h6>
									<p class="mb-0 author">Par Cal Newport</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Slider item -->
				<div>
					<div class="card border rounded-3 overflow-hidden">
                        <span class="ad-badge">Sponsorisé</span>
						<div class="row g-0 align-items-center">
							<!-- Image -->
							<div class="col-sm-6">
								<img src="{{ asset('assets/images/sponsor/03.jpg') }}" class="card-img rounded-0" alt="">
							</div>

							<!-- Title and content -->
							<div class="col-sm-6">
								<div class="card-body px-3">
									<h6 class="card-title"><a href="offer-detail.html" class="stretched-link">Deep Work</a></h6>
									<p class="mb-0 author">Par Morgan Housel</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Slider item -->
				<div>
					<div class="card border rounded-3 overflow-hidden">
                        <span class="ad-badge">Sponsorisé</span>
						<div class="row g-0 align-items-center">
							<!-- Image -->
							<div class="col-sm-6">
								<img src="{{ asset('assets/images/sponsor/01.jpg') }}" class="card-img rounded-0" alt="">
							</div>

							<!-- Title and content -->
							<div class="col-sm-6">
								<div class="card-body px-3">
									<h6 class="card-title"><a href="offer-detail.html" class="stretched-link">The Psychology of Money</a></h6>
									<p class="mb-0 author"> Par Robin Sharma</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<!-- Slider END -->
	</div>
</section>
<!-- =======================
Advertisement END -->

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
				<form class="row g-4">
					<!-- Input item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-control-borderless">
							<label class="form-label">Entrez le nom du livre</label>
							<input type="text" class="form-control form-control-lg">
						</div>
					</div>

					<!-- nouislider item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Prix</label>
							<select class="form-select js-choice border-0">
								<option value="">Selectionnez une option</option>
								<option>10 - 20$</option>
								<option>30 - 40$</option>
								<option>+50$ </option>
                              
							</select>
						</div>
					</div>

					<!-- Select item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Categorie</label>
							<select class="form-select js-choice border-0">
								<option value="">Selectionnez une option</option>
								<option>Science</option>
								<option>Fiction</option>
								<option>Etat d'esprit</option>
                                <option>Histoire</option>
                                <option>Developpement personnel</option>
							</select>
						</div>
					</div>

					<!-- Customer rating -->
						<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Auteurs</label>
							<select class="form-select js-choice border-0">
								<option value="">Selectionnez une option</option>
								<option>Ornella</option>
								<option>Fifa</option>
							</select>
						</div>
					</div>

					<!-- Star rating -->
					<div class="col-md-6 col-lg-4">
						<div class="form-control-borderless">
							<label class="form-label">Nombre d'etoiles</label>
							<ul class="list-inline mb-0 g-3">
								<!-- 1 -->
								<li class="list-inline-item">
									<input type="checkbox" class="btn-check" id="btn-check-9">
									<label class="btn btn-white btn-primary-soft-check" for="btn-check-9">1<i class="bi bi-star-fill"></i></label>
								</li>
								<!-- 2 -->
								<li class="list-inline-item">
									<input type="checkbox" class="btn-check" id="btn-check-10">
									<label class="btn btn-white btn-primary-soft-check" for="btn-check-10">2<i class="bi bi-star-fill"></i></label>
								</li>
								<!-- 3 -->
								<li class="list-inline-item">
									<input type="checkbox" class="btn-check" id="btn-check-11">
									<label class="btn btn-white btn-primary-soft-check" for="btn-check-11">3<i class="bi bi-star-fill"></i></label>
								</li>
								<!-- 4 -->
								<li class="list-inline-item">
									<input type="checkbox" class="btn-check" id="btn-check-12">
									<label class="btn btn-white btn-primary-soft-check" for="btn-check-12">4<i class="bi bi-star-fill"></i></label>
								</li>
								<!-- 4 -->
								<li class="list-inline-item">
									<input type="checkbox" class="btn-check" id="btn-check-13">
									<label class="btn btn-white btn-primary-soft-check" for="btn-check-13">5<i class="bi bi-star-fill"></i></label>
								</li>
							</ul>
						</div>	
					</div>

					<!-- Select item -->
					<div class="col-md-6 col-lg-4">
						<div class="form-size-lg form-control-borderless">
							<label class="form-label">Type du livre</label>
							<select class="form-select js-choice border-0">
								<option value="">Selectionnez une option</option>
								<option>Ebook</option>
								<option>Audio</option>
							</select>
						</div>
					</div>

					<!-- Button -->
					<div class="text-end align-items-center">
						<button class="btn btn-link p-0 mb-0">Tout effacer</button>
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
			<!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book2.jpg')}}" class="rounded-2 " alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">Atomic Habits</a></h5>
                        <p class="author"> Par James Clear</p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">320 pages</li>
							<li class="nav-item">Anglais</li>
							<li class="nav-item">2025</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">22$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->
			
            <!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book3.jpg')}}" class="rounded-2" alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">The 5 AM Club</a></h5>
                        <p class="author"> Par Robin Sharma </p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">120 pages</li>
							<li class="nav-item">Francais</li>
							<li class="nav-item">2026</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">12$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->

            <!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book4.jpg')}}" class="rounded-2" alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">Deep Work</a></h5>
                        <p class="author"> Par Cal NewPort</p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">200 pages</li>
							<li class="nav-item">Anglais</li>
							<li class="nav-item">2020</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">17.5$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->

           <!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book2.jpg')}}" class="rounded-2" alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">Atomic Habits</a></h5>
                        <p class="author"> Par James Clear</p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">320 pages</li>
							<li class="nav-item">Anglais</li>
							<li class="nav-item">2025</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">22$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->
			
            <!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book3.jpg')}}" class="rounded-2" alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">The 5 AM Club</a></h5>
                        <p class="author"> Par Robin Sharma </p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">120 pages</li>
							<li class="nav-item">Francais</li>
							<li class="nav-item">2026</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">12$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->

            <!-- Card item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card shadow p-2 pb-0 h-100">
					<!-- Image -->
					<img src="{{ asset ('assets/images/category/une/4by3/book4.jpg')}}" class="rounded-2" alt="Card image">

					<!-- Card body START -->
					<div class="card-body px-3 pb-0">
						<!-- Rating and cart -->
						<div class="d-flex justify-content-between mb-3">
							<a href="" class="badge bg-dark text-white"><i class="bi fa-fw bi-star-fill me-2 text-warning"></i>4.0</a>
							<a href="" class="h6 mb-0 z-index-2"><i class="bi fa-fw bi-heart"></i></a>
						</div>

						<!-- Title -->
						<h5 class="card-title"><a href="hotel-detail.html">Deep Work</a></h5>
                        <p class="author"> Par Cal NewPort</p>

						<!-- List -->
						<ul class="nav nav-divider mb-2 mb-sm-3">
							<li class="nav-item">200 pages</li>
							<li class="nav-item">Anglais</li>
							<li class="nav-item">2020</li>
						</ul>
					</div>
					<!-- Card body END -->

					<!-- Card footer START-->
					<div class="card-footer pt-0">
						<!-- Price and Button -->
						<div class="d-sm-flex justify-content-sm-between align-items-center">
							<!-- Price -->
							<div class="d-flex align-items-center">
								<h5 class="fw-normal price mb-0 me-1">17.5$</h5>
							</div>
							<!-- Button -->
							<div class="mt-2 mt-sm-0 z-index-2">
								<a href="{{ url('/detaillivre') }}" class="btn btn-sm btn-primary-soft mb-0 w-100">Voir plus<i class="bi bi-arrow-right ms-2"></i></a>    
							</div>                  
						</div>
					</div>
				</div>
			</div>
			<!-- Card item END -->

		</div> <!-- Row END -->

		<!-- Pagination -->
		<div class="row">
			<div class="col-12">
				<nav class="mt-4 d-flex justify-content-center" aria-label="navigation">
					<ul class="pagination pagination-primary-soft d-inline-block d-md-flex rounded mb-0">
						<li class="page-item mb-0"><a class="page-link" href="hotel-grid.html#" tabindex="-1"><i class="fa-solid fa-angle-left"></i></a></li>
						<li class="page-item mb-0"><a class="page-link" href="hotel-grid.html#">1</a></li>
						<li class="page-item mb-0 active"><a class="page-link" href="hotel-grid.html#">2</a></li>
						<li class="page-item mb-0"><a class="page-link" href="hotel-grid.html#">..</a></li>
						<li class="page-item mb-0"><a class="page-link" href="hotel-grid.html#">6</a></li>
						<li class="page-item mb-0"><a class="page-link" href="hotel-grid.html#"><i class="fa-solid fa-angle-right"></i></a></li>
					</ul>
				</nav>
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
        et rejoignez une communauté de lecteurs passionnés.
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