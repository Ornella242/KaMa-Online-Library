@extends('layouts.app')

@section('content')

<section class="login-page login-page--2fa">

    <div class="login-shell">

        <div class="login-panel">

            <!-- ================= LEFT HERO ================= -->
            <div class="login-hero">

                <span class="login-badge">
                    <i class="bi bi-shield-check"></i> Sécurité KaMa
                </span>

                <h1>Double authentification</h1>

                <p class="login-description">
                    Pour garantir la confidentialité de vos données et la sécurité de votre compte,
                    nous venons d'envoyer un code de vérification unique à votre adresse e-mail.
                </p>

                <div class="login-hero-cards">

                    <div class="hero-card">
                        <i class="bi bi-envelope-open"></i>

                        <h5>Code par e-mail</h5>

                        <p>
                            Un code de confirmation à 6 chiffres a été généré pour cette session.
                        </p>
                    </div>

                    <div class="hero-card">
                        <i class="bi bi-clock-history"></i>

                        <h5>Validité 10 minutes</h5>

                        <p>
                            Ce code temporaire expire automatiquement dans 10 minutes.
                        </p>
                    </div>

                    <div class="hero-card">
                        <i class="bi bi-shield-lock"></i>

                        <h5>Compte protégé</h5>

                        <p>
                            Vos livres, revenus et données personnelles restent sécurisés.
                        </p>
                    </div>

                </div>

            </div>

            <!-- ================= RIGHT FORM ================= -->
            <div class="login-form-shell">

                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="login-header">

                    <h2>Vérification du code</h2>

                    <p>
                        Saisissez le code à 6 chiffres envoyé à
                        <strong class="text-dark otp-email">{{ $maskedEmail }}</strong>
                    </p>

                </div>

                <form method="POST" action="{{ route('auth.2fa.verify') }}" class="login-form" id="twoFactorForm">

                    @csrf

                    <input type="hidden" name="code" id="fullCodeInput" value="{{ old('code') }}">

                    <div class="otp-inputs-wrapper">
                        <label class="form-label text-muted small fw-semibold d-block text-center mb-2" for="otpContainer">
                            Code de sécurité à 6 chiffres
                        </label>

                        <div class="otp-boxes" id="otpContainer" role="group" aria-label="Code à 6 chiffres">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="0" autocomplete="one-time-code" aria-label="Chiffre 1" autofocus>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="1" aria-label="Chiffre 2">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="2" aria-label="Chiffre 3">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="3" aria-label="Chiffre 4">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="4" aria-label="Chiffre 5">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="5" aria-label="Chiffre 6">
                        </div>

                        <small class="otp-paste-hint text-muted d-block text-center mt-2">
                            <i class="bi bi-info-circle me-1"></i>Vous pouvez coller directement le code à 6 chiffres.
                        </small>
                    </div>

                    <button type="submit" class="login-btn" id="verifyBtn">
                        <i class="bi bi-shield-check"></i>
                        <span>Confirmer et se connecter</span>
                    </button>

                </form>

                <div class="otp-resend text-center">
                    <p class="text-muted small mb-2">
                        Vous n'avez pas reçu le code ?
                    </p>

                    <form method="POST" action="{{ route('auth.2fa.resend') }}" id="resendForm">
                        @csrf
                        <button type="submit"
                                class="otp-resend-btn"
                                id="resendBtn"
                                @if($cooldownRemaining > 0) disabled @endif>
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Renvoyer un nouveau code</span>
                            <span id="cooldownTimer" class="otp-cooldown">@if($cooldownRemaining > 0)({{ $cooldownRemaining }}s)@endif</span>
                        </button>
                    </form>
                </div>

                <div class="login-footer">

                    <a href="{{ route('auth.2fa.cancel') }}" class="back-home">
                        <i class="bi bi-arrow-left"></i>
                        Utiliser un autre compte
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@push('styles')
<style>
.login-page--2fa {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 16px;
    background: #f4f6f9;
    overflow-x: clip;
    box-sizing: border-box;
}

