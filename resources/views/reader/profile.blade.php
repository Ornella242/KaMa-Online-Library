@extends('layouts.reader')

@section('reader-content')

<!-- Main content START -->
		
	<!-- Offcanvas menu button -->
	<div class="d-grid mb-0 d-lg-none w-100">
		<button class="btn btn-primary mb-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
			<i class="fas fa-sliders-h"></i> Menu
		</button>
	</div>

	<div class="vstack gap-4">
		
		<div class="bg-light rounded p-3">

			<h6>Compléter votre profil</h6>

			<div class="progress progress-sm bg-success bg-opacity-10">
				<div class="progress-bar bg-success"
					role="progressbar"
					style="width: {{ $score }}%"
					aria-valuenow="{{ $score }}"
					aria-valuemin="0"
					aria-valuemax="100">

					<span class="progress-percent-simple h6 fw-light ms-auto">
						{{ $score }}%
					</span>

				</div>
			</div>

			<p class="mb-0">
				Complétez votre profil pour profiter pleinement de la plateforme.
			</p>

			<!-- Checklist -->
			<div class="bg-body rounded p-3 mt-3">

				<ul class="list-inline hstack flex-wrap gap-2 justify-content-between mb-0">

					@if (Auth::user()->hasVerifiedEmail())
						<li class="list-inline-item h6 fw-normal mb-0">
							<i class="bi bi-check-circle-fill text-success me-2"></i>
							Email vérifié
						</li>
					@else
					<li class="list-inline-item h6 fw-normal mb-0">
						<i class="bi bi-x-circle-fill text-danger me-2"></i>
						Email nonvérifié
					</li>
					@endif

					@if (Auth::user()->phone)
						<li class="list-inline-item h6 fw-normal mb-0">
							<i class="bi bi-check-circle-fill text-success me-2"></i>
							Téléphone ajouté
						</li>
					@else
						<li class="list-inline-item h6 fw-normal mb-0">
							<i class="bi bi-x-circle-fill text-danger me-2"></i>
							Téléphone non ajouté
						</li>
					@endif

					@if (Auth::user()->gender)
						<li class="list-inline-item h6 fw-normal mb-0">
							<i class="bi bi-check-circle-fill text-success me-2"></i>
							Sexe ajouté
						</li>
					@else
						<li class="list-inline-item h6 fw-normal mb-0">
							<i class="bi bi-x-circle-fill text-danger me-2"></i>
							Sexe non ajouté
						</li>
					@endif

				</ul>

			</div>

		</div>

		<!-- Personal info START -->
		<div class="card border ">
			<!-- Card header -->
			<div class="card-header border-bottom bg-table-red">
				<h4 class="card-header-title">Information Personnelle</h4>
			</div>

			@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
					<i class="bi bi-check-circle-fill me-2"></i>
					{{ session('success') }}

					<button type="button"
							class="btn-close"
							data-bs-dismiss="alert"
							aria-label="Close">
					</button>
				</div>
			@endif
			@if($errors->any())
				<div class="alert alert-danger mb-4">
					<strong>Veuillez corriger les erreurs suivantes :</strong>

					<ul class="mb-0 mt-2">
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<!-- Card body START -->
			<div class="card-body">
				<!-- Form START -->
				<form class="row g-3" method="POST" action="{{ route('reader.account.update') }}" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<!-- Profile photo -->
					<div class="row mb-2 mt-2 align-items-center">

							<div class="col-lg-12 text-center">

								<label for="uploadfile-1" class="position-relative">

									<img
										src="{{ Auth::user()->avatar
										? asset('storage/'.Auth::user()->avatar)
										: asset('assets/images/avatar/01.jpg') }}"
										class="rounded-circle shadow border border-3"
										style="width:120px;height:120px;object-fit:cover;cursor:pointer;">

									<input
										id="uploadfile-1"
										type="file"
										name="avatar"
										class="d-none">

								</label>

								<p class="medium fw-semibold text-black mt-2 mb-0">
									Cliquez sur la photo pour la modifier
								</p>

							</div>
						</div>

					<!-- Name -->
					<div class="col-md-6">
						<label class="form-label fw-semibold text-black"><i class="bi bi-person me-2 icon-red"></i>Nom<span class="text-danger">*</span></label>
						<input type="text" class="form-control rounded-3 shadow-sm" name="lastname" value="{{ Auth::user()->lastname }}" placeholder="Enter your full name">
					</div>

					<div class="col-md-6">
						<label class="form-label fw-semibold text-black"><i class="bi bi-person me-2 icon-red"></i>Prénom<span class="text-danger">*</span></label>
						<input type="text" class="form-control rounded-3 shadow-sm" name="firstname" value="{{ Auth::user()->firstname }}" placeholder="Enter your first name">
					</div>

					<!-- Email -->
					<div class="col-md-6">
						<label class="form-label fw-semibold text-black"><i class="bi bi-envelope me-2 icon-red"></i>Addresse mail<span class="text-danger">*</span></label>
						<input type="email" class="form-control rounded-3 shadow-sm" name="email" value="{{ Auth::user()->email }}" placeholder="Enter your email id">
					</div>

					<!-- Mobile -->
					<div class="col-md-6">
						<label class="form-label fw-semibold text-black"><i class="bi bi-phone me-2 icon-red"></i>Numéro de téléphone<span class="text-danger"></span></label>
						<input type="text" class="form-control rounded-3 shadow-sm" name="phone" value="{{ Auth::user()->phone }}" placeholder="Enter your mobile number">
					</div>

					<!-- Nationality -->
					<div class="col-md-6">
						<label class="form-label fw-semibold text-black"><i class="bi bi-flag me-2 icon-red"></i>Pays<span class="text-danger">*</span></label>
						<input type="text" class="form-control rounded-3 shadow-sm" name="country" value="{{ Auth::user()->country }}" placeholder="Enter votre pays">

					</div>


					<!-- Gender -->
					<div class="col-md-6">
						<label class="form-label fw-semibold text-black">Selectionnez votre genre<span class="text-danger">*</span></label>
						<div class="d-flex gap-4">
							<div class="form-check">
									<input class="form-check-input" type="radio"
										name="gender" value="male"
										{{ Auth::user()->gender === 'male' ? 'checked' : '' }}>
									<label>Homme</label>
								</div>

								<div class="form-check">
									<input class="form-check-input" type="radio"
										name="gender" value="female"
										{{ Auth::user()->gender === 'female' ? 'checked' : '' }}>
									<label>Femme</label>
								</div>

								<div class="form-check">
									<input class="form-check-input" type="radio"
										name="gender" value="other"
										{{ Auth::user()->gender === 'other' ? 'checked' : '' }}>
									<label>Autre</label>
							</div>
						</div>
					</div>


					<!-- Button -->
					<div class="col-12 text-end">
						<button type="submit" class="btn btn-submit mb-0">
							Sauvegarder
						</button>
					</div>
				</form>
				<!-- Form END -->
			</div>
			<!-- Card body END -->
		</div>
		<!-- Personal info END -->


		<!-- Update Password START -->
		<div class="card border">
			<!-- Card header -->
			<div class="card-header border-bottom bg-table-black">
				<h4 class="card-header-title">Modifier votre mot de passe</h4>
			</div>

			<!-- Card body START -->
			<form class="card-body" method="POST" action="{{ route('reader.password.update') }}">
				@csrf
				@method('PUT')
				<!-- Current password -->
				<div class="mb-3">
					<label class="form-label">Mot de passe actuel</label>

					<div class="input-group">
						<input
							type="password"
							name="current_password"
							id="current_password"
							class="form-control"
							placeholder="Entrez votre mot de passe actuel"
							required>

						<span class="input-group-text bg-transparent">
							<i class="fas fa-eye-slash cursor-pointer toggle-password"
							data-target="current_password"></i>
						</span>
					</div>
				</div>

				<!-- New password -->
				<div class="mb-3">
					<label class="form-label">Nouveau mot de passe</label>

					<div class="input-group">
						<input
							type="password"
							name="password"
							id="password"
							class="form-control"
							placeholder="Entrez votre nouveau mot de passe"
							required>

						<span class="input-group-text bg-transparent">
							<i class="fas fa-eye-slash cursor-pointer toggle-password"
							data-target="password"></i>
						</span>
					</div>
				</div>

				<!-- Confirm password -->
				<div class="mb-3">
					<label class="form-label">Confirmer le nouveau mot de passe</label>

					<div class="input-group">
						<input
							type="password"
							name="password_confirmation"
							id="password_confirmation"
							class="form-control"
							placeholder="Confirmer le nouveau mot de passe"
							required>

						<span class="input-group-text bg-transparent">
							<i class="fas fa-eye-slash cursor-pointer toggle-password"
							data-target="password_confirmation"></i>
						</span>
					</div>
				</div>

				  <div class="text-end">
						<button type="submit" class="btn btn-submit">
							Modifier le mot de passe
						</button>
					</div>
			</form>
			<!-- Card body END -->
		</div>
		<!-- Update Password END -->
	</div>
<!-- Main content END -->

@endsection