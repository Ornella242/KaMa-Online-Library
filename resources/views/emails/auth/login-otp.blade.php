@extends('emails.layouts.app')

@section('content')

<h1 style="
    font-size:26px;
    line-height:1.3;
    margin:0 0 20px;
    color:#111827;
">
    Votre code de connexion KaMa 🔐
</h1>

<p style="
    font-size:16px;
    line-height:1.8;
    color:#374151;
    margin:0 0 16px;
">
    Bonjour <strong>{{ $user->firstname }} {{ $user->lastname }}</strong>,
</p>

<p style="
    font-size:16px;
    line-height:1.8;
    color:#374151;
    margin:0 0 25px;
">
    Une tentative de connexion à votre compte <strong>KaMa Online Library</strong> a été initiée.
    Pour vérifier votre identité et finaliser votre connexion en toute sécurité, veuillez renseigner le code à 6 chiffres ci-dessous :
</p>

<!-- CODE DISPLAY BOX -->
<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:25px 0 30px;
            background:#fff5f5;
            border:2px dashed #b30000;
            border-radius:14px;
       ">
    <tr>
        <td align="center" style="padding:28px 20px;">
            <div style="
                font-size:12px;
                text-transform:uppercase;
                letter-spacing:1.5px;
                color:#991b1b;
                font-weight:bold;
                margin-bottom:10px;
            ">
                Code de vérification (OTP)
            </div>

            <div style="
                font-family:'Courier New', Courier, monospace, Arial;
                font-size:40px;
                font-weight:bold;
                letter-spacing:10px;
                color:#b30000;
                padding-left:10px;
                line-height:1.2;
            ">
                {{ $code }}
            </div>

            <div style="
                margin-top:12px;
                font-size:13px;
                color:#7f1d1d;
                font-weight:500;
            ">
                ⏱️ Valable pendant <strong>10 minutes</strong>
            </div>
        </td>
    </tr>
</table>

<!-- SECURITY NOTICE -->
<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:0 0 25px;
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-left:4px solid #6b7280;
            border-radius:10px;
       ">
    <tr>
        <td style="padding:16px 20px;">
            <strong style="font-size:13px; color:#111827; display:block; margin-bottom:6px;">
                🛡️ Information de sécurité
            </strong>
            <p style="
                font-size:13px;
                line-height:1.7;
                color:#4b5563;
                margin:0;
            ">
                Si vous <strong>n'êtes pas à l'origine de cette demande de connexion</strong>, ne communiquez ce code à personne.
                Votre compte est protégé tant que ce code reste secret. Par mesure de précaution, nous vous recommandons de
                modifier votre mot de passe immédiatement sur KaMa.
            </p>
        </td>
    </tr>
</table>

<p style="
    font-size:13px;
    line-height:1.6;
    color:#9ca3af;
    text-align:center;
    margin:25px 0 0;
">
    Merci de votre fidélité sur KaMa Online Library.<br>
    Cet e-mail automatique est destiné à sécuriser l'accès à votre compte.
</p>

@endsection
