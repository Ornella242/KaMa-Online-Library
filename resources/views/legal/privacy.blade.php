@extends('layouts.app')

@section('title', 'Politique de confidentialité — KaMa')

@section('content')
<div class="kama-legal-page">

    {{-- En-tête --}}
    <div class="kama-legal-hero">
        <div class="container">
            <span class="kama-legal-eyebrow">CONFIDENTIALITÉ ET DONNÉES PERSONNELLES</span>

            <h1>Politique de confidentialité</h1>

            <p>
                Chez KaMa, nous accordons une importance particulière à la protection
                de vos données personnelles. Cette politique explique comment nous
                les collectons, utilisons, conservons et protégeons lorsque vous
                utilisez notre plateforme.
            </p>

            <div class="kama-legal-updated">
                Dernière mise à jour : 11 octobre 2026
            </div>
        </div>
    </div>

    {{-- Contenu --}}
    <div class="kama-legal-section-wrapper">
        <div class="container">
            <div class="kama-legal-layout">

                {{-- Sommaire --}}
                <aside class="kama-legal-toc">

                    <h3>
                        <i class="bi bi-list-ul"></i>
                        Dans cette page
                    </h3>

                    <nav aria-label="Sommaire de la politique de confidentialité">

                        <a href="#responsable">
                            <i class="bi bi-chevron-right"></i>
                            Responsable du traitement
                        </a>

                        <a href="#donnees">
                            <i class="bi bi-chevron-right"></i>
                            Données personnelles collectées
                        </a>

                        <a href="#collecte">
                            <i class="bi bi-chevron-right"></i>
                            Collecte des données
                        </a>

                        <a href="#finalites">
                            <i class="bi bi-chevron-right"></i>
                            Utilisation des données
                        </a>

                        <a href="#bases-legales">
                            <i class="bi bi-chevron-right"></i>
                            Bases légales du traitement
                        </a>

                        <a href="#paiements">
                            <i class="bi bi-chevron-right"></i>
                            Paiements et revenus des auteurs
                        </a>

                        <a href="#elevenlabs">
                            <i class="bi bi-chevron-right"></i>
                            Génération des audiobooks
                        </a>

                        <a href="#partage">
                            <i class="bi bi-chevron-right"></i>
                            Partage des données
                        </a>

                        <a href="#transferts">
                            <i class="bi bi-chevron-right"></i>
                            Transferts internationaux
                        </a>

                        <a href="#conservation">
                            <i class="bi bi-chevron-right"></i>
                            Conservation des données
                        </a>

                        <a href="#securite">
                            <i class="bi bi-chevron-right"></i>
                            Sécurité des données
                        </a>

                        <a href="#cookies">
                            <i class="bi bi-chevron-right"></i>
                            Cookies et traceurs
                        </a>

                        <a href="#droits">
                            <i class="bi bi-chevron-right"></i>
                            Vos droits
                        </a>

                        <a href="#mineurs">
                            <i class="bi bi-chevron-right"></i>
                            Protection des mineurs
                        </a>

                        <a href="#liens">
                            <i class="bi bi-chevron-right"></i>
                            Liens vers des sites tiers
                        </a>

                        <a href="#modifications">
                            <i class="bi bi-chevron-right"></i>
                            Modification de la politique
                        </a>

                        <a href="#contact">
                            <i class="bi bi-chevron-right"></i>
                            Contact
                        </a>

                    </nav>

                </aside>

                {{-- Texte juridique --}}
                <main class="kama-legal-content">

                    <div class="kama-legal-introduction">
                        <p>
                            La présente politique s'applique aux utilisateurs de la
                            plateforme KaMa, notamment aux lecteurs, auteurs,
                            contributeurs et visiteurs.
                        </p>

                        <p>
                            Elle doit être lue avec nos
                            <a href="{{ route('legal.terms') }}">Conditions générales d'utilisation</a>,
                            nos
                            <a href="{{ route('legal.sales') }}">Conditions générales de vente</a>
                            et notre
                            <a href="{{ route('legal.cookies') }}">Politique relative aux cookies</a>.
                        </p>
                    </div>

                    {{-- 1 --}}
                    <section id="responsable" class="kama-legal-section">
                        <span class="kama-legal-section-number">01</span>
                        <h2>Responsable du traitement</h2>

                        <p>
                            Le responsable du traitement est l'entité qui détermine
                            les finalités et les moyens du traitement des données
                            personnelles dans le cadre de l'exploitation de KaMa.
                        </p>

                        <ul>
                            <li><strong>Nom de la plateforme :</strong> KaMa Afrika Online Library</li>
                            <li>
                                <strong>Contact relatif aux données personnelles :</strong>
                                <a href="mailto:contact@kama-afrika.com">contact@kama-afrika.com</a>.
                            </li>
                        </ul>
                    </section>

                    {{-- 2 --}}
                    <section id="donnees" class="kama-legal-section">
                        <span class="kama-legal-section-number">02</span>
                        <h2>Données personnelles collectées</h2>

                        <p>
                            Selon les fonctionnalités utilisées, KaMa peut traiter
                            les catégories de données suivantes :
                        </p>

                        <h3>2.1. Données de compte</h3>
                        <ul>
                            <li>Nom et prénom, lorsque ces informations sont demandées.</li>
                            <li>Adresse e-mail.</li>
                            <li>Mot de passe sous forme protégée par un mécanisme de hachage.</li>
                            <li>Rôle sur la plateforme : lecteur, auteur.</li>
                            <li>Préférences et paramètres du compte.</li>
                        </ul>

                        <h3>2.2. Données des auteurs</h3>
                        <ul>
                            <li>Nom public.</li>
                            <li>Biographie, photographie et informations de profil fournies.</li>
                            <li>Livres soumis, descriptions, catégories et métadonnées.</li>
                            <li>Historique de publication, de modération et de mise à jour des ouvrages.</li>
                            <li>Informations nécessaires à la gestion des revenus et aux reversements.</li>
                        </ul>

                        <h3>2.3. Données d'achat et de paiement</h3>
                        <ul>
                            <li>Historique des commandes et des achats.</li>
                            <li>Montants, devises, statuts et références des transactions.</li>
                            <li>Informations nécessaires au remboursement et à la prévention de la fraude.</li>
                            <li>
                                Informations de paiement ou de versement nécessaires au traitement
                                des opérations, selon le prestataire utilisé.
                            </li>
                        </ul>

                        <p>
                            Les coordonnées bancaires complètes 
                            ne sont pas accessibles à KaMa. Leur collecte
                            ne se fait qu'au moment du paiement(demande de retrait des fonds par les auteurs).
                        </p>

                        <h3>2.4. Données techniques</h3>
                        <ul>
                            <li>Type de navigateur, système d'exploitation et informations sur l'appareil.</li>
                            <li>Dates de connexion, événements de sécurité et erreurs techniques.</li>
                            <li>Données d'utilisation nécessaires au fonctionnement et à l'amélioration du service.</li>
                        </ul>

                        <h3>2.5. Communications</h3>
                        <p>
                            Nous pouvons conserver les messages que vous nous adressez,
                            les demandes d'assistance et les informations nécessaires au
                            suivi des réclamations ou des signalements.
                        </p>
                    </section>

                    {{-- 3 --}}
                    <section id="collecte" class="kama-legal-section">
                        <span class="kama-legal-section-number">03</span>
                        <h2>Comment collectons-nous vos données ?</h2>

                        <p>Les données peuvent être collectées :</p>

                        <ul>
                            <li>Lorsque vous créez ou modifiez un compte.</li>
                            <li>Lorsque vous achetez un livre ou utilisez une fonctionnalité de KaMa.</li>
                            <li>Lorsque vous soumettez un ouvrage ou gérez votre catalogue d'auteur.</li>
                            <li>Lorsqu'une opération de paiement ou de reversement est effectuée.</li>
                            <li>Lorsque vous contactez notre équipe.</li>
                            <li>Automatiquement, par l'intermédiaire, selon vos choix, des cookies ou traceurs.</li>
                        </ul>

                        <p>
                            Lorsque la loi l'exige, nous vous informons avant de collecter
                            des données supplémentaires et sollicitons votre consentement
                            lorsqu'il constitue la base légale appropriée.
                        </p>
                    </section>

                    {{-- 4 --}}
                    <section id="finalites" class="kama-legal-section">
                        <span class="kama-legal-section-number">04</span>
                        <h2>Pourquoi utilisons-nous vos données ?</h2>

                        <p>Les données sont utilisées, dans la mesure nécessaire, pour :</p>

                        <ul>
                            <li>Créer, administrer et sécuriser les comptes.</li>
                            <li>Permettre la consultation, l'achat et l'accès aux livres numériques.</li>
                            <li>Permettre aux auteurs de soumettre, gérer et suivre leurs publications.</li>
                            <li>Traiter les paiements, remboursements et reversements dus aux auteurs.</li>
                            <li>Gérer les demandes d'assistance et les réclamations.</li>
                            <li>Générer et distribuer des audiobooks lorsque cette fonctionnalité est autorisée.</li>
                            <li>Détecter les activités frauduleuses, les abus et les atteintes aux droits.</li>
                            <li>Respecter nos obligations légales, comptables et fiscales.</li>
                            <li>Mesurer la fiabilité du service et améliorer son fonctionnement.</li>
                            <li>Envoyer les communications nécessaires au service.</li>
                            <li>Envoyer des communications promotionnelles.</li>
                        </ul>

                        <p>
                            Les données ne sont pas utilisées à des fins
                            incompatibles avec celles annoncées, sauf si une nouvelle
                            base légale le permet et si les obligations d'information
                            applicables sont respectées.
                        </p>
                    </section>

                    {{-- 5 --}}
                    <section id="bases-legales" class="kama-legal-section">
                        <span class="kama-legal-section-number">05</span>
                        <h2>Bases légales du traitement</h2>

                        <p>
                            Lorsque le RGPD s'applique, chaque traitement repose sur
                            une base légale appropriée, notamment :
                        </p>

                        <ul>
                            <li>
                                <strong>Exécution d'un contrat :</strong>
                                création du compte, fourniture des livres achetés,
                                gestion des publications et exécution des opérations convenues.
                            </li>
                            <li>
                                <strong>Obligation légale :</strong>
                                lorsque la réglementation applicable impose à KaMa de conserver certaines informations ou 
                                de répondre à une demande légalement fondée.
                            </li>
                            <li>
                                <strong>Intérêt légitime :</strong>
                                sécurité de la plateforme, prévention des abus et défense
                                de droits en justice, sous réserve de la mise en balance
                                des intérêts et droits des personnes.
                            </li>
                            <li>
                                <strong>Consentement :</strong>
                                certains cookies ou traceurs, certaines communications
                                promotionnelles et les traitements pour lesquels le
                                consentement est requis.
                            </li>
                        </ul>

                        <p>
                            Lorsque le traitement repose sur le consentement, celui-ci
                            peut être retiré à tout moment pour l'avenir, sans remettre
                            en cause la licéité des traitements effectués avant ce retrait.
                        </p>
                    </section>

                    {{-- 6 --}}
                    <section id="paiements" class="kama-legal-section">
                        <span class="kama-legal-section-number">06</span>
                        <h2>Paiements et revenus des auteurs</h2>

                        <p>
                            KaMa permet l'achat d'ouvrages et propose des
                            fonctionnalités de reversements aux auteurs. Les données
                            nécessaires à ces opérations sont utilisées pour enregistrer
                            les transactions, calculer les montants dus et assurer le suivi
                            des paiements.
                        </p>

                        <p>
                            Les transactions sont traitées par des prestataires
                            de paiement tiers. Ces prestataires peuvent traiter certaines
                            données en qualité de responsables de traitement indépendants
                            ou de sous-traitants, selon leurs services et leurs conditions.
                        </p>

                        <p>
                            Nous vous invitons à consulter les politiques de confidentialité
                            des prestataires concernés au moment du paiement ou de
                            l'enregistrement des informations nécessaires à un versement.
                        </p>

                        <p>
                            Les données requises pour satisfaire aux obligations comptables,
                            fiscales, de lutte contre la fraude ou de vérification des
                            paiements peuvent être conservées pendant les durées prévues
                            par la réglementation applicable.
                        </p>
                    </section>

                    {{-- 7 --}}
                    <section id="elevenlabs" class="kama-legal-section">
                        <span class="kama-legal-section-number">07</span>
                        <h2>Génération des audiobooks et services d'intelligence artificielle</h2>

                        <p>
                            KaMa propose aux auteurs la génération de livres audio
                            à partir de leurs ouvrages, notamment au moyen de technologies
                            de synthèse vocale fournies par des prestataires tiers.
                        </p>

                        <p>Lorsque cette fonctionnalité est utilisée :</p>

                        <ul>
                            <li>
                                Le texte de l'ouvrage et les informations techniques
                                nécessaires peuvent être transmis au prestataire concerné
                                afin de générer la narration audio.
                            </li>
                            <li>
                                Le texte et les fichiers audio générés peuvent être
                                traités, stockés ou hébergés dans les systèmes nécessaires
                                à la fourniture du service.
                            </li>
                            <li>
                                Les données techniques relatives à la génération peuvent
                                être utilisées pour suivre l'opération, résoudre les erreurs
                                et assurer le fonctionnement de la fonctionnalité.
                            </li>
                        </ul>

                        <p>
                            Lorsqu’un auteur demande et paie la génération d’un audiobook à partir de son ouvrage sur KaMa, il autorise l’utilisation du contenu concerné aux fins nécessaires à la génération de l’audio, sous réserve de détenir les droits requis sur cet ouvrage.
                        </p>

                        <p>
                            Pour réaliser cette opération, KaMa transmet au prestataire technique concerné les éléments du livre nécessaires à la génération de l’audio. Les données sont traitées conformément aux conditions applicables au service utilisé et aux engagements contractuels du prestataire.
                        </p>

                        <p>
                            Les modalités de traitement, la durée de conservation des données, les éventuels transferts internationaux et les conditions d’utilisation du contenu par le prestataire dépendent des dispositions applicables à ce service. KaMa veille à informer les utilisateurs des traitements pertinents et à encadrer le recours à ces prestataires.
                        </p>
                    </section>

                    {{-- 8 --}}
                    <section id="partage" class="kama-legal-section">
                        <span class="kama-legal-section-number">08</span>
                        <h2>Avec qui partageons-nous vos données ?</h2>

                        <p>
                            KaMa ne vend pas les données personnelles des utilisateurs.
                            Certaines données peuvent néanmoins être communiquées,
                            dans la mesure nécessaire, aux catégories de destinataires suivantes :
                        </p>

                        <ul>
                            <li>Hébergeurs et prestataires d'infrastructure technique.</li>
                            <li>Prestataires de paiement et de gestion des transactions.</li>
                            <li>Prestataires de génération audio et d'intelligence artificielle.</li>
                            <li>Prestataires de messagerie, d'assistance et de sécurité.</li>
                            <li>Prestataires d'analyse technique, sous réserve des règles applicables aux traceurs.</li>
                            <li>Conseils professionnels, autorités publiques ou juridictions lorsque la loi le permet ou l'exige.</li>
                        </ul>

                        <p>
                            Lorsque ces destinataires agissent comme sous-traitants,
                            leur accès aux données doit être encadré par des obligations
                            contractuelles appropriées en matière de confidentialité,
                            de sécurité et de protection des données.
                        </p>

                        <p>
                            Les données peuvent également être communiquées lorsque cela
                            est nécessaire à la prévention d'une fraude, à la protection
                            des droits des utilisateurs ou à la défense des intérêts légitimes
                            de KaMa, dans les limites prévues par la loi.
                        </p>
                    </section>

                    {{-- 9 --}}
                    <section id="transferts" class="kama-legal-section">
                        <span class="kama-legal-section-number">09</span>
                        <h2>Transferts internationaux de données</h2>

                        <p>
                            KaMa vise un public international. Certains prestataires
                            techniques peuvent traiter ou héberger des données en dehors
                            du pays dans lequel se trouve l'utilisateur, notamment lorsque
                            leurs infrastructures sont réparties sur plusieurs territoires.
                        </p>

                        <p>
                            Lorsque des données personnelles sont transférées vers un autre pays dans le cadre des services utilisés par KaMa, 
                            ces transferts sont encadrés conformément aux exigences légales applicables en matière de protection des données personnelles, 
                            notamment lorsque le RGPD (Règlement Général sur la Protection des Données) s'applique.
                        </p>

                        <p>
                            Les garanties effectivement applicables dépendent du
                            prestataire, de la localisation des données et des modalités
                            du service utilisé. Elles doivent être vérifiées avant la mise
                            en production des intégrations concernées.
                        </p>
                    </section>

                    {{-- 10 --}}
                    <section id="conservation" class="kama-legal-section">
                        <span class="kama-legal-section-number">10</span>
                        <h2>Durée de conservation des données</h2>

                        <p>
                            Les données personnelles sont conservées uniquement pendant
                            la durée nécessaire aux finalités poursuivies, sous réserve
                            des obligations légales et de la nécessité de préserver
                            certains éléments pour la défense de droits en justice.
                        </p>

                        <ul>
                            <li>
                                <strong>Données de compte :</strong>
                                pendant la durée d'activité du compte, puis selon les
                                nécessités de clôture, de gestion des litiges et les
                                obligations applicables.
                            </li>
                            <li>
                                <strong>Données de commande et de facturation :</strong>
                                pendant les durées imposées par les obligations comptables
                                et fiscales applicables.
                            </li>
                            <li>
                                <strong>Données d'assistance :</strong>
                                pendant la durée nécessaire au traitement de la demande
                                et au suivi d'un éventuel litige.
                            </li>
                            <li>
                                <strong>Journaux de sécurité :</strong>
                                pendant une durée proportionnée aux besoins de sécurité
                                et aux obligations applicables.
                            </li>
                            <li>
                                <strong>Cookies et traceurs :</strong>
                                selon leur finalité et les durées indiquées dans la
                                politique relative aux cookies.
                            </li>
                            <li>
                                <strong>Fichiers liés aux audiobooks :</strong>
                                selon les besoins du service, les droits applicables,
                                les paramètres de publication et les obligations contractuelles.
                            </li>
                        </ul>

                        <p>
                            Les durées précises devront être définies dans le registre
                            des traitements de KaMa et alignées sur la configuration
                            réelle de la plateforme et de ses prestataires.
                        </p>
                    </section>

                    {{-- 11 --}}
                    <section id="securite" class="kama-legal-section">
                        <span class="kama-legal-section-number">11</span>
                        <h2>Sécurité des données</h2>

                        <p>
                            KaMa met en œuvre des mesures techniques et organisationnelles
                            proportionnées aux risques afin de protéger les données
                            personnelles contre l'accès non autorisé, la perte,
                            l'altération, la divulgation ou la destruction.
                        </p>

                        <p>Ces mesures peuvent notamment inclure, selon les fonctionnalités déployées :</p>

                        <ul>
                            <li>La protection des comptes et l'authentification.</li>
                            <li>La gestion des autorisations d'accès.</li>
                            <li>La protection des échanges et des systèmes.</li>
                            <li>La sauvegarde et la surveillance des services.</li>
                            <li>La limitation des accès aux personnes qui en ont besoin.</li>
                            <li>La gestion des incidents de sécurité.</li>
                        </ul>

                        <p>
                            Aucun système informatique ne peut garantir une sécurité
                            absolue. En cas de violation de données personnelles,
                            KaMa appliquera les obligations de notification et
                            d'information prévues par la réglementation applicable.
                        </p>
                    </section>

                    {{-- 12 --}}
                    <section id="cookies" class="kama-legal-section">
                        <span class="kama-legal-section-number">12</span>
                        <h2>Cookies et traceurs</h2>

                        <p>
                            KaMa peut utiliser des cookies ou technologies similaires
                            pour assurer le fonctionnement du site, maintenir les sessions,
                            mémoriser certaines préférences et, si de tels outils sont
                            déployés, mesurer l'audience ou proposer des fonctionnalités
                            supplémentaires.
                        </p>

                        <p>
                            Les cookies strictement nécessaires au service peuvent être
                            utilisés conformément aux règles applicables. Pour les
                            cookies ou traceurs soumis au consentement, celui-ci doit
                            être recueilli avant leur dépôt ou leur lecture, lorsque la loi l'exige.
                        </p>

                        <p>
                            Vous pouvez consulter les finalités des cookies, gérer vos
                            préférences et retirer votre consentement à travers le
                            mécanisme de gestion des cookies de KaMa, lorsqu'il est disponible.
                        </p>

                        <p>
                            Pour plus d'informations, consultez notre
                            <a href="{{ route('legal.cookies') }}">Politique relative aux cookies</a>.
                        </p>
                    </section>

                    {{-- 13 --}}
                    <section id="droits" class="kama-legal-section">
                        <span class="kama-legal-section-number">13</span>
                        <h2>Vos droits sur vos données personnelles</h2>

                        <p>
                            Lorsque le RGPD s'applique, vous pouvez bénéficier,
                            selon les circonstances, des droits suivants :
                        </p>

                        <ul>
                            <li>
                                <strong>Droit d'accès :</strong>
                                obtenir la confirmation qu'un traitement est effectué
                                et accéder à vos données.
                            </li>
                            <li>
                                <strong>Droit de rectification :</strong>
                                faire corriger les données inexactes ou incomplètes.
                            </li>
                            <li>
                                <strong>Droit à l'effacement :</strong>
                                demander la suppression de données dans les conditions
                                prévues par la loi.
                            </li>
                            <li>
                                <strong>Droit à la limitation :</strong>
                                demander, dans certains cas, la limitation du traitement.
                            </li>
                            <li>
                                <strong>Droit d'opposition :</strong>
                                vous opposer à certains traitements fondés sur l'intérêt
                                légitime ou à la prospection directe.
                            </li>
                            <li>
                                <strong>Droit à la portabilité :</strong>
                                recevoir certaines données dans un format structuré,
                                lorsque les conditions légales sont remplies.
                            </li>
                            <li>
                                <strong>Droit de retirer votre consentement :</strong>
                                lorsque le traitement repose sur celui-ci.
                            </li>
                        </ul>

                        <p>
                            Pour exercer vos droits, contactez-nous à l'adresse
                            <a href="mailto:contact@kama-afrika.com">
                                contact@kama-afrika.com
                            </a>.
                        </p>

                        <p>
                            Nous pouvons demander les informations strictement nécessaires
                            pour vérifier votre identité avant de répondre à une demande.
                            Nous répondons dans les délais prévus par la réglementation
                            applicable.
                        </p>

                    </section>

                    {{-- 14 --}}
                    <section id="mineurs" class="kama-legal-section">
                        <span class="kama-legal-section-number">14</span>
                        <h2>Protection des mineurs</h2>

                        <p>
                            Les conditions d'accès à KaMa et les règles applicables
                            aux mineurs sont précisées dans les conditions d'utilisation
                            et, le cas échéant, dans les conditions de vente.
                        </p>

                        <p>
                            Lorsque des données personnelles de mineurs sont collectées,
                            les règles applicables à leur âge, au consentement et à
                            l'autorisation parentale doivent être respectées.
                        </p>

                        <p>
                            Si vous pensez qu'un mineur a fourni des données personnelles
                            en violation des règles applicables, contactez-nous afin que
                            nous puissions examiner la situation.
                        </p>
                    </section>

                    {{-- 15 --}}
                    <section id="liens" class="kama-legal-section">
                        <span class="kama-legal-section-number">15</span>
                        <h2>Liens vers des sites tiers</h2>

                        <p>
                            La plateforme peut contenir des liens vers des sites ou
                            services exploités par des tiers. KaMa ne contrôle pas
                            nécessairement leurs pratiques en matière de confidentialité.
                        </p>

                        <p>
                            Nous vous recommandons de consulter les politiques de
                            confidentialité des services concernés avant de leur
                            communiquer des données personnelles.
                        </p>
                    </section>

                    {{-- 16 --}}
                    <section id="modifications" class="kama-legal-section">
                        <span class="kama-legal-section-number">16</span>
                        <h2>Modifications de cette politique</h2>

                        <p>
                            La présente politique peut être mise à jour pour tenir
                            compte des évolutions de KaMa, de ses fonctionnalités,
                            de ses prestataires ou de la réglementation applicable.
                        </p>

                        <p>
                            La date de dernière mise à jour figure en haut de cette page.
                            Lorsque la loi l'exige, les utilisateurs concernés seront
                            informés des modifications importantes.
                        </p>
                    </section>

                    {{-- 17 --}}
                    <section id="contact" class="kama-legal-section">
                        <span class="kama-legal-section-number">17</span>
                        <h2>Nous contacter</h2>

                        <p>
                            Pour toute question relative à cette politique ou au
                            traitement de vos données personnelles, vous pouvez nous
                            contacter :
                        </p>

                        <ul>
                            <li><strong>Plateforme :</strong> KaMa.</li>
                            <li>
                                <strong>E-mail :</strong>
                                <a href="mailto:contact@kama-afrika.com">
                                    contact@kama-afrika.com
                                </a>
                            </li>
                        </ul>
                    </section>

                    <div class="kama-legal-footer-note">
                        <p>
                            Cette politique décrit le cadre de protection des données
                            envisagé pour KaMa. Elle doit être finalisée à partir des
                            traitements réellement mis en œuvre, des prestataires utilisés,
                            des durées de conservation définies et de la situation juridique
                            de l'entreprise exploitante.
                        </p>
                    </div>

                </main>
            </div>
        </div>
    </div>
</div>
@endsection