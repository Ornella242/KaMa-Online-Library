@extends('emails.layouts.app')


@section('content')


    <h1 style="
        font-size:32px;
        line-height:1.3;
        margin:0 0 25px;
        color:#111827;">

        Merci pour votre achat 🎉

    </h1>

    <p style="
        font-size:16px;
        line-height:1.9;">

        Bonjour {{ $order->firstname }},
    </p>

    <p style="
        font-size:16px;
        line-height:1.9;">

        Votre paiement a bien été confirmé.
        Vos livres sont maintenant disponibles
        dans votre bibliothèque KaMa.

    </p>


<!-- ORDER INFO -->

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
            padding:25px;">


            <h3 style="
                margin:0 0 15px;
                color:#111;
                font-size:18px;
            ">

            Commande confirmée

            </h3>


            <p style="
                margin:0;
                color:#666;
                font-size:14px;">

                Référence :

                <strong>
                {{ $order->reference }}
                </strong>


            </p>


        </td>

    </tr>


</table>



<p style="
    font-size:16px;
    line-height:1.9;">

    Vous pouvez télécharger vos livres grâce aux liens
    ci-dessous.

    Les liens restent accessibles pendant :

    <strong>
    14 jours
    </strong>.

</p>



<!-- DOWNLOADS -->


@forelse($downloads as $download)


    <table width="100%"
        cellpadding="0"
        cellspacing="0"
        style="
                margin:20px 0;
                border:1px solid #eeeeee;
                border-radius:15px;
        ">

        <tr>
            <td style="padding:22px;">



                <p style="
                    margin:0 0 8px;
                    color:#b30000;
                    font-size:12px;
                    font-weight:bold;
                    text-transform:uppercase;
                ">

                {{ $download['type'] }}

                </p>



                <h3 style="
                    margin:0 0 20px;
                    color:#111;
                    font-size:18px;
                ">

                {{ $download['title'] }}

                </h3>




                @include(
                    'emails.partials.button',
                    [
                        'url' => $download['url'],
                        'slot' => 'Télécharger mon livre'
                    ]
                )



            </td>
        </tr>

    </table>



@empty


    <table width="100%">

        <tr>


            <td style="
                background:#fff7ed;
                border-left:4px solid #b30000;
                padding:20px;
                border-radius:10px;
                font-size:14px;
                line-height:1.8;
                color:#555;
                ">


                Votre commande est confirmée.

                Si vous ne trouvez pas vos fichiers,
                contactez le support KaMa.


            </td>


        </tr>

    </table>


@endforelse


<p style="
    margin-top:35px;
    font-size:14px;
    line-height:1.8;
    color:#777;">

    Conservez cet email.

    Il contient vos liens personnels d'accès.
    Ne les partagez pas.


</p>





@endsection
