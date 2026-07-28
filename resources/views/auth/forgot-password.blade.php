@extends('layouts.app')

@section('content')
    <section class="login-page">

        <div class="login-shell">

            <div class="login-panel">

                <!-- ================= LEFT ================= -->
                <div class="login-hero">
                    <span class="login-badge">
                        <i class="bi bi-shield-lock"></i>
                        Sécurité KaMa
                    </span>

                    <h4>
                        Récupérez votre accès KaMa
                    </h4>

                    <p class="login-description">
                        Vous avez oublié votre mot de passe ?
                        Entrez votre adresse email et nous vous
                        enverrons un lien sécurisé pour créer un nouveau mot de passe.
                    </p>

                    <div class="login-hero-cards">
                        <div class="hero-card">
                            <i class="bi bi-envelope-check"></i>
                            <h5>
                                Email sécurisé
                            </h5>
                            <p>
                                Recevez un lien unique de récupération.
                            </p>
                        </div>

                        <div class="hero-card">
                            <i class="bi bi-clock-history"></i>
                            <h5>
                                Rapide
                            </h5>

                            <p>
                                Changez votre mot de passe en quelques minutes.
                            </p>
                        </div>


                        <div class="hero-card">
                            <i class="bi bi-shield-check"></i>
                            <h5>
                                Protection
                            </h5>

                            <p>
                                Votre compte reste protégé.
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
                        <h4>
                            Mot de passe oublié
                        </h4>

                        <p>
                            Entrez votre email pour recevoir le lien.
                        </p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ route('password.email') }}"
                        class="login-form">
                        @csrf

                        <div class="login-field">
                            <label>
                                Email
                            </label>
                            <div class="login-input">
                                <i class="bi bi-envelope"></i>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="votre@email.com"
                                    required
                                >
                            </div>

                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <button type="submit"
                                class="login-btn">
                            <i class="bi bi-send"></i>
                            Envoyer le lien
                        </button>
                    </form>

                    <div class="login-footer">
                        <a href="{{ route('login') }}"
                        class="back-home">
                            <i class="bi bi-arrow-left"></i>
                            Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection