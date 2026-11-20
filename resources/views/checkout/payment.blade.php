@extends('layouts.app')

@section('content')
@php
    $momoReady = $pawaPayConfigured && $momoAvailable && $momoQuote;
@endphp
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
                    @if($order->country) · {{ $order->country->name }} @endif
                </p>
            </div>
            @if($stripeTestMode || $pawaPaySandbox)
                <span class="kkiapay-sandbox-badge">
                    <i class="bi bi-shield-check"></i>
                    Mode test
                </span>
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
                        <span>Total catalogue</span>
                        <strong>{{ number_format($order->amount, 2, '.', ',') }}€</strong>
                    </div>
                    @if($momoReady)
                        <div class="mt-2" style="font-size:.85rem;color:#777;">
                            Mobile Money ≈ <strong>{{ $momoQuote['label'] }}</strong>
                            ({{ $momoCountry['name'] }})
                        </div>
                    @endif
                </div>
            </aside>

            <section class="kkiapay-payment-card">
                <header>
                    <span><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Paiement sécurisé</small>
                        <h2>Choisissez votre moyen</h2>
                    </div>
                </header>

                @unless($stripeConfigured || $pawaPayConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>Aucun moyen de paiement configuré</strong>
                            <p>Renseignez Stripe et/ou PawaPay dans le fichier <code>.env</code>.</p>
                        </div>
                    </div>
                @endunless

                <div class="kkiapay-methods" role="radiogroup" aria-label="Moyen de paiement">
                    <label class="{{ $stripeConfigured ? '' : 'is-disabled' }}">
                        <input type="radio"
                               name="payment_method"
                               value="card"
                               @checked($stripeConfigured || ! $momoReady)
                               @disabled(! $stripeConfigured)>
                        <span class="kkiapay-method-icon card"><i class="bi bi-credit-card-2-front"></i></span>
                        <div>
                            <strong>Carte bancaire (EUR)</strong>
                            <small>Visa, Mastercard via Stripe</small>
                        </div>
                        <div class="kkiapay-card-logos">
                            <img src="{{ asset('assets/images/element/visa.svg') }}" alt="Visa">
                            <img src="{{ asset('assets/images/element/mastercard.svg') }}" alt="Mastercard">
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>

                    <label class="{{ $momoReady ? '' : 'is-disabled' }}">
                        <input type="radio"
                               name="payment_method"
                               value="momo"
                               @checked($momoReady && ! $stripeConfigured)
                               @disabled(! $momoReady)>
                        <span class="kkiapay-method-icon card"><i class="bi bi-phone"></i></span>
                        <div>
                            <strong>Mobile Money @if($momoReady)({{ $momoQuote['currency'] }})@endif</strong>
                            <small>
                                @if($momoReady)
                                    {{ $momoCountry['name'] }} — opérateurs locaux via PawaPay
                                @elseif($pawaPayConfigured)
                                    Indisponible pour {{ $order->country?->name ?? 'ce pays' }} — utilisez la carte
                                @else
                                    PawaPay non configuré
                                @endif
                            </small>
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>
                </div>

                @if($pawaPayConfigured && ! $momoAvailable)
                    <div class="kkiapay-config-warning" style="margin-top:12px;">
                        <i class="bi bi-info-circle"></i>
                        <div>
                            <strong>Mobile Money non disponible</strong>
                            <p>
                                Votre commande est enregistrée pour
                                <strong>{{ $order->country?->name ?? 'un autre pays' }}</strong>,
                                qui n’est pas couvert par PawaPay sur KaMa. Payez par carte bancaire.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong id="summaryAmount">{{ number_format($order->amount, 2, '.', ',') }}€</strong></div>
                    <div><span>Devise</span><strong id="summaryCurrency">EUR</strong></div>
                    <div class="total"><span>Total maintenant</span><strong id="summaryTotal">{{ number_format($order->amount, 2, '.', ',') }}€</strong></div>
                </div>

                <div id="paymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button"
                        id="openPayment"
                        class="kkiapay-pay-button"
                        @disabled(! $stripeConfigured && ! $momoReady)>
                    <span><i class="bi bi-lock-fill"></i> Payer maintenant</span>
                    <strong id="payButtonAmount">{{ number_format($order->amount, 2, '.', ',') }}€</strong>
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span id="payFooterText">Paiement sécurisé — carte (Stripe) ou Mobile Money (PawaPay).</span>
                </footer>
            </section>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
.kkiapay-methods label.is-disabled { opacity: .55; pointer-events: none; }
</style>
@endpush

@push('scripts')
@php
    $amountEurFormatted = number_format((float) $order->amount, 2, '.', '');
    $momoLabel = $momoQuote['label'] ?? '';
    $momoCurrency = $momoQuote['currency'] ?? '';
@endphp
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('openPayment');
    const feedback = document.getElementById('paymentFeedback');
    const methodInputs = document.querySelectorAll('input[name="payment_method"]');

    const amountEur = @json($amountEurFormatted);
    const momoLabel = @json($momoLabel);
    const momoCurrency = @json($momoCurrency);
    const stripeOk = @json((bool) $stripeConfigured);
    const momoOk = @json((bool) $momoReady);
    const momoCountryName = @json($momoCountry['name'] ?? '');

    const showFeedback = (message, type = 'error') => {
        feedback.className = `kkiapay-payment-feedback ${type}`;
        feedback.textContent = message;
    };

    const selectedMethod = () => {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        return checked ? checked.value : (stripeOk ? 'card' : 'momo');
    };

    const refreshUi = () => {
        const method = selectedMethod();
        const isMomo = method === 'momo';

        document.getElementById('summaryAmount').textContent = isMomo ? momoLabel : `${amountEur}€`;
        document.getElementById('summaryCurrency').textContent = isMomo ? momoCurrency : 'EUR';
        document.getElementById('summaryTotal').textContent = isMomo ? momoLabel : `${amountEur}€`;
        document.getElementById('payButtonAmount').textContent = isMomo ? momoLabel : `${amountEur}€`;
        document.getElementById('payFooterText').textContent = isMomo
            ? `Paiement Mobile Money via PawaPay (${momoCountryName} · ${momoCurrency}).`
            : 'Paiement carte sécurisé via Stripe (EUR).';

        const canPay = isMomo ? momoOk : stripeOk;
        payButton.disabled = !canPay;
        payButton.querySelector('span').innerHTML = isMomo
            ? '<i class="bi bi-phone"></i> Payer en Mobile Money'
            : '<i class="bi bi-credit-card"></i> Payer par carte';
    };

    methodInputs.forEach((input) => input.addEventListener('change', refreshUi));
    refreshUi();

    const setLoading = (loading) => {
        const method = selectedMethod();
        const canPay = method === 'momo' ? momoOk : stripeOk;
        payButton.disabled = loading || !canPay;
        payButton.classList.toggle('loading', loading);
        if (loading) {
            payButton.querySelector('span').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Redirection…';
        } else {
            refreshUi();
        }
    };

    payButton?.addEventListener('click', async () => {
        setLoading(true);
        feedback.classList.add('d-none');

        const method = selectedMethod();
        const body = new FormData();
        body.append('method', method);

        try {
            const response = await fetch(@json(route('checkout.pay', $order)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body,
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Impossible de préparer le paiement.');
            }
            if (!result.checkout_url) {
                throw new Error('URL de paiement manquante.');
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
