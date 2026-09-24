@extends('layouts.admin')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')

@section('admin-content')
@php
    $activeTab = $activeTab ?? 'commerce';
@endphp

<div class="admin-profile-settings admin-settings-tabs-page">

    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="setting-icon">
                    <i class="bi bi-person-gear"></i>
                </div>
                <div>
                    <h3 class="h3 mb-1">Paramètres de la plateforme</h3>
                    <p class="text-black mb-0">
                        Commerce, Mobile Money, profil et sécurité — un onglet à la fois.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills admin-settings-nav mb-4" id="adminSettingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'commerce' ? 'active' : '' }}"
                    id="tab-commerce-btn"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-commerce"
                    data-settings-tab="commerce"
                    type="button"
                    role="tab"
                    aria-controls="tab-commerce"
                    aria-selected="{{ $activeTab === 'commerce' ? 'true' : 'false' }}">
                <i class="bi bi-cash-stack"></i>
                <span>Commerce</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'momo' ? 'active' : '' }}"
                    id="tab-momo-btn"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-momo"
                    data-settings-tab="momo"
                    type="button"
                    role="tab"
                    aria-controls="tab-momo"
                    aria-selected="{{ $activeTab === 'momo' ? 'true' : 'false' }}">
                <i class="bi bi-phone"></i>
                <span>Mobile Money</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'profil' ? 'active' : '' }}"
                    id="tab-profil-btn"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-profil"
                    data-settings-tab="profil"
                    type="button"
                    role="tab"
                    aria-controls="tab-profil"
                    aria-selected="{{ $activeTab === 'profil' ? 'true' : 'false' }}">
                <i class="bi bi-person-vcard"></i>
                <span>Profil</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'securite' ? 'active' : '' }}"
                    id="tab-securite-btn"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-securite"
                    data-settings-tab="securite"
                    type="button"
                    role="tab"
                    aria-controls="tab-securite"
                    aria-selected="{{ $activeTab === 'securite' ? 'true' : 'false' }}">
                <i class="bi bi-shield-check"></i>
                <span>Sécurité</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminSettingsTabsContent">

        {{-- Commerce --}}
        <div class="tab-pane fade {{ $activeTab === 'commerce' ? 'show active' : '' }}"
             id="tab-commerce"
             role="tabpanel"
             aria-labelledby="tab-commerce-btn"
             tabindex="0">

            <section class="admin-publication-fees">
                <div class="admin-publication-fees-header">
                    <div>
                        <span>Configuration commerciale</span>
                        <h4>Frais de publication des livres</h4>
                        <p>Ces montants sont appliqués automatiquement selon le format choisi par l’écrivain.</p>
                    </div>
                    <span class="admin-publication-fees-lock">
                        <i class="bi bi-shield-lock"></i> Montants contrôlés par l’administration
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.settings.publication-fees.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="admin-publication-fees-grid">
                        <label>
                            <span class="admin-publication-fee-icon"><i class="bi bi-file-earmark-text"></i></span>
                            <div>
                                <strong>Livre écrit — Ebook</strong>
                                <small>Frais demandés pour le dépôt d’un fichier PDF.</small>
                                <div class="admin-publication-fee-input">
                                    <input type="number"
                                           name="ebook_amount"
                                           min="0.01"
                                           max="999999"
                                           step="0.01"
                                           value="{{ old('ebook_amount', data_get($publicationFees, 'ebook.amount', 10)) }}"
                                           required>
                                    <span class="fee-currency-preview">EUR</span>
                                </div>
                            </div>
                        </label>

                        <label>
                            <span class="admin-publication-fee-icon audio"><i class="bi bi-headphones"></i></span>
                            <div>
                                <strong>Livre audio</strong>
                                <small>Frais demandés pour le dépôt d’un fichier audio.</small>
                                <div class="admin-publication-fee-input">
                                    <input type="number"
                                           name="audio_amount"
                                           min="0.01"
                                           max="999999"
                                           step="0.01"
                                           value="{{ old('audio_amount', data_get($publicationFees, 'audio.amount', 15)) }}"
                                           required>
                                    <span class="fee-currency-preview">EUR</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="admin-publication-fees-footer">
                        <div>
                            <label for="publication-fee-currency">Devise</label>
                            <input id="publication-fee-currency"
                                   type="text"
                                   name="currency"
                                   maxlength="3"
                                   value="{{ old('currency', data_get($publicationFees, 'ebook.currency', 'EUR')) }}"
                                   readonly
                                   required>
                        </div>
                        <p>
                            <i class="bi bi-info-circle"></i>
                            Les nouvelles valeurs s’appliquent aux prochaines demandes de paiement uniquement.
                        </p>
                        <button type="submit">
                            <i class="bi bi-check2-circle"></i> Enregistrer les frais
                        </button>
                    </div>
                </form>
            </section>

            <section class="admin-publication-fees" style="margin-top:22px;">
                <div class="admin-publication-fees-header">
                    <div>
                        <span>Configuration commerciale</span>
                        <h4>Retraits auteurs</h4>
                        <p>Commission prélevée sur chaque demande de retrait et montant minimum autorisé.</p>
                    </div>
                    <span class="admin-publication-fees-lock">
                        <i class="bi bi-percent"></i> Appliqué à chaque nouvelle demande
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.settings.withdrawal.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="admin-publication-fees-grid">
                        <label>
                            <span class="admin-publication-fee-icon"><i class="bi bi-percent"></i></span>
                            <div>
                                <strong>Commission de retrait</strong>
                                <small>Pourcentage prélevé par KaMa sur le montant demandé.</small>
                                <div class="admin-publication-fee-input">
                                    <input type="number"
                                           name="withdrawal_commission_percent"
                                           min="0"
                                           max="50"
                                           step="0.01"
                                           value="{{ old('withdrawal_commission_percent', $withdrawalCommissionPercent) }}"
                                           required>
                                    <span class="fee-currency-preview">%</span>
                                </div>
                            </div>
                        </label>

                        <label>
                            <span class="admin-publication-fee-icon audio"><i class="bi bi-cash-coin"></i></span>
                            <div>
                                <strong>Montant minimum</strong>
                                <small>Seuil en dessous duquel un retrait n’est pas possible.</small>
                                <div class="admin-publication-fee-input">
                                    <input type="number"
                                           name="withdrawal_minimum_amount"
                                           min="1"
                                           max="999999"
                                           step="0.01"
                                           value="{{ old('withdrawal_minimum_amount', $withdrawalMinimumAmount) }}"
                                           required>
                                    <span class="fee-currency-preview">EUR</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="admin-publication-fees-footer">
                        <p>
                            <i class="bi bi-info-circle"></i>
                            Exemple : 100 € demandés avec 5 % → commission 5 €, net versé 95 €.
                        </p>
                        <button type="submit">
                            <i class="bi bi-check2-circle"></i> Enregistrer les retraits
                        </button>
                    </div>
                </form>
            </section>
        </div>

        {{-- Mobile Money --}}
        <div class="tab-pane fade {{ $activeTab === 'momo' ? 'show active' : '' }}"
             id="tab-momo"
             role="tabpanel"
             aria-labelledby="tab-momo-btn"
             tabindex="0">

            <section class="admin-publication-fees admin-momo-rates">
                <div class="admin-publication-fees-header">
                    <div>
                        <span>CurrencyFreaks</span>
                        <h4>Taux Mobile Money (1 EUR → devise locale)</h4>
                        <p>Taux live récupérés automatiquement. Plus de saisie manuelle.</p>
                    </div>
                    <span class="admin-publication-fees-lock">
                        <i class="bi bi-currency-exchange"></i> {{ count($pawaPayRates) }} devises
                    </span>
                </div>

                @if(!($fxMeta['configured'] ?? false))
                    <div class="alert alert-warning m-4 mb-0">
                        <strong>Clé API manquante.</strong>
                        Ajoutez <code>CURRENCYFREAKS_API_KEY</code> dans le fichier <code>.env</code>
                        (clé gratuite sur
                        <a href="https://currencyfreaks.com/signup" target="_blank" rel="noopener">currencyfreaks.com</a>).
                    </div>
                @elseif(!empty($fxMeta['error']))
                    <div class="alert alert-danger m-4 mb-0">
                        {{ $fxMeta['error'] }}
                    </div>
                @endif

                <div class="admin-momo-rates-grid">
                    @forelse($pawaPayRates as $currency => $rate)
                        @php
                            $meta = $pawaPayCurrencyMeta[$currency] ?? null;
                        @endphp
                        <div class="admin-momo-rate-card">
                            <div class="admin-momo-rate-top">
                                <strong>{{ $currency }}</strong>
                            </div>
                            <span class="admin-momo-rate-name">{{ $meta['label'] ?? $currency }}</span>
                            @if(!empty($meta['countries']))
                                <small class="admin-momo-rate-countries">{{ $meta['countries'] }}</small>
                            @endif
                            <div class="admin-momo-rate-value">
                                <span>{{ number_format((float) $rate, 4, ',', ' ') }}</span>
                                <small>/ EUR</small>
                            </div>
                        </div>
                    @empty
                        <div class="admin-momo-rate-empty">
                            Aucun taux affiché pour le moment.
                        </div>
                    @endforelse
                </div>

                <div class="admin-publication-fees-footer">
                    <p>
                        <i class="bi bi-info-circle"></i>
                        Source :
                        <strong>{{ $fxMeta['source'] ?? '—' }}</strong>
                        @if(!empty($fxMeta['date']))
                            · {{ $fxMeta['date'] }}
                        @endif
                        · aucune marge KaMa
                        @if(($fxMeta['source'] ?? '') === 'currencyfreaks_usd_cross')
                            · plan gratuit = croisement USD (identique à (USD→devise)/(USD→EUR))
                        @endif
                    </p>
                    <form method="POST" action="{{ route('admin.settings.pawapay-rates.update') }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" @disabled(!($fxMeta['configured'] ?? false))>
                            <i class="bi bi-arrow-repeat"></i> Rafraîchir les taux
                        </button>
                    </form>
                </div>
            </section>
        </div>

        {{-- Profil --}}
        <div class="tab-pane fade {{ $activeTab === 'profil' ? 'show active' : '' }}"
            id="tab-profil"
            role="tabpanel"
            aria-labelledby="tab-profil-btn"
            tabindex="0">

            <section class="admin-profile-section">

                {{-- HEADER --}}
                <div class="admin-profile-header">
                    <div>
                        <span>Configuration du compte</span>
                        <h4 class="admin-profile-headerh4">Informations personnelles</h4>
                        <p>Gérez vos informations personnelles, votre photo de profil et vos coordonnées.</p>
                    </div>

                    <span class="admin-profile-badge">
                        <i class="bi bi-shield-check"></i>
                        Compte administrateur
                    </span>
                </div>

                <form method="POST"
                    action="{{ route('admin.account.update') }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    {{-- PROFIL --}}
                    <div class="admin-profile-content">

                        {{-- AVATAR --}}
                        <div class="admin-profile-identity">

                            <div class="admin-profile-avatar-wrapper">
                                <label for="uploadfile-1" class="admin-profile-avatar-label">

                                    <img
                                        src="{{ Auth::user()->avatar
                                            ? asset('storage/'.Auth::user()->avatar)
                                            : asset('assets/images/avatar/01.jpg') }}"
                                        class="admin-profile-avatar"
                                        alt="Avatar">

                                    <span class="admin-profile-avatar-overlay">
                                        <i class="bi bi-camera"></i>
                                    </span>

                                    <input id="uploadfile-1"
                                        type="file"
                                        name="avatar"
                                        class="d-none"
                                        accept="image/*">
                                </label>

                                <small>
                                    Cliquez sur la photo pour la modifier
                                </small>
                            </div>

                            <div class="admin-profile-identity-info">
                                <h5>
                                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                </h5>

                                <span class="admin-profile-role">
                                    <i class="bi bi-person-badge"></i>
                                    Administrateur
                                </span>

                                <p>
                                    {{ Auth::user()->email }}
                                </p>
                            </div>

                        </div>

                        {{-- INFORMATIONS --}}
                        <div class="admin-profile-block">

                            <div class="admin-profile-block-header">
                                <div>
                                    <span>Informations du compte</span>
                                    <h5>Identité et coordonnées</h5>
                                </div>

                                <i class="bi bi-person-vcard"></i>
                            </div>

                            <div class="admin-profile-grid">

                                {{-- PRÉNOM --}}
                                <div class="admin-profile-field">
                                    <label for="firstname">
                                        <i class="bi bi-person"></i>
                                        Prénom
                                    </label>

                                    <input
                                        id="firstname"
                                        type="text"
                                        name="firstname"
                                        value="{{ old('firstname', Auth::user()->firstname) }}"
                                        required>
                                </div>

                                {{-- NOM --}}
                                <div class="admin-profile-field">
                                    <label for="lastname">
                                        <i class="bi bi-person-lines-fill"></i>
                                        Nom
                                    </label>

                                    <input
                                        id="lastname"
                                        type="text"
                                        name="lastname"
                                        value="{{ old('lastname', Auth::user()->lastname) }}"
                                        required>
                                </div>

                                {{-- EMAIL --}}
                                <div class="admin-profile-field">
                                    <label for="email">
                                        <i class="bi bi-envelope"></i>
                                        Adresse email
                                    </label>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email', Auth::user()->email) }}"
                                        required>
                                </div>

                                {{-- TÉLÉPHONE --}}
                                <div class="admin-profile-field">
                                    <label for="phone">
                                        <i class="bi bi-telephone"></i>
                                        Téléphone
                                    </label>

                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', Auth::user()->phone) }}">
                                </div>

                                {{-- PAYS --}}
                                <div class="admin-profile-field">
                                    <label for="country_id">
                                        <i class="bi bi-globe"></i>
                                        Pays
                                    </label>

                                    <select
                                        id="country_id"
                                        name="country_id"
                                        required>

                                        <option value="">
                                            Sélectionnez votre pays
                                        </option>

                                        @foreach($countries as $country)
                                            <option
                                                value="{{ $country->id }}"
                                                {{ old('country_id', optional(Auth::user()->country)->id) == $country->id ? 'selected' : '' }}>

                                                {{ $country->flag }} {{ $country->name }}

                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- VILLE --}}
                                <div class="admin-profile-field">
                                    <label for="city">
                                        <i class="bi bi-geo-alt"></i>
                                        Ville
                                    </label>

                                    <input
                                        id="city"
                                        type="text"
                                        name="city"
                                        value="{{ old('city', Auth::user()->city) }}">
                                </div>

                            </div>

                        </div>

                        {{-- GENRE --}}
                        <div class="admin-profile-block">

                            <div class="admin-profile-block-header">
                                <div>
                                    <span>Préférences personnelles</span>
                                    <h5>Genre</h5>
                                </div>

                                <i class="bi bi-gender-ambiguous"></i>
                            </div>

                            <div class="admin-profile-gender">

                                <input type="radio"
                                    class="btn-check"
                                    name="gender"
                                    id="male"
                                    value="male"
                                    {{ Auth::user()->gender == 'male' ? 'checked' : '' }}>

                                <label for="male">
                                    <i class="bi bi-gender-male"></i>
                                    Homme
                                </label>


                                <input type="radio"
                                    class="btn-check"
                                    name="gender"
                                    id="female"
                                    value="female"
                                    {{ Auth::user()->gender == 'female' ? 'checked' : '' }}>

                                <label for="female">
                                    <i class="bi bi-gender-female"></i>
                                    Femme
                                </label>


                                <input type="radio"
                                    class="btn-check"
                                    name="gender"
                                    id="other"
                                    value="other"
                                    {{ Auth::user()->gender == 'other' ? 'checked' : '' }}>

                                <label for="other">
                                    <i class="bi bi-gender-ambiguous"></i>
                                    Autre
                                </label>

                            </div>

                        </div>

                        {{-- BIO --}}
                        <div class="admin-profile-block">

                            <div class="admin-profile-block-header">
                                <div>
                                    <span>Présentation</span>
                                    <h5>Biographie</h5>
                                </div>

                                <i class="bi bi-chat-square-text"></i>
                            </div>

                            <div class="admin-profile-bio">

                                <textarea
                                    name="bio"
                                    rows="5"
                                    placeholder="Parlez-nous de vous...">{{ old('bio', Auth::user()->bio) }}</textarea>

                                <small>
                                    Présentez brièvement votre profil ou ajoutez toute information utile.
                                </small>

                            </div>

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="admin-profile-footer">

                        <p>
                            <i class="bi bi-info-circle"></i>
                            Les modifications seront appliquées immédiatement à votre compte.
                        </p>

                        <button type="submit">
                            <i class="bi bi-check2-circle"></i>
                            Enregistrer les modifications
                        </button>

                    </div>

                </form>

            </section>

        </div>

        {{-- Sécurité --}}
        <div class="tab-pane fade {{ $activeTab === 'securite' ? 'show active' : '' }}"
            id="tab-securite"
            role="tabpanel"
            aria-labelledby="tab-securite-btn"
            tabindex="0">

            <section class="admin-security-section">

                {{-- HEADER --}}
                <div class="admin-security-header">
                    <div>
                        <span>Protection du compte</span>
                        <h4 class="admin-security-headerh4">Sécurité</h4>
                        <p>
                            Modifiez régulièrement votre mot de passe afin de protéger votre compte administrateur.
                        </p>
                    </div>

                    <span class="admin-security-badge">
                        <i class="bi bi-shield-check"></i>
                        Compte sécurisé
                    </span>
                </div>


                {{-- CONTENT --}}
                <div class="admin-security-content">

                    <form method="POST" action="{{ route('admin.password.update') }}">
                        @csrf
                        @method('PUT')


                        {{-- MOT DE PASSE ACTUEL --}}
                        <div class="admin-security-field">

                            <label for="current_password">
                                <span class="admin-security-field-icon">
                                    <i class="bi bi-key-fill"></i>
                                </span>

                                <span>
                                    <strong>Mot de passe actuel</strong>
                                    <small>
                                        Entrez votre mot de passe actuel pour continuer.
                                    </small>
                                </span>
                            </label>

                            <div class="admin-security-input">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="Votre mot de passe actuel">

                                <button
                                    type="button"
                                    class="toggle-password"
                                    data-target="current_password"
                                    aria-label="Afficher ou masquer le mot de passe">

                                    <i class="bi bi-eye-slash"></i>

                                </button>
                            </div>

                        </div>


                        {{-- NOUVEAU MOT DE PASSE --}}
                        <div class="admin-security-field">

                            <label for="password">
                                <span class="admin-security-field-icon">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </span>

                                <span>
                                    <strong>Nouveau mot de passe</strong>
                                    <small>
                                        Choisissez un mot de passe suffisamment robuste.
                                    </small>
                                </span>
                            </label>

                            <div class="admin-security-input">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Nouveau mot de passe">

                                <button
                                    type="button"
                                    class="toggle-password"
                                    data-target="password"
                                    aria-label="Afficher ou masquer le mot de passe">

                                    <i class="bi bi-eye-slash"></i>

                                </button>
                            </div>

                        </div>


                        {{-- CONFIRMATION --}}
                        <div class="admin-security-field">

                            <label for="password_confirmation">
                                <span class="admin-security-field-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </span>

                                <span>
                                    <strong>Confirmation du mot de passe</strong>
                                    <small>
                                        Saisissez à nouveau votre nouveau mot de passe.
                                    </small>
                                </span>
                            </label>

                            <div class="admin-security-input">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirmez votre nouveau mot de passe">

                                <button
                                    type="button"
                                    class="toggle-password"
                                    data-target="password_confirmation"
                                    aria-label="Afficher ou masquer le mot de passe">

                                    <i class="bi bi-eye-slash"></i>

                                </button>
                            </div>

                        </div>


                        {{-- CONSEILS --}}
                        <div class="admin-security-tips">

                            <div class="admin-security-tips-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>
                                <h6>
                                    Conseils de sécurité
                                </h6>

                                <p>
                                    Pour renforcer la sécurité de votre compte, votre mot de passe devrait contenir :
                                </p>

                                <div class="admin-security-requirements">

                                    <span>
                                        <i class="bi bi-check2"></i>
                                        Au moins 8 caractères
                                    </span>

                                    <span>
                                        <i class="bi bi-check2"></i>
                                        Une lettre majuscule
                                    </span>

                                    <span>
                                        <i class="bi bi-check2"></i>
                                        Un chiffre
                                    </span>

                                    <span>
                                        <i class="bi bi-check2"></i>
                                        Un caractère spécial
                                    </span>

                                </div>
                            </div>

                        </div>


                        {{-- FOOTER --}}
                        <div class="admin-security-footer">

                            <p>
                                <i class="bi bi-info-circle"></i>
                                Après modification, utilisez votre nouveau mot de passe lors de votre prochaine connexion.
                            </p>

                            <button type="submit">
                                <i class="bi bi-lock-fill"></i>
                                Modifier le mot de passe
                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>

