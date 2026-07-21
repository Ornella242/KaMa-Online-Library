@extends('layouts.admin')

@section('title', 'Ajouter un livre')
@section('page-title', 'Ajouter un livre')

@section('admin-content')

<!-- =======================
Steps START -->
<section>
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
						<button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger2" aria-controls="step-2" disabled style="pointer-events:none; opacity:0.5;">
							<span class="bs-stepper-circle">2</span>
						</button>
						<h6 class="bs-stepper-label d-none d-md-block">Contenu et fichiers</h6>
					</div>
				</div>
				<div class="line"></div>

				<!-- Step 3 -->
				<div class="step" data-target="#step-3">
					<div class="text-center">
						<button type="button" class="btn btn-link step-trigger mb-0" role="tab" id="steppertrigger3" aria-controls="step-3" disabled style="pointer-events:none; opacity:0.5;">
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

			<!-- Step content START -->
			<div class="bs-stepper-content p-0 pt-4 pt-md-5">
				<div class="row g-4">

					<!-- Main content START -->
					<div class="col-12">
						<form method="POST" 
								action="{{ route('admin.books.store') }}"
								enctype="multipart/form-data">

							@csrf
							<!-- Step 1 content START -->
							<div id="step-1" role="tabpanel" class="content fade" aria-labelledby="steppertrigger1">
								<div class="vstack gap-4">
									<!-- Main card -->
									<div class="card book-card">
										<div class="card-header border-bottom">
											<h4 class="mb-0">
												<i class="bi bi-book me-2 text-danger"></i>
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
																src=""
																alt="Aperçu couverture"
																style="display:none;">

															<div id="coverPlaceholder">
																<i class="bi bi-image"></i>
																<span>
																	Aperçu couverture
																</span>
															</div>

														</div>

														<label class="btn btn-outline-danger w-100 mt-3">

															<i class="bi bi-upload me-2"></i>
															Ajouter la couverture

															<input 
																type="file"
																id="coverImageInput"
																name="cover_image"
																hidden
																accept="image/png,image/jpeg">

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
																placeholder="Ex: Les chemins de l'avenir">

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
																		checked>
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
																		value="audio">
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

																	<option value="{{ $category->id }}">
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
																class="form-control book-input"
																placeholder="Ex: 120">

														</div>


														<div class="col-md-4" id="durationField" style="display:none;">

															<label class="form-label">
																Durée du livre audio *
															</label>

															<input 
																type="text"
																name="duration"
																id="durationInput"
																class="form-control book-input"
																placeholder="Ex: 02:35:00"
																inputmode="numeric"
																maxlength="9"
																pattern="[0-9]{1,3}:[0-5][0-9]:[0-5][0-9]"
																title="Utilisez le format heures:minutes:secondes, par exemple 02:35:00">

															<small class="text-muted">
																Format obligatoire : heures:minutes:secondes (ex. 02:35:00)
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
																<option>
																	Français
																</option>
																<option>
																	Anglais
																</option>
															</select>
														</div>

														<!-- Year -->
														<div class="col-md-4">
															<label class="form-label">
																Année de publication *
															</label>

															<select
																name="publication_year"
																class="form-select book-input">
																<option value="">Choisir une année</option>
																@for($y = date('Y'); $y >= 1900; $y--)
																	<option value="{{ $y }}">{{ $y }}</option>
																@endfor
															</select>
														</div>

														<div class="col-md-4">
															<label class="form-label">
																Prix en $ *
															</label>

															<input
																type="number"
																name="price"
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

									<div class="text-end wizard-actions wizard-actions-end">
										<button 
											type="button"
											id="step1NextBtn"
											class="btn btn-danger next-btn wizard-action-btn wizard-action-primary"
											disabled>
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
														Résumé *
													</label>
													<textarea
														name="short_description"
														rows="4"
														class="form-control book-input"
														placeholder="Une courte présentation qui apparaîtra sur la fiche du livre..."
													></textarea>

												</div>

												{{-- Type d'apercu --}}
												<div class="col-12" id="previewTypeContainer">

													<label class="form-label">
														Type d'aperçu
													</label>

													<select
														name="preview_type"
														id="preview_type"
														class="form-select">

														<option value="text">
															Extrait texte du livre
														</option>

														<option value="pages">
															Pages du livre (maximum 5 pages)
														</option>

													</select>

												</div>

												<!-- FULL DESCRIPTION -->
												<div class="col-12" id="textPreview">

													<label class="form-label">
														Extrait / Morceau *
													</label>

													<div class="bg-light border border-bottom-0 rounded-top py-3 quilltoolbar">

														<span class="ql-formats">
															<button class="ql-bold"></button>
															<button class="ql-italic"></button>
															<button class="ql-underline"></button>
														</span>

													</div>

													<div class="bg-white border rounded-bottom h-300px quilleditor">
													</div>

													<input 
														type="hidden"
														name="long_description"
														id="long_description">

												</div>

												
												<div class="col-12 d-none" id="pagesPreview">
													<div class="row">
														<div class="col-md-6">
															<label class="form-label">
																Première page *
															</label>

															<input
																type="number"
																name="preview_start_page"
																id="previewStartPage"
																min="1"
																class="form-control"
																placeholder="Ex: 1">
														</div>

														<div class="col-md-6">
															<label class="form-label">
																Dernière page *
															</label>

															<input
																type="number"
																name="preview_end_page"
																id="previewEndPage"
																min="1"
																class="form-control"
																placeholder="Ex: 5">
														</div>
													</div>

													<small class="text-black" id="pagesPreviewHint">
														Vous pouvez sélectionner au maximum 5 pages consécutives.
													</small>
													<small class="text-danger d-none" id="pagesPreviewError">
														L'écart entre la première et la dernière page ne doit pas dépasser 5 pages.
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

													<div class="upload-box book-upload" id="uploadDropZone">

														<div id="uploadInitialState">
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
																accept=".pdf">

															<div class="upload-info mt-3">
																<span id="acceptedFormat" class="badge bg-danger">
																	PDF uniquement
																</span>
																<small class="text-muted d-block mt-2">
																	Taille maximale : 100 MB
																</small>
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

																<h5 class="upload-success-title">Fichier chargé avec succès</h5>

																<div class="upload-file-info" id="uploadedFileName"></div>

																<div class="upload-actions">
																	<button type="button" class="upload-action-btn upload-action-preview" id="previewBookBtn">
																		<span class="upload-action-icon">
																			<i class="bi bi-eye-fill" id="previewActionIcon"></i>
																		</span>
																		<span class="upload-action-label" id="previewActionLabel">Voir le livre</span>
																	</button>

																	<button type="button" class="upload-action-btn upload-action-change" id="changeFileBtn">
																		<span class="upload-action-icon">
																			<i class="bi bi-arrow-repeat"></i>
																		</span>
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
									<!-- FILE UPLOAD CARD END -->

									<!-- COPYRIGHT DECLARATION START -->

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

												<strong>Important :</strong>
												
												En téléversant ce livre sur KaMa Online Library,
												vous engagez votre responsabilité concernant les droits liés
												à cette œuvre.

											</div>


											<p class="mb-3">

												Je déclare être l'auteur ou le détenteur légal des droits
												nécessaires pour publier ce livre sur KaMa Online Library.

												Je confirme que le contenu ajouté ne porte pas atteinte aux
												droits d'auteur, droits de propriété intellectuelle ou autres
												droits de tiers.

											</p>


											<p class="mb-3">
												Je comprends que toute déclaration frauduleuse, publication
												non autorisée ou violation des droits d'un tiers peut entraîner
												le retrait du contenu, la suspension de mon compte ainsi que
												d'éventuelles poursuites conformément aux lois applicables.
											</p>



											<div class="form-check">
												<input
													class="form-check-input"
													type="checkbox"
													id="authorDeclaration"
													name="copyright_accepted"
   													value="1">

												<label 
													class="form-check-label"
													for="authorDeclaration">

													Je confirme avoir lu et accepté cette déclaration et
													j'assume la responsabilité du contenu que je publie.

												</label>

											</div>


										</div>

									</div>

									<!-- COPYRIGHT DECLARATION END -->

									<!-- BUTTONS -->
									<div class="hstack gap-2 justify-content-between wizard-actions">
										<button
											type="button"
											class="btn btn-secondary prev-btn wizard-action-btn wizard-action-secondary">
											<i class="bi bi-arrow-left me-2"></i>
											Retour
										</button>

										<button
											type="button"
											id="continueUploadBtn"
											class="btn btn-danger next-btn wizard-action-btn wizard-action-primary" disabled>
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
													src=""
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

									<!-- STATUS ADMIN START -->
									<div class="status-card" style="background: linear-gradient(135deg,#e8f5e9,#f1f8e9); border:1px solid #c8e6c9; border-radius:16px; padding:24px; display:flex; gap:20px; align-items:flex-start;">
										<div class="status-icon" style="background:#2e7d32; color:#fff; width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
											<i class="bi bi-patch-check-fill fs-4"></i>
										</div>
										<div>
											<h5 class="mb-1">Statut : Publication immédiate</h5>
											<p class="mb-0 text-muted">En tant qu'administrateur, votre livre sera publié instantanément sur la plateforme. Aucun frais de dépôt ni délai de vérification ne sont requis.</p>
										</div>
									</div>
									<!-- STATUS ADMIN END -->


									<!-- BUTTONS -->
									<div class="d-flex justify-content-between wizard-actions">


										<button
											type="button"
											class="btn btn-secondary prev-btn wizard-action-btn wizard-action-secondary">

											<i class="bi bi-arrow-left me-2"></i>

											Retour
										</button>

										<button
											type="submit"
											class="btn btn-danger wizard-action-btn wizard-action-primary">
											<i class="bi bi-cloud-check me-2"></i>
											Enregistrer mon livre
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
</section>
<!-- =======================
Steps END -->

<div id="bookPreviewOverlay" class="book-preview-overlay d-none">
	<div class="book-preview-container">
		<div class="book-preview-header">
			<h5>
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
				<i class="bi bi-headphones text-danger display-3"></i>
				<h4 class="mt-3 mb-2">Écouter le livre audio</h4>
				<p class="text-muted mb-4" id="audioPreviewFileName"></p>
				<audio id="bookAudioPreview" class="w-100" controls preload="metadata">
					Votre navigateur ne prend pas en charge la lecture audio.
				</audio>
			</div>
		</div>
	</div>
</div>

<script>
(function() {
	const step1Btn = document.getElementById('step1NextBtn');
	const durationInput = document.getElementById('durationInput');

	durationInput.addEventListener('beforeinput', function(event) {
		if (event.data && !/^[0-9:]+$/.test(event.data)) {
			event.preventDefault();
		}
	});

	durationInput.addEventListener('input', function() {
		const parts = this.value
			.replace(/[^0-9:]/g, '')
			.split(':')
			.slice(0, 3);

		this.value = parts
			.map((part, index) => part.slice(0, index === 0 ? 3 : 2))
			.join(':');
	});

	function checkStep1() {
		const title = document.querySelector('[name="title"]');
		const category = document.querySelector('[name="category_id"]');
		const subcategory = document.querySelector('[name="subcategory_id"]');
		const language = document.querySelector('[name="language"]');
		const year = document.querySelector('[name="publication_year"]');
		const price = document.querySelector('[name="price"]');
		const type = document.querySelector('[name="type"]:checked');

		let valid = title && title.value.trim()
			&& category && category.value.trim()
			&& subcategory && subcategory.value.trim()
			&& language && language.value.trim()
			&& year && year.value.trim()
			&& price && price.value.trim();

		if (valid && type) {
			if (type.value === 'ebook') {
				const pages = document.getElementById('pagesInput');
				valid = pages && pages.value.trim();
			} else if (type.value === 'audio') {
				const duration = document.getElementById('durationInput');
				valid = duration && duration.value.trim() && duration.checkValidity();
			}
		}

		step1Btn.disabled = !valid;

		if (valid) {
			const step2trigger = document.getElementById('steppertrigger2');
			step2trigger.disabled = false;
			step2trigger.style.pointerEvents = '';
			step2trigger.style.opacity = '';
		}
	}

	document.getElementById('step-1').addEventListener('input', checkStep1);
	document.getElementById('step-1').addEventListener('change', checkStep1);
})();
</script>

<script>
(function() {
	const bookFileInput = document.getElementById('bookFileInput');
	const initialState = document.getElementById('uploadInitialState');
	const progressState = document.getElementById('uploadProgressState');
	const completeState = document.getElementById('uploadCompleteState');
	const progressBar = document.getElementById('uploadProgressBar');
	const progressText = document.getElementById('uploadProgressText');
	const uploadFileName = document.getElementById('uploadFileName');
	const uploadedFileName = document.getElementById('uploadedFileName');
	const previewBookBtn = document.getElementById('previewBookBtn');
	const previewActionIcon = document.getElementById('previewActionIcon');
	const previewActionLabel = document.getElementById('previewActionLabel');
	const previewHeaderIcon = document.getElementById('previewHeaderIcon');
	const previewHeaderTitle = document.getElementById('previewHeaderTitle');
	const bookPreviewFrame = document.getElementById('bookPreviewFrame');
	const audioPreviewPanel = document.getElementById('audioPreviewPanel');
	const bookAudioPreview = document.getElementById('bookAudioPreview');
	const audioPreviewFileName = document.getElementById('audioPreviewFileName');
	const changeFileBtn = document.getElementById('changeFileBtn');
	const continueUploadBtn = document.getElementById('continueUploadBtn');

	let currentFileURL = null;

	bookFileInput.addEventListener('change', function() {
		const file = this.files[0];
		if (!file) return;

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
				setTimeout(() => showCompleteState(file), 300);
			}
			progressBar.style.width = progress + '%';
			progressText.textContent = Math.round(progress) + '%';
		}, 50);
	});

	function showCompleteState(file) {
		progressState.classList.add('d-none');
		completeState.classList.remove('d-none');
		uploadedFileName.textContent = file.name;

		if (currentFileURL) URL.revokeObjectURL(currentFileURL);
		currentFileURL = URL.createObjectURL(file);

		const isAudio = document.querySelector('[name="type"]:checked')?.value === 'audio';
		previewActionIcon.className = isAudio ? 'bi bi-play-circle-fill' : 'bi bi-eye-fill';
		previewActionLabel.textContent = isAudio ? 'Écouter le livre audio' : 'Voir le livre';

		updateContinueBtn();
		updateFileSummary(file);
	}

	function updateContinueBtn() {
		const checkbox = document.getElementById('authorDeclaration');
		const fileLoaded = bookFileInput.files.length > 0 && completeState && !completeState.classList.contains('d-none');
		continueUploadBtn.disabled = !(fileLoaded && checkbox && checkbox.checked);
	}

	previewBookBtn.addEventListener('click', function() {
		if (!currentFileURL) return;
		const overlay = document.getElementById('bookPreviewOverlay');
		const isAudio = document.querySelector('[name="type"]:checked')?.value === 'audio';

		if (isAudio) {
			bookPreviewFrame.classList.add('d-none');
			bookPreviewFrame.src = '';
			audioPreviewPanel.classList.remove('d-none');
			audioPreviewFileName.textContent = bookFileInput.files[0]?.name ?? '';
			previewHeaderIcon.className = 'bi bi-headphones me-2';
			previewHeaderTitle.textContent = 'Aperçu du livre audio';
			bookAudioPreview.src = currentFileURL;
			bookAudioPreview.load();
		} else {
			bookAudioPreview.pause();
			bookAudioPreview.removeAttribute('src');
			audioPreviewPanel.classList.add('d-none');
			bookPreviewFrame.classList.remove('d-none');
			bookPreviewFrame.src = currentFileURL;
			previewHeaderIcon.className = 'bi bi-book me-2';
			previewHeaderTitle.textContent = 'Aperçu du livre';
		}

		overlay.classList.remove('d-none');
		document.body.style.overflow = 'hidden';
	});

	document.getElementById('closePreviewOverlay').addEventListener('click', function() {
		const overlay = document.getElementById('bookPreviewOverlay');
		overlay.classList.add('d-none');
		bookPreviewFrame.src = '';
		bookAudioPreview.pause();
		bookAudioPreview.currentTime = 0;
		bookAudioPreview.removeAttribute('src');
		bookAudioPreview.load();
		document.body.style.overflow = '';
	});

	document.getElementById('bookPreviewOverlay').addEventListener('click', function(e) {
		if (e.target === this) {
			document.getElementById('closePreviewOverlay').click();
		}
	});

	changeFileBtn.addEventListener('click', function() {
		if (!document.getElementById('bookPreviewOverlay').classList.contains('d-none')) {
			document.getElementById('closePreviewOverlay').click();
		}

		bookFileInput.value = '';
		completeState.classList.add('d-none');
		initialState.classList.remove('d-none');
		progressBar.style.width = '0%';
		progressText.textContent = '0%';
		if (currentFileURL) {
			URL.revokeObjectURL(currentFileURL);
			currentFileURL = null;
		}
	});

	continueUploadBtn.addEventListener('click', function() {
		if (!bookFileInput.files.length) {
			Swal.fire({
				icon: 'warning',
				title: 'Fichier manquant',
				text: 'Veuillez sélectionner le fichier de votre livre avant de continuer.',
				confirmButtonColor: '#dc3545'
			});
			return;
		}

		const step3trigger = document.getElementById('steppertrigger3');
		step3trigger.disabled = false;
		step3trigger.style.pointerEvents = '';
		step3trigger.style.opacity = '';

		if (typeof updateBookSummary === 'function') {
			updateBookSummary();
		}

		document.querySelector('.next-btn').click();
	});
})();
</script>

