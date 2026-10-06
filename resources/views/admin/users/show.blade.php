@extends('layouts.admin')

@section('title', 'Profil utilisateur')
@section('page-title', 'Profil utilisateur')

@section('admin-content')

@php
    $role = $user->role->name ?? 'reader';
    $isAuthor = in_array($role, ['writer', 'admin']);
@endphp

<div class="kama-user-detail-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="kama-user-page-header">

        <div class="kama-user-page-title">

            <div class="kama-user-page-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

            <div>
                <span>Gestion des utilisateurs</span>

                <h3>
                    Profil utilisateur
                </h3>

                <p>
                    Consultez les informations personnelles, le rôle et l’activité de cet utilisateur.
                </p>
            </div>

        </div>

        <a href="{{ url()->previous() }}" class="kama-user-back">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>


    {{-- =========================================================
        PROFILE HERO
    ========================================================== --}}
    <div class="kama-user-profile-card">

        <div class="kama-user-profile-main">

            {{-- AVATAR --}}
            <div class="kama-user-avatar-wrapper">

                <img
                    src="{{ $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : asset('assets/images/avatar/01.jpg') }}"
                    alt="{{ $user->firstname }} {{ $user->lastname }}"
                    class="kama-user-avatar">

            </div>


            {{-- IDENTITY --}}
            <div class="kama-user-identity">

                <div class="kama-user-name-row">

                    <h2>
                        {{ $user->firstname }} {{ $user->lastname }}
                    </h2>

                    @if($role === 'writer')

                        <span class="kama-user-role writer">
                            <i class="bi bi-pen"></i>
                            Auteur KaMa
                        </span>

                    @elseif($role === 'admin')

                        <span class="kama-user-role admin">
                            <i class="bi bi-shield-check"></i>
                            Administrateur
                        </span>

                    @else

                        <span class="kama-user-role reader">
                            <i class="bi bi-book"></i>
                            Lecteur
                        </span>

                    @endif

                </div>


                <p class="kama-user-email">
                    <i class="bi bi-envelope"></i>
                    {{ $user->email }}
                </p>


                <div class="kama-user-meta">

                    <span>
                        <i class="bi bi-calendar3"></i>
                        Membre depuis
                        <strong>{{ $user->created_at->format('d M Y') }}</strong>
                    </span>

                    @if($user->country)

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            {{ $user->country->name }}
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="kama-user-account-status">

            <span class="kama-user-status-dot"></span>

            <div>
                <strong>Compte actif</strong>
                <small>Utilisateur enregistré sur KaMa</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
        AUTHOR / ADMIN STATISTICS
    ========================================================== --}}
    @if($isAuthor)

        <div class="kama-user-stats">

            {{-- BOOKS --}}
            <div class="kama-user-stat-card">

                <div class="kama-user-stat-icon books">
                    <i class="bi bi-book-half"></i>
                </div>

                <div class="kama-user-stat-content">

                    <span>Livres au total</span>

                    <strong>
                        {{ $data['totalBooks'] ?? $user->books->count() }}
                    </strong>

                </div>

            </div>


            {{-- UNDER REVIEW --}}
            <div class="kama-user-stat-card">

                <div class="kama-user-stat-icon review">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div class="kama-user-stat-content">

                    <span>En attente de validation</span>

                    <strong>
                        {{ $data['BooksUnderreview'] ?? 0 }}
                    </strong>

                </div>

            </div>


            {{-- SALES --}}
            <div class="kama-user-stat-card">

                <div class="kama-user-stat-icon sales">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="kama-user-stat-content">

                    <span>Livres vendus</span>

                    <strong>
                        {{ $data['totalSales'] ?? 0 }}
                    </strong>

                </div>

            </div>


            {{-- REVENUE --}}
            <div class="kama-user-stat-card">

                <div class="kama-user-stat-icon revenue">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>

                <div class="kama-user-stat-content">

                    <span>Total des gains</span>

                    <strong>
                        {{ number_format((float) ($data['totalRevenue'] ?? 0), 2) }} $
                    </strong>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        PERSONAL INFORMATION
    ========================================================== --}}
    <div class="kama-user-section">

        <div class="kama-user-section-header">

            <div>

                <span>Informations du compte</span>

                <h4>
                    Informations personnelles
                </h4>

                <p>
                    Informations générales associées au compte utilisateur.
                </p>

            </div>

            <div class="kama-user-section-icon">
                <i class="bi bi-person-vcard"></i>
            </div>

        </div>


        <div class="kama-user-info-grid">

            {{-- FULL NAME --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-person"></i>
                </div>

                <div>
                    <span>Nom complet</span>

                    <strong>
                        {{ $user->firstname }} {{ $user->lastname }}
                    </strong>
                </div>

            </div>


            {{-- EMAIL --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-envelope"></i>
                </div>

                <div>
                    <span>Adresse email</span>

                    <strong class="break-email">
                        {{ $user->email }}
                    </strong>
                </div>

            </div>


            {{-- PHONE --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-telephone"></i>
                </div>

                <div>
                    <span>Téléphone</span>

                    <strong>
                        {{ $user->phone ?? 'Non renseigné' }}
                    </strong>
                </div>

            </div>


            {{-- GENDER --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-gender-ambiguous"></i>
                </div>

                <div>
                    <span>Genre</span>

                    <strong>
                        @if($user->gender === 'male')
                            Homme
                        @elseif($user->gender === 'female')
                            Femme
                        @elseif($user->gender === 'other')
                            Autre
                        @else
                            Non renseigné
                        @endif
                    </strong>
                </div>

            </div>


            {{-- COUNTRY --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-globe"></i>
                </div>

                <div>
                    <span>Pays</span>

                    <strong>
                        {{ $user->country->name ?? 'Non renseigné' }}
                    </strong>
                </div>

            </div>


            {{-- CITY --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <div>
                    <span>Ville</span>

                    <strong>
                        {{ $user->city ?? 'Non renseignée' }}
                    </strong>
                </div>

            </div>


            {{-- ROLE --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-person-badge"></i>
                </div>

                <div>
                    <span>Rôle</span>

                    <strong>

                        @if($role === 'admin')
                            Administrateur
                        @elseif($role === 'writer')
                            Auteur KaMa
                        @else
                            Lecteur
                        @endif

                    </strong>

                </div>

            </div>


            {{-- MEMBER SINCE --}}
            <div class="kama-user-info-item">

                <div class="kama-user-info-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div>
                    <span>Membre KaMa depuis</span>

                    <strong>
                        {{ $user->created_at->format('d M Y') }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BIOGRAPHY
    ========================================================== --}}
    @if($isAuthor)

        <div class="kama-user-section">

            <div class="kama-user-section-header">

                <div>

                    <span>Présentation</span>

                    <h4>
                        Biographie
                    </h4>

                    <p>
                        Présentation publique renseignée par l'utilisateur.
                    </p>

                </div>

                <div class="kama-user-section-icon">
                    <i class="bi bi-chat-square-text"></i>
                </div>

            </div>


            <div class="kama-user-bio">

                @if($user->bio)

                    <p>
                        {{ $user->bio }}
                    </p>

                @else

                    <div class="kama-user-empty-inline">

                        <i class="bi bi-chat-square"></i>

                        <span>
                            Aucune biographie renseignée.
                        </span>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- =========================================================
        BOOKS
    ========================================================== --}}
    @if($isAuthor)

        <div class="kama-user-section kama-user-books-section">

            <div class="kama-user-section-header">

                <div>

                    <span>Bibliothèque de l'auteur</span>

                    <h4>
                        Livres ajoutés
                    </h4>

                    <p>
                        Livres associés à ce compte auteur.
                    </p>

                </div>

                <div class="kama-user-books-count">

                    <strong>
                        {{ $user->books->count() }}
                    </strong>

                    <span>
                        livres
                    </span>

                </div>

            </div>


           <div class="kama-user-books-list">

    @forelse($user->books as $book)

        <div class="kama-user-book-row">

            {{-- NOM --}}
            <div class="kama-user-book-name">

                <div class="kama-user-book-icon">
                    <i class="bi bi-book"></i>
                </div>

                <div>
                    <strong>
                        {{ $book->title }}
                    </strong>
                </div>

            </div>


            {{-- TYPE --}}
            <div class="kama-user-book-type">

                @if($book->type === 'audio')

                    <i class="bi bi-headphones"></i>
                    Audio

                @else

                    <i class="bi bi-file-earmark-text"></i>
                    Ebook

                @endif

            </div>


            {{-- PRIX --}}
            <div class="kama-user-book-price">

                {{ number_format((float) $book->price, 2) }} $

            </div>

        </div>

    @empty

        <div class="kama-user-empty-books">

            <div class="kama-user-empty-icon">
                <i class="bi bi-book"></i>
            </div>

            <h5>
                Aucun livre
            </h5>

            <p>
                Cet utilisateur n'a encore ajouté aucun livre.
            </p>

        </div>

    @endforelse

</div>

        </div>

    @endif

</div>

@endsection


@push('styles')
<style>

/* =========================================================
   LISTE DES LIVRES UTILISATEUR
   ========================================================= */

.kama-user-books-list {
    width: 100%;
}


/* =========================================================
   LIGNE D'UN LIVRE
   ========================================================= */

.kama-user-book-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 180px 140px;
    align-items: center;

    min-height: 72px;
    padding: 14px 24px;

    background: #fff;
    border-bottom: 1px solid #e7e8eb;

    transition:
        background-color .2s ease,
        box-shadow .2s ease;
}

.kama-user-book-row:last-child {
    border-bottom: 0;
}

.kama-user-book-row:hover {
    background: #fffafa;
}


/* =========================================================
   NOM DU LIVRE
   ========================================================= */

.kama-user-book-name {
    display: flex;
    align-items: center;
    gap: 13px;

    min-width: 0;
}

.kama-user-book-icon {
    width: 40px;
    height: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 40px;

    background: #fff5f5;
    border: 1px solid #f0cfcf;
    border-radius: 10px;

    color: #b30000;
    font-size: .95rem;
}

.kama-user-book-name strong {
    display: block;

    max-width: 100%;

    color: #202124;
    font-size: .88rem;
    font-weight: 700;
    line-height: 1.4;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


/* =========================================================
   TYPE
   ========================================================= */

.kama-user-book-type {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #656871;

    font-size: .83rem;
    font-weight: 600;
}

.kama-user-book-type i {
    color: #b30000;
    font-size: .95rem;
}


/* =========================================================
   PRIX
   ========================================================= */

.kama-user-book-price {
    color: #202124;

    font-size: .88rem;
    font-weight: 800;

    text-align: right;
}


/* =========================================================
   ÉTAT VIDE
   ========================================================= */

.kama-user-empty-books {
    padding: 50px 24px;

    text-align: center;
}

.kama-user-empty-icon {
    width: 52px;
    height: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 14px;

    background: #fff5f5;
    border: 1px solid #f0cfcf;
    border-radius: 14px;

    color: #b30000;
    font-size: 1.15rem;
}

.kama-user-empty-books h5 {
    margin: 0 0 6px;

    color: #202124;
    font-size: .95rem;
    font-weight: 800;
}

.kama-user-empty-books p {
    margin: 0;

    color: #8a8d94;
    font-size: .82rem;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 800px) {

    .kama-user-book-row {
        grid-template-columns: minmax(0, 1fr) 130px 100px;

        padding-left: 18px;
        padding-right: 18px;
    }

}


@media (max-width: 600px) {

    .kama-user-book-row {
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 10px;

        padding: 15px 18px;
    }

    .kama-user-book-name {
        grid-column: 1;
        grid-row: 1;
    }

    .kama-user-book-type {
        grid-column: 1;
        grid-row: 2;

        font-size: .76rem;
    }

    .kama-user-book-price {
        grid-column: 2;
        grid-row: 1;

        text-align: right;
    }

}


@media (max-width: 400px) {

    .kama-user-book-icon {
        width: 36px;
        height: 36px;

        flex-basis: 36px;
    }

    .kama-user-book-name strong {
        font-size: .82rem;
    }

    .kama-user-book-price {
        font-size: .82rem;
    }

}

/* =========================================================
   KAMA USER DETAIL
   ========================================================= */

.kama-user-detail-page {
    width: 100%;
}


/* =========================================================
   PAGE HEADER
   ========================================================= */

.kama-user-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.kama-user-page-title {
    display: flex;
    align-items: center;
    gap: 14px;
}

.kama-user-page-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 13px;
    background: #fff5f5;
    color: #b30000;
    font-size: 1.25rem;
}

.kama-user-page-title > div:last-child > span {
    display: block;
    margin-bottom: 3px;
    color: #b30000;
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.kama-user-page-title h3 {
    margin: 0;
    color: #18181b;
    font-size: 1.35rem;
    font-weight: 800;
}

.kama-user-page-title p {
    margin: 4px 0 0;
    color: #71717a;
    font-size: .78rem;
}

.kama-user-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    color: #52525b;
    font-size: .75rem;
    font-weight: 800;
    text-decoration: none;
    transition: all .2s ease;
}

.kama-user-back:hover {
    border-color: #b30000;
    background: #fff5f5;
    color: #b30000;
}


/* =========================================================
   PROFILE HERO
   ========================================================= */

.kama-user-profile-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 20px;
    padding: 24px;
    border: 1px solid #e7e8eb;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
}

.kama-user-profile-main {
    display: flex;
    align-items: center;
    gap: 20px;
    min-width: 0;
}

.kama-user-avatar-wrapper {
    flex-shrink: 0;
}

.kama-user-avatar {
    width: 92px;
    height: 92px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .12);
}

.kama-user-identity {
    min-width: 0;
}

.kama-user-name-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.kama-user-name-row h2 {
    margin: 0;
    color: #18181b;
    font-size: 1.35rem;
    font-weight: 800;
}

.kama-user-role {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: .65rem;
    font-weight: 800;
}

.kama-user-role.writer {
    background: #fff5f5;
    border: 1px solid #f0cfcf;
    color: #b30000;
}

.kama-user-role.admin {
    background: #f4f4f5;
    border: 1px solid #d4d4d8;
    color: #27272a;
}

.kama-user-role.reader {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
}

.kama-user-email {
    display: flex;
    align-items: center;
    gap: 7px;
    margin: 8px 0 10px;
    color: #52525b;
    font-size: .8rem;
}

.kama-user-email i {
    color: #b30000;
}

.kama-user-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 18px;
}

.kama-user-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #71717a;
    font-size: .7rem;
}

.kama-user-meta i {
    color: #b30000;
}

.kama-user-meta strong {
    color: #3f3f46;
    font-weight: 700;
}

.kama-user-account-status {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    padding: 11px 14px;
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    background: #f0fdf4;
}

.kama-user-status-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #16a34a;
    box-shadow: 0 0 0 4px rgba(22, 163, 74, .10);
}

.kama-user-account-status strong {
    display: block;
    color: #166534;
    font-size: .72rem;
    font-weight: 800;
}

.kama-user-account-status small {
    display: block;
    margin-top: 2px;
    color: #65a30d;
    font-size: .62rem;
}


/* =========================================================
   STATISTICS
   ========================================================= */

.kama-user-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.kama-user-stat-card {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 18px;
    border: 1px solid #e7e8eb;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .03);
}

.kama-user-stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border-radius: 11px;
    font-size: 1rem;
}

.kama-user-stat-icon.books {
    background: #fff5f5;
    color: #b30000;
}

.kama-user-stat-icon.review {
    background: #fff8e7;
    color: #b7791f;
}

.kama-user-stat-icon.sales {
    background: #f0f9ff;
    color: #0369a1;
}

.kama-user-stat-icon.revenue {
    background: #f0fdf4;
    color: #15803d;
}

.kama-user-stat-content {
    min-width: 0;
}

.kama-user-stat-content span {
    display: block;
    margin-bottom: 4px;
    color: #71717a;
    font-size: .68rem;
    line-height: 1.3;
}

.kama-user-stat-content strong {
    display: block;
    color: #18181b;
    font-size: 1.15rem;
    font-weight: 800;
}


/* =========================================================
   GENERIC SECTION
   ========================================================= */

.kama-user-section {
    margin-bottom: 20px;
    border: 1px solid #e7e8eb;
    border-radius: 16px;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
}

.kama-user-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 20px 24px;
    border-bottom: 1px solid #e7e8eb;
    background: #fff;
}

.kama-user-section-header > div:first-child > span {
    display: block;
    margin-bottom: 3px;
    color: #b30000;
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
}

.kama-user-section-header h4 {
    margin: 0;
    color: #18181b;
    font-size: 1rem;
    font-weight: 800;
}

.kama-user-section-header p {
    margin: 4px 0 0;
    color: #71717a;
    font-size: .7rem;
}

.kama-user-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 11px;
    background: #fff5f5;
    color: #b30000;
    font-size: 1rem;
}


/* =========================================================
   PERSONAL INFO
   ========================================================= */

.kama-user-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0;
}

.kama-user-info-item {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 19px 24px;
    border-bottom: 1px solid #f0f0f1;
}

.kama-user-info-item:nth-child(odd) {
    border-right: 1px solid #f0f0f1;
}

.kama-user-info-item:nth-last-child(-n+2) {
    border-bottom: 0;
}

.kama-user-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 10px;
    background: #fafafa;
    color: #b30000;
}

