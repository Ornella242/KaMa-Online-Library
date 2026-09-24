@extends('layouts.admin')

@section('title', 'Rôles & permissions')
@section('page-title', 'Rôles & permissions')

@section('admin-content')

    @php
        $systemRoles = ['admin'];

        $totalRoles = $roles->count();

        $totalPermissions = $permissions->count();

        $customRoles = $roles->filter(function ($role) use ($systemRoles) {
            return !in_array($role->name, $systemRoles);
        });

        $totalCustomRoleUsers = $customRoles->sum('admin_users_count');
    @endphp

    <div class="roles-page">

        {{-- ==========================================
             HEADER
        =========================================== --}}
        <header class="roles-heading">
            <div>
                <span>Système</span>

                <h2>Gestion des rôles & permissions</h2>

                <p>
                    Gérez les rôles administratifs et contrôlez les accès aux différentes fonctionnalités de KaMa.
                </p>
            </div>

            @if(auth()->user()->hasAdminPermission('roles.create'))
                <button type="button"
                        class="roles-add js-create-role">
                    <i class="bi bi-shield-plus"></i>
                    Créer un rôle
                </button>
            @endif
        </header>


        {{-- ==========================================
             STATISTIQUES
        =========================================== --}}
        <section class="roles-stats">

            <article>
                <span class="total">
                    <i class="bi bi-shield-lock-fill"></i>
                </span>

                <div>
                    <small>Rôles administratifs</small>
                    <strong>{{ number_format($totalRoles) }}</strong>
                </div>
            </article>

            <article>
                <span class="permissions">
                    <i class="bi bi-key-fill"></i>
                </span>

                <div>
                    <small>Permissions disponibles</small>
                    <strong>{{ number_format($totalPermissions) }}</strong>
                </div>
            </article>

            <article>
                <span class="custom">
                    <i class="bi bi-person-gear"></i>
                </span>

                <div>
                    <small>Rôles personnalisés</small>
                    <strong>{{ number_format($customRoles->count()) }}</strong>
                </div>
            </article>

            <article>
                <span class="users">
                    <i class="bi bi-people-fill"></i>
                </span>

                <div>
                    <small>Utilisateurs assignés</small>
                    <strong>{{ number_format($totalCustomRoleUsers) }}</strong>
                </div>
            </article>

        </section>


        {{-- ==========================================
             PANEL
        =========================================== --}}
        <div class="roles-panel">

            <header class="roles-panel-header">

                <div>
                    <strong>{{ number_format($roles->count()) }} rôle(s)</strong>

                    <span>
                        Rôles disponibles pour la gestion des accès administratifs
                    </span>
                </div>

                <div class="roles-panel-info">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Les rôles Lecteur et Écrivain sont gérés séparément.
                    </span>
                </div>

            </header>


            @if($roles->isEmpty())

                <div class="roles-empty">

                    <span>
                        <i class="bi bi-shield-x"></i>
                    </span>

                    <h3>Aucun rôle administratif</h3>

                    <p>
                        Aucun rôle n'est actuellement disponible.
                    </p>

                    <a href="{{ route('admin.roles.create') }}">
                        Créer le premier rôle
                    </a>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table roles-table align-middle">

                        <thead>
                            <tr>
                                <th>Rôle</th>
                                <th>Description</th>
                                <th>Permissions</th>
                                <th>Utilisateurs</th>
                                <th>Type</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($roles as $role)

                                @php
                                    $isSystemRole = $role->name === 'admin';

                                    $roleLabel = match ($role->name) {
                                        'admin' => 'Administrateur',
                                        default => $role->label,
                                    };

                                    $permissionCount = $role->permissions_count ?? $role->permissions()->count();
                                @endphp

                                <tr>

                                    {{-- RÔLE --}}
                                    <td>

                                        <div class="roles-name-cell">

                                            <span class="roles-avatar {{ $isSystemRole ? 'system' : 'custom' }}">

                                                <i class="bi {{ $isSystemRole ? 'bi-shield-lock-fill' : 'bi-person-gear' }}"></i>

                                            </span>

                                            <div>

                                                <strong>{{ $roleLabel }}</strong>

                                                <small>
                                                    {{ $isSystemRole ? 'Rôle principal de la plateforme' : 'Rôle personnalisé' }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- DESCRIPTION --}}
                                    <td>

                                        <div class="roles-description-cell">

                                            <span>
                                                {{ $role->description ?: 'Aucune description renseignée.' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- PERMISSIONS --}}
                                    <td>

                                        <span class="roles-count-badge permissions">

                                            <i class="bi bi-key-fill"></i>

                                            {{ number_format($permissionCount) }}

                                            <small>
                                                {{ $permissionCount > 1 ? 'permissions' : 'permission' }}
                                            </small>

                                        </span>

                                    </td>


                                    {{-- UTILISATEURS --}}
                                    <td>

                                        <span class="roles-count-badge users">

                                            <i class="bi bi-people-fill"></i>

                                            {{ number_format($role->admin_users_count) }}

                                            <small>
                                                {{ $role->admin_users_count > 1 ? 'utilisateurs' : 'utilisateur' }}
                                            </small>

                                        </span>

                                    </td>


                                    {{-- TYPE --}}
                                    <td>

                                        @if($isSystemRole)

                                            <span class="roles-type-badge system">
                                                <i class="bi bi-lock-fill"></i>
                                                Système
                                            </span>

                                        @else

                                            <span class="roles-type-badge custom">
                                                <i class="bi bi-sliders"></i>
                                                Personnalisé
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}
                                    <td>

                                        <div class="roles-row-actions">

                                            @if(!$isSystemRole)

                                                @if(auth()->user()->hasAdminPermission('roles.edit'))

                                                    <button type="button"
                                                            class="edit js-edit-role"
                                                            data-role-id="{{ $role->id }}"
                                                            data-role-label="{{ $role->label }}"
                                                            data-role-description="{{ $role->description }}"
                                                            data-role-permissions="{{ $role->permissions->pluck('id')->implode(',') }}"
                                                            title="Modifier le rôle">

                                                        <i class="bi bi-pencil"></i>

                                                    </button>

                                                @endif


                                                @if(auth()->user()->hasAdminPermission('roles.delete'))

                                                    <button type="button"
                                                            class="delete js-role-delete"
                                                            data-role-id="{{ $role->id }}"
                                                            data-role-name="{{ $roleLabel }}"
                                                            title="Supprimer">

                                                        <i class="bi bi-trash"></i>

                                                    </button>

                                                @endif

                                            @else

                                                <span class="roles-locked"
                                                    title="Rôle système protégé">

                                                    <i class="bi bi-lock-fill"></i>

                                                </span>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

        {{-- ==========================================
            CREATE ROLE MODAL
        =========================================== --}}

        <div class="roles-overlay d-none"
            id="createRoleOverlay"
            role="dialog"
            aria-modal="true"
            aria-hidden="true">

            <div class="roles-overlay-container roles-create-container">

                {{-- HEADER --}}
                <header class="roles-overlay-header">

                    <div>

                        <span>Système</span>

                        <h2>
                            Créer un rôle administratif
                        </h2>

                    </div>

                    <button type="button"
                            class="js-create-role-close"
                            aria-label="Fermer">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </header>


                {{-- FORM --}}
                <form method="POST"
                    action="{{ route('admin.roles.store') }}"
                    id="createRoleForm">

                    @csrf

                    <div class="roles-create-body">

                        {{-- INFORMATIONS --}}
                        <section class="roles-form-section">

                            <div class="roles-form-section-heading">

                                <div>
                                    <strong>Informations du rôle</strong>

                                    <span>
                                        Définissez l'identité du rôle administratif.
                                    </span>
                                </div>

                            </div>


                        <div class="roles-form-grid">

                            {{-- NOM DU RÔLE --}}
                            <div class="roles-form-field">

                                <label for="create_role_label">
                                    Nom du rôle
                                    <span>*</span>
                                </label>

                                <input type="text"
                                    id="create_role_label"
                                    name="label"
                                    value="{{ old('label') }}"
                                    placeholder="Ex : Assistant livres KaMa"
                                    maxlength="100"
                                    required>

                                <small>
                                    Ce nom sera affiché dans l'administration de KaMa.
                                </small>

                                @error('label')
                                    <div class="roles-form-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="roles-form-field">

                                <label for="create_role_description">
                                    Description
                                </label>

                                <input type="text"
                                    id="create_role_description"
                                    name="description"
                                    value="{{ old('description') }}"
                                    placeholder="Ex : Gestion du catalogue KaMa"
                                    maxlength="255">

                                <small>
                                    Décrivez brièvement les responsabilités de ce rôle.
                                </small>

                                @error('description')
                                    <div class="roles-form-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        </section>


                        {{-- PERMISSIONS --}}
                        <section class="roles-form-section">

                            <div class="roles-form-section-heading">

                                <div>

                                    <strong>Permissions</strong>

                                    <span>
                                        Sélectionnez les actions accessibles à ce rôle.
                                    </span>

                                </div>


                                <div class="roles-permission-actions">

                                    <button type="button"
                                            class="js-select-all-permissions">
                                        Tout sélectionner
                                    </button>

                                    <button type="button"
                                            class="js-clear-all-permissions">
                                        Tout retirer
                                    </button>

                                </div>

                            </div>


                            @if($permissionGroups->isNotEmpty())

                                <div class="roles-permissions-grid">

                                    @foreach($permissionGroups as $group => $groupPermissions)

                                        @php

                                            $groupLabels = [
                                                'dashboard' => 'Tableau de bord',
                                                'books' => 'Catalogue — Livres',
                                                'editorial' => 'File éditoriale',
                                                'categories' => 'Catégories',
                                                'sponsorship_plans' => 'Formules sponsoring',
                                                'sponsorships' => 'Demandes sponsoring',

                                                'author_books' => 'Espace administrateur auteur — Livres',
                                                'author_reviews' => 'Espace administrateur auteur — Avis',
                                                'author_wallet' => 'Espace administrateur auteur — Portefeuille',
                                                'author_revenues' => 'Espace administrateur auteur — Revenus',

                                                'notifications' => 'Notifications',

                                                'users' => 'Utilisateurs',

                                                'platform_wallet' => 'Portefeuille KaMa',

                                                'withdrawals' => 'Retraits',

                                                'settings.commerce' => 'Paramètres — Commerce',
                                                'settings.mobile_money' => 'Paramètres — Mobile Money',
                                                'settings.profile' => 'Paramètres — Profil',
                                                'settings.security' => 'Paramètres — Sécurité',

                                                'roles' => 'Rôles & permissions',
                                            ];

                                            $groupLabel = $groupLabels[$group] ?? ucfirst(str_replace('_', ' ', $group));

                                        @endphp


                                        <div class="roles-permission-group">

                                            <div class="roles-permission-group-header">

                                                <strong>
                                                    {{ $groupLabel }}
                                                </strong>

                                                <span>
                                                    {{ $groupPermissions->count() }}
                                                </span>

                                            </div>


                                            <div class="roles-permission-list">

                                                @foreach($groupPermissions as $permission)

                                                    <label class="roles-permission-item">

                                                        <input type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            class="role-permission-checkbox"
                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>

                                                        <span class="roles-permission-check">
                                                            <i class="bi bi-check"></i>
                                                        </span>

                                                        <span class="roles-permission-content">

                                                            <strong>
                                                                {{ $permission->label }}
                                                            </strong>

                                                            @if($permission->description)

                                                                <small>
                                                                    {{ $permission->description }}
                                                                </small>

                                                            @endif

                                                        </span>

                                                    </label>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="roles-no-permissions">

                                    <i class="bi bi-key"></i>

                                    <strong>
                                        Aucune permission disponible
                                    </strong>

                                    <span>
                                        Les permissions doivent être configurées avant de créer un rôle.
                                    </span>

                                </div>

                            @endif


                            @error('permissions')
                                <div class="roles-form-error roles-form-error-global">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            @error('permissions.*')
                                <div class="roles-form-error roles-form-error-global">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                        </section>

                    </div>


                    {{-- FOOTER --}}
                    <footer class="roles-overlay-footer">

                        <button type="button"
                                class="secondary js-create-role-close">

                            Annuler

                        </button>


                        <button type="submit"
                                class="primary">

                            <i class="bi bi-shield-plus"></i>

                            Créer le rôle

                        </button>

                    </footer>

                </form>

            </div>

        </div>

        {{-- ==========================================
            EDIT ROLE MODAL
        =========================================== --}}

        <div class="roles-overlay d-none"
            id="editRoleOverlay"
            role="dialog"
            aria-modal="true"
            aria-hidden="true">

            <div class="roles-overlay-container roles-create-container">

                {{-- HEADER --}}
                <header class="roles-overlay-header">

                    <div>

                        <span>Système</span>

                        <h2>
                            Modifier le rôle administratif
                        </h2>

                    </div>

                    <button type="button"
                            class="js-edit-role-close"
                            aria-label="Fermer">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </header>


                {{-- FORM --}}
                <form method="POST"
                    id="editRoleForm">

                    @csrf

                    @method('PUT')


                    <div class="roles-create-body">

                        {{-- INFORMATIONS --}}
                        <section class="roles-form-section">

                            <div class="roles-form-section-heading">

                                <div>

                                    <strong>Informations du rôle</strong>

                                    <span>
                                        Modifiez le nom et la description du rôle.
                                    </span>

                                </div>

                            </div>


                            <div class="roles-form-grid">

                                {{-- NOM --}}
                                <div class="roles-form-field">

                                    <label for="edit_role_label">
                                        Nom du rôle
                                        <span>*</span>
                                    </label>

                                    <input type="text"
                                        id="edit_role_label"
                                        name="label"
                                        maxlength="100"
                                        required>

                                    <small>
                                        Ce nom sera affiché dans l'administration de KaMa.
                                    </small>

                                </div>


                                {{-- DESCRIPTION --}}
                                <div class="roles-form-field">

                                    <label for="edit_role_description">
                                        Description
                                    </label>

                                    <input type="text"
                                        id="edit_role_description"
                                        name="description"
                                        maxlength="255">

                                    <small>
                                        Décrivez brièvement les responsabilités de ce rôle.
                                    </small>

                                </div>

                            </div>

                        </section>


                        {{-- PERMISSIONS --}}
                        <section class="roles-form-section">

                            <div class="roles-form-section-heading">

                                <div>

                                    <strong>Permissions</strong>

                                    <span>
                                        Définissez les actions accessibles à ce rôle.
                                    </span>

                                </div>


                                <div class="roles-permission-actions">

                                    <button type="button"
                                            class="js-edit-select-all-permissions">

                                        Tout sélectionner

                                    </button>

                                    <button type="button"
                                            class="js-edit-clear-all-permissions">

                                        Tout retirer

                                    </button>

                                </div>

                            </div>


                            @if($permissionGroups->isNotEmpty())

                                <div class="roles-permissions-grid">

                                    @foreach($permissionGroups as $group => $groupPermissions)

                                        @php

                                            $groupLabels = [
                                                'dashboard' => 'Tableau de bord',
                                                'books' => 'Catalogue — Livres',
                                                'editorial' => 'File éditoriale',
                                                'categories' => 'Catégories',
                                                'sponsorship_plans' => 'Formules sponsoring',
                                                'sponsorships' => 'Demandes sponsoring',

                                                'author_books' => 'Mon espace auteur — Livres',
                                                'author_reviews' => 'Mon espace auteur — Avis',
                                                'author_wallet' => 'Mon espace auteur — Portefeuille',
                                                'author_revenues' => 'Mon espace auteur — Revenus',

                                                'notifications' => 'Notifications',

                                                'users' => 'Utilisateurs',

                                                'platform_wallet' => 'Portefeuille KaMa',

                                                'withdrawals' => 'Retraits',

                                                'settings.commerce' => 'Paramètres — Commerce',
                                                'settings.mobile_money' => 'Paramètres — Mobile Money',
                                                'settings.profile' => 'Paramètres — Profil',
                                                'settings.security' => 'Paramètres — Sécurité',

                                                'roles' => 'Rôles & permissions',
                                            ];

                                            $groupLabel = $groupLabels[$group]
                                                ?? ucfirst(str_replace('_', ' ', $group));

                                        @endphp


                                        <div class="roles-permission-group">

                                            <div class="roles-permission-group-header">

                                                <strong>
                                                    {{ $groupLabel }}
                                                </strong>

                                                <span>
                                                    {{ $groupPermissions->count() }}
                                                </span>

                                            </div>


                                            <div class="roles-permission-list">

                                                @foreach($groupPermissions as $permission)

                                                    <label class="roles-permission-item">

                                                        <input type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            class="edit-role-permission-checkbox">

                                                        <span class="roles-permission-check">

                                                            <i class="bi bi-check"></i>

                                                        </span>

                                                        <span class="roles-permission-content">

                                                            <strong>
                                                                {{ $permission->label }}
                                                            </strong>

                                                            @if($permission->description)

                                                                <small>
                                                                    {{ $permission->description }}
                                                                </small>

                                                            @endif

                                                        </span>

                                                    </label>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                        </section>

                    </div>


                    {{-- FOOTER --}}
                    <footer class="roles-overlay-footer">

                        <button type="button"
                                class="secondary js-edit-role-close">

                            Annuler

                        </button>


                        <button type="submit"
                                class="primary">

                            <i class="bi bi-check-lg"></i>

                            Enregistrer les modifications

                        </button>

                    </footer>

                </form>

            </div>

        </div>

        {{-- ==========================================
             DELETE MODAL
        =========================================== --}}
        <div class="roles-overlay d-none"
             id="deleteRoleOverlay"
             role="dialog"
             aria-modal="true"
             aria-hidden="true">

            <div class="roles-overlay-container">

                <header class="roles-overlay-header danger">

                    <div>
                        <span>Action irréversible</span>

                        <h2>
                            Supprimer ce rôle ?
                        </h2>
                    </div>

                    <button type="button"
                            class="js-role-close"
                            aria-label="Fermer">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </header>


                <div class="roles-confirm-body">

                    <span class="danger">
                        <i class="bi bi-trash3"></i>
                    </span>

                    <h3 id="deleteRoleName">
                        Ce rôle
                    </h3>

                    <p>
                        Le rôle sera définitivement supprimé.
                        Les utilisateurs associés ne seront plus liés à ce rôle administratif.
                    </p>

                    <div class="warning">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                        <span>
                            Cette action est irréversible.
                        </span>

                    </div>

                </div>


                <footer class="roles-overlay-footer">

                    <button type="button"
                            class="secondary js-role-close">

                        Annuler

                    </button>

                    <form id="deleteRoleForm"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="danger">

                            <i class="bi bi-trash"></i>

                            Confirmer la suppression

                        </button>

                    </form>

                </footer>

            </div>

        </div>

    </div>

