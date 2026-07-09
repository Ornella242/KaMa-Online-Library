<!-- =======================
Menu item START -->
<section class="pt-4">
	<div class="container">
		<div class="card rounded-3 border p-3 pb-2 bg-table-red">
			<!-- Avatar and info START -->
			<div class="d-sm-flex align-items-center">
				<div class="avatar avatar-xl mb-2 mb-sm-0">
                    <img src="{{ Auth::user()->avatar 
                        ? asset('storage/'.Auth::user()->avatar) 
                        : asset('assets/images/avatar/01.jpg') }}"
                        class="avatar-img rounded-circle border border-white border-3 shadow">
				</div>
				<h4 class="mb-2 mb-sm-0 ms-sm-3"><span class="fw-light">Bienvenue</span> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h4>
				<a href="{{ url('writer/books/create') }}" class="btn btn-sm btn-primary-soft mb-0 ms-auto flex-shrink-0"><i class="bi bi-plus-lg fa-fw me-2"></i>Ajouter un nouveau livre</a>
			</div>
			<!-- Avatar and info START -->
			
			<!-- Responsive navbar toggler -->
			<button class="btn btn-active w-100 d-block d-xl-none mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboardMenu" aria-controls="dashboardMenu">
				<i class="bi bi-list"></i> Dashboard Menu
			</button>

			<!-- Nav links START -->
			<div class="offcanvas-xl offcanvas-end mt-xl-3" tabindex="-1" id="dashboardMenu">
				<div class="offcanvas-header border-bottom p-3">
					<h5 class="offcanvas-title">Menu</h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#dashboardMenu" aria-label="Close"></button>
				</div>
				<!-- Offcanvas body -->
				<div class="offcanvas-body p-3 p-xl-0">
					<!-- Nav item -->
					<div class="navbar navbar-expand-xl">
						<ul class="navbar-nav navbar-offcanvas-menu">

							<li class="nav-item"> <a class="nav-link {{ request()->is('writer/dashboard') ? 'active' : '' }}" href="{{ url('/writer/dashboard') }}"><i class="bi bi-house-door fa-fw me-1"></i>Dashboard</a>	</li>

							<li class="nav-item"> <a class="nav-link {{ request()->is('writer/books') ? 'active' : '' }}" href="{{ url('/writer/books') }}"><i class="bi bi-journals fa-fw me-1"></i>Ma bibliothèque</a> </li>
							<li class="nav-item"> <a class="nav-link {{ request()->is('writer/revenues') ? 'active' : '' }}" href="{{ url('/writer/revenues') }}"><i class="bi bi-graph-up-arrow fa-fw me-1"></i>Ventes</a>	</li>
							<li class="nav-item"> <a class="nav-link {{ request()->is('writer/reviews') ? 'active' : '' }}" href="{{ url('/writer/reviews') }}"><i class="bi bi-star fa-fw me-1"></i>Revues</a></li>
							<li class="nav-item"> <a class="nav-link {{ request()->is('writer/activities') ? 'active' : '' }}" href="{{ url('/writer/activities') }}"><i class="bi bi-bell fa-fw me-1"></i>Activités</a> </li>
							<li> <a class="nav-link {{ request()->is('writer/settings') ? 'active' : '' }}" href="{{ url('/writer/settings') }}"><i class="bi bi-gear fa-fw me-1"></i>Paramètres</a></li>

						</ul>
					</div>
				</div>
			</div>
			<!-- Nav links END -->
		</div>
	</div>
</section>
<!-- =======================
Menu item END -->