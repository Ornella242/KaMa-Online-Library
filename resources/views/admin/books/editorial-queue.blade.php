@extends('layouts.admin')

@section('admin-content')
<div class="editorial-queue">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Livres en cours de vérification
            </h3>

            <p class="text-black mb-0">
                Gérez tous les livres ajouter sur KaMa.
            </p>
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        @endif
    </div>

    <div class="row g-4 mb-5 kama-editorial-stats">

    <!-- En verification -->
    <div class="col-md-6 col-xl-4">
        <div class="card card-body shadow p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="fw-semibold text-white">
                        En vérification
                    </span>

                    <h3 class="mb-0 mt-2">
                        {{ $reviewBooks }}
                    </h3>

                </div>


                <div class="icon-lg rounded-circle flex-shrink-0 bg-info bg-opacity-10 text-info">

                    <i class="bi bi-search"></i>

                </div>

            </div>

        </div>
    </div>


    <!-- Publies -->
    <div class="col-md-6 col-xl-4">
        <div class="card card-body shadow p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <span class="fw-semibold text-white">
                        Publiés ce mois
                    </span>

                    <h3 class="mb-0 mt-2">
                        {{ $publishedBooks }}
                    </h3>

                </div>


                <div class="icon-lg rounded-circle flex-shrink-0 bg-success bg-opacity-10 text-success">

                    <i class="bi bi-check-circle"></i>

                </div>

            </div>

        </div>
    </div>


    <!-- Rejetes -->
    <div class="col-md-6 col-xl-4">
        <div class="card card-body shadow p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-semibold text-white">
                        Rejetés ce mois
                    </span>

                    <h3 class="mb-0 mt-2">
                        {{ $rejectedBooks }}
                    </h3>
                </div>

                <div class="icon-lg rounded-circle flex-shrink-0 bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>


   @if($books->count())

    <div class="editorial-grid">

        @foreach($books as $book)

        <div class="editorial-card">

            <div class="editorial-cover">

                <img
                src="{{ asset('storage/'.$book->cover_image) }}"
                alt="{{ $book->title }}">

                <span class="editorial-status">
                    Sous vérification
                </span>

            </div>


            <div class="editorial-content">

                <h4>
                    {{ $book->title }}
                </h4>


                <p class="editorial-author">
                    <i class="bi bi-person"></i>
                    {{ $book->author->firstname }}
                    {{ $book->author->lastname }}
                </p>


                <div class="editorial-actions">

                    <a
                    href="{{ route('admin.books.show',$book) }}"
                    class="btn-view">

                        <i class="bi bi-eye"></i>

                    </a>


                    <form 
                    action="{{ route('admin.books.publish',$book) }}" 
                    method="POST"
                    onsubmit="return confirm('Publier ce livre sur KaMa ?')">

                        @csrf

                        <button class="btn-publish">

                            <i class="bi bi-check2-circle"></i>
                            Publier

                        </button>

                    </form>


                    <button
                    class="btn-reject"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#rejectBook{{ $book->id }}">

                        <i class="bi bi-x-circle"></i>
                        Rejeter

                    </button>

                </div>

            </div>

        </div>


        <!-- OFFCANVAS REJET -->

        <div class="offcanvas offcanvas-end category-offcanvas"
        tabindex="-1"
        id="rejectBook{{ $book->id }}">

            <div class="offcanvas-header">

                <h5>
                    Rejeter le livre
                </h5>

                <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
                </button>

            </div>


            <div class="offcanvas-body">

                <form
                method="POST"
                action="{{ route('admin.books.reject',$book) }}">

                    @csrf

                    <div class="mb-4">

                        <label class="form-label">
                            Motif du rejet
                        </label>


                        <textarea
                        class="form-control"
                        rows="8"
                        name="reason"
                        placeholder="Expliquez la raison du rejet..."
                        required></textarea>

                    </div>


                    <button
                    class="btn btn-danger w-100 rounded-pill">

                        Confirmer le rejet

                    </button>

                </form>

            </div>

        </div>


        @endforeach

    </div>


@else


    <div class="editorial-empty-wrapper">
        <div class="editorial-empty">
            <div class="empty-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h4>
                Aucun livre en attente
            </h4>

            <p>
                Tous les livres soumis ont été traités.
                La file éditoriale est à jour.
            </p>
        </div>
    </div>
@endif

</div>
@endsection