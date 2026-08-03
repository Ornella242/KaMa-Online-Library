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
            @if($lemonTestMode)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Lemon Squeezy</span>
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
                            <strong>${{ number_format($item->unit_price, 2, '.', ',') }}</strong>
                        </div>
                    @endforeach
                    <div class="total">
                        <span>Total à payer</span>
                        <strong>${{ number_format($order->amount, 2, '.', ',') }}</strong>
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

                @unless($lemonConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>Lemon Squeezy n’est pas encore configuré</strong>
                            <p>Ajoutez <code>LEMON_SQUEEZY_API_KEY</code>, <code>LEMON_SQUEEZY_STORE_ID</code> et <code>LEMON_SQUEEZY_VARIANT_ID</code> dans le fichier <code>.env</code>.</p>
                        </div>
                    </div>
                @endunless

                <div class="kkiapay-methods" role="radiogroup" aria-label="Moyen de paiement">
                    <label>
                        <input type="radio" name="payment_method" value="card" checked>
                        <span class="kkiapay-method-icon card"><i class="bi bi-credit-card-2-front"></i></span>
                        <div>
                            <strong>Carte bancaire (USD)</strong>
                            <small>Visa, Mastercard, Apple Pay, PayPal via Lemon Squeezy</small>
                        </div>
                        <div class="kkiapay-card-logos">
                            <img src="{{ asset('assets/images/element/visa.svg') }}" alt="Visa">
                            <img src="{{ asset('assets/images/element/mastercard.svg') }}" alt="Mastercard">
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>
                </div>

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong>${{ number_format($order->amount, 2, '.', ',') }}</strong></div>
                    <div><span>Devise</span><strong>USD</strong></div>
                    <div class="total"><span>Total maintenant</span><strong>${{ number_format($order->amount, 2, '.', ',') }}</strong></div>
                </div>

                <div id="paymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button"
                        id="openLemonPayment"
                        class="kkiapay-pay-button"
                        @disabled(! $lemonConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec Lemon Squeezy</span>
                    <strong>${{ number_format($order->amount, 2, '.', ',') }}</strong>
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span>Le paiement est traité de façon sécurisée par Lemon Squeezy (USD).</span>
                </footer>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://app.lemonsqueezy.com/js/lemon.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('openLemonPayment');
    const feedback = document.getElementById('paymentFeedback');
    let pollTimer = null;

    const showFeedback = (message, type = 'error') => {
        feedback.className = `kkiapay-payment-feedback ${type}`;
        feedback.textContent = message;
    };

    const setLoading = (loading) => {
        payButton.disabled = loading || {{ $lemonConfigured ? 'false' : 'true' }};
        payButton.classList.toggle('loading', loading);
        payButton.querySelector('span').innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm"></span> Préparation…'
            : '<i class="bi bi-lock-fill"></i> Payer avec Lemon Squeezy';
    };

    const pollVerification = async () => {
        try {
            const response = await fetch(@json(route('checkout.verify', $order)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify({}),
            });
            const result = await response.json();

            if (response.ok && result.redirect) {
                showFeedback(result.message || 'Paiement confirmé.', 'success');
                window.clearInterval(pollTimer);
                window.setTimeout(() => window.location.assign(result.redirect), 700);
            }
        } catch (e) {
            // keep polling briefly
        }
    };

    window.createLemonSqueezy?.();
    if (window.LemonSqueezy?.Setup) {
        window.LemonSqueezy.Setup({
            eventHandler: (event) => {
                if (event?.event === 'Checkout.Success') {
                    showFeedback('Paiement reçu. Confirmation en cours…', 'loading');
                    pollVerification();
                    pollTimer = window.setInterval(pollVerification, 2000);
                    window.setTimeout(() => window.clearInterval(pollTimer), 30000);
                }
            }
        });
    }

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
                throw new Error('URL de paiement Lemon Squeezy manquante.');
            }

            if (window.LemonSqueezy?.Url?.Open) {
                window.LemonSqueezy.Url.Open(result.checkout_url);
            } else {
                window.location.assign(result.checkout_url);
            }
        } catch (error) {
            showFeedback(error.message, 'error');
        } finally {
            setLoading(false);
        }
    });
});
</script>
@endpush
