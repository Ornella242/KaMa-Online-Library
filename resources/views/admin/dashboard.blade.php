@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('admin-content')
@php
    $formatAmounts = static function (array $amounts): string {
        if ($amounts === []) {
            return '0';
        }

        return collect($amounts)
            ->map(fn ($amount, $currency) => number_format($amount, 2, ',', ' ').' '.$currency)
            ->implode(' · ');
    };
@endphp
<div class="admin-dashboard">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Bonjour {{ auth()->user()->firstname }} 👋</span>
            <h2>Voici ce qui se passe sur KaMa aujourd’hui.</h2>
            <p>Suivez l’activité éditoriale, les membres et les revenus depuis un seul espace.</p>
        </div>
        <div class="admin-welcome-actions">
            
            <a href="{{ route('admin.books.create') }}" class="btn admin-btn-dark">
                <i class="bi bi-plus-lg me-2"></i>Ajouter un livre
            </a>
        </div>
    </section>

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon red"><i class="bi bi-bag-check-fill"></i></span>
            <div><small>Nombre de ventes</small><strong>{{ number_format($metrics['sales']) }}</strong><span>Achats de livres validés</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-cash-stack"></i></span>
            <div><small>Revenus globaux</small><strong class="admin-metric-amount">{{ $formatAmounts($metrics['global_revenue']) }}</strong><span>Tous revenus encaissés, par devise</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon violet"><i class="bi bi-arrow-repeat"></i></span>
            <div><small>Revenus abonnements</small><strong class="admin-metric-amount">{{ $formatAmounts($metrics['subscription_revenue']) }}</strong><span>Abonnements validés</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-megaphone-fill"></i></span>
            <div><small>Revenus publicité</small><strong class="admin-metric-amount">{{ $formatAmounts($metrics['advertising_revenue']) }}</strong><span>Campagnes et mises en avant payées</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-eye-fill"></i></span>
            <div><small>Visites sur le site</small><strong>{{ number_format($metrics['visits']) }}</strong><span>Pages publiques consultées</span></div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon dark"><i class="bi bi-cart-check-fill"></i></span>
            <div><small>Visites avec achat</small><strong>{{ number_format($metrics['converted_visits']) }}</strong><span>{{ $metrics['visits'] > 0 ? number_format(($metrics['converted_visits'] / $metrics['visits']) * 100, 1, ',', ' ').' % de conversion' : 'Aucune conversion enregistrée' }}</span></div>
        </article>
    </section>

    <div class="admin-dashboard-grid">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div><span>Performance</span><h3>Ventes et visites des 6 derniers mois</h3></div>
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

        <section class="admin-panel admin-country-sales-panel">
            <div class="admin-panel-header">
                <div><span>Répartition géographique</span><h3>Ventes par pays et montant</h3></div>
            </div>
            <div class="admin-country-sales">
                <div class="admin-country-sales-head">
                    <span>Pays</span><span>Ventes</span><span>Montant</span>
                </div>
                @forelse($salesByCountry as $countrySale)
                    <div class="admin-country-sales-row">
                        <strong><i class="bi bi-geo-alt-fill"></i>{{ $countrySale['country'] }}</strong>
                        <span>{{ number_format($countrySale['sales_count']) }}</span>
                        <b>{{ $formatAmounts($countrySale['amounts']) }}</b>
                    </div>
                @empty
                    <div class="admin-country-sales-empty">
                        <i class="bi bi-globe2"></i>
                        <p>Les ventes apparaîtront ici avec le pays renseigné par l’acheteur.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-dashboard{display:grid;gap:22px}.admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff;box-shadow:0 18px 45px rgba(179,0,0,.2)}.admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}.admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.55rem}.admin-welcome-card p{margin:0;opacity:.8}.admin-welcome-actions{display:flex;gap:10px;flex-shrink:0}.admin-welcome-actions .btn{padding:10px 15px;border-radius:10px;font-size:.8rem;font-weight:700}.admin-btn-dark{background:#151619;color:#fff}.admin-btn-dark:hover{background:#000;color:#fff}.admin-metric-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.admin-metric-card{display:flex;align-items:center;gap:15px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.04)}.admin-metric-icon{display:grid;width:48px;height:48px;flex:0 0 48px;place-items:center;border-radius:13px;font-size:1.15rem}.admin-metric-icon.red{background:#fff0f0;color:#b30000}.admin-metric-icon.dark{background:#ededee;color:#18191c}.admin-metric-icon.amber{background:#fff7df;color:#b57800}.admin-metric-icon.green{background:#eaf9ef;color:#138443}.admin-metric-card small,.admin-metric-card strong,.admin-metric-card span{display:block}.admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}.admin-metric-card strong{margin:2px 0;font-size:1.35rem}.admin-metric-card span{color:#8c9097;font-size:.68rem}.admin-dashboard-grid{display:grid;grid-template-columns:minmax(0,1.7fr) minmax(280px,.8fr);gap:18px}.admin-dashboard-grid-bottom{grid-template-columns:minmax(0,1.5fr) minmax(300px,.8fr)}.admin-panel{min-width:0;padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.035)}.admin-panel-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px}.admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}.admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}.admin-panel-header a{color:#b30000;font-size:.73rem;font-weight:700}.admin-panel-badge{padding:5px 8px;border-radius:999px;background:#eef8f1;color:#168046!important}.admin-status-list{display:grid;gap:8px}.admin-status-list a{display:flex;align-items:center;justify-content:space-between;padding:12px 3px;border-bottom:1px solid #f0f1f3;color:#3c3e43;font-size:.8rem}.admin-status-list a:last-child{border-bottom:0}.admin-status-list i{margin-right:8px}.admin-status-list strong{font-size:.9rem}.admin-dashboard-table{margin:0}.admin-dashboard-table th{padding:9px;color:#9699a0;font-size:.67rem;text-transform:uppercase;letter-spacing:.05em}.admin-dashboard-table td{padding:11px 9px;border-color:#f0f1f3;font-size:.76rem}.admin-book-cell{display:flex;align-items:center;gap:10px;min-width:190px}.admin-book-cell img{width:36px;height:46px;border-radius:6px;object-fit:cover;background:#eee}.admin-book-cell strong,.admin-book-cell small{display:block}.admin-book-cell strong{max-width:210px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.78rem}.admin-book-cell small{margin-top:2px;color:#9699a0;font-size:.65rem}.admin-row-action{display:grid;width:31px;height:31px;place-items:center;border-radius:8px;background:#f2f3f5;color:#333}.admin-user-list{display:grid}.admin-user-item{display:grid;grid-template-columns:42px minmax(0,1fr) auto;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f0f1f3;color:#292b2f}.admin-user-item:last-child{border-bottom:0}.admin-user-item img{width:40px;height:40px;border-radius:11px;object-fit:cover}.admin-user-item strong,.admin-user-item small{display:block}.admin-user-item strong{font-size:.78rem}.admin-user-item small{max-width:175px;margin-top:2px;overflow:hidden;color:#9699a0;font-size:.65rem;text-overflow:ellipsis}.admin-user-item>span{padding:4px 7px;border-radius:999px;background:#f2f3f5;color:#666970;font-size:.61rem;font-weight:700}
.admin-metric-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
.admin-metric-card{min-height:104px}
/* span.admin-metric-icon doit battre la règle ".admin-metric-card span" qui réduit la police et casse le centrage */
.admin-metric-card span.admin-metric-icon{display:grid;width:62px;height:62px;flex:0 0 62px;place-items:center;border-radius:17px;font-size:1.7rem;line-height:1}
.admin-metric-icon.blue{background:#e8f5ff;color:#1678ad}
.admin-metric-icon.violet{background:#f1edff;color:#6d4bc3}
.admin-metric-card .admin-metric-amount{font-size:1rem;line-height:1.35;white-space:normal}
.admin-country-sales{display:grid}
.admin-country-sales-head,.admin-country-sales-row{display:grid;grid-template-columns:minmax(0,1fr) 65px minmax(105px,.8fr);align-items:center;gap:12px}
.admin-country-sales-head{padding:0 3px 9px;border-bottom:1px solid #eceef0;color:#9699a0;font-size:.62rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase}
.admin-country-sales-head span:nth-child(n+2){text-align:right}
.admin-country-sales-row{padding:12px 3px;border-bottom:1px solid #f0f1f3}
.admin-country-sales-row:last-child{border-bottom:0}
.admin-country-sales-row strong{display:flex;min-width:0;align-items:center;gap:7px;overflow:hidden;color:#34363b;font-size:.73rem;text-overflow:ellipsis;white-space:nowrap}
.admin-country-sales-row strong i{color:#b30000}
.admin-country-sales-row span,.admin-country-sales-row b{text-align:right}
.admin-country-sales-row span{color:#686c73;font-size:.72rem}
.admin-country-sales-row b{color:#222429;font-size:.7rem}
.admin-country-sales-empty{display:grid;min-height:190px;place-items:center;align-content:center;color:#9699a0;text-align:center}
.admin-country-sales-empty i{margin-bottom:8px;color:#b30000;font-size:1.6rem}
.admin-country-sales-empty p{max-width:270px;margin:0;font-size:.72rem;line-height:1.55}
@media(max-width:1100px){.admin-metric-grid{grid-template-columns:repeat(2,1fr)}.admin-dashboard-grid,.admin-dashboard-grid-bottom{grid-template-columns:1fr}}
@media(max-width:700px){.admin-welcome-card{align-items:flex-start;flex-direction:column;padding:23px}.admin-welcome-actions{width:100%;flex-wrap:wrap}.admin-metric-grid{grid-template-columns:1fr}.admin-panel{padding:17px}.admin-metric-icon{width:56px;height:56px;flex-basis:56px;font-size:1.35rem}}
@media(max-width:480px){.admin-welcome-actions .btn{width:100%}.admin-country-sales-head,.admin-country-sales-row{grid-template-columns:minmax(0,1fr) 50px minmax(90px,.8fr)}}
</style>
@endpush

@push('scripts')
<script>
const activityElement = document.querySelector('#adminActivityChart');
if (activityElement && typeof ApexCharts !== 'undefined') {
    new ApexCharts(activityElement, {
        chart: {type: 'area', height: 285, toolbar: {show: false}, fontFamily: 'DM Sans, sans-serif'},
        series: [
            {name: 'Ventes', data: @json($monthlyPerformance->pluck('sales'))},
            {name: 'Visites', data: @json($monthlyPerformance->pluck('visits'))}
        ],
        colors: ['#b30000', '#18191c'],
        dataLabels: {enabled: false},
        stroke: {curve: 'smooth', width: 3},
        fill: {type: 'gradient', gradient: {opacityFrom: .25, opacityTo: .03}},
        xaxis: {categories: @json($monthlyPerformance->pluck('label')), axisBorder: {show: false}, axisTicks: {show: false}},
        yaxis: {min: 0, forceNiceScale: true},
        grid: {borderColor: '#eff0f2', strokeDashArray: 4},
        legend: {position: 'top', horizontalAlign: 'right'}
    }).render();
}
</script>
@endpush
