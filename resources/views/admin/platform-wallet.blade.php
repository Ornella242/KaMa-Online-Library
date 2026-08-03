@extends('layouts.admin')

@section('title', 'Portefeuille KaMa')
@section('page-title', 'Portefeuille KaMa')

@section('admin-content')
<div class="admin-dashboard">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Activité commerciale</span>
            <h2>Portefeuille plateforme</h2>
            <p>Vue réelle de ce qui a été encaissé sur KaMa : ventes, frais de publication, sponsoring et publicité.</p>
        </div>
        
    </section>

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-safe2-fill"></i></span>
            <div>
                <small>Total encaissé</small>
                <strong class="admin-metric-amount">${{ number_format($platformBalance, 2, '.', ',') }}</strong>
                <span>Tout ce qui est entré via Lemon Squeezy</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-wallet2"></i></span>
            <div>
                <small>Dû aux auteurs</small>
                <strong class="admin-metric-amount">${{ number_format($authorsOwed, 2, '.', ',') }}</strong>
                <span>Somme des portefeuilles auteurs</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon red"><i class="bi bi-piggy-bank-fill"></i></span>
            <div>
                <small>Net plateforme</small>
                <strong class="admin-metric-amount">${{ number_format($platformNet, 2, '.', ',') }}</strong>
                <span>Encaissé − dû aux auteurs</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-calendar3"></i></span>
            <div>
                <small>Ce mois</small>
                <strong class="admin-metric-amount">${{ number_format($thisMonth, 2, '.', ',') }}</strong>
                <span>Encaissements du mois en cours</span>
            </div>
        </article>
    </section>

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon dark"><i class="bi bi-bag-check-fill"></i></span>
            <div>
                <small>Ventes livres</small>
                <strong class="admin-metric-amount">${{ number_format($salesTotal, 2, '.', ',') }}</strong>
                <span>Achats lecteurs confirmés</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon violet"><i class="bi bi-file-earmark-text-fill"></i></span>
            <div>
                <small>Frais de publication</small>
                <strong class="admin-metric-amount">${{ number_format($publicationTotal, 2, '.', ',') }}</strong>
                <span>Dépôts auteurs payés</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-megaphone-fill"></i></span>
            <div>
                <small>Sponsoring</small>
                <strong class="admin-metric-amount">${{ number_format($sponsorshipTotal, 2, '.', ',') }}</strong>
                <span>Mises en avant payées</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-badge-ad-fill"></i></span>
            <div>
                <small>Publicité</small>
                <strong class="admin-metric-amount">${{ number_format($advertisementTotal, 2, '.', ',') }}</strong>
                <span>Campagnes enregistrées</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-percent"></i></span>
            <div>
                <small>Commissions retraits</small>
                <strong class="admin-metric-amount">${{ number_format($withdrawalCommissionTotal, 2, '.', ',') }}</strong>
                <span>Sur retraits terminés</span>
            </div>
        </article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <div>
                <span>Mouvements</span>
                <h3>Historique des encaissements</h3>
            </div>
            <span class="admin-panel-badge">{{ $transactions->total() }} opération(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table admin-dashboard-table align-middle">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Payeur</th>
                        <th>Auteur</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @php
                            [$badgeClass, $icon] = match ($transaction->kind) {
                                'sale' => ['success', 'bi-bag-check-fill'],
                                'publication' => ['warning', 'bi-file-earmark-text-fill'],
                                'sponsorship' => ['danger', 'bi-megaphone-fill'],
                                default => ['secondary', 'bi-badge-ad-fill'],
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="badge text-bg-{{ $badgeClass }}">
                                    <i class="bi {{ $icon }} me-1"></i>{{ $transaction->label }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $transaction->description }}</strong>
                                @if($transaction->reference)
                                    <small class="d-block text-muted">{{ $transaction->reference }}</small>
                                @endif
                            </td>
                            <td>{{ $transaction->party }}</td>
                            <td>{{ $transaction->author }}</td>
                            <td>
                                <strong class="text-success">
                                    +${{ number_format($transaction->amount, 2, '.', ',') }}
                                </strong>
                            </td>
                            <td>
                                <strong>{{ optional($transaction->occurred_at)->format('d/m/Y') ?: '—' }}</strong>
                                <small class="d-block text-muted">{{ optional($transaction->occurred_at)->format('H:i') }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Aucun encaissement pour le moment.
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

    <section class="admin-panel admin-wallet-legend">
        <div class="admin-panel-header">
            <div>
                <span>Légende</span>
                <h3>Comment lire ces montants</h3>
            </div>
        </div>
        <div class="admin-status-list">
            <div><span><i class="bi bi-safe2-fill text-success"></i> Total encaissé</span><strong>Tout ce qui est entré via Lemon Squeezy</strong></div>
            <div><span><i class="bi bi-wallet2 text-warning"></i> Dû aux auteurs</span><strong>Somme des portefeuilles auteurs</strong></div>
            <div><span><i class="bi bi-piggy-bank-fill text-danger"></i> Net plateforme</span><strong>Encaissé − dû aux auteurs</strong></div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.admin-dashboard{display:grid;gap:22px}
.admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff;box-shadow:0 18px 45px rgba(179,0,0,.2)}
.admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}
.admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.55rem}
.admin-welcome-card p{margin:0;opacity:.8;max-width:620px}
.admin-welcome-actions{display:flex;gap:10px;flex-shrink:0}
.admin-welcome-actions .btn{padding:10px 15px;border-radius:10px;font-size:.8rem;font-weight:700}
.admin-metric-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.admin-metric-card{display:flex;align-items:center;gap:15px;min-height:104px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.04)}
.admin-metric-card span.admin-metric-icon{display:grid;width:62px;height:62px;flex:0 0 62px;place-items:center;border-radius:17px;font-size:1.7rem;line-height:1}
.admin-metric-icon.red{background:#fff0f0;color:#b30000}
.admin-metric-icon.dark{background:#ededee;color:#18191c}
.admin-metric-icon.amber{background:#fff7df;color:#b57800}
.admin-metric-icon.green{background:#eaf9ef;color:#138443}
.admin-metric-icon.blue{background:#e8f5ff;color:#1678ad}
.admin-metric-icon.violet{background:#f1edff;color:#6d4bc3}
.admin-metric-card small,.admin-metric-card strong,.admin-metric-card span:not(.admin-metric-icon){display:block}
.admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}
.admin-metric-card strong{margin:2px 0;font-size:1.35rem}
.admin-metric-card .admin-metric-amount{font-size:1.05rem;line-height:1.35;white-space:normal}
.admin-metric-card span:not(.admin-metric-icon){color:#8c9097;font-size:.68rem}
.admin-panel{min-width:0;padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.035)}
.admin-panel-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px}
.admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}
.admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}
.admin-panel-badge{padding:5px 8px;border-radius:999px;background:#eef8f1;color:#168046!important}
.admin-dashboard-table{margin:0}
.admin-dashboard-table th{padding:9px;color:#9699a0;font-size:.67rem;text-transform:uppercase;letter-spacing:.05em}
.admin-dashboard-table td{padding:11px 9px;border-color:#f0f1f3;font-size:.76rem;vertical-align:middle}
.admin-dashboard-table .badge{font-size:.65rem;font-weight:700}
.admin-wallet-legend .admin-status-list{display:grid;gap:8px}
.admin-wallet-legend .admin-status-list > div{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 3px;border-bottom:1px solid #f0f1f3;color:#3c3e43;font-size:.8rem}
.admin-wallet-legend .admin-status-list > div:last-child{border-bottom:0}
.admin-wallet-legend .admin-status-list i{margin-right:8px}
.admin-wallet-legend .admin-status-list strong{font-size:.78rem;font-weight:600;color:#686c73;text-align:right}
@media(max-width:1100px){.admin-metric-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:700px){
    .admin-welcome-card{align-items:flex-start;flex-direction:column;padding:23px}
    .admin-welcome-actions{width:100%;flex-wrap:wrap}
    .admin-metric-grid{grid-template-columns:1fr}
    .admin-panel{padding:17px}
    .admin-wallet-legend .admin-status-list > div{flex-direction:column;align-items:flex-start}
}
</style>
@endpush
