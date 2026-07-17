@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Suivi</span>
                <h1>Activités</h1>
                <p>Retrouvez les événements importants liés à vos livres.</p>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <section class="writer-panel">
            <header class="writer-panel-header">
                <div>
                    <span>Historique</span>
                    <h2>Activités récentes</h2>
                    <p>{{ $activities->total() }} événement(s) enregistré(s).</p>
                </div>
            </header>

            <div class="writer-activity-list">
                @forelse($activities as $activity)
                    @php
                        $activityClass = in_array($activity->color, ['success', 'warning', 'info', 'danger', 'primary'], true)
                            ? $activity->color
                            : 'neutral';
                    @endphp
                    <article class="writer-activity-item">
                        <span class="writer-activity-icon {{ $activityClass }}">
                            <i class="{{ $activity->icon ?: 'bi bi-activity' }}"></i>
                        </span>
                        <div class="writer-activity-content">
                            <div>
                                <strong>{{ $activity->title }}</strong>
                                <small>{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p>{{ $activity->description }}</p>
                            @if($activity->book)
                                <a href="{{ route('writer.books.show', $activity->book) }}">
                                    <i class="bi bi-book"></i> {{ $activity->book->title }}
                                </a>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('writer.activities.destroy', $activity) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="writer-icon-action danger" aria-label="Supprimer l’activité">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="writer-empty-state">
                        <span><i class="bi bi-activity"></i></span>
                        <h3>Aucune activité</h3>
                        <p>Les événements relatifs à vos livres apparaîtront ici.</p>
                    </div>
                @endforelse
            </div>

            @if($activities->hasPages())
                <footer class="writer-panel-footer">{{ $activities->links() }}</footer>
            @endif
        </section>
    </div>
</main>
@endsection
