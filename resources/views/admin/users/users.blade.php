@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('admin-content')
    @php
        $roleLabels = [
            'reader' => 'Lecteur',
            'writer' => 'Écrivain',
            'admin' => 'Administrateur',
        ];
    @endphp

    <div class="users-page">
        <header class="users-heading">
            <div>
                <span>Communauté</span>
                <h2>Gestion des utilisateurs</h2>
                <p>Gérez les lecteurs, écrivains et administrateurs de la plateforme KaMa.</p>
            </div>
            <button type="button" class="users-add js-users-open" data-overlay-target="createUserOverlay">
                <i class="bi bi-person-plus"></i> Ajouter un utilisateur
            </button>
        </header>

        <section class="users-stats">
            <article>
                <span class="total"><i class="bi bi-people-fill"></i></span>
                <div><small>Utilisateurs</small><strong>{{ number_format($totalUsers) }}</strong></div>
            </article>
            <article>
                <span class="readers"><i class="bi bi-book-half"></i></span>
                <div><small>Lecteurs</small><strong>{{ number_format($totalReaders) }}</strong></div>
            </article>
            <article>
                <span class="writers"><i class="bi bi-pen-fill"></i></span>
                <div><small>Écrivains</small><strong>{{ number_format($totalWriters) }}</strong></div>
            </article>
            <article>
                <span class="admins"><i class="bi bi-shield-lock-fill"></i></span>
                <div><small>Administrateurs</small><strong>{{ number_format($totalAdmins) }}</strong></div>
            </article>
        </section>

        <div class="users-panel">
            <form method="GET" action="{{ route('admin.users') }}" class="users-toolbar">
                <div class="users-search">
                    <i class="bi bi-search"></i>
                    <input type="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un nom, email ou téléphone…"
                           aria-label="Rechercher un utilisateur">
                </div>
                <select name="type" class="users-filter-select" aria-label="Filtrer par rôle">
                    <option value="">Tous les rôles</option>
                    <option value="reader" @selected(request('type') === 'reader')>Lecteurs</option>
                    <option value="writer" @selected(request('type') === 'writer')>Écrivains</option>
                    <option value="admin" @selected(request('type') === 'admin')>Administrateurs</option>
                </select>
                <button type="submit" class="users-filter-btn">Filtrer</button>
                @if(request()->filled('search') || request()->filled('type'))
                    <a href="{{ route('admin.users') }}" class="users-reset" title="Réinitialiser">
                        <i class="bi bi-x-lg"></i>
                        <span>Réinitialiser</span>
                    </a>
                @endif
            </form>

            <header class="users-panel-header">
                <div>
                    <strong>{{ number_format($users->total()) }} utilisateur(s)</strong>
                    <span>
                        @if($users->total())
                            Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }}
                        @else
                            Aucun utilisateur trouvé
                        @endif
                    </span>
                </div>
            </header>

            @if($users->isEmpty())
                <div class="users-empty">
                    <span><i class="bi bi-people"></i></span>
                    <h3>Aucun utilisateur trouvé</h3>
                    <p>Aucun compte ne correspond à votre recherche.</p>
                    @if(request()->filled('search') || request()->filled('type'))
                        <a href="{{ route('admin.users') }}">Réinitialiser les filtres</a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table users-table align-middle">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Contact</th>
                                <th>Rôle</th>
                                <th>Inscrit le</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @php
                                    $roleName = $user->role?->name;
                                    $roleLabel = $roleLabels[$roleName] ?? ucfirst($roleName ?? 'Aucun rôle');
                                    $fullName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: 'Sans nom';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="users-name-cell">
                                            <span class="users-avatar {{ $roleName ?: 'default' }}">
                                                {{ strtoupper(mb_substr($user->firstname ?: 'U', 0, 1) . mb_substr($user->lastname ?: '', 0, 1)) }}
                                            </span>
                                            <div>
                                                <strong>{{ $fullName }}</strong>
                                                <small>#{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="users-contact-cell">
                                            <strong>{{ $user->email }}</strong>
                                            <small>{{ $user->phone ?: 'Téléphone non renseigné' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="users-role-badge {{ $roleName ?: 'default' }}">
                                            <i class="bi {{ $roleName === 'writer' ? 'bi-pen-fill' : ($roleName === 'admin' ? 'bi-shield-lock-fill' : 'bi-book-half') }}"></i>
                                            {{ $roleLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="users-date">
                                            <strong>{{ $user->created_at?->format('d/m/Y') }}</strong>
                                            <small>{{ $user->created_at?->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="users-row-actions">
                                            <a href="{{ route('admin.show', $user) }}" class="view" title="Voir le profil">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button"
                                                    class="edit js-users-open"
                                                    data-overlay-target="editUser-{{ $user->id }}"
                                                    title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <button type="button"
                                                        class="delete js-users-open"
                                                        data-overlay-target="deleteUser-{{ $user->id }}"
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <footer class="users-pagination">
                        <span>Page {{ $users->currentPage() }} sur {{ $users->lastPage() }}</span>
                        {{ $users->onEachSide(1)->links() }}
                    </footer>
                @endif
            @endif
        </div>

        {{-- Overlay : créer --}}
        <div class="users-overlay d-none"
             id="createUserOverlay"
             role="dialog"
             aria-modal="true"
             aria-hidden="true"
             aria-labelledby="createUserTitle">
            <div class="users-overlay-container">
                <header class="users-overlay-header">
                    <div>
                        <span>Nouveau compte</span>
                        <h2 id="createUserTitle">Ajouter un utilisateur</h2>
                    </div>
                    <button type="button" class="js-users-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                </header>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="users-form-body">
                        <div class="users-form-intro">
                            <span><i class="bi bi-person-plus-fill"></i></span>
                            <div>
                                <small>Création</small>
                                <strong>Un mot de passe temporaire sera généré</strong>
                                <span>Les identifiants seront envoyés automatiquement par email.</span>
                            </div>
                        </div>

                        <div class="users-form-grid">
                            <div>
                                <label for="createFirstname">Prénom <span>*</span></label>
                                <input type="text" id="createFirstname" name="firstname" value="{{ old('firstname') }}" required maxlength="255" placeholder="Ex : Ama">
                            </div>
                            <div>
                                <label for="createLastname">Nom <span>*</span></label>
                                <input type="text" id="createLastname" name="lastname" value="{{ old('lastname') }}" required maxlength="255" placeholder="Ex : Koffi">
                            </div>
                            <div class="full">
                                <label for="createEmail">Email <span>*</span></label>
                                <input type="email" id="createEmail" name="email" value="{{ old('email') }}" required placeholder="exemple@email.com">
                            </div>
                            <div>
                                <label for="createPhone">Téléphone</label>
                                <input type="text" id="createPhone" name="phone" value="{{ old('phone') }}" maxlength="30" placeholder="+229 …">
                            </div>
                            <div>
                                <label for="createRole">Rôle <span>*</span></label>
                                <select id="createRole" name="role_id" required>
                                    <option value="">Choisir un rôle</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @selected((string) old('role_id') === (string) $role->id)>
                                            {{ $roleLabels[$role->name] ?? ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <footer class="users-overlay-footer">
                        <button type="button" class="secondary js-users-close">Annuler</button>
                        <button type="submit" class="primary"><i class="bi bi-check2-circle"></i> Créer l’utilisateur</button>
                    </footer>
                </form>
            </div>
        </div>

        @foreach($users as $user)
            @php
                $fullName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: 'Sans nom';
            @endphp

            {{-- Overlay : modifier --}}
            <div class="users-overlay d-none"
                 id="editUser-{{ $user->id }}"
                 role="dialog"
                 aria-modal="true"
                 aria-hidden="true"
                 aria-labelledby="editUserTitle-{{ $user->id }}">
                <div class="users-overlay-container wide">
                    <header class="users-overlay-header dark">
                        <div>
                            <span>Compte #{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <h2 id="editUserTitle-{{ $user->id }}">Modifier {{ $fullName }}</h2>
                        </div>
                        <button type="button" class="js-users-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                    </header>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="users-form-body">
                            <div class="users-form-intro">
                                <span><i class="bi bi-pencil-square"></i></span>
                                <div>
                                    <small>Utilisateur concerné</small>
                                    <strong>{{ $fullName }}</strong>
                                    <span>{{ $user->email }} · {{ $roleLabels[$user->role?->name] ?? 'Sans rôle' }}</span>
                                </div>
                            </div>

                            <div class="users-form-grid">
                                <div>
                                    <label for="editFirstname-{{ $user->id }}">Prénom <span>*</span></label>
                                    <input type="text" id="editFirstname-{{ $user->id }}" name="firstname" value="{{ $user->firstname }}" required maxlength="255">
                                </div>
                                <div>
                                    <label for="editLastname-{{ $user->id }}">Nom <span>*</span></label>
                                    <input type="text" id="editLastname-{{ $user->id }}" name="lastname" value="{{ $user->lastname }}" required maxlength="255">
                                </div>
                                <div class="full">
                                    <label for="editEmail-{{ $user->id }}">Email <span>*</span></label>
                                    <input type="email" id="editEmail-{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div>
                                    <label for="editPhone-{{ $user->id }}">Téléphone</label>
                                    <input type="text" id="editPhone-{{ $user->id }}" name="phone" value="{{ $user->phone }}" maxlength="30">
                                </div>
                                <div>
                                    <label for="editGender-{{ $user->id }}">Genre</label>
                                    <select id="editGender-{{ $user->id }}" name="gender">
                                        <option value="">Non précisé</option>
                                        <option value="male" @selected($user->gender === 'male')>Homme</option>
                                        <option value="female" @selected($user->gender === 'female')>Femme</option>
                                        <option value="other" @selected($user->gender === 'other')>Autre</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editCountry-{{ $user->id }}">Pays</label>
                                    <select id="editCountry-{{ $user->id }}" name="country_id">
                                        <option value="">Choisir un pays</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @selected((string) $user->country_id === (string) $country->id)>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="editCity-{{ $user->id }}">Ville</label>
                                    <input type="text" id="editCity-{{ $user->id }}" name="city" value="{{ $user->city }}" maxlength="100">
                                </div>
                                <div class="full">
                                    <label for="editBio-{{ $user->id }}">Biographie</label>
                                    <textarea id="editBio-{{ $user->id }}" name="bio" rows="3" placeholder="Présentation courte…">{{ $user->bio }}</textarea>
                                </div>
                            </div>
                        </div>
                        <footer class="users-overlay-footer">
                            <button type="button" class="secondary js-users-close">Annuler</button>
                            <button type="submit" class="primary"><i class="bi bi-check2-circle"></i> Enregistrer</button>
                        </footer>
                    </form>
                </div>
            </div>

            @if($user->id !== auth()->id())
                {{-- Overlay : supprimer --}}
                <div class="users-overlay d-none"
                     id="deleteUser-{{ $user->id }}"
                     role="dialog"
                     aria-modal="true"
                     aria-hidden="true"
                     aria-labelledby="deleteUserTitle-{{ $user->id }}">
                    <div class="users-overlay-container">
                        <header class="users-overlay-header danger">
                            <div>
                                <span>Action irréversible</span>
                                <h2 id="deleteUserTitle-{{ $user->id }}">Supprimer cet utilisateur ?</h2>
                            </div>
                            <button type="button" class="js-users-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
                        </header>
                        <div class="users-confirm-body">
                            <span class="danger"><i class="bi bi-trash3"></i></span>
                            <h3>{{ $fullName }}</h3>
                            <p>Le compte <strong>{{ $user->email }}</strong> sera définitivement supprimé.</p>
                            <div class="warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Cette action est irréversible. Les données liées pourront être impactées.
                            </div>
                        </div>
                        <footer class="users-overlay-footer">
                            <button type="button" class="secondary js-users-close">Annuler</button>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger"><i class="bi bi-trash"></i> Confirmer la suppression</button>
                            </form>
                        </footer>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        .users-page { display: grid; gap: 22px }

        .users-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            background: transparent;
            z-index: auto;
        }

        .users-heading > div > span {
            display: block;
            margin-bottom: 4px;
            color: #b30000;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .users-heading h2 { margin: 0; font-size: 1.75rem }
        .users-heading p { margin: 5px 0 0; color: #777b83; font-size: .95rem }

        .users-add {
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
            font-weight: 700
        }

        .users-add:hover { background: #8f0000; color: #fff }

        .users-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 13px
        }

        .users-stats article {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border: 1px solid #e6e7ea;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .users-stats article > span {
            display: grid;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            place-items: center;
            border-radius: 12px;
            font-size: 1.25rem
        }

        .users-stats .total { background: #fff0f0; color: #b30000 }
        .users-stats .readers { background: #e8f5ff; color: #157bb5 }
        .users-stats .writers { background: #eaf8ef; color: #138443 }
        .users-stats .admins { background: #f3f0ff; color: #5b4bb7 }

        .users-stats small,
        .users-stats strong { display: block }
        .users-stats small { color: #858991; font-size: .78rem; font-weight: 700 }
        .users-stats strong { margin-top: 2px; font-size: 1.4rem }

        .users-panel {
            overflow: hidden;
            border: 1px solid #e5e7ea;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .users-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 17px 19px;
            border-bottom: 1px solid #eceef0;
            background: #fafafa
        }

        .users-search {
            position: relative;
            display: flex;
            min-width: 220px;
            max-width: 420px;
            flex: 1;
            align-items: center
        }

        .users-search i {
            position: absolute;
            left: 13px;
            color: #8c9097;
            font-size: .95rem
        }

        .users-search input,
        .users-filter-select {
            height: 42px;
            border: 1px solid #dfe1e5;
            border-radius: 10px;
            background: #fff;
            color: #33363b;
            font-size: .88rem;
            outline: none
        }

        .users-search input {
            width: 100%;
            padding: 8px 13px 8px 38px
        }

        .users-filter-select { min-width: 170px; padding: 8px 12px }

        .users-search input:focus,
        .users-filter-select:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .users-filter-btn {
            height: 42px;
            padding: 8px 18px;
            border: 0;
            border-radius: 10px;
            background: #1c1d20;
            color: #fff;
            font-size: .85rem;
            font-weight: 700
        }

        .users-reset {
            display: inline-flex;
            height: 42px;
            align-items: center;
            gap: 6px;
            padding: 8px 11px;
            border: 1px solid #e0e2e5;
            border-radius: 10px;
            color: #777b82;
            font-size: .82rem;
            font-weight: 700
        }

        .users-panel-header {
            padding: 16px 19px 9px;
            background: transparent;
            z-index: auto;
        }

        .users-panel-header strong,
        .users-panel-header span { display: block }
        .users-panel-header strong { font-size: .95rem }
        .users-panel-header span { margin-top: 2px; color: #989ba2; font-size: .78rem }

        .users-table { min-width: 900px; margin: 0 }
        .users-table thead th {
            padding: 11px 14px;
            border-color: #eceef0;
            color: #8e9299;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap
        }
        .users-table tbody td {
            padding: 13px 14px;
            border-color: #eff0f2;
            color: #45484e;
            font-size: .86rem
        }
        .users-table tbody tr:hover { background: #fcfcfd }

        .users-name-cell {
            display: flex;
            min-width: 180px;
            align-items: center;
            gap: 11px
        }

        .users-name-cell .users-avatar {
            display: inline-flex;
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #b30000;
            font-size: .78rem;
            font-weight: 800;
            line-height: 1;
            text-align: center
        }

        .users-name-cell .users-avatar.writer { background: #eaf8ef; color: #138443 }
        .users-name-cell .users-avatar.admin { background: #f3f0ff; color: #5b4bb7 }
        .users-name-cell .users-avatar.reader { background: #e8f5ff; color: #157bb5 }

        .users-name-cell strong,
        .users-name-cell small,
        .users-contact-cell strong,
        .users-contact-cell small,
        .users-date strong,
        .users-date small { display: block }

        .users-name-cell strong,
        .users-contact-cell strong,
        .users-date strong { color: #23252a; font-size: .9rem }

        .users-name-cell small,
        .users-contact-cell small,
        .users-date small { margin-top: 2px; color: #989ba2; font-size: .73rem }

        .users-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f0f1f3;
            color: #4d5056;
            font-size: .76rem;
            font-weight: 700;
            white-space: nowrap
        }

        .users-role-badge.reader { background: #e8f5ff; color: #157bb5 }
        .users-role-badge.writer { background: #eaf8ef; color: #138443 }
        .users-role-badge.admin { background: #f3f0ff; color: #5b4bb7 }

        .users-row-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px
        }

        .users-row-actions a,
        .users-row-actions button {
            display: inline-flex;
            width: 33px;
            height: 33px;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: .85rem
        }

        .users-row-actions .view,
        .users-row-actions .edit {
            border-color: #e2e4e7;
            background: #fff;
            color: #3f4248
        }

        .users-row-actions .view:hover,
        .users-row-actions .edit:hover {
            border-color: #1c1d20;
            background: #1c1d20;
            color: #fff
        }

        .users-row-actions .delete {
            background: #fff0f0;
            color: #b30000
        }

        .users-row-actions .delete:hover {
            background: #b30000;
            color: #fff
        }

        .users-empty {
            display: grid;
            width: 100%;
            min-height: 280px;
            place-items: center;
            align-content: center;
            justify-items: center;
            padding: 40px 24px;
            text-align: center
        }

        .users-empty > span {
            display: grid;
            width: 60px;
            height: 60px;
            place-items: center;
            border-radius: 17px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.5rem
        }

        .users-empty h3 { margin: 14px 0 4px; font-size: 1.12rem }
        .users-empty p { margin: 0; color: #8c9097; font-size: .88rem }
        .users-empty a { margin-top: 12px; color: #b30000; font-size: .85rem; font-weight: 700 }

        .users-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 19px;
            border-top: 1px solid #eceef0;
            background: transparent;
            z-index: auto;
        }

        .users-pagination > span { color: #8b8f96; font-size: .8rem }
        .users-pagination .pagination { margin: 0 }

        .users-overlay {
            position: fixed;
            inset: 0;
            z-index: 10060;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(8, 10, 14, .72);
            backdrop-filter: blur(6px)
        }

        .users-overlay-container {
            display: flex;
            width: min(100%, 590px);
            max-height: 94vh;
            overflow: hidden;
            flex-direction: column;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .36)
        }

        .users-overlay-container.wide { width: min(100%, 720px) }
        .users-overlay-container > form { display: flex; min-height: 0; flex-direction: column }

        .users-overlay-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 17px 21px;
            background: #8f0000;
            color: #fff;
            z-index: auto;
        }

        .users-overlay-header.dark { background: #111827 }
        .users-overlay-header.danger { background: #8f0000 }

        .users-overlay-header span {
            display: block;
            margin-bottom: 3px;
            color: #fca5a5;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .users-overlay-header h2 {
            max-width: 520px;
            margin: 0;
            overflow: hidden;
            color: #fff;
            font-size: 1.15rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .users-overlay-header > button {
            display: grid;
            width: 35px;
            height: 35px;
            flex: 0 0 35px;
            place-items: center;
            border: 0;
            border-radius: 9px;
            background: rgba(255, 255, 255, .12);
            color: #fff
        }

        .users-form-body { overflow: auto; padding: 24px }

        .users-form-intro {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 11px;
            background: #f7f7f8
        }

        .users-form-intro > span {
            display: grid;
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            place-items: center;
            border-radius: 11px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.1rem
        }

        .users-form-intro small,
        .users-form-intro strong,
        .users-form-intro > div > span { display: block }

        .users-form-intro small {
            color: #9699a0;
            font-size: .7rem;
            text-transform: uppercase
        }

        .users-form-intro strong { margin-top: 2px; font-size: .88rem }
        .users-form-intro > div > span { margin-top: 2px; color: #878b92; font-size: .76rem }

        .users-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 16px
        }

        .users-form-grid .full { grid-column: 1 / -1 }

        .users-form-grid label {
            display: block;
            margin-bottom: 7px;
            color: #36383d;
            font-size: .84rem;
            font-weight: 700
        }

        .users-form-grid label span { color: #b30000 }

        .users-form-grid input,
        .users-form-grid select,
        .users-form-grid textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dfe1e5;
            border-radius: 11px;
            color: #36383d;
            font-size: .88rem;
            line-height: 1.55;
            outline: none
        }

        .users-form-grid textarea { resize: vertical }

        .users-form-grid input:focus,
        .users-form-grid select:focus,
        .users-form-grid textarea:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .users-confirm-body {
            overflow: auto;
            padding: 29px;
            text-align: center
        }

        .users-confirm-body > span {
            display: grid;
            width: 65px;
            height: 65px;
            margin: 0 auto 14px;
            place-items: center;
            border-radius: 18px;
            font-size: 1.65rem
        }

        .users-confirm-body > span.danger { background: #fff0f0; color: #b30000 }
        .users-confirm-body h3 { margin: 0 0 7px; font-size: 1.2rem }
        .users-confirm-body p {
            max-width: 440px;
            margin: 0 auto;
            color: #71757d;
            font-size: .88rem;
            line-height: 1.6
        }

        .users-confirm-body > div {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 19px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #fff8ec;
            color: #8a6512;
            font-size: .8rem;
            text-align: left
        }

        .users-overlay-footer {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 14px 20px;
            border-top: 1px solid #e7e8eb;
            background: #fff
        }

        .users-overlay-footer button {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 14px;
            border: 0;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700
        }

        .users-overlay-footer .secondary {
            border: 1px solid #dedfe2;
            background: #fff;
            color: #666970
        }

        .users-overlay-footer .primary,
        .users-overlay-footer .danger {
            background: #b30000;
            color: #fff
        }

        .users-overlay-footer .primary:hover,
        .users-overlay-footer .danger:hover { background: #8f0000 }

        @media (max-width: 1050px) {
            .users-stats { grid-template-columns: repeat(2, 1fr) }
        }

        @media (max-width: 767px) {
            .users-heading { align-items: flex-start; flex-direction: column }
            .users-add { width: 100%; justify-content: center }
            .users-toolbar { align-items: stretch; flex-direction: column }
            .users-search { width: 100%; max-width: none }
            .users-filter-select,
            .users-filter-btn { width: 100% }
            .users-reset { justify-content: center }
            .users-form-grid { grid-template-columns: 1fr }
            .users-overlay { padding: 10px }
            .users-overlay-footer { flex-wrap: wrap }
            .users-overlay-footer button { flex: 1 }
            .users-overlay-footer form { flex: 1; display: flex }
            .users-overlay-footer form button { width: 100% }
            .users-pagination { align-items: flex-start; flex-direction: column }
        }

        @media (max-width: 520px) {
            .users-stats { grid-template-columns: 1fr }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openOverlays = () => document.querySelectorAll('.users-overlay:not(.d-none)');

            const closeOverlay = (overlay) => {
                if (!overlay) return;
                overlay.classList.add('d-none');
                overlay.setAttribute('aria-hidden', 'true');
                if (!openOverlays().length) document.body.style.overflow = '';
            };

            const openOverlay = (id) => {
                const overlay = document.getElementById(id);
                if (!overlay) return;
                overlay.classList.remove('d-none');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                overlay.querySelector('input, select, textarea, .js-users-close')?.focus();
            };

            document.querySelectorAll('.js-users-open').forEach((button) => {
                button.addEventListener('click', () => openOverlay(button.dataset.overlayTarget));
            });

            document.querySelectorAll('.users-overlay').forEach((overlay) => {
                overlay.querySelectorAll('.js-users-close').forEach((button) => {
                    button.addEventListener('click', () => closeOverlay(overlay));
                });
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) closeOverlay(overlay);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeOverlay(document.querySelector('.users-overlay:not(.d-none)'));
            });

            @if($errors->any())
                openOverlay('createUserOverlay');
            @endif
        });
    </script>
@endpush
