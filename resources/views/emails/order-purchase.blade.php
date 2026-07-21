<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vos livres KaMa</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.08);">
                <tr>
                    <td align="center" style="background:#b30000;padding:32px 20px;">
                        <h1 style="color:#ffffff;font-size:24px;margin:0;">Merci pour votre achat</h1>
                        <p style="color:#ffd6d6;margin:10px 0 0;font-size:14px;">Commande {{ $order->reference }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 40px;color:#333333;">
                        <p style="font-size:15px;line-height:1.7;color:#4b5563;margin-top:0;">
                            Bonjour {{ $order->firstname }},
                        </p>
                        <p style="font-size:15px;line-height:1.7;color:#4b5563;">
                            Votre paiement a bien été confirmé. Téléchargez vos livres ci-dessous.
                            Les liens restent valides pendant <strong>14 jours</strong>.
                        </p>

                        @forelse($downloads as $download)
                            <div style="border:1px solid #eceef0;border-radius:12px;padding:18px;margin:18px 0;">
                                <p style="margin:0 0 4px;font-size:12px;color:#b30000;font-weight:bold;text-transform:uppercase;">
                                    {{ $download['type'] }}
                                </p>
                                <p style="margin:0 0 14px;font-size:16px;font-weight:bold;color:#111;">
                                    {{ $download['title'] }}
                                </p>
                                <a href="{{ $download['url'] }}"
                                   style="background:#b30000;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:999px;font-size:14px;font-weight:bold;display:inline-block;">
                                    Télécharger
                                </a>
                            </div>
                        @empty
                            <p style="font-size:15px;line-height:1.7;color:#4b5563;">
                                Votre commande est confirmée. Contactez le support KaMa si vous ne trouvez pas vos fichiers.
                            </p>
                        @endforelse

                        <p style="font-size:13px;line-height:1.6;color:#6b7280;margin-bottom:0;">
                            Conservez cet email : il contient vos liens d’accès personnels.
                            Ne les partagez pas.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f9fafb;padding:22px;text-align:center;color:#6b7280;font-size:13px;">
                        <p style="margin:0;">© {{ date('Y') }} KaMa. Tous droits réservés.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
