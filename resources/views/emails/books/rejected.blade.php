@extends('emails.layouts.app')


@section('content')


<h1 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;">
    Bonjour {{ $user->firstname }},
</h1>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Après vérification éditoriale, votre livre nécessite des ajustements
    avant la publication officielle sur KaMa. Veuillez lire le motif de rejet et procéder aux modifications nécessaires.

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
                            font-size:14px;
                            color:#777;
                        ">

                            Votre manuscrit nécessite quelques ajustements.

                        </p>


                    </td>


                </tr>


            </table>


        </td>


    </tr>


</table>




<!-- REASON BOX -->


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

                Motif du rejet :

            </strong>


            <br><br>


            {{ $book->rejection_reason }}


        </td>


    </tr>


</table>




<p style="
    margin-top:35px;
    font-size:16px;
    line-height:1.9;
">

    Vous pouvez apporter les modifications demandées
    puis soumettre votre livre à nouveau pour une nouvelle vérification.

</p>




@include(
    'emails.partials.button',
    [
        'url' => route('writer.books.edit',$book),
        'slot' => 'Modifier mon livre'
    ]
)




<p style="
    margin-top:35px;
    font-size:14px;
    color:#777;
    line-height:1.8;
">

    Notre équipe éditoriale reste disponible si vous avez
    besoin d'informations complémentaires.

</p>



@endsection