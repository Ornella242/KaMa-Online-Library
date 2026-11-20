@extends('emails.layouts.app')


@section('content')


<h3 style="
    font-size:32px;
    line-height:1.3;
    margin:0 0 25px;
    color:#111827;">

    Bonjour {{ $user->firstname }},

</h3>



<p style="
    font-size:16px;
    line-height:1.9;
    margin-bottom:20px;
">

    Nous avons reçu une demande de réinitialisation
    de votre mot de passe KaMa.

</p>



<p style="
    font-size:16px;
    line-height:1.9;
">

    Cliquez sur le bouton ci-dessous pour créer
    un nouveau mot de passe sécurisé.

</p>




@include(
    'emails.partials.button',
    [
        'url' => $url,
        'slot' => 'Réinitialiser mon mot de passe'
    ]
)



<table width="100%" style="margin-top:30px;">
    <tr>
        <td style="
            background:#fff7ed;
            border-left:4px solid #b30000;
            padding:18px 20px;
            border-radius:10px;
            font-size:14px;
            line-height:1.7;
            color:#555;">

            Le lien de réinitialisation expirera automatiquement.
            Si vous n'avez pas demandé cette modification,
            vous pouvez ignorer cet email.

        </td>
    </tr>
</table>


<p style="
    font-size:14px;
    color:#777;
    margin-top:35px;
    line-height:1.8;">

    Si le bouton ne fonctionne pas,
    copiez ce lien dans votre navigateur :

    <br><br>

    <a href="{{ $url }}"
        style="
            color:#b30000;
            word-break:break-all;">
        {{ $url }}
    </a>
</p>

@endsection