@endsection


@push('styles')

<style>

    /* ==========================================
       PAGE
    ========================================== */

    .roles-page {
        display: grid;
        gap: 22px;
    }


    /* ==========================================
       HEADER
    ========================================== */

    .roles-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        background: transparent;
    }

    .roles-heading > div > span {
        display: block;
        margin-bottom: 4px;
        color: #b30000;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .roles-heading h2 {
        margin: 0;
        font-size: 1.75rem;
    }

    .roles-heading p {
        margin: 5px 0 0;
        color: #777b83;
        font-size: .95rem;
    }

    .roles-add {
        display: inline-flex;
        min-height: 43px;
        flex-shrink: 0;
        align-items: center;
        gap: 8px;
        padding: 9px 15px;
        border: 0;
        border-radius: 10px;
        background: #b30000;
        color: #fff;
        font-size: .88rem;
        font-weight: 700;
        text-decoration: none;
    }

    .roles-add:hover {
        background: #8f0000;
        color: #fff;
    }


    /* ==========================================
       STATS
    ========================================== */

    .roles-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 13px;
    }

    .roles-stats article {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px;
        border: 1px solid #e6e7ea;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .035);
    }

    .roles-stats article > span {
        display: grid;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        place-items: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }

    .roles-stats .total {
        background: #fff0f0;
        color: #b30000;
    }

    .roles-stats .permissions {
        background: #f3f0ff;
        color: #5b4bb7;
    }

    .roles-stats .custom {
        background: #eaf8ef;
        color: #138443;
    }

    .roles-stats .users {
        background: #e8f5ff;
        color: #157bb5;
    }

    .roles-stats small,
    .roles-stats strong {
        display: block;
    }

    .roles-stats small {
        color: #858991;
        font-size: .78rem;
        font-weight: 700;
    }

    .roles-stats strong {
        margin-top: 2px;
        font-size: 1.4rem;
    }


    /* ==========================================
       PANEL
    ========================================== */

    .roles-panel {
        overflow: hidden;
        border: 1px solid #e5e7ea;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .035);
    }

    .roles-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 16px 19px;
        border-bottom: 1px solid #eceef0;
    }

    .roles-panel-header strong,
    .roles-panel-header span {
        display: block;
    }

    .roles-panel-header strong {
        font-size: .95rem;
    }

    .roles-panel-header > div > span {
        margin-top: 2px;
        color: #989ba2;
        font-size: .78rem;
    }

    .roles-panel-info {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        border-radius: 9px;
        background: #f7f7f8;
        color: #777b82;
        font-size: .75rem;
    }

    .roles-panel-info i {
        color: #b30000;
    }


    /* ==========================================
       TABLE
    ========================================== */

    .roles-table {
        min-width: 980px;
        margin: 0;
    }

    .roles-table thead th {
        padding: 11px 14px;
        border-color: #eceef0;
        color: #8e9299;
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .roles-table tbody td {
        padding: 14px;
        border-color: #eff0f2;
        color: #45484e;
        font-size: .86rem;
    }

    .roles-table tbody tr:hover {
        background: #fcfcfd;
    }


    /* ==========================================
       ROLE CELL
    ========================================== */

    .roles-name-cell {
        display: flex;
        min-width: 210px;
        align-items: center;
        gap: 11px;
    }

    .roles-avatar {
        display: inline-flex;
        width: 41px;
        height: 41px;
        flex: 0 0 41px;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: .95rem;
    }

    .roles-avatar.system {
        background: #f3f0ff;
        color: #5b4bb7;
    }

    .roles-avatar.custom {
        background: #fff0f0;
        color: #b30000;
    }

    .roles-name-cell strong,
    .roles-name-cell small {
        display: block;
    }

    .roles-name-cell strong {
        color: #23252a;
        font-size: .9rem;
    }

    .roles-name-cell small {
        margin-top: 2px;
        color: #989ba2;
        font-size: .73rem;
    }


    /* ==========================================
       DESCRIPTION
    ========================================== */

    .roles-description-cell {
        max-width: 300px;
    }

    .roles-description-cell span {
        display: -webkit-box;
        overflow: hidden;
        color: #73777e;
        font-size: .82rem;
        line-height: 1.5;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }


    /* ==========================================
       COUNT BADGES
    ========================================== */

    .roles-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .roles-count-badge.permissions {
        background: #f3f0ff;
        color: #5b4bb7;
    }

    .roles-count-badge.users {
        background: #e8f5ff;
        color: #157bb5;
    }

    .roles-count-badge small {
        font-size: .7rem;
        font-weight: 600;
    }


    /* ==========================================
       TYPE
    ========================================== */

    .roles-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .roles-type-badge.system {
        background: #f3f0ff;
        color: #5b4bb7;
    }

    .roles-type-badge.custom {
        background: #eaf8ef;
        color: #138443;
    }


    /* ==========================================
       ACTIONS
    ========================================== */

    .roles-row-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
    }

    .roles-row-actions a,
    .roles-row-actions button,
    .roles-locked {
        display: inline-flex;
        width: 33px;
        height: 33px;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: .85rem;
    }

    .roles-row-actions .edit {
        border-color: #e2e4e7;
        background: #fff;
        color: #3f4248;
        text-decoration: none;
    }

    .roles-row-actions .edit:hover {
        border-color: #1c1d20;
        background: #1c1d20;
        color: #fff;
    }

    .roles-row-actions .delete {
        border: 0;
        background: #fff0f0;
        color: #b30000;
    }

    .roles-row-actions .delete:hover {
        background: #b30000;
        color: #fff;
    }

    .roles-locked {
        background: #f3f3f4;
        color: #a0a3a8;
        cursor: not-allowed;
    }


    /* ==========================================
       EMPTY
    ========================================== */

    .roles-empty {
        display: grid;
        width: 100%;
        min-height: 280px;
        place-items: center;
        align-content: center;
        justify-items: center;
        padding: 40px 24px;
        text-align: center;
    }

    .roles-empty > span {
        display: grid;
        width: 60px;
        height: 60px;
        place-items: center;
        border-radius: 17px;
        background: #fff0f0;
        color: #b30000;
        font-size: 1.5rem;
    }

    .roles-empty h3 {
        margin: 14px 0 4px;
        font-size: 1.12rem;
    }

    .roles-empty p {
        margin: 0;
        color: #8c9097;
        font-size: .88rem;
    }

    .roles-empty a {
        margin-top: 12px;
        color: #b30000;
        font-size: .85rem;
        font-weight: 700;
    }


    /* ==========================================
       OVERLAY
    ========================================== */

    .roles-overlay {
        position: fixed;
        inset: 0;
        z-index: 10060;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(8, 10, 14, .72);
        backdrop-filter: blur(6px);
    }

    .roles-overlay-container {
        display: flex;
        width: min(100%, 590px);
        max-height: 94vh;
        overflow: hidden;
        flex-direction: column;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 28px 90px rgba(0, 0, 0, .36);
    }

    .roles-overlay-header {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 17px 21px;
        background: #8f0000;
        color: #fff;
    }

    .roles-overlay-header.danger {
        background: #8f0000;
    }

    .roles-overlay-header span {
        display: block;
        margin-bottom: 3px;
        color: #fca5a5;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .roles-overlay-header h2 {
        margin: 0;
        color: #fff;
        font-size: 1.15rem;
    }

    .roles-overlay-header > button {
        display: grid;
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        place-items: center;
        border: 0;
        border-radius: 9px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
    }


    /* ==========================================
       CONFIRMATION
    ========================================== */

    .roles-confirm-body {
        overflow: auto;
        padding: 29px;
        text-align: center;
    }

    .roles-confirm-body > span {
        display: grid;
        width: 65px;
        height: 65px;
        margin: 0 auto 14px;
        place-items: center;
        border-radius: 18px;
        font-size: 1.65rem;
    }

    .roles-confirm-body > span.danger {
        background: #fff0f0;
        color: #b30000;
    }

    .roles-confirm-body h3 {
        margin: 0 0 7px;
        font-size: 1.2rem;
    }

    .roles-confirm-body p {
        max-width: 440px;
        margin: 0 auto;
        color: #71757d;
        font-size: .88rem;
        line-height: 1.6;
    }

    .roles-confirm-body .warning {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 19px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #fff8ec;
        color: #8a6512;
        font-size: .8rem;
        text-align: left;
    }


    /* ==========================================
       FOOTER
    ========================================== */

    .roles-overlay-footer {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding: 14px 20px;
        border-top: 1px solid #e7e8eb;
        background: #fff;
    }

    .roles-overlay-footer button {
        display: inline-flex;
        min-height: 39px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 14px;
        border: 0;
        border-radius: 9px;
        font-size: .82rem;
        font-weight: 700;
    }

    .roles-overlay-footer .secondary {
        border: 1px solid #dedfe2;
        background: #fff;
        color: #666970;
    }

    .roles-overlay-footer .danger {
        background: #b30000;
        color: #fff;
    }

    .roles-overlay-footer .danger:hover {
        background: #8f0000;
    }

    /* ==========================================
   CREATE ROLE
========================================== */

