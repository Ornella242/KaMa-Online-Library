@extends('layouts.app')

@section('content')
<section class="kama-checkout-page py-5">
    <div class="container">
        <div class="kama-checkout-head mb-4">
            <a href="{{ route('cart.index') }}" class="kama-cart-back">
                <i class="bi bi-arrow-left"></i> Retour au panier
            </a>
            <h1>Finaliser ma commande</h1>
            <p>Renseignez vos informations pour payer en USD via Lemon Squeezy — aucun compte n’est requis.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <form method="POST" action="{{ route('checkout.store') }}" class="kama-checkout-form">
                    @csrf
                    <h2>Vos coordonnées</h2>
                    <p class="hint">Ces informations serviront à confirmer votre achat et vous contacter si besoin.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstname" class="form-label">Prénom *</label>
                            <input type="text" name="firstname" id="firstname" class="form-control"
                                   value="{{ old('firstname', $user?->firstname) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Nom *</label>
                            <input type="text" name="lastname" id="lastname" class="form-control"
                                   value="{{ old('lastname', $user?->lastname) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $user?->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                   value="{{ old('phone', $user?->phone) }}" placeholder="Ex. 22901xxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label for="country_id" class="form-label">Pays *</label>
                            <select name="country_id" id="country_id" class="form-select" required>
                                <option value="">Sélectionner</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                        @selected((string) old('country_id', $user?->country_id) === (string) $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">Ville *</label>
                            <input type="text" name="city" id="city" class="form-control"
                                   value="{{ old('city', $user?->city) }}" required>
                        </div>
                    </div>

                    @guest
                        <p class="login-hint mt-3 mb-0">
                            Déjà un compte ?
                            <a href="{{ route('login') }}">Connectez-vous</a>
                            pour préremplir vos infos — ce n’est pas obligatoire pour acheter.
                        </p>
                    @endguest

                    <button type="submit" class="btn btn-danger w-100 mt-4">
                        Continuer vers le paiement
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                <aside class="kama-cart-summary">
                    <h2>Récapitulatif</h2>
                    <ul class="kama-checkout-lines">
                        @foreach($items as $item)
                            <li>
                                <span>{{ $item['title'] }}</span>
                                <strong>${{ number_format($item['price'], 2, '.', ',') }}</strong>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <div class="line total">
                        <span>Total</span>
                        <strong>${{ number_format($total, 2, '.', ',') }}</strong>
                    </div>
                    <p class="hint">Paiement sécurisé par carte bancaire via Lemon Squeezy (USD).</p>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.kama-checkout-page { background:#f6f7f9; min-height:70vh; }
.kama-cart-back { display:inline-flex; align-items:center; gap:6px; color:#b30000; font-weight:700; font-size:.9rem; text-decoration:none; }
.kama-checkout-head h1 { margin:10px 0 4px; font-size:1.8rem; }
.kama-checkout-head p { margin:0; color:#777; }
.kama-checkout-form { background:#fff; border:1px solid #eceef0; border-radius:16px; padding:24px; }
.kama-checkout-form h2 { margin:0 0 6px; font-size:1.2rem; }
.kama-checkout-form .hint { color:#888; font-size:.85rem; margin-bottom:18px; }
.kama-checkout-form .login-hint { color:#666; font-size:.88rem; }
.kama-checkout-form .login-hint a { color:#b30000; font-weight:700; }
.kama-cart-summary { padding:20px; background:#fff; border-radius:16px; border:1px solid #eceef0; position:sticky; top:90px; }
.kama-cart-summary h2 { margin:0 0 16px; font-size:1.15rem; }
.kama-checkout-lines { list-style:none; margin:0; padding:0; }
.kama-checkout-lines li { display:flex; justify-content:space-between; gap:12px; margin-bottom:10px; color:#555; font-size:.92rem; }
.kama-checkout-lines li span { flex:1; min-width:0; }
.kama-cart-summary .line { display:flex; justify-content:space-between; }
.kama-cart-summary .total { font-size:1.1rem; color:#111; }
.kama-cart-summary .hint { margin:12px 0 0; color:#888; font-size:.78rem; }
</style>
@endpush
