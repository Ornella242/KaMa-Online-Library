<!DOCTYPE html>
<html lang="en">
<head>
	<title>KaMa Online Library</title>
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="KaMa - KaMa Online Library est une librairie numérique dédiée à la valorisation de la littérature africaine. Elle propose un accès simple et rapide à une large sélection d’ouvrages d’auteurs africains, disponibles en formats ebook et audiolivre, permettant aux lecteurs de découvrir, lire et écouter des histoires authentiques issues du continent et de sa diaspora.">

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
	

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

</head>

<body>

    @include('partials.header')

	 {{-- SIDEBAR DYNAMIQUE --}}
        {{-- @auth
            @if(auth()->user()->role === 'reader')
                @include('partials.sidebar-reader')

            @elseif(auth()->user()->role === 'author')
                @include('partials.sidebar-author')

            @elseif(auth()->user()->role === 'admin')
                @include('partials.sidebar-admin')
            @endif
        @endauth --}}
		

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

    <!-- ThemeFunctions -->
    <script src="assets/js/functions.js"></script>
</body>
</html>