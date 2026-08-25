@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Finances</span>
                <h1>Portefeuille</h1>
                <p>Consultez le solde disponible issu des ventes de vos livres et l’historique de vos transactions.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('writer.withdrawals.create') }}" class="writer-primary-action">
                    <i class="bi bi-cash-coin"></i> Faire un retrait
                </a>
                <a href="{{ route('writer.revenues') }}" class="writer-primary-action" style="background:#111;">
                    <i class="bi bi-graph-up-arrow"></i> Revenus
                </a>
            </div>
        </header>

        <div class="writer-metric-grid">
            <article class="writer-metric">
                <span class="writer-metric-icon red"><i class="bi bi-wallet2"></i></span>
                <div>
                    <small>Solde disponible</small>
                    <strong>{{ number_format($wallet->balance, 2, '.', ',') }}€</strong>
                    <span class="neutral">{{ strtoupper($wallet->currency ?: 'EUR') }}</span>
                </div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon black"><i class="bi bi-arrow-down-left"></i></span>
                <div>
                    <small>Total crédité</small>
                    <strong>{{ number_format($totalCredited, 2, '.', ',') }}€</strong>
                    <span class="neutral">Ventes cumulées</span>
                </div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon blue"><i class="bi bi-calendar3"></i></span>
                <div>
                    <small>Ce mois</small>
                    <strong>{{ number_format($salesThisMonth, 2, '.', ',') }}€</strong>
                    <span class="neutral">Crédits du mois</span>
                </div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon amber"><i class="bi bi-arrow-up-right"></i></span>
                <div>
                    <small>Retraits</small>
                    <strong>{{ number_format($totalWithdrawn, 2, '.', ',') }}€</strong>
                    <span class="neutral">Débits enregistrés</span>
                </div>
            </article>
        </div>

        <div class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Portefeuille</span>
                    <h2>Historique des transactions</h2>
                    <p>Chaque vente confirmée crédite automatiquement votre solde.</p>
                </div>
                <strong class="writer-panel-count">{{ $transactions->total() }} transaction(s)</strong>
            </header>

            <div class="table-responsive">
                <table class="table writer-data-table align-middle">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Livre</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            @php
                                $isSale = $transaction->type === 'sale';
                                $book = $transaction->payment?->book;
                            @endphp
                            <tr>
                                <td>
                                    <span class="writer-status {{ $isSale ? 'success' : 'pending' }}">
                                        <i class="bi {{ $isSale ? 'bi-plus-circle-fill' : 'bi-dash-circle-fill' }}"></i>
                                        {{ $isSale ? 'Vente' : 'Retrait' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $transaction->description ?: ($isSale ? 'Crédit vente' : 'Retrait') }}</strong>
                                    @if($transaction->reference)
                                        <small class="d-block text-muted">{{ $transaction->reference }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($book)
                                        <div class="writer-table-book">
                                            <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                                            <div>
                                                <strong>{{ $book->title }}</strong>
                                                <small>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</small>
                                            </div>
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $isSale ? 'writer-money-positive' : 'writer-money-negative' }}">
                                        {{ $isSale ? '+' : '−' }}${{ number_format(abs($transaction->amount), 2, '.', ',') }}
                                    </strong>
                                </td>
                                <td>{{ $transaction->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="writer-empty-state">
                                        <span><i class="bi bi-wallet2"></i></span>
                                        <h3>Aucune transaction</h3>
                                        <p>Dès qu’un lecteur achète l’un de vos livres, le montant apparaît ici.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <footer class="writer-panel-footer">{{ $transactions->links() }}</footer>
            @endif
        </div>
    </div>
</main>
@endsection
