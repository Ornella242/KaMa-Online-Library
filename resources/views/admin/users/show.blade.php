@extends('layouts.admin')

@section('admin-content')
    @if($user->role->name === 'writer' || $user->role->name === 'admin')
        <div class="row g-4 mb-4">
            <!-- Counter item -->
            <div class="col-lg-3">
                <div class="card card-body border border-primary bg-primary bg-opacity-10 border-opacity-25 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h3 class="mb-0 fw-bold">{{$data['totalBooks']}}</h3>
                            <span class="mb-0 h6 fw-light">Livres au total</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle flex-shrink-0 bg-primary text-white mb-0"><i class="bi bi-book-half fa-fw"></i></div>
                    </div>
                </div>
            </div>

             <div class="col-lg-3">
                <div class="card card-body border border-danger bg-danger bg-opacity-10 border-opacity-25 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h5 class="mb-0 fw-bold">{{$data['BooksUnderreview']}} Livres</h5>
                            <span class="mb-0 h6 fw-light">En attente de validation </span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle flex-shrink-0 bg-danger text-white mb-0"><i class="bi bi-hourglass-split fa-fw"></i></div>
                    </div>
                </div>
            </div>

            <!-- Counter item -->
            <div class="col-lg-3">
                <div class="card card-body border border-warning bg-warning bg-opacity-10 border-opacity-25 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $data['totalSales'] }}</h3>
                            <span class="mb-0 h6 fw-light">Livres vendus</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle flex-shrink-0 bg-warning text-white mb-0"><i class="bi bi-journal-check fa-fw"></i></div>
                    </div>
                </div>
            </div>

            <!-- Counter item -->
            <div class="col-lg-3">
                <div class="card card-body border border-success bg-success bg-opacity-10 border-opacity-25 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $data['totalRevenue'] }} $</h3>
                            <span class="mb-0 h6 fw-light">Total de gain</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle flex-shrink-0 bg-success text-white mb-0"><i class="fa-solid fa-money-bill-trend-up fa-fw"></i></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        <div class="kama-user-detail">
            <div class="row g-4 mb-5">
                <!-- User info START -->
                <div class="col-md-4 col-xxl-3">

                    <div class="card profile-card h-100">


                        <!-- Header profil -->
                        <div class="profile-header">


                            <div class="avatar avatar-xl mb-3 mx-auto">

                                <img 
                                    class="avatar-img rounded-circle border border-3 border-white shadow"
                                    src="{{ $user->avatar
                                        ? asset('storage/'.$user->avatar)
                                        : asset('assets/images/avatar/01.jpg') }}"
                                    alt="avatar">

                            </div>


                            <h4 class="profile-name mb-2">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </h4>


                            <span class="badge role-badge">
                                @if($user->role->name === 'writer')
                                    Auteur KaMa
                                @elseif($user->role->name === 'admin')
                                    Administrateur
                                @else
                                    Lecteur
                                @endif
                            </span>


                        </div>



                        <!-- Informations -->
                        <div class="card-body p-4">


                            <h6 class="profile-title mb-4">
                                Informations de contact
                            </h6>



                            <!-- Email -->
                            <div class="profile-info">


                                <div class="profile-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>


                                <div class="profile-content">

                                    <small>Email</small>

                                    <p class="email-text">
                                        {{ $user->email }}
                                    </p>

                                </div>


                            </div>




                            <!-- Téléphone -->
                            <div class="profile-info">


                                <div class="profile-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>


                                <div class="profile-content">

                                    <small>Téléphone</small>

                                    <p>
                                        {{ $user->phone ?? '-' }}
                                    </p>

                                </div>


                            </div>





                            <!-- Pays -->
                            <div class="profile-info">


                                <div class="profile-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>


                                <div class="profile-content">

                                    <small>Pays</small>

                                    <p>
                                        {{ $user->country->name ?? '-' }}
                                    </p>

                                </div>


                            </div>



                        </div>


                    </div>

                </div>
                <!-- Agent info END -->

                <div class="col-md-8 col-xxl-9">

                    <!-- Personal info START -->
                    <div class="card personal-card shadow">

                        <!-- Card header -->
                        <div class="card-header border-bottom bg-table-red">
                            <h5 class="mb-0">
                                Information personnelle
                            </h5>
                        </div>


                        <!-- Card body -->
                        <div class="card-body">


                            <div class="table-responsive">

                                <table class="table profile-table align-middle mb-0">

                                    <tbody>


                                        <tr>

                                            <th>
                                                <i class="bi bi-person-circle me-2"></i>
                                                Nom complet
                                            </th>

                                            <td>
                                                {{ $user->firstname }} {{ $user->lastname }}
                                            </td>


                                            <th>
                                                <i class="bi bi-envelope me-2"></i>
                                                Email
                                            </th>

                                            <td>
                                                <div class="email-column">
                                                    {{ $user->email }}
                                                </div>
                                            </td>

                                        </tr>



                                        <tr>

                                            <th>
                                                <i class="bi bi-telephone me-2"></i>
                                                Téléphone
                                            </th>

                                            <td>
                                                {{ $user->phone ?? '-' }}
                                            </td>


                                            <th>
                                                <i class="bi bi-gender-ambiguous me-2"></i>
                                                Genre
                                            </th>

                                            <td>
                                                {{ ucfirst($user->gender) }}
                                            </td>

                                        </tr>



                                        <tr>

                                            <th>
                                                <i class="bi bi-geo-alt me-2"></i>
                                                Pays
                                            </th>

                                            <td>
                                                {{ $user->country->name ?? '-' }}
                                            </td>


                                            <th>
                                                <i class="bi bi-calendar-event me-2"></i>
                                                Membre KaMa depuis
                                            </th>

                                            <td>
                                                {{ $user->created_at->format('d M Y') }}
                                            </td>

                                        </tr>




                                        @if($user->role->name === 'writer' || $user->role->name === 'admin')

                                        <tr>

                                            <th class="align-top">

                                                <i class="bi bi-journal-text me-2"></i>
                                                Biographie

                                            </th>


                                            <td colspan="3">

                                                <div class="bio-text">

                                                    {{ $user->bio ?: 'Aucune biographie renseignée.' }}

                                                </div>

                                            </td>

                                        </tr>

                                        @endif


                                    </tbody>

                                </table>

                            </div>


                        </div>

                    </div>

                </div>
            </div> <!-- Row END -->
        </div>
        <!-- Hotel list START -->


        @if($user->role->name === 'writer' || $user->role->name === 'admin')
            <!-- Title -->
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">

                    <h4 class="mb-0 d-flex align-items-center gap-2">
                        <span class="kama-books-title-icon">
                            <i class="bi bi-book-half"></i>
                        </span>
                        Livres ajoutés
                    </h4>

                    <span class="badge kama-book-count">
                        {{ $user->books->count() }} livres
                    </span>

                </div>
            </div>

            <div class="row g-4 kama-user-books kama-books-mobile-slider mt-4">
                
                @forelse($user->books as $book)
                    <div class="col-lg-6 kama-book-item">
                        <div class="kama-book-card">
                            <!-- Cover -->
                            <div class="kama-book-cover">
                                <img 
                                src="{{ $book->cover_image 
                                    ? asset('storage/'.$book->cover_image)
                                    : asset('assets/images/book-placeholder.jpg') }}"
                                alt="{{ $book->title }}">
                            </div>

                            <!-- Content -->
                            <div class="kama-book-content">
                                
                                <h5 class="mb-1">
                                    {{ $book->title }}
                                </h5>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="kama-book-badge category">
                                        <i class="bi bi-tag"></i>
                                        {{ $book->category->name ?? 'Sans catégorie' }}
                                    </span>


                                    <span class="kama-book-badge type">
                                        <i class="bi bi-file-earmark-text"></i>
                                        {{ ucfirst($book->type ?? 'ebook') }}
                                    </span>


                                    <span class="kama-book-badge pages">
                                        @if($book->type == 'ebook')
                                            <i class="bi bi-files"></i>
                                            {{ $book->pages ?? 0 }} pages
                                        @else
                                            <i class="bi bi-headphones"></i>
                                            {{ $book->duration }} durée
                                        @endif
                                    </span>

                                    <span class="kama-book-badge status 
                                        {{ $book->status == 'published' ? 'published' : 
                                        ($book->status == 'pending' ? 'pending' : 
                                        ($book->status == 'draft' ? 'draft' : 'rejected')) }}">

                                        @if($book->status == 'published')

                                            <i class="bi bi-check-circle-fill"></i>
                                            Publié

                                        @elseif($book->status == 'pending')

                                            <i class="bi bi-clock-fill"></i>
                                            En attente

                                        @elseif($book->status == 'draft')

                                            <i class="bi bi-pencil-fill"></i>
                                            Brouillon

                                        @elseif($book->status == 'under_review')
                                            <i class="bi bi-hourglass-split"></i>
                                            Sous-vérification
                                        @else

                                            <i class="bi bi-x-circle-fill"></i>
                                            Rejeté

                                        @endif

                                    </span>
                                </div>



                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            Prix
                                        </small>

                                        <h5 class="mb-0 fw-bold">
                                            {{ number_format($book->price,2) }} $
                                        </h5>
                                    </div>

                                    <a href="#"
                                    class="btn btn-sm kama-book-view">
                                        <i class="bi bi-eye-fill me-1"></i>
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty

                    <div class="col-12">
                        <div class="kama-empty-books">
                            <i class="bi bi-book"></i>
                            <p>
                                Aucun livre publié pour le moment.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        @endif
@endsection