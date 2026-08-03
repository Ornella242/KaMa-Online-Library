@extends('layouts.app')

@section('content')

<section class="login-page">

    <div class="login-shell">

        <div class="login-panel">

            <div class="login-hero">

                <span class="login-badge">
                    Bon retour ici sur KaMa <i class="bi bi-emoji-smile"></i>
                </span>

                <h1>Heureux de vous revoir sur KaMa</h1>

                <p class="login-description">
                    Connectez-vous pour retrouver votre bibliothèque,
                    poursuivre vos lectures et découvrir de nouveaux auteurs. <br>

                    Connectez-vous pour retrouver votre espace ecrivain, publiez vos livres et accédez à des outils de gestion de votre contenu.
                </p>

                <div class="login-hero-cards">

                    <div class="hero-card">
                        <i class="bi bi-book-half"></i>

                        <h5>Votre bibliothèque</h5>

                        <p>
                            Retrouvez tous vos ebooks et livres audio en un seul endroit.
                        </p>
                    </div>

                    <div class="hero-card">
                        <i class="bi bi-bookmark-heart"></i>

                        <h5>Wishlist</h5>

                        <p>
                            Continuez vos achats et gardez vos coups de cœur.
                        </p>
                    </div>

                    <div class="hero-card">
                        <i class="bi bi-shield-lock"></i>

                        <h5>Connexion sécurisée</h5>

                        <p>
                            Vos données personnelles sont protégées et chiffrées.
                        </p>
                    </div>

                </div>

            </div>

            <!-- ================= RIGHT ================= -->

            <div class="login-form-shell">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="login-header">

                    <h2>Connexion</h2>

                    <p>
                        Entrez vos informations pour accéder à votre espace.
                    </p>

                </div>

                <form method="POST" action="{{ route('login') }}" class="login-form">

                    @csrf

                    <!-- Email -->

                    <div class="login-field">

                        <label>Email</label>

                        <div class="login-input">

                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="votre@email.com"
                                required>

                        </div>

                    </div>

                    <!-- Password -->

                    <div class="login-field">

                        <label>Mot de passe</label>

                        <div class="login-input">

                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                id="login_password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password">

                            <i
                                class="bi bi-eye-slash toggle-password"
                                data-target="login_password"
                                role="button"
                                tabindex="0"
                                title="Afficher le mot de passe"
                                aria-label="Afficher le mot de passe"></i>

                        </div>

                    </div>

                    <!-- OPTIONS -->

                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" value="1">
                            <span>Se souvenir de moi</span>
                        </label>

                        <a href="{{ url('/forgot-password') }}" class="forgot-password">
                            Mot de passe oublié ?
                        </a>

                    </div>

                    <button type="submit" class="login-btn">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Se connecter

                    </button>

                </form>


                <div class="login-footer">

                    <a href="{{ url('/') }}" class="back-home">

                        <i class="bi bi-arrow-left"></i>

                        Retour à l'accueil

                    </a>

                    <p>

                        Vous n'avez pas encore de compte ?

                        <a href="{{ url('/register') }}" class="login-register-link">
                            Inscrivez-vous
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
