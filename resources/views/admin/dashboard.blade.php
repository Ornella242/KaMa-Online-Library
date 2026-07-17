@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('admin-content')
<div class="admin-dashboard">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Bonjour {{ auth()->user()->firstname }} 👋</span>
            <h2>Voici ce qui se passe sur KaMa aujourd’hui.</h2>
            <p>Suivez l’activité éditoriale, les membres et les revenus depuis un seul espace.</p>
        </div>
        <div class="admin-welcome-actions">
            <a href="{{ route('admin.books.editorial.queue') }}" class="btn btn-light">
                <i class="bi bi-clipboard2-check me-2"></i>Traiter la file éditoriale
            </a>
            <a href="{{ route('admin.books.create') }}" class="btn admin-btn-dark">
                <i class="bi bi-plus-lg me-2"></i>Ajouter un livre
            </a>
        </div>
    </section>

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon red"><i class="bi bi-people-fill"></i></span>
            <div><small>Utilisateurs</small><strong>{{ number_format($metrics['users']) }}</strong><span>{{ number_format($metrics['writers']) }} écrivain(s)</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon dark"><i class="bi bi-book-half"></i></span>
            <div><small>Livres</small><strong>{{ number_format($metrics['books']) }}</strong><span>{{ number_format($metrics['published']) }} publié(s)</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-hourglass-split"></i></span>
            <div><small>À traiter</small><strong>{{ number_format($metrics['waiting'] + $metrics['under_review']) }}</strong><span>{{ number_format($metrics['waiting']) }} en attente</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-wallet2"></i></span>
            <div><small>Paiements validés</small><strong>{{ number_format($metrics['revenue'], 2, ',', ' ') }} $</strong><span>Toutes opérations réussies</span></div>
        </article>
    </section>

    <div class="admin-dashboard-grid">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Évolution</span><h3>Activité des 6 derniers mois</h3></div>
                <span class="admin-panel-badge">Données réelles</span>
            </div>
            <div id="adminActivityChart"></div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header"><div><span>Cycle éditorial</span><h3>État des livres</h3></div></div>
            <div class="admin-status-list">
                <a href="{{ route('admin.books.all') }}"><span><i class="bi bi-check-circle-fill text-success"></i> Publiés</span><strong>{{ $metrics['published'] }}</strong></a>
                <a href="{{ route('admin.books.all') }}"><span><i class="bi bi-clock-fill text-warning"></i> En attente</span><strong>{{ $metrics['waiting'] }}</strong></a>
                <a href="{{ route('admin.books.editorial.queue') }}"><span><i class="bi bi-search text-primary"></i> En vérification</span><strong>{{ $metrics['under_review'] }}</strong></a>
                <a href="{{ route('admin.books.all') }}"><span><i class="bi bi-arrow-counterclockwise text-danger"></i> À corriger</span><strong>{{ $metrics['revision_required'] }}</strong></a>
                <a href="{{ route('admin.categories.index') }}"><span><i class="bi bi-tags-fill text-secondary"></i> Catégories</span><strong>{{ $metrics['categories'] }}</strong></a>
            </div>
        </section>
    </div>

    <div class="admin-dashboard-grid admin-dashboard-grid-bottom">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Priorité</span><h3>Livres à suivre</h3></div>
                <a href="{{ route('admin.books.all') }}">Voir tous <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table admin-dashboard-table align-middle">
                    <thead><tr><th>Livre</th><th>Auteur</th><th>Statut</th><th></th></tr></thead>
                    <tbody>
                        @forelse($recentBooks as $book)
                            @php
                                [$statusLabel, $statusClass] = match($book->status) {
                                    'waiting_review' => ['En attente', 'warning'],
                                    'under_review' => ['En vérification', 'info'],
                                    'revision_required' => ['À corriger', 'danger'],
                                    default => ['Inconnu', 'secondary'],
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="admin-book-cell">
                                        <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                                        <div><strong>{{ $book->title }}</strong><small>{{ $book->category?->name ?? 'Sans catégorie' }}</small></div>
                                    </div>
                                </td>
                                <td>{{ trim(($book->author?->firstname ?? '').' '.($book->author?->lastname ?? '')) ?: '—' }}</td>
                                <td><span class="badge text-bg-{{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td><a href="{{ route('admin.books.show', $book) }}" class="admin-row-action" aria-label="Voir le livre"><i class="bi bi-arrow-up-right"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Aucun livre ne nécessite d’action.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Communauté</span><h3>Nouveaux membres</h3></div>
                <a href="{{ route('admin.users') }}">Voir tous <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="admin-user-list">
                @forelse($recentUsers as $user)
                    @php $displayName = trim($user->firstname.' '.$user->lastname) ?: $user->email; @endphp
                    <a href="{{ route('admin.show', $user) }}" class="admin-user-item">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/avatar/01.jpg') }}" alt="">
                        <div><strong>{{ $displayName }}</strong><small>{{ $user->email }}</small></div>
                        <span>{{ ucfirst($user->role?->name ?? 'membre') }}</span>
                    </a>
                @empty
                    <p class="text-center text-muted py-5">Aucun utilisateur.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-dashboard{display:grid;gap:22px}.admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff;box-shadow:0 18px 45px rgba(179,0,0,.2)}.admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}.admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.55rem}.admin-welcome-card p{margin:0;opacity:.8}.admin-welcome-actions{display:flex;gap:10px;flex-shrink:0}.admin-welcome-actions .btn{padding:10px 15px;border-radius:10px;font-size:.8rem;font-weight:700}.admin-btn-dark{background:#151619;color:#fff}.admin-btn-dark:hover{background:#000;color:#fff}.admin-metric-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.admin-metric-card{display:flex;align-items:center;gap:15px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.04)}.admin-metric-icon{display:grid;width:48px;height:48px;flex:0 0 48px;place-items:center;border-radius:13px;font-size:1.15rem}.admin-metric-icon.red{background:#fff0f0;color:#b30000}.admin-metric-icon.dark{background:#ededee;color:#18191c}.admin-metric-icon.amber{background:#fff7df;color:#b57800}.admin-metric-icon.green{background:#eaf9ef;color:#138443}.admin-metric-card small,.admin-metric-card strong,.admin-metric-card span{display:block}.admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}.admin-metric-card strong{margin:2px 0;font-size:1.35rem}.admin-metric-card span{color:#8c9097;font-size:.68rem}.admin-dashboard-grid{display:grid;grid-template-columns:minmax(0,1.7fr) minmax(280px,.8fr);gap:18px}.admin-dashboard-grid-bottom{grid-template-columns:minmax(0,1.5fr) minmax(300px,.8fr)}.admin-panel{min-width:0;padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.035)}.admin-panel-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px}.admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}.admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}.admin-panel-header a{color:#b30000;font-size:.73rem;font-weight:700}.admin-panel-badge{padding:5px 8px;border-radius:999px;background:#eef8f1;color:#168046!important}.admin-status-list{display:grid;gap:8px}.admin-status-list a{display:flex;align-items:center;justify-content:space-between;padding:12px 3px;border-bottom:1px solid #f0f1f3;color:#3c3e43;font-size:.8rem}.admin-status-list a:last-child{border-bottom:0}.admin-status-list i{margin-right:8px}.admin-status-list strong{font-size:.9rem}.admin-dashboard-table{margin:0}.admin-dashboard-table th{padding:9px;color:#9699a0;font-size:.67rem;text-transform:uppercase;letter-spacing:.05em}.admin-dashboard-table td{padding:11px 9px;border-color:#f0f1f3;font-size:.76rem}.admin-book-cell{display:flex;align-items:center;gap:10px;min-width:190px}.admin-book-cell img{width:36px;height:46px;border-radius:6px;object-fit:cover;background:#eee}.admin-book-cell strong,.admin-book-cell small{display:block}.admin-book-cell strong{max-width:210px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.78rem}.admin-book-cell small{margin-top:2px;color:#9699a0;font-size:.65rem}.admin-row-action{display:grid;width:31px;height:31px;place-items:center;border-radius:8px;background:#f2f3f5;color:#333}.admin-user-list{display:grid}.admin-user-item{display:grid;grid-template-columns:42px minmax(0,1fr) auto;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f0f1f3;color:#292b2f}.admin-user-item:last-child{border-bottom:0}.admin-user-item img{width:40px;height:40px;border-radius:11px;object-fit:cover}.admin-user-item strong,.admin-user-item small{display:block}.admin-user-item strong{font-size:.78rem}.admin-user-item small{max-width:175px;margin-top:2px;overflow:hidden;color:#9699a0;font-size:.65rem;text-overflow:ellipsis}.admin-user-item>span{padding:4px 7px;border-radius:999px;background:#f2f3f5;color:#666970;font-size:.61rem;font-weight:700}
@media(max-width:1100px){.admin-metric-grid{grid-template-columns:repeat(2,1fr)}.admin-dashboard-grid,.admin-dashboard-grid-bottom{grid-template-columns:1fr}}@media(max-width:700px){.admin-welcome-card{align-items:flex-start;flex-direction:column;padding:23px}.admin-welcome-actions{width:100%;flex-wrap:wrap}.admin-metric-grid{grid-template-columns:1fr}.admin-panel{padding:17px}}@media(max-width:480px){.admin-welcome-actions .btn{width:100%}}
</style>
@endpush

@push('scripts')
<script>
const activityElement = document.querySelector('#adminActivityChart');
if (activityElement && typeof ApexCharts !== 'undefined') {
    new ApexCharts(activityElement, {
        chart: {type: 'area', height: 285, toolbar: {show: false}, fontFamily: 'DM Sans, sans-serif'},
        series: [
            {name: 'Livres ajoutés', data: @json($monthlyActivity->pluck('books'))},
            {name: 'Nouveaux membres', data: @json($monthlyActivity->pluck('users'))}
        ],
        colors: ['#b30000', '#18191c'],
        dataLabels: {enabled: false},
        stroke: {curve: 'smooth', width: 3},
        fill: {type: 'gradient', gradient: {opacityFrom: .25, opacityTo: .03}},
        xaxis: {categories: @json($monthlyActivity->pluck('label')), axisBorder: {show: false}, axisTicks: {show: false}},
        yaxis: {min: 0, forceNiceScale: true},
        grid: {borderColor: '#eff0f2', strokeDashArray: 4},
        legend: {position: 'top', horizontalAlign: 'right'}
    }).render();
}
</script>
@endpush
