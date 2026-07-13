<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur KaMa</title>
</head>

<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:40px 0;">
    <tr>
        <td align="center">

            <!-- Container -->
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.08);">


                <!-- Header -->
                <tr>
                    <td align="center"
                        style="background:#4f46e5;padding:35px 20px;">

                        <img src="{{ asset('assets/images/logo-light.svg') }}"
                             alt="KaMa"
                             width="150"
                             style="display:block;margin-bottom:20px;">

                        <h1 style="color:#ffffff;font-size:26px;margin:0;">
                            Bienvenue sur KaMa
                        </h1>

                    </td>
                </tr>


                <!-- Content -->
                <tr>
                    <td style="padding:40px 45px;color:#333333;">

                        <h2 style="font-size:22px;margin-top:0;color:#111827;">
                            Bonjour {{ $user->firstname }},
                        </h2>


                        <p style="font-size:15px;line-height:1.7;color:#4b5563;">
                            Votre compte KaMa a été créé avec succès.
                            Vous pouvez maintenant accéder à votre espace personnel
                            et profiter de toutes les fonctionnalités de la plateforme.
                        </p>


                        <div style="
                            background:#f5d6d6;
                            border-radius:12px;
                            padding:25px;
                            margin:30px 0;
                            text-align:center;
                        ">

                            <p style="
                                margin:0 0 10px;
                                color:#6b7280;
                                font-size:14px;
                            ">
                                Votre mot de passe temporaire
                            </p>


                            <div style="
                                display:inline-block;
                                background:#ffffff;
                                border:1px dashed #b30000;
                                padding:15px 30px;
                                border-radius:10px;
                                font-size:22px;
                                font-weight:bold;
                                letter-spacing:2px;
                                color:#b30000;
                            ">
                                {{ $password }}
                            </div>

                        </div>


                        <p style="font-size:15px;line-height:1.7;color:#4b5563;">
                            Pour votre sécurité, nous vous recommandons de modifier
                            ce mot de passe après votre première connexion.
                        </p>


                        <div style="text-align:center;margin-top:35px;">

                            <a href="#"
                               style="
                               background:#b30000;
                               color:#ffffff;
                               text-decoration:none;
                               padding:14px 30px;
                               border-radius:30px;
                               font-size:15px;
                               font-weight:bold;
                               display:inline-block;
                               ">
                                Accéder à KaMa
                            </a>

                        </div>

                    </td>
                </tr>


                <!-- Footer -->
                <tr>
                    <td style="
                        background:#f9fafb;
                        padding:25px;
                        text-align:center;
                        color:#6b7280;
                        font-size:13px;
                    ">

                        <p style="margin:0 0 8px;">
                            © {{ date('Y') }} KaMa. Tous droits réservés.
                        </p>

                        <p style="margin:0;">
                            La plateforme dédiée aux lecteurs et écrivains.
                        </p>

                    </td>
                </tr>


            </table>

        </td>
    </tr>
</table>


</body>
</html>