.admin-security-section {
    border: 1px solid #e7e8eb;
    border-radius: 16px;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
}


/* HEADER */

.admin-security-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px;
    border-bottom: 1px solid #e7e8eb;
}

.admin-security-header > div > span {
    display: block;
    margin-bottom: 4px;
    color: #b30000;
    font-size: .72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.admin-security-header h4 {
    margin: 0;
    color: #18181b;
    font-size: 1.2rem;
    font-weight: 800;
}

.admin-security-header p {
    margin: 5px 0 0;
    color: #71717a;
    font-size: .82rem;
}


/* BADGE */

.admin-security-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
    font-size: .7rem;
    font-weight: 800;
    white-space: nowrap;
}

.admin-security-badge i {
    font-size: .9rem;
}


/* CONTENT */

.admin-security-content {
    width: 100%;
    padding: 24px;
}


/* FIELD */

.admin-security-field {
    display: grid;
    grid-template-columns: minmax(220px, .7fr) minmax(300px, 1.3fr);
    align-items: center;
    gap: 30px;
    padding: 20px 0;
    border-bottom: 1px solid #f0f0f1;
}

.admin-security-field:first-child {
    padding-top: 0;
}

.admin-security-field:last-of-type {
    border-bottom: 0;
}


/* LABEL */

