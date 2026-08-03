@extends('layouts.app')

@section('content')
<section class="kama-cart-page py-5">
    <div class="container" style="max-width:720px;">
        <div class="kama-cart-empty">
            <span><i class="bi bi-hourglass-split"></i></span>
            <h2>Confirmation du paiement…</h2>
            <p>
                Votre paiement Lemon Squeezy est en cours de validation.
                Cette page se mettra à jour automatiquement.
            </p>
            <p class="text-muted mb-0">Commande {{ $order->reference }}</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const poll = async () => {
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
                window.location.assign(result.redirect);
            }
        } catch (e) {}
    };

    poll();
    const timer = window.setInterval(poll, 2500);
    window.setTimeout(() => window.clearInterval(timer), 60000);
});
</script>
@endpush
