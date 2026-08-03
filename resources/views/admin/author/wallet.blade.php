@extends('layouts.admin')

@section('title', 'Mon portefeuille')
@section('page-title', 'Mon portefeuille')

@section('admin-content')
    <div class="admin-dashboard">
        <section class="admin-welcome-card">
            <div>
                <span class="admin-welcome-kicker">Mon espace auteur</span>
                <h2>Mon portefeuille</h2>
                <p>Solde disponible issu des ventes de vos livres et historique de vos transactions.</p>
            </div>
            <div class="admin-welcome-actions">
                <a href="{{ route('admin.author.withdrawals.create') }}" class="btn btn-light">
                    <i class="bi bi-cash-coin me-2"></i>Faire un retrait
                </a>
                <a href="{{ route('admin.author.revenues') }}" class="btn btn-dark">
                    <i class="bi bi-graph-up-arrow me-2"></i>Revenus
                </a>
            </div>
        </section>

        <section class="admin-metric-grid">
            <article class="admin-metric-card">
                <span class="admin-metric-icon green"><i class="bi bi-wallet2"></i></span>
                <div>
                    <small>Solde disponible</small>
                    <strong class="admin-metric-amount">${{ number_format($wallet->balance, 2, '.', ',') }}</strong>
                    <span>{{ strtoupper($wallet->currency ?: 'USD') }} — prêt à retirer</span>
                </div>
            </article>
            <article class="admin-metric-card">
                <span class="admin-metric-icon dark"><i class="bi bi-arrow-down-left"></i></span>
                <div>
                    <small>Total crédité</small>
                    <strong class="admin-metric-amount">${{ number_format($totalCredited, 2, '.', ',') }}</strong>
                    <span>Ventes cumulées</span>
                </div>
            </article>
            <article class="admin-metric-card">
                <span class="admin-metric-icon blue"><i class="bi bi-calendar3"></i></span>
                <div>
                    <small>Ce mois</small>
                    <strong class="admin-metric-amount">${{ number_format($salesThisMonth, 2, '.', ',') }}</strong>
                    <span>Crédits du mois en cours</span>
                </div>
            </article>
            <article class="admin-metric-card">
                <span class="admin-metric-icon amber"><i class="bi bi-arrow-up-right"></i></span>
                <div>
                    <small>Retraits</small>
                    <strong class="admin-metric-amount">${{ number_format($totalWithdrawn, 2, '.', ',') }}</strong>
                    <span>Débits enregistrés</span>
                </div>
            </article>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <span>Mouvements</span>
                    <h3>Historique des transactions</h3>
                </div>
                <span class="admin-panel-badge">{{ $transactions->total() }} transaction(s)</span>
            </div>

            <div class="table-responsive">
                <table class="table admin-dashboard-table align-middle">
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
                                    <span class="badge text-bg-{{ $isSale ? 'success' : 'warning' }}">
                                        <i class="bi {{ $isSale ? 'bi-plus-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i>
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
                                        <div class="admin-book-cell">
                                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/book/01.jpg') }}"
                                                alt="">
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
                                    <strong class="{{ $isSale ? 'text-success' : 'text-danger' }}">
                                        {{ $isSale ? '+' : '−' }}${{ number_format(abs($transaction->amount), 2, '.', ',') }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $transaction->created_at?->format('d/m/Y') }}</strong>
                                    <small class="d-block text-muted">{{ $transaction->created_at?->format('H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Aucune transaction pour le moment. Les ventes de vos livres apparaîtront ici.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="mt-3">{{ $transactions->links() }}</div>
            @endif
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .admin-dashboard {
            display: grid;
            gap: 22px
        }

        .admin-welcome-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 28px 30px;
            border-radius: 20px;
            background: linear-gradient(120deg, #b30000 0%, #7e0000 100%);
            color: #fff;
            box-shadow: 0 18px 45px rgba(179, 0, 0, .2)
        }

        .admin-welcome-kicker {
            display: block;
            margin-bottom: 5px;
            font-size: .78rem;
            font-weight: 700;
            opacity: .76;
            text-transform: uppercase;
            letter-spacing: .08em
        }

        .admin-welcome-card h2 {
            margin: 0 0 7px;
            color: #fff;
            font-size: 1.55rem
        }

        .admin-welcome-card p {
            margin: 0;
            opacity: .8;
            max-width: 620px
        }

        .admin-welcome-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0
        }

        .admin-welcome-actions .btn {
            padding: 10px 15px;
            border-radius: 10px;
            font-size: .8rem;
            font-weight: 700
        }

        .admin-metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px
        }

        .admin-metric-card {
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 104px;
            padding: 20px;
            border: 1px solid #e7e8eb;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .04)
        }

        .admin-metric-card span.admin-metric-icon {
            display: grid;
            width: 62px;
            height: 62px;
            flex: 0 0 62px;
            place-items: center;
            border-radius: 17px;
            font-size: 1.7rem;
            line-height: 1
        }

        .admin-metric-icon.red {
            background: #fff0f0;
            color: #b30000
        }

        .admin-metric-icon.dark {
            background: #ededee;
            color: #18191c
        }

        .admin-metric-icon.amber {
            background: #fff7df;
            color: #b57800
        }

        .admin-metric-icon.green {
            background: #eaf9ef;
            color: #138443
        }

        .admin-metric-icon.blue {
            background: #e8f5ff;
            color: #1678ad
        }

        .admin-metric-card small,
        .admin-metric-card strong,
        .admin-metric-card span:not(.admin-metric-icon) {
            display: block
        }

        .admin-metric-card small {
            color: #858991;
            font-size: .72rem;
            font-weight: 700
        }

        .admin-metric-card strong {
            margin: 2px 0;
            font-size: 1.35rem
        }

        .admin-metric-card .admin-metric-amount {
            font-size: 1.05rem;
            line-height: 1.35;
            white-space: normal
        }

        .admin-metric-card span:not(.admin-metric-icon) {
            color: #8c9097;
            font-size: .68rem
        }

        .admin-panel {
            min-width: 0;
            padding: 22px;
            border: 1px solid #e7e8eb;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .admin-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px
        }

        .admin-panel-header span {
            color: #999ca3;
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em
        }

        .admin-panel-header h3 {
            margin: 2px 0 0;
            font-size: 1.02rem
        }

        .admin-panel-badge {
            padding: 5px 8px;
            border-radius: 999px;
            background: #eef8f1;
            color: #168046 !important
        }

        .admin-dashboard-table {
            margin: 0
        }

        .admin-dashboard-table th {
            padding: 9px;
            color: #9699a0;
            font-size: .67rem;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .admin-dashboard-table td {
            padding: 11px 9px;
            border-color: #f0f1f3;
            font-size: .76rem;
            vertical-align: middle
        }

        .admin-dashboard-table .badge {
            font-size: .65rem;
            font-weight: 700
        }

        .admin-book-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 190px
        }

        .admin-book-cell img {
            width: 36px;
            height: 46px;
            border-radius: 6px;
            object-fit: cover;
            background: #eee
        }

        .admin-book-cell strong,
        .admin-book-cell small {
            display: block
        }

        .admin-book-cell strong {
            max-width: 210px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .78rem
        }

        .admin-book-cell small {
            margin-top: 2px;
            color: #9699a0;
            font-size: .65rem
        }

        @media(max-width:1100px) {
            .admin-metric-grid {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:700px) {
            .admin-welcome-card {
                align-items: flex-start;
                flex-direction: column;
                padding: 23px
            }

            .admin-welcome-actions {
                width: 100%;
                flex-wrap: wrap
            }

            .admin-metric-grid {
                grid-template-columns: 1fr
            }

            .admin-panel {
                padding: 17px
            }
        }
    </style>
@endpush