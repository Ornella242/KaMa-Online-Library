@extends('layouts.reader')

@section('reader-content')

<div class="card border-0 shadow-sm rounded-4">

    <!-- Header -->
    <div class="card-header bg-book-table border-bottom py-4 d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">Mes livres</h4>
            <small class="text-black fs-6">
                Retrouvez tous vos livres achetés.
            </small>
        </div>

        {{-- <a href="#" class="btn btn-danger rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>
            Ajouter un livre
        </a> --}}

    </div>

    <!-- Body -->
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 book-table">

                <thead>

                    <tr>

                        <th>Livre</th>

                        <th>Auteur</th>

                        <th>Prix</th>

                        <th>Statut</th>

                        <th class="text-end">Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <img src="{{ asset('assets/images/category/une/4by3/book2.jpg') }}"
                                     class="book-cover me-3">

                                <div>

                                    <h6 class="mb-1 fw-bold">
                                        Atomic Habits
                                    </h6>

                                    <span class="text-black small">
                                        Développement personnel
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>James Clear</td>

                        <td>

                            <span class="fw-bold text-danger">
                                22 $
                            </span>

                        </td>

                        <td>

                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                Terminé
                            </span>

                        </td>

                        <td class="text-end">

                            <a href="#" class="action-btn view-btn">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="#" class="action-btn edit-btn">
                                <i class="bi bi-download"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <img src="{{ asset('assets/images/category/une/4by3/book3.jpg') }}"
                                     class="book-cover me-3">

                                <div>

                                    <h6 class="mb-1 fw-bold">
                                        Deep Work
                                    </h6>

                                    <span class="text-black small">
                                        Productivité
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>Cal Newport</td>

                        <td>

                            <span class="fw-bold text-danger">
                                18 $
                            </span>

                        </td>

                        <td>

                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">
                                En cours de lecture
                            </span>

                        </td>

                        <td class="text-end">

                            <a href="#" class="action-btn view-btn">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="#" class="action-btn edit-btn">
                                <i class="bi bi-download"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                      <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <img src="{{ asset('assets/images/category/une/4by3/book3.jpg') }}"
                                     class="book-cover me-3">

                                <div>

                                    <h6 class="mb-1 fw-bold">
                                        Deep Work
                                    </h6>

                                    <span class="text-black small">
                                        Productivité
                                    </span>

                                </div>

                            </div>

                        </td>

                        <td>Cal Newport</td>

                        <td>

                            <span class="fw-bold text-danger">
                                18 $
                            </span>

                        </td>

                        <td>

                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">
                                En cours de lecture
                            </span>

                        </td>

                        <td class="text-end">

                            <a href="#" class="action-btn view-btn">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="#" class="action-btn edit-btn">
                                <i class="bi bi-download"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection