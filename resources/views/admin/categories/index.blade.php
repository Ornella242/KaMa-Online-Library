@extends('layouts.admin')

@section('admin-content')

<div class="container">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Gestion des catégories
            </h3>

            <p class="text-black mb-0">
                Gérez les catégories et sous-catégories des livres.
            </p>
        </div>


         <button class="btn btn-submit rounded-pill px-4"
                data-bs-toggle="offcanvas"
                data-bs-target="#createCategoryOffcanvas">

            <i class="bi bi-plus-circle me-2"></i>
            Ajouter une catégorie

        </button>
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

    <div class="card category-filter-card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-danger"></i>
                    </span>

                    <input
                        type="text"
                        name="search"
                        class="form-control border-start-0"
                        placeholder="Rechercher une catégorie ou sous-catégorie..."
                        value="{{ request('search') }}">

                    <button class="btn btn-submit px-4">

                        Rechercher

                    </button>


                </div>


            </form>


        </div>
    </div>


    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>

                            <th>
                                Catégorie
                            </th>

                            <th>
                               Nombre de livres
                            </th>
                            <th>
                                Sous-catégories
                            </th>

                            <th class="text-end">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($categories as $category)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="category-icon">
                                        <i class="bi bi-tag-fill"></i>
                                    </div>

                                    <div>
                                        <h6 class="mb-0 fw-semibold">
                                            {{ $category->name }}
                                        </h6>

                                        @if($category->description)
                                            <small class="text-muted">
                                                {{ Str::limit($category->description,50) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                            </td>

                            <td>
                                <span class="badge bg-danger-subtle text-danger">
                                    {{ $category->books_count }}
                                    livres
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-danger-subtle text-danger">
                                    {{ $category->subcategories_count }}
                                    sous-catégories
                                </span>

                                @if($category->subcategories->count())
                                    <div class="mt-2">
                                        @foreach($category->subcategories as $subcategory)
                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                {{ $subcategory->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td class="text-end">
                                <button type="button"
                                        class="btn btn-sm btn-light rounded-circle"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#editCategory{{ $category->id }}">

                                    <i class="bi bi-pencil-fill text-primary"></i>

                                </button>

                               <button type="button"
                                        class="btn btn-sm btn-light rounded-circle"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#deleteCategory{{ $category->id }}">
                                    <i class="bi bi-trash-fill text-danger"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Offcanvas edit --}}
                        <div class="offcanvas offcanvas-end offcanvas-end category-offcanvas"
                            tabindex="-1"
                            id="editCategory{{ $category->id }}">

                            <div class="offcanvas-header border-bottom">

                                <h5 class="mb-0 fw-bold">

                                    <i class="bi bi-pencil-square text-danger me-2"></i>

                                    Modifier la catégorie

                                </h5>


                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="offcanvas">
                                </button>


                            </div>

                            <form action="{{ route('admin.categories.update',$category) }}"
                                method="POST"
                                class="d-flex flex-column">

                                @csrf
                                @method('PUT')


                                <div class="offcanvas-body">


                                    <div class="mb-4">


                                        <label class="form-label fw-semibold">
                                            Nom de la catégorie
                                        </label>


                                        <input type="text"
                                            name="name"
                                            class="form-control premium-input"
                                            value="{{ $category->name }}"
                                            required>


                                    </div>



                                    <div class="mb-4">


                                        <label class="form-label fw-semibold">
                                            Sous-catégories
                                        </label>


                                        <input type="text"
                                            name="subcategories"
                                            class="form-control premium-input"
                                            value="{{ $category->subcategories->pluck('name')->implode(', ') }}"
                                            placeholder="Ex: Thriller, Policier, Mystère">


                                        <small class="text-muted">

                                            Séparez les sous-catégories par des virgules.

                                        </small>


                                    </div>



                                </div>



                                <div class="offcanvas-footer border-top p-4">


                                    <div class="d-flex gap-2">


                                        <button type="button"
                                                class="btn btn-light rounded-pill flex-fill"
                                                data-bs-dismiss="offcanvas">

                                            Annuler

                                        </button>



                                        <button type="submit"
                                                class="btn btn-submit rounded-pill flex-fill">

                                            Modifier

                                        </button>


                                    </div>


                                </div>


                            </form>

                        </div>

                        {{-- Offcanvas delete --}}
                        <div class="offcanvas offcanvas-end category-offcanvas"
                            tabindex="-1"
                            id="deleteCategory{{ $category->id }}">

                            <div class="offcanvas-header border-bottom">

                                <h4 class="offcanvas-title d-flex align-items-center">
                                    <i class="bi bi-trash-fill text-danger me-2"></i>
                                    Supprimer une catégorie
                                </h4>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="offcanvas">
                                </button>

                            </div>

                            <form action="{{ route('admin.categories.destroy',$category) }}"
                                method="POST"
                                class="d-flex flex-column">

                                @csrf
                                @method('DELETE')

                                <div class="offcanvas-body">
                                    <div class="text-center py-4">
                                        <div class="delete-icon mb-4">
                                            <i class="bi bi-trash3-fill"></i>
                                        </div>

                                        <h4 class="fw-bold mb-3">
                                            Supprimer cette catégorie ?
                                        </h4>

                                        <p class="text-muted mb-4">
                                            Vous êtes sur le point de supprimer définitivement
                                            <strong>{{ $category->name }}</strong>.
                                        </p>

                                        @if($category->subcategories_count)
                                            <div class="alert alert-warning rounded-4">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                Cette catégorie contient
                                                <strong>{{ $category->subcategories_count }}</strong>
                                                sous-catégorie(s).
                                            </div>
                                        @endif

                                        <div class="alert alert-danger rounded-4">
                                            <i class="bi bi-x-circle-fill me-2"></i>
                                            Cette action est irréversible.
                                        </div>
                                    </div>
                                </div>

                                <div class="offcanvas-footer border-top p-4">

                                    <div class="d-flex gap-2">

                                        <button type="button"
                                                class="btn btn-light rounded-pill flex-fill"
                                                data-bs-dismiss="offcanvas">
                                            Annuler
                                        </button>

                                        <button type="submit"
                                                class="btn btn-danger rounded-pill flex-fill">
                                            <i class="bi bi-trash-fill me-2"></i>
                                            Supprimer
                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>
                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-tags fs-1 text-muted"></i>
                                <p class="mt-3 text-muted">
                                    Aucune catégorie trouvée.
                                </p>
                            </td>
                        </tr>

                        
                    @endforelse

                   

                    </tbody>
                </table>
               
            </div>
        </div>

        <div class="card-footer bg-white border-0">

            <div class="category-pagination">

                {{ $categories->onEachSide(1)->links() }}

            </div>

        </div>

    </div>
</div>

{{-- offcanvas  --}}

{{-- create --}}

<div class="offcanvas offcanvas-end category-offcanvas"
     tabindex="-1"
     id="createCategoryOffcanvas">


    <div class="offcanvas-header border-bottom">

        <div>

            <h5 class="offcanvas-title fw-bold mb-1">

                <i class="bi bi-tags-fill text-danger me-2"></i>

                Nouvelle catégorie

            </h5>

            <small class="text-black">
                Ajoutez une catégorie et ses sous-catégories
            </small>

        </div>


        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
        </button>
    </div>

    <form action="{{ route('admin.categories.store') }}"
          method="POST"
          class="h-100 d-flex flex-column">

        @csrf

        <div class="offcanvas-body">
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Nom de la catégorie
                </label>


                <input type="text"
                       name="name"
                       class="form-control premium-input"
                       placeholder="Ex: Romance"
                       required>

            </div>


            <div id="subcategoryContainer">
                <div class="subcategory-item mb-3">
                    <label class="form-label fw-semibold">
                       Liste des sous-catégories
                    </label>
                    <p class="text-red fw-semibold">
                        Séparez les sous-catégories par des virgules.
                    </p>
                    <div class="input-group align-items-stretch"> 
                        <input type="text"
                            name="subcategories[]"
                            class="form-control premium-input"
                            placeholder="Ex: Thriller, Policier, Mystère">
                    </div>
 
                </div>

            </div>


        </div>



        <div class="offcanvas-footer border-top p-4">


            <div class="d-flex gap-2">


                <button type="button"
                        class="btn btn-light rounded-pill flex-fill"
                        data-bs-dismiss="offcanvas">

                    Annuler

                </button>


                <button type="submit"
                        class="btn btn-submit rounded-pill flex-fill">

                    Enregistrer

                </button>


            </div>


        </div>


    </form>


</div>

{{-- Script create --}}
<script>
    const addSubcategoryBtn = document.getElementById('addSubcategory');

    if(addSubcategoryBtn){
        addEventListener('click', function(){


            let container = document.getElementById('subcategoryContainer');


            let div = document.createElement('div');


            div.className = "input-group mb-2";


            div.innerHTML = `

                <input type="text"
                    name="subcategories[]"
                    class="form-control"
                    placeholder="Nom de la sous-catégorie">


                <button type="button"
                        class="btn btn-outline-danger delete-subcategory">

                    <i class="bi bi-trash"></i>

                </button>

            `;


            container.appendChild(div);


        });
    }


    document.addEventListener('click',function(e){


        if(e.target.closest('.delete-subcategory')){


            e.target.closest('.input-group').remove();


        }


    });
</script>

@endsection