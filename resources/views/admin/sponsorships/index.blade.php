@extends('layouts.admin')

@section('title', 'Demandes sponsoring')
@section('page-title', 'Demandes sponsoring')

@section('admin-content')
<div class="categories-page">
    <header class="categories-heading">
        <div>
            <span>Publicité</span>
            <h2>Validation des sponsorings</h2>
            <p>Validez les paiements auteurs pour activer la mise en avant des livres sur KaMa.</p>
        </div>
        <a href="{{ route('admin.sponsorship-plans.index') }}" class="categories-add">
            <i class="bi bi-sliders"></i> Gérer les formules
        </a>
    </header>

    <section class="categories-stats">
        <article>
            <span class="empty"><i class="bi bi-hourglass-split"></i></span>
            <div><small>En attente</small><strong>{{ number_format($stats['pending']) }}</strong></div>
        </article>
        <article>
            <span class="sub"><i class="bi bi-broadcast"></i></span>
            <div><small>Actifs</small><strong>{{ number_format($stats['active']) }}</strong></div>
        </article>
        <article>
            <span class="books"><i class="bi bi-cash-coin"></i></span>
            <div><small>Revenus payés</small><strong>{{ number_format($stats['revenue'], 0, ',', ' ') }} <small>XOF</small></strong></div>
        </article>
    </section>

    <section class="categories-panel">
        <div class="sponsorship-tabs mb-3">
            <a href="{{ route('admin.sponsorships.index', ['tab' => 'pending']) }}"
               class="{{ $tab === 'pending' ? 'active' : '' }}">
                À valider ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('admin.sponsorships.index', ['tab' => 'active']) }}"
               class="{{ $tab === 'active' ? 'active' : '' }}">
                Actifs
            </a>
            <a href="{{ route('admin.sponsorships.index', ['tab' => 'history']) }}"
               class="{{ $tab === 'history' ? 'active' : '' }}">
                Historique
            </a>
        </div>

        @php
            $rows = match ($tab) {
                'active' => $active,
                'history' => $history,
                default => $pending,
            };
        @endphp

        <div class="table-responsive">
            <table class="table categories-table align-middle">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Formule</th>
                        <th>Montant</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        @if($tab === 'pending')
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $item)
                        <tr>
                            <td>
                                <div class="categories-name-cell">
                                    <span><i class="bi bi-book"></i></span>
                                    <div>
                                        <strong>{{ $item->book?->title ?? 'Livre supprimé' }}</strong>
                                        <small>#{{ $item->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ trim(($item->writer?->firstname ?? '').' '.($item->writer?->lastname ?? '')) ?: '—' }}
                            </td>
                            <td>
                                {{ $item->plan?->name ?? ($item->source === 'admin' ? 'Boost admin' : '—') }}
                                @if($item->plan)
                                    <br><small>{{ $item->plan->duration_days }} jours</small>
                                @endif
                            </td>
                            <td><strong>{{ number_format($item->amount, 0, ',', ' ') }} XOF</strong></td>
                            <td>
                                <small>{{ $item->paid_at?->format('d/m/Y H:i') ?? '—' }}</small>
                                @if($item->transaction_reference)
                                    <br><small class="text-muted">{{ Str::limit($item->transaction_reference, 18) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($tab === 'pending')
                                    <span class="categories-count-badge">Payé · à valider</span>
                                @elseif($tab === 'active')
                                    <span class="categories-count-badge filled">
                                        Jusqu’au {{ $item->ends_at?->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="categories-count-badge">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            @if($tab === 'pending')
                                <td>
                                    <div class="categories-row-actions">
                                        <form method="POST" action="{{ route('admin.sponsorships.approve', $item) }}">
                                            @csrf
                                            <button type="submit" class="edit">
                                                <i class="bi bi-check-lg"></i> Valider
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.sponsorships.reject', $item) }}"
                                              onsubmit="return confirm('Refuser cette demande ?')">
                                            @csrf
                                            <button type="submit" class="delete">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tab === 'pending' ? 7 : 6 }}">
                                <div class="categories-empty">
                                    <span><i class="bi bi-inbox"></i></span>
                                    <h3>Aucune demande</h3>
                                    <p>Rien à afficher dans cet onglet pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rows->hasPages())
            <footer class="categories-pagination">
                {{ $rows->links() }}
            </footer>
        @endif
    </section>
</div>
@endsection

@push('styles')
    @include('admin.partials.catalog-admin-styles')
    <style>
        .sponsorship-tabs { display:flex; flex-wrap:wrap; gap:8px; padding: 0 19px; }
        .sponsorship-tabs a {
          padding:8px 14px; border-radius:999px; text-decoration:none; font-weight:700; font-size:.88rem;
          color:#666; background:#f4f5f7; border:1px solid transparent;
        }
        .sponsorship-tabs a.active { background:#fff0f0; color:#b30000; border-color:#ffd0d0; }
        .categories-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        @media (max-width: 1050px) {
            .categories-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .categories-stats { grid-template-columns: 1fr; }
        }
    </style>
@endpush
