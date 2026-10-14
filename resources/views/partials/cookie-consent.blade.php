{{-- =========================================================
     KaMa — BANNIÈRE ET CENTRE DE GESTION DES COOKIES
========================================================= --}}

<!-- BANNIÈRE FLOTTANTE DE CONSENTEMENT -->
<div id="kamaCookieBanner" class="kama-cookie-banner" role="region" aria-label="Consentement aux cookies" style="display: none;">
    <div class="kama-cookie-banner-inner">
        <div class="kama-cookie-banner-content">
            <div class="kama-cookie-header">
                <span class="kama-cookie-icon-wrapper">
                    <i class="bi bi-shield-check"></i>
                </span>
                <div>
                    <span class="kama-cookie-badge">Vie privée &amp; Transparence</span>
                    <h3 class="kama-cookie-title">Gestion de vos cookies sur KaMa 🍪</h3>
                </div>
            </div>

            <p class="kama-cookie-text">
                Nous utilisons des cookies pour assurer le bon fonctionnement de la plateforme, sécuriser vos accès et paiements,
                mémoriser vos préférences et mesurer l'audience. Vous pouvez accepter tous les cookies, refuser les cookies non essentiels
                ou personnaliser vos choix selon vos préférences.
                <a href="{{ route('legal.cookies') }}" class="kama-cookie-learn-more" target="_blank" rel="noopener">
                    Consulter notre politique <i class="bi bi-box-arrow-up-right"></i>
                </a>
            </p>
        </div>

        <div class="kama-cookie-banner-actions">
            <button type="button" class="kama-cookie-btn kama-cookie-btn-primary" id="kamaCookieAcceptAllBtn">
                <i class="bi bi-check2-circle"></i>
                <span>Tout accepter</span>
            </button>

            <button type="button" class="kama-cookie-btn kama-cookie-btn-secondary" id="kamaCookieRejectAllBtn">
                <span>Refuser non-essentiels</span>
            </button>

            <button type="button" class="kama-cookie-btn kama-cookie-btn-outline" id="kamaCookieCustomizeBtn">
                <i class="bi bi-sliders"></i>
                <span>Personnaliser</span>
            </button>
        </div>
    </div>
</div>