.kama-user-info-item > div:last-child {
    min-width: 0;
}

.kama-user-info-item span {
    display: block;
    margin-bottom: 4px;
    color: #71717a;
    font-size: .66rem;
}

.kama-user-info-item strong {
    display: block;
    overflow-wrap: anywhere;
    color: #27272a;
    font-size: .8rem;
    font-weight: 800;
}

.break-email {
    word-break: break-word;
}


/* =========================================================
   BIO
   ========================================================= */

.kama-user-bio {
    padding: 22px 24px;
}

.kama-user-bio p {
    margin: 0;
    color: #52525b;
    font-size: .82rem;
    line-height: 1.75;
    white-space: pre-line;
}

.kama-user-empty-inline {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #a1a1aa;
    font-size: .75rem;
}

.kama-user-empty-inline i {
    color: #b30000;
    font-size: 1rem;
}


/* =========================================================
   BOOKS HEADER
   ========================================================= */

.kama-user-books-count {
    display: flex;
    align-items: baseline;
    gap: 5px;
    padding: 7px 11px;
    border-radius: 999px;
    background: #fff5f5;
    border: 1px solid #f0cfcf;
    color: #b30000;
}

.kama-user-books-count strong {
    font-size: .8rem;
    font-weight: 800;
}

.kama-user-books-count span {
    font-size: .65rem;
    font-weight: 700;
}


