@extends('layouts.reader')

@section('reader-content')

<!-- Offcanvas menu button -->
<div class="d-grid mb-0 d-lg-none w-100">
	<button class="btn btn-primary mb-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
		<i class="fas fa-sliders-h"></i> Menu
	</button>
</div>

<div class="vstack gap-4">
	<!-- Notifications START -->
	<div class="card border">
		<!-- Card header -->
		<div class="card-header border-bottom bg-table-yellow">
			<h4 class="card-header-title">Notification Settings</h4>
		</div>

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
		<!-- Form START -->
		<form class="card-body"
			method="POST"
			action="{{ route('reader.notifications.update') }}">
			@csrf
			<!-- Switch -->
			<div class="form-check form-switch d-flex justify-content-between mb-4">
				<label class="form-check-label">
					Être informé lorsqu'un auteur que je suis publie un nouveau livre
				</label>

				<input class="form-check-input"
					type="checkbox"
					name="favorite_author_books"
					@checked(data_get($settings->settings, 'favorite_author_books'))>
			</div>

			<!-- Switch -->
			<div class="form-check form-switch d-flex justify-content-between mb-4">
				<label class="form-check-label">
					Recevoir des rappels pour reprendre mes lectures en cours
				</label>

				<input class="form-check-input"
					type="checkbox"
					name="reading_reminders"
					@checked(data_get($settings->settings, 'reading_reminders'))>
			</div>

			<!-- Switch -->
			<div class="form-check form-switch d-flex justify-content-between mb-4">
				<label class="form-check-label">
					Recevoir des recommandations de livres selon mes préférences
				</label>

				<input class="form-check-input"
					type="checkbox"
					name="recommendations"
					@checked(data_get($settings->settings, 'recommendations'))>
			</div>

				<!-- Switch -->
			<div class="form-check form-switch d-flex justify-content-between mb-4">
				<label class="form-check-label">
					Recevoir une notification lorsque l'achat d'un livre est confirmé.
				</label>

				<input class="form-check-input"
					type="checkbox"
					name="purchase_confirmation"
					@checked(data_get($settings->settings, 'purchase_confirmation'))>
			</div>

			<!-- Button -->
			<div class="d-sm-flex justify-content-end">
				<button type="submit" class="btn btn-sm btn-submit me-2 mb-0">Enregistrer</button>
			</div>
		</form>
		<!-- Form END -->
	</div>
	<!-- Notifications END -->
</div>

@endsection