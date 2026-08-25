@extends('layouts.admin')

@section('title', 'Modifier le livre')
@section('page-title', 'Modifier le livre')

@section('admin-content')
{{-- Admin peut toujours tout modifier, jamais de read-only --}}

<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-2"></i>
            Retour à mes livres
        </a>
        <h4 class="fw-bold mt-3">
            Modifier le livre <span class="text-danger">{{ $book->title }}</span>
        </h4>
    </div>

    <div id="stepper" class="bs-stepper stepper-outline">
        <!-- Step Buttons -->
        <div class="bs-stepper-header" role="tablist">
            <div class="step" data-target="#step-1">
                <div class="text-center">
                    <button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger1" aria-controls="step-1">
                        <span class="bs-stepper-circle">1</span>
                    </button>
                    <h6 class="bs-stepper-label d-none d-md-block">Information Générale</h6>
                </div>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#step-2">
                <div class="text-center">
                    <button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger2" aria-controls="step-2">
                        <span class="bs-stepper-circle">2</span>
                    </button>
                    <h6 class="bs-stepper-label d-none d-md-block">Contenu et fichiers</h6>
                </div>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#step-3">
                <div class="text-center">
                    <button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger3" aria-controls="step-3">
                        <span class="bs-stepper-circle">3</span>
                    </button>
                    <h6 class="bs-stepper-label d-none d-md-block">Récapitulatif</h6>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

        @if($book->status === 'revision_required')
            <div class="alert alert-warning mt-4">
                <h6><i class="bi bi-exclamation-triangle"></i> Corrections demandées par l'équipe éditoriale</h6>
                <p class="mb-0">{{ $book->rejection_reason }}</p>
            </div>
        @endif

        <!-- Step content -->
        <div class="bs-stepper-content p-0 pt-4 pt-md-5">
            <div class="row g-4">
                <div class="col-12">
                    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- ===================== STEP 1 ===================== -->
                        <div id="step-1" role="tabpanel" class="content fade" aria-labelledby="steppertrigger1">
                            <div class="vstack gap-4">
                                <div class="card book-card">
                                    <div class="card-header border-bottom">
                                        <h4 class="mb-0">
                                            <i class="bi bi-book text-danger me-2"></i>
                                            Détails du livre
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <!-- Cover -->
                                            <div class="col-lg-4">
                                                <div class="cover-upload-box">
                                                    <div class="cover-preview">
                                                        <img id="coverPreviewImage"
                                                            src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : '' }}"
                                                            alt="Aperçu couverture"
                                                            style="{{ $book->cover_image ? '' : 'display:none;' }}">
                                                        <div id="coverPlaceholder"
                                                            style="{{ $book->cover_image ? 'display:none;' : '' }}">
                                                            <i class="bi bi-image"></i>
                                                            <span>Aperçu couverture</span>
                                                        </div>
                                                    </div>
                                                    <label class="btn btn-outline-danger w-100 mt-3">
                                                        <i class="bi bi-upload me-2"></i>
                                                        Modifier la couverture
                                                        <input type="file" id="coverImageInput" name="cover_image" hidden accept="image/png,image/jpeg">
                                                    </label>
                                                    <small class="text-muted d-block mt-2">JPG ou PNG recommandé (600x900px) / 2MB</small>
                                                </div>
                                            </div>

                                            <!-- Informations -->
                                            <div class="col-lg-8">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label">Titre du livre *</label>
                                                        <input type="text" name="title" class="form-control book-input" value="{{ old('title', $book->title) }}">
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Auteur</label>
                                                        <div class="author-box">
                                                            <i class="bi bi-person-circle"></i>
                                                            <span>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Type de publication *</label>
                                                        <div class="book-type-selector">
                                                            <label class="type-card">
                                                                <input type="radio" name="type" value="ebook" {{ old('type', $book->type) == 'ebook' ? 'checked' : '' }}>
                                                                <div>
                                                                    <i class="bi bi-file-earmark-text"></i>
                                                                    <strong>Ebook</strong>
                                                                    <small>PDF téléchargeable</small>
                                                                </div>
                                                            </label>
                                                            <label class="type-card">
                                                                <input type="radio" name="type" value="audio" {{ old('type', $book->type) == 'audio' ? 'checked' : '' }}>
                                                                <div>
                                                                    <i class="bi bi-headphones"></i>
                                                                    <strong>Livre audio</strong>
                                                                    <small>Format audio MP3</small>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Catégorie *</label>
                                                        <select name="category_id" id="category_id" class="form-select js-choice">
                                                            <option value="">Choisir une catégorie</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Sous-catégorie *</label>
                                                        <select name="subcategory_id" id="subcategory_id" class="form-select">
                                                            <option value="">Sous-catégorie</option>
                                                            @foreach($subcategories as $subcategory)
                                                                <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $book->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                                    {{ $subcategory->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4" id="pagesField" style="display:none;">
                                                        <label class="form-label">Nombre de pages *</label>
                                                        <input type="number" name="pages" id="pagesInput" value="{{ old('pages', $book->pages) }}" class="form-control book-input" placeholder="Ex: 120">
                                                    </div>

                                                    <div class="col-md-4" id="durationField" style="display:none;">
                                                        <label class="form-label">Durée du livre audio *</label>
                                                        <input type="text" name="duration" id="durationInput" value="{{ old('duration', $book->duration) }}" class="form-control book-input" placeholder="Ex: 02:35:00" inputmode="numeric" maxlength="9" pattern="[0-9]{1,3}:[0-5][0-9]:[0-5][0-9]">
                                                        <small class="text-muted">Format obligatoire : heures:minutes:secondes (ex. 02:35:00)</small>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Langue *</label>
                                                        <select name="language" class="form-select js-choice">
                                                            <option value="">Choisir une langue</option>
                                                            <option value="Français" {{ old('language', $book->language) == 'Français' ? 'selected' : '' }}>Français</option>
                                                            <option value="Anglais" {{ old('language', $book->language) == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Année de publication *</label>
                                                        <select name="publication_year" class="form-select book-input">
                                                            <option value="">Choisir une année</option>
                                                            @for($y = date('Y'); $y >= 1900; $y--)
                                                                <option value="{{ $y }}" @selected((string) old('publication_year', $book->publication_year) === (string) $y)>{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Prix en € *</label>
                                                        <input type="number" name="price" value="{{ old('price', $book->price) }}" class="form-control book-input" placeholder="5000">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end wizard-actions wizard-actions-end">
                                    <button type="button" id="step1NextBtn" class="btn btn-danger next-btn wizard-action-btn wizard-action-primary">
                                        Continuer <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- STEP 1 END -->

                        <!-- ===================== STEP 2 ===================== -->
                        <div id="step-2" role="tabpanel" class="content fade" aria-labelledby="steppertrigger2">
                            <div class="vstack gap-4">
                                <div class="step-title-box">
                                    <span><i class="bi bi-journal-text"></i> Contenu du livre</span>
                                    <h4>Présentez votre ouvrage aux lecteurs</h4>
                                    <p>Ajoutez une description claire de votre livre ainsi que les fichiers nécessaires à sa publication sur KaMa Online Library.</p>
                                </div>

                                <!-- Description card -->
                                <div class="card book-card">
                                    <div class="card-header border-bottom">
                                        <h4 class="mb-0 text-white">
                                            <i class="bi bi-card-text text-danger me-2"></i>
                                            Présentation du livre
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <label class="form-label">Description courte *</label>
                                                <textarea name="short_description" rows="4" class="form-control book-input" placeholder="Une courte présentation qui apparaîtra sur la fiche du livre...">{{ old('short_description', $book->short_description) }}</textarea>
                                            </div>

                                            @if($book->type == 'ebook')
                                                <div class="col-12">
                                                    <label class="form-label">Type d'aperçu</label>
                                                    <select name="preview_type" id="previewType" class="form-select">
                                                        <option value="text" {{ $book->preview_type == 'text' ? 'selected' : '' }}>Extrait texte</option>
                                                        <option value="pages" {{ $book->preview_type == 'pages' ? 'selected' : '' }}>Pages du livre</option>
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-12 {{ $book->preview_type == 'pages' ? 'd-none' : '' }}" id="textPreview">
                                                <label class="form-label">Extrait / Morceau *</label>
                                                <div class="bg-light border border-bottom-0 rounded-top py-3 quilltoolbar">
                                                    <span class="ql-formats">
                                                        <button class="ql-bold"></button>
                                                        <button class="ql-italic"></button>
                                                        <button class="ql-underline"></button>
                                                    </span>
                                                    <span class="ql-formats">
                                                        <button class="ql-list" value="ordered"></button>
                                                        <button class="ql-list" value="bullet"></button>
                                                    </span>
                                                    <span class="ql-formats">
                                                        <button class="ql-link"></button>
                                                    </span>
                                                </div>
                                                <div class="bg-white border rounded-bottom h-300px quilleditor">{!! $book->long_description !!}</div>
                                                <input type="hidden" name="long_description" id="long_description" value="{{ old('long_description', $book->long_description) }}">
                                            </div>

                                            <div class="col-12 {{ $book->preview_type == 'text' ? 'd-none' : '' }}" id="pagesPreview">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Première page</label>
                                                        <input type="number" name="preview_start_page" id="previewStartPage" min="1" class="form-control" value="{{ old('preview_start_page', $book->preview_start_page) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Dernière page</label>
                                                        <input type="number" name="preview_end_page" id="previewEndPage" min="1" class="form-control" value="{{ old('preview_end_page', $book->preview_end_page) }}">
                                                    </div>
                                                </div>
                                                <small class="text-black" id="pagesPreviewHint">Vous pouvez sélectionner au maximum 5 pages consécutives.</small>
                                                <small class="text-danger d-none" id="pagesPreviewError">L'écart entre la première et la dernière page ne doit pas dépasser 5 pages.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- File upload card -->
                                <div class="card book-card">
                                    <div class="card-header border-bottom">
                                        <h4 class="mb-0 text-white">
                                            <i class="bi bi-cloud-arrow-up text-danger me-2"></i>
                                            Fichiers du livre
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label">
                                                    Fichier du livre
                                                    @if(!$book->file_path)<span class="text-danger">*</span>@endif
                                                </label>

                                                @if($book->file_path)
                                                    <small class="text-muted d-block mb-3">Voici le fichier téléversé lors de la création. Vous pouvez le consulter ci-dessous ou le remplacer.</small>
                                                @endif

                                                <div class="upload-box book-upload {{ $book->file_path ? 'has-existing-file' : '' }}" id="uploadDropZone"
                                                    data-existing-file="{{ $existingFileName ?: '' }}"
                                                    data-existing-url="{{ $existingPreviewUrl ?: '' }}"
                                                    data-book-type="{{ $book->type }}">

                                                    @if($book->file_path && $existingPreviewUrl)
                                                        <div id="existingFilePanel" class="existing-file-panel">
                                                            <div class="existing-file-meta">
                                                                <span class="existing-file-badge">
                                                                    <i class="bi {{ $isAudioBook ? 'bi-headphones' : 'bi-file-earmark-pdf-fill' }}"></i>
                                                                    {{ strtoupper($book->file_type ?: ($isAudioBook ? 'mp3' : 'pdf')) }}
                                                                </span>
                                                                <div>
                                                                    <strong id="existingFileLabel">{{ $existingFileName }}</strong>
                                                                    <small>
                                                                        Fichier actuel
                                                                        @if($existingFileSize) · {{ $existingFileSize }} @endif
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <div class="existing-file-preview">
                                                                @if($isAudioBook)
                                                                    <div class="existing-audio-preview">
                                                                        <div class="upload-success-icon mx-auto mb-3">
                                                                            <i class="bi bi-headphones"></i>
                                                                        </div>
                                                                        <p class="mb-3 text-muted">Écoutez le livre audio déjà enregistré</p>
                                                                        <audio id="existingAudioPlayer" controls class="w-100" style="max-width: 520px;" preload="metadata">
                                                                            <source src="{{ $existingPreviewUrl }}" type="audio/mpeg">
                                                                        </audio>
                                                                    </div>
                                                                @else
                                                                    <iframe id="existingPdfPreview" class="existing-pdf-preview"
                                                                        src="{{ $existingPreviewUrl }}#toolbar=1&navpanes=0"
                                                                        title="Aperçu du livre téléversé">
                                                                    </iframe>
                                                                @endif
                                                            </div>

                                                            <div class="upload-actions mt-3">
                                                                <button type="button" class="upload-action-btn upload-action-preview" id="previewBookBtn">
                                                                    <span class="upload-action-icon">
                                                                        <i class="{{ $isAudioBook ? 'bi bi-play-circle-fill' : 'bi bi-arrows-fullscreen' }}" id="previewActionIcon"></i>
                                                                    </span>
                                                                    <span class="upload-action-label" id="previewActionLabel">
                                                                        {{ $isAudioBook ? 'Écouter en grand' : "Agrandir l'aperçu" }}
                                                                    </span>
                                                                </button>
                                                                <button type="button" class="upload-action-btn upload-action-change" id="changeFileBtn">
                                                                    <span class="upload-action-icon"><i class="bi bi-arrow-repeat"></i></span>
                                                                    <span class="upload-action-label">Remplacer le fichier</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div id="uploadInitialState" class="{{ $book->file_path ? 'd-none' : '' }}">
                                                        <div class="upload-icon" id="uploadIcon">
                                                            @if($isAudioBook)
                                                                <i class="bi bi-headphones"></i>
                                                            @else
                                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                            @endif
                                                        </div>
                                                        <h5 id="uploadTitle">{{ $isAudioBook ? 'Téléverser votre livre audio' : 'Téléverser votre ebook' }}</h5>
                                                        <p id="uploadDescription">{{ $isAudioBook ? 'Sélectionnez le fichier MP3 de votre livre audio.' : 'Sélectionnez le fichier PDF de votre ebook.' }}</p>
                                                        <input type="file" id="bookFileInput" name="{{ $isAudioBook ? 'audio_file' : 'ebook_file' }}" class="form-control mt-3" accept="{{ $isAudioBook ? '.mp3,audio/mpeg' : '.pdf' }}">
                                                        <div class="upload-info mt-3">
                                                            <span id="acceptedFormat" class="badge bg-danger">{{ $isAudioBook ? 'MP3 uniquement' : 'PDF uniquement' }}</span>
                                                            <small class="text-muted d-block mt-2">Taille maximale : 100 MB</small>
                                                        </div>
                                                    </div>

                                                    <div id="uploadProgressState" class="d-none">
                                                        <div class="upload-icon">
                                                            <i class="bi bi-cloud-arrow-up text-danger"></i>
                                                        </div>
                                                        <h5 class="mb-2">Téléversement en cours...</h5>
                                                        <p class="text-muted mb-3" id="uploadFileName"></p>
                                                        <div class="upload-progress-wrapper">
                                                            <div class="upload-progress-bar-bg">
                                                                <div class="upload-progress-bar" id="uploadProgressBar" style="width: 0%"></div>
                                                            </div>
                                                            <span class="upload-progress-text" id="uploadProgressText">0%</span>
                                                        </div>
                                                    </div>

                                                    <div id="uploadCompleteState" class="d-none">
                                                        <div class="upload-success-card">
                                                            <div class="upload-success-icon">
                                                                <i class="bi bi-check-lg"></i>
                                                            </div>
                                                            <h5 class="upload-success-title">Nouveau fichier prêt à être enregistré</h5>
                                                            <div class="upload-file-info" id="uploadedFileName"></div>
                                                            <div class="upload-actions">
                                                                <button type="button" class="upload-action-btn upload-action-preview" id="previewNewBookBtn">
                                                                    <span class="upload-action-icon"><i class="bi bi-eye-fill" id="previewNewActionIcon"></i></span>
                                                                    <span class="upload-action-label" id="previewNewActionLabel">Voir le livre</span>
                                                                </button>
                                                                <button type="button" class="upload-action-btn upload-action-change" id="changeNewFileBtn">
                                                                    <span class="upload-action-icon"><i class="bi bi-arrow-repeat"></i></span>
                                                                    <span class="upload-action-label">Changer</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Copyright -->
                                <div class="card book-card border-danger">
                                    <div class="card-header border-bottom">
                                        <h4 class="mb-0 text-white">
                                            <i class="bi bi-shield-check text-danger me-2"></i>
                                            Déclaration de droits d'auteur
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning mb-4">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <strong>Important :</strong> En téléversant ce livre sur KaMa Online Library, vous engagez votre responsabilité concernant les droits liés à cette œuvre.
                                        </div>
                                        <p class="mb-3">Je déclare être l'auteur ou le détenteur légal des droits nécessaires pour publier ce livre sur KaMa Online Library. Je confirme que le contenu ajouté ne porte pas atteinte aux droits d'auteur, droits de propriété intellectuelle ou autres droits de tiers.</p>
                                        <p class="mb-3">Je comprends que toute déclaration frauduleuse, publication non autorisée ou violation des droits d'un tiers peut entraîner le retrait du contenu, la suspension de mon compte ainsi que d'éventuelles poursuites conformément aux lois applicables.</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="authorDeclaration" name="copyright_accepted" value="1" {{ old('copyright_accepted', $book->copyright_accepted) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="authorDeclaration">
                                                Je confirme avoir lu et accepté cette déclaration et j'assume la responsabilité du contenu que je publie.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="hstack gap-2 justify-content-between wizard-actions">
                                    <button type="button" class="btn btn-secondary prev-btn wizard-action-btn wizard-action-secondary">
                                        <i class="bi bi-arrow-left me-2"></i> Retour
                                    </button>
                                    <button type="button" id="continueUploadBtn" class="btn btn-danger next-btn wizard-action-btn wizard-action-primary"
                                        {{ ($book->file_path && $book->copyright_accepted) ? '' : 'disabled' }}>
                                        Continuer <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- STEP 2 END -->

                        <!-- ===================== STEP 3 ===================== -->
                        <div id="step-3" class="content fade" role="tabpanel" aria-labelledby="steppertrigger3">
                            <div class="vstack gap-4">
                                <!-- Preview card -->
                                <div class="card preview-card">
                                    <div class="preview-header">
                                        <div>
                                            <h4 class="text-white"><i class="bi bi-eye-fill me-2"></i>Récapitulatif du livre</h4>
                                            <p>Vérifiez les informations de votre livre avant son enregistrement dans la bibliothèque KaMa.</p>
                                        </div>
                                        <span class="preview-badge">Aperçu</span>
                                    </div>
                                    <div class="preview-body">
                                        <div class="preview-cover">
                                            <img id="summary_cover"
                                                src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/default-book.png') }}"
                                                alt="Couverture du livre">
                                        </div>
                                        <div class="preview-info">
                                            <h2 id="summary_title">Titre du livre</h2>
                                            <div class="preview-author">
                                                <i class="bi bi-person-fill"></i>
                                                <span>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                                            </div>
                                            <div class="preview-tags">
                                                <span id="summary_category" class="tag red">Catégorie</span>
                                                <span id="summary_subcategory" class="tag">Sous-catégorie</span>
                                                <span id="summary_language" class="tag">Langue</span>
                                                <span id="summary_type" class="tag">Type</span>
                                            </div>
                                            <div class="book-meta">
                                                <div><small>Prix</small><strong id="summary_price">0 €</strong></div>
                                                <div id="summary_pages_box"><small>Pages</small><strong id="summary_pages">0</strong></div>
                                                <div id="summary_duration_box" style="display:none;"><small>Durée</small><strong id="summary_duration">00:00</strong></div>
                                                <div><small>Publication</small><strong id="summary_year">{{ date('Y') }}</strong></div>
                                            </div>
                                            <div class="file-summary-card">
                                                <div class="file-icon"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                                                <div class="file-details">
                                                    <h6>Fichier du livre</h6>
                                                    <p id="summary_file_name">Aucun fichier sélectionné</p>
                                                    <div class="file-meta">
                                                        <span id="summary_file_type">Format</span>
                                                        <span id="summary_file_size">Taille</span>
                                                    </div>
                                                </div>
                                                <div class="file-status" id="summary_file_status">
                                                    <i class="bi bi-check-circle-fill"></i> Prêt
                                                </div>
                                            </div>
                                            <div class="preview-description">
                                                <h6>Résumé du livre</h6>
                                                <p id="summary_short_description">Aucune description disponible.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin status info (no deposit, no sponsoring) -->
                                <div class="status-card" style="background: linear-gradient(135deg,#e8f5e9,#f1f8e9); border:1px solid #c8e6c9; border-radius:16px; padding:24px; display:flex; gap:20px; align-items:flex-start;">
                                    <div class="status-icon" style="background:#2e7d32; color:#fff; width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <i class="bi bi-patch-check-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Statut : Publié automatiquement</h5>
                                        <p class="mb-0 text-muted">En tant qu'administrateur, votre livre est publié immédiatement après validation. Aucun frais de dépôt ni processus éditorial requis.</p>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between wizard-actions">
                                    <button type="button" class="btn btn-secondary prev-btn wizard-action-btn wizard-action-secondary">
                                        <i class="bi bi-arrow-left me-2"></i> Retour
                                    </button>
                                    <button type="submit" class="btn btn-danger wizard-action-btn wizard-action-primary">
                                        <i class="bi bi-cloud-check me-2"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- STEP 3 END -->

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Preview overlay --}}
<div id="bookPreviewOverlay" class="book-preview-overlay d-none">
    <div class="book-preview-container">
        <div class="book-preview-header">
            <h5 class="mb-0 text-white">
                <i class="bi bi-book me-2" id="previewHeaderIcon"></i>
                <span id="previewHeaderTitle">Aperçu du livre</span>
            </h5>
            <button type="button" class="book-preview-close" id="closePreviewOverlay">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="book-preview-body">
            <iframe id="bookPreviewFrame" class="book-preview-frame d-none"></iframe>
            <div id="audioPreviewPanel" class="d-none text-center p-4">
                <div class="upload-success-icon mx-auto mb-3"><i class="bi bi-headphones"></i></div>
                <h4 class="mt-3 mb-2">Écouter le livre audio</h4>
                <p class="text-muted mb-4" id="audioPreviewFileName"></p>
                <audio id="bookAudioPreview" controls class="w-100" style="max-width: 480px;"></audio>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const durationInput = document.getElementById('durationInput');
    if (!durationInput) return;
    durationInput.addEventListener('beforeinput', function(event) {
        if (event.data && !/^[0-9:]+$/.test(event.data)) event.preventDefault();
    });
    durationInput.addEventListener('input', function() {
        const parts = this.value.replace(/[^0-9:]/g, '').split(':').slice(0, 3);
        this.value = parts.map((part, index) => part.slice(0, index === 0 ? 3 : 2)).join(':');
    });
})();
</script>

<script>
(function() {
    const bookFileInput = document.getElementById('bookFileInput');
    const dropZone = document.getElementById('uploadDropZone');
    const existingFilePanel = document.getElementById('existingFilePanel');
    const initialState = document.getElementById('uploadInitialState');
    const progressState = document.getElementById('uploadProgressState');
    const completeState = document.getElementById('uploadCompleteState');
    const progressBar = document.getElementById('uploadProgressBar');
    const progressText = document.getElementById('uploadProgressText');
    const uploadFileName = document.getElementById('uploadFileName');
    const uploadedFileName = document.getElementById('uploadedFileName');
    const previewBookBtn = document.getElementById('previewBookBtn');
    const previewNewBookBtn = document.getElementById('previewNewBookBtn');
    const previewNewActionIcon = document.getElementById('previewNewActionIcon');
    const previewNewActionLabel = document.getElementById('previewNewActionLabel');
    const previewHeaderIcon = document.getElementById('previewHeaderIcon');
    const previewHeaderTitle = document.getElementById('previewHeaderTitle');
    const bookPreviewFrame = document.getElementById('bookPreviewFrame');
    const audioPreviewPanel = document.getElementById('audioPreviewPanel');
    const bookAudioPreview = document.getElementById('bookAudioPreview');
    const audioPreviewFileName = document.getElementById('audioPreviewFileName');
    const existingAudioPlayer = document.getElementById('existingAudioPlayer');
    const changeFileBtn = document.getElementById('changeFileBtn');
    const changeNewFileBtn = document.getElementById('changeNewFileBtn');
    const continueUploadBtn = document.getElementById('continueUploadBtn');
    const authorDeclaration = document.getElementById('authorDeclaration');

    if (!bookFileInput || !dropZone) return;

    const existingUrl = dropZone.dataset.existingUrl || null;
    const existingName = dropZone.dataset.existingFile || '';
    let currentFileURL = existingUrl;
    let usingExistingFile = Boolean(existingUrl && existingFilePanel);
    let objectUrlCreated = false;

    function isAudioType() {
        return document.querySelector('[name="type"]:checked')?.value === 'audio'
            || dropZone.dataset.bookType === 'audio';
    }

    function hasLoadedFile() {
        if (usingExistingFile && existingFilePanel && !existingFilePanel.classList.contains('d-none')) return true;
        return bookFileInput.files.length > 0 && completeState && !completeState.classList.contains('d-none');
    }

    function updateContinueBtn() {
        if (!continueUploadBtn) return;
        continueUploadBtn.disabled = !(hasLoadedFile() && authorDeclaration && authorDeclaration.checked);
    }

    function updateNewPreviewLabels() {
        const isAudio = isAudioType();
        if (previewNewActionIcon) previewNewActionIcon.className = isAudio ? 'bi bi-play-circle-fill' : 'bi bi-eye-fill';
        if (previewNewActionLabel) previewNewActionLabel.textContent = isAudio ? 'Écouter le livre audio' : 'Voir le livre';
    }

    function pauseEmbeddedPlayers() {
        if (existingAudioPlayer) existingAudioPlayer.pause();
    }

    function showReplaceMode() {
        pauseEmbeddedPlayers();
        if (existingFilePanel) existingFilePanel.classList.add('d-none');
        completeState.classList.add('d-none');
        progressState.classList.add('d-none');
        initialState.classList.remove('d-none');
        dropZone.classList.remove('has-existing-file');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
        bookFileInput.value = '';
        usingExistingFile = false;
        if (objectUrlCreated && currentFileURL) URL.revokeObjectURL(currentFileURL);
        currentFileURL = null;
        objectUrlCreated = false;
        updateContinueBtn();
    }

    function restoreExistingFile() {
        if (!existingUrl || !existingFilePanel) return;
        pauseEmbeddedPlayers();
        if (objectUrlCreated && currentFileURL) URL.revokeObjectURL(currentFileURL);
        bookFileInput.value = '';
        initialState.classList.add('d-none');
        progressState.classList.add('d-none');
        completeState.classList.add('d-none');
        existingFilePanel.classList.remove('d-none');
        dropZone.classList.add('has-existing-file');
        currentFileURL = existingUrl;
        objectUrlCreated = false;
        usingExistingFile = true;
        updateContinueBtn();
    }

    function showCompleteState(fileName, fileUrl, isObjectUrl) {
        if (existingFilePanel) existingFilePanel.classList.add('d-none');
        progressState.classList.add('d-none');
        initialState.classList.add('d-none');
        completeState.classList.remove('d-none');
        uploadedFileName.textContent = fileName;
        if (objectUrlCreated && currentFileURL) URL.revokeObjectURL(currentFileURL);
        currentFileURL = fileUrl;
        objectUrlCreated = Boolean(isObjectUrl);
        usingExistingFile = false;
        updateNewPreviewLabels();
        updateContinueBtn();
    }

    function openPreview(fileUrl, fileName) {
        if (!fileUrl) return;
        const overlay = document.getElementById('bookPreviewOverlay');
        const isAudio = isAudioType();
        pauseEmbeddedPlayers();
        if (isAudio) {
            bookPreviewFrame.classList.add('d-none');
            bookPreviewFrame.src = '';
            audioPreviewPanel.classList.remove('d-none');
            audioPreviewFileName.textContent = fileName || '';
            previewHeaderIcon.className = 'bi bi-headphones me-2';
            previewHeaderTitle.textContent = 'Aperçu du livre audio';
            bookAudioPreview.src = fileUrl;
            bookAudioPreview.load();
        } else {
            bookAudioPreview.pause();
            bookAudioPreview.removeAttribute('src');
            audioPreviewPanel.classList.add('d-none');
            bookPreviewFrame.classList.remove('d-none');
            bookPreviewFrame.src = fileUrl;
            previewHeaderIcon.className = 'bi bi-book me-2';
            previewHeaderTitle.textContent = 'Aperçu du livre';
        }
        overlay.classList.remove('d-none');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const overlay = document.getElementById('bookPreviewOverlay');
        overlay.classList.add('d-none');
        bookPreviewFrame.src = '';
        bookAudioPreview.pause();
        bookAudioPreview.currentTime = 0;
        bookAudioPreview.removeAttribute('src');
        bookAudioPreview.load();
        document.body.style.overflow = '';
    }

    bookFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        usingExistingFile = false;
        if (existingFilePanel) existingFilePanel.classList.add('d-none');
        initialState.classList.add('d-none');
        progressState.classList.remove('d-none');
        completeState.classList.add('d-none');
        uploadFileName.textContent = file.name;
        let progress = 0;
        const fileSize = file.size;
        const speed = Math.max(2, Math.min(8, fileSize / (1024 * 1024)));
        const interval = setInterval(() => {
            progress += speed;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(() => {
                    showCompleteState(file.name, URL.createObjectURL(file), true);
                    if (typeof updateFileSummary === 'function') updateFileSummary(file);
                }, 300);
            }
            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
        }, 50);
    });

    if (previewBookBtn) {
        previewBookBtn.addEventListener('click', function() { openPreview(existingUrl || currentFileURL, existingName); });
    }
    if (previewNewBookBtn) {
        previewNewBookBtn.addEventListener('click', function() { openPreview(currentFileURL, bookFileInput.files[0]?.name || uploadedFileName.textContent); });
    }

    document.getElementById('closePreviewOverlay').addEventListener('click', closePreview);
    document.getElementById('bookPreviewOverlay').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });

    if (changeFileBtn) {
        changeFileBtn.addEventListener('click', function() {
            if (!document.getElementById('bookPreviewOverlay').classList.contains('d-none')) closePreview();
            showReplaceMode();
        });
    }
    if (changeNewFileBtn) {
        changeNewFileBtn.addEventListener('click', function() {
            if (!document.getElementById('bookPreviewOverlay').classList.contains('d-none')) closePreview();
            if (existingUrl && existingFilePanel) restoreExistingFile();
            else showReplaceMode();
        });
    }
    if (authorDeclaration) authorDeclaration.addEventListener('change', updateContinueBtn);

    const originalBookType = dropZone.dataset.bookType;
    document.querySelectorAll('input[name="type"]').forEach((input) => {
        input.addEventListener('change', function() {
            dropZone.dataset.bookType = this.value;
            updateNewPreviewLabels();
            if (!document.getElementById('bookPreviewOverlay').classList.contains('d-none')) closePreview();
            if (this.value === originalBookType && existingUrl) {
                restoreExistingFile();
                return;
            }
            showReplaceMode();
        });
    });

    if (existingUrl && existingFilePanel) restoreExistingFile();
    updateContinueBtn();
})();
</script>

