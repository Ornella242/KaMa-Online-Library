@extends('layouts.app')

@section('content')

<section class="register-page">

    <div class="register-shell">

        <div class="register-panel">

            <!-- LEFT -->
            <div class="register-hero">

                <span class="register-badge">
                    Nouveau membre
                </span>

                <h1>Rejoignez KaMa</h1>

                <p>
                    Créez votre compte pour accéder à des recommandations personnalisées,
                    sauvegarder vos lectures et retrouver facilement vos auteurs préférés.
                </p>

                <p>
                    Faites découvrir vos livres et rejoignez une communauté d’écrivains.
                </p>

                <div class="register-hero-cards">

                    <div class="hero-card">
                        <h5>Lecture personnalisée</h5>
                        <p>
                            Des suggestions de livres qui correspondent à votre style et vos envies.
                        </p>
                    </div>

                    <div class="hero-card">
                        <h5>Bibliothèque instantanée</h5>
                        <p>
                            Retrouvez rapidement tous les livres achetés dans votre bibliothèque.
                        </p>
                    </div>

                    <div class="hero-card">
                        <h5>Connexion sécurisée</h5>
                        <p>
                            Votre compte est protégé et vos informations restent privées.
                        </p>
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="register-form-shell">

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('register') }}" class="register-form">

                    @csrf

                    <!-- Account type -->
                    <div class="register-role-picker">
                        <div class="register-role-picker-head">
                            <label>Type de compte</label>
                            <p>Qui êtes-vous ? Choisissez le type de compte à créer.</p>
                        </div>

                        <div class="register-role-options" role="radiogroup" aria-label="Type de compte">
                            @foreach($roles as $role)
                                @if($role->name !== 'admin')
                                    @php
                                        $isReader = $role->name === 'reader';
                                        $isChecked = (string) old('role_id', '') === (string) $role->id;
                                    @endphp
                                    <label class="register-role-card {{ $isReader ? 'is-reader' : 'is-writer' }}">
                                        <input
                                            type="radio"
                                            name="role_id"
                                            value="{{ $role->id }}"
                                            required
                                            @checked($isChecked)>
                                        <span class="register-role-icon">
                                            <i class="bi {{ $isReader ? 'bi-book' : 'bi-pen' }}"></i>
                                        </span>
                                        <span class="register-role-body">
                                            <strong>{{ $isReader ? 'Lecteur' : 'Écrivain' }}</strong>
                                            <small>
                                                {{ $isReader
                                                    ? 'Achetez, lisez et retrouvez vos livres dans votre bibliothèque.'
                                                    : 'Publiez vos ouvrages et suivez vos revenus sur KaMa.' }}
                                            </small>
                                        </span>
                                        <span class="register-role-check">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="register-fields-row">

                        <!-- Firstname -->
                        <div class="register-field">

                            <label>Prénom</label>

                            <div class="input-icon-group">

                                <i class="bi bi-person input-icon"></i>

                                <input
                                    type="text"
                                    name="firstname"
                                    placeholder="Votre prénom"
                                    value="{{ old('firstname') }}"
                                    required>

                            </div>

                        </div>

                        <!-- Lastname -->
                        <div class="register-field">

                            <label>Nom</label>

                            <div class="input-icon-group">

                                <i class="bi bi-person input-icon"></i>

                                <input
                                    type="text"
                                    name="lastname"
                                    placeholder="Votre nom"
                                    value="{{ old('lastname') }}"
                                    required>
                            </div>

                        </div>

                    </div>

                    <!-- Email -->
                    <div class="register-field">

                        <label>Email</label>

                        <div class="input-icon-group">

                            <i class="bi bi-envelope input-icon"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="votre@email.com"
                                value="{{ old('email') }}"
                                required>

                        </div>

                    </div>

                    <!-- Country -->
                    <div class="row">
                        <div class="register-field col-md-6">
                            <label>Pays</label>
                            <div class="input-icon-group">
                                <i class="bi bi-geo-alt input-icon"></i>
                                <select
                                    name="country_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Votre pays
                                    </option>

                                    @foreach($countries as $country)

                                        <option value="{{ $country->id }}" @selected((string) old('country_id') === (string) $country->id)>

                                            {{ $country->flag }} {{ $country->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>
                        </div>
                        <div class="register-field col-md-6">
                            <label>Ville</label>
                            <div class="input-icon-group">

                                <i class="bi bi-geo-alt input-icon"></i>

                            <input
                                    type="text"
                                    name="city"
                                    placeholder="Votre ville ex: Accra"
                                    value="{{ old('city') }}"
                                    required>

                            </div>
                        </div>

                    </div>

                    <!-- Password -->
                    {{-- <div class="register-field">

                        <label>Mot de passe</label>

                        <div class="input-icon-group">

                            <i class="bi bi-lock input-icon"></i>

                            <input
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required>
                        </div>

                    </div> --}}

                   

                       <!-- New password -->
                    <div class="mb-3 register-field">

                        <label>
                            Mot de passe
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-transparent">
                                <i class="fas fa-eye-slash cursor-pointer toggle-password" data-target="password"></i>                                        
                            </span>
                            <input type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                placeholder="Mot de passe">
                        </div>
                    </div>
                    <!-- Confirm password -->
                    <div class="mb-4 register-field">

                        <label>
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

                    <button class="register-submit" type="submit">
                        Créer mon compte
                    </button>

                </form>

                <div class="register-footer">

                    <a href="{{ url('/') }}" class="register-back">
                        <i class="bi bi-arrow-left"></i>
                        Retour à l'accueil
                    </a>

                    <p>
                      Vous avez déjà un compte? <a href="{{ url('/login') }}">Connectez-vous</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection