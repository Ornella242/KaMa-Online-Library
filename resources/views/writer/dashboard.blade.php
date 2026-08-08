@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Vue d’ensemble</span>
                <h1>Tableau de bord</h1>
                <p>Suivez les performances de vos livres et l’évolution de votre audience.</p>
            </div>
            <a href="{{ route('writer.books.create') }}" class="writer-primary-action">
                <i class="bi bi-plus-lg"></i> Ajouter un livre
            </a>
        </header>

        <div class="writer-metric-grid">
            <article class="writer-metric">
                <span class="writer-metric-icon red"><i class="bi bi-currency-dollar"></i></span>
                <div>
                    <small>Revenus bruts</small>
                    <strong>{{ number_format($totalRevenue, 2, ',', ' ') }} $</strong>
                    <span class="{{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi {{ $revenueGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        {{ abs($revenueGrowth) }}% ce mois
                    </span>
                </div>
            </article>

            <article class="writer-metric">
                <span class="writer-metric-icon black"><i class="bi bi-people"></i></span>
                <div>
                    <small>Lecteurs uniques</small>
                    <strong>{{ number_format($totalReaders) }}</strong>
                    <span class="{{ $readersGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi {{ $readersGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        {{ abs($readersGrowth) }}% ce mois
                    </span>
                </div>
            </article>

            <article class="writer-metric">
                <span class="writer-metric-icon blue"><i class="bi bi-book-half"></i></span>
                <div>
                    <small>Livres publiés</small>
                    <strong>{{ number_format($publishedBooks) }}</strong>
                    <span class="{{ $booksGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi {{ $booksGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        {{ abs($booksGrowth) }}% ce mois
                    </span>
                </div>
            </article>

            <article class="writer-metric">
                <span class="writer-metric-icon amber"><i class="bi bi-star-fill"></i></span>
                <div>
                    <small>Note moyenne</small>
                    <strong>{{ number_format($averageRating ?? 0, 1) }}/5</strong>
                    <span class="neutral">{{ number_format($totalReviews) }} avis reçus</span>
                </div>
            </article>
        </div>

        <div class="writer-panel">
            <div class="writer-panel-header">
                <div>
                    <span>Performance de vos livres</span>
                    <h2>Revenus & investissements</h2>
                    <p>Comparez vos investissements et vos revenus pour chaque livre.</p>
                </div>
                <span class="writer-panel-badge">Données réelles</span>
            </div>
            <div id="writerBookPerformanceChart"></div>
        </div>

        <div class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Performance</span>
                    <h2>Livres les plus vendus</h2>
                    <p>Classement de vos ouvrages selon les achats confirmés.</p>
                </div>
                <a href="{{ route('writer.books') }}">Voir ma bibliothèque <i class="bi bi-arrow-right"></i></a>
            </header>

            <div class="table-responsive">
                <table class="table writer-data-table align-middle">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Livre</th>
                            <th>Date d’ajout</th>
                            <th>Ventes</th>
                            <th>Note</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bestBooks as $index => $book)
                            <tr>
                                <td><span class="writer-rank">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="writer-table-book">
                                        <img src="{{ $book->cover_image
                                            ? asset('storage/'.$book->cover_image)
                                            : asset('assets/images/book/01.jpg') }}"
                                             alt="">
                                        <div>
                                            <strong>{{ $book->title }}</strong>
                                            <small>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $book->created_at->format('d/m/Y') }}</td>
                                <td><strong>{{ number_format($book->sales_count) }}</strong></td>
                                <td>
                                    <span class="writer-rating">
                                        <i class="bi bi-star-fill"></i>
                                        {{ number_format($book->reviews_avg_rating ?? 0, 1) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('writer.books.show', $book) }}" class="writer-icon-action" aria-label="Voir le livre">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="writer-empty-state">
                                        <span><i class="bi bi-bar-chart"></i></span>
                                        <h3>Aucune vente pour le moment</h3>
                                        <p>Vos livres vendus apparaîtront ici.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const bookPerformanceElement = document.querySelector('#writerBookPerformanceChart');

if (bookPerformanceElement && typeof ApexCharts !== 'undefined') {

    new ApexCharts(bookPerformanceElement, {

        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
            fontFamily: 'DM Sans, sans-serif'
        },

        series: [
            {
                name: 'Budget investi',
                data: @json($bookPerformance->pluck('investment')->values())
            },
            {
                name: 'Ventes',
                data: @json($bookPerformance->pluck('sales')->values())
            }
        ],

        colors: ['#18191c', '#b30000'],

        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                borderRadius: 4
            }
        },

        dataLabels: {
            enabled: false
        },

        xaxis: {
            categories: @json($bookPerformance->pluck('label')->values()),

            axisBorder: {
                show: false
            },

            axisTicks: {
                show: false
            },

            labels: {
                rotate: -45,
                trim: true,
                maxHeight: 80
            }
        },

        yaxis: {
            min: 0,
            forceNiceScale: true,

            labels: {
                formatter: function(value) {
                    return '$' + value.toLocaleString();
                }
            }
        },

        tooltip: {
            y: {
                formatter: function(value) {
                    return '$' + value.toLocaleString();
                }
            }
        },

        grid: {
            borderColor: '#eff0f2',
            strokeDashArray: 4
        },

        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }

    }).render();
}
</script>
@endsection
