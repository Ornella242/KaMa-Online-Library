
@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 520px; margin: 60px auto; padding: 24px;">
    <h1 style="font-size: 28px; margin-bottom: 12px;">
        Réinitialiser mon mot de passe
    </h1>

    <p style="color: #666; margin-bottom: 28px;">
        Choisissez un nouveau mot de passe pour votre compte KaMa.
    </p>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; margin-bottom: 8px;">
                Adresse e-mail
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
                autocomplete="email"
                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 8px;">
                Nouveau mot de passe
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 24px;">
            <label for="password_confirmation" style="display: block; margin-bottom: 8px;">
                Confirmer le nouveau mot de passe
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;"
            >
        </div>

        <button
            type="submit"
            style="width: 100%; padding: 14px; background: #b30000; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;"
        >
            Enregistrer le nouveau mot de passe
        </button>
    </form>
</div>
@endsection
