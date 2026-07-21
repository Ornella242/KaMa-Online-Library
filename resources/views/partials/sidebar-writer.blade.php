@php
    $writer = auth()->user();
    $writerName = trim($writer->firstname.' '.$writer->lastname) ?: $writer->email;
    $writerAvatar = $writer->avatar
        ? asset('storage/'.$writer->avatar)
        : asset('assets/images/avatar/01.jpg');
    $notifications = $writer->unreadNotifications()->latest()->take(5)->get();
@endphp

<section class="writer-workspace">
    <div class="container">
        <div class="writer-workspace-shell">
            <header class="writer-workspace-topbar">
                <div class="writer-workspace-identity">
                    <img src="{{ $writerAvatar }}" alt="">
                    <div>
                        <span><i class="bi bi-pen-fill"></i> Espace auteur</span>
                        <h2>{{ $writerName }}</h2>
                        <p>Gérez vos œuvres et suivez leur performance.</p>
                    </div>
                </div>

                <div class="writer-workspace-actions">
                    <div class="dropdown">
                        <button type="button"
                                class="writer-workspace-notification"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                aria-label="Notifications">
                            <i class="bi bi-bell"></i>
                            @if($notifications->isNotEmpty())
                                <span>{{ $notifications->count() }}</span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-end writer-workspace-notification-menu">
                            <div class="writer-workspace-notification-header">
                                <div>
                                    <strong>Notifications</strong>
                                    <small>{{ $notifications->count() }} non lue(s)</small>
                                </div>
                                @if($notifications->isNotEmpty())
                                    <form action="{{ route('notifications.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Tout effacer</button>
                                    </form>
                                @endif
                            </div>

                            <div class="writer-workspace-notification-list">
                                @forelse($notifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? route('writer.activities') }}">
                                        <span><i class="bi bi-info-circle"></i></span>
                                        <div>
                                            <strong>{{ $notification->data['title'] ?? 'Nouvelle activité' }}</strong>
                                            <p>{{ $notification->data['message'] ?? '' }}</p>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="writer-workspace-notification-empty">
                                        <i class="bi bi-bell-slash"></i>
                                        <p>Aucune nouvelle notification</p>
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('writer.activities') }}" class="writer-workspace-notification-footer">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('writer.books.create') }}" class="writer-workspace-create">
                        <i class="bi bi-plus-lg"></i>
                        <span>Ajouter un livre</span>
                    </a>

                    <button type="button"
                            class="writer-workspace-toggle d-xl-none"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#writerWorkspaceMenu"
                            aria-controls="writerWorkspaceMenu"
                            aria-label="Ouvrir la navigation">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </header>

            <div class="offcanvas-xl offcanvas-end writer-workspace-offcanvas"
                 tabindex="-1"
                 id="writerWorkspaceMenu"
                 aria-labelledby="writerWorkspaceMenuLabel">
                <div class="offcanvas-header">
                    <div>
                        <span class="writer-workspace-mobile-label">Espace auteur</span>
                        <h5 id="writerWorkspaceMenuLabel">Navigation</h5>
                    </div>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            data-bs-target="#writerWorkspaceMenu"
                            aria-label="Fermer"></button>
                </div>

                <div class="offcanvas-body">
                    <nav class="writer-workspace-nav" aria-label="Navigation de l’espace auteur">
                        <a href="{{ route('writer.dashboard') }}"
                           class="{{ request()->routeIs('writer.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Vue d’ensemble</span>
                        </a>

                        <a href="{{ route('writer.books') }}"
                           class="{{ request()->routeIs('writer.books', 'writer.books.*') ? 'active' : '' }}">
                            <i class="bi bi-book-half"></i>
                            <span>Mes livres</span>
                        </a>

                        <a href="{{ route('writer.revenues') }}"
                           class="{{ request()->routeIs('writer.revenues') ? 'active' : '' }}">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Revenus</span>
                        </a>

                        <a href="{{ route('writer.reviews') }}"
                           class="{{ request()->routeIs('writer.reviews') ? 'active' : '' }}">
                            <i class="bi bi-chat-square-quote-fill"></i>
                            <span>Avis lecteurs</span>
                        </a>

                        <a href="{{ route('writer.activities') }}"
                           class="{{ request()->routeIs('writer.activities*') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                            @if($notifications->isNotEmpty())
                                <small>{{ $notifications->count() }}</small>
                            @endif
                        </a>

                        <a href="{{ route('writer.settings') }}"
                           class="{{ request()->routeIs('writer.settings') ? 'active' : '' }}">
                            <i class="bi bi-sliders"></i>
                            <span>Paramètres</span>
                        </a>
                    </nav>

                    <a href="{{ route('writer.books.create') }}" class="writer-workspace-mobile-create d-xl-none">
                        <i class="bi bi-plus-circle"></i> Ajouter un nouveau livre
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
