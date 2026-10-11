@extends('layouts.app')

@section('content')

<section class="login-page">

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
                        Saisissez le code à 6 chiffres envoyé à<br>
                        <strong class="text-dark">{{ $maskedEmail }}</strong>
                    </p>

                </div>

                <form method="POST" action="{{ route('auth.2fa.verify') }}" class="login-form" id="twoFactorForm">

                    @csrf

                    <input type="hidden" name="code" id="fullCodeInput" value="{{ old('code') }}">

                    <div class="otp-inputs-wrapper">
                        <label class="form-label text-muted small fw-semibold d-block text-center mb-2">
                            Code de sécurité à 6 chiffres
                        </label>

                        <div class="otp-boxes" id="otpContainer">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="0" autocomplete="one-time-code" autofocus>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="1">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="2">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="3">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="4">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit" data-index="5">
                        </div>

                        <small class="text-muted d-block text-center mt-2" style="font-size: 0.82rem;">
                            <i class="bi bi-info-circle me-1"></i>Vous pouvez coller directement le code à 6 chiffres.
                        </small>
                    </div>

                    <button type="submit" class="login-btn" id="verifyBtn">
                        <i class="bi bi-shield-check"></i>
                        <span>Confirmer et se connecter</span>
                    </button>

                </form>

                <!-- RESEND FORM -->
                <div class="text-center mt-4 pt-2 border-top">
                    <p class="text-muted small mb-2">
                        Vous n'avez pas reçu le code ?
                    </p>

                    <form method="POST" action="{{ route('auth.2fa.resend') }}" id="resendForm" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-link text-decoration-none fw-semibold p-0"
                                id="resendBtn"
                                style="color: #b30000; font-size: 0.9rem;"
                                @if($cooldownRemaining > 0) disabled @endif>
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <span>Renvoyer un nouveau code</span>
                            <span id="cooldownTimer" class="ms-1 text-muted">@if($cooldownRemaining > 0)({{ $cooldownRemaining }}s)@endif</span>
                        </button>
                    </form>
                </div>

                <div class="login-footer mt-4">

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
.otp-inputs-wrapper {
    margin: 20px 0 24px;
}
.otp-boxes {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 10px 0;
}
.otp-digit {
    width: 48px;
    height: 56px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    color: #111827;
    transition: all 0.2s ease;
    outline: none;
    padding: 0;
}
.otp-digit:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, 0.15);
    background: #fffafa;
    transform: translateY(-2px);
}
.otp-digit.filled {
    border-color: #b30000;
    background: #fff8f8;
}
#resendBtn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    color: #6b7280 !important;
}
@media (max-width: 480px) {
    .otp-boxes {
        gap: 6px;
    }
    .otp-digit {
        width: 38px;
        height: 48px;
        font-size: 20px;
        border-radius: 8px;
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
