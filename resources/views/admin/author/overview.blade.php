@extends('layouts.admin')

@section('title', 'Vue d’ensemble')
@section('page-title', 'Vue d’ensemble')

@section('admin-content')
<div class="admin-dashboard author-overview">
    <section class="admin-welcome-card">
        <div>
            <span class="admin-welcome-kicker">Mon espace auteur</span>
            <h2>Vos livres, vos lecteurs, votre solde.</h2>
            <p>Cette vue ne concerne que les ouvrages publiés avec votre compte auteur.</p>
        </div>
        <div class="admin-welcome-actions">
            <a href="{{ route('admin.author.withdrawals.create') }}" class="btn btn-light">
                <i class="bi bi-cash-coin me-2"></i>Faire un retrait
            </a>
            <a href="{{ route('admin.books.create') }}" class="btn admin-btn-dark">
                <i class="bi bi-plus-lg me-2"></i>Ajouter un livre
            </a>
        </div>
    </section>

    <section class="admin-metric-grid">
        <article class="admin-metric-card">
            <span class="admin-metric-icon green"><i class="bi bi-wallet2"></i></span>
            <div>
                <small>Solde à retirer</small>
                <strong class="admin-metric-amount">{{ number_format($wallet->balance, 2, ',', ' ') }} €</strong>
                <span>Part auteur déjà créditée</span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon red"><i class="bi bi-cash-stack"></i></span>
            <div>
                <small>Ventes brutes</small>
                <strong class="admin-metric-amount">{{ number_format($totalRevenue, 2, ',', ' ') }} €</strong>
                <span class="{{ $revenueGrowth >= 0 ? 'is-up' : 'is-down' }}">
                    <i class="bi {{ $revenueGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                    {{ abs($revenueGrowth) }} % ce mois
                </span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon dark"><i class="bi bi-people"></i></span>
            <div>
                <small>Lecteurs uniques</small>
                <strong>{{ number_format($totalReaders) }}</strong>
                <span class="{{ $readersGrowth >= 0 ? 'is-up' : 'is-down' }}">
                    <i class="bi {{ $readersGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                    {{ abs($readersGrowth) }} % ce mois
                </span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon blue"><i class="bi bi-book-half"></i></span>
            <div>
                <small>Livres publiés</small>
                <strong>{{ number_format($publishedBooks) }}</strong>
                <span class="{{ $booksGrowth >= 0 ? 'is-up' : 'is-down' }}">
                    <i class="bi {{ $booksGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                    {{ abs($booksGrowth) }} % ce mois
                </span>
            </div>
        </article>
        <article class="admin-metric-card">
            <span class="admin-metric-icon amber"><i class="bi bi-star-fill"></i></span>
            <div>
                <small>Note moyenne</small>
                <strong>{{ number_format($averageRating ?? 0, 1, ',', ' ') }}/5</strong>
                <span>{{ number_format($totalReviews) }} avis reçus</span>
            </div>
        </article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <div>
                <span>Performance de vos livres</span>
                <h3>Ventes et investissements</h3>
            </div>
            <span class="admin-panel-badge">En euros</span>
        </div>
        <div id="authorBookPerformanceChart"></div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <div>
                <span>Performance</span>
                <h3>Livres les plus vendus</h3>
            </div>
            <a href="{{ route('admin.books.index') }}">Voir mes livres <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table admin-dashboard-table align-middle">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Livre</th>
                        <th>Date d’ajout</th>
                        <th>Ventes</th>
                        <th>Note</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestBooks as $index => $book)
                        <tr>
                            <td><strong>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                <div class="admin-book-cell">
                                    <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
                                    <div>
                                        <strong>{{ $book->title }}</strong>
                                        <small>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $book->created_at?->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($book->sales_count) }}</strong></td>
                            <td>
                                <span class="author-rating"><i class="bi bi-star-fill"></i> {{ number_format($book->reviews_avg_rating ?? 0, 1, ',', ' ') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.books.show', $book) }}" class="admin-row-action" aria-label="Voir le livre">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Aucune vente pour le moment. Vos livres vendus apparaîtront ici.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.author-overview{display:grid;gap:22px}
.author-overview .admin-welcome-card{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px 30px;border-radius:20px;background:linear-gradient(120deg,#b30000 0%,#7e0000 100%);color:#fff;box-shadow:0 18px 45px rgba(179,0,0,.2)}
.author-overview .admin-welcome-kicker{display:block;margin-bottom:5px;font-size:.78rem;font-weight:700;opacity:.76;text-transform:uppercase;letter-spacing:.08em}
.author-overview .admin-welcome-card h2{margin:0 0 7px;color:#fff;font-size:1.55rem}
.author-overview .admin-welcome-card p{margin:0;opacity:.8}
.author-overview .admin-welcome-actions{display:flex;gap:10px;flex-shrink:0}
.author-overview .admin-welcome-actions .btn{padding:10px 15px;border-radius:10px;font-size:.8rem;font-weight:700}
.author-overview .admin-btn-dark{background:#151619;color:#fff}
.author-overview .admin-btn-dark:hover{background:#000;color:#fff}
.author-overview .admin-metric-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.author-overview .admin-metric-card{display:flex;align-items:center;gap:15px;min-height:104px;padding:20px;border:1px solid #e7e8eb;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.04)}
.author-overview .admin-metric-card span.admin-metric-icon{display:grid;width:62px;height:62px;flex:0 0 62px;place-items:center;border-radius:17px;font-size:1.7rem;line-height:1}
.author-overview .admin-metric-icon.red{background:#fff0f0;color:#b30000}
.author-overview .admin-metric-icon.dark{background:#ededee;color:#18191c}
.author-overview .admin-metric-icon.amber{background:#fff7df;color:#b57800}
.author-overview .admin-metric-icon.green{background:#eaf9ef;color:#138443}
.author-overview .admin-metric-icon.blue{background:#e8f5ff;color:#1678ad}
.author-overview .admin-metric-card small,.author-overview .admin-metric-card strong,.author-overview .admin-metric-card span:not(.admin-metric-icon){display:block}
.author-overview .admin-metric-card small{color:#858991;font-size:.72rem;font-weight:700}
.author-overview .admin-metric-card strong{margin:2px 0;font-size:1.35rem}
.author-overview .admin-metric-card .admin-metric-amount{font-size:1.05rem;line-height:1.35}
.author-overview .admin-metric-card span:not(.admin-metric-icon){color:#8c9097;font-size:.68rem}
.author-overview .is-up{color:#138443!important}
.author-overview .is-down{color:#b30000!important}
.author-overview .admin-panel{min-width:0;padding:22px;border:1px solid #e7e8eb;border-radius:17px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.035)}
.author-overview .admin-panel-header{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:18px}
.author-overview .admin-panel-header span{color:#999ca3;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}
.author-overview .admin-panel-header h3{margin:2px 0 0;font-size:1.02rem}
.author-overview .admin-panel-header a{color:#b30000;font-size:.73rem;font-weight:700}
.author-overview .admin-panel-badge{padding:5px 8px;border-radius:999px;background:#eef8f1;color:#168046!important}
.author-overview .admin-dashboard-table{margin:0}
.author-overview .admin-dashboard-table th{padding:9px;color:#9699a0;font-size:.67rem;text-transform:uppercase;letter-spacing:.05em}
.author-overview .admin-dashboard-table td{padding:11px 9px;border-color:#f0f1f3;font-size:.76rem}
.author-overview .admin-book-cell{display:flex;align-items:center;gap:10px;min-width:190px}
.author-overview .admin-book-cell img{width:36px;height:46px;border-radius:6px;object-fit:cover;background:#eee}
.author-overview .admin-book-cell strong,.author-overview .admin-book-cell small{display:block}
.author-overview .admin-book-cell strong{max-width:210px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.78rem}
.author-overview .admin-book-cell small{margin-top:2px;color:#9699a0;font-size:.65rem}
.author-overview .admin-row-action{display:grid;width:31px;height:31px;place-items:center;border-radius:8px;background:#f2f3f5;color:#333}
.author-overview .author-rating{color:#b57800;font-weight:700}
@media(max-width:1100px){.author-overview .admin-metric-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:700px){.author-overview .admin-welcome-card{align-items:flex-start;flex-direction:column;padding:23px}.author-overview .admin-welcome-actions{width:100%;flex-wrap:wrap}.author-overview .admin-metric-grid{grid-template-columns:1fr}.author-overview .admin-panel{padding:17px}}
@media(max-width:480px){.author-overview .admin-welcome-actions .btn{width:100%}}
</style>
@endpush

@push('scripts')
<script>
const authorPerformanceElement = document.querySelector('#authorBookPerformanceChart');
if (authorPerformanceElement && typeof ApexCharts !== 'undefined') {
    const euro = (value) => Number(value || 0).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' €';
    new ApexCharts(authorPerformanceElement, {
        chart: {type: 'bar', height: 340, toolbar: {show: false}, fontFamily: 'DM Sans, sans-serif'},
        series: [
            {name: 'Investi', data: @json($bookPerformance->pluck('investment')->values())},
            {name: 'Ventes', data: @json($bookPerformance->pluck('sales')->values())}
        ],
        colors: ['#18191c', '#b30000'],
        plotOptions: {bar: {horizontal: false, columnWidth: '45%', borderRadius: 4}},
        dataLabels: {enabled: false},
        xaxis: {
            categories: @json($bookPerformance->pluck('label')->values()),
            axisBorder: {show: false},
            axisTicks: {show: false},
            labels: {rotate: -45, trim: true, maxHeight: 80}
        },
        yaxis: {min: 0, forceNiceScale: true, labels: {formatter: euro}},
        tooltip: {y: {formatter: euro}},
        grid: {borderColor: '#eff0f2', strokeDashArray: 4},
        legend: {position: 'top', horizontalAlign: 'right'}
    }).render();
}
</script>
@endpush
