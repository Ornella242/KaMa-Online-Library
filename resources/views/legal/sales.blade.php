@extends('layouts.app')

@section('title', 'Conditions générales de vente — KaMa')

@section('content')
<div class="kama-legal-page">

    {{-- En-tête --}}
    <section class="kama-legal-hero">
        <div class="container">
            <span class="kama-legal-eyebrow">
                <i class="bi bi-bag-check"></i>
                Achats et transactions
            </span>

            <h1>Conditions générales de vente</h1>

            <p>
                Retrouvez les conditions applicables aux achats de livres
                numériques sur KaMa, aux paiements, à l'accès aux contenus
                et aux demandes de remboursement.
            </p>

            <div class="kama-legal-updated">
                <i class="bi bi-calendar3"></i>
                Dernière mise à jour : 11 octobre 2026
            </div>
        </div>
    </section>

    {{-- Contenu --}}
    <section class="kama-legal-section-wrapper">
        <div class="container">
            <div class="kama-legal-layout">

                {{-- Sommaire --}}
                <aside class="kama-legal-toc">
                    <h3>
                        <i class="bi bi-list-ul"></i>
                        Dans cette page
                    </h3>

                    <nav aria-label="Sommaire des conditions générales de vente">
                        <a href="#objet">
                            <i class="bi bi-chevron-right"></i>
                            Objet et champ d'application
                        </a>

                        <a href="#vendeur">
                            <i class="bi bi-chevron-right"></i>
                            Rôle de KaMa
                        </a>

                        <a href="#produits">
                            <i class="bi bi-chevron-right"></i>
                            Produits numériques
                        </a>

                        <a href="#compte">
                            <i class="bi bi-chevron-right"></i>
                            Compte utilisateur
                        </a>

                        <a href="#prix">
                            <i class="bi bi-chevron-right"></i>
                            Prix et taxes
                        </a>

                        <a href="#commande">
                            <i class="bi bi-chevron-right"></i>
                            Commande
                        </a>

                        <a href="#paiement">
                            <i class="bi bi-chevron-right"></i>
                            Paiement
                        </a>

                        <a href="#livraison">
                            <i class="bi bi-chevron-right"></i>
                            Livraison numérique
                        </a>

                        <a href="#retractation">
                            <i class="bi bi-chevron-right"></i>
                            Droit de rétractation
                        </a>

                        <a href="#remboursement">
                            <i class="bi bi-chevron-right"></i>
                            Remboursements
                        </a>

                        <a href="#conformite">
                            <i class="bi bi-chevron-right"></i>
                            Conformité des contenus
                        </a>

                        <a href="#responsabilite">
                            <i class="bi bi-chevron-right"></i>
                            Responsabilité
                        </a>

                        <a href="#reclamations">
                            <i class="bi bi-chevron-right"></i>
                            Réclamations
                        </a>

                        <a href="#donnees">
                            <i class="bi bi-chevron-right"></i>
                            Données personnelles
                        </a>

                        <a href="#droit">
                            <i class="bi bi-chevron-right"></i>
                            Droit applicable et litiges
                        </a>

                        <a href="#modifications">
                            <i class="bi bi-chevron-right"></i>
                            Modification des conditions
                        </a>

                        <a href="#contact">
                            <i class="bi bi-chevron-right"></i>
                            Contact
                        </a>
                    </nav>
                </aside>

                {{-- Texte principal --}}
                <main class="kama-legal-content">

                    <div class="kama-legal-introduction">
                        <p>
                            Les présentes conditions générales de vente,
                            ci-après les « CGV », définissent les conditions
                            applicables aux commandes et aux achats de contenus
                            numériques proposés sur KaMa.
                        </p>

                        <p>
                            Elles complètent les
                            <a href="{{ route('legal.terms') }}">
                                conditions générales d'utilisation
                            </a>
                            et la
                            <a href="{{ route('legal.privacy') }}">
                                politique de confidentialité
                            </a>
                            de la plateforme.
                        </p>

                        <p>
                            Les droits impératifs accordés aux consommateurs
                            par la législation applicable restent préservés.
                            Aucune disposition des présentes CGV ne vise à
                            supprimer ou limiter un droit auquel le consommateur
                            ne peut légalement renoncer.
                        </p>
                    </div>

                    {{-- 01 --}}
                    <section class="kama-legal-section" id="objet">
                        <span class="kama-legal-section-number">01</span>

                        <h2>Objet et champ d'application</h2>

                        <p>
                            KaMa est une plateforme numérique dédiée à la
                            découverte, à la publication, à l'achat et à la
                            consultation de livres numériques, notamment des
                            ebooks et des audiobooks.
                        </p>

                        <p>
                            Les présentes CGV s'appliquent aux achats réalisés
                            sur la plateforme par les utilisateurs, sous
                            réserve des dispositions particulières applicables
                            à certains produits, services ou catégories
                            d'utilisateurs.
                        </p>

                        <p>
                            Avant de finaliser une commande, l'acheteur doit
                            pouvoir consulter les caractéristiques essentielles
                            du produit, son prix et les principales conditions
                            de l'achat.
                        </p>

                        <p>
                            La validation de la commande implique l'acceptation
                            des CGV présentées à l'acheteur au moment de
                            l'achat.
                        </p>
                    </section>

                    {{-- 02 --}}
                    <section class="kama-legal-section" id="vendeur">
                        <span class="kama-legal-section-number">02</span>

                        <h2>Rôle de KaMa</h2>

                        <p>
                            KaMa met à disposition une plateforme permettant
                            aux auteurs de proposer leurs ouvrages et aux
                            lecteurs de découvrir et d'acheter des contenus
                            numériques.
                        </p>

                        <p>
                            Selon le modèle de commercialisation effectivement
                            appliqué à une transaction, KaMa peut intervenir
                            dans la présentation des ouvrages, la gestion des
                            commandes, le traitement des paiements et l'accès
                            aux contenus achetés.
                        </p>

                        <p>
                            Les informations communiquées lors de l'achat
                            doivent permettre d'identifier le vendeur
                            contractuel lorsqu'il est distinct de KaMa.
                            Lorsque KaMa vend directement un contenu, elle
                            assume les obligations applicables à cette vente.
                        </p>

                        <p>
                            Les conditions particulières applicables aux
                            auteurs, notamment celles relatives à la
                            publication, aux droits d'exploitation et à la
                            rémunération, sont définies dans les documents
                            contractuels qui leur sont destinés.
                        </p>
                    </section>

                    {{-- 03 --}}
                    <section class="kama-legal-section" id="produits">
                        <span class="kama-legal-section-number">03</span>

                        <h2>Produits numériques proposés</h2>

                        <p>
                            KaMa peut notamment proposer les catégories
                            de produits suivantes :
                        </p>

                        <ul>
                            <li>
                                <strong>Ebooks :</strong> livres accessibles
                                sous forme numérique, dans les formats
                                indiqués sur la fiche du produit.
                            </li>

                            <li>
                                <strong>Audiobooks :</strong> livres proposés
                                sous forme audio et accessibles selon les
                                modalités précisées sur la fiche du produit.
                            </li>
                        </ul>

                        <p>
                            Chaque fiche produit présente, dans la mesure
                            applicable, le titre de l'ouvrage, le nom de
                            l'auteur, une description, le format proposé,
                            le prix et les conditions d'accès.
                        </p>

                        <p>
                            Les extraits, aperçus et descriptions ont pour
                            objectif de permettre à l'acheteur de mieux
                            connaître le contenu avant son achat. Ils ne
                            remplacent pas les caractéristiques essentielles
                            du produit.
                        </p>

                        <p>
                            Les fonctionnalités disponibles peuvent varier
                            selon le format acheté et les conditions
                            techniques de la plateforme.
                        </p>
                    </section>

                    {{-- 04 --}}
                    <section class="kama-legal-section" id="compte">
                        <span class="kama-legal-section-number">04</span>

                        <h2>Compte utilisateur</h2>

                        <p>
                            Certaines fonctionnalités d'achat ou d'accès
                            aux contenus peuvent nécessiter la création
                            d'un compte KaMa.
                        </p>

                        <p>
                            L'acheteur s'engage à fournir des informations
                            exactes et à maintenir la confidentialité de
                            ses identifiants de connexion.
                        </p>

                        <p>
                            L'acheteur doit signaler à KaMa toute utilisation
                            non autorisée de son compte dont il a connaissance.
                        </p>

                        <p>
                            Les achats sont associés au compte ou au moyen
                            d'accès prévu par la plateforme. L'acheteur doit
                            vérifier les informations de son compte avant
                            de finaliser une commande.
                        </p>
                    </section>

                    {{-- 05 --}}
                    <section class="kama-legal-section" id="prix">
                        <span class="kama-legal-section-number">05</span>

                        <h2>Prix et taxes</h2>

                        <p>
                            Les prix sont affichés sur les fiches produits
                            et dans le récapitulatif de commande avant
                            la validation de l'achat.
                        </p>

                        <p>
                            La devise applicable est celle indiquée au
                            moment de la commande. Si une conversion
                            monétaire est nécessaire, son traitement dépend
                            notamment du prestataire de paiement et de
                            l'établissement financier concerné.
                        </p>

                        <p>
                            Les taxes applicables sont déterminées en
                            fonction de la nature de la transaction, du
                            lieu de résidence de l'acheteur et des règles
                            fiscales applicables.
                        </p>

                        <p>
                            Lorsque des taxes ou des frais supplémentaires
                            sont applicables et doivent être supportés
                            par l'acheteur, ils sont indiqués avant la
                            validation définitive de la commande.
                        </p>

                        <p>
                            KaMa peut modifier les prix des produits pour
                            les commandes futures. Une modification de prix
                            ne change pas rétroactivement le prix d'une
                            commande déjà conclue, sous réserve des règles
                            applicables.
                        </p>
                    </section>

                    {{-- 06 --}}
                    <section class="kama-legal-section" id="commande">
                        <span class="kama-legal-section-number">06</span>

                        <h2>Commande</h2>

                        <p>
                            Avant de confirmer un achat, l'acheteur doit
                            pouvoir vérifier le produit sélectionné, le
                            prix total, la devise et les informations
                            nécessaires à la transaction.
                        </p>

                        <p>
                            L'acheteur doit ensuite utiliser la commande
                            de validation prévue sur la plateforme et
                            effectuer les étapes de paiement demandées.
                        </p>

                        <p>
                            Une commande est considérée comme confirmée
                            lorsque les conditions de validation prévues
                            par le parcours d'achat sont remplies et que
                            le paiement a été accepté, lorsqu'un paiement
                            est requis.
                        </p>

                        <p>
                            Un message de confirmation ou un récapitulatif
                            peut être mis à disposition sur KaMa ou transmis
                            par voie électronique.
                        </p>

                        <p>
                            En cas d'échec, de refus ou d'interruption du
                            paiement, la commande peut rester inachevée
                            et l'accès au contenu payant peut ne pas être
                            activé.
                        </p>
                    </section>

                    {{-- 07 --}}
                    <section class="kama-legal-section" id="paiement">
                        <span class="kama-legal-section-number">07</span>

                        <h2>Paiement</h2>

                        <p>
                            Les moyens de paiement disponibles sont ceux
                            présentés au moment de la commande. Selon le
                            pays, la devise et le parcours d'achat.
                        </p>

                        <h3>7.1. Prestataires de paiement</h3>

                        <p>
                            Les transactions sont soumises aux conditions
                            du prestataire de paiement utilisé et, le cas
                            échéant, à celles de la banque ou de l'opérateur
                            de paiement de l'acheteur.
                        </p>

                        <p>
                            Certains moyens de paiement peuvent nécessiter
                            une authentification ou une confirmation
                            supplémentaire.
                        </p>

                        <h3>7.2. Sécurité et validation</h3>

                        <p>
                            KaMa ne considère pas une simple
                            redirection vers un prestataire ou le lancement
                            d'une tentative de paiement comme une preuve
                            suffisante de paiement réussi. La validation
                            dépend du statut confirmé de la transaction
                            selon le fonctionnement technique applicable.
                        </p>

                        <h3>7.3. Incident de paiement</h3>

                        <p>
                            En cas de paiement refusé, interrompu, annulé
                            ou non confirmé, l'acheteur peut être invité
                            à vérifier les informations fournies ou à
                            utiliser un autre moyen de paiement disponible.
                        </p>

                        <p>
                            Si un montant a été débité alors que la commande
                            n'a pas été confirmée ou que le contenu n'a
                            pas été rendu accessible, l'acheteur peut
                            contacter KaMa afin que la situation soit
                            vérifiée et qu'une solution appropriée soit
                            recherchée.
                        </p>
                    </section>

                    {{-- 08 --}}
                    <section class="kama-legal-section" id="livraison">
                        <span class="kama-legal-section-number">08</span>

                        <h2>Livraison et accès aux contenus numériques</h2>

                        <p>
                            Les produits vendus sur KaMa sont des contenus
                            numériques. Ils sont mis à disposition par voie
                            électronique et ne donnent pas lieu à une
                            livraison physique.
                        </p>

                        <p>
                            Après confirmation du paiement, le contenu
                            acheté est rendu accessible selon les modalités
                            prévues sur la plateforme, par exemple dans
                            l'espace utilisateur ou par mail après
                            la confirmation du paiement.
                        </p>

                        <p>
                            Si l'accès au contenu n'est pas activé après
                            un paiement confirmé, l'acheteur peut contacter
                            le service client en indiquant les informations
                            permettant de retrouver la transaction.
                        </p>

                        <p>
                            L'accès peut nécessiter une connexion Internet,
                            un appareil compatible et un compte utilisateur
                            actif, selon les fonctionnalités proposées.
                        </p>

                        <p>
                            Les conditions d'accès hors ligne, de
                            téléchargement ou de lecture sur plusieurs
                            appareils sont celles indiquées pour le produit
                            et les fonctionnalités effectivement disponibles.
                        </p>
                    </section>

                    {{-- 09 --}}
                    <section class="kama-legal-section" id="retractation">
                        <span class="kama-legal-section-number">09</span>

                        <h2>Droit de rétractation</h2>

                        <p>
                            Les ebooks et audiobooks proposés sur KaMa sont des contenus numériques fournis sans support matériel. 
                            Lorsque le lecteur demande expressément que la fourniture du contenu commence immédiatement après la confirmation du paiement, et reconnaît qu’il perd son droit de rétractation, ce droit ne peut plus être exercé dès lors que les conditions légales applicables sont réunies.
                            Cette disposition ne porte pas atteinte aux droits dont le lecteur peut bénéficier en cas de contenu numérique non conforme, défectueux ou inaccessible, ni aux autres droits impératifs prévus par la législation applicable.
                        </p>
                    </section>

                    {{-- 10 --}}
                    <section class="kama-legal-section" id="remboursement">
                        <span class="kama-legal-section-number">10</span>

                        <h2>Remboursements</h2>

                        <p>
                            Toute demande de remboursement est examinée
                            en tenant compte de la nature de la commande,
                            du statut du paiement, de l'accès au contenu
                            et des droits prévus par la législation
                            applicable.
                        </p>

                        <h3>10.1. Paiement non abouti</h3>

                        <p>
                            Lorsqu'un paiement a échoué et que la commande
                            n'a pas été confirmée, aucun achat ne doit
                            être considéré comme finalisé sur la seule
                            base de la tentative de paiement.
                        </p>

                        <p>
                            Si un débit apparaît malgré l'échec de la
                            commande, l'acheteur peut contacter KaMa
                            pour faire vérifier la transaction.
                        </p>

                        <h3>10.2. Contenu inaccessible</h3>

                        <p>
                            Lorsqu'un achat a été payé mais que le contenu
                            n'est pas accessible ou ne peut pas être
                            utilisé conformément aux caractéristiques
                            annoncées, l'acheteur peut demander une
                            assistance ou un examen de sa demande.
                        </p>

                        <p>
                            Selon la situation, une correction technique,
                            une nouvelle mise à disposition, une réduction
                            de prix ou un remboursement peut être envisagé
                            conformément aux droits applicables.
                        </p>

                        <h3>10.3. Modalités de remboursement</h3>

                        <p>
                            Lorsqu'un remboursement est accepté ou
                            légalement requis, il est effectué selon
                            les modalités applicables à la transaction.
                            Le délai effectif peut dépendre du prestataire
                            de paiement, de la banque ou de l'opérateur
                            concerné.
                        </p>

                        <p>
                            Les présentes CGV ne limitent pas les droits
                            légaux de l'acheteur en matière de rétractation,
                            de conformité ou de remboursement.
                        </p>
                    </section>

                    {{-- 11 --}}
                    <section class="kama-legal-section" id="conformite">
                        <span class="kama-legal-section-number">11</span>

                        <h2>Conformité des contenus numériques</h2>

                        <p>
                            Les contenus numériques doivent être fournis
                            conformément aux exigences légales applicables
                            et aux caractéristiques annoncées lors de
                            l'achat.
                        </p>

                        <p>
                            Lorsque la législation applicable prévoit
                            une garantie légale de conformité des contenus
                            et services numériques, l'acheteur bénéficie
                            des droits correspondants.
                        </p>

                        <p>
                            En cas de difficulté liée à l'accès, à la
                            lecture ou à l'écoute d'un contenu, l'acheteur
                            est invité à contacter KaMa afin de permettre
                            l'examen du problème.
                        </p>

                        <p>
                            L'acheteur doit, dans la mesure du possible,
                            préciser le produit concerné, l'appareil utilisé,
                            le problème rencontré et les éventuels messages
                            d'erreur.
                        </p>

                        <p>
                            Ces informations facilitent le diagnostic
                            technique sans limiter les droits dont
                            bénéficie l'acheteur en vertu de la loi.
                        </p>
                    </section>

                    {{-- 12 --}}
                    <section class="kama-legal-section" id="responsabilite">
                        <span class="kama-legal-section-number">12</span>

                        <h2>Responsabilité</h2>

                        <p>
                            KaMa met en œuvre des moyens raisonnables
                            pour assurer le bon fonctionnement de la
                            plateforme et l'accès aux contenus achetés.
                        </p>

                        <p>
                            Des interruptions temporaires peuvent survenir
                            en raison d'opérations de maintenance,
                            d'incidents techniques, de problèmes de réseau
                            ou de défaillances affectant des services
                            tiers.
                        </p>

                        <p>
                            L'acheteur est responsable de disposer d'un
                            appareil compatible et, lorsque cela est
                            nécessaire, d'une connexion Internet adaptée.
                        </p>

                        <p>
                            Aucune disposition de ces CGV ne vise à
                            exclure ou à limiter une responsabilité qui
                            ne peut légalement être exclue ou limitée,
                            ni les garanties légales dont bénéficie
                            l'acheteur.
                        </p>
                    </section>

                    {{-- 13 --}}
                    <section class="kama-legal-section" id="reclamations">
                        <span class="kama-legal-section-number">13</span>

                        <h2>Réclamations</h2>

                        <p>
                            Pour toute question relative à une commande,
                            un paiement, un accès au contenu ou un
                            remboursement, l'acheteur peut contacter
                            KaMa à l'adresse suivante :
                        </p>

                        <p>
                            <a href="mailto:contact@kama-online.com">
                                <strong>contact@kama-online.com</strong>
                            </a>
                        </p>

                        <p>
                            Afin de faciliter le traitement de la demande,
                            il est recommandé de préciser :
                        </p>

                        <ul>
                            <li>l'adresse e-mail associée au compte ;</li>
                            <li>le titre du livre concerné ;</li>
                            <li>la date approximative de la commande ;</li>
                            <li>la référence de transaction, si elle est disponible ;</li>
                            <li>la nature du problème rencontré.</li>
                        </ul>

                        <p>
                            Les informations de paiement sensibles, notamment
                            les mots de passe, codes d'authentification
                            et numéros complets de carte bancaire, ne
                            doivent pas être envoyées par e-mail.
                        </p>

                        <p>
                            Lorsque des règles particulières de règlement
                            amiable ou de médiation s'appliquent, les
                            informations correspondantes doivent être
                            communiquées à l'acheteur conformément à
                            la législation applicable.
                        </p>
                    </section>

                    {{-- 14 --}}
                    <section class="kama-legal-section" id="donnees">
                        <span class="kama-legal-section-number">14</span>

                        <h2>Données personnelles</h2>

                        <p>
                            Les données nécessaires à la gestion des
                            commandes, à la confirmation des paiements,
                            à l'accès aux contenus et au traitement des
                            réclamations peuvent être traitées par KaMa
                            conformément à sa politique de confidentialité.
                        </p>

                        <p>
                            Des prestataires de paiement peuvent également
                            traiter certaines données dans le cadre de
                            leurs services, selon leurs conditions
                            applicables et les modalités d'intégration
                            utilisées.
                        </p>

                        <p>
                            Pour connaître les finalités du traitement,
                            les destinataires, les durées de conservation
                            et les droits des personnes concernées,
                            consultez notre
                            <a href="{{ route('legal.privacy') }}">
                                politique de confidentialité
                            </a>.
                        </p>
                    </section>

                    {{-- 15 --}}
                    <section class="kama-legal-section" id="droit">
                        <span class="kama-legal-section-number">15</span>

                        <h2>Droit applicable et règlement des litiges</h2>

                        <p>
                            Le droit applicable aux présentes CGV dépend
                            notamment de l'identité du vendeur contractuel,
                            de son établissement et des règles de conflit
                            de lois applicables.
                        </p>

                        <p>
                            Lorsqu'un acheteur bénéficie de protections
                            impératives prévues par la législation de
                            son pays de résidence, les présentes CGV
                            ne visent pas à le priver de ces protections.
                        </p>

                        <p>
                            En cas de différend, les parties sont invitées
                            à rechercher une solution amiable avant
                            d'engager, si nécessaire, les procédures
                            prévues par la législation applicable.
                        </p>

                        <p>
                            Les éventuelles informations relatives à
                            la médiation de la consommation, aux autorités
                            compétentes et aux voies de recours doivent
                            être adaptées au vendeur contractuel et
                            au pays de l'acheteur.
                        </p>
                    </section>

                    {{-- 16 --}}
                    <section class="kama-legal-section" id="modifications">
                        <span class="kama-legal-section-number">16</span>

                        <h2>Modification des conditions</h2>

                        <p>
                            KaMa peut mettre à jour les présentes CGV
                            afin de refléter les évolutions de ses
                            services, de ses modalités de vente ou
                            de la réglementation applicable.
                        </p>

                        <p>
                            La version applicable à une commande est
                            celle portée à la connaissance de l'acheteur
                            au moment de la conclusion du contrat,
                            sous réserve des dispositions légales
                            applicables.
                        </p>

                        <p>
                            Toute modification ultérieure est publiée
                            sur cette page avec une date de mise à jour.
                        </p>
                    </section>

                    {{-- 17 --}}
                    <section class="kama-legal-section" id="contact">
                        <span class="kama-legal-section-number">17</span>

                        <h2>Contact</h2>

                        <p>
                            Pour toute question concernant les présentes
                            CGV, une commande ou un achat effectué sur
                            KaMa, vous pouvez nous contacter à l'adresse
                            suivante :
                        </p>

                        <p>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:contact@kama-online.com">
                                <strong>contact@kama-online.com</strong>
                            </a>
                        </p>
                    </section>

                    {{-- Note finale --}}
                    <div class="kama-legal-footer-note">
                        <i class="bi bi-info-circle"></i>

                        <p>
                            Les présentes conditions constituent une base
                            de rédaction. Elles doivent être finalisées
                            en fonction du vendeur contractuel, des
                            moyens de paiement, du parcours de commande,
                            des règles fiscales et des droits applicables
                            aux consommateurs dans les pays desservis
                            par KaMa.
                        </p>
                    </div>

                </main>
            </div>
        </div>
    </section>
</div>
@endsection
