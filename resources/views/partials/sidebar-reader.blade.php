
			<!-- Sidebar START -->
			<div class="col-lg-4 col-xl-3">
				<!-- Responsive offcanvas body START -->
				<div class="offcanvas-lg offcanvas-end" tabindex="-1" id="offcanvasSidebar" >
					<!-- Offcanvas header -->
					<div class="offcanvas-header justify-content-end pb-2">
						<button  type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
					</div>

					<!-- Offcanvas body -->
					<div class="offcanvas-body p-3 p-lg-0">
						<div class="card bg-light w-100">

							<!-- Edit profile button -->
							<div class="position-absolute top-0 end-0 p-3">
								<a href="account-wishlist.html#" class="text-primary-hover" data-bs-toggle="tooltip" data-bs-title="Edit profile">
									<i class="bi bi-pencil-square"></i>
								</a>
							</div>

							<!-- Card body START -->
							<div class="card-body p-3">
								<!-- Avatar and content -->
								<div class="text-center mb-3">
									<!-- Avatar -->
									<div class="avatar avatar-xl mb-2">
										<img class="avatar-img rounded-circle border border-2 border-white" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/default.png') }}" alt="">
									</div>
									<h6 class="mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
									<a href="" class="text-reset text-primary-hover small">{{ Auth::user()->email }}</a>
									<hr>
								</div>

								<!-- Sidebar menu item START -->
								<ul class="nav nav-pills-primary-soft flex-column">
									<li class="nav-item">
										<a class="nav-link {{ request()->is('reader/account') ? 'active' : '' }}" href="{{ url('/reader/account') }}"><i class="bi bi-person fa-fw me-2"></i>Mon Profile</a>
									</li>
									<li class="nav-item">
										<a class="nav-link {{ request()->is('reader/books') ? 'active' : '' }}" href="{{ url('/reader/books') }}"><i class="bi bi-book fa-fw me-2"></i>Mes Livres</a>
									</li>
									
									<li class="nav-item">
										<a class="nav-link {{ request()->is('reader/wishlist') ? 'active' : '' }}" href="{{ url('/reader/wishlist') }}"><i class="bi bi-heart fa-fw me-2"></i>Wishlist</a>
									</li>
									<li class="nav-item">
										<a class="nav-link {{ request()->is('reader/cart') ? 'active' : '' }}" href="{{ url('/reader/cart') }}"><i class="bi bi-cart fa-fw me-2"></i>Mon Panier</a>
									</li>

									<li class="nav-item">
										<a class="nav-link {{ request()->is('reader/settings') ? 'active' : '' }}" href="{{ url('/reader/settings') }}"><i class="bi bi-gear fa-fw me-2"></i>Settings</a>
									</li>
									
									<li class="nav-item">
										<form method="POST" action="{{ route('logout') }}">
											@csrf

											<button type="submit" class="nav-link text-danger bg-danger-soft-hover">
												<i class="fas fa-sign-out-alt fa-fw me-2"></i>
												Se deconnecter
											</button>
										</form>									
									</li>

									<li class="nav-item">
										@if(!auth()->user()->is_writer)
										<form method="POST" action="/become-writer">
											@csrf
											<button class="btn btn-submit">
												Devenir écrivain
											</button>
										</form>

										@endif
									</li>
								</ul>
								<!-- Sidebar menu item END -->

								
							</div>
							<!-- Card body END -->
						</div>
					</div>
				</div>	
				<!-- Responsive offcanvas body END -->	
			</div>
			<!-- Sidebar END -->