<!-- MODAL DU CENTRE DE PRÉFÉRENCES -->
<div id="kamaCookieModal" class="kama-cookie-modal" role="dialog" aria-modal="true" aria-labelledby="kamaCookieModalTitle" style="display: none;">
    <div class="kama-cookie-modal-backdrop" id="kamaCookieModalBackdrop"></div>

    <div class="kama-cookie-modal-dialog">
        <div class="kama-cookie-modal-header">
            <div>
                <span class="kama-cookie-badge">Paramètres de confidentialité</span>
                <h3 id="kamaCookieModalTitle">Centre de préférences des cookies</h3>
            </div>
            <button type="button" class="kama-cookie-modal-close" id="kamaCookieModalCloseBtn" aria-label="Fermer la fenêtre">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="kama-cookie-modal-body">
            <p class="kama-cookie-modal-intro">
                Lors de votre navigation sur KaMa, des cookies sont déposés sur votre appareil. Vous pouvez choisir d'activer ou de
                désactiver chaque catégorie de cookies ci-dessous. Vos choix sont enregistrés pour une durée de <strong>6 mois</strong>
                et peuvent être modifiés à tout moment via le lien « Gestion des cookies » en bas de page.
            </p>

            <div class="kama-cookie-categories">
                <!-- 1. ESSENTIELS -->
                <div class="kama-cookie-category-card required">
                    <div class="kama-cookie-category-header">
                        <div class="kama-cookie-category-title">
                            <span class="kama-cookie-cat-icon"><i class="bi bi-shield-lock-fill"></i></span>
                            <div>
                                <h4>1. Cookies strictement nécessaires</h4>
                                <span class="kama-cookie-tag required">Toujours actif</span>
                            </div>
                        </div>
                        <div class="kama-cookie-switch-wrapper">
                            <input type="checkbox" checked disabled id="kamaConsentEssential" aria-label="Cookies nécessaires activés">
                            <span class="kama-cookie-switch-label">Obligatoire</span>
                        </div>
                    </div>
                    <p class="kama-cookie-category-desc">
                        Indispensables au fonctionnement de KaMa : maintien de votre session de connexion, sécurité CSRF des formulaires,
                        double authentification (2FA), panier d'achat et sécurisation des transactions. Ils ne peuvent pas être désactivés.
                    </p>
                </div>

                <!-- 2. FONCTIONNELS -->
                <div class="kama-cookie-category-card">
                    <div class="kama-cookie-category-header">
                        <div class="kama-cookie-category-title">
                            <span class="kama-cookie-cat-icon"><i class="bi bi-sliders2"></i></span>
                            <div>
                                <h4>2. Cookies de fonctionnalités &amp; préférences</h4>
                                <span class="kama-cookie-tag optional">Optionnel</span>
                            </div>
                        </div>
                        <label class="kama-cookie-switch" for="kamaConsentFunctional">
                            <input type="checkbox" id="kamaConsentFunctional" checked>
                            <span class="kama-cookie-slider"></span>
                        </label>
                    </div>
                    <p class="kama-cookie-category-desc">
                        Permettent d'enregistrer vos paramètres de confort : taille de police et thème du lecteur d'ebook, mémorisation
                        du lecteur audio, devises d'affichage (€) et options de tri du catalogue.
                    </p>
                </div>

                <!-- 3. MESURE D'AUDIENCE -->
                <div class="kama-cookie-category-card">
                    <div class="kama-cookie-category-header">
                        <div class="kama-cookie-category-title">
                            <span class="kama-cookie-cat-icon"><i class="bi bi-graph-up-arrow"></i></span>
                            <div>
                                <h4>3. Cookies de mesure d'audience &amp; statistiques</h4>
                                <span class="kama-cookie-tag optional">Optionnel</span>
                            </div>
                        </div>
                        <label class="kama-cookie-switch" for="kamaConsentAnalytics">
                            <input type="checkbox" id="kamaConsentAnalytics" checked>
                            <span class="kama-cookie-slider"></span>
                        </label>
                    </div>
                    <p class="kama-cookie-category-desc">
                        Permettent de collecter des informations anonymes sur l'utilisation du site (pages consultées, temps de lecture,
                        erreurs de navigation) afin d'améliorer la réactivité, l'ergonomie et le catalogue de KaMa.
                    </p>
                </div>

                <!-- 4. MARKETING & RECOMMANDATIONS -->
                <div class="kama-cookie-category-card">
                    <div class="kama-cookie-category-header">
                        <div class="kama-cookie-category-title">
                            <span class="kama-cookie-cat-icon"><i class="bi bi-bullseye"></i></span>
                            <div>
                                <h4>4. Cookies de personnalisation &amp; recommandations</h4>
                                <span class="kama-cookie-tag optional">Optionnel</span>
                            </div>
                        </div>
                        <label class="kama-cookie-switch" for="kamaConsentMarketing">
                            <input type="checkbox" id="kamaConsentMarketing">
                            <span class="kama-cookie-slider"></span>
                        </label>
                    </div>
                    <p class="kama-cookie-category-desc">
                        Permettent de vous suggérer des ouvrages et des auteurs africains adaptés à vos goûts littéraires et de mesurer
                        l'efficacité de nos campagnes d'information et de sponsoring.
                    </p>
                </div>
            </div>
        </div>

        <div class="kama-cookie-modal-footer">
            <div class="kama-cookie-footer-left">
                <button type="button" class="kama-cookie-btn kama-cookie-btn-secondary" id="kamaModalRejectAllBtn">
                    Tout refuser
                </button>
                <button type="button" class="kama-cookie-btn kama-cookie-btn-secondary" id="kamaModalAcceptAllBtn">
                    Tout accepter
                </button>
            </div>
            <div class="kama-cookie-footer-right">
                <button type="button" class="kama-cookie-btn kama-cookie-btn-primary" id="kamaModalSaveBtn">
                    <i class="bi bi-check2"></i>
                    <span>Enregistrer mes choix</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TOAST DE CONFIRMATION -->
<div id="kamaCookieToast" class="kama-cookie-toast" role="status" aria-live="polite" style="display: none;">
    <i class="bi bi-check-circle-fill"></i>
    <span>Vos préférences de cookies ont bien été enregistrées.</span>
</div>