<script>
const previewStartPage = document.getElementById('previewStartPage');
const previewEndPage = document.getElementById('previewEndPage');
const pagesPreviewError = document.getElementById('pagesPreviewError');
const pagesPreviewHint = document.getElementById('pagesPreviewHint');

function validatePreviewPages() {
    if (!previewStartPage || !previewEndPage) return true;
    const start = parseInt(previewStartPage.value);
    const end = parseInt(previewEndPage.value);
    if (!isNaN(start) && !isNaN(end)) {
        if (end < start) {
            pagesPreviewError.textContent = 'La dernière page doit être supérieure ou égale à la première.';
            pagesPreviewError.classList.remove('d-none');
            pagesPreviewHint.classList.add('d-none');
            previewEndPage.classList.add('is-invalid');
            return false;
        }
        if (end - start + 1 > 5) {
            pagesPreviewError.textContent = "L'écart entre la première et la dernière page ne doit pas dépasser 5 pages.";
            pagesPreviewError.classList.remove('d-none');
            pagesPreviewHint.classList.add('d-none');
            previewEndPage.classList.add('is-invalid');
            return false;
        }
        pagesPreviewError.classList.add('d-none');
        pagesPreviewHint.classList.remove('d-none');
        previewEndPage.classList.remove('is-invalid');
    }
    return true;
}

