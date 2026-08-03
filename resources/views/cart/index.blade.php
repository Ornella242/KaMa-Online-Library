@extends('layouts.app')

@section('content')
<section class="kama-cart-page py-5">
    <div class="container">
        <div class="kama-cart-head mb-4">
            <a href="{{ route('catalogue') }}" class="kama-cart-back">
                <i class="bi bi-arrow-left"></i> Continuer mes achats
            </a>
            <h1>Mon panier</h1>
            <p>{{ count($items) }} article(s) · Total ${{ number_format($total, 2, '.', ',') }}</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(empty($items))
            <div class="kama-cart-empty">
                <span><i class="bi bi-cart3"></i></span>
                <h2>Votre panier est vide</h2>
                <p>Parcourez le catalogue et ajoutez des livres à votre panier.</p>
                <a href="{{ route('catalogue') }}" class="btn btn-danger">Voir le catalogue</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="kama-cart-list">
                        @foreach($items as $item)
                            @php $book = $books->get($item['book_id']); @endphp
                            <article class="kama-cart-item">
                                <img src="{{ asset('storage/' . ($item['cover_image'] ?? '')) }}" alt="">
                                <div class="kama-cart-item-body">
                                    <span class="type">{{ ($item['type'] ?? '') === 'audio' ? 'Livre audio' : 'Ebook' }}</span>
                                    <h3>
                                        @if($book)
                                            <a href="{{ route('books.show', $book) }}">{{ $item['title'] }}</a>
                                        @else
                                            {{ $item['title'] }}
                                        @endif
                                    </h3>
                                    <small>Par {{ $item['author'] ?: 'Auteur inconnu' }}</small>
                                    <strong>${{ number_format($item['price'], 2, '.', ',') }}</strong>
                                </div>
                                <form method="POST" action="{{ route('cart.destroy', $item['book_id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove" title="Retirer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </article>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Vider le panier</button>
                    </form>
                </div>

                <div class="col-lg-4">
                    <aside class="kama-cart-summary">
                        <h2>Récapitulatif</h2>
                        <div class="line"><span>Sous-total</span><strong>${{ number_format($total, 2, '.', ',') }}</strong></div>
                        <div class="line"><span>Articles</span><strong>{{ count($items) }}</strong></div>
                        <hr>
                        <div class="line total"><span>Total</span><strong>${{ number_format($total, 2, '.', ',') }}</strong></div>

                        <a href="{{ route('checkout.show') }}" class="btn btn-danger w-100 mt-3">
                            <i class="bi bi-shield-lock me-1"></i> Commander
                        </a>

                        <p class="hint">Aucun compte requis. Renseignez vos infos puis payez en USD via Lemon Squeezy.</p>
                    </aside>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.kama-cart-page { background: #f6f7f9; min-height: 70vh; }
.kama-cart-back { display:inline-flex; align-items:center; gap:6px; color:#b30000; font-weight:700; font-size:.9rem; text-decoration:none; }
.kama-cart-head h1 { margin:10px 0 4px; font-size:1.8rem; }
.kama-cart-head p { margin:0; color:#777; }
.kama-cart-empty { text-align:center; padding:70px 20px; background:#fff; border-radius:18px; }
.kama-cart-empty span { display:grid; width:70px; height:70px; margin:0 auto 14px; place-items:center; border-radius:18px; background:#fff0f0; color:#b30000; font-size:1.8rem; }
.kama-cart-list { display:grid; gap:12px; }
.kama-cart-item { display:flex; gap:14px; align-items:center; padding:14px; background:#fff; border-radius:14px; border:1px solid #eceef0; }
.kama-cart-item img { width:64px; height:84px; object-fit:cover; border-radius:8px; background:#eee; }
.kama-cart-item-body { flex:1; min-width:0; }
.kama-cart-item-body .type { display:inline-block; margin-bottom:4px; color:#b30000; font-size:.72rem; font-weight:800; text-transform:uppercase; }
.kama-cart-item-body h3 { margin:0; font-size:1rem; }
.kama-cart-item-body h3 a { color:#1c1d20; text-decoration:none; }
.kama-cart-item-body small { display:block; color:#888; margin:3px 0 6px; }
.kama-cart-item-body strong { color:#b30000; }
.kama-cart-item .remove { width:36px; height:36px; border:0; border-radius:9px; background:#fff0f0; color:#b30000; }
.kama-cart-summary { padding:20px; background:#fff; border-radius:16px; border:1px solid #eceef0; position:sticky; top:90px; }
.kama-cart-summary h2 { margin:0 0 16px; font-size:1.15rem; }
.kama-cart-summary .line { display:flex; justify-content:space-between; margin-bottom:10px; color:#555; }
.kama-cart-summary .total { font-size:1.1rem; color:#111; }
.kama-cart-summary .hint { margin:12px 0 0; color:#888; font-size:.78rem; }
@media(max-width:767px){ .kama-cart-item{align-items:flex-start;} }
</style>
@endpush