.roles-create-container {
    width: min(100%, 920px);
}


/* ==========================================
   CREATE BODY
========================================== */

.roles-create-body {
    max-height: calc(94vh - 145px);
    overflow-y: auto;
    padding: 22px;
}


/* ==========================================
   FORM SECTION
========================================== */

.roles-form-section {
    padding: 18px;
    border: 1px solid #e7e8eb;
    border-radius: 13px;
    background: #fff;
}

.roles-form-section + .roles-form-section {
    margin-top: 15px;
}


.roles-form-section-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 17px;
}


.roles-form-section-heading strong,
.roles-form-section-heading span {
    display: block;
}


.roles-form-section-heading strong {
    color: #25272b;
    font-size: .92rem;
}


.roles-form-section-heading span {
    margin-top: 3px;
    color: #92959c;
    font-size: .76rem;
}


/* ==========================================
   FORM GRID
========================================== */

.roles-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}


.roles-form-field label {
    display: block;
    margin-bottom: 7px;
    color: #393c42;
    font-size: .78rem;
    font-weight: 700;
}


.roles-form-field label span {
    color: #b30000;
}


.roles-form-field input {
    width: 100%;
    min-height: 42px;
    padding: 9px 12px;
    border: 1px solid #dfe1e5;
    border-radius: 9px;
    outline: none;
    background: #fff;
    color: #292b30;
    font-size: .84rem;
    transition: border-color .15s ease, box-shadow .15s ease;
}


