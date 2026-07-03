@extends('layouts.reader')

@section('reader-content')

<!-- Wishlist START -->
<div class="card border bg-transparent">

    <!-- Header -->
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
        <h4 class="card-header-title mb-0">Ma Wishlist</h4>

        <button class="btn btn-sm btn-danger">
            <i class="bi bi-trash me-2"></i>
            Tout supprimer
        </button>
    </div>

    <!-- Body -->
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Prix</th>
                        <th>Note</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Livre 1 -->
                    <tr>

                        <td>
                            <div class="d-flex align-items-center gap-3">

                                <img src="{{ asset('assets/images/category/une/4by3/book2.jpg') }}"
                                     width="65"
                                     class="rounded"
                                     alt="">

                                <div>
                                    <h6 class="mb-1">Atomic Habits</h6>
                                    <small class="text-muted">
                                        Développement personnel
                                    </small>
                                </div>

                            </div>
                        </td>

                        <td>James Clear</td>

                        <td>
                            <strong>22 $</strong>
                        </td>

                        <td>
                            ⭐ 4.9
                        </td>

                        <td class="text-end">

                            <a href="#" class="btn btn-sm btn-light">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-success">
                                <i class="bi bi-cart-plus"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                    <!-- Livre 2 -->
                    <tr>

                        <td>
                            <div class="d-flex align-items-center gap-3">

                                <img src="{{ asset('assets/images/category/une/4by3/book3.jpg') }}"
                                     width="65"
                                     class="rounded"
                                     alt="">

                                <div>
                                    <h6 class="mb-1">Deep Work</h6>
                                    <small class="text-muted">
                                        Productivité
                                    </small>
                                </div>

                            </div>
                        </td>

                        <td>Cal Newport</td>

                        <td>
                            <strong>18 $</strong>
                        </td>

                        <td>
                            ⭐ 4.8
                        </td>

                        <td class="text-end">

                            <a href="#" class="btn btn-sm btn-light">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-success">
                                <i class="bi bi-cart-plus"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
<!-- Wishlist END -->

@endsection
