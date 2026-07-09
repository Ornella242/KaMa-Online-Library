
@extends('layouts.writer')

@section('writer-content')

    <section class="pt-0">
        <div class="container vstack gap-4">
            <!-- Title START -->
            <div class="row">
                <div class="col-12">
                    <h1 class="fs-4 mb-0"><i class="bi bi-house-door fa-fw me-1"></i>Dashboard</h1>
                </div>
            </div>	
            <!-- Title END -->

            <!-- Counter 1 START -->
            {{-- <div class="row g-4">

                <!-- Total Books -->
                <div class="col-sm-6 col-xl-3">
                    <div class="author-stat-card">
                        <div class="stat-icon bg-success-soft">
                            <i class="bi bi-book"></i>
                        </div>

                        <div class="stat-content">
                            <h3>{{ $publishedBooks }}</h3>
                            <p>Livres publiés</p>
                            <span class="stat-growth 
                                {{ $booksGrowth >= 0 ? 'text-success':'text-danger' }}">

                                <i class="bi 
                                    {{ $booksGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}">
                                </i>

                                {{ abs($booksGrowth) }}%

                            </span>
                            <small>ce mois</small>
                        </div>
                    </div>
                </div>


                <!-- Earnings -->
                <div class="col-sm-6 col-xl-3">
                    <div class="author-stat-card">

                        <div class="stat-icon bg-primary-soft">
                            <i class="bi bi-wallet2"></i>
                        </div>

                        <div class="stat-content">
                            <h3>{{$totalRevenue}} $ </h3>
                            <p>Revenus</p>

                            <span class="stat-growth 
                                {{ $revenueGrowth >= 0 ? 'text-success':'text-danger' }}">

                                <i class="bi 
                                    {{ $revenueGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}">
                                </i>

                                {{ abs($revenueGrowth) }}%

                            </span>

                            <small>ce mois</small>
                        </div>

                    </div>
                </div>


                <!-- Readers -->
                <div class="col-sm-6 col-xl-3">
                    <div class="author-stat-card">

                        <div class="stat-icon bg-warning-soft">
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="stat-content">
                            <h3>{{ $totalReaders}}</h3>
                            <p>Lecteurs</p>

                            <span class="stat-growth 
                                {{ $readersGrowth >= 0 ? 'text-success':'text-danger' }}">

                                <i class="bi 
                                    {{ $readersGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}">
                                </i>
                                {{ abs($readersGrowth) }}%
                            </span>

                            <small>ce mois</small>
                        </div>

                    </div>
                </div>

                <!-- Reviews -->
                <div class="col-sm-6 col-xl-3">
                    <div class="author-stat-card">

                        <div class="stat-icon bg-danger-soft">
                            <i class="bi bi-star-fill"></i>
                        </div>

                        <div class="stat-content">
                            <h3>{{ number_format($averageRating ?? 0, 1) }}</h3>
                            <p>Note moyenne</p>
                            <p> Basé sur {{$totalReviews}} avis</p>

                            <span class="rating">
                                @php
                                    $rating = round($averageRating ?? 0);
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </span>

                        </div>

                    </div>
                </div>


            </div> --}}
            <!-- Counter 1 END -->

            <!-- Counter 2 START -->
            <div class="row g-4">
                <!-- REVENUS -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card revenue">

                        <div class="stat-header">
                            <span>Revenus</span>
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div class="stat-value">{{$totalRevenue}} $</div>

                        <div class="stat-meta">
                             <span class="stat-growth positive
                                {{ $revenueGrowth >= 0 ? 'text-success':'text-danger' }}">

                                <i class="bi 
                                    {{ $revenueGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}">
                                </i>

                                {{ abs($revenueGrowth) }}%

                            </span>
                            <span>vs mois dernier</span>
                        </div>

                    </div>
                </div>

                <!-- VENTES -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card sales">

                        <div class="stat-header">
                            <span>Lecteurs</span>
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="stat-value">{{ $totalReaders}}</div>

                        <div class="stat-meta">
                            <span class="stat-growth neutral
                                {{ $readersGrowth >= 0 ? 'text-success':'text-danger' }}">

                                <i class="bi 
                                    {{ $readersGrowth >= 0 ? 'bi-arrow-up':'bi-arrow-down' }}">
                                </i>
                                {{ abs($readersGrowth) }}%
                            </span>
                            <span>vs le mois dernier</span>
                        </div>

                    </div>
                </div>

                <!-- LIVRES -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card books">

                        <div class="stat-header">
                            <span>Livres publiés</span>
                            <i class="bi bi-journal-bookmark"></i>
                        </div>

                        <div class="stat-value">{{ $publishedBooks }}</div>

                        <div class="stat-meta">
                            <span class="neutral">{{ $totalBooks }}</span>
                            <span>livres enregistrés</span>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stat-card books">

                        <div class="stat-header">
                            <span>Notes</span>
                            <i class="bi bi-star"></i>
                        </div>

                        <div class="stat-value">{{ number_format($averageRating ?? 0, 1) }}</div>

                        <div class="stat-meta">
                            <span class="neutral">{{$totalReviews}}</span>
                            <span>Nombre total d'avis</span>
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
                                                        <i class="bi bi-eye me-1"></i>
                                                        Voir détail
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