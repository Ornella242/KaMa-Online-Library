<!DOCTYPE html>
<html lang="en">
<head>
	<title>KaMa Afrika, Online Library</title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="Booking - Multipurpose Online Booking Theme">

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')
 
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
		    var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})

				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})

			showActiveTheme(getPreferredTheme())

			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})

			}
		})
		
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/css/apexcharts.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tiny-slider/tiny-slider.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/glightbox/css/glightbox.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/choices/css/choices.min.css')}}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/stepper/css/bs-stepper.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/quill/css/quill.snow.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/dropzone/css/dropzone.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip/dist/css/page-flip.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}">

</head>

<body>

<!-- **************** MAIN CONTENT START **************** -->
<main>
	
	<!-- Sidebar START -->
	
    <nav class="navbar sidebar navbar-expand-xl navbar-light admin-dashboard">

        <!-- Logo -->
       <div class="d-flex align-items-center justify-content-center px-3 kama-sidebar-logo">

            <a class="navbar-brand m-0" href="">
                
                <img class="light-mode-item kama-logo"
                    src="{{ asset('assets/images/logo.svg') }}"
                    alt="KaMa">

                <img class="dark-mode-item kama-logo"
                    src="{{ asset('assets/images/logo-light.svg') }}"
                    alt="KaMa">

            </a>

        </div>


        <div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100"
            data-bs-backdrop="true"
            tabindex="-1"
            id="offcanvasSidebar">


            <div class="offcanvas-body sidebar-content d-flex flex-column pt-4">
                <ul class="navbar-nav flex-column" id="navbar-sidebar">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href=""
                        class="nav-link active">
                            <i class="bi bi-grid-fill me-2"></i>
                            Dashboard
                        </a>
                    </li>


                    <li class="nav-item ms-2 my-3 text-uppercase small fw-bold">
                        Gestion KaMa
                    </li>

                    <!-- Users -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/users') }}">
                            <i class="bi bi-people-fill me-2"></i>
                            Utilisateurs
                        </a>

                        <ul class="nav collapse flex-column"
                            id="collapseUsers"
                            data-bs-parent="#navbar-sidebar">
                            <li class="nav-item">
                                <a class="nav-link"
                                href="#">
                                    Lecteurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                href="#">
                                    Écrivains
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                href="#">
                                    Administrateurs
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Books -->
                    <li class="nav-item">
                        <a class="nav-link"
                        data-bs-toggle="collapse"
                        href="#collapseBooks">
                            <i class="bi bi-book-half me-2"></i>
                            Livres
                        </a>

                        <ul class="nav collapse flex-column"
                            id="collapseBooks"
                            data-bs-parent="#navbar-sidebar">

							<li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.books.create') }}">
                                    Ajouter un livre
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.books.index') }}">
                                    Mes livres
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.books.all') }}">
                                    Tous les livres
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.books.editorial.queue') }}">
                                    Validation des livres
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- Revues et commentaires -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-star-fill me-2"></i>
                            Commentaires
                        </a>
                    </li>

                    <!-- Categories -->
                    <li class="nav-item">
						<a class="nav-link" href="{{ route('admin.categories.index') }}">
							<i class="bi bi-tags-fill me-2"></i>
							Catégories
						</a>
					</li>

                    <!-- Payments -->
                    <li class="nav-item">
                        <a class="nav-link"
                        data-bs-toggle="collapse"
                        href="#collapseReports">
                            <i class="bi bi-wallet2 me-2"></i>
                            Paiements
                        </a>
                         <ul class="nav collapse flex-column"
                            id="collapseReports"
                            data-bs-parent="#navbar-sidebar">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    Mon portefeuille
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    Portefeuille ecrivain
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Advertisements -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-megaphone-fill me-2"></i>
                            Publicités
                        </a>
                    </li>

                    <!-- Orders -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-cart-check-fill me-2"></i>
                            Abonnements
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings') }}">
                            <i class="bi bi-gear-fill me-2"></i>
                            Paramètres
                        </a>
                    </li>
                </ul>

                <!-- Footer -->

                <div class="mt-auto p-3 border-top">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-danger-soft w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
	<!-- Sidebar END -->
	
	<!-- Page content START -->
	<div class="page-content">
	
		<!-- Top bar START -->
		<nav class="navbar top-bar navbar-light py-0 py-xl-3">
			<div class="container-fluid p-0">
				<div class="d-flex align-items-center w-100">
	
					<!-- Logo START -->
					<div class="d-flex align-items-center d-xl-none">
						<a class="navbar-brand" href="index.html">
							<img class="navbar-brand-item h-40px" src="{{ asset('assets/images/logo.svg')}}" alt="">
						</a>
					</div>
					<!-- Logo END -->
	
					<!-- Toggler for sidebar START -->
					<div class="navbar-expand-xl sidebar-offcanvas-menu">
						<button class="navbar-toggler me-auto p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-expanded="false" aria-label="Toggle navigation" data-bs-auto-close="outside">
							<i class="bi bi-list text-primary fa-fw" data-bs-target="#offcanvasMenu"></i>
						</button>
					</div>
					<!-- Toggler for sidebar END -->
					
					<!-- Top bar left -->
					<div class="kama-navbar-welcome">

						<h5 class="welcome-badge">
							Centre d'administration KaMa
						</h5>

					</div>
					<!-- Top bar left END -->
					
					<!-- Top bar right START -->
					<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">
						<!-- Dark mode options START -->
						<li class="nav-item dropdown ms-3">
							<button class="nav-notification lh-0 btn btn-light p-0 mb-0" id="bd-theme"
							type="button"
							aria-expanded="false"
							data-bs-toggle="dropdown"
							data-bs-display="static">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half fa-fw theme-icon-active" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
									<use href="#"></use>
								</svg>
							</button>

							<ul class="dropdown-menu min-w-auto dropdown-menu-end" aria-labelledby="bd-theme">
								<li class="mb-1">
									<button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">
										<svg width="16" height="16" fill="currentColor" class="bi bi-brightness-high-fill fa-fw mode-switch me-1" viewBox="0 0 16 16">
											<path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
											<use href="#"></use>
										</svg>Light						
									</button>
								</li>
								<li class="mb-1">
									<button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars-fill fa-fw mode-switch me-1" viewBox="0 0 16 16">
											<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
											<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
											<use href="#"></use>
										</svg>Dark
									</button>
								</li>
								<li>
									<button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half fa-fw mode-switch" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
											<use href="#"></use>
										</svg>Auto
									</button>
								</li>
							</ul>
						</li>
						<!-- Dark mode options END-->

						<ul class="navbar-nav flex-row align-items-center gap-3 navbar-user-actions">
							<!-- Notification dropdown START -->
						@php
							$notifications = auth()->user()
								->unreadNotifications()
								->latest()
								->take(5)
								->get();

						@endphp
						<li class="nav-item dropdown ms-3">
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
													<a href="{{ $notification->data['url'] }}"
													class="list-group-item list-group-item-action rounded notif-unread border-0 mb-1 p-3">

													<h6 class="mb-2">{{ $notification->data['title'] }}</h6>

														<p class="mb-0 small">

														{{ $notification->data['message'] }}

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
						<!-- Notification dropdown END -->
		
						<!-- Profile dropdown START -->
						<li class="nav-item ms-3 dropdown">
							<!-- Avatar -->
							<a class="avatar avatar-sm p-0" href="admin-dashboard.html#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
								<img class="avatar-img rounded-2" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
							</a>
		
							<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
								<!-- Profile info -->
								<li class="px-3 mb-3">
									<div class="d-flex align-items-center">
										<!-- Avatar -->
										<div class="avatar me-3">
											<img class="avatar-img rounded-circle shadow" src="assets/images/avatar/01.jpg" alt="avatar">
										</div>
										<div>
											<a class="h6 mt-2 mt-sm-0" href="admin-dashboard.html#">Lori Ferguson</a>
											<p class="small m-0">example@gmail.com</p>
										</div>
									</div>
								</li>
		
								<!-- Links -->
								<li> <hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="admin-dashboard.html#"><i class="bi bi-bookmark-check fa-fw me-2"></i>My Bookings</a></li>
								<li><a class="dropdown-item" href="admin-dashboard.html#"><i class="bi bi-heart fa-fw me-2"></i>My Wishlist</a></li>
								<li><a class="dropdown-item" href="admin-dashboard.html#"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
								<li><a class="dropdown-item" href="admin-dashboard.html#"><i class="bi bi-info-circle fa-fw me-2"></i>Help Center</a></li>
								<li><a class="dropdown-item bg-danger-soft-hover" href="admin-dashboard.html#"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
							</ul>
						</li>
						<!-- Profile dropdown END -->
						</ul>
						
					</ul>
					<!-- Top bar right END -->
				</div>
			</div>
		</nav>
		<!-- Top bar END -->
	
		<!-- Page main content START -->
		<div class="page-content-wrapper p-xxl-4">
	
			 @yield('admin-content')
	
		</div>
		<!-- Page main content END -->
	</div>
	<!-- Page content END -->
	
	</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- Bootstrap JS -->