if (previewStartPage) previewStartPage.addEventListener('input', validatePreviewPages);
if (previewEndPage) previewEndPage.addEventListener('input', validatePreviewPages);
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const previewType = document.getElementById('previewType');
    const textPreview = document.getElementById('textPreview');
    const pagesPreview = document.getElementById('pagesPreview');

    function togglePreviewType() {
        if (!previewType || !textPreview || !pagesPreview) return;
        if (previewType.value === 'text') {
            textPreview.classList.remove('d-none');
            pagesPreview.classList.add('d-none');
        } else {
            textPreview.classList.add('d-none');
            pagesPreview.classList.remove('d-none');
        }
    }

    if (previewType) {
        previewType.addEventListener('change', togglePreviewType);
        togglePreviewType();
    }

    const editor = document.querySelector('.quilleditor');
    const hiddenDescription = document.getElementById('long_description');
    if (editor && hiddenDescription) {
        const quill = new Quill(editor, {
            theme: 'snow',
            modules: { toolbar: '.quilltoolbar' }
        });
        quill.root.innerHTML = `{!! addslashes($book->long_description ?? '') !!}`;
        quill.on('text-change', function () {
            hiddenDescription.value = quill.root.innerHTML;
        });
    }
});
</script>

<script>
// Cover preview
(function() {
    const coverInput = document.getElementById('coverImageInput');
    if (!coverInput) return;
    coverInput.addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (!file) return;
        const coverImage = document.getElementById('coverPreviewImage');
        const placeholder = document.getElementById('coverPlaceholder');
        const summaryCover = document.getElementById('summary_cover');
        const reader = new FileReader();
        reader.onload = (loadEvent) => {
            const dataUrl = loadEvent.target?.result;
            if (!dataUrl) return;
            if (coverImage) { coverImage.src = dataUrl; coverImage.style.display = 'block'; }
            if (placeholder) { placeholder.style.display = 'none'; }
            if (summaryCover) { summaryCover.src = dataUrl; }
        };
        reader.readAsDataURL(file);
    });
})();

