@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Finances</span>
                <h1>Revenus</h1>
                <p>Suivez les ventes de vos ouvrages et vos frais de publication.</p>
            </div>
            <a href="{{ route('writer.wallet') }}" class="writer-primary-action">
                <i class="bi bi-wallet2"></i> Mon portefeuille
            </a>
        </header>

        <div class="writer-metric-grid">
            <article class="writer-metric">
                <span class="writer-metric-icon red"><i class="bi bi-currency-dollar"></i></span>
                <div><small>Revenus bruts</small><strong>{{ number_format($totalRevenue, 2, ',', ' ') }}€</strong><span class="neutral">Achats confirmés</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon black"><i class="bi bi-calendar3"></i></span>
                <div><small>Ce mois</small><strong>{{ number_format($monthlyRevenue, 2, ',', ' ') }}€</strong><span class="neutral">Revenus mensuels</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon blue"><i class="bi bi-bag-check"></i></span>
                <div><small>Ventes</small><strong>{{ number_format($totalSales) }}</strong><span class="neutral">Transactions réussies</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon amber"><i class="bi bi-people"></i></span>
                <div><small>Lecteurs</small><strong>{{ number_format($totalReaders) }}</strong><span class="neutral">Acheteurs uniques</span></div>
            </article>
        </div>

        <div class="writer-finance-summary">
            <div>
                <span><i class="bi bi-wallet2"></i></span>
                <div><small>Solde portefeuille</small><strong>{{ number_format($availableBalance, 2, ',', ' ') }}€</strong></div>
            </div>
            <div>
                <span><i class="bi bi-arrow-up-right"></i></span>
                <div><small>Retraits approuvés</small><strong>{{ number_format($totalWithdrawn, 2, ',', ' ') }}€</strong></div>
            </div>
            <div>
                <span><i class="bi bi-hourglass-split"></i></span>
                <div><small>Retraits en attente</small><strong>{{ number_format($pendingWithdrawals, 2, ',', ' ') }}€</strong></div>
            </div>
            <div>
                <span><i class="bi bi-clock-history"></i></span>
                <div><small>Ventes en attente</small><strong>{{ number_format($pendingAmount, 2, ',', ' ') }}€</strong></div>
            </div>
        </div>

        <div class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Transactions</span>
                    <h2>Historique des ventes</h2>
                    <p>Achats confirmés effectués par vos lecteurs.</p>
                </div>
                <strong class="writer-panel-count">{{ $purchasePayments->total() }} vente(s)</strong>
            </header>

            <div class="table-responsive">
                <table class="table writer-data-table align-middle">
                    <thead><tr><th>Livre</th><th>Lecteur</th><th>Montant</th><th>Méthode</th><th>Statut</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($purchasePayments as $payment)
                            <tr>
                                <td>
                                    <div class="writer-table-book">
                                        <img src="{{ $payment->book?->cover_image ? asset('storage/'.$payment->book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                                        <div><strong>{{ $payment->book?->title ?? 'Livre indisponible' }}</strong><small>{{ $payment->book?->type === 'audio' ? 'Livre audio' : 'Ebook' }}</small></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="writer-table-person">
                                        <strong>{{ trim(($payment->user?->firstname ?? '').' '.($payment->user?->lastname ?? '')) ?: 'Lecteur' }}</strong>
                                        <small>{{ $payment->user?->email }}</small>
                                    </div>
                                </td>
                                <td><strong class="writer-money-positive">{{ number_format($payment->amount, 2, ',', ' ') }} {{ $payment->currency }}</strong></td>
                                <td>{{ $payment->payment_method ? ucfirst($payment->payment_method) : '—' }}</td>
                                <td><span class="writer-status success"><i class="bi bi-check-circle-fill"></i> Payé</span></td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="writer-empty-state"><span><i class="bi bi-cart-x"></i></span><h3>Aucune vente</h3><p>Vos ventes apparaîtront ici.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($purchasePayments->hasPages())
                <footer class="writer-panel-footer">{{ $purchasePayments->links() }}</footer>
            @endif
        </div>

        <div class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Publications</span>
                    <h2>Frais de publication</h2>
                    <p>Historique des paiements liés au dépôt de vos livres.</p>
                </div>
                <strong class="writer-panel-count">{{ $publicationPayments->total() }} dépôt(s)</strong>
            </header>

            <div class="table-responsive">
                <table class="table writer-data-table align-middle">
                    <thead><tr><th>Livre</th><th>Montant</th><th>Référence</th><th>Méthode</th><th>Statut</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($publicationPayments as $payment)
                            @php
                                [$paymentLabel, $paymentClass, $paymentIcon] = match($payment->status) {
                                    'success' => ['Payé', 'success', 'bi-check-circle-fill'],
                                    'pending' => ['En attente', 'pending', 'bi-hourglass-split'],
                                    default => ['Échec', 'danger', 'bi-x-circle-fill'],
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="writer-table-book">
                                        <img src="{{ $payment->book?->cover_image ? asset('storage/'.$payment->book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                                        <div><strong>{{ $payment->book?->title ?? 'Livre indisponible' }}</strong><small>Dépôt de publication</small></div>
                                    </div>
                                </td>
                                <td><strong>{{ number_format($payment->amount, 2, ',', ' ') }} {{ $payment->currency }}</strong></td>
                                <td><code class="writer-reference">{{ $payment->reference }}</code></td>
                                <td>{{ $payment->payment_method ? ucfirst($payment->payment_method) : '—' }}</td>
                                <td><span class="writer-status {{ $paymentClass }}"><i class="bi {{ $paymentIcon }}"></i> {{ $paymentLabel }}</span></td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="writer-empty-state"><span><i class="bi bi-cloud-upload"></i></span><h3>Aucun paiement</h3><p>Vos frais de publication apparaîtront ici.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($publicationPayments->hasPages())
                <footer class="writer-panel-footer">{{ $publicationPayments->links() }}</footer>
            @endif
        </div>
    </div>
</main>
@endsection