.admin-security-field > label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin: 0;
    cursor: default;
}

.admin-security-field-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #fff5f5;
    color: #b30000;
}

.admin-security-field-icon i {
    font-size: 1rem;
}

.admin-security-field label > span:last-child {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.admin-security-field label strong {
    color: #27272a;
    font-size: .82rem;
    font-weight: 800;
}

.admin-security-field label small {
    color: #71717a;
    font-size: .68rem;
    line-height: 1.4;
}


/* INPUT */

.admin-security-input {
    position: relative;
}

.admin-security-input input {
    width: 100%;
    height: 46px;
    padding: 0 48px 0 14px;
    border: 1px solid #dfe1e5;
    border-radius: 10px;
    background: #fff;
    color: #18181b;
    font-size: .82rem;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.admin-security-input input::placeholder {
    color: #a1a1aa;
}

.admin-security-input input:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, .08);
}


/* EYE BUTTON */

.admin-security-input .toggle-password {
    position: absolute;
    top: 50%;
    right: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    transform: translateY(-50%);
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: #71717a;
    cursor: pointer;
    transition: all .2s ease;
}

.admin-security-input .toggle-password:hover {
    background: #fff5f5;
    color: #b30000;
}

.admin-security-input .toggle-password i {
    font-size: .95rem;
}


