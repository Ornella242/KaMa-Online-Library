@extends('layouts.writer')

@section('writer-content')
<div class="writer-sponsor-page">
    <header class="writer-sponsor-hero">
        <div>
            <a href="{{ route('writer.books') }}" class="back">
                <i class="bi bi-arrow-left"></i> Ma bibliothèque
            </a>
            <span class="eyebrow">Visibilité</span>
            <h1>Sponsoriser « {{ $book->title }} »</h1>
            <p>Choisissez une formule, payez par carte via KKiaPay, puis l’équipe KaMa active la mise en avant.</p>
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
    @elseif($pendingValidation)
        <div class="alert alert-warning">
            Paiement reçu. Votre demande est <strong>en attente de validation</strong> par l’administration KaMa.
        </div>
    @endif

    @if($plans->isEmpty())
        <div class="writer-sponsor-empty">
            <h2>Aucune formule disponible</h2>
            <p>Les offres de sponsoring ne sont pas encore configurées.</p>
        </div>
    @else
        <div class="writer-sponsor-grid">
            @foreach($plans as $plan)
                <article class="writer-sponsor-card {{ $loop->iteration === 2 ? 'featured' : '' }}">
                    @if($loop->iteration === 2)
                        <span class="badge-popular">Populaire</span>
                    @endif
                    <h3>{{ $plan->name }}</h3>
                    <div class="price">
                        ${{ number_format($plan->price, 2, '.', ',') }}
                    </div>
                    <p class="days">{{ $plan->duration_days }} jours de visibilité</p>
                    <ul>
                        <li><i class="bi bi-check-circle-fill"></i> Mise en avant sur l’accueil</li>
                        <li><i class="bi bi-check-circle-fill"></i> Bannière livres sponsorisés</li>
                        <li><i class="bi bi-check-circle-fill"></i> Plus d’exposition lecteurs</li>
                    </ul>

                    @if($activeSponsorship || $pendingValidation)
                        <button type="button" class="btn-choose" disabled>Indisponible</button>
                    @else
                        <form method="POST" action="{{ route('writer.sponsorship.store', [$book, $plan]) }}">
                            @csrf
                            <button type="submit" class="btn-choose">
                                Choisir cette formule
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
.writer-sponsor-page { max-width:1400px; margin:0 auto; padding:10px 0 40px; }
.writer-sponsor-hero {
  display:flex; flex-wrap:wrap; justify-content:space-between; gap:18px; align-items:flex-end;
  margin-bottom:24px; padding:22px 24px; border-radius:18px; background:linear-gradient(135deg,#fff7f5,#fff); border:1px solid #f0e4e2;
}
.writer-sponsor-hero .back { display:inline-flex; align-items:center; gap:6px; color:#b30000; font-weight:700; text-decoration:none; font-size:.9rem; }
.writer-sponsor-hero .eyebrow { display:block; margin:10px 0 4px; color:#b30000; font-size:.72rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }
.writer-sponsor-hero h1 { margin:0 0 6px; font-size:1.7rem; }
.writer-sponsor-hero p { margin:0; color:#777; max-width:520px; }
.book-chip { display:flex; gap:12px; align-items:center; background:#fff; border:1px solid #eceef0; border-radius:14px; padding:10px 12px; }
.book-chip img { width:48px; height:64px; object-fit:cover; border-radius:8px; }
.book-chip strong { display:block; font-size:.95rem; }
.book-chip small { color:#888; }
.writer-sponsor-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px; }
.writer-sponsor-card {
  position:relative; background:#fff; border:1px solid #eceef0; border-radius:18px; padding:22px 20px; display:flex; flex-direction:column; gap:10px;
}
.writer-sponsor-card.featured { border-color:#b30000; box-shadow:0 12px 30px rgba(179,0,0,.08); }
.badge-popular {
  position:absolute; top:12px; right:12px; background:#b30000; color:#fff; font-size:.68rem; font-weight:800;
  padding:4px 8px; border-radius:999px; text-transform:uppercase;
}
.writer-sponsor-card h3 { margin:0; font-size:1.15rem; }
.writer-sponsor-card .price { font-size:1.8rem; font-weight:800; color:#111; line-height:1; }
.writer-sponsor-card .price small { font-size:.85rem; color:#888; font-weight:700; }
.writer-sponsor-card .days { margin:0; color:#888; font-size:.9rem; }
.writer-sponsor-card ul { list-style:none; margin:6px 0 12px; padding:0; display:grid; gap:8px; flex:1; }
.writer-sponsor-card li { display:flex; gap:8px; color:#444; font-size:.9rem; }
.writer-sponsor-card li i { color:#1a7f4b; }
.btn-choose {
  width:100%; border:0; border-radius:999px; padding:12px 16px; background:#b30000; color:#fff; font-weight:700; cursor:pointer;
}
.btn-choose:disabled { opacity:.45; cursor:not-allowed; background:#999; }
.btn-choose:not(:disabled):hover { background:#910000; }
.writer-sponsor-empty { text-align:center; padding:48px 20px; background:#fff; border-radius:16px; border:1px dashed #e4e6ea; }
</style>
@endpush
