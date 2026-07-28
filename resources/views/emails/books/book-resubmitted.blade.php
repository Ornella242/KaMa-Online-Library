@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Nouvelle soumission reçue 📚

</h1>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Bonjour {{ $user->firstname }},

</p>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Un auteur a effectué les modifications demandées
    et a soumis à nouveau son livre pour une nouvelle
    vérification éditoriale.

</p>


<!-- BOOK INFORMATION CARD -->


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
                            font-size:14px;
                            color:#777;
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


<!-- STATUS BOX -->


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

                Action requise :

            </strong>


            <br>


            Effectuer une nouvelle vérification
            éditoriale avant validation.


        </td>


    </tr>


</table>


@include(
    'emails.partials.button',
    [
        'url' => route('admin.books.show',$book),
        'slot' => 'Voir le livre'
    ]
)



<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    Merci de procéder à une nouvelle vérification
    éditoriale afin de poursuivre le processus
    de publication KaMa.

</p>



@endsection