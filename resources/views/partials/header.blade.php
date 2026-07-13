
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

						<a class="nav-link dropdown-toggle" href="#">
							Catégories
						</a>


						<div class="dropdown-menu kama-mega-menu">
							<div class="row">
								@foreach($categories as $category)
									<div class="col-md-3">
										<a href="{{ route('catalogue', ['category' => $category->id]) }}"
										class="text-decoration-none">
											<h6>
												{{ $category->name }}
											</h6>
										</a>
									</div>
								@endforeach
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