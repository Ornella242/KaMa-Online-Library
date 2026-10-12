@extends('layouts.app')

@section('title', 'Politique relative aux cookies — KaMa')

@section('content')
<div class="kama-legal-page">

    {{-- En-tête --}}
    <section class="kama-legal-hero">
        <div class="container">
            <span class="kama-legal-eyebrow">
                <i class="bi bi-shield-check"></i>
                Transparence et confidentialité
            </span>

            <h1>Politique relative aux cookies</h1>

            <p>
                Découvrez comment KaMa utilise les cookies et technologies
                similaires pour assurer le fonctionnement de la plateforme,
                améliorer votre expérience et protéger vos préférences.
            </p>

            <div class="kama-legal-updated">
                <i class="bi bi-calendar3"></i>
                Dernière mise à jour : 11 octobre 2026
            </div>
        </div>
    </section>

    {{-- Contenu juridique --}}
    <section class="kama-legal-section-wrapper">
        <div class="container">
            <div class="kama-legal-layout">

                {{-- Sommaire --}}
                <aside class="kama-legal-toc">
                    <h3>
                        <i class="bi bi-list-ul"></i>
                        Dans cette page
                    </h3>

                    <nav aria-label="Sommaire de la politique relative aux cookies">
                        <a href="#presentation">
                            <i class="bi bi-chevron-right"></i>
                            Présentation des cookies
                        </a>

                        <a href="#fonctionnement">
                            <i class="bi bi-chevron-right"></i>
                            Fonctionnement des cookies
                        </a>

                        <a href="#categories">
                            <i class="bi bi-chevron-right"></i>
                            Catégories de cookies
                        </a>

                        <a href="#finalites">
                            <i class="bi bi-chevron-right"></i>
                            Finalités d'utilisation
                        </a>

                        <a href="#tiers">
                            <i class="bi bi-chevron-right"></i>
                            Services tiers
                        </a>

                        <a href="#consentement">
                            <i class="bi bi-chevron-right"></i>
                            Consentement
                        </a>

                        <a href="#gestion">
                            <i class="bi bi-chevron-right"></i>
                            Gestion des préférences
                        </a>

                        <a href="#navigateurs">
                            <i class="bi bi-chevron-right"></i>
                            Paramètres du navigateur
                        </a>

                        <a href="#duree">
                            <i class="bi bi-chevron-right"></i>
                            Durée de conservation
                        </a>

                        <a href="#donnees">
                            <i class="bi bi-chevron-right"></i>
                            Données personnelles
                        </a>

                        <a href="#modifications">
                            <i class="bi bi-chevron-right"></i>
                            Modifications de la politique
                        </a>

                        <a href="#contact">
                            <i class="bi bi-chevron-right"></i>
                            Contact
                        </a>
                    </nav>
                </aside>

                {{-- Contenu principal --}}
                <main class="kama-legal-content">

                    <div class="kama-legal-introduction">
                        <p>
                            La présente politique explique comment les cookies
                            et technologies similaires peuvent être utilisés
                            lorsque vous consultez le site KaMa, naviguez dans
                            la bibliothèque, accédez à votre compte ou effectuez
                            des opérations sur la plateforme.
                        </p>

                        <p>
                            Elle complète notre
                            <a href="{{ route('legal.privacy') }}">
                                politique de confidentialité
                            </a>
                            et doit être lue conjointement avec nos
                            <a href="{{ route('legal.terms') }}">
                                conditions générales d'utilisation
                            </a>.
                        </p>
                    </div>

                    {{-- 01 --}}
                    <section class="kama-legal-section" id="presentation">
                        <span class="kama-legal-section-number">01</span>

                        <h2>Présentation des cookies</h2>

                        <p>
                            Un cookie est un petit fichier enregistré sur votre
                            appareil lorsque vous consultez un site web.
                            Il permet notamment de mémoriser certaines
                            informations relatives à votre navigation ou à
                            vos préférences.
                        </p>

                        <p>
                            Des technologies similaires peuvent également être
                            utilisées pour assurer le fonctionnement du site,
                            maintenir une session utilisateur, mémoriser des
                            choix ou mesurer l'utilisation de certaines
                            fonctionnalités.
                        </p>

                        <p>
                            Dans cette politique, le terme « cookies » désigne,
                            par souci de simplicité, les cookies et les
                            technologies similaires concernés.
                        </p>
                    </section>

                    {{-- 02 --}}
                    <section class="kama-legal-section" id="fonctionnement">
                        <span class="kama-legal-section-number">02</span>

                        <h2>Comment fonctionnent les cookies sur KaMa ?</h2>

                        <p>
                            Selon les fonctionnalités effectivement activées,
                            KaMa peut utiliser des cookies déposés directement
                            par la plateforme ou des technologies mises en
                            œuvre par certains prestataires tiers.
                        </p>

                        <p>Ces technologies peuvent notamment servir à :</p>

                        <ul>
                            <li>maintenir votre session lorsque vous êtes connecté ;</li>
                            <li>sécuriser votre compte et les opérations réalisées sur la plateforme ;</li>
                            <li>mémoriser certaines préférences de navigation ;</li>
                            <li>faciliter l'utilisation des fonctionnalités de KaMa ;</li>
                            <li>mesurer l'audience ou améliorer les performances du site, si de tels outils sont utilisés.</li>
                        </ul>

                        <p>
                            Les cookies effectivement utilisés dépendent de la
                            configuration technique du site et des services
                            qui y sont intégrés.
                        </p>
                    </section>

                    {{-- 03 --}}
                    <section class="kama-legal-section" id="categories">
                        <span class="kama-legal-section-number">03</span>

                        <h2>Catégories de cookies</h2>

                        <h3>3.1. Cookies strictement nécessaires</h3>

                        <p>
                            Ces cookies sont nécessaires au fonctionnement du
                            site ou à la fourniture d'un service expressément
                            demandé par l'utilisateur.
                        </p>

                        <p>Ils peuvent notamment contribuer à :</p>

                        <ul>
                            <li>gérer l'authentification et les sessions ;</li>
                            <li>protéger les formulaires contre certaines attaques ;</li>
                            <li>assurer la sécurité et l'intégrité des fonctionnalités ;</li>
                            <li>mémoriser les choix relatifs au consentement aux cookies.</li>
                        </ul>

                        <p>
                            Lorsque ces cookies sont strictement nécessaires,
                            ils peuvent être utilisés sans consentement
                            préalable, dans les limites prévues par la
                            réglementation applicable.
                        </p>

                        <h3>3.2. Cookies de mesure d'audience</h3>

                        <p>
                            Si KaMa utilise des outils de mesure d'audience,
                            ceux-ci peuvent produire des statistiques relatives
                            à la fréquentation du site, aux pages consultées
                            ou à l'utilisation de certaines fonctionnalités.
                        </p>

                        <p>
                            Le recours à ces cookies dépend de l'outil utilisé
                            et de ses caractéristiques. Lorsqu'un consentement
                            préalable est requis, ils ne doivent être activés
                            qu'après l'obtention de ce consentement.
                        </p>

                        <h3>3.3. Cookies fonctionnels</h3>

                        <p>
                            Certains cookies peuvent permettre de mémoriser
                            des préférences ou de faciliter des fonctionnalités
                            supplémentaires. Leur régime dépend de leur
                            finalité et de leur nécessité réelle pour le
                            service demandé.
                        </p>

                        <h3>3.4. Cookies publicitaires ou de suivi</h3>

                        <p>
                            Si des outils publicitaires ou de suivi sont
                            intégrés à KaMa, ils peuvent servir à mesurer
                            des campagnes ou à personnaliser des contenus.
                            Lorsqu'ils sont soumis au consentement préalable,
                            ils ne sont activés qu'après l'accord de
                            l'utilisateur.
                        </p>

                        <p>
                            Cette description des catégories ne signifie pas
                            que toutes ces catégories sont actuellement
                            utilisées sur KaMa.
                        </p>
                    </section>

                    {{-- 04 --}}
                    <section class="kama-legal-section" id="finalites">
                        <span class="kama-legal-section-number">04</span>

                        <h2>Finalités d'utilisation</h2>

                        <p>
                            Les cookies utilisés par KaMa peuvent répondre
                            aux finalités suivantes, lorsque les fonctionnalités
                            correspondantes sont effectivement mises en œuvre :
                        </p>

                        <ul>
                            <li>assurer le fonctionnement et la sécurité de la plateforme ;</li>
                            <li>permettre la connexion et la navigation dans les espaces utilisateurs ;</li>
                            <li>mémoriser les préférences et les choix de consentement ;</li>
                            <li>faciliter les opérations liées à l'achat de livres ou aux paiements ;</li>
                            <li>établir des statistiques d'utilisation et améliorer les performances ;</li>
                            <li>permettre certaines fonctionnalités fournies par des services tiers.</li>
                        </ul>

                        <p>
                            KaMa ne doit pas utiliser des cookies soumis au
                            consentement préalable avant d'avoir obtenu le
                            consentement requis.
                        </p>
                    </section>

                    {{-- 05 --}}
                    <section class="kama-legal-section" id="tiers">
                        <span class="kama-legal-section-number">05</span>

                        <h2>Services tiers</h2>

                        <p>
                            KaMa peut faire appel à des prestataires externes
                            pour certaines fonctionnalités, notamment le
                            traitement des paiements.
                        </p>

                        <p>
                            Selon le parcours utilisé et l'intégration technique
                            mise en place, certains prestataires peuvent utiliser
                            leurs propres cookies ou technologies similaires.
                            Cela ne signifie pas que chaque prestataire dépose
                            systématiquement des cookies sur toutes les pages
                            de KaMa.
                        </p>

                    </section>

                    {{-- 06 --}}
                    <section class="kama-legal-section" id="consentement">
                        <span class="kama-legal-section-number">06</span>

                        <h2>Consentement aux cookies</h2>

                        <p>
                            Lorsque la réglementation applicable l'exige,
                            le dépôt ou la lecture de cookies non essentiels
                            est soumis au consentement préalable de
                            l'utilisateur.
                        </p>

                        <p>
                            Ce consentement doit être libre, spécifique,
                            éclairé et univoque. L'utilisateur doit pouvoir
                            accepter ou refuser les cookies concernés sans
                            que le refus soit rendu inutilement difficile.
                        </p>

                        <p>
                            Les cookies strictement nécessaires au fonctionnement
                            du service peuvent être exemptés de consentement
                            dans les conditions prévues par la réglementation.
                        </p>

                        <p>
                            Lorsque le consentement est requis, la poursuite
                            de la navigation ne doit pas être considérée,
                            à elle seule, comme une acceptation des cookies.
                        </p>

                        <p>
                            Le consentement doit pouvoir être retiré aussi
                            facilement qu'il a été donné. Les choix de
                            l'utilisateur doivent être respectés par le
                            mécanisme de gestion des cookies.
                        </p>
                    </section>

                    {{-- 07 --}}
                    <section class="kama-legal-section" id="gestion">
                        <span class="kama-legal-section-number">07</span>

                        <h2>Gestion de vos préférences</h2>

                        <p>
                            Lorsque KaMa utilise des cookies soumis au
                            consentement préalable, la plateforme doit mettre
                            à disposition un mécanisme permettant de choisir
                            les catégories acceptées et refusées.
                        </p>

                        <p>
                            Vous devez pouvoir modifier vos choix ou retirer
                            votre consentement après votre première visite.
                            Les cookies non essentiels concernés doivent
                            alors cesser d'être utilisés conformément aux
                            règles applicables.
                        </p>

                        <p>
                            Pour modifier vos préférences, utilisez le lien
                            ou le bouton de gestion des cookies disponible
                            sur KaMa, lorsqu'il est mis en place.
                        </p>

                    </section>

                    {{-- 08 --}}
                    <section class="kama-legal-section" id="navigateurs">
                        <span class="kama-legal-section-number">08</span>

                        <h2>Paramètres de votre navigateur</h2>

                        <p>
                            La plupart des navigateurs permettent de consulter,
                            bloquer ou supprimer les cookies enregistrés sur
                            votre appareil.
                        </p>

                        <p>
                            Vous pouvez consulter l'aide de votre navigateur
                            pour connaître les options disponibles, notamment
                            dans les paramètres de confidentialité et de
                            sécurité.
                        </p>

                        <p>
                            La suppression ou le blocage de certains cookies
                            peut toutefois affecter le fonctionnement de
                            KaMa. Vous pourriez notamment devoir vous
                            reconnecter ou perdre certaines préférences
                            enregistrées.
                        </p>

                        <p>
                            Les paramètres du navigateur ne remplacent pas
                            nécessairement le mécanisme de consentement
                            proposé par le site.
                        </p>
                    </section>

                    {{-- 09 --}}
                    <section class="kama-legal-section" id="duree">
                        <span class="kama-legal-section-number">09</span>

                        <h2>Durée de conservation</h2>

                        <p>
                            La durée de conservation des cookies dépend de
                            leur nature, de leur finalité et des paramètres
                            techniques utilisés.
                        </p>

                        <p>
                            Certains cookies sont supprimés à la fermeture
                            du navigateur. D'autres peuvent rester enregistrés
                            pendant une durée déterminée.
                        </p>

                        <p>
                            Lorsque la réglementation applicable prévoit
                            une durée maximale ou des règles spécifiques,
                            KaMa veille à respecter ces exigences.
                        </p>

                        <p>
                            La durée effective de chaque cookie doit être
                            vérifiée dans l'inventaire technique des cookies
                            et des technologies réellement utilisés sur
                            la plateforme.
                        </p>
                    </section>

                    {{-- 10 --}}
                    <section class="kama-legal-section" id="donnees">
                        <span class="kama-legal-section-number">10</span>

                        <h2>Cookies et données personnelles</h2>

                        <p>
                            Certains cookies ou identifiants techniques
                            peuvent être associés à des données personnelles
                            ou permettre d'identifier indirectement un
                            utilisateur.
                        </p>

                        <p>
                            Lorsque des données personnelles sont traitées
                            par l'intermédiaire de ces technologies, leur
                            traitement doit respecter la réglementation
                            applicable en matière de protection des données.
                        </p>

                        <p>
                            Pour connaître les catégories de données traitées,
                            les finalités, les bases légales, les destinataires
                            et vos droits, consultez notre
                            <a href="{{ route('legal.privacy') }}">
                                politique de confidentialité
                            </a>.
                        </p>
                    </section>

                    {{-- 11 --}}
                    <section class="kama-legal-section" id="modifications">
                        <span class="kama-legal-section-number">11</span>

                        <h2>Modifications de la politique</h2>

                        <p>
                            KaMa peut mettre à jour cette politique afin
                            de refléter les évolutions de la plateforme,
                            des technologies utilisées, des prestataires
                            ou de la réglementation applicable.
                        </p>

                        <p>
                            La version publiée sur cette page indique la
                            date de sa dernière mise à jour. Lorsque les
                            changements le nécessitent, les utilisateurs
                            seront informés selon des modalités appropriées.
                        </p>
                    </section>

                    {{-- 12 --}}
                    <section class="kama-legal-section" id="contact">
                        <span class="kama-legal-section-number">12</span>

                        <h2>Contact</h2>

                        <p>
                            Pour toute question concernant cette politique,
                            l'utilisation des cookies ou la gestion de vos
                            préférences, vous pouvez contacter l'équipe
                            KaMa :
                        </p>

                        <p>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:contact@kama-online.com">
                                <strong>contact@kama-online.com</strong>
                            </a>
                        </p>

                        <p>
                            Pour les demandes relatives à vos données
                            personnelles, vous pouvez également consulter
                            notre
                            <a href="{{ route('legal.privacy') }}">
                                politique de confidentialité
                            </a>.
                        </p>
                    </section>

                    {{-- Note finale --}}
                    <div class="kama-legal-footer-note">
                        <i class="bi bi-info-circle"></i>

                        <p>
                            Cette politique présente les principes applicables
                            aux cookies sur KaMa. Les cookies, leurs finalités,
                            leurs durées de conservation et les éventuels
                            prestataires associés doivent être confirmés à
                            partir de la configuration réelle de la plateforme.
                        </p>
                    </div>

                </main>
            </div>
        </div>
    </section>
</div>
@endsection