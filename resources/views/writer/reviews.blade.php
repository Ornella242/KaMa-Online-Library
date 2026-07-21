@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Communauté</span>
                <h1>Avis lecteurs</h1>
                <p>Découvrez les retours laissés par les lecteurs sur vos ouvrages.</p>
            </div>
        </header>

        <div class="writer-metric-grid">
            <article class="writer-metric">
                <span class="writer-metric-icon red"><i class="bi bi-star-fill"></i></span>
                <div><small>Note moyenne</small><strong>{{ number_format($averageRating, 1) }}/5</strong><span class="neutral">Tous les avis</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon black"><i class="bi bi-chat-square-text"></i></span>
                <div><small>Avis reçus</small><strong>{{ number_format($totalReviews) }}</strong><span class="neutral">Commentaires publiés</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon amber"><i class="bi bi-hand-thumbs-up"></i></span>
                <div><small>Excellents avis</small><strong>{{ number_format($fiveStars) }}</strong><span class="neutral">Notes de 5 étoiles</span></div>
            </article>
            <article class="writer-metric">
                <span class="writer-metric-icon blue"><i class="bi bi-emoji-smile"></i></span>
                <div><small>Satisfaction</small><strong>{{ $satisfaction }}%</strong><span class="neutral">Notes de 4 ou 5</span></div>
            </article>
        </div>

        <div class="writer-reviews-layout">
            <div class="writer-panel">
                <header class="writer-panel-header">
                    <div><span>Analyse</span><h2>Répartition des notes</h2><p>Distribution de 1 à 5 étoiles.</p></div>
                </header>
                <div class="writer-rating-distribution">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="writer-rating-row">
                            <span>{{ $i }} <i class="bi bi-star-fill"></i></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $ratingPercentages[$i] }}%"></div>
                            </div>
                            <strong>{{ $ratingCounts[$i] }}</strong>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="writer-panel writer-review-panel">
                <header class="writer-panel-header">
                    <div><span>Derniers retours</span><h2>Commentaires des lecteurs</h2><p>{{ $reviews->total() }} avis au total.</p></div>
                </header>

                <div class="writer-review-list">
                    @forelse($reviews as $review)
                        @php
                            $readerName = trim(
                                ($review->user?->firstname ?? '').' '.($review->user?->lastname ?? '')
                            ) ?: 'Lecteur KaMa';
                        @endphp
                        <article class="writer-review-item">
                            <div class="writer-review-avatar">
                                <img src="{{ $review->user?->avatar
                                    ? asset('storage/'.$review->user->avatar)
                                    : asset('assets/images/avatar/01.jpg') }}"
                                     alt="">
                            </div>
                            <div class="writer-review-content">
                                <div class="writer-review-meta">
                                    <div><strong>{{ $readerName }}</strong><small>{{ $review->created_at->diffForHumans() }}</small></div>
                                    <span>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                </div>
                                <small class="writer-review-book"><i class="bi bi-book"></i> {{ $review->book?->title ?? 'Livre indisponible' }}</small>
                                <p>{{ $review->comment }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="writer-empty-state">
                            <span><i class="bi bi-chat-square-text"></i></span>
                            <h3>Aucun avis reçu</h3>
                            <p>Les retours de vos lecteurs apparaîtront ici.</p>
                        </div>
                    @endforelse
                </div>

                @if($reviews->hasPages())
                    <footer class="writer-panel-footer">{{ $reviews->links() }}</footer>
                @endif
            </section>
        </div>
    </div>
</main>
@endsection
