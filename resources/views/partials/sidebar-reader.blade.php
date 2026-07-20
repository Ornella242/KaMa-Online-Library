<div class="col-lg-4 col-xl-3">
    <div class="offcanvas-lg offcanvas-end" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header justify-content-end pb-2">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-3 p-lg-0">
            <aside class="reader-sidebar">
                <div class="reader-sidebar-profile">
                    <a href="{{ route('reader.account') }}" class="edit" title="Modifier le profil">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <img
                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/01.jpg') }}"
                        alt="">
                    <h6>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
                    <small>{{ Auth::user()->email }}</small>
                </div>

                <nav class="reader-sidebar-nav">
                    <a class="{{ request()->routeIs('reader.account') ? 'active' : '' }}" href="{{ route('reader.account') }}">
                        <i class="bi bi-person"></i> Mon profil
                    </a>
                    <a class="{{ request()->routeIs('reader.books*') ? 'active' : '' }}" href="{{ route('reader.books') }}">
                        <i class="bi bi-book"></i> Mes livres
                    </a>
                    <a class="{{ request()->routeIs('reader.wishlist*') ? 'active' : '' }}" href="{{ route('reader.wishlist') }}">
                        <i class="bi bi-heart"></i> Wishlist
                    </a>
                    <a class="{{ request()->routeIs('reader.cart') || request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart"></i> Mon panier
                    </a>
                    <a class="{{ request()->routeIs('reader.settings') ? 'active' : '' }}" href="{{ route('reader.settings') }}">
                        <i class="bi bi-gear"></i> Paramètres
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout">
                            <i class="bi bi-box-arrow-right"></i> Se déconnecter
                        </button>
                    </form>
                </nav>

                @unless(auth()->user()->isWriter())
                    <div class="reader-become-writer">
                        <button type="button" id="openBecomeWriterOverlay">
                            <i class="bi bi-pen"></i> Devenir écrivain
                        </button>
                    </div>
                    @if($errors->hasAny(['accept_fees', 'accept_rights', 'accept_terms', 'confirm_text']))
                        <div class="alert alert-danger mt-2 mb-0 py-2 px-3 small">
                            Veuillez compléter la confirmation pour devenir écrivain.
                        </div>
                    @endif
                @endunless
            </aside>
        </div>
    </div>
</div>

@unless(auth()->user()->isWriter())
<div id="becomeWriterOverlay" class="book-preview-overlay d-none">
    <div class="book-preview-container become-writer-container">
        <div class="book-preview-header">
            <h5>
                <i class="bi bi-pen me-2"></i>
                <span>Devenir écrivain sur KaMa</span>
            </h5>
            <button type="button" class="book-preview-close" id="closeBecomeWriterOverlay">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="book-preview-body become-writer-body">
            <form method="POST" action="{{ route('become.writer') }}" id="becomeWriterForm">
                @csrf

                <p class="lead-text">
                    Votre compte lecteur restera actif. Vous gagnerez en plus un espace pour publier,
                    suivre vos ventes et gérer vos revenus.
                </p>

                <ul class="writer-perks">
                    <li><i class="bi bi-check2-circle"></i> Publier des ebooks et livres audio</li>
                    <li><i class="bi bi-check2-circle"></i> Suivre les ventes et retirer vos gains</li>
                    <li><i class="bi bi-check2-circle"></i> Accéder au tableau de bord écrivain</li>
                </ul>

                <div class="writer-commitments">
                    <label class="commitment">
                        <input type="checkbox" name="accept_fees" value="1" required>
                        <span>Je comprends que la publication d’un livre nécessite le paiement de frais d’enregistrement via KKiaPay.</span>
                    </label>
                    <label class="commitment">
                        <input type="checkbox" name="accept_rights" value="1" required>
                        <span>Je confirme ne publier que des contenus dont je détiens les droits (ou pour lesquels j’ai l’autorisation).</span>
                    </label>
                    <label class="commitment">
                        <input type="checkbox" name="accept_terms" value="1" required>
                        <span>J’accepte les règles de publication KaMa et le contrôle éditorial avant mise en ligne.</span>
                    </label>
                </div>

                <div class="writer-confirm-field">
                    <label for="writer_confirm_text">Pour confirmer, tapez <strong>ÉCRIVAIN</strong></label>
                    <input
                        type="text"
                        id="writer_confirm_text"
                        name="confirm_text"
                        class="form-control"
                        placeholder="ÉCRIVAIN"
                        autocomplete="off"
                        required>
                </div>

                <div class="become-writer-actions">
                    <button type="button" class="become-writer-btn cancel" id="cancelBecomeWriterOverlay">
                        Annuler
                    </button>
                    <button type="submit" class="become-writer-btn confirm" id="becomeWriterSubmit" disabled>
                        Confirmer et devenir écrivain
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('becomeWriterOverlay');
    const openBtn = document.getElementById('openBecomeWriterOverlay');
    const closeBtn = document.getElementById('closeBecomeWriterOverlay');
    const cancelBtn = document.getElementById('cancelBecomeWriterOverlay');
    const form = document.getElementById('becomeWriterForm');

    if (!overlay || !form) return;

    const submitBtn = document.getElementById('becomeWriterSubmit');
    const confirmInput = document.getElementById('writer_confirm_text');
    const checkboxes = form.querySelectorAll('.writer-commitments input[type="checkbox"]');

    const openOverlay = () => overlay.classList.remove('d-none');
    const closeOverlay = () => overlay.classList.add('d-none');

    const refresh = () => {
        const allChecked = [...checkboxes].every((box) => box.checked);
        const confirmed = (confirmInput.value || '').trim().toUpperCase() === 'ÉCRIVAIN';
        submitBtn.disabled = !(allChecked && confirmed);
    };

    openBtn?.addEventListener('click', openOverlay);
    closeBtn?.addEventListener('click', closeOverlay);
    cancelBtn?.addEventListener('click', closeOverlay);

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeOverlay();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !overlay.classList.contains('d-none')) {
            closeOverlay();
        }
    });

    checkboxes.forEach((box) => box.addEventListener('change', refresh));
    confirmInput.addEventListener('input', refresh);
    refresh();

    @if($errors->hasAny(['accept_fees', 'accept_rights', 'accept_terms', 'confirm_text']))
        openOverlay();
    @endif
});
</script>
@endpush
@endunless
