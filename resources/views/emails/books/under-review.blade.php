@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Bonjour {{ $user->firstname }},

</h1>




<p style="
    font-size:16px;
    line-height:1.9;
">

    Bonne nouvelle ! Votre livre a bien été reçu
    par l'équipe éditoriale KaMa.

</p>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Votre livre est maintenant en cours de
    vérification éditoriale avant sa publication.

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


                        <img

                            src="{{ asset('storage/'.$book->cover_image) }}"

                            width="80"

                            style="
                                border-radius:10px;
                            "

                            alt="{{ $book->title }}">


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

                            Statut :
                            <strong style="color:#c6ad0e;">
                                En vérification
                            </strong>

                        </p>


                    </td>



                </tr>


            </table>


        </td>


    </tr>


</table>







<!-- INFO BOX -->


<table width="100%">

    <tr>


        <td style="
            background:#fff7ed;
            border-left:4px solid #b30000;
            padding:20px;
            border-radius:10px;
            font-size:15px;
            line-height:1.8;
            color:#555;
        ">


            <strong style="
                color:#111;
            ">

                Délai de vérification :

            </strong>


            <br>


            Notre équipe éditoriale examine votre contenu.
            La vérification peut prendre jusqu'à
            <strong>
                15 jours ouvrables
            </strong>
            avant la décision finale.


        </td>


    </tr>


</table>







<p style="
    margin-top:35px;
    font-size:16px;
    line-height:1.9;
">

    Vous recevrez automatiquement une notification
    dès que la vérification sera terminée.


</p>







@include(
    'emails.partials.button',
    [
        'url' => route('writer.books.show',$book),
        'slot' => 'Voir mon livre'
    ]
)







<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    Merci de votre patience et de votre confiance envers KaMa.

</p>



@endsection