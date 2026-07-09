@extends('layouts.writer')

@section('writer-content')

<section class="pt-0">
	<div class="container vstack gap-4">
		<!-- Title START -->
		<div class="row">
			<div class="col-12">
				<h1 class="fs-4 mb-0"><i class="bi bi-gear fa-fw me-1"></i>Paramètres</h1>
			</div>
		</div>
		<!-- Title END -->

		<!-- Tabs START -->
		<div class="row g-4">
			<div class="col-12">
				<div class="bg-light pb-0 px-2 px-lg-0 rounded-top">
					<ul class="nav nav-tabs nav-bottom-line nav-responsive border-0 nav-justified" role="tablist">
						<li class="nav-item"> <a class="nav-link mb-0 active" data-bs-toggle="tab" href="#tab-1"><i class="fas fa-cog fa-fw me-2"></i>Modifier votre profile</a> </li>
						<li class="nav-item"> <a class="nav-link mb-0" data-bs-toggle="tab" href="#tab-2"><i class="fas fa-bell fa-fw me-2"></i>Paramètres de notification </a> </li>
						<li class="nav-item"> <a class="nav-link mb-0" data-bs-toggle="tab" href="#tab-3"><i class="fas fa-user-circle fa-fw me-2"></i>Paramètres du compte</a> </li>
					</ul>
				</div>
			</div>
		</div>	
		<!-- Tabs END -->

        <div class="row g-4">
			<div class="col-12">
				<div class="tab-content">
					<!-- Tab content 1 START -->
					<div class="tab-pane show active" id="tab-1">
						<div class="row g-4">
							<!-- Edit profile START -->
							<div class="col-12">
								<div class="card border">
									<div class="card-header border-bottom bg-table-red">
										<h5 class="card-header-title">Modifier votre Profile</h5>
									</div>
										<div class="card-body">
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
                                        </div>
                                        <form class="row g-3" method="POST" action="{{ route('writer.account.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="mb-3 ">
                                                    <!-- Avatar upload START -->
                                                   <div class="row mb-4 align-items-center">

                                                        <div class="col-lg-3 text-center">

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

                                                        <div class="col-lg-9">

                                                            <label class="form-label fw-semibold text-black">
                                                                Biographie
                                                            </label>

                                                            <textarea
                                                                name="bio"
                                                                rows="5"
                                                                class="form-control rounded-3"
                                                                placeholder="Présentez-vous en quelques lignes...">{{ old('bio', Auth::user()->bio) }}</textarea>

                                                            <small class="text-blck">
                                                                Cette biographie sera visible sur votre profil d'auteur.
                                                            </small>

                                                        </div>

                                                    </div>
                                                    <!-- Avatar upload END -->
                                                </div>                                            
                                           
                                                <div class='row mb-3'>
                                                    <div class="col-md-6 mb-4">

                                                        <label class="form-label fw-semibold text-black">
                                                            <i class="bi bi-person me-2 text-danger"></i>
                                                            Prénom
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="firstname"
                                                            class="form-control rounded-3 shadow-sm"
                                                            value="{{ Auth::user()->firstname }}">

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-black"><i class="bi bi-person me-2 icon-red"></i>Nom</label>
                                                        <input type="text" class="form-control rounded-3 shadow-sm" name="lastname" value="{{ Auth::user()->lastname }}" placeholder="Nom de famille">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-black"><i class="bi bi-flag me-2 icon-red"></i>Pays<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control rounded-3 shadow-sm" name="country" value="{{ Auth::user()->country }}" placeholder="Enter votre pays">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-black">Selectionnez votre genre<span class="text-danger">*</span></label>
                                                <div class="btn-group w-100" role="group">

                                                        <input type="radio"
                                                            class="btn-check"
                                                            name="gender"
                                                            id="male"
                                                            value="male"
                                                            {{ Auth::user()->gender=='male'?'checked':'' }}>

                                                        <label class="btn btn-outline-danger rounded-start"
                                                            for="male">
                                                            Homme
                                                        </label>

                                                        <input type="radio"
                                                            class="btn-check"
                                                            name="gender"
                                                            id="female"
                                                            value="female"
                                                            {{ Auth::user()->gender=='female'?'checked':'' }}>

                                                        <label class="btn btn-outline-danger"
                                                            for="female">
                                                            Femme
                                                        </label>

                                                        <input type="radio"
                                                            class="btn-check"
                                                            name="gender"
                                                            id="other"
                                                            value="other"
                                                            {{ Auth::user()->gender=='other'?'checked':'' }}>

                                                        <label class="btn btn-outline-danger rounded-end"
                                                            for="other">
                                                            Autre
                                                        </label>

                                                    </div>
                                                    </div>

                                                </div>
                                            
                                                <div class="row">
                                                    <!-- Email id -->
                                                    <div class="col-md-6 MB-3">
                                                        <label class="form-label fw-semibold text-black"><i class="bi bi-envelope-at me-2 icon-red"></i>Adresse email</label>
                                                        <input type="email" name="email" class="form-control rounded-3 shadow-sm" value="{{ Auth::user()->email }}" placeholder="Entrez votre adresse mail">
                                                    </div>
                                                    <!-- Mobile number -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold text-black"><i class="bi bi-phone me-2 icon-red"></i>Numéro de téléphone</label>
                                                        <input type="text" class="form-control rounded-3 shadow-sm" name="phone" value="{{ Auth::user()->phone }}" placeholder="Entrez votre numéro (ex: +233 0500000000)">
                                                    </div>
                                                </div>
                                            
                                                
                                                <!-- Save button -->
    
                                                <div class="d-flex justify-content-center mt-4">
                                                <button type="submit" class="btn btn-submit mb-2">
                                                        Sauvegarder
                                                </button>
										</div>
                                           
                                        </form>
									</div>
								</div>
							</div>
							<!-- Edit profile END -->


							<!-- Update Password START -->
							<div class="col-md-6">
								<div class="card border">
									<div class="card-header border-bottom bg-table-red">
										<h5 class="card-header-title">Changez votre mot de passe </h5>
									</div>
									<!-- Card body START -->
                                    <form class="card-body" method="POST" action="{{ route('writer.password.update') }}">
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
							</div>
							<!-- Update Password END -->
						</div>
					</div>
					<!-- Tab content 1 END -->

					<!-- Tab content 2 START -->
					<div class="tab-pane" id="tab-2">
						<div class="card border mb-4">
							<!-- Card header -->
							<div class="card-header bg-transparent border-bottom">
								<h5 class="card-header-title">Paramètres de notification</h5>
								<p class="mb-0">Déterminez les sujets pour lesquels vous souhaitez recevoir des notifications, et désabonnez-vous de ceux qui ne vous intéressent pas.</p>
							</div>
		
							<!-- Form START -->
							<form class="card-body"
                                method="POST"
                                action="{{ route('writer.notifications.update') }}">
                                @csrf
								<!-- Switch -->
								<div class="form-check form-switch d-flex justify-content-between mb-4">
                                    <label class="form-check-label">
                                        Être notifié quand un lecteur achète mon livre
                                    </label>

                                   <input class="form-check-input"
                                        type="checkbox"
                                        name="book_sold"
                                        @checked(data_get($settings->settings, 'book_sold'))
                                    >
                                </div>
		
								<!-- Switch -->
								<div class="form-check form-switch d-flex justify-content-between mb-4">
                                    <label class="form-check-label">
                                        Être notifié quand une publicité de mon livre est validée
                                    </label>

                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="ad_approved"
                                        {{ !empty($settings->settings['ad_approved']) ? 'checked' : '' }}>
                                </div>
		
								<!-- Switch -->
								<div class="form-check form-switch d-flex justify-content-between mb-4">
                                    <label class="form-check-label">
                                        Être notifié des avis sur mes livres
                                    </label>

                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="book_review"
                                        {{ !empty($settings->settings['book_review']) ? 'checked' : '' }}>
                                </div>
		
								<!-- Button -->
								<div class="d-sm-flex justify-content-end">
									<button type="submit" class="btn btn-sm btn-primary me-2 mb-0">Enregistrer</button>
									<a href="#" class="btn btn-sm btn-outline-secondary mb-0">Annuler</a>
								</div>
							</form>
							<!-- Form END -->
						</div>
					</div>
					<!-- Tab content 2 END -->

					<!-- Tab content 3 START -->
					<div class="tab-pane" id="tab-3">
						<div class="row g-4">

							<!-- Social account END -->
							<div class="col-lg-12">
								<div class="card border rounded-3 ">
									<!-- Card header -->
									<div class="card-header border-bottom bg-table-black">
										<h5 class="card-header-title">Profil sur les réseaux sociaux</h5>
									</div>
									<!-- Card body START -->
									<div class="card-body">
                                        <form action="{{ route('social.profile.save') }}" method="POST">
                                            @csrf
                                                <div class="row">
                                                
                                                    <!-- Facebook username -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><i class="fab fa-facebook text-facebook me-2"></i>Lien Facebook</label>
                                                        <input class="form-control" name="facebook_url" value=" " placeholder="https://facebook.com/.....">
                                                    </div>
                                                    
                                                    <!-- Twitter username -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label"><i class="bi bi-twitter text-twitter me-2"></i>Lien X</label>
                                                        <input class="form-control" type="text" name="x-url" value=" " placeholder="https://x.com/.....">
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                        <!-- Instagram username -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label"><i class="fab fa-instagram text-instagram-gradient me-2"></i> Lien Instagram</label>
                                                            <input class="form-control" type="text" name="instagram_url" value=" " placeholder="https://instagram.com/.....">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label"><i class="fab fa-linkedin text-instagram-gradient me-2"></i>Lien LinkedIn</label>
                                                            <input class="form-control" type="text" name="linkedin_url" value="{{ optional($social)->linkedin_url }}" placeholder="https://linkedin.com/.....">
                                                        </div>
                                                </div>
                                                <!-- Button -->
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="submit" class="btn btn-submit mb-0">Enregistrer mes comptes</button>
                                                </div>
                                        </form>
										
									</div>
									<!-- Card body END -->
								</div>
							</div>
							<!-- Social account END -->
						</div>
					</div>
					<!-- Tab content 3 END -->
				</div>
			</div>
		</div>
		
	</div>	
</section>
    
@endsection