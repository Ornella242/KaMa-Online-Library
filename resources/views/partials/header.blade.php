<!-- Header START -->
{{-- <header class="navbar-light header-sticky">
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-xl">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="{{ url ('/') }}">
				<img class="light-mode-item navbar-brand-item" src="{{ asset('assets/images/logo.svg')}}" alt="logo">
				<img class="dark-mode-item navbar-brand-item" src="{{ asset('assets/images/logo-light.svg')}}" alt="logo">
			</a>
			<!-- Logo END -->

			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto ms-sm-0 p-0 p-sm-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategoryCollapse" aria-controls="navbarCategoryCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<i class="bi bi-grid-3x3-gap-fill fa-fw"></i> <span class="d-none d-sm-inline-block small">Menu</span>
			</button>

			<!-- Main navbar START -->
			<div class="navbar-collapse collapse" id="navbarCategoryCollapse">
				<ul class="navbar-nav navbar-nav-scroll me-auto nav-pills-primary-soft text-center ms-auto p-2 p-xl-0">

                    <!-- Nav item Accueil -->
					<li class="nav-item"> <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/') }}" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-house me-2"></i>Accueil</a></li>

					<!-- Nav item Catalogue -->
					<li class="nav-item"> <a class="nav-link {{ request()->is('catalogue') ? 'active' : '' }}" href="{{ url('/catalogue') }}" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-book me-2"></i>Catalogue</a></li>
				</ul>
			</div>
			<!-- Main navbar END -->

			<!-- Profile and Notification START -->
			<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">

				<!-- Notification dropdown START -->
				<li class="nav-item dropdown ms-0 ms-md-3">
					<!-- Notification button -->
					<a class="nav-notification btn btn-light p-0 mb-0" href="index.html#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
						<i class="bi bi-bell fa-fw"></i>
					</a>
					<!-- Notification dote -->
					<span class="notif-badge animation-blink"></span>

					<!-- Notification dropdown menu START -->
					<div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md shadow-lg p-0">
						<div class="card bg-transparent">
							<!-- Card header -->
							<div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
								<h6 class="m-0">Notifications <span class="badge bg-danger bg-opacity-10 text-danger ms-2">4 nouveaux</span></h6>
								<a class="small" href="index.html#">Tout effacer</a>
							</div>

							<!-- Card body START -->
							<div class="card-body p-0">
								<ul class="list-group list-group-flush list-unstyled p-2">
									<!-- Notification item -->
									<li>
										<a href="index.html#" class="list-group-item list-group-item-action rounded notif-unread border-0 mb-1 p-3">
											<h6 class="mb-2">Nouveau! Nouveau Livre ajouter sur KaMa</h6>
											<p class="mb-0 small">Trouvez les livres que vous recherchez</p>
											<span>Mercredi</span>
										</a>
									</li>
									<!-- Notification item -->
									<li>
										<a href="index.html#" class="list-group-item list-group-item-action rounded border-0 mb-1 p-3">
											<h6 class="mb-2">Un nouveau livre a été ajouté</h6>
											<span>15 Juin 2026</span>
										</a>
									</li>
								</ul>
							</div>
							<!-- Card body END -->

							<!-- Card footer -->
							<div class="card-footer bg-transparent text-center border-top">
								<a href="index.html#" class="btn btn-sm btn-link mb-0 p-0">Voir toute l'activité entrante</a>
							</div>
						</div>
					</div>
					<!-- Notification dropdown menu END -->
				</li>
				<!-- Notification dropdown END -->

				@if (Auth::check())
					<!-- Profile dropdown START -->
					<li class="nav-item ms-3 dropdown">
						<!-- Avatar -->
						<a class="avatar avatar-sm p-0" href="index.html#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="avatar-img rounded-2" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
						</a>

						<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
							<!-- Profile info -->
							<li class="px-3 mb-3">
								<div class="d-flex align-items-center">
									<!-- Avatar -->
									<div class="avatar me-3">
										<img class="avatar-img rounded-circle shadow" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
									</div>
									<div>
										@if(Auth::user()->role->name === 'writer')
											<a class="h6 mt-2 mt-sm-0" href="{{ url('/writer/dashboard') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
										@elseif(Auth::user()->role->name === 'reader')
											<a class="h6 mt-2 mt-sm-0" href="{{ url('/reader/account') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
										@else (Auth::user()->role->name === 'admin')
											<a class="h6 mt-2 mt-sm-0" href="{{ url('/admin/dashboard') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
										@endif
										<p class="small m-0">{{ Auth::user()->email }}</p>
									</div>
								</div>
							</li>

							@if(Auth::user()->role->name === 'writer')
								<li><a class="dropdown-item" href="{{ url('/writer/dashboard') }}"><i class="bi bi-house fa-fw me-2"></i>Tableau de bord</a></li>
								<li> <hr class="dropdown-divider"></li>
								<li>
									<form method="POST" action="{{ route('logout') }}">
										@csrf

										<button type="submit" class="nav-link text-danger bg-danger-soft-hover">
											<i class="fas fa-sign-out-alt fa-fw me-2"></i>
											Se deconnecter
										</button>
									</form>
								</li>
							@elseif(Auth::user()->role->name === 'reader')
								<!-- Links -->
								<li> <hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="{{ url('/reader/account') }}"><i class="bi bi-person-fill icon-red fa-fw me-2"></i>Mon Compte</a></li>
								<li><a class="dropdown-item" href="{{ url('/book') }}"><i class="bi bi-book icon-red fa-fw me-2"></i>Mes Livres</a></li>
								<li><a class="dropdown-item" href="{{ url('/wishlist') }}"><i class="bi bi-heart icon-red fa-fw me-2"></i>Ma Liste de Souhaits</a></li>
								<li><a class="dropdown-item" href="{{ url('/book') }}"><i class="bi bi-cart icon-red fa-fw me-2"></i>Mon panier</a></li>

								<li> <hr class="dropdown-divider"></li>
								<li>
									<form method="POST" action="{{ route('logout') }}">
										@csrf

										<button type="submit" class="nav-link text-danger bg-danger-soft-hover">
											<i class="fas fa-sign-out-alt fa-fw me-2"></i>
											Se deconnecter
										</button>
									</form>
								</li>
							@endif
								@else
								<!-- Profile dropdown END -->
								
								<li class="nav-item ms-0 ms-md-3">
									<a href="{{ url('/register') }}" class="btn btn-sm btn-register mb-0"><i class="fa-solid fa-right-to-bracket me-sm-2"></i><span class="d-none d-sm-inline">Inscription</span></a>
								</li>
						@endif
						</ul>
			<!-- Profile and Notification START -->

		</div>
	</nav>
	<!-- Logo Nav END -->
</header> --}}

