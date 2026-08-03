@extends('layouts.admin')

@section('title', 'Retrait #'.$withdrawal->id)
@section('page-title', 'Retrait #'.$withdrawal->id)

@section('admin-content')
@php
    $author = trim(($withdrawal->user?->firstname ?? '').' '.($withdrawal->user?->lastname ?? '')) ?: ($withdrawal->user?->email ?? '—');
    $badge = match($withdrawal->status) {
        'completed' => 'success',
        'processing' => 'primary',
        'rejected' => 'danger',
        default => 'warning',
    };
    $methods = \App\Models\Withdrawal::paymentMethods();
    $fieldLabels = $methods[$withdrawal->payment_method]['fields'] ?? [];
@endphp
<div class="admin-dashboard">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Demande #{{ $withdrawal->id }}</span>
            <h2>Retrait — {{ $author }}</h2>
            <p>{{ $withdrawal->methodLabel() }} · Statut actuel : {{ $withdrawal->statusLabel() }}</p>
        </div>
        <div class="admin-welcome-actions">
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon dark"><i class="bi bi-cash"></i></span>
            <div><small>Montant demandé</small><strong class="admin-metric-amount">${{ number_format($withdrawal->amount, 2, '.', ',') }}</strong><span>Débité du portefeuille auteur</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-percent"></i></span>
            <div><small>Commission KaMa</small><strong class="admin-metric-amount">${{ number_format($withdrawal->commission_amount, 2, '.', ',') }}</strong><span>{{ number_format($withdrawal->commission_percent, 2, '.', ',') }} %</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-send-check"></i></span>
            <div><small>Net à verser</small><strong class="admin-metric-amount">${{ number_format($withdrawal->net_amount, 2, '.', ',') }}</strong><span>Montant à envoyer à l’auteur</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-flag"></i></span>
            <div><small>Statut</small><strong><span class="badge text-bg-{{ $badge }}">{{ $withdrawal->statusLabel() }}</span></strong><span>{{ $withdrawal->created_at?->format('d/m/Y H:i') }}</span></div>
        </article>
    </section>

    <div class="admin-dashboard-grid">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Paiement</span><h3>Coordonnées de réception</h3></div>
            </div>
            <div class="admin-status-list">
                <div><span>Mode</span><strong>{{ $withdrawal->methodLabel() }}</strong></div>
                <div><span>Identifiant principal</span><strong>{{ $withdrawal->account_number ?: '—' }}</strong></div>
                @foreach(($withdrawal->payout_details ?? []) as $key => $value)
                    <div>
                        <span>{{ $fieldLabels[$key]['label'] ?? ucfirst(str_replace('_', ' ', $key)) }}</span>
                        <strong>{{ $value }}</strong>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Actions</span><h3>Traiter la demande</h3></div>
            </div>

            @if($withdrawal->status === 'initiated')
                <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}" class="mb-3">
                    @csrf
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-play-circle me-1"></i> Passer en cours
                    </button>
                </form>
            @endif

            @if(in_array($withdrawal->status, ['initiated', 'processing'], true))
                <form method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}" class="mb-3">
                    @csrf
                    <label class="form-label">Note (optionnel)</label>
                    <textarea name="admin_note" class="form-control mb-2" rows="2" placeholder="Référence du virement, opérateur…">{{ old('admin_note', $withdrawal->admin_note) }}</textarea>
                    <button class="btn btn-success w-100" type="submit"
                            onclick="return confirm('Confirmer que le versement net a bien été envoyé à l’auteur ?')">
                        <i class="bi bi-check2-circle me-1"></i> Marquer comme terminé
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                    @csrf
                    <label class="form-label">Motif du refus (optionnel)</label>
                    <textarea name="admin_note" class="form-control mb-2" rows="2" placeholder="Ex. coordonnées invalides">{{ old('admin_note') }}</textarea>
                    <button class="btn btn-outline-danger w-100" type="submit"
                            onclick="return confirm('Refuser cette demande ? Le montant sera recrédité au portefeuille auteur.')">
                        <i class="bi bi-x-circle me-1"></i> Refuser et rembourser
                    </button>
                </form>
            @else
                <div class="alert alert-secondary mb-0">
                    Cette demande est clôturée.
                    @if($withdrawal->admin_note)
                        <div class="mt-2"><strong>Note :</strong> {{ $withdrawal->admin_note }}</div>
                    @endif
                    @if($withdrawal->processor)
                        <div class="mt-1"><small>Traité par {{ $withdrawal->processor->firstname }} {{ $withdrawal->processor->lastname }}</small></div>
                    @endif
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-dashboard{display:grid;gap:22px}
.admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff}
.admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}
.admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.45rem}
.admin-welcome-card p{margin:0;opacity:.8}
.admin-welcome-actions .btn{padding:10px 15px;border-radius:10px;font-size:.8rem;font-weight:700}
.admin-metric-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.admin-metric-card{display:flex;align-items:center;gap:15px;min-height:104px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff}
.admin-metric-card span.admin-metric-icon{display:grid;width:62px;height:62px;flex:0 0 62px;place-items:center;border-radius:17px;font-size:1.7rem}
.admin-metric-icon.dark{background:#ededee;color:#18191c}
.admin-metric-icon.amber{background:#fff7df;color:#b57800}
.admin-metric-icon.green{background:#eaf9ef;color:#138443}
.admin-metric-icon.blue{background:#e8f5ff;color:#1678ad}
.admin-metric-card small,.admin-metric-card strong,.admin-metric-card span:not(.admin-metric-icon){display:block}
.admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}
.admin-metric-card strong{margin:2px 0;font-size:1.2rem}
.admin-metric-card span:not(.admin-metric-icon){color:#8c9097;font-size:.68rem}
.admin-dashboard-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:18px}
.admin-panel{padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff}
.admin-panel-header{margin-bottom:16px}
.admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}
.admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}
.admin-status-list{display:grid;gap:8px}
.admin-status-list > div{display:flex;justify-content:space-between;gap:12px;padding:11px 0;border-bottom:1px solid #f0f1f3;font-size:.8rem}
.admin-status-list > div:last-child{border-bottom:0}
.admin-status-list span{color:#888}
@media(max-width:900px){.admin-metric-grid,.admin-dashboard-grid{grid-template-columns:1fr}}
</style>
@endpush
