@extends('layouts.admin')

@section('title', 'Revenus')
@section('page-title', 'Revenus')

@section('admin-content')
<div class="admin-books-page">
    <div class="admin-books-heading">
        <div>
            <span class="admin-books-eyebrow">Mon espace auteur</span>
            <h2>Revenus</h2>
            <p>Suivez les ventes de vos ouvrages et vos frais de publication.</p>
        </div>
    </div>

    <div class="admin-books-stats">
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon total"><i class="bi bi-cash-stack"></i></span>
            <div><small>Revenus bruts</small><strong>{{ number_format($totalRevenue, 0, ',', ' ') }}</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon waiting"><i class="bi bi-calendar3"></i></span>
            <div><small>Ce mois</small><strong>{{ number_format($monthlyRevenue, 0, ',', ' ') }}</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon published"><i class="bi bi-bag-check"></i></span>
            <div><small>Ventes</small><strong>{{ number_format($totalSales) }}</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon review"><i class="bi bi-wallet2"></i></span>
            <div><small>Solde estimé</small><strong>{{ number_format($availableBalance, 0, ',', ' ') }}</strong></div>
        </div>
    </div>

    <div class="admin-books-panel">
        <div class="admin-books-table-header">
            <div>
                <strong>Historique des ventes</strong>
                <span>{{ $purchasePayments->total() }} vente(s) confirmée(s)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-books-table align-middle">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Lecteur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchasePayments as $payment)
                        <tr>
                            <td>
                                <div class="admin-books-title-cell">
                                    <img src="{{ $payment->book?->cover_image
                                        ? asset('storage/'.$payment->book->cover_image)
                                        : asset('assets/images/book/01.jpg') }}" alt="">
                                    <div>
                                        <strong>{{ $payment->book?->title ?? 'Livre' }}</strong>
                                        <small>{{ $payment->book?->type === 'audio' ? 'Audio' : 'Ebook' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-books-author">
                                    <strong>{{ trim(($payment->user?->firstname ?? '').' '.($payment->user?->lastname ?? '')) ?: 'Lecteur' }}</strong>
                                    <small>{{ $payment->user?->email ?? ($payment->guest_email ?? '—') }}</small>
                                </div>
                            </td>
                            <td>
                                <strong class="admin-books-price">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                                </strong>
                            </td>
                            <td>
                                <span class="admin-books-status published"><span></span>Confirmé</span>
                            </td>
                            <td>
                                <div class="admin-books-date">
                                    <strong>{{ $payment->created_at?->format('d/m/Y') }}</strong>
                                    <small>{{ $payment->created_at?->format('H:i') }}</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="admin-books-empty">
                                    <span><i class="bi bi-receipt"></i></span>
                                    <h3>Aucune vente</h3>
                                    <p>Les achats de vos livres apparaîtront ici.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchasePayments->hasPages())
            <div class="admin-books-pagination">
                <span>Page {{ $purchasePayments->currentPage() }} sur {{ $purchasePayments->lastPage() }}</span>
                {{ $purchasePayments->onEachSide(1)->links() }}
            </div>
        @endif
    </div>

    <div class="admin-books-panel">
        <div class="admin-books-table-header">
            <div>
                <strong>Frais de publication</strong>
                <span>{{ $publicationPayments->total() }} paiement(s)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-books-table align-middle">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publicationPayments as $payment)
                        @php
                            $statusMeta = match ($payment->status) {
                                'success' => ['Payé', 'published'],
                                'pending' => ['En attente', 'waiting'],
                                'failed' => ['Échoué', 'rejected'],
                                default => [ucfirst($payment->status), 'unknown'],
                            };
                            $statusLabel = $statusMeta[0];
                            $statusClass = $statusMeta[1];
                        @endphp
                        <tr>
                            <td>
                                <div class="admin-books-title-cell">
                                    <img src="{{ $payment->book?->cover_image
                                        ? asset('storage/'.$payment->book->cover_image)
                                        : asset('assets/images/book/01.jpg') }}" alt="">
                                    <div>
                                        <strong>{{ $payment->book?->title ?? 'Livre' }}</strong>
                                        <small>{{ $payment->reference }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="admin-books-price">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                                </strong>
                            </td>
                            <td>
                                <span class="admin-books-status {{ $statusClass }}"><span></span>{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <div class="admin-books-date">
                                    <strong>{{ $payment->created_at?->format('d/m/Y') }}</strong>
                                    <small>{{ $payment->created_at?->format('H:i') }}</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="admin-books-empty">
                                    <span><i class="bi bi-credit-card"></i></span>
                                    <h3>Aucun frais</h3>
                                    <p>Les paiements de dépôt apparaîtront ici.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publicationPayments->hasPages())
            <div class="admin-books-pagination">
                <span>Page {{ $publicationPayments->currentPage() }} sur {{ $publicationPayments->lastPage() }}</span>
                {{ $publicationPayments->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@include('admin.partials.admin-books-styles')