// Type toggle
(function() {
    const pagesField = document.getElementById('pagesField');
    const durationField = document.getElementById('durationField');
    const summaryPagesBox = document.getElementById('summary_pages_box');
    const summaryDurationBox = document.getElementById('summary_duration_box');

    function updateBookType(type) {
        if (!pagesField || !durationField) return;
        if (type === 'ebook') {
            pagesField.style.display = 'block';
            durationField.style.display = 'none';
            if (summaryPagesBox) summaryPagesBox.style.display = '';
            if (summaryDurationBox) summaryDurationBox.style.display = 'none';
        } else {
            pagesField.style.display = 'none';
            durationField.style.display = 'block';
            if (summaryPagesBox) summaryPagesBox.style.display = 'none';
            if (summaryDurationBox) summaryDurationBox.style.display = '';
        }
    }

    const currentType = document.querySelector('input[name="type"]:checked')?.value;
    if (currentType) updateBookType(currentType);

    document.querySelectorAll('input[name="type"]').forEach(function(radio) {
        radio.addEventListener('change', function() { updateBookType(this.value); });
    });
})();

// Summary sync
(function() {
    const titleInput = document.querySelector('input[name="title"]');
    const priceInput = document.querySelector('input[name="price"]');
    const pagesInput = document.getElementById('pagesInput');
    const durationInput = document.getElementById('durationInput');
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    const languageSelect = document.querySelector('select[name="language"]');
    const yearSelect = document.querySelector('select[name="publication_year"]');
    const shortDescInput = document.querySelector('textarea[name="short_description"]');

    function syncSummary() {
        const summaryTitle = document.getElementById('summary_title');
        if (summaryTitle && titleInput) summaryTitle.textContent = titleInput.value || 'Titre du livre';

        const summaryPrice = document.getElementById('summary_price');
        if (summaryPrice && priceInput) summaryPrice.textContent = (priceInput.value || '0') + ' $';

        const summaryPages = document.getElementById('summary_pages');
        if (summaryPages && pagesInput) summaryPages.textContent = pagesInput.value || '0';

        const summaryDuration = document.getElementById('summary_duration');
        if (summaryDuration && durationInput) summaryDuration.textContent = durationInput.value || '00:00';

        const summaryCategory = document.getElementById('summary_category');
        if (summaryCategory && categorySelect) summaryCategory.textContent = categorySelect.options[categorySelect.selectedIndex]?.text || 'Catégorie';

        const summarySub = document.getElementById('summary_subcategory');
        if (summarySub && subcategorySelect) summarySub.textContent = subcategorySelect.options[subcategorySelect.selectedIndex]?.text || 'Sous-catégorie';

        const summaryLang = document.getElementById('summary_language');
        if (summaryLang && languageSelect) summaryLang.textContent = languageSelect.options[languageSelect.selectedIndex]?.text || 'Langue';

        const summaryType = document.getElementById('summary_type');
        if (summaryType) {
            const checked = document.querySelector('input[name="type"]:checked');
            summaryType.textContent = checked?.value === 'audio' ? 'Livre audio' : 'Ebook';
        }

        const summaryYear = document.getElementById('summary_year');
        if (summaryYear && yearSelect) summaryYear.textContent = yearSelect.value || new Date().getFullYear();

        const summaryDesc = document.getElementById('summary_short_description');
        if (summaryDesc && shortDescInput) summaryDesc.textContent = shortDescInput.value || 'Aucune description disponible.';
    }

    [titleInput, priceInput, pagesInput, durationInput, categorySelect, subcategorySelect, languageSelect, yearSelect, shortDescInput]
        .filter(Boolean)
        .forEach(el => el.addEventListener('input', syncSummary));

    document.querySelectorAll('input[name="type"]').forEach(el => el.addEventListener('change', syncSummary));

    syncSummary();
})();

