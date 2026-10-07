
<footer class="kama-footer">
    <div class="container">

        <div class="row g-5">

            <!-- BRAND -->
            <div class="col-lg-5">

                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/KaMa2.png') }}"
                         class="footer-logo"
                         alt="KaMa">
                </a>

                <!-- SOCIAL -->
                <div class="footer-social">

                    <a href="#" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#" aria-label="X">
                        <i class="bi bi-twitter-x"></i>
                    </a>

                    <a href="#" aria-label="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>

                    <a href="#" aria-label="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>

            </div>


            <!-- LINKS -->
            <div class="col-lg-7">

                <div class="row g-4">

                    <!-- EXPLORE -->
                    <div class="col-6 col-md-3">

                        <h5>
                            Explorer
                        </h5>

                        <ul>

                            <li>
                                <a href="{{ route('home') }}">
                                    Accueil
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('catalogue') }}">
                                    Catalogue
                                </a>
                            </li>

                        </ul>

                    </div>


                    <!-- AUTHORS -->
                    <div class="col-6 col-md-3">

                        <h5>
                            Auteurs
                        </h5>

                        <ul>

                            <li>
                                <a href="{{ route('register') }}">
                                    Devenir auteur
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('register') }}">
                                    Publier un livre
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('register') }}">
                                    Espace écrivain
                                </a>
                            </li>

                        </ul>

                    </div>


                    <!-- ABOUT -->
                    <div class="col-6 col-md-3">

                        <h5>
                            KaMa
                        </h5>

                        <ul>

                            <li>
                                <a href="{{ route('about') }}">
                                    À propos
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    Contact
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('faq') }}">
                                    FAQ
                                </a>
                            </li>

                        </ul>

                    </div>


                    <!-- LEGAL -->
                    <div class="col-6 col-md-3">

                        <h5>
                            Légal
                        </h5>

                        <ul>

                            <li>
                                <a href="{{ route('legal.terms') }}">
                                    Conditions d'utilisation
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('legal.privacy') }}">
                                    Confidentialité
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('legal.cookies') }}">
                                    Cookies
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('legal.sales') }}">
                                    Paiement & remboursements
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>


        <!-- NEWSLETTER -->
        {{-- <div class="footer-newsletter">

            <div>

                <h4>
                    Restez connecté à la littérature africaine
                </h4>

                <p>
                    Recevez les nouveautés, les auteurs à découvrir et les actualités KaMa.
                </p>

            </div>


            <form>

                <input
                    type="email"
                    name="email"
                    placeholder="Votre adresse email"
                    autocomplete="email"
                    required
                >

                <button type="submit">
                    S'inscrire
                </button>

            </form>

        </div> --}}


        <!-- BOTTOM -->
        <div class="footer-bottom">

            <span>
                © {{ date('Y') }} KaMa. Tous droits réservés.
            </span>

            <div class="payment-icons">

                <img
                    src="{{ asset('assets/images/element/visa.svg') }}"
                    alt="Visa"
                >

                <img
                    src="{{ asset('assets/images/element/mastercard.svg') }}"
                    alt="Mastercard"
                >

            </div>

        </div>

    </div>
</footer>