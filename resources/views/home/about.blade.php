@extends('layouts.app')

@section('content')
<div class="about-page">

    <section class="about-hero">

        <div class="container about-container">

            <div class="about-hero-content">

                <!-- LEFT -->
                <div class="about-hero-text">

                    <span class="about-label">
                        <i class="bi bi-book-half"></i>
                        À propos de KaMa
                    </span>

                    <h1>
                        KaMa, la plateforme qui
                        <span>connecte les histoires africaines</span>
                        au monde.
                    </h1>

                    <p>
                        KaMa est une plateforme numérique dédiée à la découverte,
                        à la lecture et à la valorisation des livres africains.
                        Nous créons un espace où les lecteurs découvrent des histoires
                        uniques et où les écrivains peuvent publier leurs œuvres,
                        toucher de nouveaux lecteurs et développer leur communauté.
                    </p>

                    <div class="about-actions">

                        <a href="{{ route('catalogue') }}" class="about-btn primary">
                            <i class="bi bi-search"></i>
                            Découvrir les livres
                        </a>

                        <a href="{{ route('register') }}" class="about-btn secondary">
                            <i class="bi bi-pencil-square"></i>
                            Publier mon livre
                        </a>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="about-hero-image">

                    <div class="image-card-main">

                        <img src="{{ asset('assets/images/africa.jpg') }}"
                             alt="KaMa">

                    </div>


                </div>

            </div>

        </div>

    </section>

</div>


<!-- WHAT IS KAMA -->
<section class="about-intro">
    <div class="container">
        <div class="section-heading">
            <span>
                Notre vision
            </span>

            <h2>
                Donner une place aux histoires africaines
            </h2>

            <p>
                KaMa est née d'une ambition simple :
                rendre les livres africains plus accessibles
                tout en offrant aux écrivains une plateforme
                pour faire connaître leurs créations.
            </p>
        </div>


        <div class="about-cards">

            <div class="about-card">
                <div class="icon">
                    <i class="bi bi-book"></i>
                </div>

                <h3>
                    Pour les lecteurs
                </h3>


                <p>
                    Découvrez une bibliothèque numérique riche
                    en romans, essais, contes, ouvrages jeunesse
                    et bien plus encore.
                </p>


            </div>





            <div class="about-card">


                <div class="icon">

                    <i class="bi bi-pen"></i>

                </div>


                <h3>
                    Pour les écrivains
                </h3>


                <p>
                    Publiez vos œuvres, développez votre audience
                    et partagez vos histoires avec des lecteurs
                    partout dans le monde.
                </p>


            </div>





            <div class="about-card">


                <div class="icon">

                    <i class="bi bi-globe-africa"></i>

                </div>


                <h3>
                    Pour la culture africaine
                </h3>


                <p>
                    Préserver et promouvoir les voix,
                    les cultures et les imaginaires africains.
                </p>


            </div>


        </div>


    </div>


</section>

@endsection