
<footer class="kama-footer">
    <div class="container">

        {{-- MAIN FOOTER --}}
        <div class="kama-footer-main">

            {{-- BRAND --}}
            <div class="kama-footer-brand">

                <a href="{{ route('home') }}"
                   class="kama-footer-brand-link"
                   aria-label="KaMa — Accueil">

                    <img
                        src="{{ asset('assets/images/KaMa2.png') }}"
                        class="footer-logo"
                        alt="KaMa"
                    >
                </a>


                <div class="footer-social"
                     aria-label="Réseaux sociaux de KaMa">

                    {{-- Remplacer les # par les URL réelles des profils --}}
                    <a href="#"
                       aria-label="Facebook"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#"
                       aria-label="Instagram"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#"
                       aria-label="X"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="bi bi-twitter-x"></i>
                    </a>

                    <a href="#"
                       aria-label="LinkedIn"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="bi bi-linkedin"></i>
                    </a>

                    <a href="#"
                       aria-label="YouTube"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>
            </div>

            {{-- NAVIGATION --}}
            <div class="kama-footer-navigation">

                {{-- EXPLORE --}}
                <div class="kama-footer-column">
                    <h2>Explorer</h2>

                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li>
                            <a href="{{ route('catalogue') }}">Catalogue</a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}">À propos de KaMa</a>
                        </li>
                    </ul>
                </div>

                {{-- AUTHORS --}}
                <div class="kama-footer-column">
                    <h2>Pour les auteurs</h2>

                    <ul>
                        <li>
                            <a href="{{ route('register') }}">Devenir auteur</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}">Publier un livre</a>
                        </li>
                        <li>
                            <a href="{{ route('legal.author-terms') }}">
                                Conditions de publication
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- HELP --}}
                <div class="kama-footer-column">
                    <h2>Besoin d’aide ?</h2>

                    <ul>
                        <li>
                            <a href="mailto:contact@kama-online.com">
                                Nous contacter
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq') }}">FAQ</a>
                        </li>
                    </ul>
                </div>

                {{-- LEGAL --}}
                <div class="kama-footer-column">
                    <h2>Informations légales</h2>

                    <ul>
                        <li>
                            <a href="{{ route('legal.terms') }}">
                                Conditions d’utilisation
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.sales') }}">
                                Paiements et remboursements
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.privacy') }}">
                                Confidentialité
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.cookies') }}">
                                Politique relative aux cookies
                            </a>
                        </li>
                        <li>
                            <button type="button" class="kama-footer-cookie-btn js-open-cookie-settings">
                                Gestion des cookies
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- BOTTOM --}}
        <div class="kama-footer-bottom">

            <p class="kama-footer-copyright">
                © {{ date('Y') }} KaMa.
                Tous droits réservés.
            </p>

            <div class="kama-footer-payment"
                 aria-label="Moyens de paiement">

                <span>Paiements sécurisés</span>

                <img
                    src="{{ asset('assets/images/element/visa.svg') }}"
                    alt="Visa"
                    loading="lazy"
                >

                <img
                    src="{{ asset('assets/images/element/mastercard.svg') }}"
                    alt="Mastercard"
                    loading="lazy"
                >
            </div>

        </div>

    </div>
</footer>