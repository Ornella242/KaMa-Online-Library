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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-4 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mt-4 mb-0">
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
                            · mis à jour {{ $fxMeta['date'] }}
                        @endif
                        · cache {{ (int) config('services.currencyfreaks.cache_ttl', 3600) / 60 }} min
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

            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom kama-card-header">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <span class="section-icon">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        Informations personnelles
                    </h5>
                </div>

                <form class="row g-3" method="POST" action="{{ route('admin.account.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 gap-4 flex-wrap">
                            <div class="text-center">
                                <label for="uploadfile-1" class="position-relative mb-0">
                                    <img
                                        src="{{ Auth::user()->avatar
                                            ? asset('storage/'.Auth::user()->avatar)
                                            : asset('assets/images/avatar/01.jpg') }}"
                                        class="rounded-circle shadow border border-3"
                                        style="width:120px;height:120px;object-fit:cover;cursor:pointer;"
                                        alt="Avatar">
                                    <input id="uploadfile-1" type="file" name="avatar" class="d-none">
                                </label>
                                <p class="medium fw-semibold text-black mt-2 mb-0">
                                    Cliquez sur la photo pour la modifier
                                </p>
                            </div>
                            <div>
                                <h5 class="mb-1">
                                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                </h5>
                                <span class="badge kama-role">Administrateur</span>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person text-danger me-1"></i>
                                    Prénom
                                </label>
                                <input type="text" class="form-control" name="firstname" value="{{ Auth::user()->firstname }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person-lines-fill text-danger me-1"></i>
                                    Nom
                                </label>
                                <input type="text" class="form-control" name="lastname" value="{{ Auth::user()->lastname }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-envelope-fill text-primary me-1"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    Téléphone
                                </label>
                                <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-globe text-warning me-1"></i>
                                    Pays
                                </label>
                                <select name="country_id" class="form-select rounded-3 shadow-sm" required>
                                    <option value="">Sélectionnez votre pays</option>
                                    @foreach($countries as $country)
                                        <option
                                            value="{{ $country->id }}"
                                            {{ old('country_id', optional(Auth::user()->country)->id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->flag }} {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt text-warning me-1"></i>
                                    Ville
                                </label>
                                <input type="text" class="form-control" name="city" value="{{ Auth::user()->city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-gender-ambiguous text-info me-1"></i>
                                    Genre
                                </label>
                                <div>
                                    <input type="radio" class="btn-check" name="gender" id="male" value="male"
                                           {{ Auth::user()->gender=='male'?'checked':'' }}>
                                    <label class="btn btn-outline-danger rounded-start" for="male">Homme</label>

                                    <input type="radio" class="btn-check" name="gender" id="female" value="female"
                                           {{ Auth::user()->gender=='female'?'checked':'' }}>
                                    <label class="btn btn-outline-danger" for="female">Femme</label>

                                    <input type="radio" class="btn-check" name="gender" id="other" value="other"
                                           {{ Auth::user()->gender=='other'?'checked':'' }}>
                                    <label class="btn btn-outline-danger rounded-end" for="other">Autre</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-chat-square-text-fill text-primary me-1"></i>
                                    Biographie
                                </label>
                                <textarea class="form-control" name="bio" rows="4"
                                          placeholder="Parlez-nous de vous...">{{ old('bio', Auth::user()->bio) }}</textarea>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn kama-btn" type="submit">
                                <i class="bi bi-check-circle me-2"></i>
                                Modifier mon profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sécurité --}}
        <div class="tab-pane fade {{ $activeTab === 'securite' ? 'show active' : '' }}"
             id="tab-securite"
             role="tabpanel"
             aria-labelledby="tab-securite-btn"
             tabindex="0">

            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header kama-card-header border-bottom">
                            <h5 class="mb-0">
                                <span class="section-icon security">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                Sécurité
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.password.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-lock-fill text-danger me-1"></i>
                                        Mot de passe actuel
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <i class="bi bi-key text-danger"></i>
                                        </span>
                                        <input type="password"
                                               class="form-control"
                                               name="current_password"
                                               placeholder="Mot de passe actuel">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-shield-lock-fill text-primary me-1"></i>
                                        Nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <i class="fas fa-eye-slash cursor-pointer toggle-password" data-target="password"></i>
                                        </span>
                                        <input type="password"
                                               id="password"
                                               class="form-control"
                                               name="password"
                                               placeholder="Nouveau mot de passe">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        Confirmation du mot de passe
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <i class="fas fa-eye-slash cursor-pointer toggle-password"
                                               data-target="password_confirmation"></i>
                                        </span>
                                        <input type="password"
                                               id="password_confirmation"
                                               class="form-control"
                                               name="password_confirmation"
                                               placeholder="Confirmez votre nouveau mot de passe">
                                    </div>
                                </div>

                                <div class="password-info mb-4">
                                    <h6 class="mb-2">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Conseils de sécurité
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Au moins 8 caractères</li>
                                        <li>Une lettre majuscule</li>
                                        <li>Un chiffre</li>
                                        <li>Un caractère spécial</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn kama-btn w-100">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Changer le mot de passe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
