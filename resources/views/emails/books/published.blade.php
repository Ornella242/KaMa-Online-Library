@extends('emails.layouts.app')


@section('content')


<h2 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;
">

    Félicitations {{ $user->firstname }} 🎉

</h2>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Votre livre a été validé et est maintenant disponible
    sur KaMa.

</p>



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

                            Votre publication est maintenant visible
                            par les lecteurs KaMa.

                        </p>


                    </td>


                </tr>


            </table>


        </td>

    </tr>


</table>





<p style="
    font-size:16px;
    line-height:1.9;
">

    Les lecteurs peuvent maintenant découvrir votre œuvre,
    l'ajouter à leur bibliothèque et commencer leur lecture.
</p>




@include(
    'emails.partials.button',
    [
        'url' => route('books.show',$book),
        'slot' => 'Voir mon livre'
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
            padding:18px 20px;
            border-radius:10px;
            font-size:14px;
            color:#555;
        ">

            Merci de contribuer à enrichir la bibliothèque
            numérique KaMa avec votre créativité.
        </td>


    </tr>


</table>



@endsection