/* SECURITY TIPS */

.admin-security-tips {
    display: flex;
    gap: 14px;
    margin-top: 22px;
    padding: 18px;
    border: 1px solid #f0cfcf;
    border-radius: 12px;
    background: #fff8f8;
}

.admin-security-tips-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #b30000;
    color: #fff;
}

.admin-security-tips-icon i {
    font-size: 1rem;
}

.admin-security-tips h6 {
    margin: 0 0 4px;
    color: #27272a;
    font-size: .82rem;
    font-weight: 800;
}

.admin-security-tips p {
    margin: 0 0 12px;
    color: #71717a;
    font-size: .7rem;
}


/* REQUIREMENTS */

.admin-security-requirements {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 18px;
}

.admin-security-requirements span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #52525b;
    font-size: .68rem;
    font-weight: 600;
}

.admin-security-requirements i {
    color: #16a34a;
    font-size: .8rem;
}


/* FOOTER */

.admin-security-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #e7e8eb;
}

.admin-security-footer p {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin: 0;
    color: #71717a;
    font-size: .7rem;
    line-height: 1.4;
}

.admin-security-footer p i {
    flex-shrink: 0;
    color: #b30000;
}

.admin-security-footer button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    border: 0;
    border-radius: 10px;
    padding: 11px 17px;
    background: #b30000;
    color: #fff;
    font-size: .78rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(179, 0, 0, .18);
    transition: all .2s ease;
}

