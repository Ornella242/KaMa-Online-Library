<!DOCTYPE html>
<html lang="en">
<head>
	<title>KaMa Online Library</title>
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="KaMa - KaMa Online Library est une librairie numérique dédiée à la valorisation de la littérature africaine. Elle propose un accès simple et rapide à une large sélection d’ouvrages d’auteurs africains, disponibles en formats ebook et audiolivre, permettant aux lecteurs de découvrir, lire et écouter des histoires authentiques issues du continent et de sa diaspora.">

	@yield('meta')
	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')
 
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
		    var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})

				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})

			showActiveTheme(getPreferredTheme())

			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})

			}
		})
		
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/font-awesome/css/all.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tiny-slider/tiny-slider.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/glightbox/css/glightbox.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/flatpickr/css/flatpickr.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/choices/css/choices.min.css')}}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/stepper/css/bs-stepper.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/quill/css/quill.snow.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/dropzone/css/dropzone.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/page-flip/dist/css/page-flip.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

</head>

<body>

    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Vendors -->
    <script src="{{ asset('assets/vendor/tiny-slider/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
	<script>
	function openPreviewModal() {
		document.getElementById("previewModal").style.display = "flex";
	}

	function closePreviewModal() {
		document.getElementById("previewModal").style.display = "none";
	}

	function openCommentModal() {
		document.getElementById("commentModal").style.display = "flex";
	}

	function closeCommentModal() {
		document.getElementById("commentModal").style.display = "none";
	}
	</script>
	<script>
		document.querySelectorAll('.toggle-password').forEach(icon => {
			icon.addEventListener('click', function () {

				const targetId = this.getAttribute('data-target');
				const input = document.getElementById(targetId);

				if (!input) return;

				const isPassword = input.type === 'password';
				input.type = isPassword ? 'text' : 'password';

				this.classList.toggle('fa-eye');
				this.classList.toggle('fa-eye-slash');
			});
		});
	</script>

	<!-- Vendors -->
	<script src="{{ asset('assets/vendor/apexcharts/js/apexcharts.min.js') }}"></script>
	
    

	<!-- Back to top -->
	<div class="back-top"></div>
	<!-- Vendors -->
	<script src="{{ asset('assets/vendor/stepper/js/bs-stepper.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/quill/js/quill.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/dropzone/js/dropzone.js') }}"></script>

	<!-- ThemeFunctions -->
    <script src="{{ asset('assets/js/functions.js') }}"></script>
	<script>

		document.addEventListener('DOMContentLoaded', function () {

			const category = document.getElementById('category_id');
			const subcategory = document.getElementById('subcategory_id');


			category.addEventListener('change', function () {


				let categoryId = this.value;


				subcategory.innerHTML = `
					<option value="">
						Choisir une sous-catégorie
					</option>
				`;


				if(categoryId){

					fetch(`/writer/categories/${categoryId}/subcategories`)

					.then(response => response.json())

					.then(data => {


						data.forEach(item => {

							subcategory.innerHTML += `
								<option value="${item.id}">
									${item.name}
								</option>
							`;

						});


					});

				}


			});


		});
    </script>

	<script>

		const coverInput = document.getElementById('coverImageInput');

		const coverImage = document.getElementById('coverPreviewImage');

		const placeholder = document.getElementById('coverPlaceholder');

		coverInput.addEventListener('change', function(e){
			const file = e.target.files[0];
			if(file){
				const reader = new FileReader();


				reader.onload = function(event){


					coverImage.src = event.target.result;


					coverImage.style.display = "block";


					placeholder.style.display = "none";
				};
				reader.readAsDataURL(file);
			}
		});


				const typeInputs = document.querySelectorAll('input[name="type"]');
				const uploadTitle = document.getElementById('uploadTitle');
				const uploadDescription = document.getElementById('uploadDescription');
				const uploadIcon = document.getElementById('uploadIcon');
				const acceptedFormat = document.getElementById('acceptedFormat');
				const bookInput = document.getElementById('bookFileInput');

				typeInputs.forEach(input => {

					input.addEventListener('change', function () {

						if (this.value === 'ebook') {

							uploadTitle.innerText = 'Téléverser votre ebook';

							uploadDescription.innerText =
								'Sélectionnez le fichier PDF de votre ebook.';

							uploadIcon.innerHTML =
								'<i class="bi bi-file-earmark-pdf-fill"></i>';

							acceptedFormat.innerText = 'PDF uniquement';

							bookInput.accept = '.pdf';

							bookInput.name = 'ebook_file';

							bookInput.value = '';

						} else {

							uploadTitle.innerText = 'Téléverser votre livre audio';

							uploadDescription.innerText =
								'Sélectionnez le fichier MP3 de votre livre audio.';

							uploadIcon.innerHTML =
								'<i class="bi bi-headphones"></i>';

							acceptedFormat.innerText = 'MP3 uniquement';

							bookInput.accept = '.mp3,audio/mpeg';

							bookInput.name = 'audio_file';

							bookInput.value = '';

						}

					});

				});



			function updateFileSummary(file){

					if(!file) return;


					document.getElementById("summary_file_name").textContent = file.name;


					let extension = file.name.split('.').pop().toUpperCase();


					document.getElementById("summary_file_type").textContent = extension;


					document.getElementById("summary_file_size").textContent =
						(file.size / 1024 / 1024).toFixed(2) + " MB";


					document.getElementById("summary_file_status").innerHTML =
						'<i class="bi bi-check-circle-fill"></i> Prêt';

			}

			const bookFileInput = document.getElementById('bookFileInput');
				if(bookFileInput){

					bookFileInput.addEventListener('change', function(){

						const file = this.files[0];

						if(file){

							updateFileSummary(file);

						}

					});

				}
				const publicationTypeInputs = document.querySelectorAll('input[name="type"]');

				const pagesField = document.getElementById('pagesField');
				const durationField = document.getElementById('durationField');

				const pagesInput = document.getElementById('pagesInput');
				const durationInput = document.getElementById('durationInput');


				publicationTypeInputs.forEach(input => {

					input.addEventListener('change', function(){


						if(this.value === "ebook"){


							pagesField.style.display = "block";

							durationField.style.display = "none";


							pagesInput.required = true;

							durationInput.required = false;


							durationInput.value = "";


						}


						else if(this.value === "audio"){


							pagesField.style.display = "none";

							durationField.style.display = "block";


							pagesInput.required = false;

							durationInput.required = true;


							pagesInput.value = "";


						}


					});

				});



				// Affichage initial
				const checkedType = document.querySelector('input[name="type"]:checked');


				if(checkedType){

					checkedType.dispatchEvent(new Event('change'));

				}
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {


		function updateBookSummary(){

			// =========================
			// TITRE
			// =========================

			let title = document.querySelector('[name="title"]')?.value;
			document.querySelector("#summary_title").innerText =
				title || "Titre du livre";

			// =========================
			// CATEGORIE
			// =========================

			let category = document.querySelector('[name="category_id"]');
			if(category){

				document.querySelector("#summary_category").innerText =
					category.options[category.selectedIndex]?.text || "Catégorie";

			}
			// =========================
			// SOUS-CATEGORIE
			// =========================

			let subcategory = document.querySelector('[name="subcategory_id"]');

			if(subcategory){

				document.querySelector("#summary_subcategory").innerText =
					subcategory.options[subcategory.selectedIndex]?.text || "Sous-catégorie";

			}
			// =========================
			// LANGUE
			// =========================

			let language = document.querySelector('[name="language"]')?.value;
			document.querySelector("#summary_language").innerText =
				language || "Langue";

			// =========================
			// TYPE
			// =========================

			let type = document.querySelector('[name="type"]:checked');
			if(type){
				document.querySelector("#summary_type").innerText =
					type.value === "ebook"
					? "Ebook"
					: "Livre audio";

			}

			// =========================
			// PRIX
			// =========================

			let price = document.querySelector('[name="price"]')?.value;
			document.querySelector("#summary_price").innerText =
				price ? price + " $" : "0 $";

		// =========================
		// PAGES / DUREE AUDIO
		// =========================

		const publicationTypeInputs = document.querySelectorAll('input[name="type"]');

		const pagesField = document.getElementById('pagesField');
		const durationField = document.getElementById('durationField');

		const pagesInput = document.getElementById('pagesInput');
		const durationInput = document.getElementById('durationInput');


		// Summary

		const summaryPagesBox = document.getElementById('summary_pages_box');
		const summaryDurationBox = document.getElementById('summary_duration_box');

		const summaryPages = document.getElementById('summary_pages');
		const summaryDuration = document.getElementById('summary_duration');



		function updateBookTypeDisplay(type){


			if(type === "ebook"){


				// Formulaire

				if(pagesField){
					pagesField.style.display = "block";
				}

				if(durationField){
					durationField.style.display = "none";
				}


				if(pagesInput){

					pagesInput.required = true;

				}
				if(durationInput){

					durationInput.required = false;
					durationInput.value = "";

				}
				// Résumé

				if(summaryPagesBox){

					summaryPagesBox.style.display = "block";

				}
				if(summaryDurationBox){
					summaryDurationBox.style.display = "none";
				}
			}
			else if(type === "audio"){
				// Formulaire
				if(pagesField){

					pagesField.style.display = "none";

				}
				if(durationField){
					durationField.style.display = "block";

				}
				if(pagesInput){

					pagesInput.required = false;
					pagesInput.value = "";

				}

				if(durationInput){

					durationInput.required = true;

				}

				// Résumé

				if(summaryPagesBox){

					summaryPagesBox.style.display = "none";

				}


				if(summaryDurationBox){

					summaryDurationBox.style.display = "block";

				}


			}


		}



		// Changement Ebook / Audio

		publicationTypeInputs.forEach(input => {


			input.addEventListener('change', function(){


				updateBookTypeDisplay(this.value);


			});


		});



		// Affichage initial

		const checkedType = document.querySelector('input[name="type"]:checked');


		if(checkedType){

			updateBookTypeDisplay(checkedType.value);

		}



		// =========================
		// UPDATE SUMMARY PAGES
		// =========================

		if(pagesInput){


			pagesInput.addEventListener('input', function(){


				if(summaryPages){

					summaryPages.innerText = this.value || "0";

				}


			});


		}



					// =========================
					// UPDATE SUMMARY DUREE
					// =========================

					if(durationInput){


						durationInput.addEventListener('input', function(){


							if(summaryDuration){

								summaryDuration.innerText = this.value || "00:00:00";

							}


						});


					}
					
					// =========================
					// ANNEE
					// =========================

					let year = document.querySelector('[name="publication_year"]')?.value;
					document.querySelector("#summary_year").innerText =
						year || "----";

					// =========================
					// DESCRIPTION
					// =========================

					let description =
						document.querySelector('[name="short_description"]')?.value;

					document.querySelector("#summary_short_description").innerText =
						description || "Aucune description disponible.";

					// =========================
					// COUVERTURE
					// =========================

					let cover =
						document.querySelector('[name="cover_image"]');
					if(cover && cover.files.length > 0){
						let reader = new FileReader();
						reader.onload = function(e){
							document.querySelector("#summary_cover").src =
								e.target.result;

						}
						reader.readAsDataURL(cover.files[0]);
					}
				}
					document.addEventListener("input", function(e){


						if(
							e.target.matches(
								'[name="title"], [name="price"], [name="pages"], [name="publication_year"], [name="short_description"]'
							)
						){

							updateBookSummary();

						}
					});

					document.addEventListener("change", function(e){


						if(
							e.target.matches(
								'[name="category_id"], [name="subcategory_id"], [name="language"], [name="type"], [name="cover_image"]'
							)
						){
							updateBookSummary();
						}
					});

					// Chargement initial

					updateBookSummary();
				});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
	    const editor = document.querySelector(".quilleditor");
			if(editor){
				const quill = new Quill(editor, {
					modules: {
						toolbar: '.quilltoolbar'
					},

					theme: 'snow'

				});
				const existingDescription = document.getElementById('long_description').value;

					if (existingDescription) {
						quill.root.innerHTML = existingDescription;
					}

				quill.on('text-change', function(){


					document.querySelector("#long_description").value =
						quill.root.innerHTML;


				});

			}

		});
	</script>


	<script>

		const bookTypes = document.querySelectorAll('input[name="type"]');

		const previewType = document.getElementById('preview_type');

		const previewTypeContainer = document.getElementById('previewTypeContainer');

		const textPreview = document.getElementById('textPreview');

		const pagesPreview = document.getElementById('pagesPreview');

		function updatePreviewFields() {

			const selectedType = document.querySelector('input[name="type"]:checked');

			if (!selectedType) return;

			// =============================
			// LIVRE AUDIO
			// =============================

			if (selectedType.value === 'audio') {

				previewTypeContainer.classList.add('d-none');

				textPreview.classList.remove('d-none');

				pagesPreview.classList.add('d-none');

				// On force toujours le type texte
				previewType.value = 'text';

			}

			// =============================
			// EBOOK
			// =============================

			else {

				previewTypeContainer.classList.remove('d-none');

				if (previewType.value === 'pages') {

					textPreview.classList.add('d-none');

					pagesPreview.classList.remove('d-none');

				} else {

					textPreview.classList.remove('d-none');

					pagesPreview.classList.add('d-none');

				}

			}

		}


		// Changement Ebook / Audio
		bookTypes.forEach(type => {

			type.addEventListener('change', updatePreviewFields);

		});


		// Changement du type d'aperçu
		previewType.addEventListener('change', updatePreviewFields);


		// Initialisation
		updatePreviewFields();

	</script>
    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>

			document.querySelectorAll('.delete-book-btn').forEach(button => {
				button.addEventListener('click', function(){
					const form = this.closest('.delete-book-form');
					Swal.fire({

						title: 'Supprimer ce livre ?',

						text: "Cette action est définitive. Le fichier et la couverture seront supprimés.",

						icon: 'warning',

						showCancelButton: true,

						confirmButtonText: 'Oui, supprimer',

						cancelButtonText: 'Annuler',

						reverseButtons: true,

						customClass: {

							popup: 'rounded-4',

							confirmButton: 'btn btn-danger px-4',

							cancelButton: 'btn btn-light px-4'

						},
						buttonsStyling: false
					}).then((result)=>{

						if(result.isConfirmed){

							form.submit();

						}
					});
				});
			});

	</script>
</body>
</html>