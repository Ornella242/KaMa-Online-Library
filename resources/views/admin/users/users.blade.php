@extends('layouts.admin')

@section('admin-content')

<div class="kama-users">
    <!-- Title -->

    <div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Gestion des utilisateurs
        </h1>

        <p class="text-black mb-0">
            Gérez les lecteurs et écrivains de la plateforme KaMa.
        </p>
    </div>


    <div>

        <a href="{{ route('admin.users.create') }}"
           class="btn kama-primary-btn d-flex align-items-center gap-2">

            <i class="bi bi-person-plus-fill"></i>

            Ajouter un utilisateur

        </a>

    </div>

</div>
<div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                    </button>
                </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Veuillez corriger les erreurs suivantes :</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

    <!-- Stats -->

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 kama-stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon users">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>
                        <h3 class="mb-0">
                            {{ $totalUsers }}
                        </h3>
                        <span class="text-black fw-semibold">
                            Utilisateurs
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 kama-stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon readers">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <div>
                        <h3 class="mb-0">
                            {{ $totalReaders }}
                        </h3>
                        <span class="text-black fw-semibold">
                            Lecteurs
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 kama-stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon writers">
                        <i class="bi bi-pen-fill"></i>
                    </div>

                    <div>
                        <h3 class="mb-0">
                            {{ $totalWriters }}
                        </h3>
                        <span class="text-black fw-semibold">
                            Écrivains
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->

        <div class="card shadow-sm border-0">
            <div class="card-header kama-card-header kama-table-header">

            <div class="row g-3 align-items-center">

                <div class="col-lg-4">

                    <h5 class="mb-0">
                        Liste des utilisateurs
                    </h5>

                </div>


                <div class="col-lg-5">

                    <div class="input-group">

                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            id="searchUsers"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Rechercher un nom ou un email...">

                    </div>

                </div>


                <div class="col-lg-3">

                    <select
                        id="userTypeFilter"
                        class="form-select">

                        <option value="">
                            Tous les utilisateurs
                        </option>

                        <option value="reader">
                            Lecteurs
                        </option>

                        <option value="writer">
                            Écrivains
                        </option>

                    </select>

                </div>

            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable"
                       class="table align-middle">
                    <thead>
                        <tr>
                            <th>
                                Nom
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Rôle
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{ $user->firstname }}
                                    {{ $user->lastname }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    @if($user->role)

                                        @switch($user->role->name)

                                            @case('reader')

                                                <span class="badge kama-role-badge reader">
                                                    <i class="bi bi-book-half me-1"></i>
                                                    Lecteur
                                                </span>

                                                @break


                                            @case('writer')

                                                <span class="badge kama-role-badge writer">
                                                    <i class="bi bi-pen-fill me-1"></i>
                                                    Écrivain
                                                </span>

                                                @break


                                            @case('admin')

                                                <span class="badge kama-role-badge admin">
                                                    <i class="bi bi-shield-lock-fill me-1"></i>
                                                    Administrateur
                                                </span>

                                                @break


                                            @default

                                                <span class="badge kama-role-badge default">
                                                    <i class="bi bi-person-fill me-1"></i>
                                                    {{ ucfirst($user->role->name) }}
                                                </span>

                                        @endswitch

                                    @else

                                        <span class="badge kama-role-badge default">
                                            <i class="bi bi-person-fill me-1"></i>
                                            Aucun rôle
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Voir -->
                                       <a href="{{ route('admin.show', $user->id) }}"
                                            class="btn btn-sm kama-action view"
                                            title="Voir le profil">

                                                <i class="bi bi-eye-fill"></i>

                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                        class="btn btn-sm kama-action edit"
                                        title="Modifier">

                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <!-- Supprimer -->
                                       <button
                                            class="btn btn-sm kama-action delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->firstname }} {{ $user->lastname }}"
                                            title="Supprimer">

                                            <i class="bi bi-trash-fill"></i>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0">

            <div class="category-pagination">

                {{ $users->onEachSide(1)->links() }}

            </div>

        </div>
    </div>

    
    
 </div>

  <div class="modal fade"
     id="deleteUserModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0">

                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    Supprimer l'utilisateur
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                Êtes-vous sûr de vouloir supprimer

                <strong id="deleteUserName"></strong> ?

                <p class="text-muted mt-2 mb-0">
                    Cette action est irréversible.
                </p>

            </div>

            <div class="modal-footer border-0">

                <button
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Annuler

                </button>

                <form
                    id="deleteUserForm"
                    method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger">

                        <i class="bi bi-trash me-1"></i>

                        Supprimer

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection