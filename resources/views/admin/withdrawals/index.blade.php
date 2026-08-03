@extends('layouts.admin')

@section('title', 'Retraits')
@section('page-title', 'Retraits')

@section('admin-content')
<div class="admin-dashboard">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Activité commerciale</span>
            <h2>Demandes de retrait</h2>
            <p>Traitez les versements aux auteurs : initié → en cours → terminé.</p>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-hourglass-split"></i></span>
            <div><small>Initiés</small><strong>{{ $stats['initiated'] }}</strong><span>En attente de traitement</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-arrow-repeat"></i></span>
            <div><small>En cours</small><strong>{{ $stats['processing'] }}</strong><span>Versement en préparation</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-check2-circle"></i></span>
            <div><small>Terminés</small><strong>{{ $stats['completed'] }}</strong><span>Commission gagnée : ${{ number_format($stats['commission_earned'], 2, '.', ',') }}</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon red"><i class="bi bi-cash-stack"></i></span>
            <div><small>Net à verser</small><strong class="admin-metric-amount">${{ number_format($stats['pending_amount'], 2, '.', ',') }}</strong><span>Demandes ouvertes</span></div>
        </article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <div>
                <span>Filtres</span>
                <h3>Liste des retraits</h3>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.withdrawals.index') }}" class="badge {{ $status === '' ? 'text-bg-dark' : 'text-bg-light' }}">Tous</a>
                <a href="{{ route('admin.withdrawals.index', ['status' => 'initiated']) }}" class="badge {{ $status === 'initiated' ? 'text-bg-warning' : 'text-bg-light' }}">Initiés</a>
                <a href="{{ route('admin.withdrawals.index', ['status' => 'processing']) }}" class="badge {{ $status === 'processing' ? 'text-bg-primary' : 'text-bg-light' }}">En cours</a>
                <a href="{{ route('admin.withdrawals.index', ['status' => 'completed']) }}" class="badge {{ $status === 'completed' ? 'text-bg-success' : 'text-bg-light' }}">Terminés</a>
                <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" class="badge {{ $status === 'rejected' ? 'text-bg-danger' : 'text-bg-light' }}">Refusés</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-dashboard-table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Auteur</th>
                        <th>Demandé</th>
                        <th>Commission</th>
                        <th>Net à verser</th>
                        <th>Mode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        @php
                            $badge = match($withdrawal->status) {
                                'completed' => 'success',
                                'processing' => 'primary',
                                'rejected' => 'danger',
                                default => 'warning',
                            };
                            $author = trim(($withdrawal->user?->firstname ?? '').' '.($withdrawal->user?->lastname ?? '')) ?: ($withdrawal->user?->email ?? '—');
                        @endphp
                        <tr>
                            <td><strong>#{{ $withdrawal->id }}</strong></td>
                            <td>{{ $author }}</td>
                            <td>${{ number_format($withdrawal->amount, 2, '.', ',') }}</td>
                            <td>${{ number_format($withdrawal->commission_amount, 2, '.', ',') }}</td>
                            <td><strong>${{ number_format($withdrawal->net_amount, 2, '.', ',') }}</strong></td>
                            <td>{{ $withdrawal->methodLabel() }}</td>
                            <td><span class="badge text-bg-{{ $badge }}">{{ $withdrawal->statusLabel() }}</span></td>
                            <td>{{ $withdrawal->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="admin-row-action" title="Traiter">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-5 text-muted">Aucune demande de retrait.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="mt-3">{{ $withdrawals->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
.admin-dashboard{display:grid;gap:22px}
.admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff;box-shadow:0 18px 45px rgba(179,0,0,.2)}
.admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}
.admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.55rem}
.admin-welcome-card p{margin:0;opacity:.8}
.admin-metric-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.admin-metric-card{display:flex;align-items:center;gap:15px;min-height:104px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.04)}
.admin-metric-card span.admin-metric-icon{display:grid;width:62px;height:62px;flex:0 0 62px;place-items:center;border-radius:17px;font-size:1.7rem;line-height:1}
.admin-metric-icon.red{background:#fff0f0;color:#b30000}
.admin-metric-icon.amber{background:#fff7df;color:#b57800}
.admin-metric-icon.green{background:#eaf9ef;color:#138443}
.admin-metric-icon.blue{background:#e8f5ff;color:#1678ad}
.admin-metric-card small,.admin-metric-card strong,.admin-metric-card span:not(.admin-metric-icon){display:block}
.admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}
.admin-metric-card strong{margin:2px 0;font-size:1.35rem}
.admin-metric-card .admin-metric-amount{font-size:1.05rem}
.admin-metric-card span:not(.admin-metric-icon){color:#8c9097;font-size:.68rem}
.admin-panel{padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff}
.admin-panel-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px}
.admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}
.admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}
.admin-dashboard-table{margin:0}
.admin-dashboard-table th{padding:9px;color:#9699a0;font-size:.67rem;text-transform:uppercase}
.admin-dashboard-table td{padding:11px 9px;border-color:#f0f1f3;font-size:.76rem;vertical-align:middle}
.admin-row-action{display:grid;width:31px;height:31px;place-items:center;border-radius:8px;background:#f2f3f5;color:#333}
@media(max-width:1100px){.admin-metric-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:700px){.admin-metric-grid{grid-template-columns:1fr}}
</style>
@endpush
