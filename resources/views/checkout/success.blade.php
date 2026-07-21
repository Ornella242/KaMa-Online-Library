@extends('layouts.app')

@section('content')
<section class="kama-checkout-page py-5">
    <div class="container">
        <div class="kama-checkout-success">
            <span class="icon"><i class="bi bi-check-circle-fill"></i></span>
            <h1>Paiement confirmé</h1>
            <p>Merci {{ $order->firstname }} ! Votre commande <strong>{{ $order->reference }}</strong> a bien été enregistrée.</p>

            <div class="kama-success-card">
                <h2>Télécharger vos livres</h2>
                <ul class="downloads">
                    @forelse($downloads as $download)
                        <li>
                            <div>
                                <small>{{ $download['type'] }}</small>
                                <span>{{ $download['title'] }}</span>
                            </div>
                            <a href="{{ $download['url'] }}" class="btn btn-danger btn-sm">
                                <i class="bi bi-download"></i> Télécharger
                            </a>
                        </li>
                    @empty
                        <li class="empty">Fichiers indisponibles pour le moment — consultez votre email ou contactez le support.</li>
                    @endforelse
                </ul>

                <div class="total">
                    <span>Total payé</span>
                    <strong>{{ number_format($order->amount, 0, ',', ' ') }} XOF</strong>
                </div>
                <p class="meta">
                    Un email avec les mêmes liens a été envoyé à <strong>{{ $order->email }}</strong>
                    (valables 14 jours).
                    @guest
                        Vous pouvez aussi créer un compte plus tard avec cet email.
                    @endguest
                </p>
            </div>

            <div class="actions">
                <a href="{{ route('catalogue') }}" class="btn btn-danger">Continuer à explorer</a>
                @auth
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Retour à l’accueil</a>
                @else
                    <a href="{{ route('register.form') }}" class="btn btn-outline-secondary">Créer un compte</a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.kama-checkout-page { background:#f6f7f9; min-height:70vh; }
.kama-checkout-success { max-width:640px; margin:0 auto; text-align:center; }
.kama-checkout-success .icon { display:grid; width:72px; height:72px; margin:0 auto 16px; place-items:center; border-radius:20px; background:#e8f8ef; color:#1a7f4b; font-size:2rem; }
.kama-checkout-success h1 { margin:0 0 8px; font-size:1.9rem; }
.kama-checkout-success > p { color:#666; margin-bottom:24px; }
.kama-success-card { text-align:left; background:#fff; border:1px solid #eceef0; border-radius:16px; padding:22px; margin-bottom:22px; }
.kama-success-card h2 { margin:0 0 14px; font-size:1.1rem; }
.kama-success-card ul.downloads { list-style:none; margin:0; padding:0; }
.kama-success-card ul.downloads li { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #f0f1f3; color:#555; }
.kama-success-card ul.downloads li:last-child { border-bottom:0; margin-bottom:0; padding-bottom:0; }
.kama-success-card ul.downloads li small { display:block; color:#b30000; font-size:.72rem; font-weight:800; text-transform:uppercase; }
.kama-success-card ul.downloads li span { display:block; color:#111; font-weight:600; }
.kama-success-card ul.downloads li.empty { display:block; border:0; color:#888; }
.kama-success-card .total { display:flex; justify-content:space-between; margin-top:14px; padding-top:14px; border-top:1px solid #eee; font-size:1.05rem; }
.kama-success-card .meta { margin:14px 0 0; color:#888; font-size:.86rem; }
.kama-checkout-success .actions { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
</style>
@endpush
