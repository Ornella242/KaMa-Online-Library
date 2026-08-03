@extends('layouts.admin')

@section('title', 'Mes retraits')
@section('page-title', 'Mes retraits')

@section('admin-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Finances</span>
                <h1>Mes retraits</h1>
                <p>Suivez l’avancement de vos demandes de versement.</p>
            </div>
            <a href="{{ route('admin.author.withdrawals.create') }}" class="writer-primary-action">
                <i class="bi bi-plus-lg"></i> Nouvelle demande
            </a>
        </header>

        <div class="writer-panel">
            <div class="table-responsive">
                <table class="table writer-data-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Montant</th>
                            <th>Commission</th>
                            <th>Net</th>
                            <th>Mode</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            @php
                                $statusClass = match($withdrawal->status) {
                                    'completed' => 'success',
                                    'processing' => 'pending',
                                    'rejected' => 'danger',
                                    default => 'pending',
                                };
                            @endphp
                            <tr>
                                <td><strong>#{{ $withdrawal->id }}</strong></td>
                                <td>${{ number_format($withdrawal->amount, 2, '.', ',') }}</td>
                                <td>${{ number_format($withdrawal->commission_amount, 2, '.', ',') }}
                                    <small class="d-block text-muted">{{ number_format($withdrawal->commission_percent, 2, '.', ',') }}%</small>
                                </td>
                                <td><strong class="writer-money-positive">${{ number_format($withdrawal->net_amount, 2, '.', ',') }}</strong></td>
                                <td>{{ $withdrawal->methodLabel() }}</td>
                                <td><span class="writer-status {{ $statusClass }}">{{ $withdrawal->statusLabel() }}</span></td>
                                <td>{{ $withdrawal->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="writer-empty-state">
                                        <span><i class="bi bi-cash-stack"></i></span>
                                        <h3>Aucun retrait</h3>
                                        <p>Lancez une demande depuis votre portefeuille.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdrawals->hasPages())
                <footer class="writer-panel-footer">{{ $withdrawals->links() }}</footer>
            @endif
        </div>
    </div>
</main>
@endsection