.roles-form-field input:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, .08);
}


.roles-form-field input::placeholder {
    color: #b1b4ba;
}


.roles-form-field small {
    display: block;
    margin-top: 6px;
    color: #9a9da3;
    font-size: .7rem;
    line-height: 1.4;
}


/* ==========================================
   PERMISSION ACTIONS
========================================== */

.roles-permission-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}


.roles-permission-actions button {
    padding: 6px 9px;
    border: 1px solid #dedfe2;
    border-radius: 7px;
    background: #fff;
    color: #686c73;
    font-size: .7rem;
    font-weight: 700;
}


.roles-permission-actions button:hover {
    border-color: #b30000;
    color: #b30000;
}


/* ==========================================
   PERMISSION GRID
========================================== */

.roles-permissions-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 11px;
}


.roles-permission-group {
    overflow: hidden;
    border: 1px solid #e7e8eb;
    border-radius: 11px;
    background: #fafafa;
}


.roles-permission-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border-bottom: 1px solid #e7e8eb;
    background: #f6f6f7;
}


.roles-permission-group-header strong {
    color: #3a3d42;
    font-size: .77rem;
}


.roles-permission-group-header span {
    display: inline-flex;
    min-width: 21px;
    height: 21px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff;
    color: #888c93;
    font-size: .66rem;
    font-weight: 700;
}


