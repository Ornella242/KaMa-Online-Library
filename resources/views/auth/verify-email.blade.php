@extends('layouts.app')

@section('content')
<div class="verify-page-test">

    <div class="verify-box">

        <div class="verify-icon">
            <i class="bi bi-envelope-check"></i>
        </div>


        <h1>Confirmez votre adresse email</h1>


        <p>
            Un lien de vérification vient d’être envoyé à votre adresse email.
            Veuillez cliquer sur ce lien afin d’activer votre compte KaMa et accéder à votre espace personnel.
        </p>


        <p class="verify-sub">
            Vous ne trouvez pas notre email ? Pensez à vérifier votre dossier
            <strong>courriers indésirables</strong> ou demandez un nouvel envoi.
        </p>


        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button class="verify-btn">
                Renvoyer le lien de vérification
            </button>

        </form>


        <a href="{{ url('/login') }}" class="verify-link">
            <i class="bi bi-arrow-left"></i>
            Retour à la connexion
        </a>


    </div>

</div>

@endsection