.admin-security-footer button:hover {
    background: #990000;
    transform: translateY(-1px);
}


/* RESPONSIVE */

@media (max-width: 800px) {

    .admin-security-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-security-content {
        max-width: none;
        padding: 20px;
    }

    .admin-security-field {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .admin-security-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .admin-security-footer button {
        width: 100%;
    }
}

@media (max-width: 550px) {

    .admin-security-header {
        padding: 18px;
    }

    .admin-security-content {
        padding: 18px;
    }

    .admin-security-requirements {
        flex-direction: column;
        gap: 7px;
    }
}

.admin-settings-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px;
    border: 1px solid #e7e8eb;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
}
.admin-settings-nav .nav-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    border-radius: 12px;
    color: #52525b;
    font-weight: 700;
    font-size: .88rem;
    border: 1px solid transparent;
}
.admin-settings-nav .nav-link i { font-size: 1rem; }
.admin-settings-nav .nav-link:hover {
    color: #b30000;
    background: #fff5f5;
}
.admin-settings-nav .nav-link.active {
    color: #fff;
    background: #b30000;
    box-shadow: 0 8px 18px rgba(179, 0, 0, .22);
}
.admin-momo-rates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
    padding: 24px;
}
.admin-momo-rate-card {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin: 0;
    padding: 18px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fafafa;
    min-height: 168px;
}
.admin-momo-rate-card.is-pegged {
    background: #fff8f8;
    border-color: #f0cfcf;
}
.admin-momo-rate-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.admin-momo-rate-top strong {
    color: #18181b;
    font-size: 1.05rem;
    letter-spacing: .04em;
}
.admin-momo-rate-badge {
    padding: 3px 8px;
    border-radius: 999px;
    background: #b30000;
    color: #fff;
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.admin-momo-rate-name {
    color: #3f3f46;
    font-size: .82rem;
    font-weight: 600;
}
.admin-momo-rate-countries {
    display: block;
    margin: 0 0 8px;
    color: #71717a;
    font-size: .72rem;
    line-height: 1.35;
    min-height: 2.1em;
}
.admin-momo-rate-card .admin-publication-fee-input {
    margin-top: auto;
}
.admin-momo-rate-card .admin-publication-fee-input input {
    font-size: 1.05rem;
}
.admin-momo-rate-value {
    margin-top: auto;
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding-top: 8px;
}
.admin-momo-rate-value span {
    color: #18181b;
    font-size: 1.2rem;
    font-weight: 800;
}
.admin-momo-rate-value small {
    color: #71717a;
    font-size: .72rem;
    font-weight: 700;
}
.admin-momo-rate-empty {
    grid-column: 1 / -1;
    padding: 28px;
    color: #71717a;
    text-align: center;
}
@media (max-width: 700px) {
    .admin-settings-nav .nav-link span { display: none; }
    .admin-settings-nav .nav-link { padding: 11px 14px; }
    .admin-momo-rates-grid { grid-template-columns: 1fr; padding: 16px; }
}

/* =========================================================
   ADMIN PROFILE
   ========================================================= */

.admin-profile-section {
    border: 1px solid #e7e8eb;
    border-radius: 16px;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
}

/* HEADER */

.admin-profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px;
    border-bottom: 1px solid #e7e8eb;
}

