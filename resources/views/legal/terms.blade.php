
@extends('layouts.app')

@section('title', 'Conditions générales d’utilisation — KaMa')

@section('content')

<div class="kama-legal-page">

    {{-- HERO --}}
    <section class="kama-legal-hero">
        <div class="container">

            <span>
                <i class="bi bi-shield-check"></i>
                Informations légales KaMa
            </span>

            <h1>Conditions générales d’utilisation</h1>

            <p>
                Découvrez les règles qui encadrent l’utilisation de KaMa,
                la publication des œuvres, la protection des droits d’auteur
                et l’accès aux contenus littéraires numériques.
            </p>

            <div class="kama-legal-updated">
                <i class="bi bi-calendar3"></i>
                Dernière mise à jour : {{ date('d/m/Y') }}
            </div>

        </div>
    </section>


    {{-- LEGAL CONTENT --}}
    <section class="kama-legal-section-wrapper">
        <div class="container">

            <div class="kama-legal-layout">

                {{-- TABLE OF CONTENTS --}}
                <aside class="kama-legal-toc">

                    <h3>
                        <i class="bi bi-list-ul"></i>
                        Dans cette page
                    </h3>

                    <nav aria-label="Sommaire des conditions d’utilisation">

                        <a href="#presentation">
                            <i class="bi bi-chevron-right"></i>
                            Présentation de KaMa
                        </a>

                        <a href="#acceptation">
                            <i class="bi bi-chevron-right"></i>
                            Objet et acceptation
                        </a>

                        <a href="#compte">
                            <i class="bi bi-chevron-right"></i>
                            Création d’un compte
                        </a>

                        <a href="#utilisation">
                            <i class="bi bi-chevron-right"></i>
                            Utilisation de la plateforme
                        </a>

                        <a href="#lecteurs">
                            <i class="bi bi-chevron-right"></i>
                            Conditions applicables aux lecteurs
                        </a>

                        <a href="#auteurs">
                            <i class="bi bi-chevron-right"></i>
                            Conditions applicables aux auteurs
                        </a>

                        <a href="#audiobooks">
                            <i class="bi bi-chevron-right"></i>
                            Génération des audiobooks
                        </a>

                        <a href="#propriete">
                            <i class="bi bi-chevron-right"></i>
                            Propriété intellectuelle
                        </a>

                        <a href="#moderation">
                            <i class="bi bi-chevron-right"></i>
                            Signalement et modération
                        </a>

                        <a href="#suspension">
                            <i class="bi bi-chevron-right"></i>
                            Suspension d’un compte
                        </a>

                        <a href="#disponibilite">
                            <i class="bi bi-chevron-right"></i>
                            Disponibilité du service
                        </a>

                        <a href="#donnees">
                            <i class="bi bi-chevron-right"></i>
                            Données personnelles et cookies
                        </a>

                        <a href="#modification">
                            <i class="bi bi-chevron-right"></i>
                            Modification des conditions
                        </a>

                        <a href="#droit">
                            <i class="bi bi-chevron-right"></i>
                            Droit applicable et litiges
                        </a>

                        <a href="#contact">
                            <i class="bi bi-chevron-right"></i>
                            Contact
                        </a>

                    </nav>

                </aside>


                {{-- LEGAL TEXT --}}
                <div class="kama-legal-content">

                    {{-- INTRODUCTION --}}
                    <div class="kama-legal-introduction">

                        <h2>Bienvenue sur KaMa</h2>

                        <p>
                            KaMa est une plateforme numérique dédiée à la
                            découverte, à la publication et à la
                            commercialisation de livres numériques,
                            notamment d’œuvres d’auteurs africains.
                        </p>

                        <p>
                            Les présentes Conditions générales d’utilisation,
                            ci-après les « CGU », définissent les règles
                            applicables aux visiteurs, aux lecteurs et aux
                            auteurs qui utilisent les services de KaMa.
                        </p>

                        <p>
                            En créant un compte ou en utilisant les
                            fonctionnalités réservées de la plateforme,
                            l’utilisateur s’engage à respecter les présentes
                            conditions. Les achats sont également soumis
                            aux Conditions générales de vente (CGV), tandis
                            que les auteurs sont soumis aux conditions
                            spécifiques applicables à la publication de
                            leurs œuvres.
                        </p>

                    </div>


                    {{-- 01. PRESENTATION --}}
                    <section class="kama-legal-section" id="presentation">

                        <span class="kama-legal-section-number">01</span>

                        <h2>Présentation de KaMa</h2>

                        <p>
                            KaMa est une plateforme numérique permettant
                            aux lecteurs de découvrir des œuvres littéraires
                            et aux auteurs de proposer leurs créations à la
                            publication et à la vente, sous réserve des
                            conditions applicables.
                        </p>

                        <p>
                            Les services proposés peuvent notamment inclure :
                        </p>

                        <ul>
                            <li>la consultation du catalogue de livres ;</li>
                            <li>la présentation des auteurs et de leurs œuvres ;</li>
                            <li>l’achat et l’accès à des ebooks ;</li>
                            <li>l’achat et l’écoute de livres audio ;</li>
                            <li>la création et la gestion d’un espace lecteur ;</li>
                            <li>la soumission et le suivi des publications par les auteurs ;</li>
                            <li>la gestion des ventes et des revenus des auteurs, selon les fonctionnalités disponibles.</li>
                        </ul>

                        {{-- <p>
                            KaMa est exploitée par
                            <strong>[DÉNOMINATION SOCIALE DE L’ENTREPRISE]</strong>,
                            une entreprise enregistrée en France. Ses
                            informations légales complètes doivent être
                            consultées dans les mentions légales du site.
                        </p> --}}

                        <p>
                            Sauf disposition contractuelle contraire, la
                            création d’un compte ou la soumission d’une œuvre
                            ne transfère pas à KaMa la propriété intellectuelle
                            de cette œuvre.
                        </p>

                    </section>


                    {{-- 02. ACCEPTATION --}}
                    <section class="kama-legal-section" id="acceptation">

                        <span class="kama-legal-section-number">02</span>

                        <h2>Objet et acceptation des conditions</h2>

                        <p>
                            Les présentes CGU encadrent l’accès à KaMa et
                            l’utilisation de ses fonctionnalités. Elles
                            s’appliquent à tout utilisateur de la plateforme,
                            qu’il soit simple visiteur, lecteur inscrit
                            ou auteur.
                        </p>

                        <p>
                            L’utilisateur est invité à lire les présentes
                            conditions avant d’utiliser les services
                            concernés. Lorsque la réglementation ou la
                            nature d’un service l’exige, KaMa recueille
                            expressément son acceptation.
                        </p>

                        <p>
                            Les CGU ne remplacent pas les conditions
                            contractuelles propres aux achats, aux paiements,
                            à la publication ou à la rémunération des auteurs.
                            Ces opérations peuvent être soumises à des
                            documents distincts.
                        </p>

                        <p>
                            Si une disposition des présentes CGU est jugée
                            inapplicable, les autres dispositions demeurent
                            applicables dans la mesure permise par la loi.
                        </p>

                    </section>


                    {{-- 03. ACCOUNT --}}
                    <section class="kama-legal-section" id="compte">

                        <span class="kama-legal-section-number">03</span>

                        <h2>Création et gestion d’un compte</h2>

                        <p>
                            Certaines fonctionnalités de KaMa nécessitent
                            la création d’un compte utilisateur. Lors de
                            son inscription, l’utilisateur s’engage à
                            fournir des informations exactes, complètes
                            et à jour.
                        </p>

                        <p>
                            Il lui appartient de maintenir ses informations
                            à jour et de préserver la confidentialité de
                            ses identifiants. Il doit prendre des mesures
                            raisonnables pour empêcher tout accès non
                            autorisé à son compte.
                        </p>

                        <p>
                            Toute suspicion d’accès frauduleux ou
                            d’utilisation non autorisée doit être signalée
                            rapidement à KaMa par l’intermédiaire de
                            <strong>contact@kama-afrika.com</strong>.
                        </p>

                        <p>
                            L’utilisateur ne doit pas usurper l’identité
                            d’une autre personne, fournir volontairement
                            de fausses informations ou créer des comptes
                            dans le but de contourner les règles de la
                            plateforme.
                        </p>

                        <p>
                            KaMa peut demander des informations
                            complémentaires lorsque cela est nécessaire
                            à la sécurité du compte, à la vérification des
                            droits sur une œuvre, à la gestion des paiements
                            ou au respect d’une obligation légale.
                        </p>

                    </section>


                    {{-- 04. PLATFORM USE --}}
                    <section class="kama-legal-section" id="utilisation">

                        <span class="kama-legal-section-number">04</span>

                        <h2>Utilisation de la plateforme</h2>

                        <p>
                            L’utilisateur s’engage à utiliser KaMa
                            conformément aux lois applicables, aux
                            présentes CGU et aux droits des autres
                            utilisateurs.
                        </p>

                        <p>Il est notamment interdit de :</p>

                        <ul>
                            <li>utiliser la plateforme à des fins frauduleuses ou illicites ;</li>
                            <li>publier une œuvre sans disposer des droits nécessaires ;</li>
                            <li>copier, extraire, redistribuer ou revendre sans autorisation les contenus protégés ;</li>
                            <li>contourner les mesures de sécurité ou les restrictions d’accès aux contenus ;</li>
                            <li>tenter d’accéder sans autorisation aux comptes, données ou systèmes de KaMa ;</li>
                            <li>perturber le fonctionnement de la plateforme ou compromettre sa sécurité ;</li>
                            <li>usurper l’identité d’un utilisateur, d’un auteur ou d’un représentant de KaMa ;</li>
                            <li>manipuler artificiellement les ventes, les évaluations ou les statistiques ;</li>
                            <li>diffuser des contenus illicites ou portant atteinte aux droits de tiers ;</li>
                            <li>utiliser les données personnelles d’autres utilisateurs à des fins non autorisées.</li>
                        </ul>

                        <p>
                            KaMa peut examiner les violations présumées
                            des présentes règles et prendre des mesures
                            proportionnées, dans le respect des procédures
                            et garanties prévues par la réglementation
                            applicable.
                        </p>

                    </section>


                    {{-- 05. READERS --}}
                    <section class="kama-legal-section" id="lecteurs">

                        <span class="kama-legal-section-number">05</span>

                        <h2>Conditions applicables aux lecteurs</h2>

                        <p>
                            KaMa permet aux lecteurs de découvrir des
                            œuvres littéraires et, selon les fonctionnalités
                            disponibles, d’acheter des ebooks et des
                            audiobooks.
                        </p>

                        <p>
                            Les informations relatives à chaque œuvre,
                            notamment son titre, son auteur, sa description,
                            son format, son prix et les modalités d’accès,
                            sont présentées sur sa fiche.
                        </p>

                        <p>
                            L’achat d’un contenu numérique confère au
                            lecteur un droit d’utilisation personnel,
                            dans les limites prévues par les conditions
                            applicables à cet achat.
                        </p>

                        <p>
                            Sauf autorisation du titulaire des droits ou
                            exception prévue par la loi, le lecteur ne
                            peut pas reproduire, revendre, distribuer à
                            des tiers, mettre à disposition du public ou
                            exploiter commercialement le contenu acheté.
                        </p>

                        <p>
                            Les modalités de paiement, de livraison
                            numérique, de rétractation, de remboursement
                            et les garanties applicables sont définies
                            dans les
                            <a href="{{ route('legal.sales') }}">
                                Conditions générales de vente et la politique
                                de remboursement de KaMa
                            </a>.
                        </p>

                        <p>
                            Le lecteur demeure responsable de l’utilisation
                            de son compte et doit respecter les droits
                            des auteurs ainsi que les restrictions légales
                            applicables aux œuvres.
                        </p>

                    </section>


                    {{-- 06. AUTHORS --}}
                    <section class="kama-legal-section" id="auteurs">

                        <span class="kama-legal-section-number">06</span>

                        <h2>Conditions applicables aux auteurs</h2>

                        <p>
                            Les auteurs peuvent, lorsque la fonctionnalité
                            correspondante est disponible, soumettre
                            leurs œuvres à KaMa en vue d’un examen et
                            d’une éventuelle publication.
                        </p>

                        <p>
                            La soumission d’une œuvre ne garantit pas
                            son acceptation ni sa mise en vente. KaMa
                            peut vérifier les informations fournies,
                            la qualité technique du fichier, le respect
                            des règles de publication et les droits
                            nécessaires à la diffusion de l’œuvre.
                        </p>

                        <p>
                            L’auteur demeure responsable du contenu qu’il
                            soumet et des informations associées à celui-ci.
                            Il déclare disposer des droits et autorisations
                            nécessaires à sa publication et aux usages
                            qu’il autorise.
                        </p>

                        <p>
                            Il lui appartient notamment de vérifier les
                            droits relatifs aux textes, traductions,
                            illustrations, photographies, extraits et
                            autres éléments incorporés dans son œuvre.
                        </p>

                        <p>
                            Les droits accordés à KaMa, les modalités
                            de publication, la fixation des prix, les
                            commissions, les versements, le retrait des
                            œuvres et les éventuelles obligations
                            complémentaires sont définis dans les
                            conditions de publication des auteurs.
                        </p>

                        <p>
                            La création d’un compte auteur ne transfère
                            pas automatiquement à KaMa la propriété de
                            l’œuvre. Toute autorisation d’exploitation
                            doit être définie dans les conditions
                            contractuelles applicables.
                        </p>

                    </section>


                    {{-- 07. AUDIOBOOKS --}}
                    <section class="kama-legal-section" id="audiobooks">

                        <span class="kama-legal-section-number">07</span>

                        <h2>Génération et distribution des audiobooks</h2>

                        <p>
                            KaMa propose aux auteurs une fonctionnalité
                            permettant de transformer leurs œuvres
                            écrites en livres audio au moyen d’une
                            technologie de synthèse vocale.
                        </p>

                        <p>
                            La génération et la distribution d’un audiobook
                            à partir d’une œuvre nécessitent une autorisation
                            appropriée de l’auteur ou du titulaire des droits.
                            Cette autorisation doit couvrir les opérations
                            concernées, notamment la transmission du texte
                            au prestataire technique, la génération du
                            fichier audio, son traitement, son hébergement
                            et sa distribution sur KaMa.
                        </p>

                        <p>
                            L’auteur doit disposer des droits nécessaires
                            pour autoriser ces opérations. Il lui appartient
                            également de signaler toute restriction
                            contractuelle ou tout droit de tiers susceptible
                            de limiter la conversion ou la diffusion
                            audio de l’œuvre.
                        </p>

                        <p>
                            KaMa ne doit pas présenter une voix synthétique
                            comme étant celle d’une personne réelle ayant
                            enregistré ou approuvé l’œuvre sans autorisation
                            appropriée.
                        </p>

                        <p>
                            Les modalités de génération, de distribution,
                            de rémunération, de retrait et de traitement
                            des fichiers audio sont définies dans les
                            conditions de publication ou dans l’autorisation
                            spécifique applicable à cette fonctionnalité.
                        </p>

                    </section>


                    {{-- 08. INTELLECTUAL PROPERTY --}}
                    <section class="kama-legal-section" id="propriete">

                        <span class="kama-legal-section-number">08</span>

                        <h2>Propriété intellectuelle</h2>

                        <p>
                            Les œuvres accessibles sur KaMa sont protégées
                            par les règles applicables en matière de
                            propriété intellectuelle. Les droits sur
                            ces œuvres appartiennent à leurs auteurs
                            ou à leurs autres titulaires légitimes.
                        </p>

                        <p>
                            L’achat d’un ebook ou d’un audiobook ne
                            transfère pas les droits d’auteur au lecteur.
                            Il lui confère uniquement les droits d’usage
                            prévus par les conditions de vente et la
                            réglementation applicable.
                        </p>

                        <p>
                            La marque KaMa, son identité visuelle, son
                            interface, ses textes propres, ses éléments
                            graphiques et les autres éléments appartenant
                            à l’exploitant de la plateforme peuvent
                            également être protégés.
                        </p>

                        <p>
                            Toute reproduction, modification, extraction,
                            diffusion ou exploitation non autorisée de
                            ces éléments est interdite, sous réserve
                            des exceptions prévues par la loi.
                        </p>

                        <p>
                            Toute personne estimant qu’un contenu
                            accessible sur KaMa porte atteinte à ses
                            droits peut adresser un signalement à
                            <a href="mailto:contact@kama-afrika.com">
                                <strong>contact@kama-afrika.com</strong>
                            </a>,
                            en identifiant précisément le contenu
                            concerné et en fournissant les informations
                            nécessaires à l’examen de sa demande.
                        </p>

                    </section>


                    {{-- 09. MODERATION --}}
                    <section class="kama-legal-section" id="moderation">

                        <span class="kama-legal-section-number">09</span>

                        <h2>Signalement et modération des contenus</h2>

                        <p>
                            KaMa peut recevoir des signalements concernant
                            des contenus susceptibles d’être illicites,
                            contrefaisants ou contraires aux règles
                            de la plateforme.
                        </p>

                        <p>
                            Les signalements doivent être suffisamment
                            précis pour permettre l’identification du
                            contenu concerné et l’examen des faits
                            invoqués.
                        </p>

                        <p>
                            Selon la nature du signalement, les éléments
                            disponibles et les obligations légales
                            applicables, KaMa peut demander des
                            informations complémentaires, contacter
                            l’utilisateur concerné, restreindre
                            temporairement l’accès à un contenu ou
                            procéder à son retrait.
                        </p>

                        <p>
                            Lorsque la réglementation l’exige, KaMa
                            applique les procédures de notification,
                            de traitement des signalements et
                            d’information des personnes concernées
                            prévues par les textes applicables aux
                            services numériques.
                        </p>

                        <p>
                            Les utilisateurs doivent coopérer de bonne
                            foi à ces procédures et ne pas transmettre
                            sciemment de fausses informations.
                        </p>

                    </section>


                    {{-- 10. ACCOUNT SUSPENSION --}}
                    <section class="kama-legal-section" id="suspension">

                        <span class="kama-legal-section-number">10</span>

                        <h2>Suspension ou fermeture d’un compte</h2>

                        <p>
                            KaMa peut prendre des mesures à l’encontre
                            d’un compte en cas de violation des présentes
                            CGU, de fraude, d’atteinte aux droits de tiers,
                            de risque pour la sécurité de la plateforme
                            ou de manquement à une obligation légale.
                        </p>

                        <p>
                            Selon les circonstances, les mesures peuvent
                            comprendre un avertissement, une demande
                            de correction, une restriction de certaines
                            fonctionnalités, une suspension temporaire
                            ou la fermeture du compte.
                        </p>

                        <p>
                            Dans la mesure du possible et lorsque la
                            loi ne s’y oppose pas, l’utilisateur est
                            informé du motif de la mesure et des
                            modalités permettant de présenter ses
                            observations ou de contester la décision.
                        </p>

                        <p>
                            En cas de risque grave, de fraude manifeste
                            ou d’obligation légale, une mesure immédiate
                            peut être nécessaire.
                        </p>

                        <p>
                            La suspension ou la fermeture d’un compte
                            ne supprime pas automatiquement les droits
                            légaux des consommateurs ni les sommes
                            éventuellement dues aux auteurs. Les
                            conséquences sur les œuvres publiées et
                            les transactions en cours sont déterminées
                            conformément aux contrats applicables
                            et à la loi.
                        </p>

                    </section>


                    {{-- 11. SERVICE AVAILABILITY --}}
                    <section class="kama-legal-section" id="disponibilite">

                        <span class="kama-legal-section-number">11</span>

                        <h2>Disponibilité et sécurité du service</h2>

                        <p>
                            KaMa met en œuvre des mesures raisonnables
                            pour assurer le bon fonctionnement et la
                            sécurité de la plateforme.
                        </p>

                        <p>
                            Des interruptions temporaires peuvent
                            toutefois survenir en raison d’opérations
                            de maintenance, de mises à jour, d’incidents
                            techniques, de problèmes de sécurité ou
                            de défaillances de prestataires externes.
                        </p>

                        <p>
                            KaMa s’efforce de rétablir le service dans
                            des délais raisonnables lorsqu’une
                            interruption survient.
                        </p>

                        <p>
                            Aucune disposition des présentes CGU
                            ne vise à exclure ou à limiter une garantie
                            légale ou une responsabilité qui ne peut
                            être écartée en vertu du droit applicable.
                        </p>

                    </section>


                    {{-- 12. PERSONAL DATA --}}
                    <section class="kama-legal-section" id="donnees">

                        <span class="kama-legal-section-number">12</span>

                        <h2>Données personnelles et cookies</h2>

                        <p>
                            KaMa traite certaines données personnelles
                            nécessaires à la gestion des comptes, à
                            la fourniture des services, aux achats,
                            aux publications, à la sécurité et au
                            respect de ses obligations légales.
                        </p>

                        <p>
                            Les modalités de collecte, d’utilisation,
                            de conservation et de protection des données
                            personnelles, ainsi que les droits des
                            utilisateurs, sont décrits dans la
                            <a href="{{ route('legal.privacy') }}">
                                Politique de confidentialité
                            </a>
                            de KaMa.
                        </p>

                        <p>
                            L’utilisation des cookies et autres traceurs
                            est encadrée par la
                            <a href="{{ route('legal.cookies') }}">
                                Politique cookies
                            </a>
                            et, lorsque cela est requis, par le mécanisme
                            de gestion du consentement disponible sur
                            le site.
                        </p>

                        <p>
                            Les présentes CGU ne remplacent ni la
                            Politique de confidentialité ni la
                            Politique cookies.
                        </p>

                    </section>


                    {{-- 13. CHANGES --}}
                    <section class="kama-legal-section" id="modification">

                        <span class="kama-legal-section-number">13</span>

                        <h2>Modification des conditions</h2>

                        <p>
                            KaMa peut modifier les présentes CGU afin
                            de tenir compte des évolutions légales,
                            techniques, commerciales ou fonctionnelles
                            de la plateforme.
                        </p>

                        <p>
                            La version applicable est publiée sur
                            le site avec sa date de mise à jour.
                        </p>

                        <p>
                            En cas de modification substantielle
                            affectant les droits ou obligations des
                            utilisateurs, KaMa prend les mesures
                            d’information appropriées, conformément
                            à la réglementation applicable.
                        </p>

                        <p>
                            Lorsque la loi l’exige, le consentement
                            ou l’acceptation de l’utilisateur est
                            recueilli avant l’application des
                            nouvelles conditions.
                        </p>

                    </section>


                    {{-- 14. LAW AND DISPUTES --}}
                    <section class="kama-legal-section" id="droit">

                        <span class="kama-legal-section-number">14</span>

                        <h2>Droit applicable et règlement des litiges</h2>

                        <p>
                            Les présentes Conditions Générales d’Utilisation sont interprétées conformément 
                            au droit applicable à l’entité exploitant la plateforme KaMa, 
                            sous réserve des dispositions impératives de protection des consommateurs 
                            et des autres règles d’ordre public applicables dans le pays de résidence de l’utilisateur.
                        </p>

                        <p>
                            En cas de différend, KaMa et l’utilisateur s’efforceront de rechercher une solution amiable avant d’envisager toute autre voie de recours. 
                            Cette démarche ne prive pas l’utilisateur des droits et recours dont il bénéficie en vertu de la législation applicable.
                        </p>

                        <p>
                            Les coordonnées de contact sont indiquées
                            dans les mentions légales et dans la
                            section Contact ci-dessous.
                        </p>

                        <p>
                            Les droits impératifs dont bénéficient
                            les consommateurs dans leur pays de
                            résidence, lorsqu’ils sont applicables,
                            demeurent préservés.
                        </p>

                    </section>


                    {{-- 15. CONTACT --}}
                    <section class="kama-legal-section" id="contact">

                        <span class="kama-legal-section-number">15</span>

                        <h2>Contact</h2>

                        <p>
                            Pour toute question relative aux présentes
                            CGU, à l’utilisation de KaMa ou au
                            fonctionnement de la plateforme, vous
                            pouvez contacter l’équipe KaMa :
                            <a href="mailto:contact@kama-afrika.com">
                                    <strong>contact@kama-afrika.com</strong>
                                </a>
                        </p>
                               
                        </ul>

                        <p>
                            Les informations légales complètes de
                            l’entreprise sont disponibles dans les
                            mentions légales de KaMa.
                        </p>

                    </section>


                    {{-- FOOTER NOTE --}}
                    <div class="kama-legal-footer-note">

                        <i class="bi bi-info-circle"></i>

                        <p>
                            Les présentes CGU doivent être lues
                            conjointement avec les Conditions générales
                            de vente, la Politique de confidentialité,
                            la Politique cookies et les conditions de
                            publication des auteurs, lorsqu’elles
                            sont applicables.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </section>

</div>

@endsection
