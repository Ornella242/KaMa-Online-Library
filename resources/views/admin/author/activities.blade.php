@extends('layouts.admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('admin-content')
<div class="admin-books-page">
    <div class="admin-books-heading">
        <div>
            <span class="admin-books-eyebrow">Mon espace auteur</span>
            <h2>Notifications</h2>
            <p>{{ $notifications->total() }} alerte(s) liée(s) à vos livres et à votre compte auteur.</p>
        </div>
        @if($notifications->total() > 0)
            <form method="POST" action="{{ route('notifications.clear') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-books-add" style="background:#fff;color:#b30000;border:1px solid #f0c8c8;">
                    <i class="bi bi-trash3"></i> Tout effacer
                </button>
            </form>
        @endif
    </div>

    <section class="admin-books-panel">
        <div class="admin-books-table-header">
            <div>
                <strong>{{ number_format($notifications->total()) }} notification(s)</strong>
                <span>
                    @if($notifications->total())
                        Affichage de {{ $notifications->firstItem() }} à {{ $notifications->lastItem() }}
                    @else
                        Aucune notification
                    @endif
                </span>
            </div>
        </div>

        <div class="admin-author-notifications">
            @forelse($notifications as $notification)
                @php
                    $dataType = $notification->data['type'] ?? '';
                    $class = class_basename($notification->type ?? '');
                    $notifMeta = match (true) {
                        $dataType === 'book_published' || $class === 'BookPublishedNotification'
                            => ['bi bi-check-circle-fill', 'published'],
                        $dataType === 'book_rejected' || $class === 'BookRejectedNotification'
                            => ['bi bi-x-circle-fill', 'rejected'],
                        $class === 'BookRevisionRequiredNotification'
                            => ['bi bi-pencil-square', 'revision'],
                        $class === 'BookUnderReviewNotification'
                            => ['bi bi-hourglass-split', 'review'],
                        $class === 'BookResubmittedNotification'
                            => ['bi bi-arrow-repeat', 'waiting'],
                        default => ['bi bi-bell-fill', 'draft'],
                    };
                    $icon = $notifMeta[0];
                    $tone = $notifMeta[1];
                    $url = $notification->data['url']
                        ?? (isset($notification->data['book_id'])
                            ? route('admin.books.show', $notification->data['book_id'])
                            : null);
                @endphp
                <article class="admin-author-notif {{ $notification->read_at ? '' : 'is-unread' }}">
                    <span class="admin-books-status {{ $tone }}"><span></span><i class="{{ $icon }}"></i></span>
                    <div>
                        <div class="admin-author-notif-meta">
                            <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                            <time>{{ $notification->created_at->diffForHumans() }}</time>
                        </div>
                        <p>{{ $notification->data['message'] ?? '' }}</p>
                        @if($url)
                            <a href="{{ $url }}"><i class="bi bi-box-arrow-up-right"></i> Voir le détail</a>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.author.activities.destroy', $notification) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-author-notif-delete" aria-label="Supprimer">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </article>
            @empty
                <div class="admin-books-empty">
                    <span><i class="bi bi-bell"></i></span>
                    <h3>Aucune notification</h3>
                    <p>Les alertes sur vos livres apparaîtront ici.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="admin-books-pagination">
                <span>Page {{ $notifications->currentPage() }} sur {{ $notifications->lastPage() }}</span>
                {{ $notifications->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
</div>
@endsection

@include('admin.partials.admin-books-styles')
@include('admin.partials.author-space-styles')