.login-page--2fa *,
.login-page--2fa *::before,
.login-page--2fa *::after {
    box-sizing: border-box;
}

.login-page--2fa .login-shell {
    width: 100%;
    max-width: 1100px;
}

.login-page--2fa .login-panel {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.08);
}

.login-page--2fa .login-panel,
.login-page--2fa .login-form-shell,
.login-page--2fa .login-hero {
    min-width: 0;
}

.login-page--2fa .login-hero {
    padding: 56px 48px;
    background: linear-gradient(135deg, #ffffff 0%, #fff5f5 35%, #ffe9e9 70%, #f8dede 100%);
    color: #1a1a1a;
}

.login-page--2fa .login-form-shell {
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #fff;
}

.login-page--2fa .login-header h2 {
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    margin-bottom: 6px;
}

.login-page--2fa .login-header p {
    color: #777;
    margin-bottom: 18px;
    line-height: 1.5;
}

.login-page--2fa .otp-email {
    display: inline-block;
    max-width: 100%;
    word-break: break-word;
}

.otp-inputs-wrapper {
    margin: 4px 0 10px;
}

.otp-boxes {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: clamp(6px, 2.2vw, 12px);
    width: 100%;
    max-width: 360px;
    margin: 10px auto;
}

.otp-digit {
    width: 100%;
    min-width: 0;
    aspect-ratio: 1 / 1.12;
    max-height: 58px;
    text-align: center;
    font-size: clamp(1rem, 4.5vw, 1.5rem);
    font-weight: 700;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    color: #111827;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    outline: none;
    padding: 0;
    -webkit-appearance: none;
    appearance: none;
}

.otp-digit:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, 0.15);
    background: #fffafa;
}

.otp-digit.filled {
    border-color: #b30000;
    background: #fff8f8;
}

.otp-paste-hint {
    font-size: 0.82rem;
    line-height: 1.4;
    padding: 0 4px;
}

.login-page--2fa .login-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    white-space: normal;
    line-height: 1.3;
}

.otp-resend {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid #eceef1;
}

.otp-resend-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
    max-width: 100%;
    padding: 0;
    border: 0;
    background: transparent;
    color: #b30000;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.4;
    text-align: center;
}

.otp-resend-btn:hover:not(:disabled) {
    text-decoration: underline;
}

.otp-resend-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    color: #6b7280;
}

.otp-cooldown {
    color: #6b7280;
}

.login-page--2fa .login-footer {
    margin-top: 1.25rem;
}

@media (max-width: 992px) {
    .login-page--2fa {
        align-items: flex-start;
        padding: 28px 14px 40px;
    }

    .login-page--2fa .login-panel {
        grid-template-columns: 1fr;
    }

    .login-page--2fa .login-form-shell {
        order: -1;
        padding: 28px 22px 32px;
    }

    .login-page--2fa .login-hero {
        padding: 24px 22px 28px;
        border-top: 1px solid #f0e4e4;
    }

    .login-page--2fa .login-hero h1 {
        font-size: clamp(1.55rem, 5vw, 2rem);
        margin-bottom: 10px;
    }

    .login-page--2fa .login-description {
        margin-bottom: 18px;
        font-size: 0.95rem;
    }

    .login-page--2fa .login-header p {
        margin-bottom: 12px;
        font-size: 0.92rem;
    }

    .login-page--2fa .login-hero-cards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .login-page--2fa .hero-card {
        padding: 12px;
    }

    .login-page--2fa .hero-card i {
        font-size: 1.1rem;
        margin-bottom: 6px;
    }

    .login-page--2fa .hero-card h5 {
        font-size: 0.82rem;
    }

    .login-page--2fa .hero-card p {
        font-size: 0.72rem;
        line-height: 1.35;
    }
}

@media (max-width: 700px) {
    .login-page--2fa .login-hero-cards {
        grid-template-columns: 1fr;
    }

    .login-page--2fa .hero-card {
        display: grid;
        grid-template-columns: 28px minmax(0, 1fr);
        column-gap: 10px;
        align-items: start;
    }

    .login-page--2fa .hero-card i {
        grid-row: 1 / span 2;
        margin: 2px 0 0;
    }

    .login-page--2fa .hero-card h5,
    .login-page--2fa .hero-card p {
        grid-column: 2;
    }
}