<header class="kama-header header-sticky">
	<nav class="navbar navbar-expand-xl navbar-light">
		<div class="container">

			<!-- LOGO -->
			<a class="navbar-brand kama-logo-wrapper" href="{{ url('/') }}">
				<img src="{{ asset('assets/images/logo.svg') }}" 
					class="kama-logo"
					alt="KaMa Online Library">
			</a>

			<!-- MOBILE BUTTON -->
			<button class="navbar-toggler" 
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#kamaNavbar">

				<span class="navbar-toggler-icon"></span>

			</button>

			<div class="collapse navbar-collapse" id="kamaNavbar">
				<!-- MENU -->
				<ul class="navbar-nav ms-4">
					<li class="nav-item">
						<a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/') }}" 
						href="{{ url('/') }}">
						Accueil
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link {{ request()->is('catalogue') ? 'active' : '' }}" href="{{ url('/catalogue') }}" 
						href="{{ url('/catalogue') }}">
						Catalogue
						</a>
					</li>

					<!-- CATEGORIES MEGA MENU -->
					<li class="nav-item dropdown kama-dropdown">
						<a class="nav-link dropdown-toggle" 
						href="#">
							Catégories
						</a>

						<div class="dropdown-menu kama-mega-menu">
							<div class="row">


								<div class="col-md-3">

									<h6>
										Romans
									</h6>

									<a href="#">
										Romance
									</a>

									<a href="#">
										Thriller
									</a>

									<a href="#">
										Fantastique
									</a>

								</div>

								<div class="col-md-3">

									<h6>
										Développement personnel
									</h6>

									<a href="#">
										Motivation
									</a>

									<a href="#">
										Leadership
									</a>

									<a href="#">
										Psychologie
									</a>

								</div>

								<div class="col-md-3">

									<h6>
										Business
									</h6>

									<a href="#">
										Entrepreneuriat
									</a>

									<a href="#">
										Finance
									</a>

									<a href="#">
										Marketing
									</a>


								</div>

								<div class="col-md-3">

									<h6>
										Littérature Africaine
									</h6>

									<a href="#">
										Contes Africains
									</a>

									<a href="#">
										Histoire
									</a>

									<a href="#">
										Culture
									</a>
								</div>
							</div>
						</div>
					</li>
				</ul>

				<!-- SEARCH BAR -->
				
				<!-- ACTIONS -->
				<ul class="navbar-nav align-items-center ms-auto">
					@auth
					<!-- Notification -->
					<li class="nav-item me-3">
						<a href="#" class="kama-icon">
							<i class="bi bi-bell"></i>
						</a>
					</li>

					<!-- USER -->

						@if (Auth::check())
						<!-- Profile dropdown START -->
						<li class="nav-item dropdown">
							<!-- Avatar -->
							<a class="avatar avatar-sm p-0" href="index.html#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
								<img class="avatar-img rounded-2" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
							</a>

							<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
								<!-- Profile info -->
								<li class="px-3 mb-3">
									<div class="d-flex align-items-center">
										<!-- Avatar -->
										<div class="avatar me-3">
											<img class="avatar-img rounded-circle shadow" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
										</div>
										<div>
											@if(Auth::user()->role->name === 'writer')
												<a class="h6 mt-2 mt-sm-0" href="{{ url('/writer/dashboard') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
											@elseif(Auth::user()->role->name === 'reader')
												<a class="h6 mt-2 mt-sm-0" href="{{ url('/reader/account') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
											@else (Auth::user()->role->name === 'admin')
												<a class="h6 mt-2 mt-sm-0" href="{{ url('/admin/dashboard') }}">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
											@endif
											<p class="small m-0">{{ Auth::user()->email }}</p>
										</div>
									</div>
								</li>

								@if(Auth::user()->role->name === 'writer')
									<li><a class="dropdown-item" href="{{ url('/writer/dashboard') }}"><i class="bi bi-house fa-fw me-2"></i>Tableau de bord</a></li>
									<li> <hr class="dropdown-divider"></li>
									<li>
										<form method="POST" action="{{ route('logout') }}">
											@csrf

											<button type="submit" class="nav-link text-danger bg-danger-soft-hover">
												<i class="fas fa-sign-out-alt fa-fw me-2"></i>
												Se deconnecter
											</button>
										</form>
									</li>
								@elseif(Auth::user()->role->name === 'reader')
									<!-- Links -->
									<li> <hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="{{ url('/reader/account') }}"><i class="bi bi-person-fill icon-red fa-fw me-2"></i>Mon Compte</a></li>
									<li><a class="dropdown-item" href="{{ url('/book') }}"><i class="bi bi-book icon-red fa-fw me-2"></i>Mes Livres</a></li>
									<li><a class="dropdown-item" href="{{ url('/wishlist') }}"><i class="bi bi-heart icon-red fa-fw me-2"></i>Ma Liste de Souhaits</a></li>
									<li><a class="dropdown-item" href="{{ url('/book') }}"><i class="bi bi-cart icon-red fa-fw me-2"></i>Mon panier</a></li>

									<li> <hr class="dropdown-divider"></li>
									<li>
										<form method="POST" action="{{ route('logout') }}">
											@csrf

											<button type="submit" class="nav-link text-danger bg-danger-soft-hover">
												<i class="fas fa-sign-out-alt fa-fw me-2"></i>
												Se deconnecter
											</button>
										</form>
									</li>
								@endif
									@else
									<!-- Profile dropdown END -->
									
									<li class="nav-item ms-0 ms-md-3">
										<a href="{{ url('/register') }}" class="btn btn-sm btn-register mb-0"><i class="fa-solid fa-right-to-bracket me-sm-2"></i><span class="d-none d-sm-inline">Inscription</span></a>
									</li>
							@endif
							</ul>
						</li>
						@else
						<li class="nav-item">
							<a href="{{url('/login')}}" 
							class="btn btn-outline-dark me-2">
								Connexion
							</a>
						</li>

						<li class="nav-item">
							<a href="{{url('/register')}}" 
							class="btn btn-danger kama-btn">
								Inscription
							</a>
						</li>
					@endauth
				</ul>
			</div>
		</div>
	</nav>
</header>
<!-- Header END -->