/* =========================================================
   BOOKS GRID
   ========================================================= */

.kama-user-books-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    padding: 22px 24px 24px;
}

.kama-user-book-card {
    display: flex;
    min-width: 0;
    min-height: 180px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fff;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.kama-user-book-card:hover {
    transform: translateY(-2px);
    border-color: #f0cfcf;
    box-shadow: 0 10px 25px rgba(15, 23, 42, .07);
}

.kama-user-book-cover {
    width: 118px;
    min-width: 118px;
    background: #f4f4f5;
}

.kama-user-book-cover img {
    display: block;
    width: 100%;
    height: 100%;
    min-height: 180px;
    object-fit: cover;
}

.kama-user-book-content {
    display: flex;
    flex: 1;
    flex-direction: column;
    min-width: 0;
    padding: 15px;
}

.kama-user-book-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 10px;
}

.kama-user-book-type,
.kama-user-book-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 999px;
    padding: 4px 8px;
    font-size: .6rem;
    font-weight: 800;
}

.kama-user-book-type {
    background: #f4f4f5;
    color: #52525b;
}

.kama-user-book-status.published {
    background: #f0fdf4;
    color: #15803d;
}

.kama-user-book-status.review {
    background: #fff8e7;
    color: #b7791f;
}

.kama-user-book-status.pending {
    background: #eff6ff;
    color: #1d4ed8;
}

