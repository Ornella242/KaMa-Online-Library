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
                                required>

                        </div>

                    </div>

                    <!-- Country -->
                    <div class="register-field">

                        <label>Pays</label>

                        <div class="input-icon-group">

                            <i class="bi bi-geo-alt input-icon"></i>

                           <input
                                type="text"
                                name="country"
                                placeholder="Votre pays ex: France"
                                required>

                        </div>

                    </div>

                     <!-- User Type -->
                    <div class="register-field">

                        <label>Type d'utilisateur</label>

                        <div class="input-icon-group">

                            <i class="bi bi-shield input-icon"></i>

                            <select name="role_id" required>
                                <option value="">Choisir un rôle</option>

                                @foreach($roles as $role)

                                    @if($role->name !== 'admin')

                                        <option value="{{ $role->id }}">
                                            {{ $role->name === 'reader' ? 'Lecteur' : 'Écrivain' }}
                                        </option>

                                    @endif

                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="register-field">

                        <label>Mot de passe</label>

                        <div class="input-icon-group">

                            <i class="bi bi-lock input-icon"></i>

                            <input
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required>
                        </div>

                    </div>

                     <!-- Password -->
                    <div class="register-field">

                        <label> Confirmer le mot de passe</label>

                        <div class="input-icon-group">

                            <i class="bi bi-lock input-icon"></i>

                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="••••••••"
                                required>
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