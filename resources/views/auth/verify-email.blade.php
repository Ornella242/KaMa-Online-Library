@extends('layouts.app')

@section('content')
<section class="verify-page">

    <div class="verify-box">

        <div class="verify-icon">
            <i class="bi bi-envelope-check"></i>
        </div>

        <h1>Vérifie ton email</h1>

        <p>
            Un lien de vérification a été envoyé à ton adresse email.
            Clique sur le lien pour activer ton compte KaMa.
        </p>

        <p class="verify-sub">
            Si tu ne vois pas l’email, vérifie tes spams ou renvoie-le.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button class="verify-btn">
                Renvoyer l’email
            </button>
        </form>

        <a href="{{ url('/login') }}" class="verify-link">
            Retour à la connexion
        </a>

    </div>

</section>

@endsection