<style>
/* =========================================================
   STYLE BANNIÈRE & MODAL COOKIES KAMA
========================================================= */
:root {
    --kama-cookie-red: #b30000;
    --kama-cookie-red-hover: #990000;
    --kama-cookie-dark: #111827;
    --kama-cookie-gray: #4b5563;
    --kama-cookie-border: #e5e7eb;
    --kama-cookie-bg-card: #ffffff;
}

/* BANNIÈRE FLOTTANTE */
.kama-cookie-banner {
    position: fixed;
    bottom: 24px;
    right: 24px;
    left: 24px;
    max-width: 960px;
    margin: 0 auto;
    z-index: 999990;
    background: #ffffff;
    border: 1px solid rgba(179, 0, 0, 0.15);
    border-radius: 20px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
    animation: kamaSlideUpFade 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    overflow: hidden;
}

@keyframes kamaSlideUpFade {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.kama-cookie-banner-inner {
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

@media (min-width: 992px) {
    .kama-cookie-banner-inner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
    }
}

.kama-cookie-banner-content {
    flex: 1;
}

.kama-cookie-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 10px;
}

.kama-cookie-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #fff5f5;
    color: var(--kama-cookie-red);
    font-size: 22px;
    flex-shrink: 0;
    border: 1px solid rgba(179, 0, 0, 0.15);
}

.kama-cookie-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--kama-cookie-red);
    margin-bottom: 2px;
}

.kama-cookie-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--kama-cookie-dark);
    margin: 0;
    line-height: 1.3;
}

.kama-cookie-text {
    font-size: 13.5px;
    line-height: 1.65;
    color: var(--kama-cookie-gray);
    margin: 0;
}

.kama-cookie-learn-more {
    color: var(--kama-cookie-red);
    font-weight: 600;
    text-decoration: none;
    margin-left: 6px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    transition: opacity 0.2s;
}

.kama-cookie-learn-more:hover {
    text-decoration: underline;
    color: var(--kama-cookie-red-hover);
}

.kama-cookie-banner-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

@media (max-width: 576px) {
    .kama-cookie-banner-actions {
        flex-direction: column;
        width: 100%;
    }
    .kama-cookie-banner-actions .kama-cookie-btn {
        width: 100%;
        justify-content: center;
    }
}

/* BOUTONS COOKIES */
.kama-cookie-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    white-space: nowrap;
}

.kama-cookie-btn-primary {
    background: var(--kama-cookie-red);
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(179, 0, 0, 0.25);
}

.kama-cookie-btn-primary:hover {
    background: var(--kama-cookie-red-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(179, 0, 0, 0.35);
}

.kama-cookie-btn-secondary {
    background: #f3f4f6;
    color: #374151 !important;
    border-color: #e5e7eb;
}

.kama-cookie-btn-secondary:hover {
    background: #e5e7eb;
    color: #111827 !important;
}

.kama-cookie-btn-outline {
    background: transparent;
    color: var(--kama-cookie-red) !important;
    border-color: rgba(179, 0, 0, 0.3);
}

.kama-cookie-btn-outline:hover {
    background: #fff5f5;
    border-color: var(--kama-cookie-red);
}

/* MODAL DE PRÉFÉRENCES */
.kama-cookie-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 999995;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.kama-cookie-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(17, 24, 39, 0.65);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    animation: kamaFadeIn 0.3s ease;
}

@keyframes kamaFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.kama-cookie-modal-dialog {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35);
    animation: kamaModalZoom 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    z-index: 10;
    overflow: hidden;
}

@keyframes kamaModalZoom {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(15px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.kama-cookie-modal-header {
    padding: 24px 28px;
    border-bottom: 1px solid var(--kama-cookie-border);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    background: #fafafa;
}

.kama-cookie-modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--kama-cookie-dark);
    margin: 4px 0 0;
}

.kama-cookie-modal-close {
    background: #ffffff;
    border: 1px solid var(--kama-cookie-border);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--kama-cookie-gray);
    cursor: pointer;
    font-size: 15px;
    transition: all 0.2s;
}

.kama-cookie-modal-close:hover {
    background: #f3f4f6;
    color: var(--kama-cookie-dark);
}

.kama-cookie-modal-body {
    padding: 24px 28px;
    overflow-y: auto;
    flex: 1;
}

