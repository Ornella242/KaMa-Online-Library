@extends('layouts.writer')

@section('writer-content')

    <div class="container-fluid pt-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-header activity-header">

                        <div>

                        <h5 class="mb-1 texte-white"> <i class="bi bi-bell-fill me-2"></i> Activités récentes </h5>
                                <p class="mb-0">
                                Suivez toutes les actions liées à vos livres.
                                </p>
                        </div>

                    </div>

                    <div class="card-body p-4">
                        <div class="timeline">
                            @php
                            $activities = collect([

                                (object)[
                                    'title' => 'Votre livre a été publié',
                                    'description' => 'Votre ouvrage est maintenant disponible pour les lecteurs sur KaMa.',
                                    'icon' => 'bi bi-check-circle-fill',
                                    'color' => 'success',
                                    'book' => (object)[
                                        'title' => 'Le pouvoir des habitudes'
                                    ],
                                    'created_at' => now()->subHours(2)
                                ],


                                (object)[
                                    'title' => 'Nouveau lecteur',
                                    'description' => 'Un lecteur vient d’acheter votre livre.',
                                    'icon' => 'bi bi-cart-check-fill',
                                    'color' => 'primary',
                                    'book' => (object)[
                                        'title' => 'Les chemins de la réussite'
                                    ],
                                    'created_at' => now()->subDay()
                                ],


                                (object)[
                                    'title' => 'Nouveau commentaire reçu',
                                    'description' => 'Marie Dupont a laissé un avis 5 étoiles sur votre ouvrage.',
                                    'icon' => 'bi bi-star-fill',
                                    'color' => 'warning',
                                    'book' => (object)[
                                        'title' => 'L’art de créer'
                                    ],
                                    'created_at' => now()->subDays(3)
                                ],


                                (object)[
                                    'title' => 'Paiement du dépôt effectué',
                                    'description' => 'Le paiement du dépôt de publication a été confirmé.',
                                    'icon' => 'bi bi-credit-card-fill',
                                    'color' => 'danger',
                                    'book' => (object)[
                                        'title' => 'Développer sa confiance'
                                    ],
                                    'created_at' => now()->subWeek()
                                ],


                                (object)[
                                    'title' => 'Livre modifié',
                                    'description' => 'Vous avez mis à jour les informations de votre livre.',
                                    'icon' => 'bi bi-pencil-square',
                                    'color' => 'info',
                                    'book' => (object)[
                                        'title' => 'Réussir ses objectifs'
                                    ],
                                    'created_at' => now()->subWeeks(2)
                                ]

                            ]);
                            @endphp

                            @forelse($activities as $activity)
                               <div class="activity-item">

                                <div class="activity-left">

                                    <div class="activity-icon bg-{{ $activity->color }}">
                                        <i class="{{ $activity->icon }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>
                                            {{ $activity->title }}
                                        </h6>
                                        <p>
                                            {{ $activity->description }}
                                        </p>
                                        @if($activity->book)

                                            <span class="book-name">
                                                <i class="bi bi-book"></i>
                                                {{ $activity->book->title }}
                                            </span>
                                        @endif

                                        <small>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>

                                <div class="activity-actions">
                                    <form action="" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            class="btn-delete-activity">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>

                            </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-bell display-5 text-danger"></i>
                                    <h5 class="mt-3"> Aucune activité </h5>
                                    <p class="text-muted"> Vos actions apparaîtront ici.</p>
                                </div>
                            @endforelse

                        </div>
                    </div>

                    <div class="card-footer">
                    {{-- {{ $activities->links() }} --}}
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection