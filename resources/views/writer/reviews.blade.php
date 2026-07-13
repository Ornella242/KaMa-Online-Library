@extends('layouts.writer')

@section('writer-content')
    <div class="container">
    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="review-stat-card">

                <div class="review-icon bg-danger-subtle">

                    <i class="bi bi-star-fill"></i>

                </div>

                <div>

                    <h2>{{ number_format($averageRating,1) }}</h2>

                    <span>Note moyenne</span>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="review-stat-card">

                <div class="review-icon bg-warning-subtle">

                    <i class="bi bi-chat-dots-fill"></i>

                </div>

                <div>

                    <h2>{{ $totalReviews }}</h2>

                    <span>Avis reçus</span>

                </div>

            </div>

        </div>



        <div class="col-md-3">

            <div class="review-stat-card">

                <div class="review-icon bg-success-subtle">

                    <i class="bi bi-hand-thumbs-up-fill"></i>

                </div>

                <div>

                    <h2>{{ $fiveStars }}</h2>

                    <span>Avis 5 étoiles</span>

                </div>

            </div>

        </div>



        <div class="col-md-3">

            <div class="review-stat-card">

                <div class="review-icon bg-primary-subtle">

                    <i class="bi bi-emoji-smile-fill"></i>

                </div>

                <div>

                    <h2>{{ $satisfaction }}%</h2>

                    <span>Satisfaction</span>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header review-header">

            <h5>

                <i class="bi bi-bar-chart-fill me-2"></i>

                Répartition des notes

            </h5>

        </div>


        <div class="card-body">


            @for($i=5;$i>=1;$i--)

            <div class="rating-row">

                <span>{{ $i }} ★</span>

                <div class="progress flex-grow-1">

                    <div class="progress-bar"

                        style="width:{{ $ratingPercentages[$i] }}%">

                    </div>

                </div>

                <strong>{{ $ratingCounts[$i] }}</strong>

            </div>

            @endfor


        </div>

    </div>

   <div class="row">

    <div class="col-12">

        <div class="card review border-0 shadow-sm rounded-4">
            <!-- HEADER -->
            <div class="card-header border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold text-white">
                            <i class="bi bi-chat-left-text-fill text-danger me-2"></i>
                            Avis des lecteurs
                        </h5>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                        {{ $reviews->total() }} avis
                    </span>
                </div>
            </div>

            <!-- BODY -->
            <div class="card-body p-4">
                @php

                    $reviews = collect([

                        (object)[
                            'user' => (object)[
                                'firstname' => 'Marie',
                                'lastname' => 'Dupont',
                                'avatar' => null
                            ],
                            'book' => (object)[
                                'title' => 'Le pouvoir des habitudes'
                            ],
                            'rating' => 5,
                            'comment' => 'Un excellent livre. Les conseils sont simples, pratiques et applicables au quotidien. Je recommande vivement cet ouvrage.',
                            'created_at' => now()->subDays(2)
                        ],


                        (object)[
                            'user' => (object)[
                                'firstname' => 'David',
                                'lastname' => 'Johnson',
                                'avatar' => null
                            ],
                            'book' => (object)[
                                'title' => 'Les chemins de la réussite'
                            ],
                            'rating' => 4,
                            'comment' => 'Un très bon ouvrage avec des idées intéressantes. Certains passages sont particulièrement motivants.',
                            'created_at' => now()->subWeek()
                        ],


                        (object)[
                            'user' => (object)[
                                'firstname' => 'Sophie',
                                'lastname' => 'Martin',
                                'avatar' => null
                            ],
                            'book' => (object)[
                                'title' => 'L’art de créer'
                            ],
                            'rating' => 5,
                            'comment' => 'Une lecture inspirante du début à la fin. Le contenu est riche et donne envie de passer à l’action.',
                            'created_at' => now()->subWeeks(2)
                        ],


                        (object)[
                            'user' => (object)[
                                'firstname' => 'Thomas',
                                'lastname' => 'Brown',
                                'avatar' => null
                            ],
                            'book' => (object)[
                                'title' => 'Développer sa confiance'
                            ],
                            'rating' => 3,
                            'comment' => 'Un livre intéressant mais certains chapitres auraient mérité plus de détails.',
                            'created_at' => now()->subMonth()
                        ]

                    ]);

                @endphp
                @forelse($reviews as $review)
                    <div class="review-box mb-4">
                        <!-- TOP -->
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <img
                                src="{{ $review->user->avatar 
                                    ? asset('storage/'.$review->user->avatar)
                                    : asset('assets/images/avatar/01.jpg') }}"
                                class="avatar avatar-md rounded-circle me-3">

                                <div>
                                    <h6 class="mb-1 fw-bold">

                                        {{ $review->user->firstname }}
                                        {{ $review->user->lastname }}

                                    </h6>
                                    <small class="text-black">

                                        {{ $review->created_at->diffForHumans() }}

                                    </small>
                                </div>
                            </div>

                            <!-- STARS -->
                            <div class="review-stars">
                                @for($i=1;$i<=5;$i++)

                                    @if($i <= $review->rating)

                                        <i class="bi bi-star-fill"></i>

                                    @else

                                        <i class="bi bi-star"></i>

                                    @endif
                                @endfor
                            </div>
                        </div>

                        <!-- BOOK -->
                        <h6 class="mt-4 mb-2">
                            <span class="text-black">
                                Avis sur :
                            </span>
                            {{ $review->book->title }}
                        </h6>

                        <!-- COMMENT -->
                        <p class="text-black mb-0">

                            {{ $review->comment }}

                        </p>



                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-chat-square-text display-5 text-danger"></i>
                        <h5 class="mt-3">
                            Aucun avis reçu
                        </h5>
                        <p class="text-black">
                            Les commentaires de vos lecteurs apparaîtront ici.
                        </p>
                    </div>
                @endforelse
            </div>



            <!-- FOOTER -->
            <div class="card-footer bg-white border-top p-4">
                {{-- {{ $reviews->links() }} --}}
            </div>


        </div>


    </div>


</div>
    
</div>


@endsection