.kama-cookie-modal-intro {
    font-size: 13.5px;
    color: var(--kama-cookie-gray);
    line-height: 1.65;
    margin-bottom: 20px;
    background: #f9fafb;
    border: 1px solid var(--kama-cookie-border);
    border-radius: 12px;
    padding: 14px 16px;
}

.kama-cookie-categories {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.kama-cookie-category-card {
    border: 1px solid var(--kama-cookie-border);
    border-radius: 14px;
    padding: 18px 20px;
    background: #ffffff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.kama-cookie-category-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.kama-cookie-category-card.required {
    background: #fafbfc;
}

.kama-cookie-category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 8px;
}

.kama-cookie-category-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.kama-cookie-cat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #fff5f5;
    color: var(--kama-cookie-red);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

.kama-cookie-category-title h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--kama-cookie-dark);
    margin: 0 0 2px;
}

.kama-cookie-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 6px;
}

.kama-cookie-tag.required {
    background: #ecfdf5;
    color: #065f46;
}

.kama-cookie-tag.optional {
    background: #eff6ff;
    color: #1e40af;
}

.kama-cookie-category-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
    padding-left: 48px;
}

@media (max-width: 576px) {
    .kama-cookie-category-desc {
        padding-left: 0;
        margin-top: 8px;
    }
}

/* TOGGLE SWITCH MODERNE */
.kama-cookie-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}

.kama-cookie-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.kama-cookie-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #d1d5db;
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 34px;
}

.kama-cookie-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.kama-cookie-switch input:checked + .kama-cookie-slider {
    background-color: var(--kama-cookie-red);
}

.kama-cookie-switch input:checked + .kama-cookie-slider:before {
    transform: translateX(22px);
}

.kama-cookie-switch-wrapper {
    display: flex;
    align-items: center;
    gap: 6px;
}

.kama-cookie-switch-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

/* FOOTER MODAL */
.kama-cookie-modal-footer {
    padding: 18px 28px;
    border-top: 1px solid var(--kama-cookie-border);
    background: #fafafa;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.kama-cookie-footer-left {
    display: flex;
    gap: 8px;
}

@media (max-width: 576px) {
    .kama-cookie-modal-footer {
        flex-direction: column;
    }
    .kama-cookie-footer-left,
    .kama-cookie-footer-right {
        width: 100%;
        justify-content: center;
    }
    .kama-cookie-footer-right .kama-cookie-btn {
        width: 100%;
    }
}

/* TOAST */
.kama-cookie-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: #111827;
    color: #ffffff;
    padding: 12px 22px;
    border-radius: 50px;
    font-size: 13.5px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    z-index: 999999;
    animation: kamaToastIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.kama-cookie-toast i {
    color: #10b981;
    font-size: 16px;
}

@keyframes kamaToastIn {
    from {
        opacity: 0;
        transform: translate(-50%, 20px);
    }
    to {
        opacity: 1;
        transform: translate(-50%, 0);
    }
}

.kama-footer-cookie-btn {
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    color: inherit;
    font: inherit;
    cursor: pointer;
    text-align: left;
    transition: color 0.2s ease;
}

.kama-footer-cookie-btn:hover {
    color: var(--kama-cookie-red) !important;
}
</style>

<script>
/**
 * Gestion du consentement aux cookies KaMa
 */