.kama-user-book-status.draft {
    background: #f4f4f5;
    color: #52525b;
}

.kama-user-book-status.rejected {
    background: #fef2f2;
    color: #b91c1c;
}

.kama-user-book-content h5 {
    margin: 0 0 9px;
    overflow: hidden;
    color: #18181b;
    font-size: .92rem;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.kama-user-book-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 7px 13px;
}

.kama-user-book-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #71717a;
    font-size: .65rem;
}

.kama-user-book-meta i {
    color: #b30000;
}

.kama-user-book-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
    margin-top: auto;
    padding-top: 14px;
}

.kama-user-book-footer small {
    display: block;
    margin-bottom: 2px;
    color: #a1a1aa;
    font-size: .6rem;
}

.kama-user-book-footer strong {
    color: #18181b;
    font-size: .88rem;
    font-weight: 800;
}

.kama-user-book-view {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 11px;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
    background: #fff;
    color: #52525b;
    font-size: .65rem;
    font-weight: 800;
    text-decoration: none;
    transition: all .2s ease;
}

.kama-user-book-view:hover {
    border-color: #b30000;
    background: #b30000;
    color: #fff;
}


/* =========================================================
   EMPTY BOOKS
   ========================================================= */

.kama-user-empty-books {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    min-height: 230px;
    padding: 30px;
    border: 1px dashed #dfe1e5;
    border-radius: 12px;
    background: #fafafa;
    text-align: center;
}

