@php
    $admin = auth()->user();
    $notifications = $admin->unreadNotifications()->latest()->take(5)->get();
    $adminAvatar = $admin->avatar
        ? asset('storage/'.$admin->avatar)
        : asset('assets/images/avatar/01.jpg');
    $adminName = trim($admin->firstname.' '.$admin->lastname) ?: $admin->email;
@endphp

<header class="kama-admin-header">
    <div class="kama-admin-header-left">
        <button type="button"
                class="kama-admin-menu-toggle d-xl-none"
                id="adminMenuToggle"
                aria-controls="adminSidebar"
                aria-expanded="false"
                aria-label="Ouvrir le menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <span class="kama-admin-eyebrow">Centre d’administration</span>
            <h1>@yield('page-title', 'Tableau de bord')</h1>
        </div>
    </div>

    <div class="kama-admin-header-actions">
        <a href="{{ route('home') }}"
           class="kama-admin-site-link d-none d-md-inline-flex"
           target="_blank"
           rel="noopener">
            <i class="bi bi-box-arrow-up-right"></i>
            Voir le site
        </a>

        <div class="dropdown">
            <button class="kama-admin-icon-btn position-relative"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Notifications">
                <i class="bi bi-bell"></i>
                @if($notifications->isNotEmpty())
                    <span class="kama-admin-notification-dot"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end kama-admin-notifications">
                <div class="kama-admin-dropdown-title">
                    <div>
                        <strong>Notifications</strong>
                        <small>{{ $notifications->count() }} non lue(s)</small>
                    </div>
                    @if($notifications->isNotEmpty())
                        <form method="POST" action="{{ route('notifications.clear') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Tout effacer</button>
                        </form>
                    @endif
                </div>
                <div class="kama-admin-notification-list">
                    @forelse($notifications as $notification)
                        <a href="{{ $notification->data['url'] ?? route('admin.dashboard') }}"
                           class="kama-admin-notification-item">
                            <span><i class="bi bi-info-circle"></i></span>
                            <div>
                                <strong>{{ $notification->data['title'] ?? 'Nouvelle activité' }}</strong>
                                <p>{{ $notification->data['message'] ?? '' }}</p>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    @empty
                        <div class="kama-admin-notification-empty">
                            <i class="bi bi-bell-slash"></i>
                            <span>Aucune nouvelle notification</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="dropdown">
            <button class="kama-admin-profile-btn"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <img src="{{ $adminAvatar }}" alt="">
                <span class="d-none d-sm-flex">
                    <strong>{{ $adminName }}</strong>
                    <small>Administrateur</small>
                </span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end kama-admin-profile-menu">
                <div class="kama-admin-profile-summary">
                    <img src="{{ $adminAvatar }}" alt="">
                    <div>
                        <strong>{{ $adminName }}</strong>
                        <small>{{ $admin->email }}</small>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.settings') }}">
                    <i class="bi bi-gear"></i> Paramètres du compte
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-left"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
