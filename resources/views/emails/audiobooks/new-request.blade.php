@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Nouvelle demande d’audiobook 🎧

</h1>


<p style="
    font-size:16px;
    line-height:1.9;
">

    Bonjour équipe KaMa,

</p>


<p style="
    font-size:16px;
    line-height:1.9;
">

    Un auteur vient de confirmer le paiement de sa demande
    d’audiobook. La demande est maintenant disponible
    pour traitement par l'équipe habilitée.

</p>


<!-- BOOK CARD -->


<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:30px 0;
            background:#fafafa;
            border:1px solid #eeeeee;
            border-radius:15px;
       ">

    <tr>

        <td style="
            padding:25px;
        ">


            <table width="100%">

                <tr>

                    <td width="90">

                        @if($book->cover_image)

                            <img
                                src="{{ asset('storage/'.$book->cover_image) }}"
                                width="80"
                                style="
                                    border-radius:10px;
                                    display:block;
                                "
                                alt="{{ $book->title }}">

                        @endif

                    </td>


                    <td style="
                        padding-left:20px;
                    ">


                        <h3 style="
                            margin:0 0 10px;
                            color:#111;
                            font-size:18px;
                        ">

                            {{ $book->title }}

                        </h3>


                        <p style="
                            margin:0;
                            color:#777;
                            font-size:14px;
                        ">

                            Auteur :

                            {{ $author->firstname }}

                            {{ $author->lastname }}

                        </p>


                    </td>

                </tr>

            </table>


        </td>

    </tr>

</table>


<!-- REQUEST INFORMATION -->


<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin-bottom:25px;
       ">

    <tr>

        <td style="
            padding:20px;
            background:#fafafa;
            border:1px solid #eeeeee;
            border-radius:10px;
        ">

            <p style="
                margin:0 0 10px;
                font-size:14px;
                color:#777;
            ">

                Demande d’audiobook

            </p>


            <p style="
                margin:0;
                font-size:18px;
                font-weight:700;
                color:#111;
            ">

                {{ number_format(
                    (int) $audiobookRequest->characters,
                    0,
                    ',',
                    ' '
                ) }}

                caractères

            </p>


            <p style="
                margin:8px 0 0;
                font-size:14px;
                color:#777;
            ">

                {{ number_format(
                    (int) $audiobookRequest->words,
                    0,
                    ',',
                    ' '
                ) }}

                mots

            </p>

        </td>

    </tr>

</table>


<!-- PAYMENT STATUS -->


<table width="100%">

    <tr>

        <td style="
            background:#ecfdf5;
            border-left:4px solid #0f9d58;
            padding:20px;
            border-radius:10px;
            font-size:15px;
            line-height:1.8;
            color:#555;
        ">

            <strong style="
                color:#111;
            ">

                Paiement confirmé

            </strong>

            <br>

            Le paiement de la demande d’audiobook a été confirmé.
            La demande est prête à être traitée par un administrateur
            disposant de la permission de génération des audiobooks.

        </td>

    </tr>

</table>


<!-- PAYMENT DETAILS -->


<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:25px 0;
       ">

    <tr>

        <td style="
            padding:18px;
            background:#fafafa;
            border:1px solid #eeeeee;
        ">

            <p style="
                margin:0 0 7px;
                font-size:13px;
                color:#777;
            ">

                Montant payé

            </p>

            <p style="
                margin:0;
                font-size:18px;
                font-weight:700;
                color:#111;
            ">

                {{ number_format(
                    (float) $audiobookRequest->total_amount,
                    2,
                    ',',
                    ' '
                ) }}

                $

            </p>

        </td>


    </tr>

</table>


<p style="
    margin-top:35px;
    font-size:16px;
    line-height:1.9;
">

    Merci de consulter la demande et de lancer la génération
    de l'audiobook lorsque vous êtes prêt.

</p>


@include(
    'emails.partials.button',
    [
        'url' => route(
            'admin.audiobooks.requests.show',
            $audiobookRequest
        ),
        'slot' => 'Voir la demande'
    ]
)


<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    L'équipe KaMa vous remercie pour le suivi
    des demandes d'audiobooks.

</p>


@endsection