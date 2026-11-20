@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Préférences</span>
                <h1>Paramètres</h1>
                <p>Gérez votre profil d’auteur, vos notifications et la sécurité du compte.</p>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Veuillez corriger les informations suivantes :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="writer-settings-layout">
            <nav class="writer-settings-nav" role="tablist" aria-label="Sections des paramètres">
                <button class="active" data-bs-toggle="tab" data-bs-target="#writer-profile" type="button">
                    <i class="bi bi-person"></i><span>Profil</span>
                </button>
                <button data-bs-toggle="tab" data-bs-target="#writer-notifications" type="button">
                    <i class="bi bi-bell"></i><span>Notifications</span>
                </button>
                <button data-bs-toggle="tab" data-bs-target="#writer-security" type="button">
                    <i class="bi bi-shield-lock"></i><span>Sécurité</span>
                </button>
                <button data-bs-toggle="tab" data-bs-target="#writer-social" type="button">
                    <i class="bi bi-share"></i><span>Réseaux sociaux</span>
                </button>
            </nav>

            <div class="tab-content writer-settings-content">
                <div class="tab-pane fade show active writer-form-panel" id="writer-profile">
                    <header><span><i class="bi bi-person"></i></span><div><h2>Profil d’auteur</h2><p>Ces informations peuvent être visibles par vos lecteurs.</p></div></header>
                    <form method="POST" action="{{ route('writer.account.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="writer-profile-editor">
                            <label for="writer-avatar" class="writer-avatar-upload">
                                <img src="{{ auth()->user()->avatar
                                    ? asset('storage/'.auth()->user()->avatar)
                                    : asset('assets/images/avatar/01.jpg') }}" alt="">
                                <span><i class="bi bi-camera"></i></span>
                                <input id="writer-avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp">
                                <small>JPG, PNG ou WebP · 2 Mo maximum</small>
                            </label>
                            <div class="writer-field">
                                <label for="writer-bio">Biographie</label>
                                <textarea id="writer-bio" name="bio" rows="5" maxlength="255" placeholder="Présentez-vous en quelques lignes…">{{ old('bio', auth()->user()->bio) }}</textarea>
                                <small>Une présentation courte de votre parcours et de vos œuvres.</small>
                            </div>
                        </div>

                        <div class="writer-form-grid">
                            <div class="writer-field"><label for="writer-firstname">Prénom</label><input id="writer-firstname" name="firstname" value="{{ old('firstname', auth()->user()->firstname) }}" required></div>
                            <div class="writer-field"><label for="writer-lastname">Nom</label><input id="writer-lastname" name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}" required></div>
                            <div class="writer-field">
                                <label for="writer-country">Pays</label>
                                <select id="writer-country" name="country_id" required>
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @selected(old('country_id', auth()->user()->country_id) == $country->id)>
                                            {{ $country->flag }} {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="writer-field"><label for="writer-city">Ville</label><input id="writer-city" name="city" value="{{ old('city', auth()->user()->city) }}" required></div>
                            <div class="writer-field"><label for="writer-email">Adresse email</label><input id="writer-email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required></div>
                            <div class="writer-field"><label for="writer-phone">Téléphone</label><input id="writer-phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+233…"></div>
                            <div class="writer-field writer-field-full">
                                <label>Genre</label>
                                <div class="writer-gender-options">
                                    @foreach(['male' => 'Homme', 'female' => 'Femme', 'other' => 'Autre'] as $value => $label)
                                        <label><input type="radio" name="gender" value="{{ $value }}" @checked(old('gender', auth()->user()->gender) === $value)><span>{{ $label }}</span></label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <footer><button type="submit" class="writer-primary-action"><i class="bi bi-check2"></i> Enregistrer le profil</button></footer>
                    </form>
                </div>

                <div class="tab-pane fade writer-form-panel" id="writer-notifications">
                    <header><span><i class="bi bi-bell"></i></span><div><h2>Notifications</h2><p>Choisissez les événements pour lesquels vous souhaitez être averti.</p></div></header>
                    <form method="POST" action="{{ route('writer.notifications.update') }}">
                        @csrf
                        <div class="writer-preference-list">
                            <label><div><strong>Vente d’un livre</strong><small>Lorsqu’un lecteur achète l’un de vos ouvrages.</small></div><input type="checkbox" name="book_sold" @checked(data_get($settings->settings, 'book_sold'))></label>
                            <label><div><strong>Publicité validée</strong><small>Lorsqu’une campagne de promotion est approuvée.</small></div><input type="checkbox" name="ad_approved" @checked(data_get($settings->settings, 'ad_approved'))></label>
                            <label><div><strong>Nouvel avis</strong><small>Lorsqu’un lecteur publie un avis sur votre livre.</small></div><input type="checkbox" name="book_review" @checked(data_get($settings->settings, 'book_review'))></label>
                        </div>
                        <footer><button type="submit" class="writer-primary-action"><i class="bi bi-check2"></i> Enregistrer les préférences</button></footer>
                    </form>
                </div>

                <div class="tab-pane fade writer-form-panel" id="writer-security">
                    <header><span><i class="bi bi-shield-lock"></i></span><div><h2>Sécurité du compte</h2><p>Utilisez un mot de passe unique et difficile à deviner.</p></div></header>
                    <form method="POST" action="{{ route('writer.password.update') }}" class="writer-security-form">
                        @csrf
                        @method('PUT')
                        <div class="writer-field"><label for="current_password">Mot de passe actuel</label><input type="password" id="current_password" name="current_password" required autocomplete="current-password"></div>
                        <div class="writer-field"><label for="password">Nouveau mot de passe</label><input type="password" id="password" name="password" required autocomplete="new-password"></div>
                        <div class="writer-field"><label for="password_confirmation">Confirmer le mot de passe</label><input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"></div>
                        <footer><button type="submit" class="writer-primary-action"><i class="bi bi-shield-check"></i> Modifier le mot de passe</button></footer>
                    </form>
                </div>

                <div class="tab-pane fade writer-form-panel" id="writer-social">
                    <header><span><i class="bi bi-share"></i></span><div><h2>Réseaux sociaux</h2><p>Ajoutez uniquement les profils que vous souhaitez rendre publics.</p></div></header>
                    <form method="POST" action="{{ route('social.profile.save') }}">
                        @csrf
                        <div class="writer-form-grid">
                            <div class="writer-field"><label for="facebook_url"><i class="bi bi-facebook"></i> Facebook</label><input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $social->facebook_url) }}" placeholder="https://facebook.com/…"></div>
                            <div class="writer-field"><label for="x_url">𝕏 X</label><input type="url" id="x_url" name="x_url" value="{{ old('x_url', $social->x_url) }}" placeholder="https://x.com/…"></div>
                            <div class="writer-field"><label for="instagram_url"><i class="bi bi-instagram"></i> Instagram</label><input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $social->instagram_url) }}" placeholder="https://instagram.com/…"></div>
                            <div class="writer-field"><label for="linkedin_url"><i class="bi bi-linkedin"></i> LinkedIn</label><input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $social->linkedin_url) }}" placeholder="https://linkedin.com/in/…"></div>
                        </div>
                        <footer><button type="submit" class="writer-primary-action"><i class="bi bi-check2"></i> Enregistrer les liens</button></footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