.kama-user-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    margin-bottom: 12px;
    border-radius: 13px;
    background: #fff5f5;
    color: #b30000;
    font-size: 1.2rem;
}

.kama-user-empty-books h5 {
    margin: 0 0 5px;
    color: #3f3f46;
    font-size: .85rem;
    font-weight: 800;
}

.kama-user-empty-books p {
    margin: 0;
    color: #a1a1aa;
    font-size: .7rem;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1100px) {

    .kama-user-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .kama-user-books-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 800px) {

    .kama-user-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .kama-user-profile-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .kama-user-account-status {
        width: 100%;
    }

    .kama-user-info-grid {
        grid-template-columns: 1fr;
    }

    .kama-user-info-item:nth-child(odd) {
        border-right: 0;
    }

    .kama-user-info-item:nth-last-child(-n+2) {
        border-bottom: 1px solid #f0f0f1;
    }

    .kama-user-info-item:last-child {
        border-bottom: 0;
    }

}


@media (max-width: 600px) {

    .kama-user-page-title {
        align-items: flex-start;
    }

    .kama-user-page-icon {
        width: 42px;
        height: 42px;
    }

    .kama-user-page-title h3 {
        font-size: 1.15rem;
    }

    .kama-user-profile-card {
        padding: 18px;
    }

    .kama-user-profile-main {
        align-items: flex-start;
        flex-direction: column;
    }

    .kama-user-name-row h2 {
        font-size: 1.15rem;
    }

    .kama-user-stats {
        grid-template-columns: 1fr;
    }

    .kama-user-section-header {
        padding: 18px;
    }

    .kama-user-info-item {
        padding: 17px 18px;
    }

    .kama-user-books-grid {
        padding: 18px;
    }

}


@media (max-width: 480px) {

    .kama-user-book-card {
        flex-direction: column;
    }

    .kama-user-book-cover {
        width: 100%;
        height: 190px;
    }

    .kama-user-book-cover img {
        min-height: 190px;
    }

    .kama-user-book-content {
        padding: 15px;
    }

    .kama-user-book-top {
        align-items: flex-start;
        flex-direction: column;
    }

}

</style>
@endpush