.admin-profile-header > div > span {
    display: block;
    margin-bottom: 4px;
    color: #b30000;
    font-size: .72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.admin-profile-header h4 {
    margin: 0;
    color: #18181b;
    font-size: 1.2rem;
    font-weight: 800;
}

.admin-profile-header p {
    margin: 5px 0 0;
    color: #71717a;
    font-size: .82rem;
}

.admin-profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #fff5f5;
    color: #b30000;
    border: 1px solid #f0cfcf;
    font-size: .7rem;
    font-weight: 800;
    white-space: nowrap;
}

.admin-profile-badge i {
    font-size: .9rem;
}


/* CONTENT */

.admin-profile-content {
    padding: 24px;
}


/* IDENTITY */

.admin-profile-identity {
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 20px;
    margin-bottom: 22px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fafafa;
}

.admin-profile-avatar-wrapper {
    flex-shrink: 0;
    text-align: center;
}

.admin-profile-avatar-label {
    position: relative;
    display: block;
    width: 110px;
    height: 110px;
    cursor: pointer;
}

.admin-profile-avatar {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .12);
}

.admin-profile-avatar-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(0, 0, 0, .48);
    color: #fff;
    opacity: 0;
    transition: opacity .2s ease;
}

.admin-profile-avatar-label:hover .admin-profile-avatar-overlay {
    opacity: 1;
}

