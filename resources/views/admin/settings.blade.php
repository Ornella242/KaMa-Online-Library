@extends('layouts.admin')

@section('admin-content')

<div class="admin-profile-settings">

    <!-- Header -->
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-flex align-items-center gap-3">

                <div class="setting-icon">
                    <i class="bi bi-person-gear"></i>
                </div>

                <div>
                    <h3 class="h3 mb-1">Paramètres de la plateforme</h3>
                    <p class="text-black mb-0">
                        Configurez les frais de publication, votre profil et la sécurité de KaMa.
                    </p>
                </div>
                                       
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
        </div>
    </div>

    <section class="admin-publication-fees">
        <div class="admin-publication-fees-header">
            <div>
                <span>Configuration commerciale</span>
                <h4>Frais de publication des livres</h4>
                <p>Ces montants sont appliqués automatiquement selon le format choisi par l’écrivain.</p>
            </div>
            <span class="admin-publication-fees-lock">
                <i class="bi bi-shield-lock"></i> Montants contrôlés par l’administration
            </span>
        </div>

        <form method="POST" action="{{ route('admin.settings.publication-fees.update') }}">
            @csrf
            @method('PUT')

            <div class="admin-publication-fees-grid">
                <label>
                    <span class="admin-publication-fee-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <div>
                        <strong>Livre écrit — Ebook</strong>
                        <small>Frais demandés pour le dépôt d’un fichier PDF.</small>
                        <div class="admin-publication-fee-input">
                            <input type="number"
                                   name="ebook_amount"
                                   min="1"
                                   max="999999"
                                   step="1"
                                   value="{{ old('ebook_amount', data_get($publicationFees, 'ebook.amount', 10)) }}"
                                   required>
                            <span class="fee-currency-preview">XOF</span>
                        </div>
                    </div>
                </label>

                <label>
                    <span class="admin-publication-fee-icon audio"><i class="bi bi-headphones"></i></span>
                    <div>
                        <strong>Livre audio</strong>
                        <small>Frais demandés pour le dépôt d’un fichier audio.</small>
                        <div class="admin-publication-fee-input">
                            <input type="number"
                                   name="audio_amount"
                                   min="1"
                                   max="999999"
                                   step="1"
                                   value="{{ old('audio_amount', data_get($publicationFees, 'audio.amount', 15)) }}"
                                   required>
                            <span class="fee-currency-preview">XOF</span>
                        </div>
                    </div>
                </label>
            </div>

            <div class="admin-publication-fees-footer">
                <div>
                    <label for="publication-fee-currency">Devise</label>
                    <input id="publication-fee-currency"
                           type="text"
                           name="currency"
                           maxlength="3"
                           value="XOF"
                           readonly
                           required>
                </div>
                <p>
                    <i class="bi bi-info-circle"></i>
                    Les nouvelles valeurs s’appliquent aux prochaines demandes de paiement uniquement.
                </p>
                <button type="submit">
                    <i class="bi bi-check2-circle"></i> Enregistrer les frais
                </button>
            </div>
        </form>
    </section>



    <div class="row g-4">
        <!-- Profile -->
        <div class="col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom kama-card-header">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <span class="section-icon">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        Informations personnelles
                    </h5>
                </div>


                <form class="row g-3" method="POST" action="{{ route('admin.account.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="card-body">
                        <!-- Avatar -->
                        <div class="d-flex align-items-center mb-4">
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

                            <div>

                                <h5 class="mb-1">
                                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                </h5>

                                <span class="badge kama-role">
                                    Administrateur
                                </span>
                            </div>
                        </div>


                        <div class="row g-4">
                            <!-- firstname -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="bi bi-person text-danger me-1"></i>
                                    Prénom
                                </label>

                                <input type="text" class="form-control" name="firstname" value="{{ Auth::user()->firstname }}">
                            </div>

                            <!-- lastname -->

                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="bi bi-person-lines-fill text-danger me-1"></i>
                                    Nom
                                </label>

                                <input type="text" class="form-control" name="lastname" value="{{ Auth::user()->lastname }}">

                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-envelope-fill text-primary me-1"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    Téléphone
                                </label>
                                <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                            </div>

                            <!-- Country -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-globe text-warning me-1"></i>
                                    Pays
                                </label>

                            <select 
                                name="country_id"
                                class="form-select rounded-3 shadow-sm"
                                required>

                                <option value="">
                                    Sélectionnez votre pays
                                </option>

                                @foreach($countries as $country)

                                    <option 
                                        value="{{ $country->id }}"
                                        {{ old('country_id', optional(Auth::user()->country)->id) == $country->id ? 'selected' : '' }}>

                                        {{ $country->flag }} {{ $country->name }}

                                    </option>

                                @endforeach

                            </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-globe text-warning me-1"></i>
                                    Ville
                                </label>

                                <input type="text" class="form-control" name="city" value="{{ Auth::user()->city }}">
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-gender-ambiguous text-info me-1"></i>
                                    Genre
                                </label>

                                <div>

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

                    
                            <!-- Bio -->
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-chat-square-text-fill text-primary me-1"></i>
                                    Biographie
                                </label>

                                <textarea class="form-control" name="bio"
                                        rows="4"
                                        placeholder="Parlez-nous de vous...">{{ old('bio', Auth::user()->bio) }}</textarea>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn kama-btn" type="submit">
                                <i class="bi bi-check-circle me-2"></i>
                                Modifier mon profil
                            </button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

        <!-- Security -->
        <div class="col-xl-4">
            <div class="card shadow-sm border-0">
                <div class="card-header kama-card-header border-bottom">
                    <h5 class="mb-0">
                        <span class="section-icon security">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        Sécurité
                    </h5>
                </div>
                    <div class="security-box">
                        <div class="card-body">
                           <form class="card-body" method="POST" action="{{ route('admin.password.update') }}">
                                @csrf
                                @method('PUT')
                                <!-- Current password -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-lock-fill text-danger me-1"></i>
                                        Mot de passe actuel
                                    </label>


                                    <div class="input-group">

                                        <span class="input-group-text bg-transparent">
                                            <i class="bi bi-key text-danger"></i>
                                        </span>

                                        <input type="password"
                                            class="form-control"
                                            name="current_password"
                                            placeholder="Mot de passe actuel">

                                    </div>

                                </div>

                                <!-- New password -->
                                <div class="mb-3">

                                    <label class="form-label">
                                        <i class="bi bi-shield-lock-fill text-primary me-1"></i>
                                        Nouveau mot de passe
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text bg-transparent">
                                            <i class="fas fa-eye-slash cursor-pointer toggle-password" data-target="password"></i>                                        
                                        </span>
                                        <input type="password"
                                            id="password"
                                            class="form-control"
                                            name="password"
                                            placeholder="Nouveau mot de passe">
                                    </div>
                                </div
                                <!-- Confirm password -->
                                <!-- Confirm password -->
                                <div class="mb-4">

                                    <label class="form-label">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        Confirmation du mot de passe
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text bg-transparent">
                                            <i class="fas fa-eye-slash cursor-pointer toggle-password"
                                            data-target="password_confirmation"></i>
                                        </span>

                                        <input type="password"
                                            id="password_confirmation"
                                            class="form-control"
                                            name="password_confirmation"
                                            placeholder="Confirmez votre nouveau mot de passe">

                                    </div>

                                </div>

                                <!-- Password rules -->
                                <div class="password-info mb-4">
                                    <h6 class="mb-2">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Conseils de sécurité
                                    </h6>

                                    <ul class="mb-0">
                                        <li>Au moins 8 caractères</li>
                                        <li>Une lettre majuscule</li>
                                        <li>Un chiffre</li>
                                        <li>Un caractère spécial</li>
                                    </ul>
                                </div>

                                <button type="submit"
                                        class="btn kama-btn w-100">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Changer le mot de passe
                                </button>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>


</div>

@endsection