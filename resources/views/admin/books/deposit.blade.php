@extends('layouts.admin')

@section('title', 'Frais de publication')
@section('page-title', 'Paiement publication')

@section('admin-content')
<main class="kkiapay-checkout">
    <div class="container-fluid px-0">
        <div class="kkiapay-checkout-heading">
            <div>
                <a href="{{ route('admin.books.index') }}">
                    <i class="bi bi-arrow-left"></i> Mes livres
                </a>
                <span>Publication KaMa</span>
                <h1>Finaliser les frais de publication</h1>
                <p>Payez les frais de dépôt en USD via Lemon Squeezy.</p>
            </div>
            @if($lemonTestMode)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Lemon Squeezy</span>
            @endif
        </div>

        <div class="kkiapay-checkout-layout">
            <aside class="kkiapay-order-card">
                <div class="kkiapay-order-cover">
                    <img src="{{ $book->cover_image
                        ? asset('storage/'.$book->cover_image)
                        : asset('assets/images/book/01.jpg') }}"
                         alt="Couverture de {{ $book->title }}">
                </div>
                <div class="kkiapay-order-content">
                    <small>Ouvrage</small>
                    <h2>{{ $book->title }}</h2>
                    <div class="total"><span>Total</span><strong>${{ number_format($fee->amount, 2, '.', ',') }}</strong></div>
                </div>
            </aside>

            <section class="kkiapay-payment-card">
                @unless($lemonConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>Lemon Squeezy n’est pas configuré</strong>
                            <p>Renseignez <code>LEMON_SQUEEZY_*</code> dans <code>.env</code>.</p>
                        </div>
                    </div>
                @endunless

                <div id="paymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button" id="openLemonPayment" class="kkiapay-pay-button" @disabled(! $lemonConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec Lemon Squeezy</span>
                    <strong>${{ number_format($fee->amount, 2, '.', ',') }}</strong>
                </button>
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
        const response = await fetch(@json(route('books.payment.publication.verify', $book)), {
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
    };

    window.createLemonSqueezy?.();
    window.LemonSqueezy?.Setup?.({
        eventHandler: (event) => {
            if (event?.event === 'Checkout.Success') {
                showFeedback('Paiement reçu. Confirmation en cours…', 'loading');
                pollVerification();
                pollTimer = window.setInterval(pollVerification, 2000);
                window.setTimeout(() => window.clearInterval(pollTimer), 30000);
            }
        }
    });

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
