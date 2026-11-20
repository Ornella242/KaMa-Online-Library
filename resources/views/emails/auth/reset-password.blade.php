<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Réinitialisation du mot de passe</title>

</head>

<body style="
    margin:0;
    padding:40px 20px;
    background:#f6f7fb;
    font-family:Arial,Helvetica,sans-serif;
    color:#444;
    ">

    <table width="100%" cellpadding="0" cellspacing="0" border="0">

        <tr>

            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0" border="0"
                    style="
                    background:#ffffff;
                    border-radius:18px;
                    overflow:hidden;
                    box-shadow:0 15px 40px rgba(0,0,0,.08);
                    ">

                <!-- HEADER -->
                    <tr>
                        <td align="center" style="
                            padding:45px;
                            background:linear-gradient(135deg,#b30000,#8d0000);
                            ">
                            <img src="{{ asset('assets/images/KaMa2.png') }}" width="170" alt="KaMa">
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:50px;">
                            <h3 style=" margin:0 0 20px; font-size:32px; color:#111;">
                            Bonjour {{ $user->firstname }},
                            </h3>

                            <p style="
                            font-size:16px;
                            line-height:1.8;
                            margin-bottom:25px;
                            ">
                                Nous avons reçu une demande de réinitialisation de votre mot de passe.
                            </p>

                            <p style="
                            font-size:16px;
                            line-height:1.8;
                            margin-bottom:35px;
                            ">

                            Cliquez simplement sur le bouton ci-dessous pour créer un nouveau mot de passe.

                            </p>

                            <div style="text-align:center;">

                                <a href="{{ $url }}" style="
                                display:inline-block;
                                padding:18px 40px;
                                background:#b30000;
                                color:#ffffff;
                                text-decoration:none;
                                border-radius:50px;
                                font-weight:bold;
                                font-size:16px;
                                ">

                                Réinitialiser mon mot de passe

                                </a>

                            </div>


                            <p style="
                                margin-top:40px;
                                font-size:15px;
                                line-height:1.8;
                                ">

                            Si vous n'êtes pas à l'origine de cette demande,
                            vous pouvez ignorer cet email en toute sécurité.

                            </p>

                            <hr style="
                            margin:40px 0;
                            border:none;
                            border-top:1px solid #eee;
                            ">

                            <p style="
                            font-size:14px;
                            color:#888;
                            line-height:1.8;
                            ">

                            Le lien expirera automatiquement après un certain délai pour protéger votre compte.

                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->

                    <tr>

                        <td
                        align="center"
                        style="
                        background:#fafafa;
                        padding:35px;
                        font-size:13px;
                        color:#888;
                        ">

                            <strong style="color:#b30000;">

                            KaMa Online Library

                            </strong>

                            <br><br>

                            Votre bibliothèque numérique africaine.

                            <br><br>

                            © {{ date('Y') }} KaMa.

                        </td>

                    </tr>


                </table>


            </td>

        </tr>

    </table>

</body>

</html>