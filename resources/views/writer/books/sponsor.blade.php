@extends('layouts.writer')

@section('writer-content')

<div class="container py-5 sponsor-page">

    <div class="mb-4">
        <a href="{{ route('writer.books') }}" class="btn sponsor-back">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>


    <div class="section-title">
        <h2>
            Choisissez votre formule de sponsoring
        </h2>
    </div>



    <div class="row g-4">

        @foreach($plans as $plan)

        <div class="col-lg-4">

            <div class="plan-card">

                @if($loop->index==2)

                <span class="popular">

                    Le plus populaire

                </span>

                @endif
                <h4>
                    {{ $plan->name }}
                </h4>

                <div class="price">
                    {{ number_format($plan->price,2) }} $
                </div>

                <span class="days">
                    {{ $plan->duration_days }}
                    jours de visibilité
                </span>

                <ul>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Mise en avant du livre
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Apparition dans les livres sponsorisés
                    </li>

                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        Plus de visibilité auprès des lecteurs
                    </li>
                </ul>

                <form
                    action="{{ route('writer.sponsorship.store', [$book, $plan]) }}"
                    method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="btn sponsor-btn w-100">
                        Choisir cette formule
                    </button>
                </form>
            </div>
        </div>

        @endforeach

    </div>

</div>
@endsection