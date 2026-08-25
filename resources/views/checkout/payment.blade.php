@extends('layouts.app')

@section('content')
<main class="kkiapay-checkout">
    <div class="container">
        <div class="kkiapay-checkout-heading">
            <div>
                <a href="{{ route('cart.index') }}">
                    <i class="bi bi-arrow-left"></i> Panier
                </a>
                <span>Commande {{ $order->reference }}</span>
                <h1>Payer ma commande</h1>
                <p>
                    {{ $order->fullName() }} · {{ $order->email }}
                    @if($order->phone) · {{ $order->phone }} @endif
                </p>
            </div>
            @if($stripeTestMode)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Stripe</span>
            @endif
        </div>

        <div class="kkiapay-checkout-layout">
            <aside class="kkiapay-order-card">
                <div class="kkiapay-order-content">
                    <small>Articles</small>
                    <h2>{{ $order->items->count() }} livre(s)</h2>
                    @foreach($order->items as $item)
                        <div>
                            <span>{{ $item->title }}</span>
                            <strong>{{ number_format($item->unit_price, 2, '.', ',') }}€</strong>
                        </div>
                    @endforeach
                    <div class="total">
                        <span>Total à payer</span>
                        <strong>{{ number_format($order->amount, 2, '.', ',') }}€</strong>
                    </div>
                </div>
            </aside>

            <section class="kkiapay-payment-card">
                <header>
                    <span><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Paiement sécurisé</small>
                        <h2>Options de paiement</h2>
                    </div>
                </header>

                @unless($stripeConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>Stripe n’est pas encore configuré</strong>
                            <p>Ajoutez <code>STRIPE_SECRET</code> et <code>STRIPE_WEBHOOK_SECRET</code> dans le fichier <code>.env</code>.</p>
                        </div>
                    </div>
                @endunless

                <div class="kkiapay-methods" role="radiogroup" aria-label="Moyen de paiement">
                    <label>
                        <input type="radio" name="payment_method" value="card" checked>
                        <span class="kkiapay-method-icon card"><i class="bi bi-credit-card-2-front"></i></span>
                        <div>
                            <strong>Carte bancaire (EUR)</strong>
                            <small>Visa, Mastercard, Apple Pay via Stripe</small>
                        </div>
                        <div class="kkiapay-card-logos">
                            <img src="{{ asset('assets/images/element/visa.svg') }}" alt="Visa">
                            <img src="{{ asset('assets/images/element/mastercard.svg') }}" alt="Mastercard">
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>
                </div>

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong>{{ number_format($order->amount, 2, '.', ',') }}€</strong></div>
                    <div><span>Devise</span><strong>EUR</strong></div>
                    <div class="total"><span>Total maintenant</span><strong>{{ number_format($order->amount, 2, '.', ',') }}€</strong></div>
                </div>

                <div id="paymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button"
                        id="openStripePayment"
                        class="kkiapay-pay-button"
                        @disabled(! $stripeConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec Stripe</span>
                    <strong>{{ number_format($order->amount, 2, '.', ',') }}€</strong>
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span>Le paiement est traité de façon sécurisée par Stripe (EUR).</span>
                </footer>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('openStripePayment');
    const feedback = document.getElementById('paymentFeedback');

    const showFeedback = (message, type = 'error') => {
        feedback.className = `kkiapay-payment-feedback ${type}`;
        feedback.textContent = message;
    };

    const setLoading = (loading) => {
        payButton.disabled = loading || {{ $stripeConfigured ? 'false' : 'true' }};
        payButton.classList.toggle('loading', loading);
        payButton.querySelector('span').innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm"></span> Redirection…'
            : '<i class="bi bi-lock-fill"></i> Payer avec Stripe';
    };

    payButton?.addEventListener('click', async () => {
        setLoading(true);
        feedback.classList.add('d-none');

        try {
            const response = await fetch(@json(route('checkout.pay', $order)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Impossible de préparer le paiement.');
            }
            if (!result.checkout_url) {
                throw new Error('URL de paiement Stripe manquante.');
            }

            window.location.assign(result.checkout_url);
        } catch (error) {
            showFeedback(error.message, 'error');
            setLoading(false);
        }
    });
});
</script>
@endpush