function updateFileSummary(file) {
    const summaryFileName = document.getElementById('summary_file_name');
    const summaryFileType = document.getElementById('summary_file_type');
    const summaryFileSize = document.getElementById('summary_file_size');
    if (summaryFileName) summaryFileName.textContent = file.name;
    if (summaryFileType) summaryFileType.textContent = file.name.split('.').pop().toUpperCase();
    if (summaryFileSize) summaryFileSize.textContent = (file.size / 1048576).toFixed(2) + ' MB';
}
</script>

@endpush

@push('styles')
<style>
.existing-file-panel { padding: 0; }
.existing-file-meta { display: flex; align-items: center; gap: 14px; padding: 16px; border-bottom: 1px solid #f0f0f0; }
.existing-file-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fff0f0; color: #b30000; border-radius: 8px; font-weight: 700; font-size: .82rem; }
.existing-file-preview { padding: 16px; }
.existing-pdf-preview { width: 100%; height: 420px; border: none; border-radius: 8px; }
.existing-audio-preview { text-align: center; padding: 24px 0; }
.upload-actions { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }
.upload-action-btn { display: inline-flex; flex-direction: column; align-items: center; gap: 6px; padding: 12px 20px; border: 1.5px solid #e6e7ea; border-radius: 12px; background: #fff; cursor: pointer; font-size: .85rem; color: #5f636b; transition: .18s; }
.upload-action-btn:hover { border-color: #b30000; color: #b30000; background: #fff0f0; }
.upload-action-icon { font-size: 1.3rem; }
.upload-action-change { border-color: #ffd6d6; }
.book-preview-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.75); z-index: 9999; display: flex; align-items: center; justify-content: center; }
.book-preview-container { background: #1a1a2e; border-radius: 16px; width: 90vw; max-width: 960px; height: 85vh; display: flex; flex-direction: column; overflow: hidden; }
.book-preview-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid rgba(255,255,255,.1); }
.book-preview-close { background: none; border: none; color: #fff; font-size: 1.3rem; cursor: pointer; opacity: .7; }
.book-preview-close:hover { opacity: 1; }
.book-preview-body { flex: 1; overflow: hidden; }
.book-preview-frame { width: 100%; height: 100%; border: none; }
</style>
@endpush
