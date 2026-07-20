@extends('layouts.app')

@section('content')
<div class="about-page">

    <div class="about-hero">

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
                        <span>connecte les œuvres littéraires africaines</span>
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

    </div>


    <!-- WHAT IS KAMA -->
    <div class="about-intro">

        <div class="container">

            <div class="section-heading">
                <div class="row mb-2">
                    <div class="col-12 text-center">
                        <span class="section-subtitle"> <i class="bi bi-person"></i>Notre vision</span>
                        <h2 class="section-title">
                        Donner une place aux <span> oeuvres africaines
                        </h2>
                    </div>
                </div>

                {{-- <p>
                    KaMa est née d'une ambition simple :
                    rendre les livres africains plus accessibles
                    tout en offrant aux écrivains africains une plateforme
                    pour partager leurs créations avec le monde.
                </p> --}}


            </div>



            <div class="about-cards">


                <!-- READERS -->

                <div class="about-card">

                    <div class="about-card-icon">

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


                    <a href="{{ route('catalogue') }}">
                        Explorer les livres
                        <i class="bi bi-arrow-right"></i>
                    </a>


                </div>





                <!-- AUTHORS -->

                <div class="about-card">

                    <div class="about-card-icon">

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


                    <a href="{{ route('register') }}">
                        Publier une œuvre
                        <i class="bi bi-arrow-right"></i>
                    </a>


                </div>





                <!-- CULTURE -->

                <div class="about-card">

                    <div class="about-card-icon">

                        <i class="bi bi-globe"></i>

                    </div>


                    <h3>
                        Pour la culture africaine
                    </h3>


                    <p>
                        Préserver et promouvoir les voix,
                        les cultures et les imaginaires africains
                        à travers la littérature.
                    </p>


                    <a href="#">
                        Notre mission
                        <i class="bi bi-arrow-right"></i>
                    </a>


                </div>



            </div>


        </div>


    </div>

    <!-- HOW KAMA WORKS -->
<section class="kama-how-section">

    <div class="container">

        <!-- HEADER -->
        <div class="kama-section-heading">

            <span>
                <i class="bi bi-lightbulb"></i>
                Comment ça marche ?
            </span>

            <h2>
                Une expérience simple pour
                <strong>lecteurs</strong> et
                <strong>écrivains</strong>
            </h2>

            <p>
                KaMa facilite la découverte, la lecture et la publication
                des histoires africaines grâce à une plateforme pensée
                pour tous.
            </p>

        </div>



        <div class="kama-process-grid">


            <!-- LECTEURS -->
            <div class="kama-process-card">

                <div class="process-header">

                    <div class="process-icon reader">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <h3>
                        Pour les lecteurs
                    </h3>

                </div>


                <div class="process-steps">


                    <div class="process-step">

                        <span>1</span>

                        <div>
                            <h4>Découvrez</h4>
                            <p>
                                Explorez un catalogue riche en livres
                                africains de différents genres.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>2</span>

                        <div>
                            <h4>Feuilletez</h4>
                            <p>
                                Consultez un aperçu avant de choisir
                                votre prochaine lecture.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>3</span>

                        <div>
                            <h4>Achetez</h4>
                            <p>
                                Accédez facilement à vos livres numériques.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>4</span>

                        <div>
                            <h4>Lisez partout</h4>
                            <p>
                                Retrouvez vos ouvrages à tout moment.
                            </p>
                        </div>

                    </div>


                </div>

            </div>






            <!-- AUTEURS -->
            <div class="kama-process-card">

                <div class="process-header">

                    <div class="process-icon author">
                        <i class="bi bi-pen"></i>
                    </div>

                    <h3>
                        Pour les écrivains
                    </h3>

                </div>



                <div class="process-steps">


                    <div class="process-step">

                        <span>1</span>

                        <div>
                            <h4>Créez votre espace</h4>
                            <p>
                                Inscrivez-vous et présentez votre profil auteur.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>2</span>

                        <div>
                            <h4>Publiez votre livre</h4>
                            <p>
                                Ajoutez votre œuvre et partagez votre histoire.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>3</span>

                        <div>
                            <h4>Atteignez vos lecteurs</h4>
                            <p>
                                Rendez votre livre visible auprès d'une communauté.
                            </p>
                        </div>

                    </div>



                    <div class="process-step">

                        <span>4</span>

                        <div>
                            <h4>Développez votre audience</h4>
                            <p>
                                Construisez votre présence sur KaMa.
                            </p>
                        </div>

                    </div>


                </div>


            </div>



        </div>


    </div>


</section>
</div>



@endsection