.admin-profile-avatar-overlay i {
    font-size: 1.35rem;
}

.admin-profile-avatar-wrapper small {
    display: block;
    margin-top: 8px;
    color: #71717a;
    font-size: .68rem;
}

.admin-profile-identity-info h5 {
    margin: 0 0 8px;
    color: #18181b;
    font-size: 1.15rem;
    font-weight: 800;
}

.admin-profile-role {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    background: #b30000;
    color: #fff;
    font-size: .68rem;
    font-weight: 800;
}

.admin-profile-identity-info p {
    margin: 10px 0 0;
    color: #71717a;
    font-size: .8rem;
}


/* BLOCKS */

.admin-profile-block {
    margin-top: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #fff;
    overflow: hidden;
}

.admin-profile-block-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #fafafa;
}

.admin-profile-block-header span {
    display: block;
    margin-bottom: 3px;
    color: #b30000;
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
}

.admin-profile-block-header h5 {
    margin: 0;
    color: #18181b;
    font-size: .98rem;
    font-weight: 800;
}

.admin-profile-block-header > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #fff5f5;
    color: #b30000;
    font-size: 1rem;
}


/* FORM GRID */

.admin-profile-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    padding: 20px;
}

.admin-profile-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.admin-profile-field label {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #3f3f46;
    font-size: .78rem;
    font-weight: 800;
}

