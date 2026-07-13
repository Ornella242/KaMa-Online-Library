@extends('layouts.admin')

@section('admin-content')
    @if($user->role->name === 'writer' || $user->role->name === 'admin')
        <div class="row g-4 mb-4">
            <!-- Counter item -->
            <div class="col-lg-4">
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

            <!-- Counter item -->
            <div class="col-lg-4">
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
            <div class="col-lg-4">
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
                        <div class="card bg-light">
                            <!-- Card body -->
                            <div class="card-body text-center">
                                <!-- Avatar Image -->
                                <div class="avatar avatar-xl flex-shrink-0 mb-3">
                                    <img class="avatar-img rounded-circle" src="{{ $user->avatar
                                            ? asset('storage/'.$user->avatar)
                                            : asset('assets/images/avatar/01.jpg') }}" alt="avatar">
                                </div>
                                <!-- Title -->
                                <h5 class="mb-2">{{ $user->firstname }}</h5>
                            </div>
                            <!-- Card footer -->
                            <div class="card-footer bg-light border-top">
                                <h6 class="mb-3 fw-semibold text-red">Contact</h6>
                                <!-- Email id -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-md bg-mode h6 mb-0 rounded-circle flex-shrink-0"><i class="bi bi-envelope-fill text-red"></i></div>
                                    <div class="ms-2">
                                        <small>Adresse email</small>
                                        <h6 class="fw-normal small mb-0 kama-email"><a href="">{{ $user->email }}</a></h6>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-md bg-mode h6 mb-0 rounded-circle flex-shrink-0"><i class="bi bi-telephone-fill text-red"></i></div>
                                    <div class="ms-2">
                                        <small>Phone</small>
                                        <h6 class="fw-normal small mb-0"><a href="">{{ $user->phone }}</a></h6>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-md bg-mode h6 mb-0 rounded-circle flex-shrink-0"><i class="bi bi-geo-alt-fill text-red"></i></div>
                                    <div class="ms-2">
                                        <small>Pays</small>
                                        <h6 class="fw-normal small mb-0">{{ $user->country }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Agent info END -->

                    <div class="col-md-8 col-xxl-9 <div class="col-md-8 col-xxl-9">
                        <!-- Personal info START -->
                        <div class="card shadow">
                            <!-- Card header -->
                            <div class="card-header border-bottom bg-table-red">
                                <h5 class="mb-0">Information personnelle</h5>
                            </div>
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <!-- Information item -->
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-borderless">
                                            <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Nom complet:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">{{ $user->firstname }} {{ $user->lastname }}</span>
                                            </li>


                                            <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Numero de telephone:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">{{ $user->phone }}</span>
                                            </li>

                                            <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Pays:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">{{ $user->country  }}</span>
                                            </li>

                                        </ul>
                                    </div>

                                    <!-- Information item -->
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-borderless">
                                            <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Adresse email:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">{{ $user->email }}</span>
                                            </li>

                                            <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Genre:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">{{ $user->gender }}</span>
                                            </li>

                                           <li class="list-group-item mb-3">
                                                <span class="text-red fw-semibold">Membre KaMa depuis:</span>
                                                <span class="h6 fw-normal ms-1 mb-0">
                                                    {{ $user->created_at->format('d M Y') }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>


                                    @if($user->role->name === 'writer' || $user->role->name === 'admin')
                                        <!-- Information item -->
                                        <div class="col-12">
                                            <ul class="list-group list-group-borderless">
                                                <li class="list-group-item">
                                                    <span class="text-red fw-semibold">Biographie: </span>
                                                    <p class="h6 fw-normal mb-0">{{ $user->bio }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Personal info END -->
                    </div>
                </div> <!-- Row END -->
            </div>
			<!-- Hotel list START -->


			@if($user->role->name === 'writer' || $user->role->name === 'admin')

                <div class="row g-4 kama-user-books mt-4">
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


                    @forelse($user->books as $book)
                        <div class="col-lg-6">
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