/* ==========================================
   PERMISSION ITEM
========================================== */

.roles-permission-list {
    padding: 4px 0;
}


.roles-permission-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 9px 11px;
    cursor: pointer;
    transition: background .15s ease;
}


.roles-permission-item:hover {
    background: #fff;
}


.roles-permission-item input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}


.roles-permission-check {
    display: grid;
    width: 17px;
    height: 17px;
    flex: 0 0 17px;
    margin-top: 1px;
    place-items: center;
    border: 1px solid #d4d6da;
    border-radius: 5px;
    background: #fff;
    color: transparent;
    font-size: .64rem;
    transition: all .15s ease;
}


.roles-permission-item input:checked + .roles-permission-check {
    border-color: #b30000;
    background: #b30000;
    color: #fff;
}


.roles-permission-content {
    min-width: 0;
}


.roles-permission-content strong,
.roles-permission-content small {
    display: block;
}


.roles-permission-content strong {
    color: #41444a;
    font-size: .74rem;
    font-weight: 700;
}


.roles-permission-content small {
    margin-top: 2px;
    color: #999ca3;
    font-size: .66rem;
    line-height: 1.35;
}


/* ==========================================
   ERRORS
========================================== */

.roles-form-error {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    margin-top: 6px;
    color: #b30000;
    font-size: .7rem;
    line-height: 1.4;
}