<script>

const authorDeclaration = document.getElementById(
    'authorDeclaration'
);

const continueUploadBtn = document.getElementById(
    'continueUploadBtn'
);


authorDeclaration.addEventListener(
    'change',
    function(){
        const fileInput = document.getElementById('bookFileInput');
        const completeState = document.getElementById('uploadCompleteState');
        const fileLoaded = fileInput.files.length > 0 && completeState && !completeState.classList.contains('d-none');

        continueUploadBtn.disabled = !(this.checked && fileLoaded);
    }
);


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
            previewEndPage.value = start + 4;
            return false;
        }
    }

    pagesPreviewError.classList.add('d-none');
    pagesPreviewHint.classList.remove('d-none');
    previewEndPage.classList.remove('is-invalid');
    previewStartPage.classList.remove('is-invalid');
    return true;
}

if (previewStartPage && previewEndPage) {
    previewStartPage.addEventListener('input', validatePreviewPages);
    previewEndPage.addEventListener('input', validatePreviewPages);
}


</script>

<script>
(function () {
    const setText = (id, value, fallback) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value || fallback;
    };

    window.updateFileSummary = function (file) {
        if (!file) return;
        setText('summary_file_name', file.name, 'Aucun fichier sélectionné');
        setText('summary_file_type', (file.name.split('.').pop() || '').toUpperCase(), 'Format');
        setText('summary_file_size', (file.size / 1024 / 1024).toFixed(2) + ' MB', 'Taille');
        const status = document.getElementById('summary_file_status');
        if (status) {
            status.innerHTML = '<i class="bi bi-check-circle-fill"></i> Prêt';
        }
    };

    window.updateBookSummary = function () {
        const title = document.querySelector('[name="title"]')?.value;
        setText('summary_title', title, 'Titre du livre');

        const category = document.querySelector('[name="category_id"]');
        if (category) {
            setText(
                'summary_category',
                category.options[category.selectedIndex]?.text,
                'Catégorie'
            );
        }

        const subcategory = document.querySelector('[name="subcategory_id"]');
        if (subcategory) {
            setText(
                'summary_subcategory',
                subcategory.options[subcategory.selectedIndex]?.text,
                'Sous-catégorie'
            );
        }

        const language = document.querySelector('[name="language"]')?.value;
        setText('summary_language', language, 'Langue');

        const type = document.querySelector('[name="type"]:checked');
        setText(
            'summary_type',
            type ? (type.value === 'ebook' ? 'Ebook' : 'Livre audio') : null,
            'Type'
        );

        const price = document.querySelector('[name="price"]')?.value;
        setText(
            'summary_price',
            price ? Number(price).toLocaleString('fr-FR') + ' FCFA' : null,
            '0 FCFA'
        );

        const pagesInput = document.getElementById('pagesInput');
        const durationInput = document.getElementById('durationInput');
        const summaryPagesBox = document.getElementById('summary_pages_box');
        const summaryDurationBox = document.getElementById('summary_duration_box');
        const isAudio = type?.value === 'audio';

        if (summaryPagesBox) summaryPagesBox.style.display = isAudio ? 'none' : 'block';
        if (summaryDurationBox) summaryDurationBox.style.display = isAudio ? 'block' : 'none';
        setText('summary_pages', pagesInput?.value, '0');
        setText('summary_duration', durationInput?.value, '00:00:00');

        const year = document.querySelector('[name="publication_year"]')?.value;
        setText('summary_year', year, '----');

        const description = document.querySelector('[name="short_description"]')?.value;
        setText(
            'summary_short_description',
            description,
            'Aucune description disponible.'
        );

        const cover = document.querySelector('[name="cover_image"]');
        const summaryCover = document.getElementById('summary_cover');
        if (cover?.files?.length && summaryCover) {
            const reader = new FileReader();
            reader.onload = (e) => {
                summaryCover.src = e.target.result;
            };
            reader.readAsDataURL(cover.files[0]);
        }

        const fileInput = document.getElementById('bookFileInput');
        if (fileInput?.files?.length && typeof updateFileSummary === 'function') {
            updateFileSummary(fileInput.files[0]);
        }
    };

    document.addEventListener('input', (e) => {
        if (e.target.matches(
            '[name="title"], [name="price"], [name="pages"], [name="duration"], [name="publication_year"], [name="short_description"]'
        )) {
            updateBookSummary();
        }
    });

    document.addEventListener('change', (e) => {
        if (e.target.matches(
            '[name="category_id"], [name="subcategory_id"], [name="language"], [name="type"], [name="cover_image"], [name="ebook_file"], [name="audio_file"]'
        )) {
            updateBookSummary();
        }
    });

    document.addEventListener('DOMContentLoaded', updateBookSummary);
    updateBookSummary();
})();
</script>
@endsection