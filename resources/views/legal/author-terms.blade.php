
@extends('layouts.app')

@section('title', 'Conditions de publication des auteurs — KaMa')

@section('content')
<div class="kama-legal-page">

    {{-- HERO --}}
    <section class="kama-legal-hero">
        <div class="container">
            <span class="kama-legal-eyebrow">ESPACE AUTEURS</span>

            <h1>Conditions de publication des auteurs</h1>

            <p>
                Les règles applicables à la soumission, à la publication
                et à la commercialisation des ouvrages sur KaMa.
            </p>

            <p class="kama-legal-updated">
                Dernière mise à jour : 11 octobre 2026
            </p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="kama-legal-section-wrapper">
        <div class="container">
            <div class="kama-legal-layout">

                {{-- Sommaire --}}
                <aside class="kama-legal-toc">
                    <h3>
                        <i class="bi bi-list-ul"></i>
                        Dans cette page
                    </h3>

                    <nav aria-label="Sommaire des conditions de publication des auteurs">
                        <a href="#objet">
                            <i class="bi bi-chevron-right"></i>
                            Objet et champ d'application
                        </a>

                        <a href="#inscription">
                            <i class="bi bi-chevron-right"></i>
                            Inscription des auteurs
                        </a>

                        <a href="#droits">
                            <i class="bi bi-chevron-right"></i>
                            Droits d'auteur
                        </a>

                        <a href="#soumission">
                            <i class="bi bi-chevron-right"></i>
                            Soumission des ouvrages
                        </a>

                        <a href="#moderation">
                            <i class="bi bi-chevron-right"></i>
                            Examen et publication
                        </a>

                        <a href="#contenus">
                            <i class="bi bi-chevron-right"></i>
                            Contenus autorisés et interdits
                        </a>

                        <a href="#licence">
                            <i class="bi bi-chevron-right"></i>
                            Autorisation accordée à KaMa
                        </a>

                        <a href="#commercialisation">
                            <i class="bi bi-chevron-right"></i>
                            Prix et commercialisation
                        </a>

                        <a href="#remuneration">
                            <i class="bi bi-chevron-right"></i>
                            Rémunération des auteurs
                        </a>

                        <a href="#versements">
                            <i class="bi bi-chevron-right"></i>
                            Retraits et versements
                        </a>

                        <a href="#audiobooks">
                            <i class="bi bi-chevron-right"></i>
                            Génération d'audiobooks
                        </a>

                        <a href="#reclamations">
                            <i class="bi bi-chevron-right"></i>
                            Réclamations et atteintes aux droits
                        </a>

                        <a href="#suspension">
                            <i class="bi bi-chevron-right"></i>
                            Suspension et résiliation
                        </a>

                        <a href="#responsabilite">
                            <i class="bi bi-chevron-right"></i>
                            Responsabilités
                        </a>

                        <a href="#donnees">
                            <i class="bi bi-chevron-right"></i>
                            Données personnelles
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


                {{-- CONTENU PRINCIPAL --}}
                <main class="kama-legal-content">

                    <div class="kama-legal-introduction">
                        <p>
                            KaMa est une plateforme numérique qui permet aux
                            auteurs de soumettre et de proposer leurs ouvrages
                            à la lecture ou à la vente, selon les fonctionnalités
                            disponibles.
                        </p>

                        <p>
                            Les présentes conditions définissent les droits
                            et obligations des auteurs qui utilisent KaMa
                            pour publier, commercialiser ou faire adapter
                            leurs ouvrages en audiobooks.
                        </p>

                        <p>
                            Elles complètent les
                            <a href="{{ route('legal.terms') }}">
                                Conditions générales d’utilisation
                            </a>,
                            les
                            <a href="{{ route('legal.sales') }}">
                                Conditions générales de vente
                            </a>
                            et la
                            <a href="{{ route('legal.privacy') }}">
                                Politique de confidentialité
                            </a>
                            de KaMa.
                        </p>
                    </div>

                    {{-- 01 --}}
                    <section class="kama-legal-section" id="objet">
                        <span class="kama-legal-section-number">01</span>
                        <h2>Objet et champ d’application</h2>

                        <p>
                            Les présentes conditions encadrent l’utilisation
                            des fonctionnalités de publication par les auteurs
                            sur KaMa, notamment la soumission des ouvrages,
                            leur examen, leur publication, leur commercialisation
                            et, lorsque cette fonctionnalité est proposée,
                            la génération d’audiobooks.
                        </p>

                        <p>
                            Elles s’appliquent aux auteurs qui utilisent
                            leur compte pour proposer des contenus sur la
                            plateforme.
                        </p>

                        <p>
                            Des conditions particulières peuvent s’appliquer
                            à certains services, notamment aux prestations
                            payantes de publication ou de génération audio.
                            Elles doivent être communiquées à l’auteur avant
                            qu’il ne s’engage.
                        </p>
                    </section>

                    {{-- 02 --}}
                    <section class="kama-legal-section" id="inscription">
                        <span class="kama-legal-section-number">02</span>
                        <h2>Inscription et compte auteur</h2>

                        <p>
                            L’auteur doit fournir des informations exactes,
                            complètes et à jour lors de son inscription et
                            pendant l’utilisation de KaMa.
                        </p>

                        <p>Il s’engage notamment à :</p>

                        <ul>
                            <li>protéger ses identifiants de connexion ;</li>
                            <li>ne pas usurper l’identité d’un tiers ;</li>
                            <li>maintenir à jour les informations nécessaires à la gestion de son compte ;</li>
                            <li>signaler toute utilisation non autorisée de son compte ;</li>
                            <li>respecter les présentes conditions et les lois applicables.</li>
                        </ul>

                        <p>
                            KaMa peut demander des informations complémentaires
                            lorsque cela est nécessaire à la vérification
                            d’un compte, à la prévention de la fraude ou
                            au traitement des paiements et des versements.
                        </p>
                    </section>

                    {{-- 03 --}}
                    <section class="kama-legal-section" id="droits">
                        <span class="kama-legal-section-number">03</span>
                        <h2>Droits d’auteur et propriété intellectuelle</h2>

                        <p>
                            L’auteur conserve les droits de propriété
                            intellectuelle dont il est titulaire sur ses
                            ouvrages. La publication sur KaMa ne constitue
                            pas, à elle seule, une cession générale de ces
                            droits à la plateforme.
                        </p>

                        <p>L’auteur garantit qu’il :</p>

                        <ul>
                            <li>détient les droits nécessaires sur l’ouvrage ou dispose des autorisations requises ;</li>
                            <li>respecte les droits d’auteur, les marques, les droits à l’image et les autres droits applicables.</li>
                        </ul>

                        <p>
                            Il est interdit de soumettre un ouvrage piraté,
                            plagié, reproduit sans autorisation ou présenté
                            frauduleusement comme une création originale.
                        </p>

                        <p>
                            Lorsqu’un ouvrage est coécrit ou appartient à
                            plusieurs titulaires de droits, l’auteur doit
                            disposer des autorisations nécessaires à sa
                            publication et à son exploitation sur KaMa.
                        </p>
                    </section>

                    {{-- 04 --}}
                    <section class="kama-legal-section" id="soumission">
                        <span class="kama-legal-section-number">04</span>
                        <h2>Soumission des ouvrages</h2>

                        <p>
                            Les auteurs doivent soumettre leurs ouvrages
                            dans les formats acceptés (pdf pour les ebooks et mp3 pour les audios) par KaMa et fournir
                            les informations demandées lors de la publication.
                        </p>

                        <p>Selon les exigences applicables, une soumission peut nécessiter :</p>

                        <ul>
                            <li>un titre et une description fidèles au contenu ;</li>
                            <li>le nom de l’auteur ou son pseudonyme (par défaut le nom de l'utilisateur);</li>
                            <li>un fichier conforme aux exigences techniques ;</li>
                            <li>une couverture dont l’utilisation est autorisée ;</li>
                            <li>les informations de catégorie, de prix et de classement requises.</li>
                        </ul>

                        <p>
                            L’auteur est responsable de l’exactitude des
                            informations communiquées et du contenu du fichier
                            transmis.
                        </p>

                        <p>
                            Le paiement éventuel de frais de dépôt ne
                            garantit pas l’acceptation ou la mise en ligne
                            de l’ouvrage. Les conditions tarifaires
                            sont présentées avant le paiement.
                        </p>
                    </section>

                    {{-- 05 --}}
                    <section class="kama-legal-section" id="moderation">
                        <span class="kama-legal-section-number">05</span>
                        <h2>Examen, validation et publication</h2>

                        <p>
                            Les ouvrages soumis peuvent être examinés avant
                            leur publication. Une soumission peut notamment
                            être en attente, en cours d’examen, acceptée,
                            renvoyée pour correction ou refusée.
                        </p>

                        <p>
                            KaMa peut demander des corrections, des
                            informations complémentaires ou des justificatifs
                            relatifs aux droits sur le contenu.
                        </p>

                        <p>
                            La validation d’un ouvrage ne constitue pas une
                            garantie de son exactitude, de son originalité
                            ou de sa conformité juridique. L’auteur reste
                            responsable du contenu qu’il publie.
                        </p>

                        <p>
                            Les délais de traitement peuvent varier selon
                            le volume des soumissions et les vérifications
                            nécessaires. Sauf engagement particulier, aucun
                            délai précis de publication n’est garanti.
                        </p>
                    </section>

                    {{-- 06 --}}
                    <section class="kama-legal-section" id="contenus">
                        <span class="kama-legal-section-number">06</span>
                        <h2>Contenus autorisés et interdits</h2>

                        <p>
                            Les ouvrages publiés sur KaMa doivent respecter
                            les lois applicables et les règles de la plateforme.
                        </p>

                        <p>Sont notamment interdits :</p>

                        <ul>
                            <li>les contenus portant atteinte aux droits de propriété intellectuelle de tiers ;</li>
                            <li>les ouvrages obtenus ou diffusés de manière illicite ;</li>
                            <li>les contenus frauduleux ou accompagnés de fausses informations ;</li>
                            <li>les contenus constituant une escroquerie, une menace ou une incitation illégale ;</li>
                            <li>les contenus portant illicitement atteinte à la vie privée ou aux droits d’autrui ;</li>
                            <li>les contenus dont la publication est interdite par la législation applicable.</li>
                        </ul>

                        <p>
                            Lorsqu’un contenu semble enfreindre ces règles,
                            KaMa peut prendre les mesures appropriées,
                            conformément à la loi et aux procédures applicables.
                        </p>
                    </section>

                    {{-- 07 --}}
                    <section class="kama-legal-section" id="licence">
                        <span class="kama-legal-section-number">07</span>
                        <h2>Autorisation accordée à KaMa</h2>

                        <p>
                            Afin de fournir ses services, KaMa doit pouvoir
                            héberger, stocker, reproduire techniquement,
                            afficher et mettre à disposition les ouvrages
                            soumis, dans la mesure nécessaire à leur examen,
                            à leur présentation et à leur commercialisation
                            sur la plateforme.
                        </p>

                        <p>
                            L’auteur accorde à KaMa une autorisation limitée
                            aux opérations nécessaires à la fourniture de
                            ces services, pendant la période où l’ouvrage
                            est proposé sur la plateforme et pour les
                            opérations techniques qui doivent légitimement
                            se poursuivre après son retrait.
                        </p>

                        <p>
                            Cette autorisation ne transfère pas à KaMa la
                            propriété intellectuelle de l’ouvrage et ne
                            l’autorise pas à revendiquer la paternité de
                            celui-ci.
                        </p>

                        <p>
                            Toute utilisation promotionnelle d’un ouvrage,
                            de sa couverture ou d’extraits doit respecter
                            les droits détenus par l’auteur et les
                            autorisations applicables.
                        </p>
                    </section>

                    {{-- 08 --}}
                    <section class="kama-legal-section" id="commercialisation">
                        <span class="kama-legal-section-number">08</span>
                        <h2>Prix et commercialisation</h2>

                        <p>
                            L’auteur propose un prix pour son ouvrage,
                            dans les limites et selon les modalités prévues
                            sur KaMa.
                        </p>

                        <p>
                            L’auteur doit fournir des informations exactes
                            sur le livre, son format et son contenu. Il ne
                            doit pas induire les lecteurs en erreur sur
                            les caractéristiques de l’ouvrage.
                        </p>

                        <p>
                            Les prix affichés aux lecteurs, les taxes
                            applicables et les conditions de paiement
                            sont déterminés conformément aux informations
                            présentées lors de la transaction et aux
                            Conditions générales de vente.
                        </p>

                        <p>
                            Toute modification du prix s’applique aux
                            transactions futures, sans modifier
                            rétroactivement les conditions d’une vente
                            déjà réalisée.
                        </p>
                    </section>

                    {{-- 09 --}}
                    <section class="kama-legal-section" id="remuneration">
                        <span class="kama-legal-section-number">09</span>
                        <h2>Rémunération des auteurs</h2>

                        <p>
                            Pour les ventes éligibles réalisées sur KaMa, la rémunération
                            est répartie à hauteur de 80 % pour l’auteur et de 20 % pour
                            KaMa, après déduction des frais prélevés par les agrégateurs,
                            les prestataires de paiement et les autres intermédiaires
                            impliqués dans le traitement de la transaction.
                        </p>

                        <p>
                            La répartition est calculée sur le montant net restant après
                            déduction de ces frais. Ainsi, l’auteur reçoit 80 % de ce
                            montant net et KaMa conserve les 20 % restants.
                        </p>

                        <p>
                            Les revenus attribués à l’auteur correspondent aux ventes
                            éligibles effectivement réalisées, sous réserve des ajustements
                            applicables, notamment en cas de remboursement, d’annulation
                            ou de contestation de paiement.
                        </p>

                        <p>
                            Les frais de publication et ceux liés à d’autres services,
                            notamment la génération d’audiobooks, sont distincts des
                            revenus issus de la vente d’un livre, sauf indication contraire
                            communiquée avant la transaction.
                        </p>

                        <p>
                            Chaque partie assume les obligations fiscales qui lui
                            incombent légalement. Le traitement des taxes applicables
                            dans le calcul des revenus est effectué conformément aux
                            règles en vigueur et aux modalités financières communiquées
                            sur KaMa.
                        </p>
                    </section>

                    {{-- 10 --}}
                    <section class="kama-legal-section" id="versements">
                        <span class="kama-legal-section-number">10</span>
                        <h2>Retraits et versements des revenus</h2>

                        <p>
                            Les auteurs peuvent demander le versement de leurs revenus
                            disponibles et éligibles directement depuis KaMa, selon la
                            procédure prévue sur la plateforme.
                        </p>

                        <p>
                            Un seuil minimum de retrait est applicable. Son montant est
                            affiché sur KaMa et doit être atteint pour qu’une demande
                            de retrait puisse être effectuée. Les autres conditions
                            applicables sont également précisées sur la plateforme.
                        </p>

                        <p>
                            L’auteur doit fournir des informations de paiement exactes,
                            à jour et correspondant au bénéficiaire autorisé. Des
                            vérifications d’identité ou de coordonnées de paiement
                            peuvent être nécessaires avant l’approbation d’un versement.
                        </p>

                        <p>
                            Des frais de retrait peuvent être appliqués. Leur montant
                            ou leur mode de calcul est communiqué à l’auteur sur KaMa
                            avant la validation de sa demande de retrait.
                        </p>

                        <p>
                            Les délais de versement dépendent notamment des vérifications
                            nécessaires, des modalités de traitement des retraits et
                            des délais des prestataires financiers concernés.
                        </p>

                        <p>
                            La fermeture d’un compte n’entraîne pas automatiquement
                            l’annulation des revenus déjà acquis. Leur versement peut
                            toutefois être différé lorsqu’un litige, une vérification
                            nécessaire ou une obligation légale le justifie.
                        </p>
                    </section>

                    {{-- 11 --}}
                    <section class="kama-legal-section" id="audiobooks">
                        <span class="kama-legal-section-number">11</span>
                        <h2>Génération d’audiobooks</h2>

                        <p>
                            KaMa propose un service de génération
                            d’audiobooks à partir des ouvrages des auteurs,
                            selon les fonctionnalités disponibles et les
                            conditions tarifaires communiquées avant
                            la commande.
                        </p>

                        <p>
                            En demandant la génération d’un audiobook,
                            l’auteur autorise KaMa et ses prestataires
                            techniques à traiter les éléments nécessaires
                            du contenu soumis pour exécuter la génération
                            demandée.
                        </p>

                        <p>
                            Ce traitement peut comprendre l’analyse du
                            texte, sa segmentation, la synthèse vocale,
                            l’assemblage des fichiers audio et leur
                            stockage technique pour permettre la remise
                            du résultat.
                        </p>

                        <p>
                            La qualité du résultat dépend notamment du
                            texte source, de la langue, de la ponctuation,
                            de la voix sélectionnée et des capacités du
                            service de synthèse vocale utilisé. KaMa ne
                            garantit pas une reproduction parfaite de
                            chaque nuance ou intention littéraire.
                        </p>

                        <p>
                            L’auteur doit vérifier le résultat livré et
                            signaler les problèmes selon la procédure
                            applicable. Les modalités de correction,
                            de nouvelle génération et de remboursement
                            sont déterminées par les conditions du service
                            et la législation applicable.
                        </p>

                        <p>
                            La génération d’un audiobook ne transfère pas
                            à KaMa la propriété intellectuelle de l’ouvrage
                            source. L’auteur demeure responsable des droits
                            nécessaires à son adaptation audio.
                        </p>

                        <p>
                            Les traitements effectués par les prestataires
                            techniques sont encadrés par les conditions
                            applicables à leurs services et par la
                            Politique de confidentialité de KaMa.
                        </p>
                    </section>

                    {{-- 12 --}}
                    <section class="kama-legal-section" id="reclamations">
                        <span class="kama-legal-section-number">12</span>
                        <h2>Réclamations et atteintes aux droits</h2>

                        <p>
                            Toute personne qui estime qu’un ouvrage publié
                            sur KaMa porte atteinte à ses droits peut
                            contacter la plateforme en précisant le contenu
                            concerné, la nature de la réclamation et les
                            éléments permettant de l’examiner.
                        </p>

                        <p>
                            KaMa peut demander des justificatifs
                            complémentaires, limiter l’accès à un contenu
                            ou prendre d’autres mesures appropriées,
                            conformément à la législation applicable.
                        </p>

                        <p>
                            L’auteur concerné peut être invité à présenter
                            ses observations ou les justificatifs démontrant
                            qu’il dispose des droits nécessaires.
                        </p>

                        <p>
                            Les réclamations sont examinées selon les
                            procédures applicables. Leur dépôt n’entraîne
                            pas automatiquement le retrait d’un ouvrage.
                        </p>
                    </section>

                    {{-- 13 --}}
                    <section class="kama-legal-section" id="suspension">
                        <span class="kama-legal-section-number">13</span>
                        <h2>Suspension et résiliation</h2>

                        <p>
                            KaMa peut suspendre temporairement un compte,
                            retirer un ouvrage ou restreindre certaines
                            fonctionnalités en cas de violation des
                            présentes conditions, de fraude, d’atteinte
                            aux droits de tiers ou d’obligation légale.
                        </p>

                        <p>
                            Lorsque cela est approprié et légalement
                            possible, l’auteur est informé des motifs
                            de la mesure et des possibilités de
                            régularisation ou de contestation.
                        </p>

                        <p>
                            La fermeture d’un compte ou le retrait d’un
                            ouvrage n’efface pas les obligations qui
                            subsistent légalement, notamment celles
                            relatives aux transactions déjà réalisées
                            et aux litiges en cours.
                        </p>
                    </section>

                    {{-- 14 --}}
                    <section class="kama-legal-section" id="responsabilite">
                        <span class="kama-legal-section-number">14</span>
                        <h2>Responsabilités</h2>

                        <p>
                            L’auteur demeure responsable des ouvrages
                            soumis, des informations qu’il communique
                            et des droits nécessaires à leur publication
                            et à leur commercialisation.
                        </p>

                        <p>
                            KaMa met en œuvre des moyens raisonnables
                            pour assurer le fonctionnement de ses
                            services, sans garantir une disponibilité
                            permanente ni l’absence de toute interruption
                            technique.
                        </p>

                        <p>
                            Aucune disposition des présentes conditions
                            n’exclut ou ne limite une responsabilité
                            lorsque la loi applicable interdit une telle
                            exclusion ou limitation.
                        </p>
                    </section>

                    {{-- 15 --}}
                    <section class="kama-legal-section" id="donnees">
                        <span class="kama-legal-section-number">15</span>
                        <h2>Données personnelles</h2>

                        <p>
                            KaMa traite les données personnelles des
                            auteurs dans la mesure nécessaire à la
                            gestion des comptes, à l’examen des ouvrages,
                            à la gestion des transactions, aux versements
                            et à la sécurité de la plateforme.
                        </p>

                        <p>
                            Certaines données peuvent être communiquées
                            aux prestataires intervenant dans les paiements,
                            l’hébergement, la sécurité ou la génération
                            d’audiobooks, lorsque cela est nécessaire
                            à la fourniture des services.
                        </p>

                        <p>
                            Les modalités de collecte, d’utilisation,
                            de conservation et d’exercice des droits
                            relatifs aux données personnelles sont
                            détaillées dans la
                            <a href="{{ route('legal.privacy') }}">
                                Politique de confidentialité
                            </a>
                            de KaMa.
                        </p>
                    </section>

                    {{-- 16 --}}
                    <section class="kama-legal-section" id="modifications">
                        <span class="kama-legal-section-number">16</span>
                        <h2>Modification des conditions</h2>

                        <p>
                            KaMa peut modifier les présentes conditions
                            pour tenir compte de l’évolution de ses
                            fonctionnalités, de ses services ou de la
                            réglementation applicable.
                        </p>

                        <p>
                            La version applicable est celle portée à
                            la connaissance de l’auteur. Lorsque la loi
                            l’exige, les auteurs sont informés des
                            modifications et celles-ci prennent effet
                            conformément aux règles applicables.
                        </p>

                        <p>
                            En cas de modification substantielle,
                            KaMa peut demander à l’auteur de prendre
                            connaissance de la nouvelle version ou
                            de confirmer son acceptation lorsque cela
                            est nécessaire.
                        </p>
                    </section>

                    {{-- 17 --}}
                    <section class="kama-legal-section" id="contact">
                        <span class="kama-legal-section-number">17</span>
                        <h2>Contact</h2>

                        <p>
                            Pour toute question concernant les présentes
                            conditions, une publication, les droits
                            d’auteur ou la rémunération, vous pouvez
                            contacter KaMa à l’adresse suivante :
                        </p>

                        <p>
                            <strong>E-mail :</strong>
                            <a href="mailto:contact@kama-afrika.com">
                                contact@kama-afrika.com
                            </a>
                        </p>

                        <p>
                            Pour une réclamation relative à un droit
                            de propriété intellectuelle, veuillez
                            préciser l’ouvrage concerné, la nature
                            du problème et les éléments utiles à
                            l’examen de votre demande.
                        </p>
                    </section>

                    {{-- NOTE FINALE --}}
                    <div class="kama-legal-footer-note">

                        <ul>
                            <li>
                                <a href="{{ route('legal.terms') }}">
                                    Conditions générales d’utilisation
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('legal.sales') }}">
                                    Conditions générales de vente
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('legal.privacy') }}">
                                    Politique de confidentialité
                                </a>
                            </li>
                        </ul>
                    </div>

                </main>
            </div>
        </div>
    </section>
</div>
@endsection
