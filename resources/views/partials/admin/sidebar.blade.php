<aside class="kama-admin-sidebar" id="adminSidebar">
    <div class="kama-admin-brand">
        <a href="{{ route('admin.dashboard') }}" aria-label="Tableau de bord KaMa">
            <img src="{{ asset('assets/images/KaMa2.png') }}" class="h-100" alt="KaMa">
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
        <a href="{{ route('admin.sponsorship-plans.index') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.sponsorship-plans.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i>
            <span>Formules sponsoring</span>
        </a>
        <a href="{{ route('admin.sponsorships.index') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.sponsorships.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i>
            <span>Demandes sponsoring</span>
        </a>

        <span class="kama-admin-nav-label">Mon espace auteur</span>
        <a href="{{ route('admin.books.index') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.books.index', 'admin.books.edit', 'admin.books.deposit') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>Mes livres</span>
        </a>
        <a href="{{ route('admin.books.create') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.books.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square-fill"></i>
            <span>Ajouter un livre</span>
        </a>
        <a href="{{ route('admin.author.reviews') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.author.reviews') ? 'active' : '' }}">
            <i class="bi bi-chat-square-quote-fill"></i>
            <span>Avis lecteurs</span>
        </a>
        <a href="{{ route('admin.author.wallet') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.author.wallet', 'admin.author.withdrawals.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Mon portefeuille</span>
        </a>
        <a href="{{ route('admin.author.revenues') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.author.revenues') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Revenus</span>
        </a>
        <a href="{{ route('admin.author.activities') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.author.activities*') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i>
            <span>Notifications</span>
        </a>

        <span class="kama-admin-nav-label">Communauté</span>
        <a href="{{ route('admin.users') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.users*', 'admin.show') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Utilisateurs</span>
        </a>

        <span class="kama-admin-nav-label">Activité commerciale</span>
        <a href="{{ route('admin.platform-wallet') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.platform-wallet') ? 'active' : '' }}">
            <i class="bi bi-safe2-fill"></i>
            <span>Portefeuille KaMa</span>
        </a>
        <a href="{{ route('admin.withdrawals.index') }}"
           class="kama-admin-nav-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Retraits</span>
        </a>

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
