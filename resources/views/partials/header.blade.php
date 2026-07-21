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



				<!-- Notifications -->
<div class="dropdown">
    <a href="#"
        class="kama-icon"
        id="notificationDropdown"
        data-bs-toggle="dropdown"
        aria-expanded="false">

        <i class="bi bi-bell"></i>

        @if(auth()->user()->unreadNotifications->count())
            <span class="notification-badge">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow border-0">

        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <strong>Notifications</strong>
            <small>{{ auth()->user()->unreadNotifications->count() }}</small>
        </div>

        <div class="notification-list">

            @forelse(auth()->user()->notifications->take(5) as $notification)

                <a href="{{ $notification->data['url'] ?? '#' }}"
                    class="dropdown-item notification-item">

                    <div class="fw-semibold">
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </div>

                    <small class="text-muted">
                        {{ $notification->data['message'] ?? '' }}
                    </small>

                    <div class="small text-secondary mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>

                </a>

            @empty

                <div class="dropdown-item text-center text-muted py-3">
                    Aucune notification
                </div>

            @endforelse

        </div>

        <div class="dropdown-divider"></div>

        <a href="{{ route('notifications.index') }}"
            class="dropdown-item text-center fw-semibold">
            Voir toutes les notifications
        </a>

    </div>
</div>


<!-- Avatar -->
<div class="dropdown">

    <a href="#"
        data-bs-toggle="dropdown"
        aria-expanded="false">

        <img src="{{ Auth::user()->avatar
            ? asset('storage/'.Auth::user()->avatar)
            : asset('assets/images/avatar/01.jpg') }}"
            class="kama-avatar">
    </a>

    <div class="dropdown-menu dropdown-menu-end profile-dropdown shadow border-0">

        <div class="dropdown-header text-center">

            <img src="{{ Auth::user()->avatar
                ? asset('storage/'.Auth::user()->avatar)
                : asset('assets/images/avatar/01.jpg') }}"
                class="profile-avatar">

            <div class="fw-bold mt-2">
                {{ Auth::user()->name }}
            </div>

            <small class="text-muted">
                {{ Auth::user()->email }}
            </small>

        </div>

        <div class="dropdown-divider"></div>

					<a class="dropdown-item"
						href="">

						<i class="bi bi-speedometer2 me-2"></i>

						Tableau de bord

					</a>

					<form action="{{ route('logout') }}"
						method="POST">

						@csrf

						<button class="dropdown-item text-danger">

							<i class="bi bi-box-arrow-right me-2"></i>

							Déconnexion

						</button>

					</form>

				</div>

			</div>

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
				@endif

			</ul>
		</nav>

	</div>
</header>



<!-- ==============================
 MOBILE OFFCANVAS
================================ -->


<div class="offcanvas offcanvas-end kama-mobile-menu"
     tabindex="-1"
     id="mobileMenu">

	<div class="offcanvas-header">
		<h5>KaMa</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
	</div>

	<div class="offcanvas-body">

		<a href="{{url('/')}}"> Accueil</a>
		<a href="{{route('catalogue')}}"> Catalogue </a>
		<a href="#"> A propos </a>
		<a href="#">FAQ </a>

		<button class="mobile-category-btn"
		data-bs-toggle="collapse"
		data-bs-target="#mobileCategories">
		Catégories

		<i class="bi bi-chevron-down"></i>
		</button>



		<div class="collapse" id="mobileCategories">
			@foreach($categories as $category)

			<a class="mobile-cat"
			href="{{route('catalogue',['category'=>$category->id])}}">

				{{ $category->name }}

			</a>

			@endforeach
		</div>


		<div class="mobile-actions">
			<a href="{{url('/login')}}">
			Connexion
			</a>

			<a href="{{url('/register')}}">
			Inscription
			</a>
		</div>
	</div>


</div>