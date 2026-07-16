
@extends('layouts.writer')

@section('writer-content')

    <div class="pt-4">
        <div class="container-fluid vstack gap-4">
            <!-- Title START -->
            <div class="row">
                <div class="col-12">
                    <h1 class="fs-4 mb-0"><i class="bi bi-house-door fa-fw me-1"></i>Tableau de bord</h1>
                </div>
            </div>	
            <!-- Title END -->

            <!-- Counter 2 START -->
           <div class="row g-4 writer-stats">
                <!-- REVENUS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="writer-stat-card revenu-card">
                        <div class="writer-stat-top">
                            <h5>
                                Revenus
                            </h5>

                            <div class="writer-stat-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                        </div>

                        <div class="writer-stat-value">
                            {{$totalRevenue}} $
                        </div>

                        <div class="writer-stat-footer">
                            <span class="{{ $revenueGrowth >= 0 ? 'positive':'negative' }}">
                                <i class="bi {{ $revenueGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}"></i>
                                {{ abs($revenueGrowth) }}%
                            </span>

                            <small>
                                vs mois dernier
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="writer-stat-card readers-card">
                        <div class="writer-stat-top">
                            <h5>
                                Lecteurs
                            </h5>
                            <div class="writer-stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>

                        <div class="writer-stat-value">
                            {{ $totalReaders }}
                        </div>

                        <div class="writer-stat-footer">
                            <span class="{{ $readersGrowth >= 0 ? 'positive':'negative' }}">
                                <i class="bi {{ $readersGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}"></i>
                                {{ abs($readersGrowth) }}%
                            </span>

                            <small>
                                vs mois dernier
                            </small>
                        </div>
                    </div>
                </div>

                <!-- LIVRES -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="writer-stat-card books-card">
                        <div class="writer-stat-top">
                            <h5>
                                Publications
                            </h5>

                            <div class="writer-stat-icon">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                        </div>

                        <div class="writer-stat-value">
                            {{ $publishedBooks }}
                        </div>

                        <div class="writer-stat-footer">
                            <span class="neutral">
                                {{ $totalBooks }}
                            </span>

                            <small>
                                livres enregistrés
                            </small>
                        </div>
                    </div>
                </div>

                <!-- NOTES -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="writer-stat-card rating-card">
                        <div class="writer-stat-top">
                            <h5>
                                Note moyenne
                            </h5>

                            <div class="writer-stat-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>

                        <div class="writer-stat-value">
                            {{ number_format($averageRating ?? 0, 1) }}
                        </div>

                        <div class="writer-stat-footer">
                            <span class="neutral">
                                {{ $totalReviews }}
                            </span>

                            <small>
                                avis lecteurs
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Counter 3 END -->

            <!-- Booking table START -->
            <div class="row">
                <div class="col-12">

                    <div class="card border rounded-4 shadow-sm">

                        <!-- Header -->
                        <div class="card-header border-bottom bg-transparent">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <h5 class="mb-1">Livres les plus vendus</h5>
                                    <p class="text-black fw-semibold mb-0 small">
                                        Performance de vos ouvrages
                                    </p>
                                </div>


                                <a href="{{ url('/writer/books') }}" class="btn btn-sm btn-submit">
                                    Voir tous les livres
                                </a>

                            </div>

                        </div>


                        <!-- Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Livre</th>
                                            <th>Date d'ajout</th>
                                            <th>Ventes</th>
                                            <th>Note</th>
                                            <th class="text-end">
                                                Action
                                            </th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @forelse($bestBooks as $index => $book)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold">
                                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img 
                                                            src="{{ asset('storage/'.$book->cover_image) }}"
                                                            class="rounded-3 me-3"
                                                            width="50"
                                                            height="65"
                                                            style="object-fit:cover">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                {{ $book->title }}
                                                            </h6>
                                                            <small class="text-black">
                                                                {{ ucfirst($book->type) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    {{ $book->created_at->format('d M Y') }}
                                                </td>

                                                <td>
                                                    <span class="fw-semibold text-black">
                                                        {{ number_format($book->sales_count) }}
                                                    </span>

                                                    <small class="fw-semibold text-black">
                                                        ventes
                                                    </small>

                                                </td>

                                                <td>
                                                    @php
                                                        $rating = round($book->reviews_avg_rating ?? 0);
                                                    @endphp

                                                    <span class="text-warning">

                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $rating)

                                                                ★

                                                            @else
                                                                ☆
                                                            @endif
                                                        @endfor


                                                    </span>

                                                    <small>

                                                        {{ number_format($book->reviews_avg_rating ?? 0, 1) }}

                                                    </small>

                                                </td>


                                                <td class="text-end">
                                                    <a 
                                                        href="{{ route('writer.books.show',$book->id) }}"
                                                        class="btn btn-sm btn-light">
                                                        <i class="bi bi-eye text-red me-1"></i>
                                                    
                                                    </a>
                                                </td>
                                            </tr>


                                         @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    Aucun livre vendu pour le moment
                                                </td>
                                            </tr>


                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Booking table END -->
        </div>
    </section>

@endsection