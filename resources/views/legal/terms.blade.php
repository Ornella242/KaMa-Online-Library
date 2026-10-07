@extends('layouts.app')

@section('title', 'Conditions d’utilisation — KaMa')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="legal-page">

                <div class="mb-5">
                    <span class="text-muted">
                        Dernière mise à jour : {{ date('d/m/Y') }}
                    </span>

                    <h1 class="mt-2">
                        Conditions d’utilisation
                    </h1>

                    <p class="lead mt-3">
                        Les présentes conditions définissent les règles
                        applicables à l’utilisation de la plateforme KaMa.
                    </p>
                </div>


                <section class="mb-5">

                    <h2>1. Présentation de KaMa</h2>

                    <p>
                        KaMa est une plateforme numérique dédiée à la
                        découverte, à la publication et à la distribution
                        de contenus littéraires, notamment d’œuvres
                        d’auteurs africains.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>2. Acceptation des conditions</h2>

                    <p>
                        En accédant à KaMa ou en utilisant ses services,
                        l’utilisateur reconnaît avoir pris connaissance
                        des présentes conditions et accepte de les
                        respecter.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>3. Création d’un compte</h2>

                    <p>
                        Certaines fonctionnalités de KaMa nécessitent
                        la création d’un compte utilisateur.
                    </p>

                    <p>
                        L’utilisateur est responsable de l’exactitude
                        des informations fournies lors de son inscription
                        ainsi que de la confidentialité de ses
                        identifiants.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>4. Utilisation de la plateforme</h2>

                    <p>
                        L’utilisateur s’engage à utiliser KaMa de manière
                        légale et à ne pas porter atteinte au fonctionnement
                        de la plateforme, aux droits des autres utilisateurs
                        ou aux droits des auteurs et ayants droit.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>5. Contenus publiés par les auteurs</h2>

                    <p>
                        Les auteurs restent responsables des contenus
                        qu’ils soumettent à KaMa et doivent disposer des
                        droits nécessaires à leur publication et à leur
                        distribution.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>6. Propriété intellectuelle</h2>

                    <p>
                        Les œuvres publiées sur KaMa restent protégées
                        par les droits de propriété intellectuelle
                        applicables. Toute reproduction ou utilisation
                        non autorisée peut constituer une violation
                        des droits de leurs titulaires.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>7. Paiements et contenus numériques</h2>

                    <p>
                        Lorsque KaMa propose des contenus payants,
                        les conditions applicables aux paiements,
                        à l’accès aux contenus et aux remboursements
                        sont précisées dans la
                        <a href="{{ route('legal.sales') }}">
                            politique de paiement et de remboursement
                        </a>.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>8. Suspension ou fermeture d’un compte</h2>

                    <p>
                        KaMa peut prendre des mesures appropriées lorsqu’un
                        compte est utilisé en violation des présentes
                        conditions, des lois applicables ou des droits
                        de tiers.
                    </p>

                </section>


                <section class="mb-5">

                    <h2>9. Modification des conditions</h2>

                    <p>
                        KaMa peut mettre à jour les présentes conditions
                        lorsque cela est nécessaire. La version publiée
                        sur cette page constitue la version applicable
                        à compter de sa date de mise à jour.
                    </p>

                </section>


                <section>

                    <h2>10. Contact</h2>

                    <p>
                        Pour toute question concernant ces conditions,
                        veuillez contacter KaMa par l’intermédiaire
                        du canal de contact prévu sur la plateforme.
                    </p>

                </section>

            </div>

        </div>

    </div>

</div>


@endsection