@media (max-width: 576px) {
    .login-page--2fa {
        padding: 16px 10px 28px;
        min-height: auto;
        align-items: stretch;
    }

    .login-page--2fa .login-panel {
        border-radius: 18px;
    }

    .login-page--2fa .login-form-shell,
    .login-page--2fa .login-hero {
        padding: 22px 16px;
    }

    .login-page--2fa .login-badge {
        font-size: 12px;
        padding: 6px 12px;
        margin-bottom: 14px;
    }

    .login-page--2fa .login-description {
        display: none;
    }

    .login-page--2fa .login-hero-cards .hero-card:nth-child(n + 3) {
        display: none;
    }

    .otp-digit {
        border-radius: 10px;
        max-height: 52px;
    }

    .otp-paste-hint {
        font-size: 0.75rem;
    }

    .login-page--2fa .login-btn {
        padding: 12px 14px;
        font-size: 0.92rem;
    }

    .otp-resend-btn {
        font-size: 0.86rem;
    }
}

@media (max-width: 380px) {
    .otp-boxes {
        gap: 5px;
        max-width: 100%;
    }

    .otp-digit {
        border-width: 1.5px;
        border-radius: 8px;
        max-height: 46px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const digits = Array.from(document.querySelectorAll('.otp-digit'));
    const fullInput = document.getElementById('fullCodeInput');
    const form = document.getElementById('twoFactorForm');
    const resendBtn = document.getElementById('resendBtn');
    const cooldownTimer = document.getElementById('cooldownTimer');

    let cooldown = {{ (int) $cooldownRemaining }};

    if (cooldown > 0 && resendBtn && cooldownTimer) {
        const interval = setInterval(() => {
            cooldown--;
            if (cooldown <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                cooldownTimer.textContent = '';
            } else {
                cooldownTimer.textContent = `(${cooldown}s)`;
            }
        }, 1000);
    }

    const updateFullValue = () => {
        const val = digits.map(d => d.value.trim()).join('');
        fullInput.value = val;
        digits.forEach(d => {
            d.classList.toggle('filled', d.value.trim().length > 0);
        });
        return val;
    };

    // Pre-fill if old value exists
    if (fullInput.value && fullInput.value.length === 6) {
        const chars = fullInput.value.split('');
        digits.forEach((d, i) => {
            d.value = chars[i] || '';
            d.classList.toggle('filled', !!chars[i]);
        });
    }

    digits.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const val = input.value.replace(/[^0-9]/g, '');
            input.value = val ? val[0] : '';
            updateFullValue();

            if (input.value && index < digits.length - 1) {
                digits[index + 1].focus();
                digits[index + 1].select();
            }

            if (updateFullValue().length === 6) {
                // Auto-submit if desired, or let user click
                // form.submit();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                digits[index - 1].focus();
                digits[index - 1].select();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                digits[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < digits.length - 1) {
                digits[index + 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const clean = pasted.replace(/[^0-9]/g, '').slice(0, 6);
            if (!clean) return;

            clean.split('').forEach((char, i) => {
                if (digits[i]) {
                    digits[i].value = char;
                    digits[i].classList.add('filled');
                }
            });

            updateFullValue();

            const nextIndex = Math.min(clean.length, digits.length - 1);
            digits[nextIndex].focus();
        });
    });

    form.addEventListener('submit', (e) => {
        const val = updateFullValue();
        if (val.length !== 6) {
            e.preventDefault();
            alert('Veuillez renseigner les 6 chiffres du code.');
            const firstEmpty = digits.find(d => !d.value);
            if (firstEmpty) firstEmpty.focus();
        }
    });

    // Auto-focus first input
    const firstEmpty = digits.find(d => !d.value) || digits[0];
    if (firstEmpty) {
        setTimeout(() => firstEmpty.focus(), 150);
    }
});
</script>
@endpush

@endsection
