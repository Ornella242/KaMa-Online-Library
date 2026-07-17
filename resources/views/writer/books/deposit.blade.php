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
                <p>Choisissez votre moyen de paiement. KKiaPay prend en charge la transaction dans son interface sécurisée.</p>
            </div>
            @if($kkiapaySandbox)
                <span class="kkiapay-sandbox-badge"><i class="bi bi-shield-check"></i> Mode test — Sandbox</span>
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
                    <div><span>Frais d’enregistrement</span><strong>{{ number_format($fee->amount, 0, ',', ' ') }} FCFA</strong></div>
                    <div class="total"><span>Total à payer</span><strong>{{ number_format($fee->amount, 0, ',', ' ') }} FCFA</strong></div>
                </div>

                {{-- <ol class="kkiapay-order-steps">
                    <li class="done"><span><i class="bi bi-check-lg"></i></span><div><strong>Livre enregistré</strong><small>Brouillon créé</small></div></li>
                    <li class="active"><span>2</span><div><strong>Paiement</strong><small>Étape actuelle</small></div></li>
                    <li><span>3</span><div><strong>Validation éditoriale</strong><small>Après paiement confirmé</small></div></li>
                </ol> --}}
            </aside>

            <section class="kkiapay-payment-card">
                <header>
                    <span><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Paiement sécurisé</small>
                        <h2>Options de paiement</h2>
                    </div>
                </header>

                @unless($kkiapayConfigured)
                    <div class="kkiapay-config-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>KKiaPay n’est pas encore configuré</strong>
                            <p>Ajoutez les trois clés Sandbox dans le fichier <code>.env</code>, puis rechargez cette page.</p>
                        </div>
                    </div>
                @endunless

                <div class="kkiapay-methods" role="radiogroup" aria-label="Moyen de paiement">
                    {{-- <label>
                        <input type="radio" name="payment_method" value="momo" checked>
                        <span class="kkiapay-method-icon momo"><i class="bi bi-phone"></i></span>
                        <div>
                            <strong>Mobile Money</strong>
                            <small>MTN, Moov, Orange, Wave et opérateurs compatibles</small>
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label> --}}

                    <label>
                        <input type="radio" name="payment_method" value="card">
                        <span class="kkiapay-method-icon card"><i class="bi bi-credit-card-2-front"></i></span>
                        <div>
                            <strong>Carte bancaire</strong>
                            <small>Visa ou Mastercard via l’interface KKiaPay</small>
                        </div>
                        <div class="kkiapay-card-logos">
                            <img src="{{ asset('assets/images/element/visa.svg') }}" alt="Visa">
                            <img src="{{ asset('assets/images/element/mastercard.svg') }}" alt="Mastercard">
                        </div>
                        <i class="bi bi-check-circle-fill"></i>
                    </label>
                </div>

                <div class="kkiapay-payment-summary">
                    <div><span>Montant</span><strong>{{ number_format($fee->amount, 0, ',', ' ') }} FCFA</strong></div>
                    <div><span>Frais KaMa supplémentaires</span><strong>0 FCFA</strong></div>
                    <div class="total"><span>Total maintenant</span><strong>{{ number_format($fee->amount, 0, ',', ' ') }} FCFA</strong></div>
                </div>

                <div id="kkiapayPaymentFeedback" class="kkiapay-payment-feedback d-none" role="alert"></div>

                <button type="button"
                        id="openKkiapayPayment"
                        class="kkiapay-pay-button"
                        @disabled(! $kkiapayConfigured)>
                    <span><i class="bi bi-lock-fill"></i> Payer avec KKiaPay</span>
                    <strong>{{ number_format($fee->amount, 0, ',', ' ') }} FCFA</strong>
                </button>

                <button type="button"
                        id="retryKkiapayVerification"
                        class="kkiapay-retry-button d-none">
                    <i class="bi bi-arrow-clockwise"></i> Réessayer la vérification
                </button>

                <footer>
                    <i class="bi bi-shield-lock"></i>
                    <span>Vos informations de paiement sont saisies et protégées directement par KKiaPay.</span>
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
            const response = await fetch(@json(route('books.payment.publication.verify', $book)), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify({ transaction_id: transactionId }),
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'La transaction n’a pas pu être vérifiée.');
            }

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
            const response = await fetch(@json(route('books.payment.publication', $book)), {
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
            if (typeof openKkiapayWidget !== 'function') {
                throw new Error('Le service KKiaPay n’a pas pu être chargé.');
            }

            const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'momo';
            openKkiapayWidget({
                amount: result.payment.amount,
                key: @json($kkiapayPublicKey),
                sandbox: @json($kkiapaySandbox),
                position: 'center',
                theme: '#b30000',
                paymentmethod: method,
                partnerId: result.payment.reference,
                name: @json(trim(auth()->user()->firstname.' '.auth()->user()->lastname)),
                email: @json(auth()->user()->email),
                phone: @json(auth()->user()->phone),
                data: {
                    book_id: @json($book->id),
                    reference: result.payment.reference,
                },
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
