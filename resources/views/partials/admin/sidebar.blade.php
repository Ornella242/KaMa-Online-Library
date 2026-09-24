<aside class="kama-admin-sidebar" id="adminSidebar">

    {{-- BRAND --}}
    <div class="kama-admin-brand">

        <a href="{{ route('admin.dashboard') }}"
           aria-label="Tableau de bord KaMa">

            <img src="{{ asset('assets/images/KaMa2.png') }}"
                 class="h-100"
                 alt="KaMa">

        </a>

        <button type="button"
                class="kama-admin-sidebar-close d-xl-none"
                data-bs-dismiss="offcanvas"
                aria-label="Fermer le menu">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    {{-- IDENTITY --}}
    <div class="kama-admin-identity">

        <span class="kama-admin-identity-icon">
            <i class="bi bi-shield-check"></i>
        </span>

        <div>
            <strong>Administration</strong>
            <small>Centre de contrôle KaMa</small>
        </div>

    </div>


    {{-- NAVIGATION--}}
    <nav class="kama-admin-nav"
         aria-label="Navigation d’administration">


        {{-- VUE D'ENSEMBLE--}}

        @if(auth()->user()->hasAdminPermission('dashboard.view'))

            <span class="kama-admin-nav-label">
                Vue d’ensemble
            </span>

            <a href="{{ route('admin.dashboard') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid-1x2-fill"></i>

                <span>Tableau de bord</span>

            </a>

        @endif


        {{-- CATALOGUE--}}

        @if(
            auth()->user()->hasAdminPermission('books.view') ||
            auth()->user()->hasAdminPermission('editorial.view') ||
            auth()->user()->hasAdminPermission('categories.view') ||
            auth()->user()->hasAdminPermission('sponsorship_plans.view') ||
            auth()->user()->hasAdminPermission('sponsorships.view')
        )

            <span class="kama-admin-nav-label">
                Catalogue
            </span>

        @endif


        {{-- Tous les livres --}}
        @if(auth()->user()->hasAdminPermission('books.view'))

            <a href="{{ route('admin.books.all') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.books.all', 'admin.books.show') ? 'active' : '' }}">

                <i class="bi bi-book-half"></i>

                <span>Tous les livres</span>

            </a>

        @endif


        {{-- File éditoriale --}}
        @if(auth()->user()->hasAdminPermission('editorial.view'))

            <a href="{{ route('admin.books.editorial.queue') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.books.editorial.*', 'admin.books.review') ? 'active' : '' }}">

                <i class="bi bi-clipboard2-check-fill"></i>

                <span>File éditoriale</span>

            </a>

        @endif


        {{-- Catégories --}}
        @if(auth()->user()->hasAdminPermission('categories.view'))

            <a href="{{ route('admin.categories.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                <i class="bi bi-tags-fill"></i>

                <span>Catégories</span>

            </a>

        @endif


        {{-- Formules sponsoring --}}
        @if(auth()->user()->hasAdminPermission('sponsorship_plans.view'))

            <a href="{{ route('admin.sponsorship-plans.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.sponsorship-plans.*') ? 'active' : '' }}">

                <i class="bi bi-stars"></i>

                <span>Formules sponsoring</span>

            </a>

        @endif


        {{-- Demandes sponsoring --}}
        @if(auth()->user()->hasAdminPermission('sponsorships.view'))

            <a href="{{ route('admin.sponsorships.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.sponsorships.*') ? 'active' : '' }}">

                <i class="bi bi-megaphone-fill"></i>

                <span>Demandes sponsoring</span>

            </a>

        @endif


        {{--  MON ESPACE AUTEUR --}}

        @if(
            auth()->user()->hasAdminPermission('author_books.view') ||
            auth()->user()->hasAdminPermission('author_books.create') ||
            auth()->user()->hasAdminPermission('author_reviews.view') ||
            auth()->user()->hasAdminPermission('author_wallet.view') ||
            auth()->user()->hasAdminPermission('author_revenues.view') ||
            auth()->user()->hasAdminPermission('notifications.view')
        )

            <span class="kama-admin-nav-label">
                Mon espace auteur
            </span>

        @endif


        {{-- Mes livres --}}
        @if(auth()->user()->hasAdminPermission('author_books.view'))

            <a href="{{ route('admin.books.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.books.index', 'admin.books.edit', 'admin.books.deposit') ? 'active' : '' }}">

                <i class="bi bi-journal-bookmark-fill"></i>

                <span>Mes livres</span>

            </a>

        @endif


        {{-- Ajouter un livre --}}
        @if(auth()->user()->hasAdminPermission('author_books.create'))

            <a href="{{ route('admin.books.create') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.books.create') ? 'active' : '' }}">

                <i class="bi bi-plus-square-fill"></i>

                <span>Ajouter un livre</span>

            </a>

        @endif


        {{-- Avis lecteurs --}}
        @if(auth()->user()->hasAdminPermission('author_reviews.view'))

            <a href="{{ route('admin.author.reviews') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.author.reviews') ? 'active' : '' }}">

                <i class="bi bi-chat-square-quote-fill"></i>

                <span>Avis lecteurs</span>

            </a>

        @endif


        {{-- Mon portefeuille --}}
        @if(auth()->user()->hasAdminPermission('author_wallet.view'))

            <a href="{{ route('admin.author.wallet') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.author.wallet', 'admin.author.withdrawals.*') ? 'active' : '' }}">

                <i class="bi bi-wallet2"></i>

                <span>Mon portefeuille</span>

            </a>

        @endif


        {{-- Revenus --}}
        @if(auth()->user()->hasAdminPermission('author_revenues.view'))

            <a href="{{ route('admin.author.revenues') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.author.revenues') ? 'active' : '' }}">

                <i class="bi bi-graph-up-arrow"></i>

                <span>Revenus</span>

            </a>

        @endif


        {{-- Notifications --}}
        @if(auth()->user()->hasAdminPermission('notifications.view'))

            <a href="{{ route('admin.author.activities') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.author.activities*') ? 'active' : '' }}">

                <i class="bi bi-bell-fill"></i>

                <span>Notifications</span>

            </a>

        @endif


        {{-- COMMUNAUTÉ --}}

        @if(auth()->user()->hasAdminPermission('users.view'))

            <span class="kama-admin-nav-label">
                Communauté
            </span>

            <a href="{{ route('admin.users') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.users*', 'admin.show') ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>

                <span>Utilisateurs</span>

            </a>

        @endif


        {{-- ACTIVITÉ COMMERCIALE --}}

        @if(
            auth()->user()->hasAdminPermission('platform_wallet.view') ||
            auth()->user()->hasAdminPermission('withdrawals.view')
        )

            <span class="kama-admin-nav-label">
                Activité commerciale
            </span>

        @endif


        {{-- Portefeuille KaMa --}}
        @if(auth()->user()->hasAdminPermission('platform_wallet.view'))

            <a href="{{ route('admin.platform-wallet') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.platform-wallet') ? 'active' : '' }}">

                <i class="bi bi-safe2-fill"></i>

                <span>Portefeuille KaMa</span>

            </a>

        @endif


        {{-- Retraits --}}
        @if(auth()->user()->hasAdminPermission('withdrawals.view'))

            <a href="{{ route('admin.withdrawals.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">

                <i class="bi bi-cash-stack"></i>

                <span>Retraits</span>

            </a>

        @endif


        {{-- SYSTÈME --}}

        @if(
            auth()->user()->hasAdminPermission('settings.commerce.view') ||
            auth()->user()->hasAdminPermission('settings.mobile_money.view') ||
            auth()->user()->hasAdminPermission('settings.profile.view') ||
            auth()->user()->hasAdminPermission('settings.security.view') ||
            auth()->user()->hasAdminPermission('roles.view')
        )

            <span class="kama-admin-nav-label">
                Système
            </span>

        @endif


        {{-- Paramètres --}}
        @if(
            auth()->user()->hasAdminPermission('settings.commerce.view') ||
            auth()->user()->hasAdminPermission('settings.mobile_money.view') ||
            auth()->user()->hasAdminPermission('settings.profile.view') ||
            auth()->user()->hasAdminPermission('settings.security.view')
        )

            <a href="{{ route('admin.settings') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">

                <i class="bi bi-gear-fill"></i>

                <span>Paramètres</span>

            </a>

        @endif


        {{-- Rôles & permissions --}}
        @if(auth()->user()->hasAdminPermission('roles.view'))

            <a href="{{ route('admin.roles.index') }}"
               class="kama-admin-nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">

                <i class="bi bi-shield-lock-fill"></i>

                <span>Rôles & permissions</span>

            </a>

        @endif

    </nav>

    <form method="POST"
          action="{{ route('logout') }}"
          class="kama-admin-logout">

        @csrf

        <button type="submit">

            <i class="bi bi-box-arrow-left"></i>

            <span>Déconnexion</span>

        </button>

    </form>

</aside>