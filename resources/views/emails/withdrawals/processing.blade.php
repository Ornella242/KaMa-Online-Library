@extends('emails.layouts.app')

@section('content')

<h1 style="
    font-size:26px;
    line-height:1.3;
    margin:0 0 20px;
    color:#111827;
">
    Votre retrait est en cours de traitement ⏳
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
    margin:0 0 24px;
">
    Votre demande de retrait a bien été prise en charge par notre équipe financière.
    Elle est actuellement <strong>en cours de traitement</strong> et le versement sera effectué
    après vérification des coordonnées de réception.
</p>

<!-- RECAP CARD -->
<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:20px 0 30px;
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:14px;
            overflow:hidden;
       ">
    <tr>
        <td style="padding:20px 24px; border-bottom:1px solid #e5e7eb; background:#eff6ff;">
            <strong style="font-size:15px; color:#111827;">Demande #{{ $withdrawal->id }} — en cours</strong>
            <span style="float:right; font-size:13px; color:#6b7280;">
                {{ $withdrawal->processing_at ? $withdrawal->processing_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}
            </span>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px;">
            <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px; color:#4b5563;">
                <tr>
                    <td style="color:#6b7280; width:45%;">Montant brut demandé :</td>
                    <td style="text-align:right; font-weight:600; color:#111827;">
                        {{ number_format((float) $withdrawal->amount, 2, ',', ' ') }} €
                    </td>
                </tr>
                <tr>
                    <td style="color:#6b7280;">Commission KaMa ({{ $withdrawal->commission_percent }}%) :</td>
                    <td style="text-align:right; color:#dc2626;">
                        - {{ number_format((float) $withdrawal->commission_amount, 2, ',', ' ') }} €
                    </td>
                </tr>
                <tr style="border-top:1px dashed #d1d5db;">
                    <td style="color:#111827; font-weight:bold; font-size:15px; padding-top:10px;">
                        Montant net à recevoir :
                    </td>
                    <td style="text-align:right; font-weight:bold; font-size:16px; color:#16a34a; padding-top:10px;">
                        {{ number_format((float) $withdrawal->net_amount, 2, ',', ' ') }} €
                    </td>
                </tr>
                <tr>
                    <td style="color:#6b7280; padding-top:10px;">Mode de versement :</td>
                    <td style="text-align:right; font-weight:600; color:#111827; padding-top:10px;">
                        {{ $withdrawal->methodLabel() }}
                    </td>
                </tr>
                @if($withdrawal->account_number)
                <tr>
                    <td style="color:#6b7280;">Compte / Coordonnées :</td>
                    <td style="text-align:right; font-weight:600; color:#111827;">
                        {{ $withdrawal->account_number }}
                    </td>
                </tr>
                @endif
                @if($withdrawal->admin_note)
                <tr>
                    <td style="color:#6b7280;">Note :</td>
                    <td style="text-align:right; color:#4b5563;">
                        {{ $withdrawal->admin_note }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="color:#6b7280;">Statut actuel :</td>
                    <td style="text-align:right;">
                        <span style="
                            display:inline-block;
                            padding:3px 10px;
                            background:#dbeafe;
                            color:#1e40af;
                            border-radius:12px;
                            font-size:12px;
                            font-weight:600;
                        ">
                            {{ $withdrawal->statusLabel() }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- INFO -->
<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
            margin:0 0 30px;
            background:#eff6ff;
            border:1px solid #bfdbfe;
            border-left:5px solid #2563eb;
            border-radius:10px;
       ">
    <tr>
        <td style="padding:18px 20px;">
            <div style="
                font-size:14px;
                font-weight:bold;
                color:#1e3a8a;
                margin-bottom:8px;
            ">
                Et ensuite ?
            </div>
            <p style="
                font-size:13px;
                line-height:1.7;
                color:#1e40af;
                margin:0;
            ">
                Vous recevrez un nouvel e-mail dès que le versement aura été effectué.
                Si vous constatez une erreur dans vos coordonnées, contactez immédiatement
                l’équipe KaMa à l’adresse
                <strong>{{ config('mail.from.address', 'support@kama-library.com') }}</strong>.
            </p>
        </td>
    </tr>
</table>

<!-- ACTION BUTTON -->
<div align="center" style="margin:35px 0 20px;">
    <a href="{{ route('writer.wallet') }}"
       style="
            display:inline-block;
            background:#b30000;
            color:#ffffff;
            font-size:15px;
            font-weight:bold;
            text-decoration:none;
            padding:14px 28px;
            border-radius:8px;
            box-shadow:0 4px 14px rgba(179,0,0,0.35);
       ">
        Accéder à mon portefeuille KaMa
    </a>
</div>

<p style="
    font-size:13px;
    line-height:1.6;
    color:#9ca3af;
    text-align:center;
    margin:25px 0 0;
">
    Merci de votre confiance sur KaMa Online Library.<br>
    Cet e-mail est généré automatiquement suite à une opération sur votre compte.
</p>

@endsection
