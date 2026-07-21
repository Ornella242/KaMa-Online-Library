<header class="kama-header">

	<div class="container-fluid px-lg-5">
		<!--TOP HEADER-->

		<div class="kama-header-top">
			<!-- LOGO -->
			<a href="{{ url('/') }}"
			class="kama-logo-wrapper">
				<img src="{{ asset('assets/images/logo.svg') }}"
					class="kama-logo"
					alt="KaMa">
			</a>

			<nav class="kama-main-nav">
				<a href="{{ url('/') }}"
				class="{{ request()->is('/') ? 'active':'' }}">
					Accueil
				</a>

				<a href="{{ route('catalogue') }}"
				class="{{ request()->is('catalogue') ? 'active':'' }}">
					Catalogue
				</a>

				<a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active':'' }}">
					A propos
				</a>

				<a href="{{ route('faq') }}" class="{{ request()->is('faq') ? 'active':'' }}">
					FAQ
				</a>
			</nav>

			<!-- SEARCH -->
			<form action="{{ route('catalogue') }}"
				method="GET"
				class="kama-search">
				<i class="bi bi-search"></i>

				<input type="search"
					name="search"
					placeholder="Rechercher un livre...">
			</form>

			<!-- ACTIONS -->


			<div class="kama-header-actions">


				@guest


				<a href="{{url('/login')}}"
				class="kama-login">

					Connexion

				</a>



				<a href="{{url('/register')}}"
				class="kama-register">

					Inscription

				</a>



				@else



				<a href="#"
				class="kama-icon">

					<i class="bi bi-bell"></i>

				</a>



				<img src="{{ Auth::user()->avatar 
				? asset('storage/'.Auth::user()->avatar)
				: asset('assets/images/avatar/01.jpg') }}"
				class="kama-avatar">


				@endguest



			</div>

			<!-- MOBILE BUTTON -->
			<button class="kama-mobile-toggle"
					data-bs-toggle="offcanvas"
					data-bs-target="#mobileMenu">
				<i class="bi bi-list"></i>
			</button>
		</div>
		
		<!-- CATEGORY NAV DESKTOP-->

		<nav class="kama-category-nav">
			<ul>

				@foreach($categories->take(6) as $category)

				<li class="category-item">
					<a href="{{route('catalogue',['category'=>$category->id])}}">
						{{ $category->name }}
						@if($category->subcategories->count())

						<i class="bi bi-chevron-down"></i>
						@endif
					</a>


					@if($category->subcategories->count())

						<div class="subcategory-menu">
							@foreach($category->subcategories as $subcategory)
								<a href="{{route('catalogue',['subcategory'=>$subcategory->id])}}">
								{{ $subcategory->name }}
								</a>
							@endforeach
						</div>
					@endif
				</li>

				@endforeach


				@if($categories->count()>6)
					<li class="category-item">


						<a href="#">

						Plus

						<i class="bi bi-chevron-down"></i>


						</a>



						<div class="subcategory-menu">
							@foreach($categories->skip(6) as $category)
								<a href="{{route('catalogue',['category'=>$category->id])}}">
								{{ $category->name }}
								</a>
							@endforeach
						</div>
					</li>
				</ul>

				<!-- SEARCH BAR -->
				
				<!-- ACTIONS -->
				<ul class="navbar-nav align-items-center ms-auto">
					<li class="nav-item me-2">
						<a href="{{ route('cart.index') }}" class="nav-link position-relative px-2" title="Mon panier">
							<i class="bi bi-cart3 fs-5"></i>
							@if(($cartCount ?? 0) > 0)
								<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.65rem;">
									{{ $cartCount }}
								</span>
							@endif
						</a>
					</li>
					@auth
					<!-- Notification -->
						@php
							$notifications = auth()->user()
								->unreadNotifications()
								->latest()
								->take(5)
								->get();

						@endphp
						<ul class="navbar-nav flex-row align-items-center gap-3 navbar-user-actions">
							<li class="nav-item dropdown ">
							<!-- Notification button -->
							<a class="nav-notification btn btn-light p-0 mb-0" href="admin-dashboard.html#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
								<i class="bi bi-bell fa-fw"></i>
							</a>
							<!-- Notification dote -->
							@if($notifications->count() > 0)
								<span class="notif-badge animation-blink"></span>
							@endif
		
							<!-- Notification dropdown menu START -->
							<div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md shadow-lg p-0">
								<div class="card bg-transparent">
									<!-- Card header -->
									<div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
										<h6 class="m-0"> Notifications
											@if($notifications->count())

											<span class="badge bg-danger bg-opacity-10 text-danger ms-2">

											{{ $notifications->count() }}

											</span>

											@endif
										</h6>
										<form action="{{ route('notifications.clear') }}"
											method="POST">

											@csrf
											@method('DELETE')

											<button type="submit"
													class="btn text-red p-0 small">
												Clear all
											</button>

										</form>
									</div>
		
									<!-- Card body START -->
									<div class="card-body p-0">
										<ul class="list-group list-group-flush list-unstyled p-2">
											@forelse($notifications as $notification)
												<li>
													<a href="{{ $notification->data['url'] ?? '#' }}"
													class="list-group-item list-group-item-action rounded notif-unread border-0 mb-1 p-3">

													<h6 class="mb-2">{{ $notification->data['title'] ?? 'Notification' }}</h6>

														<p class="mb-0 small">

														{{ $notification->data['message'] ?? 'Message' }}

														</p>


														<span>

														{{ $notification->created_at->diffForHumans() }}

														</span>


													</a>

												</li>
											@empty

											<li class="text-center p-4">

												<i class="bi bi-bell-slash fs-4"></i>

												<p class="mb-0 mt-2">

												Aucune notification

												</p>

											</li>

											@endforelse
										</ul>
									</div>
									<!-- Card body END -->
		
									<!-- Card footer -->
									<div class="card-footer bg-transparent text-center border-top">
										<a href="admin-dashboard.html#" class="btn btn-sm text-red mb-0 p-0">Voir toutes les activités</a>
									</div>
								</div>
							</div>
							<!-- Notification dropdown menu END -->
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
									<li><a class="dropdown-item" href="{{ route('reader.account') }}"><i class="bi bi-person-fill icon-red fa-fw me-2"></i>Mon Compte</a></li>
									<li><a class="dropdown-item" href="{{ route('reader.books') }}"><i class="bi bi-book icon-red fa-fw me-2"></i>Mes Livres</a></li>
									<li><a class="dropdown-item" href="{{ route('reader.wishlist') }}"><i class="bi bi-heart icon-red fa-fw me-2"></i>Ma Liste de Souhaits</a></li>
									<li><a class="dropdown-item" href="{{ route('cart.index') }}"><i class="bi bi-cart icon-red fa-fw me-2"></i>Mon panier @if(($cartCount ?? 0) > 0)<span class="badge bg-danger ms-1">{{ $cartCount }}</span>@endif</a></li>

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

			<a href="{{url('/register')}}">
			Inscription
			</a>
		</div>
	</div>


</div>