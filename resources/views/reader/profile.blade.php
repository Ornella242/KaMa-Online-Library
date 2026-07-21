@extends('layouts.reader')

@section('reader-content')
<div class="reader-workspace">
    <div class="d-grid mb-3 d-lg-none">
        <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <header class="reader-page-hero">
        <div>
            <span class="eyebrow">Espace lecteur</span>
            <h1>Mon profil</h1>
            <p>Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
        <div class="reader-score">
            <strong>{{ $score }}%</strong>
            <span>Profil complété</span>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="reader-progress-card">
        <div class="progress">
            <div class="progress-bar" style="width: {{ $score }}%"></div>
        </div>
        <ul class="reader-checklist">
            <li class="{{ $user->hasVerifiedEmail() ? 'done' : '' }}">
                <i class="bi {{ $user->hasVerifiedEmail() ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                Email vérifié
            </li>
            <li class="{{ $user->phone ? 'done' : '' }}">
                <i class="bi {{ $user->phone ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                Téléphone
            </li>
            <li class="{{ $user->gender ? 'done' : '' }}">
                <i class="bi {{ $user->gender ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                Genre
            </li>
            <li class="{{ $user->avatar ? 'done' : '' }}">
                <i class="bi {{ $user->avatar ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                Photo
            </li>
            <li class="{{ $user->country_id && $user->city ? 'done' : '' }}">
                <i class="bi {{ $user->country_id && $user->city ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                Localisation
            </li>
        </ul>
    </div>

    <section class="reader-panel">
        <header>
            <h2>Informations personnelles</h2>
            <p>Ces infos sont utilisées pour vos commandes et votre bibliothèque.</p>
        </header>

        <form method="POST" action="{{ route('reader.account.update') }}" enctype="multipart/form-data" class="reader-form">
            @csrf
            @method('PUT')

            <div class="reader-avatar-upload">
                <label for="avatar">
                    <img
                        src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/avatar/01.jpg') }}"
                        alt="Avatar">
                    <span><i class="bi bi-camera"></i> Changer</span>
                </label>
                <input id="avatar" type="file" name="avatar" accept="image/*" class="d-none">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Prénom *</label>
                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pays *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">Sélectionner</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected((string) old('country_id', $user->country_id) === (string) $country->id)>
                                {{ $country->flag }} {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ville *</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label d-block">Genre</label>
                    <div class="reader-gender">
                        @foreach(['male' => 'Homme', 'female' => 'Femme', 'other' => 'Autre'] as $value => $label)
                            <label>
                                <input type="radio" name="gender" value="{{ $value }}" @checked(old('gender', $user->gender) === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="3" maxlength="255">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-danger">Enregistrer le profil</button>
            </div>
        </form>
    </section>

    <section class="reader-panel">
        <header>
            <h2>Mot de passe</h2>
            <p>Choisissez un mot de passe robuste d’au moins 8 caractères.</p>
        </header>

        <form method="POST" action="{{ route('reader.password.update') }}" class="reader-form">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-dark">Modifier le mot de passe</button>
            </div>
        </form>
    </section>
</div>
@endsection
