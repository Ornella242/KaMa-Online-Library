@extends('layouts.admin')

@section('title', 'Formules sponsoring')
@section('page-title', 'Formules sponsoring')

@section('admin-content')
<div class="categories-page">
    <header class="categories-heading">
        <div>
            <span>Référentiel commercial</span>
            <h2>Formules de sponsoring</h2>
            <p>Définissez les offres proposées aux auteurs pour mettre leurs livres en avant.</p>
        </div>
        <button type="button" class="categories-add js-categories-open" data-overlay-target="createPlanOverlay">
            <i class="bi bi-plus-lg"></i> Ajouter une formule
        </button>
    </header>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="categories-stats">
        <article>
            <span class="total"><i class="bi bi-megaphone-fill"></i></span>
            <div><small>Formules</small><strong>{{ number_format($stats['total']) }}</strong></div>
        </article>
        <article>
            <span class="sub"><i class="bi bi-check-circle-fill"></i></span>
            <div><small>Actives</small><strong>{{ number_format($stats['active']) }}</strong></div>
        </article>
        <article>
            <span class="empty"><i class="bi bi-pause-circle-fill"></i></span>
            <div><small>Inactives</small><strong>{{ number_format($stats['inactive']) }}</strong></div>
        </article>
        <article>
            <span class="books"><i class="bi bi-cash-stack"></i></span>
            <div><small>Prix moyen</small><strong>{{ number_format($stats['avg_price'], 0, ',', ' ') }} <small>XOF</small></strong></div>
        </article>
    </section>

    <section class="categories-panel">
        <form method="GET" action="{{ route('admin.sponsorship-plans.index') }}" class="categories-toolbar">
            <div class="categories-search">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher une formule…">
            </div>
            <select name="status" class="form-select" style="max-width:160px;border-radius:12px;">
                <option value="">Tous les statuts</option>
                <option value="active" @selected(request('status') === 'active')>Actives</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactives</option>
            </select>
            <button type="submit" class="categories-filter-btn">Filtrer</button>
        </form>

        <div class="table-responsive">
            <table class="table categories-table align-middle">
                <thead>
                    <tr>
                        <th>Formule</th>
                        <th>Durée</th>
                        <th>Prix</th>
                        <th>Utilisations</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>
                                <div class="categories-name-cell">
                                    <span><i class="bi bi-stars"></i></span>
                                    <div>
                                        <strong>{{ $plan->name }}</strong>
                                        <small>ID #{{ $plan->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $plan->duration_days }} jour(s)</td>
                            <td><strong>{{ number_format($plan->price, 0, ',', ' ') }} XOF</strong></td>
                            <td>{{ number_format($plan->sponsorships_count) }}</td>
                            <td>
                                @if($plan->active)
                                    <span class="categories-count-badge filled">Active</span>
                                @else
                                    <span class="categories-count-badge">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="categories-row-actions">
                                    <button type="button" class="edit js-categories-open" data-overlay-target="editPlan-{{ $plan->id }}">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </button>
                                    <button type="button" class="delete js-categories-open" data-overlay-target="deletePlan-{{ $plan->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="categories-empty">
                                    <span><i class="bi bi-megaphone"></i></span>
                                    <h3>Aucune formule</h3>
                                    <p>Créez votre première offre de sponsoring.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($plans->hasPages())
            <footer class="categories-pagination">
                {{ $plans->links() }}
            </footer>
        @endif
    </section>

    {{-- Create --}}
    <div class="categories-overlay d-none" id="createPlanOverlay" role="dialog" aria-modal="true">
        <div class="categories-overlay-container">
            <header class="categories-overlay-header">
                <div>
                    <span>Sponsoring</span>
                    <h2>Nouvelle formule</h2>
                </div>
                <button type="button" class="js-categories-close" aria-label="Fermer"><i class="bi bi-x-lg"></i></button>
            </header>
            <form method="POST" action="{{ route('admin.sponsorship-plans.store') }}">
                @csrf
                <div class="categories-form-body">
                    <label>Nom *</label>
                    <input type="text" name="name" required placeholder="Ex : 1 mois">
                    <label>Durée (jours) *</label>
                    <input type="number" name="duration_days" min="1" max="365" required placeholder="30">
                    <label>Prix (XOF) *</label>
                    <input type="number" name="price" min="0" step="1" required placeholder="15000">
                    <label class="d-flex align-items-center gap-2 mt-2">
                        <input type="checkbox" name="active" value="1" checked>
                        <span>Formule active (visible aux auteurs)</span>
                    </label>
                </div>
                <footer class="categories-overlay-footer">
                    <button type="button" class="js-categories-close">Annuler</button>
                    <button type="submit" class="confirm">Enregistrer</button>
                </footer>
            </form>
        </div>
    </div>

    @foreach($plans as $plan)
        <div class="categories-overlay d-none" id="editPlan-{{ $plan->id }}" role="dialog" aria-modal="true">
            <div class="categories-overlay-container">
                <header class="categories-overlay-header">
                    <div>
                        <span>Sponsoring</span>
                        <h2>Modifier — {{ $plan->name }}</h2>
                    </div>
                    <button type="button" class="js-categories-close"><i class="bi bi-x-lg"></i></button>
                </header>
                <form method="POST" action="{{ route('admin.sponsorship-plans.update', $plan) }}">
                    @csrf
                    @method('PUT')
                    <div class="categories-form-body">
                        <label>Nom *</label>
                        <input type="text" name="name" value="{{ $plan->name }}" required>
                        <label>Durée (jours) *</label>
                        <input type="number" name="duration_days" value="{{ $plan->duration_days }}" min="1" max="365" required>
                        <label>Prix (XOF) *</label>
                        <input type="number" name="price" value="{{ (int) $plan->price }}" min="0" step="1" required>
                        <label class="d-flex align-items-center gap-2 mt-2">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" value="1" @checked($plan->active)>
                            <span>Formule active</span>
                        </label>
                    </div>
                    <footer class="categories-overlay-footer">
                        <button type="button" class="js-categories-close">Annuler</button>
                        <button type="submit" class="confirm">Mettre à jour</button>
                    </footer>
                </form>
            </div>
        </div>

        <div class="categories-overlay d-none" id="deletePlan-{{ $plan->id }}" role="dialog" aria-modal="true">
            <div class="categories-overlay-container" style="max-width:480px;">
                <header class="categories-overlay-header">
                    <div>
                        <span>Attention</span>
                        <h2>Supprimer la formule ?</h2>
                    </div>
                    <button type="button" class="js-categories-close"><i class="bi bi-x-lg"></i></button>
                </header>
                <div class="categories-form-body">
                    <p>Vous allez supprimer <strong>{{ $plan->name }}</strong>. Impossible si elle a déjà été utilisée.</p>
                </div>
                <form method="POST" action="{{ route('admin.sponsorship-plans.destroy', $plan) }}">
                    @csrf
                    @method('DELETE')
                    <footer class="categories-overlay-footer">
                        <button type="button" class="js-categories-close">Annuler</button>
                        <button type="submit" class="confirm" style="background:#b30000;">Supprimer</button>
                    </footer>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('styles')
    @include('admin.partials.catalog-admin-styles')
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const open = (id) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('d-none');
        el.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };
    const closeAll = () => {
        document.querySelectorAll('.categories-overlay').forEach((el) => {
            el.classList.add('d-none');
            el.setAttribute('aria-hidden', 'true');
        });
        document.body.style.overflow = '';
    };
    document.querySelectorAll('.js-categories-open').forEach((btn) => {
        btn.addEventListener('click', () => open(btn.dataset.overlayTarget));
    });
    document.querySelectorAll('.js-categories-close').forEach((btn) => {
        btn.addEventListener('click', closeAll);
    });
    document.querySelectorAll('.categories-overlay').forEach((overlay) => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeAll();
        });
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });
});
</script>
@endpush
