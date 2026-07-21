@extends('layouts.reader')

@section('reader-content')
<div class="reader-workspace">
    <div class="d-grid mb-3 d-lg-none">
        <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <header class="reader-page-hero">
        <div>
            <span class="eyebrow">Préférences</span>
            <h1>Paramètres</h1>
            <p>Choisissez les notifications que vous souhaitez recevoir.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="reader-panel">
        <header>
            <h2>Notifications</h2>
            <p>Activez uniquement ce qui vous est utile.</p>
        </header>

        <form method="POST" action="{{ route('reader.notifications.update') }}" class="reader-form">
            @csrf

            <div class="reader-switch">
                <div>
                    <strong>Nouveautés d’auteurs suivis</strong>
                    <small>Quand un auteur que vous aimez publie un livre</small>
                </div>
                <input class="form-check-input" type="checkbox" name="favorite_author_books"
                       @checked(data_get($settings->settings, 'favorite_author_books'))>
            </div>

            <div class="reader-switch">
                <div>
                    <strong>Rappels de lecture</strong>
                    <small>Pour reprendre une lecture en cours</small>
                </div>
                <input class="form-check-input" type="checkbox" name="reading_reminders"
                       @checked(data_get($settings->settings, 'reading_reminders'))>
            </div>

            <div class="reader-switch">
                <div>
                    <strong>Recommandations</strong>
                    <small>Suggestions selon vos goûts</small>
                </div>
                <input class="form-check-input" type="checkbox" name="recommendations"
                       @checked(data_get($settings->settings, 'recommendations'))>
            </div>

            <div class="reader-switch">
                <div>
                    <strong>Confirmation d’achat</strong>
                    <small>Notification quand un paiement est confirmé</small>
                </div>
                <input class="form-check-input" type="checkbox" name="purchase_confirmation"
                       @checked(data_get($settings->settings, 'purchase_confirmation'))>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-danger">Enregistrer</button>
            </div>
        </form>
    </section>
</div>
@endsection
