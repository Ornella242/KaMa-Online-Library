@extends('layouts.app')

@section('content')

<div class="deposit-page">
    <div class="container py-5">
        <div class="deposit-card">
            <!-- LEFT SIDE -->
            <div class="deposit-left">
                <div class="security-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <span class="deposit-label">
                    Publication KaMa
                </span>
                <h2>
                    Paiement du dépôt
                </h2>
                <p>
                    Vous êtes sur le point de publier votre livre dans la bibliothèque KaMa.
                </p>

                <div class="book-info">
                    <small>
                        Livre sélectionné
                    </small>

                    <h3>
                        {{ $book->title }}
                    </h3>

                    <div class="info-row">
                        <span>
                            Type
                        </span>
                        <strong>
                            {{ ucfirst($book->type) }}
                        </strong>
                    </div>
                    <div class="info-row">
                        <span>
                            Langue
                        </span>
                        <strong>
                            {{ $book->language }}
                        </strong>

                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->


            <div class="deposit-right">
                <div class="payment-box">
                    <span>
                        Dépôt de publication
                    </span>
                    <h1>
                        10 $
                    </h1>
                </div>

                <h5 class="mt-4">
                    Moyens de paiement acceptés
                </h5>

                <div class="payment-cards">
                    <div class="card-method">
                        <i class="bi bi-credit-card"></i>
                        <span>
                            VISA
                        </span>
                    </div>

                    <div class="card-method">
                        <i class="bi bi-credit-card"></i>
                        <span>
                            Mastercard
                        </span>
                    </div>
                </div>

                <button class="payment-button">
                    <i class="bi bi-lock-fill"></i>
                    Payer maintenant
                </button>

                <div class="secure-payment">
                    <i class="bi bi-check-circle-fill"></i>
                    Paiement sécurisé SSL
                </div>
            </div>
        </div>
    </div>
</div>
  
@endsection