(function () {
    const COOKIE_NAME = 'kama_cookie_consent';
    const COOKIE_DAYS = 180; // 6 mois de validité

    const banner = document.getElementById('kamaCookieBanner');
    const modal = document.getElementById('kamaCookieModal');
    const modalBackdrop = document.getElementById('kamaCookieModalBackdrop');
    const modalCloseBtn = document.getElementById('kamaCookieModalCloseBtn');
    const toast = document.getElementById('kamaCookieToast');

    const acceptAllBtn = document.getElementById('kamaCookieAcceptAllBtn');
    const rejectAllBtn = document.getElementById('kamaCookieRejectAllBtn');
    const customizeBtn = document.getElementById('kamaCookieCustomizeBtn');

    const modalAcceptAllBtn = document.getElementById('kamaModalAcceptAllBtn');
    const modalRejectAllBtn = document.getElementById('kamaModalRejectAllBtn');
    const modalSaveBtn = document.getElementById('kamaModalSaveBtn');

    const toggleFunctional = document.getElementById('kamaConsentFunctional');
    const toggleAnalytics = document.getElementById('kamaConsentAnalytics');
    const toggleMarketing = document.getElementById('kamaConsentMarketing');

    // Utilitaires de gestion des cookies
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "; expires=" + date.toUTCString();
        document.cookie = name + "=" + encodeURIComponent(JSON.stringify(value)) + expires + "; path=/; SameSite=Lax";
        try {
            localStorage.setItem(name, JSON.stringify(value));
        } catch (e) {}
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                try {
                    return JSON.parse(decodeURIComponent(c.substring(nameEQ.length, c.length)));
                } catch (e) {
                    return null;
                }
            }
        }
        try {
            const stored = localStorage.getItem(name);
            return stored ? JSON.parse(stored) : null;
        } catch (e) {
            return null;
        }
    }

    function showBanner() {
        if (banner) {
            banner.style.display = 'block';
        }
    }

    function hideBanner() {
        if (banner) {
            banner.style.display = 'none';
        }
    }

    function openModal() {
        const consent = getCookie(COOKIE_NAME) || {
            essential: true,
            functional: true,
            analytics: true,
            marketing: false
        };

        if (toggleFunctional) toggleFunctional.checked = !!consent.functional;
        if (toggleAnalytics) toggleAnalytics.checked = !!consent.analytics;
        if (toggleMarketing) toggleMarketing.checked = !!consent.marketing;

        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function showToast(message) {
        if (!toast) return;
        if (message) {
            const span = toast.querySelector('span');
            if (span) span.textContent = message;
        }
        toast.style.display = 'inline-flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3500);
    }

    function saveConsent(consent) {
        consent.essential = true;
        consent.timestamp = new Date().toISOString();
        setCookie(COOKIE_NAME, consent, COOKIE_DAYS);

        hideBanner();
        closeModal();
        showToast('Vos préférences de cookies ont bien été enregistrées.');

        // Déclencher un événement global pour que d'autres scripts puissent réagir
        window.dispatchEvent(new CustomEvent('kama:cookieConsentUpdated', { detail: consent }));
    }

    // Gestionnaires d'événements
    if (acceptAllBtn) {
        acceptAllBtn.addEventListener('click', () => {
            saveConsent({ essential: true, functional: true, analytics: true, marketing: true });
        });
    }

    if (rejectAllBtn) {
        rejectAllBtn.addEventListener('click', () => {
            saveConsent({ essential: true, functional: false, analytics: false, marketing: false });
        });
    }

    if (customizeBtn) {
        customizeBtn.addEventListener('click', () => {
            openModal();
        });
    }

    if (modalAcceptAllBtn) {
        modalAcceptAllBtn.addEventListener('click', () => {
            saveConsent({ essential: true, functional: true, analytics: true, marketing: true });
        });
    }

    if (modalRejectAllBtn) {
        modalRejectAllBtn.addEventListener('click', () => {
            saveConsent({ essential: true, functional: false, analytics: false, marketing: false });
        });
    }

    if (modalSaveBtn) {
        modalSaveBtn.addEventListener('click', () => {
            saveConsent({
                essential: true,
                functional: toggleFunctional ? toggleFunctional.checked : true,
                analytics: toggleAnalytics ? toggleAnalytics.checked : false,
                marketing: toggleMarketing ? toggleMarketing.checked : false,
            });
        });
    }

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

    // Échapper pour fermer le modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeModal();
        }
    });

    // Exposer une fonction globale pour ré-ouvrir le centre de préférences (ex: depuis le footer)
    window.openKamaCookieSettings = openModal;

    // Boutons avec la classe .js-open-cookie-settings
    document.querySelectorAll('.js-open-cookie-settings').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    });

    // Vérification initiale du consentement au chargement de la page
    const existingConsent = getCookie(COOKIE_NAME);
    if (!existingConsent) {
        // Afficher avec une légère temporisation pour une entrée fluide
        setTimeout(showBanner, 600);
    } else {
        window.dispatchEvent(new CustomEvent('kama:cookieConsentLoaded', { detail: existingConsent }));
    }
})();
</script>