<script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>

<!-- Vendor -->
<script src="{{asset('assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js')}}"></script>
<script src="{{asset('assets/vendor/apexcharts/js/apexcharts.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/vendor/tiny-slider/tiny-slider.js') }}"></script>
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
<script src="{{ asset('assets/vendor/apexcharts/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/dropzone/js/dropzone.js') }}"></script>


<script>
    document.querySelectorAll('.toggle-password').forEach(icon => {

        icon.addEventListener('click', function () {

            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);


            if (!input) {
                console.error("Input introuvable :", targetId);
                return;
            }


            if (input.type === "password") {

                input.type = "text";

                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");

            } else {

                input.type = "password";

                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");

            }

        });

    });
</script>

<script>

    let timeout;

    $('#searchUsers').on('keyup', function(){

        clearTimeout(timeout);

        timeout = setTimeout(function(){

            reloadUsers();

        },300);

    });


    $('#userTypeFilter').on('change', function(){

        reloadUsers();

    });


    function reloadUsers(){

        let search = $('#searchUsers').val();

        let type = $('#userTypeFilter').val();

        let url =
            "{{ route('admin.users') }}" +
            "?search="+encodeURIComponent(search)+
            "&type="+type;

        window.location.href = url;

    }

</script>

<script>
    const deleteModal = document.getElementById('deleteUserModal');

	if(deleteModal){
			deleteModal.addEventListener('show.bs.modal', function (event) {

			const button = event.relatedTarget;

			const userId = button.dataset.userId;
			const userName = button.dataset.userName;

			document.getElementById('deleteUserName').textContent = userName;

			document.getElementById('deleteUserForm').action =
				`/admin/users/${userId}`;

		});
	}
