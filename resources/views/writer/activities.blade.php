@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Suivi</span>
                <h1>Notifications & activités</h1>
                <p>Retrouvez ici toutes les alertes liées à vos livres et à votre compte auteur.</p>
            </div>
            @if($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash3"></i> Tout effacer
                    </button>
                </form>
            @endif
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Historique</span>
                    <h2>Toutes vos notifications</h2>
                    <p>{{ $notifications->total() }} notification(s) enregistrée(s).</p>
                </div>
            </header>

            <div class="writer-activity-list">
                @forelse($notifications as $notification)
                    @php
                        $dataType = $notification->data['type'] ?? '';
                        $class = class_basename($notification->type ?? '');
                        [$icon, $activityClass] = match (true) {
                            $dataType === 'book_published' || $class === 'BookPublishedNotification'
                                => ['bi bi-check-circle-fill', 'success'],
                            $dataType === 'book_rejected' || $class === 'BookRejectedNotification'
                                => ['bi bi-x-circle-fill', 'danger'],
                            $class === 'BookRevisionRequiredNotification'
                                => ['bi bi-pencil-square', 'warning'],
                            $class === 'BookUnderReviewNotification'
                                => ['bi bi-hourglass-split', 'info'],
                            $class === 'BookResubmittedNotification'
                                => ['bi bi-arrow-repeat', 'primary'],
                            default => ['bi bi-bell-fill', 'neutral'],
                        };
                        $url = $notification->data['url']
                            ?? (isset($notification->data['book_id'])
                                ? route('writer.books.show', $notification->data['book_id'])
                                : null);
                    @endphp
                    <article class="writer-activity-item {{ $notification->read_at ? '' : 'is-unread' }}">
                        <span class="writer-activity-icon {{ $activityClass }}">
                            <i class="{{ $icon }}"></i>
                        </span>
                        <div class="writer-activity-content">
                            <div>
                                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p>{{ $notification->data['message'] ?? '' }}</p>
                            @if($url)
                                <a href="{{ $url }}">
                                    <i class="bi bi-box-arrow-up-right"></i> Voir le détail
                                </a>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('writer.activities.destroy', $notification) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="writer-icon-action danger" aria-label="Supprimer la notification">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="writer-empty-state">
                        <span><i class="bi bi-bell"></i></span>
                        <h3>Aucune notification</h3>
                        <p>Les alertes sur vos livres (publication, révision, rejet…) apparaîtront ici.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <footer class="writer-panel-footer">{{ $notifications->links() }}</footer>
            @endif
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
.writer-activity-item.is-unread {
    background: #fff8f7;
    border-radius: 14px;
}
.writer-page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-end;
    gap: 12px;
}
</style>
@endpush
