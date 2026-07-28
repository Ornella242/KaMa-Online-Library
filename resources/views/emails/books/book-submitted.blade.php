@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Nouveau livre soumis 📚

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

    Un nouvel ouvrage vient d'être soumis sur la plateforme
    et nécessite une vérification éditoriale.

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


                            Auteur :

                            {{ $book->author->firstname }}

                            {{ $book->author->lastname }}


                        </p>


                    </td>



                </tr>



            </table>



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

                Dépôt confirmé

            </strong>

            <br>
            Le paiement du dépôt a été confirmé.
            Le livre est prêt pour la vérification éditoriale.
        </td>


    </tr>


</table>







<p style="
    margin-top:35px;
    font-size:16px;
    line-height:1.9;
">

    Merci de procéder à l'examen du contenu,
    de la couverture et des informations associées
    avant décision de publication.


</p>







@include(
    'emails.partials.button',
    [
        'url' => route('admin.books.show',$book->id),
        'slot' => 'Vérifier le livre'
    ]
)







<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    L'équipe KaMa vous remercie pour le suivi
    du processus éditorial.

</p>



@endsection