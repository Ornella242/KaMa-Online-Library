@extends('layouts.app')

@section('content')
@php
    $readOnlyReview = $book->status === 'under_review' || $book->status === 'waiting_review' ;
@endphp
<!-- =======================
Page Banner START -->
<section class="book-create-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <!-- LEFT -->
            <div class="col-lg-12">

                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                    <i class="bi bi-book me-2"></i>
                    Modification d'un livre existant
                </span>
                <h5 class="display-6 fw-bold mb-3">
                    Modifier le livre  <span class="text-red"> {{ $book->title }} </span>
                </h5>
            </div>
        </div>
    </div>
</section>
<!-- =======================
Page Banner END -->

<!-- =======================
Steps START -->
	<div class="container">
		<div id="stepper" class="bs-stepper stepper-outline">
			<!-- Step Buttons START -->
			<div class="bs-stepper-header" role="tablist">
				<!-- Step 1 -->
				<div class="step" data-target="#step-1">
					<div class="text-center">
						<button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger1" aria-controls="step-1">
							<span class="bs-stepper-circle">1</span>
						</button>
						<h6 class="bs-stepper-label d-none d-md-block">Information Générale</h6>
					</div>
				</div>
				<div class="line"></div>

				<!-- Step 2 -->
				<div class="step" data-target="#step-2">
					<div class="text-center">
						<button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger2" aria-controls="step-2">
							<span class="bs-stepper-circle">2</span>
						</button>
						<h6 class="bs-stepper-label d-none d-md-block">Contenu et fichiers</h6>
					</div>
				</div>
				<div class="line"></div>

				<!-- Step 3 -->
				<div class="step" data-target="#step-3">
					<div class="text-center">
						<button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger3" aria-controls="step-3">
							<span class="bs-stepper-circle">3</span>
						</button>
						<h6 class="bs-stepper-label d-none d-md-block">Récapitulatif</h6>
					</div>
				</div>

				
			</div>
			<!-- Step Buttons END -->

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

			@if($readOnlyReview)
				<div class="alert alert-warning">
					<i class="bi bi-hourglass-split me-2"></i>
					Ce livre est actuellement en cours de vérification.
					Vous pouvez uniquement modifier la catégorie, la sous-catégorie, la langue,
					l'année de publication, le prix et le résumé, et le type d'affichage (Extrait ou pages du livre).
				</div>
			@endif

			<!-- Step content START -->
			<div class="bs-stepper-content p-0 pt-4 pt-md-5">
				<div class="row g-4">

					<!-- Main content START -->
					<div class="col-12">
						<form
                            action="{{ route('writer.books.update',$book) }}"
                            method="POST"
                            enctype="multipart/form-data">

							@csrf
                            @method('PUT')
							<!-- Step 1 content START -->
							<div id="step-1" role="tabpanel" class="content fade" aria-labelledby="steppertrigger1">
								<div class="vstack gap-4">
									<!-- Main card -->
									<div class="card book-card">
										<div class="card-header border-bottom">
											<h4 class="mb-0">
												<i class="bi bi-book me-2"></i>
												Détails du livre
											</h4>
										</div>


										<div class="card-body">
											<div class="row g-4">
												<!-- Cover -->
												<div class="col-lg-4">
                                                    <div class="cover-upload-box">

                                                        <div class="cover-preview">

                                                            <img 
                                                                id="coverPreviewImage"
                                                                src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : '' }}"
                                                                alt="Aperçu couverture"
                                                                style="{{ $book->cover_image ? '' : 'display:none;' }}">

                                                            <div 
                                                                id="coverPlaceholder"
                                                                style="{{ $book->cover_image ? 'display:none;' : '' }}">

                                                                <i class="bi bi-image"></i>

                                                                <span>
                                                                    Aperçu couverture
                                                                </span>

                                                            </div>

                                                        </div>


                                                        <label class="btn btn-outline-primary w-100 mt-3">

                                                            <i class="bi bi-upload me-2"></i>

                                                            Modifier la couverture

                                                            <input 
                                                                type="file"
                                                                id="coverImageInput"
                                                                name="cover_image"
                                                                hidden
                                                                accept="image/png,image/jpeg"
																{{ $readOnlyReview ? 'disabled' : '' }}>

                                                        </label>


                                                        <small class="text-muted d-block mt-2">
                                                            JPG ou PNG recommandé (600x900px) / 2MB
                                                        </small>

                                                    </div>
                                                </div>

												<!-- Informations -->
												<div class="col-lg-8">
													<div class="row g-3">
														<!-- Title -->
														<div class="col-12">
															<label class="form-label">
																Titre du livre *
															</label>
															<input 
																type="text"
																name="title"
																class="form-control book-input"
																value="{{ old('title',$book->title) }}"
																{{ $readOnlyReview ? 'disabled' : '' }}>

														</div>

														<!-- Author -->
														<div class="col-12">
															<label class="form-label">
																Auteur
															</label>

															<div class="author-box">
																<i class="bi bi-person-circle"></i>
																<span>
																	{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
																</span>

															</div>
														</div>

														<!-- Type -->
														<div class="col-12">
															<label class="form-label">
																Type de publication *
															</label>

															<div class="book-type-selector">
																<label class="type-card">
																	<input 
																		type="radio"
																		name="type"
																		value="ebook"
																		{{ old('type',$book->type)=='ebook' ? 'checked' : '' }}
																		{{ $readOnlyReview ? 'disabled' : '' }}>
																	<div>

																		<i class="bi bi-file-earmark-text"></i>

																		<strong>
																			Ebook
																		</strong>

																		<small>
																			PDF téléchargeable
																		</small>

																	</div>


																</label>

																<label class="type-card">
																	<input 
																		type="radio"
																		name="type"
																		value="audio"
                                                                        {{ old('type',$book->type)=='audio' ? 'checked' : '' }}
																		{{ $readOnlyReview ? 'disabled' : '' }}>
																	<div>

																		<i class="bi bi-headphones"></i>

																		<strong>
																			Livre audio
																		</strong>

																		<small>
																			Format audio MP3
																		</small>
																	</div>
																</label>
															</div>
														</div>


														<!-- Category -->
														<div class="col-md-4">

															<label class="form-label">
																Catégorie *
															</label>
															<select 
																name="category_id"
																id="category_id"
																class="form-select js-choice">

																<option value="">
																	Choisir une catégorie
																</option>

																@foreach($categories as $category)

																	<option 
                                                                        value="{{ $category->id }}"
                                                                        {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                                        
                                                                        {{ $category->name }}

                                                                    </option>

																@endforeach

															</select>
														</div>

														<!-- Subcategory -->
														<div class="col-md-4">
															<label class="form-label">
																Sous-catégorie *
															</label>
															<select 
																name="subcategory_id"
																id="subcategory_id"
																class="form-select">

																<option value="">
																	Sous-catégorie
																</option>

                                                                @foreach($subcategories as $subcategory)

                                                                    <option
                                                                        value="{{ $subcategory->id }}"
                                                                        {{ old('subcategory_id',$book->subcategory_id) == $subcategory->id ? 'selected' : '' }}>

                                                                        {{ $subcategory->name }}

                                                                    </option>

                                                                @endforeach

															</select>
														</div>

														<div class="col-md-4" id="pagesField" style="display:none;">

															<label class="form-label">
																Nombre de pages *
															</label>

															<input 
																type="number"
																name="pages"
																id="pagesInput"
                                                                value="{{ old('pages', $book->pages) }}"
																class="form-control book-input"
																placeholder="Ex: 120"
																{{ $readOnlyReview ? 'disabled' : '' }}>

														</div>


														<div class="col-md-4" id="durationField" style="display:none;">

															<label class="form-label">
																Durée du livre audio *
															</label>

															<input 
																type="text"
																name="duration"
																id="durationInput"
                                                                value="{{ old('duration', $book->duration) }}"
																class="form-control book-input"
																placeholder="Ex: 02:35:00"
																{{ $readOnlyReview ? 'disabled' : '' }}>

															<small class="text-muted">
																Format recommandé : heures:minutes:secondes
															</small>

														</div>


														<!-- Language -->
														<div class="col-md-4">
															<label class="form-label">
																Langue *
															</label>
															<select 
																name="language"
																class="form-select js-choice">
																<option value="">
																	Choisir une langue
																</option>
																<option value="Français" {{ old('language', $book->language) == 'Français' ? 'selected' : '' }}>
																	Français
																</option>
																<option value="Anglais" {{ old('language', $book->language) == 'Anglais' ? 'selected' : '' }}>
																	Anglais
																</option>
															</select>
														</div>

														<!-- Year -->
														<div class="col-md-4">
															<label class="form-label">
																Année de publication *
															</label>

															<input
																type="number"
																name="publication_year"
                                                                value="{{ old('publication_year', $book->publication_year) }}"
																class="form-control book-input"
																placeholder="2026">
														</div>

														<div class="col-md-4">
															<label class="form-label">
																Prix en $ *
															</label>

															<input
																type="number"
																name="price"
                                                                value="{{ old('price', $book->price) }}"
																class="form-control book-input"
																placeholder="25">
														</div>

														<div></div>
													</div>
												</div>
											</div>

										</div>

									</div>

									<!-- Next -->

									<div class="text-end">
										<button 
											type="button"
											class="btn btn-primary next-btn px-5">
											Continuer
											<i class="bi bi-arrow-right ms-2"></i>
										</button>
									</div>



								</div>
							</div>
							<!-- Step 1 content END -->

							<!-- Step 2 content START -->
							<div id="step-2" role="tabpanel" class="content fade" aria-labelledby="steppertrigger2">
								<div class="vstack gap-4">
									<!-- TITLE -->
									<div class="step-title-box">
										<span>
											<i class="bi bi-journal-text"></i>
											Contenu du livre
										</span>
										<h4>
											Présentez votre ouvrage aux lecteurs
										</h4>
										<p>
											Ajoutez une description claire de votre livre ainsi que les fichiers
											nécessaires à sa publication sur KaMa Online Library.
										</p>
									</div>

									<!-- DESCRIPTION CARD START -->
									<div class="card book-card">

										<div class="card-header border-bottom">

											<h4 class="mb-0 text-white">
												<i class="bi bi-card-text text-danger me-2"></i>
												Présentation du livre
											</h4>

										</div>

										<div class="card-body">
											<div class="row g-4">
												<!-- SHORT DESCRIPTION -->
												<div class="col-12">
													<label class="form-label">
														Description courte *
													</label>
													<textarea
														name="short_description"
														rows="4"
														class="form-control book-input"
														placeholder="Une courte présentation qui apparaîtra sur la fiche du livre..."
													>{{ old('short_description',$book->short_description) }}</textarea>

												</div>

												<!-- FULL DESCRIPTION -->
												@if($book->type == 'ebook')
													<div class="col-12">

														<label class="form-label">
															Type d'aperçu
														</label>

														<select 
															name="preview_type"
															id="previewType"
															class="form-select">

															<option value="text"
																{{ $book->preview_type == 'text' ? 'selected' : '' }}>
																Extrait texte
															</option>

															<option value="pages"
																{{ $book->preview_type == 'pages' ? 'selected' : '' }}>
																Pages du livre
															</option>

														</select>

													</div>
												@endif
												<div class="col-12 {{ $book->preview_type == 'pages' ? 'd-none' : '' }}"
													id="textPreview">

													<label class="form-label">
														Extrait / Morceau *
													</label>


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


													<div class="bg-white border rounded-bottom h-300px quilleditor">
														{!! $book->long_description !!}
													</div>


													<input
														type="hidden"
														name="long_description"
														id="long_description"
														value="{{ old('long_description', $book->long_description) }}">

												</div>

												<div class="col-12 {{ $book->preview_type == 'text' ? 'd-none' : '' }}"
													id="pagesPreview">

													<div class="row">

														<div class="col-md-6">

															<label class="form-label">
																Première page
															</label>

															<input
																type="number"
																name="preview_start_page"
																min="1"
																class="form-control"
																value="{{ old('preview_start_page', $book->preview_start_page) }}"
																>

														</div>


														<div class="col-md-6">

															<label class="form-label">
																Dernière page
															</label>

															<input
																type="number"
																name="preview_end_page"
																min="1"
																class="form-control"
																value="{{ old('preview_end_page', $book->preview_end_page) }}"
																>

														</div>

													</div>


													<small class="text-black">
														Vous pouvez sélectionner au maximum 5 pages consécutives.
													</small>

												</div>


											</div>
										</div>
									</div>
									<!-- DESCRIPTION CARD END -->

									<!-- FILE UPLOAD CARD START -->
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
														Fichier du livre <span class="text-danger">*</span>
													</label>

													<div class="upload-box book-upload">

														<div class="upload-icon" id="uploadIcon">

															<i class="bi bi-file-earmark-pdf-fill"></i>

														</div>

														<h5 id="uploadTitle">

															Téléverser votre ebook

														</h5>

														<p id="uploadDescription">

															Sélectionnez le fichier PDF de votre ebook.

														</p>

														<input
															type="file"
															id="bookFileInput"
															name="ebook_file"
															class="form-control mt-3"
															accept=".pdf"
															{{ $readOnlyReview ? 'disabled' : '' }}>

														<div class="upload-info mt-3">

															<span id="acceptedFormat" class="badge bg-danger">

																PDF uniquement

															</span>

															<small class="text-muted d-block mt-2">

																Taille maximale : 100 MB

															</small>

														</div>

													</div>

												</div>

											</div>

										</div>
									</div>
									<!-- FILE UPLOAD CARD END -->

									<!-- BUTTONS -->
									<div class="hstack gap-2 justify-content-between">
										<button
											type="button"
											class="btn btn-secondary prev-btn">
											<i class="bi bi-arrow-left me-2"></i>
											Retour
										</button>

										<button
											type="button"
											class="btn btn-danger next-btn px-4">
											Continuer
											<i class="bi bi-arrow-right ms-2"></i>
										</button>
									</div>
								</div>
							</div>
							<!-- Step 2 content END -->

							<!-- Step 3 content START -->
							<div id="step-3" class="content fade" role="tabpanel" aria-labelledby="steppertrigger3">
								<div class="vstack gap-4">
									<!-- BOOK PREVIEW START -->
									<div class="card preview-card">
										<div class="preview-header">
											<div>
												<h4 class="text-white">
													<i class="bi bi-eye-fill me-2"></i>
													Récapitulatif du livre
												</h4>
												<p>
													Vérifiez les informations de votre livre avant son enregistrement dans la bibliothèque KaMa.
												</p>
											</div>


											<span class="preview-badge">
												Aperçu
											</span>

										</div>

										<div class="preview-body">

											<!-- COVER -->

											<div class="preview-cover">

												<img 
													id="summary_cover"
													src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/default-book.png') }}"
													alt="Couverture du livre">

											</div>

											<!-- INFORMATION -->

											<div class="preview-info">
												<h2 id="summary_title">
													Titre du livre
												</h2>
												<div class="preview-author">
													<i class="bi bi-person-fill"></i>
													<span>
														{{ Auth::user()->firstname }}
														{{ Auth::user()->lastname }}
													</span>
												</div>

												<!-- TAGS -->

												<div class="preview-tags">


													<span 
														id="summary_category"
														class="tag red">
														Catégorie

													</span>

													<span 
														id="summary_subcategory"
														class="tag">
														Sous-catégorie
													</span>

													<span 
														id="summary_language"
														class="tag">
														Langue
													</span>

													<span 
														id="summary_type"
														class="tag">
														Type
													</span>
												</div>

												<!-- META -->

												<div class="book-meta">
													<div>

														<small>
															Prix
														</small>

														<strong id="summary_price">
															0 $
														</strong>

													</div>

													<!-- Pages Ebook -->
													<div id="summary_pages_box">

														<small>
															Pages
														</small>

														<strong id="summary_pages">
															0
														</strong>

													</div>


													<div id="summary_duration_box" style="display:none;">

														<small>
															Durée
														</small>

														<strong id="summary_duration">
															00:00
														</strong>

													</div>

													<div>
														<small>
															Publication
														</small>
														<strong id="summary_year">

															2026

														</strong>

													</div>

												</div>

												<!-- FILE -->

												<div class="file-summary-card">
													<div class="file-icon">
														<i class="bi bi-file-earmark-arrow-up-fill"></i>
													</div>

													<div class="file-details">
														<h6>
															Fichier du livre
														</h6>

														<p id="summary_file_name">
															Aucun fichier sélectionné
														</p>

														<div class="file-meta">
															<span id="summary_file_type">
																Format
															</span>
															<span id="summary_file_size">
																Taille
															</span>
														</div>
													</div>

													<div class="file-status" id="summary_file_status">
														<i class="bi bi-check-circle-fill"></i>
														Prêt

													</div>
												</div>


												<!-- DESCRIPTION -->

												<div class="preview-description">
													<h6>
														Résumé du livre
													</h6>

													<p id="summary_short_description">

														Aucune description disponible.

													</p>


												</div>

											</div>


										</div>


									</div>

									<!-- BOOK PREVIEW END -->

									<!-- STATUS START -->

									<div class="status-card">
										<div class="status-icon">
											<i class="bi bi-hourglass-split"></i>
										</div>

										<div>
											<h5>
												Statut : En attente de paiement
											</h5>

											<p>

												Votre livre sera enregistré dans la base de données KaMa.
												Il restera invisible au public jusqu'au paiement des frais
												de dépôt et sera ensuite soumis au processus de publication.

											</p>
										</div>


									</div>

									<!-- STATUS END -->

									<!-- DEPOSIT START -->

									<div class="deposit-card">
										<div class="deposit-left">


											<h4>

												Frais de dépôt KaMa

											</h4>



											<p>
												Ces frais couvrent la préparation et la mise en ligne
												de votre ouvrage sur la plateforme.

											</p>



											<ul>

												<li>
													<i class="bi bi-check-circle-fill"></i>
													Vérification éditoriale
												</li>


												<li>
													<i class="bi bi-check-circle-fill"></i>
													Contrôle qualité du fichier
												</li>



												<li>
													<i class="bi bi-check-circle-fill"></i>
													Référencement dans la bibliothèque KaMa
												</li>



												<li>
													<i class="bi bi-check-circle-fill"></i>
													Publication officielle après paiement
												</li>


											</ul>


										</div>

										<div class="deposit-right">


											<span>
												Montant du dépôt
											</span>


											<h1>
												25 $
											</h1>



											<small>
												Le paiement sera effectué après l'enregistrement.
											</small>


										</div>
									</div>

									<!-- DEPOSIT END -->


									<!-- BUTTONS -->
									<div class="d-flex justify-content-between">


										<button
											type="button"
											class="btn btn-light prev-btn">

											<i class="bi bi-arrow-left me-2"></i>

											Retour
										</button>

										<button
											type="submit"
											class="btn btn-danger btn-lg px-5">
											<i class="bi bi-cloud-check me-2"></i>
											Modifier mon livre
										</button>
									</div>
								</div>
							</div>
							
							<!-- Step 3 content END -->

						</form>
					</div>
					<!-- Main content END -->

				</div>						
			</div>
			<!-- Step content END -->
		</div>
	</div>
<!-- =======================
Steps END -->

<script>

document.addEventListener("DOMContentLoaded", function () {


    /*
    |--------------------------------------------------------------------------
    | Gestion preview type
    |--------------------------------------------------------------------------
    */

    const previewType = document.getElementById('previewType');

    const textPreview = document.getElementById('textPreview');

    const pagesPreview = document.getElementById('pagesPreview');


    function togglePreviewType() {


        if (previewType.value === 'text') {


            textPreview.classList.remove('d-none');

            pagesPreview.classList.add('d-none');


        } else {


            textPreview.classList.add('d-none');

            pagesPreview.classList.remove('d-none');


        }

    }


    previewType.addEventListener(
        'change',
        togglePreviewType
    );


    // Initialisation selon la valeur existante
    togglePreviewType();



    /*
    |--------------------------------------------------------------------------
    | Initialisation Quill
    |--------------------------------------------------------------------------
    */


    const editor = document.querySelector('.quilleditor');

    const hiddenDescription = document.getElementById('long_description');


    if(editor && hiddenDescription){


        const quill = new Quill(editor, {

            theme: 'snow',

            modules: {

                toolbar: '.quilltoolbar'

            }

        });



        // Charger l'ancienne description
        quill.root.innerHTML = `{!! addslashes($book->long_description ?? '') !!}`;



        // Synchroniser Quill avec le formulaire

        quill.on('text-change', function () {


            hiddenDescription.value = quill.root.innerHTML;


        });


    }



});

</script>
@endsection