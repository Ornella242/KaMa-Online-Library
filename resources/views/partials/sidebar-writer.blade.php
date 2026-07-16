<!-- =======================
Menu item START -->
<div class="pt-4 writer-navbar">

    <div class="container-fluid">

        <div class="writer-header">

            <!-- TOP PROFILE -->
            <div class="writer-profile">

                <div class="writer-user">

                    <div class="writer-avatar">

                        <img src="{{ Auth::user()->avatar 
                            ? asset('storage/'.Auth::user()->avatar) 
                            : asset('assets/images/avatar/01.jpg') }}">

                    </div>


                    <div class="writer-details">

                        <span>
                            ESPACE AUTEUR
                        </span>

                        <h4>
                            {{ Auth::user()->firstname }} 
                            {{ Auth::user()->lastname }}
                        </h4>

                        <p>
                            Publiez vos œuvres et développez votre audience.
                        </p>

                    </div>

                </div>

				<div class="writer-actions">
					<!-- Notification -->
					@php
						$notifications = auth()->user()
							->unreadNotifications()
							->latest()
							->take(5)
							->get();
					@endphp

					<div class="writer-notification dropdown">

						<a href="#"
						class="notification-btn"
						data-bs-toggle="dropdown"
						aria-expanded="false">

							<i class="bi bi-bell"></i>


							@if($notifications->count() > 0)

								<span class="notification-dot"></span>

							@endif


						</a>



						<div class="dropdown-menu dropdown-menu-end notification-menu">


							<div class="notification-header">

								<h6>
									Notifications

									@if($notifications->count())

										<span>
											{{ $notifications->count() }}
										</span>

									@endif

								</h6>


								<form action="{{ route('notifications.clear') }}"
									method="POST">

									@csrf
									@method('DELETE')


									<button>
										Effacer
									</button>


								</form>


							</div>




							<div class="notification-body">


								@forelse($notifications as $notification)


									<a href="{{ $notification->data['url'] ?? '#' }}"
									class="notification-item">


										<strong>
											{{ $notification->data['title'] ?? 'Notification' }}
										</strong>


										<p>
											{{ $notification->data['message'] ?? '' }}
										</p>


										<small>
											{{ $notification->created_at->diffForHumans() }}
										</small>


									</a>


								@empty


									<div class="empty-notification">

										<i class="bi bi-bell-slash"></i>

										<p>
											Aucune notification
										</p>

									</div>


								@endforelse


							</div>


						</div>


					</div>



					<!-- Add book button -->


					<a href="{{ url('writer/books/create') }}"
					class="writer-create">

						<i class="bi bi-plus-circle"></i>

						Ajouter un livre

					</a>


				</div>


            </div>

            <!-- MOBILE BUTTON -->

            <button class="writer-mobile-toggle d-xl-none"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#dashboardMenu">

                <i class="bi bi-list"></i>
                Menu auteur

            </button>


            <!-- NAVIGATION -->

            <div class="offcanvas-xl offcanvas-end"
                 id="dashboardMenu">
                <div class="offcanvas-header d-xl-none">
                    <h5>
                        Navigation
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="offcanvas">
                    </button>
                </div>

                <div class="offcanvas-body p-0">
                    <nav class="writer-navigation">
                        <a href="{{ url('/writer/dashboard') }}"
                           class="{{ request()->is('writer/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house"></i>
                            Tableau de bord
                        </a>

                        <a href="{{ url('/writer/books') }}"
                           class="{{ request()->is('writer/books') ? 'active' : '' }}">
                            <i class="bi bi-book"></i>
                            Mes livres
                        </a>

                        <a href="{{ url('/writer/revenues') }}"
                           class="{{ request()->is('writer/revenues') ? 'active' : '' }}">
                            <i class="bi bi-currency-dollar"></i>
                            Revenus
                        </a>

                        <a href="{{ url('/writer/reviews') }}"
                           class="{{ request()->is('writer/reviews') ? 'active' : '' }}">
                            <i class="bi bi-chat-left-text"></i>
                            Avis
                        </a>

                        <a href="{{ url('/writer/activities') }}"
                           class="{{ request()->is('writer/activities') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i>
                            Notifications
                        </a>

                        <a href="{{ url('/writer/settings') }}"
                           class="{{ request()->is('writer/settings') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i>
                            Paramètres
                        </a>
                    </nav>

                </div>

            </div>


        </div>

    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
<!-- =======================
Menu item END -->