</script>


<!-- ThemeFunctions -->
<script src="{{asset('assets/js/functions.js')}}"></script>

	<script>

		document.addEventListener('DOMContentLoaded', function () {

			const category = document.getElementById('category_id');
			const subcategory = document.getElementById('subcategory_id');

			if(category && subcategory){
				category.addEventListener('change', function () {
					let categoryId = this.value;
					subcategory.innerHTML = `
						<option value="">
							Choisir une sous-catégorie
						</option>
					`;


					if(categoryId){

						fetch(`/writer/categories/${categoryId}/subcategories`)

						.then(response => response.json())

						.then(data => {


							data.forEach(item => {

								subcategory.innerHTML += `
									<option value="${item.id}">
										${item.name}
									</option>
								`;

							});


						});

					}


				});
			}

		});
    </script>

	<script>

		const coverInput = document.getElementById('coverImageInput');

		const coverImage = document.getElementById('coverPreviewImage');

		const placeholder = document.getElementById('coverPlaceholder');

		if(coverInput){
			coverInput.addEventListener('change', function(e){
				const file = e.target.files[0];
				if(file){
					const reader = new FileReader();


					reader.onload = function(event){


						coverImage.src = event.target.result;


						coverImage.style.display = "block";


						placeholder.style.display = "none";
					};
					reader.readAsDataURL(file);
				}
			});
		}

				const typeInputs = document.querySelectorAll('input[name="type"]');
				const uploadTitle = document.getElementById('uploadTitle');
				const uploadDescription = document.getElementById('uploadDescription');
				const uploadIcon = document.getElementById('uploadIcon');
				const acceptedFormat = document.getElementById('acceptedFormat');
				const bookInput = document.getElementById('bookFileInput');

			if(typeInputs.length && uploadTitle && uploadDescription){
				typeInputs.forEach(input => {

					input.addEventListener('change', function () {

						if (this.value === 'ebook') {

							uploadTitle.innerText = 'Téléverser votre ebook';

							uploadDescription.innerText =
								'Sélectionnez le fichier PDF de votre ebook.';

							uploadIcon.innerHTML =
								'<i class="bi bi-file-earmark-pdf-fill"></i>';

							acceptedFormat.innerText = 'PDF uniquement';

							bookInput.accept = '.pdf';

							bookInput.name = 'ebook_file';

							bookInput.value = '';

						} else {

							uploadTitle.innerText = 'Téléverser votre livre audio';

							uploadDescription.innerText =
								'Sélectionnez le fichier MP3 de votre livre audio.';

							uploadIcon.innerHTML =
								'<i class="bi bi-headphones"></i>';

							acceptedFormat.innerText = 'MP3 uniquement';

							bookInput.accept = '.mp3,audio/mpeg';

							bookInput.name = 'audio_file';

							bookInput.value = '';

						}

					});

				});
			}


			function updateFileSummary(file){

					if(!file) return;


					document.getElementById("summary_file_name").textContent = file.name;


					let extension = file.name.split('.').pop().toUpperCase();


					document.getElementById("summary_file_type").textContent = extension;


					document.getElementById("summary_file_size").textContent =
						(file.size / 1024 / 1024).toFixed(2) + " MB";


					document.getElementById("summary_file_status").innerHTML =
						'<i class="bi bi-check-circle-fill"></i> Prêt';

			}

			const bookFileInput = document.getElementById('bookFileInput');
				if(bookFileInput){

					bookFileInput.addEventListener('change', function(){

						const file = this.files[0];

						if(file){

							updateFileSummary(file);

						}

					});

				}
				const publicationTypeInputs = document.querySelectorAll('input[name="type"]');

				const pagesField = document.getElementById('pagesField');
				const durationField = document.getElementById('durationField');

				const pagesInput = document.getElementById('pagesInput');
				const durationInput = document.getElementById('durationInput');


				publicationTypeInputs.forEach(input => {

					input.addEventListener('change', function(){


						if(this.value === "ebook"){


							pagesField.style.display = "block";

							durationField.style.display = "none";


							pagesInput.required = true;

							durationInput.required = false;


							durationInput.value = "";


						}


						else if(this.value === "audio"){


							pagesField.style.display = "none";

							durationField.style.display = "block";


							pagesInput.required = false;

							durationInput.required = true;


							pagesInput.value = "";


						}


					});

				});



				// Affichage initial
				const checkedType = document.querySelector('input[name="type"]:checked');


				if(checkedType){

					checkedType.dispatchEvent(new Event('change'));

				}
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {


		function updateBookSummary(){
			const summaryBox = document.querySelector("#summary_title");
			if(!summaryBox){
				return;
			}

			// =========================
			// TITRE
			// =========================

			let title = document.querySelector('[name="title"]')?.value;
			const summaryTitle = document.querySelector("#summary_title");

				if(summaryTitle){

					summaryTitle.innerText =
						title || "Titre du livre";

				}

			// =========================
			// CATEGORIE
			// =========================

			let category = document.querySelector('[name="category_id"]');
			if(category){

				document.querySelector("#summary_category").innerText =
					category.options[category.selectedIndex]?.text || "Catégorie";

			}
			// =========================
			// SOUS-CATEGORIE
			// =========================

			let subcategory = document.querySelector('[name="subcategory_id"]');

			if(subcategory){

				document.querySelector("#summary_subcategory").innerText =
					subcategory.options[subcategory.selectedIndex]?.text || "Sous-catégorie";

			}
			// =========================
			// LANGUE
			// =========================

			let language = document.querySelector('[name="language"]')?.value;
			const summaryLanguage = document.querySelector("#summary_language");
			if(summaryLanguage){

				summaryLanguage.innerText =
					language || "Langue";

			}

			// =========================
			// TYPE
			// =========================

			let type = document.querySelector('[name="type"]:checked');
			if(type){
				document.querySelector("#summary_type").innerText =
					type.value === "ebook"
					? "Ebook"
					: "Livre audio";

			}

			// =========================
			// PRIX
			// =========================

			let price = document.querySelector('[name="price"]')?.value;
			const summaryPrice = document.querySelector("#summary_price");
			if(summaryPrice){

				summaryPrice.innerText =
					price ? price + " $" : "0 $";

			}

			// =========================
			// PAGES / DUREE AUDIO
			// =========================

			const publicationTypeInputs = document.querySelectorAll('input[name="type"]');

			const pagesField = document.getElementById('pagesField');
			const durationField = document.getElementById('durationField');

			const pagesInput = document.getElementById('pagesInput');
			const durationInput = document.getElementById('durationInput');


			// Summary

			const summaryPagesBox = document.getElementById('summary_pages_box');
			const summaryDurationBox = document.getElementById('summary_duration_box');

			const summaryPages = document.getElementById('summary_pages');
			const summaryDuration = document.getElementById('summary_duration');



		function updateBookTypeDisplay(type){


			if(type === "ebook"){


				// Formulaire

				if(pagesField){
					pagesField.style.display = "block";
				}

				if(durationField){
					durationField.style.display = "none";
				}


				if(pagesInput){

					pagesInput.required = true;

				}
				if(durationInput){

					durationInput.required = false;
					durationInput.value = "";

				}
				// Résumé

				if(summaryPagesBox){

					summaryPagesBox.style.display = "block";

				}
				if(summaryDurationBox){
					summaryDurationBox.style.display = "none";
				}
			}
			else if(type === "audio"){
				// Formulaire
				if(pagesField){

					pagesField.style.display = "none";

				}
				if(durationField){
					durationField.style.display = "block";

				}
				if(pagesInput){

					pagesInput.required = false;
					pagesInput.value = "";

				}

				if(durationInput){

					durationInput.required = true;

				}

				// Résumé

				if(summaryPagesBox){

					summaryPagesBox.style.display = "none";

				}


				if(summaryDurationBox){

					summaryDurationBox.style.display = "block";

				}


			}


		}



		// Changement Ebook / Audio

		publicationTypeInputs.forEach(input => {


			input.addEventListener('change', function(){


				updateBookTypeDisplay(this.value);


			});


		});



		// Affichage initial

		const checkedType = document.querySelector('input[name="type"]:checked');


		if(checkedType){

			updateBookTypeDisplay(checkedType.value);

		}



		// =========================
		// UPDATE SUMMARY PAGES
		// =========================

		if(pagesInput){


			pagesInput.addEventListener('input', function(){


				if(summaryPages){

					summaryPages.innerText = this.value || "0";

				}


			});


		}



					// =========================
					// UPDATE SUMMARY DUREE
					// =========================

					if(durationInput){


						durationInput.addEventListener('input', function(){


							if(summaryDuration){

								summaryDuration.innerText = this.value || "00:00:00";

							}


						});


					}
					
					// =========================
					// ANNEE
					// =========================

					let year = document.querySelector('[name="publication_year"]')?.value;
					const summaryYear = document.querySelector("#summary_year");

					if(summaryYear){

						summaryYear.innerText =
							year || "----";

					}

					// =========================
					// DESCRIPTION
					// =========================

					let description =
						document.querySelector('[name="short_description"]')?.value;
						const summaryShortDescription = document.querySelector("#summary_short_description");

						if(summaryShortDescription){

							summaryShortDescription.innerText =
								description || "Aucune description disponible.";

						}

					// =========================
					// COUVERTURE
					// =========================

					let cover =
						document.querySelector('[name="cover_image"]');
					if(cover && cover.files.length > 0){
						let reader = new FileReader();
						reader.onload = function(e){
							document.querySelector("#summary_cover").src =
								e.target.result;

						}
						reader.readAsDataURL(cover.files[0]);
					}
				}
					document.addEventListener("input", function(e){


						if(
							e.target.matches(
								'[name="title"], [name="price"], [name="pages"], [name="publication_year"], [name="short_description"]'
							)
						){

							updateBookSummary();

						}
					});

					document.addEventListener("change", function(e){


						if(
							e.target.matches(
								'[name="category_id"], [name="subcategory_id"], [name="language"], [name="type"], [name="cover_image"]'
							)
						){
							updateBookSummary();
						}
					});

					// Chargement initial

					updateBookSummary();
				});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
	    const editor = document.querySelector(".quilleditor");
			if(editor){
				const quill = new Quill(editor, {
					modules: {
						toolbar: '.quilltoolbar'
					},

					theme: 'snow'

				});
				const existingDescription = document.getElementById('long_description').value;

					if (existingDescription) {
						quill.root.innerHTML = existingDescription;
					}

				quill.on('text-change', function(){


					document.querySelector("#long_description").value =
						quill.root.innerHTML;


				});

			}

		});
	</script>

	<script>

		const bookTypes = document.querySelectorAll('input[name="type"]');

		const previewType = document.getElementById('preview_type');

		const previewTypeContainer = document.getElementById('previewTypeContainer');

		const textPreview = document.getElementById('textPreview');

		const pagesPreview = document.getElementById('pagesPreview');

		function updatePreviewFields() {

			const selectedType = document.querySelector('input[name="type"]:checked');

			if (!selectedType) return;

			// =============================
			// LIVRE AUDIO
			// =============================

			if (selectedType.value === 'audio') {

				previewTypeContainer.classList.add('d-none');

				textPreview.classList.remove('d-none');

				pagesPreview.classList.add('d-none');

				// On force toujours le type texte
				previewType.value = 'text';

			}

			// =============================
			// EBOOK
			// =============================

			else {

				previewTypeContainer.classList.remove('d-none');

				if (previewType.value === 'pages') {

					textPreview.classList.add('d-none');

					pagesPreview.classList.remove('d-none');

				} else {

					textPreview.classList.remove('d-none');

					pagesPreview.classList.add('d-none');

				}

			}

		}


		// Changement Ebook / Audio
		bookTypes.forEach(type => {

			type.addEventListener('change', updatePreviewFields);

		});


		// Changement du type d'aperçu
			if(previewType){

				previewType.addEventListener('change', updatePreviewFields);

			}


		// Initialisation
		updatePreviewFields();

	</script>
    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>

			document.querySelectorAll('.delete-book-btn').forEach(button => {
				button.addEventListener('click', function(){
					const form = this.closest('.delete-book-form');
					Swal.fire({

						title: 'Supprimer ce livre ?',

						text: "Cette action est définitive. Le fichier et la couverture seront supprimés.",

						icon: 'warning',

						showCancelButton: true,

						confirmButtonText: 'Oui, supprimer',

						cancelButtonText: 'Annuler',

						reverseButtons: true,

						customClass: {

							popup: 'rounded-4',

							confirmButton: 'btn btn-danger px-4',

							cancelButton: 'btn btn-light px-4'

						},
						buttonsStyling: false
					}).then((result)=>{

						if(result.isConfirmed){

							form.submit();

						}
					});
				});
			});

	</script>

</body>
</html>