@extends('layouts.admin')

@section('title', 'Avis lecteurs')
@section('page-title', 'Avis lecteurs')

@section('admin-content')
<div class="admin-books-page">
    <div class="admin-books-heading">
        <div>
            <span class="admin-books-eyebrow">Mon espace auteur</span>
            <h2>Avis lecteurs</h2>
            <p>Consultez les retours laissés par les lecteurs sur vos ouvrages.</p>
        </div>
    </div>

    <div class="admin-books-stats">
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon total"><i class="bi bi-star-fill"></i></span>
            <div><small>Note moyenne</small><strong>{{ number_format($averageRating, 1) }}/5</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon waiting"><i class="bi bi-chat-square-text"></i></span>
            <div><small>Avis reçus</small><strong>{{ number_format($totalReviews) }}</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon published"><i class="bi bi-hand-thumbs-up"></i></span>
            <div><small>5 étoiles</small><strong>{{ number_format($fiveStars) }}</strong></div>
        </div>
        <div class="admin-books-stat">
            <span class="admin-books-stat-icon review"><i class="bi bi-emoji-smile"></i></span>
            <div><small>Satisfaction</small><strong>{{ $satisfaction }}%</strong></div>
        </div>
    </div>

    <section class="admin-books-panel">
        <div class="admin-books-table-header">
            <div>
                <strong>Répartition des notes</strong>
                <span>Distribution de 1 à 5 étoiles</span>
            </div>
        </div>
        <div class="admin-author-ratings">
            @for($i = 5; $i >= 1; $i--)
                <div class="admin-author-rating-row">
                    <span>{{ $i }} ★</span>
                    <div><i style="width: {{ $ratingPercentages[$i] }}%"></i></div>
                    <strong>{{ $ratingCounts[$i] }}</strong>
                </div>
            @endfor
        </div>
    </section>

    <section class="admin-books-panel">
        <div class="admin-books-table-header">
            <div>
                <strong>{{ number_format($reviews->total()) }} avis</strong>
                <span>
                    @if($reviews->total())
                        Affichage de {{ $reviews->firstItem() }} à {{ $reviews->lastItem() }}
                    @else
                        Aucun avis pour le moment
                    @endif
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-books-table align-middle">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Lecteur</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="admin-books-title-cell">
                                    <img src="{{ $review->book?->cover_image
                                        ? asset('storage/'.$review->book->cover_image)
                                        : asset('assets/images/book/01.jpg') }}" alt="">
                                    <div>
                                        <strong>{{ $review->book?->title ?? 'Livre' }}</strong>
                                        <small>#{{ str_pad((string) ($review->book_id ?? 0), 4, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="admin-books-author">
                                    <strong>{{ trim(($review->user?->firstname ?? '').' '.($review->user?->lastname ?? '')) ?: 'Lecteur' }}</strong>
                                    <small>{{ $review->user?->email }}</small>
                                </div>
                            </td>
                            <td>
                                <strong class="admin-books-price">{{ $review->rating }}/5</strong>
                            </td>
                            <td>
                                <span class="admin-author-comment">{{ $review->comment ?: 'Sans commentaire.' }}</span>
                            </td>
                            <td>
                                <div class="admin-books-date">
                                    <strong>{{ $review->created_at?->format('d/m/Y') }}</strong>
                                    <small>{{ $review->created_at?->diffForHumans() }}</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="admin-books-empty">
                                    <span><i class="bi bi-chat-square"></i></span>
                                    <h3>Aucun avis</h3>
                                    <p>Les commentaires de vos lecteurs apparaîtront ici.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="admin-books-pagination">
                <span>Page {{ $reviews->currentPage() }} sur {{ $reviews->lastPage() }}</span>
                {{ $reviews->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
</div>
@endsection

@include('admin.partials.admin-books-styles')
@include('admin.partials.author-space-styles')
