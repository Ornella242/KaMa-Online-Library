@extends('layouts.app')

@section('content')
<main class="kkiapay-checkout">
    <div class="container">
        <div class="kkiapay-checkout-heading">
            <div>
                <a href="{{ route('writer.books') }}">
                    <i class="bi bi-arrow-left"></i> Ma bibliothèque
                </a>
                <span>Publication KaMa</span>
                <h1>Finaliser les frais de publication</h1>
                <p>Payez les frais de dépôt en EUR via Stripe pour soumettre votre livre à la vérification.</p>
            </div>
            @if($stripeTestMode)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Stripe</span>
            @endif
        </div>

        <div class="kkiapay-checkout-layout">
            <aside class="kkiapay-order-card">
                <div class="kkiapay-order-cover">
                    <img src="{{ $book->cover_image
                        ? asset('storage/'.$book->cover_image)
                        : asset('assets/images/book/01.jpg') }}"
                         alt="Couverture de {{ $book->title }}">
                    <span>
                        <i class="bi {{ $book->type === 'audio' ? 'bi-headphones' : 'bi-file-earmark-text' }}"></i>
                        {{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}
                    </span>
                </div>

                <div class="kkiapay-order-content">
                    <small>Ouvrage concerné</small>
                    <h2>{{ $book->title }}</h2>
                    <div><span>Statut actuel</span><strong>Brouillon</strong></div>
                    <div><span>Frais d’enregistrement</span><strong>${{ number_format($fee->amount, 2, '.', ',') }}</strong></div>
                    <div class="total"><span>Total à payer</span><strong>${{ number_format($fee->amount, 2, '.', ',') }}</strong></div>
                </div>
            </aside>

            <section class="kkiapay-payment-card">
                <header>
                    <span><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Paiement sécurisé</small>
                        <h2>Stripe (EUR)</h2>
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

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong>{{ number_format($fee->amount, 2, '.', ',') }}€</strong></div>
                    <div><span>Devise</span><strong>EUR</strong></div>
                    <div class="total"><span>Total maintenant</span><strong>{{ number_format($fee->amount, 2, '.', ',') }}€</strong></div>
                </div>

                <div id="paymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button"
                        id="openStripePayment"
                        class="kkiapay-pay-button"
                        @disabled(! $stripeConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec Stripe</span>
                    <strong>{{ number_format($fee->amount, 2, '.', ',') }}€</strong>
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span>Le paiement est traité de façon sécurisée par Stripe.</span>
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
        try {
            const response = await fetch(@json(route('books.payment.publication', $book)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Impossible de préparer le paiement.');
            if (!result.checkout_url) throw new Error('URL de paiement Stripe manquante.');
            window.location.assign(result.checkout_url);
        } catch (error) {
            showFeedback(error.message, 'error');
            setLoading(false);
        }
    });
});
</script>
@endpush