.admin-profile-field label i {
    color: #b30000;
    font-size: .9rem;
}

.admin-profile-field input,
.admin-profile-field select,
.admin-profile-bio textarea {
    width: 100%;
    border: 1px solid #dfe1e5;
    border-radius: 10px;
    background: #fff;
    color: #18181b;
    padding: 11px 13px;
    font-size: .84rem;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.admin-profile-field input:focus,
.admin-profile-field select:focus,
.admin-profile-bio textarea:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, .08);
}

.admin-profile-field select {
    cursor: pointer;
}


/* GENDER */

.admin-profile-gender {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 20px;
}

.admin-profile-gender label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 120px;
    padding: 11px 16px;
    border: 1px solid #dfe1e5;
    border-radius: 10px;
    background: #fff;
    color: #52525b;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.admin-profile-gender label i {
    font-size: 1rem;
}

.admin-profile-gender label:hover {
    border-color: #b30000;
    color: #b30000;
    background: #fff8f8;
}

.admin-profile-gender .btn-check:checked + label {
    border-color: #b30000;
    background: #b30000;
    color: #fff;
    box-shadow: 0 5px 14px rgba(179, 0, 0, .18);
}


/* BIO */

.admin-profile-bio {
    padding: 20px;
}

.admin-profile-bio textarea {
    min-height: 130px;
    resize: vertical;
}

.admin-profile-bio small {
    display: block;
    margin-top: 7px;
    color: #71717a;
    font-size: .7rem;
}


/* FOOTER */

.admin-profile-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 24px;
    border-top: 1px solid #e7e8eb;
    background: #fafafa;
}

.admin-profile-footer p {
    display: flex;
    align-items: center;
    gap: 7px;
    margin: 0;
    color: #71717a;
    font-size: .72rem;
}

.admin-profile-footer p i {
    color: #b30000;
}

.admin-profile-footer button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 0;
    border-radius: 10px;
    padding: 11px 17px;
    background: #b30000;
    color: #fff;
    font-size: .78rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(179, 0, 0, .18);
    transition: all .2s ease;
}

.admin-profile-footer button:hover {
    background: #990000;
    transform: translateY(-1px);
}


/* RESPONSIVE */

@media (max-width: 800px) {

    .admin-profile-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-profile-identity {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-profile-grid {
        grid-template-columns: 1fr;
    }

    .admin-profile-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-profile-footer button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 500px) {

    .admin-profile-content,
    .admin-profile-header {
        padding: 16px;
    }

    .admin-profile-identity {
        padding: 16px;
    }

    .admin-profile-gender {
        flex-direction: column;
    }

    .admin-profile-gender label {
        width: 100%;
    }
}

</style>
@endpush

@push('scripts')
<script>
(() => {
    const tabs = document.querySelectorAll('#adminSettingsTabs [data-settings-tab]');
    if (!tabs.length) return;

    const setTabInUrl = (tab) => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    };

    tabs.forEach((btn) => {
        btn.addEventListener('shown.bs.tab', () => {
            setTabInUrl(btn.dataset.settingsTab);
        });
    });
})();
</script>
@endpush
