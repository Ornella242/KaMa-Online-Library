@extends('layouts.app')

@section('content')

<section class="details-hero">

  <!-- BACKGROUND LAYERS -->
  <div class="hero-bg-circle c1"></div>
  <div class="hero-bg-circle c2"></div>

  <!-- CONTENT -->
  <div class="hero-content">

    <div class="breadcrumb">
      <a href="{{ url('/catalogue') }}">Catalogue</a> / <span>Atomic Habits</span>
    </div>

    <h1>Atomic Habits</h1>

  </div>

</section>

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

<section class="details-book">

  <div class="details-container">

    <!-- LEFT IMAGE -->
    <div class="book-image">
      <img src="assets/images/category/une/4by3/book2.jpg" alt="Book title">
    </div>

    <!-- RIGHT CONTENT -->
    <div class="book-content">

      <span class="category">Science & Habits</span>

      <h1>Atomic Habits</h1>

      <!-- RATING -->
      <div class="rating">
        <i class="bi bi-star-fill star"></i>
        <span>4.5 • 120 reviews</span>
      </div>

      <!-- DESCRIPTION -->
      <p class="summary">
        A powerful book designed to improve your mindset, productivity and financial intelligence.
        A powerful book designed to improve your mindset, productivity and financial intelligence.

      </p>

      <!-- META INFOS -->
      <div class="meta-line">

        <span class="meta-chip">
          <i class="bi bi-file-text meta-icon"></i>
          320 pages
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-calendar meta-icon"></i>
          2018
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-globe meta-icon"></i>
          English
        </span>

        <span class="meta-sep">•</span>

        <span class="meta-chip">
          <i class="bi bi-tag meta-icon"></i>
          Science & Habits
        </span>

      </div>

      <!-- PRICE -->
      <div class="price">
        22 $
      </div>

      <!-- ACTIONS -->
      <div class="actions">

        <!-- Lire un peu -->
        <a href="#full-description" class="primary-btn">
            <i class="bi bi-book"></i>
            Lire un peu
        </a>

        <!-- Add to cart -->
        <button class="cart-btn">
          <i class="bi bi-cart"></i>
          Add to cart
        </button>

        <!-- Wishlist -->
        <button class="wishlist-btn">
          <i class="bi bi-heart"></i>
        </button>

      </div>

      <div class="same-author">

        <h3 class="same-title">Autres livres du même auteur</h3>

        <div class="same-grid">

            <!-- BOOK 1 -->
            <div class="same-card">
            <img src="{{ asset('assets/images/category/une/4by3/book2.jpg') }}" alt="Book">

            <div class="same-info">
                <h4>Atomic Habits</h4>
                <a href="#" class="see-more"><a href="#" class="see-more"><i class="bi bi-eye"></i></a></a>
            </div>
            </div>

            <!-- BOOK 2 -->
            <div class="same-card">
            <img src="{{ asset('assets/images/category/une/4by3/book4.jpg') }}" alt="Book">

            <div class="same-info">
                <h4>Power of Discipline</h4>
                <a href="#" class="see-more"><a href="#" class="see-more"><i class="bi bi-eye"></i></a></a>
            </div>
            </div>

            <!-- BOOK 3 -->
            <div class="same-card">
            <img src="{{ asset('assets/images/category/une/4by3/book3.jpg') }}" alt="Book">

            <div class="same-info">
                <h4>Make It Stick</h4>
                <a href="#" class="see-more"><i class="bi bi-eye"></i></a>
            </div>
            </div>

        </div>

     </div>
    </div>

    <section class="book-full-description">
        <!-- FULL WIDTH SECTION INSIDE CARD -->
        <div class="book-full-description" id="full-description">

        <h2>Résumé détaillé</h2>

        <p>
            Atomic Habits explique comment de petits changements quotidiens peuvent produire des résultats extraordinaires.
            Le livre montre comment les habitudes se construisent et comment les transformer durablement.
        </p>

        <p>
            L’idée centrale est simple : tu n’as pas besoin de changer radicalement ta vie, mais d’améliorer ton système
            jour après jour.
            
        </p>

        <ul>
            <li>Comprendre la formation des habitudes</li>
            <li>Supprimer les mauvaises habitudes</li>
            <li>Construire de nouveaux systèmes</li>
            <li>Améliorer la discipline personnelle</li>
        </ul>

        </div>
    </section>
  </div>

</section>



<section class="reviews-section">

  <h2 class="reviews-title">Avis des lecteurs</h2>

  <div class="reviews-grid">

    <!-- LEFT: AUTO CAROUSEL -->
    <div class="reviews-carousel carousel">


        <div class="carousel-track">
            <!-- CARD 1 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>

            <div class="review-content">
                <h4>Marie K.</h4>
                <div class="stars">★★★★★</div>
                <p>Un livre incroyable qui change la façon de penser les habitudes.</p>
            </div>
            </div>

            <!-- CARD 2 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>
            <div class="review-content">
                <h4>John D.</h4>
                <div class="stars">★★★★☆</div>
                <p>Très fluide à lire, concret et utile au quotidien.</p>
            </div>
            </div>

            <!-- CARD 3 -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>
            <div class="review-content">
                <h4>Amina S.</h4>
                <div class="stars">★★★★★</div>
                <p>Un must-read pour la discipline personnelle.</p>
            </div>
            </div>

            <!-- DUPLICATION POUR LOOP -->
            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>            <div class="review-content">
                <h4>Marie K.</h4>
                <div class="stars">★★★★★</div>
                <p>Un livre incroyable qui change la façon de penser les habitudes.</p>
            </div>
            </div>

            <div class="review-card">
            <div class="avatar">
                <img src="{{ asset ('assets/images/authors/author1.jpg') }}" alt="">
            </div>            <div class="review-content">
                <h4>John D.</h4>
                <div class="stars">★★★★☆</div>
                <p>Très fluide à lire, concret et utile au quotidien.</p>
            </div>
            </div>

        </div>
    </div>

    <!-- RIGHT: FORM -->
    <div class="reviews-form-box">

      <h3>Laisser un avis</h3>

      <form class="review-form">

        <div class="form-row">
          <input type="text" placeholder="Nom">
          <input type="text" placeholder="Prénom">
        </div>

        <input type="email" placeholder="Email">

        <textarea rows="5" placeholder="Votre avis..."></textarea>
        <div class="rating-select">
                    <label>Note</label>
                    <select name="rating">
                    <option value="5">★★★★★ (5/5)</option>
                    <option value="4">★★★★☆ (4/5)</option>
                    <option value="3">★★★☆☆ (3/5)</option>
                    <option value="2">★★☆☆☆ (2/5)</option>
                    <option value="1">★☆☆☆☆ (1/5)</option>
                    </select>
        </div>

        <button type="submit">Publier</button>

      </form>

    </div>

  </div>

</section>
@endsection