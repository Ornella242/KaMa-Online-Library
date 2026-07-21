@extends('layouts.writer')

@section('writer-content')
<main class="kkiapay-checkout">
    <div class="container">
        <div class="kkiapay-checkout-heading">
            <div>
                <a href="{{ route('writer.books.sponsor', $sponsorship->book) }}">
                    <i class="bi bi-arrow-left"></i> Formules
                </a>
                <span>Sponsoring {{ $sponsorship->transaction_reference }}</span>
                <h1>Payer la mise en avant</h1>
                <p>{{ $sponsorship->book?->title }} · {{ $sponsorship->plan?->name }}</p>
            </div>
            @if($kkiapaySandbox)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Sandbox</span>
            @endif
        </div>

        <div class="kkiapay-checkout-layout">
            <aside class="kkiapay-order-card">
                <div class="kkiapay-order-content">
                    <small>Récapitulatif</small>
                    <h2>{{ $sponsorship->plan?->name ?? 'Formule' }}</h2>
                    <div>
                        <span>Livre</span>
                        <strong>{{ $sponsorship->book?->title }}</strong>
                    </div>
                    <div>
                        <span>Durée</span>
                        <strong>{{ $sponsorship->plan?->duration_days }} jours</strong>
                    </div>
                    <div class="total">
                        <span>Total à payer</span>
                        <strong>{{ number_format($sponsorship->amount, 0, ',', ' ') }} FCFA</strong>
                    </div>
                </div>
            </aside>

            <section class="kkiapay-payment-card">
                <header>
                    <span><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Paiement sécurisé</small>
                        <h2>Carte bancaire</h2>
                    </div>
                </header>

                @unless($kkiapayConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>KKiaPay n’est pas encore configuré</strong>
                            <p>Ajoutez les clés dans le fichier <code>.env</code>.</p>
                        </div>
                    </div>
                @endunless

                <div class="kkiapay-methods" role="radiogroup" aria-label="Moyen de paiement">
                    <label>
                        <input type="radio" name="payment_method" value="card" checked>
                        <span class="kkiapay-method-icon card"><i class="bi bi-credit-card-2-front"></i></span>
                        <div>
                            <strong>Carte bancaire</strong>
                            <small>Visa ou Mastercard via KKiaPay</small>
                        </div>
                        <div class="kkiapay-card-logos">
                            <img src="{{ asset('assets/images/element/visa.svg') }}" alt="Visa">
                            <img src="{{ asset('assets/images/element/mastercard.svg') }}" alt="Mastercard">
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>
                </div>

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong>{{ number_format($sponsorship->amount, 0, ',', ' ') }} FCFA</strong></div>
                    <div class="total"><span>Total maintenant</span><strong>{{ number_format($sponsorship->amount, 0, ',', ' ') }} FCFA</strong></div>
                </div>

                <div id="kkiapayPaymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button" id="openKkiapayPayment" class="kkiapay-pay-button" @disabled(! $kkiapayConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec KKiaPay</span>
                    <strong>{{ number_format($sponsorship->amount, 0, ',', ' ') }} FCFA</strong>
                </button>

                <button type="button" id="retryKkiapayVerification" class="kkiapay-retry-button d-none">
                    <i class="bi bi-arrow-clockwise"></i> Réessayer la vérification
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span>Après paiement, l’équipe KaMa valide avant mise en ligne de la publicité.</span>
                </footer>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('openKkiapayPayment');
    const retryButton = document.getElementById('retryKkiapayVerification');
    const feedback = document.getElementById('kkiapayPaymentFeedback');
    let pendingTransactionId = null;

    const showFeedback = (message, type = 'error') => {
        feedback.className = `kkiapay-payment-feedback ${type}`;
        feedback.textContent = message;
    };

    const setLoading = (loading) => {
        payButton.disabled = loading || {{ $kkiapayConfigured ? 'false' : 'true' }};
        payButton.classList.toggle('loading', loading);
        payButton.querySelector('span').innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm"></span> Préparation…'
            : '<i class="bi bi-lock-fill"></i> Payer avec KKiaPay';
    };

    const verifyTransaction = async (transactionId) => {
        pendingTransactionId = transactionId;
        retryButton.classList.add('d-none');
        showFeedback('Paiement reçu. Vérification sécurisée en cours…', 'loading');

        try {
            const response = await fetch(@json(route('writer.sponsorship.verify', $sponsorship)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify({ transaction_id: transactionId }),
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Vérification impossible.');
            showFeedback(result.message, 'success');
            window.setTimeout(() => window.location.assign(result.redirect), 900);
        } catch (error) {
            showFeedback(error.message, 'error');
            retryButton.classList.remove('d-none');
        }
    };

    if (typeof addSuccessListener === 'function') {
        addSuccessListener((response) => {
            const transactionId = response?.transactionId || response?.transaction_id;
            if (!transactionId) {
                showFeedback('KKiaPay n’a pas retourné de référence de transaction.', 'error');
                return;
            }
            verifyTransaction(transactionId);
        });
    }

    payButton?.addEventListener('click', async () => {
        setLoading(true);
        feedback.classList.add('d-none');
        try {
            const response = await fetch(@json(route('writer.sponsorship.pay', $sponsorship)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Impossible de préparer le paiement.');
            if (typeof openKkiapayWidget !== 'function') throw new Error('Le service KKiaPay n’a pas pu être chargé.');

            openKkiapayWidget({
                amount: result.payment.amount,
                key: @json($kkiapayPublicKey),
                sandbox: @json($kkiapaySandbox),
                position: 'center',
                theme: '#b30000',
                paymentmethod: 'card',
                partnerId: result.payment.reference,
                name: result.customer.name,
                email: result.customer.email,
                phone: result.customer.phone,
                data: { sponsorship_id: @json($sponsorship->id) },
            });
        } catch (error) {
            showFeedback(error.message, 'error');
        } finally {
            setLoading(false);
        }
    });

    retryButton?.addEventListener('click', () => {
        if (pendingTransactionId) verifyTransaction(pendingTransactionId);
    });
});
</script>
@endpush
