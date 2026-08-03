@extends('layouts.admin')

@section('title', 'Sponsoriser le livre')
@section('page-title', 'Sponsoriser')

@section('admin-content')
<div class="admin-sponsor-page">
    <header class="admin-sponsor-hero">
        <div>
            <a href="{{ route('admin.books.index') }}" class="back">
                <i class="bi bi-arrow-left"></i> Mes livres
            </a>
            <span class="eyebrow">Visibilité · Gratuit</span>
            <h1>Sponsoriser « {{ $book->title }} »</h1>
            <p>Choisissez une formule. En tant qu’administrateur, le sponsoring est activé immédiatement, sans paiement.</p>
        </div>
        <div class="book-chip">
            <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('assets/images/book/01.jpg') }}" alt="">
            <div>
                <strong>{{ $book->title }}</strong>
                <small>{{ $book->type === 'audio' ? 'Livre audio' : 'Ebook' }}</small>
            </div>
        </div>
    </header>

    @if($activeSponsorship)
        <div class="alert alert-success">
            Ce livre est déjà sponsorisé jusqu’au
            <strong>{{ $activeSponsorship->ends_at?->format('d/m/Y') }}</strong>.
        </div>
    @endif

    @if($plans->isEmpty())
        <div class="admin-sponsor-empty">
            <h2>Aucune formule disponible</h2>
            <p>Créez d’abord une formule dans <a href="{{ route('admin.sponsorship-plans.index') }}">Formules sponsoring</a>.</p>
        </div>
    @else
        <div class="admin-sponsor-grid">
            @foreach($plans as $plan)
                <article class="admin-sponsor-card {{ $loop->iteration === 2 ? 'featured' : '' }}">
                    @if($loop->iteration === 2)
                        <span class="badge-popular">Populaire</span>
                    @endif
                    <h3>{{ $plan->name }}</h3>
                    <div class="price">
                        Gratuit
                        <small>admin</small>
                    </div>
                    <p class="days">{{ $plan->duration_days }} jours de visibilité</p>
                    <p class="price-ref">Tarif auteur : ${{ number_format($plan->price, 2, '.', ',') }}</p>
                    <ul>
                        <li><i class="bi bi-check-circle-fill"></i> Mise en avant sur l’accueil</li>
                        <li><i class="bi bi-check-circle-fill"></i> Bannière livres sponsorisés</li>
                        <li><i class="bi bi-check-circle-fill"></i> Activation immédiate</li>
                    </ul>

                    @if($activeSponsorship)
                        <button type="button" class="btn-choose" disabled>Déjà sponsorisé</button>
                    @else
                        <form method="POST" action="{{ route('admin.books.sponsor.store', [$book, $plan]) }}">
                            @csrf
                            <button type="submit" class="btn-choose">
                                Activer gratuitement
                            </button>
                        </form>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.admin-sponsor-page { max-width:1100px; margin:0 auto; padding:4px 0 32px; }
.admin-sponsor-hero {
  display:flex; flex-wrap:wrap; justify-content:space-between; gap:18px; align-items:flex-end;
  margin-bottom:24px; padding:22px 24px; border-radius:18px;
  background:linear-gradient(135deg,#fff7f5,#fff); border:1px solid #f0e4e2;
}
.admin-sponsor-hero .back { display:inline-flex; align-items:center; gap:6px; color:#b30000; font-weight:700; text-decoration:none; font-size:.9rem; }
.admin-sponsor-hero .eyebrow { display:block; margin:10px 0 4px; color:#b30000; font-size:.72rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }
.admin-sponsor-hero h1 { margin:0 0 6px; font-size:1.55rem; }
.admin-sponsor-hero p { margin:0; color:#777; max-width:520px; }
.book-chip { display:flex; gap:12px; align-items:center; background:#fff; border:1px solid #eceef0; border-radius:14px; padding:10px 12px; }
.book-chip img { width:48px; height:64px; object-fit:cover; border-radius:8px; }
.book-chip strong { display:block; font-size:.95rem; }
.book-chip small { color:#888; }
.admin-sponsor-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px; }
.admin-sponsor-card {
  position:relative; background:#fff; border:1px solid #eceef0; border-radius:18px; padding:22px 20px;
  display:flex; flex-direction:column; gap:10px;
}
.admin-sponsor-card.featured { border-color:#b30000; box-shadow:0 12px 30px rgba(179,0,0,.08); }
.badge-popular {
  position:absolute; top:12px; right:12px; background:#b30000; color:#fff; font-size:.68rem; font-weight:800;
  padding:4px 8px; border-radius:999px; text-transform:uppercase;
}
.admin-sponsor-card h3 { margin:0; font-size:1.15rem; }
.admin-sponsor-card .price { font-size:1.8rem; font-weight:800; color:#1a7f4b; line-height:1; }
.admin-sponsor-card .price small { font-size:.85rem; color:#888; font-weight:700; }
.admin-sponsor-card .days { margin:0; color:#888; font-size:.9rem; }
.admin-sponsor-card .price-ref { margin:0; color:#aaa; font-size:.8rem; }
.admin-sponsor-card ul { list-style:none; margin:6px 0 12px; padding:0; display:grid; gap:8px; flex:1; }
.admin-sponsor-card li { display:flex; gap:8px; color:#444; font-size:.9rem; }
.admin-sponsor-card li i { color:#1a7f4b; }
.btn-choose {
  width:100%; border:0; border-radius:999px; padding:12px 16px; background:#b30000; color:#fff; font-weight:700; cursor:pointer;
}
.btn-choose:disabled { opacity:.45; cursor:not-allowed; background:#999; }
.btn-choose:not(:disabled):hover { background:#910000; }
.admin-sponsor-empty { text-align:center; padding:48px 20px; background:#fff; border-radius:16px; border:1px dashed #e4e6ea; }
.admin-sponsor-empty a { color:#b30000; font-weight:700; }
</style>
@endpush
