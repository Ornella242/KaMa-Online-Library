<aside class="kama-admin-sidebar" id="adminSidebar">
    <div class="kama-admin-brand">
        <a href="{{ route('admin.dashboard') }}" aria-label="Tableau de bord KaMa">
            <img src="{{ asset('assets/images/logo-light.svg') }}" alt="KaMa">
        </a>
        <button type="button"
                class="kama-admin-sidebar-close d-xl-none"
                data-bs-dismiss="offcanvas"
                aria-label="Fermer le menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="kama-admin-identity">
        <span class="kama-admin-identity-icon">
            <i class="bi bi-shield-check"></i>
        </span>
        <div>
            <strong>Administration</strong>
            <small>Centre de contrôle KaMa</small>
        </div>
    </div>

    <nav class="kama-admin-nav" aria-label="Navigation d’administration">
        <span class="kama-admin-nav-label">Vue d’ensemble</span>
        <a href="{{ route('admin.dashboard') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Tableau de bord</span>
        </a>

        <span class="kama-admin-nav-label">Catalogue</span>
        <a href="{{ route('admin.books.all') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.books.all', 'admin.books.show') ? 'active' : '' }}">
            <i class="bi bi-book-half"></i>
            <span>Tous les livres</span>
        </a>
        <a href="{{ route('admin.books.editorial.queue') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.books.editorial.*', 'admin.books.review') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check-fill"></i>
            <span>File éditoriale</span>
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i>
            <span>Catégories</span>
        </a>
        <a href="{{ route('admin.books.create') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.books.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square-fill"></i>
            <span>Ajouter un livre</span>
        </a>

        <span class="kama-admin-nav-label">Communauté</span>
        <a href="{{ route('admin.users') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.users*', 'admin.show') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Utilisateurs</span>
        </a>

        <span class="kama-admin-nav-label">Activité commerciale</span>
        <span class="kama-admin-nav-link disabled" aria-disabled="true">
            <i class="bi bi-credit-card-fill"></i>
            <span>Paiements</span>
            <small>Bientôt</small>
        </span>
        <span class="kama-admin-nav-link disabled" aria-disabled="true">
            <i class="bi bi-cash-stack"></i>
            <span>Retraits</span>
            <small>Bientôt</small>
        </span>

        <span class="kama-admin-nav-label">Système</span>
        <a href="{{ route('admin.settings') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i>
            <span>Paramètres</span>
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="kama-admin-logout">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-left"></i>
            <span>Déconnexion</span>
        </button>
    </form>
</aside>
