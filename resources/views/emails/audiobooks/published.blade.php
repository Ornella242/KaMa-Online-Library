@extends('emails.layouts.app')

@section('content')

    <div style="
        text-align:center;
        margin-bottom:28px;
    ">

        <div style="
            width:64px;
            height:64px;
            margin:0 auto 18px;
            border-radius:18px;
            background:#b30000;
            color:#ffffff;
            font-size:28px;
            line-height:64px;
            text-align:center;
        ">
            🎧
        </div>

        <h1 style="
            margin:0;
            color:#1f1d1b;
            font-size:24px;
            line-height:1.3;
            font-weight:700;
        ">
            Votre audiobook est disponible
        </h1>

    </div>


    <p style="
        margin:0 0 16px;
        color:#4f4a47;
        font-size:15px;
        line-height:1.7;
    ">
        Cher {{ $user->firstname ?? $user->name ?? '' }},
    </p>


    <p style="
        margin:0 0 18px;
        color:#4f4a47;
        font-size:15px;
        line-height:1.7;
    ">
        Nous vous informons que votre demande de génération
        d’audiobook pour
        <strong>
            « {{ $audiobookRequest->book->title }} »
        </strong>
        a été traitée avec succès.
    </p>


    <div style="
        margin:24px 0;
        padding:20px;
        border:1px solid #ece7e4;
        border-radius:14px;
        background:#faf9f8;
    ">

        <p style="
            margin:0 0 8px;
            color:#8a8581;
            font-size:11px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.06em;
        ">
            Livre audio
        </p>

        <p style="
            margin:0;
            color:#252321;
            font-size:17px;
            font-weight:700;
        ">
            {{ $book?->title ?? $audiobookRequest->book->title }}
        </p>

    </div>


    <p style="
        margin:0 0 18px;
        color:#4f4a47;
        font-size:15px;
        line-height:1.7;
    ">
        Le livre audio est maintenant disponible sur
        <strong>KaMa</strong>.
        Vous pouvez accéder à votre espace auteur pour
        consulter votre audiobook et télécharger le fichier
        audio.
    </p>


    @include(
        'emails.partials.button',
        [
            'url' => $url,
            'slot' => 'Accéder à mon audiobook'
        ]
    )


    <p style="
        margin:26px 0 0;
        color:#77716d;
        font-size:13px;
        line-height:1.6;
    ">
        Vous pourrez également gérer les informations de votre
        livre audio depuis votre espace auteur.
    </p>

@endsection