.roles-form-error-global {
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    background: #fff0f0;
}


/* ==========================================
   NO PERMISSIONS
========================================== */

.roles-no-permissions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 35px 20px;
    border: 1px dashed #dedfe2;
    border-radius: 10px;
    text-align: center;
}


.roles-no-permissions > i {
    margin-bottom: 9px;
    color: #b30000;
    font-size: 1.4rem;
}


.roles-no-permissions strong {
    color: #44474d;
    font-size: .82rem;
}


.roles-no-permissions span {
    margin-top: 3px;
    color: #999ca3;
    font-size: .72rem;
}


/* ==========================================
   PRIMARY BUTTON
========================================== */

.roles-overlay-footer .primary {
    background: #b30000;
    color: #fff;
}


.roles-overlay-footer .primary:hover {
    background: #8f0000;
}


/* ==========================================
   RESPONSIVE
========================================== */

@media (max-width: 800px) {

    .roles-create-container {
        width: 100%;
    }

    .roles-permissions-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 650px) {

    .roles-form-grid {
        grid-template-columns: 1fr;
    }

    .roles-form-section-heading {
        flex-direction: column;
    }

    .roles-permission-actions {
        width: 100%;
    }

}


@media (max-width: 480px) {

    .roles-create-body {
        padding: 13px;
    }

    .roles-form-section {
        padding: 13px;
    }

    .roles-overlay-footer {
        padding: 12px;
    }

}

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 1050px) {

        .roles-stats {
            grid-template-columns: repeat(2, 1fr);
        }

    }

    @media (max-width: 767px) {

        .roles-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .roles-add {
            width: 100%;
            justify-content: center;
        }

        .roles-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .roles-panel-info {
            width: 100%;
        }

        .roles-overlay {
            padding: 10px;
        }

        .roles-overlay-footer {
            flex-wrap: wrap;
        }

        .roles-overlay-footer button,
        .roles-overlay-footer form {
            flex: 1;
        }

        .roles-overlay-footer form button {
            width: 100%;
        }

    }

    @media (max-width: 520px) {

        .roles-stats {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush


@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', () => {

        const overlay = document.getElementById('deleteRoleOverlay');
        const deleteForm = document.getElementById('deleteRoleForm');
        const deleteRoleName = document.getElementById('deleteRoleName');

        if (!overlay) return;

        const closeOverlay = () => {

            overlay.classList.add('d-none');
            overlay.setAttribute('aria-hidden', 'true');

            document.body.style.overflow = '';

        };

        const openOverlay = (roleId, roleName) => {

            deleteRoleName.textContent = roleName;

            deleteForm.action = `{{ url('admin/roles') }}/${roleId}`;

            overlay.classList.remove('d-none');
            overlay.setAttribute('aria-hidden', 'false');

            document.body.style.overflow = 'hidden';

        };

        document.querySelectorAll('.js-role-delete').forEach((button) => {

            button.addEventListener('click', () => {

                openOverlay(
                    button.dataset.roleId,
                    button.dataset.roleName
                );

            });

        });

        document.querySelectorAll('.js-role-close').forEach((button) => {

            button.addEventListener('click', closeOverlay);

        });

        overlay.addEventListener('click', (event) => {

            if (event.target === overlay) {
                closeOverlay();
            }

        });

        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape' && !overlay.classList.contains('d-none')) {
                closeOverlay();
            }

        });

    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const createOverlay = document.getElementById('createRoleOverlay');

    const createButtons = document.querySelectorAll('.js-create-role');

    const closeButtons = document.querySelectorAll('.js-create-role-close');


    /*
    |--------------------------------------------------------------------------
    | OUVRIR L'OVERLAY
    |--------------------------------------------------------------------------
    */

    createButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            console.log('Bouton créer cliqué');

            if (!createOverlay) {
                console.error('createRoleOverlay introuvable');
                return;
            }

            createOverlay.classList.remove('d-none');

            createOverlay.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow = 'hidden';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FERMER L'OVERLAY
    |--------------------------------------------------------------------------
    */

    closeButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            if (!createOverlay) {
                return;
            }

            createOverlay.classList.add('d-none');

            createOverlay.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.style.overflow = '';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FERMER EN CLIQUANT EN DEHORS
    |--------------------------------------------------------------------------
    */

    if (createOverlay) {

        createOverlay.addEventListener('click', function (event) {

            if (event.target === createOverlay) {

                createOverlay.classList.add('d-none');

                createOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FERMER AVEC ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            createOverlay &&
            !createOverlay.classList.contains('d-none')
        ) {

            createOverlay.classList.add('d-none');

            createOverlay.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.style.overflow = '';

        }

    });

});
</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const createOverlay = document.getElementById('createRoleOverlay');

        /*
        |--------------------------------------------------------------------------
        | CREATE ROLE
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-create-role').forEach(function (button) {

            button.addEventListener('click', function () {

                if (!createOverlay) {
                    return;
                }

                createOverlay.classList.remove('d-none');

                createOverlay.setAttribute(
                    'aria-hidden',
                    'false'
                );

                document.body.style.overflow = 'hidden';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE CREATE ROLE
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-create-role-close').forEach(function (button) {

            button.addEventListener('click', function () {

                if (!createOverlay) {
                    return;
                }

                createOverlay.classList.add('d-none');

                createOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | SELECT ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const selectAllButton = document.querySelector(
            '.js-select-all-permissions'
        );

        if (selectAllButton) {

            selectAllButton.addEventListener('click', function () {

                const checkboxes = document.querySelectorAll(
                    '#createRoleOverlay input.role-permission-checkbox'
                );

                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = true;

                });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const clearAllButton = document.querySelector(
            '.js-clear-all-permissions'
        );

        if (clearAllButton) {

            clearAllButton.addEventListener('click', function () {

                const checkboxes = document.querySelectorAll(
                    '#createRoleOverlay input.role-permission-checkbox'
                );

                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = false;

                });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE WHEN CLICKING OUTSIDE
        |--------------------------------------------------------------------------
        */

        if (createOverlay) {

            createOverlay.addEventListener('click', function (event) {

                if (event.target === createOverlay) {

                    createOverlay.classList.add('d-none');

                    createOverlay.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                    document.body.style.overflow = '';

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            if (
                createOverlay &&
                !createOverlay.classList.contains('d-none')
            ) {

                createOverlay.classList.add('d-none');

                createOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            }

        });

    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | CREATE ROLE OVERLAY
        |--------------------------------------------------------------------------
        */

        const createOverlay = document.getElementById('createRoleOverlay');

        const openCreateButtons = document.querySelectorAll('.js-create-role');

        const closeCreateButtons = document.querySelectorAll('.js-create-role-close');


        function openCreateOverlay() {

            if (!createOverlay) {
                return;
            }

            createOverlay.classList.remove('d-none');

            createOverlay.setAttribute('aria-hidden', 'false');

            document.body.style.overflow = 'hidden';
        }


        function closeCreateOverlay() {

            if (!createOverlay) {
                return;
            }

            createOverlay.classList.add('d-none');

            createOverlay.setAttribute('aria-hidden', 'true');

            document.body.style.overflow = '';
        }


        openCreateButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                openCreateOverlay();

            });

        });


        closeCreateButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                closeCreateOverlay();

            });

        });


        if (createOverlay) {

            createOverlay.addEventListener('click', function (event) {

                if (event.target === createOverlay) {

                    closeCreateOverlay();

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE - SELECT ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const selectAllButton = document.querySelector(
            '.js-select-all-permissions'
        );


        if (selectAllButton) {

            selectAllButton.addEventListener('click', function () {

                const checkboxes = document.querySelectorAll(
                    '#createRoleOverlay input.role-permission-checkbox'
                );


                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = true;

                });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE - CLEAR ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const clearAllButton = document.querySelector(
            '.js-clear-all-permissions'
        );


        if (clearAllButton) {

            clearAllButton.addEventListener('click', function () {

                const checkboxes = document.querySelectorAll(
                    '#createRoleOverlay input.role-permission-checkbox'
                );


                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = false;

                });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EDIT ROLE OVERLAY
        |--------------------------------------------------------------------------
        */

        const editOverlay = document.getElementById('editRoleOverlay');

        const editForm = document.getElementById('editRoleForm');

        const editLabel = document.getElementById('edit_role_label');

        const editDescription = document.getElementById(
            'edit_role_description'
        );


        function openEditOverlay(button) {

            if (!editOverlay || !editForm) {
                return;
            }


            const roleId = button.dataset.roleId;

            const roleLabel = button.dataset.roleLabel || '';

            const roleDescription = button.dataset.roleDescription || '';


            const rolePermissions =
                button.dataset.rolePermissions
                    ? button.dataset.rolePermissions
                        .split(',')
                        .map(Number)
                    : [];


            /*
            |--------------------------------------------------------------------------
            | Form action
            |--------------------------------------------------------------------------
            */

            editForm.action =
                `{{ url('admin/roles') }}/${roleId}`;


            /*
            |--------------------------------------------------------------------------
            | Fill role information
            |--------------------------------------------------------------------------
            */

            if (editLabel) {

                editLabel.value = roleLabel;

            }


            if (editDescription) {

                editDescription.value = roleDescription;

            }


            /*
            |--------------------------------------------------------------------------
            | Check assigned permissions
            |--------------------------------------------------------------------------
            */

            const checkboxes = editOverlay.querySelectorAll(
                '.edit-role-permission-checkbox'
            );


            checkboxes.forEach(function (checkbox) {

                checkbox.checked =
                    rolePermissions.includes(
                        Number(checkbox.value)
                    );

            });


            /*
            |--------------------------------------------------------------------------
            | Open overlay
            |--------------------------------------------------------------------------
            */

            editOverlay.classList.remove('d-none');

            editOverlay.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow = 'hidden';

        }


        /*
        |--------------------------------------------------------------------------
        | EDIT BUTTONS
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-edit-role').forEach(function (button) {

            button.addEventListener('click', function () {

                openEditOverlay(button);

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE EDIT OVERLAY
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-edit-role-close').forEach(function (button) {

            button.addEventListener('click', function () {

                if (!editOverlay) {
                    return;
                }


                editOverlay.classList.add('d-none');

                editOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE EDIT BY CLICKING OUTSIDE
        |--------------------------------------------------------------------------
        */

        if (editOverlay) {

            editOverlay.addEventListener('click', function (event) {

                if (event.target === editOverlay) {

                    editOverlay.classList.add('d-none');

                    editOverlay.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                    document.body.style.overflow = '';

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EDIT - SELECT ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const editSelectAllButton = document.querySelector(
            '.js-edit-select-all-permissions'
        );


        if (editSelectAllButton) {

            editSelectAllButton.addEventListener('click', function () {

                if (!editOverlay) {
                    return;
                }


                editOverlay
                    .querySelectorAll(
                        '.edit-role-permission-checkbox'
                    )
                    .forEach(function (checkbox) {

                        checkbox.checked = true;

                    });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EDIT - CLEAR ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const editClearAllButton = document.querySelector(
            '.js-edit-clear-all-permissions'
        );


        if (editClearAllButton) {

            editClearAllButton.addEventListener('click', function () {

                if (!editOverlay) {
                    return;
                }


                editOverlay
                    .querySelectorAll(
                        '.edit-role-permission-checkbox'
                    )
                    .forEach(function (checkbox) {

                        checkbox.checked = false;

                    });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE ROLE OVERLAY
        |--------------------------------------------------------------------------
        */

        const deleteOverlay = document.getElementById(
            'deleteRoleOverlay'
        );

        const deleteForm = document.getElementById(
            'deleteRoleForm'
        );

        const deleteRoleName = document.getElementById(
            'deleteRoleName'
        );


        function openDeleteOverlay(button) {

            if (!deleteOverlay || !deleteForm) {
                return;
            }


            const roleId = button.dataset.roleId;

            const roleName = button.dataset.roleName || 'ce rôle';


            /*
            |--------------------------------------------------------------------------
            | Form action
            |--------------------------------------------------------------------------
            */

            deleteForm.action =
                `{{ url('admin/roles') }}/${roleId}`;


            /*
            |--------------------------------------------------------------------------
            | Display role name
            |--------------------------------------------------------------------------
            */

            if (deleteRoleName) {

                deleteRoleName.textContent = roleName;

            }


            /*
            |--------------------------------------------------------------------------
            | Open overlay
            |--------------------------------------------------------------------------
            */

            deleteOverlay.classList.remove('d-none');

            deleteOverlay.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow = 'hidden';

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BUTTONS
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-role-delete').forEach(function (button) {

            button.addEventListener('click', function () {

                openDeleteOverlay(button);

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE DELETE OVERLAY
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.js-delete-role-close').forEach(function (button) {

            button.addEventListener('click', function () {

                if (!deleteOverlay) {
                    return;
                }


                deleteOverlay.classList.add('d-none');

                deleteOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CLOSE DELETE BY CLICKING OUTSIDE
        |--------------------------------------------------------------------------
        */

        if (deleteOverlay) {

            deleteOverlay.addEventListener('click', function (event) {

                if (event.target === deleteOverlay) {

                    deleteOverlay.classList.add('d-none');

                    deleteOverlay.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                    document.body.style.overflow = '';

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPE KEY
        |--------------------------------------------------------------------------
        */

        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }


            if (
                createOverlay &&
                !createOverlay.classList.contains('d-none')
            ) {

                closeCreateOverlay();

            }


            if (
                editOverlay &&
                !editOverlay.classList.contains('d-none')
            ) {

                editOverlay.classList.add('d-none');

                editOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            }


            if (
                deleteOverlay &&
                !deleteOverlay.classList.contains('d-none')
            ) {

                deleteOverlay.classList.add('d-none');

                deleteOverlay.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.style.overflow = '';

            }

        });

    });
</script>
@endpush