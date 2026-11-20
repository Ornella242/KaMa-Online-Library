@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Bienvenue sur KaMa {{ $user->firstname }} 👋

</h1>




<p style="
    font-size:16px;
    line-height:1.9;
">

    Nous sommes heureux de vous accueillir
    dans KaMa Online Library.

</p>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Votre compte a bien été créé.
    Pour commencer à profiter de votre espace,
    veuillez confirmer votre adresse email.

</p>







<!-- WELCOME CARD -->


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


                    <td width="50"
                        style="
                            vertical-align:top;
                        ">

                        <div style="
                            width:40px;
                            height:40px;
                            background:#00b300;
                            border-radius:50%;
                            color:white;
                            text-align:center;
                            line-height:40px;
                            font-size:20px;
                        ">

                            ✓

                        </div>


                    </td>



                    <td style="
                        padding-left:15px;
                    ">



                        <h3 style="
                            margin:0 0 8px;
                            color:#111;
                            font-size:17px;
                        ">

                            Activez votre compte

                        </h3>



                        <p style="
                            margin:0;
                            color:#777;
                            font-size:14px;
                            line-height:1.6;
                        ">

                            La vérification permet de sécuriser
                            votre compte et d'accéder pleinement
                            aux fonctionnalités KaMa.

                        </p>



                    </td>


                </tr>


            </table>



        </td>


    </tr>


</table>







@include(
    'emails.partials.button',
    [
        'url' => $verificationUrl,
        'slot' => 'Vérifier mon adresse email'
    ]
)






<table width="100%"
       style="
            margin-top:30px;
       ">

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

            Ce lien de vérification expirera dans
            <strong>
                60 minutes
            </strong>.


        </td>


    </tr>


</table>



<p style="
    margin-top:35px;
    font-size:16px;
    line-height:1.9;
">

    Une fois votre adresse confirmée, vous pourrez :

</p>



<ul style="
    padding-left:20px;
    color:#555;
    font-size:15px;
    line-height:1.8;
">

    <li>
        Découvrir des livres africains
    </li>

    <li>
        Acheter et gérer votre bibliothèque numérique
    </li>

    <li>
        Publier vos propres œuvres si vous êtes auteur
    </li>

</ul>






<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    Si vous n'êtes pas à l'origine de cette inscription,
    vous pouvez ignorer cet email.

</p>



@endsection