<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // ==========================================
            // TABLEAU DE BORD
            // ==========================================
            [
                'name' => 'dashboard.view',
                'label' => 'Voir le tableau de bord',
                'description' => 'Permet de consulter le tableau de bord administratif.',
            ],

            // ==========================================
            // CATALOGUE - LIVRES
            // ==========================================
            [
                'name' => 'books.view',
                'label' => 'Voir les livres',
                'description' => 'Permet de consulter la liste des livres.',
            ],
            [
                'name' => 'books.show',
                'label' => 'Voir les détails d’un livre',
                'description' => 'Permet de consulter les informations détaillées d’un livre.',
            ],
            [
                'name' => 'books.editorial_review',
                'label' => 'Vérifier un livre',
                'description' => 'Permet d’effectuer la vérification éditoriale d’un livre.',
            ],

            // ==========================================
            // FILE ÉDITORIALE
            // ==========================================
            [
                'name' => 'editorial.view',
                'label' => 'Voir la file éditoriale',
                'description' => 'Permet de consulter les livres en attente de traitement éditorial.',
            ],
            [
                'name' => 'editorial.approve',
                'label' => 'Approuver un livre',
                'description' => 'Permet d’approuver un livre soumis à la vérification éditoriale.',
            ],
            [
                'name' => 'editorial.reject',
                'label' => 'Rejeter un livre',
                'description' => 'Permet de rejeter un livre soumis à la vérification éditoriale.',
            ],

            // ==========================================
            // CATÉGORIES
            // ==========================================
            [
                'name' => 'categories.view',
                'label' => 'Voir les catégories',
                'description' => 'Permet de consulter les catégories de livres.',
            ],
            [
                'name' => 'categories.create',
                'label' => 'Créer une catégorie',
                'description' => 'Permet d’ajouter une nouvelle catégorie.',
            ],
            [
                'name' => 'categories.edit',
                'label' => 'Modifier une catégorie',
                'description' => 'Permet de modifier une catégorie existante.',
            ],
            [
                'name' => 'categories.delete',
                'label' => 'Supprimer une catégorie',
                'description' => 'Permet de supprimer une catégorie.',
            ],

            // ==========================================
            // FORMULES DE SPONSORING
            // ==========================================
            [
                'name' => 'sponsorship_plans.view',
                'label' => 'Voir les formules de sponsoring',
                'description' => 'Permet de consulter les formules de sponsoring.',
            ],
            [
                'name' => 'sponsorship_plans.create',
                'label' => 'Créer une formule de sponsoring',
                'description' => 'Permet de créer une nouvelle formule de sponsoring.',
            ],
            [
                'name' => 'sponsorship_plans.edit',
                'label' => 'Modifier une formule de sponsoring',
                'description' => 'Permet de modifier une formule de sponsoring.',
            ],
            [
                'name' => 'sponsorship_plans.delete',
                'label' => 'Supprimer une formule de sponsoring',
                'description' => 'Permet de supprimer une formule de sponsoring.',
            ],

            // ==========================================
            // DEMANDES DE SPONSORING
            // ==========================================
            [
                'name' => 'sponsorships.view',
                'label' => 'Voir les demandes de sponsoring',
                'description' => 'Permet de consulter les demandes de sponsoring.',
            ],
            [
                'name' => 'sponsorships.approve',
                'label' => 'Approuver une demande de sponsoring',
                'description' => 'Permet d’approuver une demande de sponsoring.',
            ],
            [
                'name' => 'sponsorships.reject',
                'label' => 'Rejeter une demande de sponsoring',
                'description' => 'Permet de rejeter une demande de sponsoring.',
            ],

            // ==========================================
            // MON ESPACE AUTEUR - LIVRES
            // ==========================================
            [
                'name' => 'author_books.view',
                'label' => 'Voir les livres du compte administrateur',
                'description' => 'Permet de consulter les livres appartenant au compte administrateur.',
            ],
            [
                'name' => 'author_books.create',
                'label' => 'Ajouter un livre au compte administrateur',
                'description' => 'Permet de créer un livre depuis l’espace auteur administrateur.',
            ],
            [
                'name' => 'author_books.edit',
                'label' => 'Modifier les livres du compte administrateur',
                'description' => 'Permet de modifier les livres appartenant au compte administrateur.',
            ],
            [
                'name' => 'author_books.show',
                'label' => 'Voir les détails des livres du compte administrateur',
                'description' => 'Permet de consulter les détails des livres du compte administrateur.',
            ],
            [
                'name' => 'author_books.share',
                'label' => 'Partager les livres du compte administrateur',
                'description' => 'Permet de partager les livres du compte administrateur.',
            ],
            [
                'name' => 'author_books.sponsor',
                'label' => 'Sponsoriser les livres du compte administrateur',
                'description' => 'Permet de sponsoriser les livres du compte administrateur.',
            ],
            [
                'name' => 'author_books.delete',
                'label' => 'Supprimer les livres du compte administrateur',
                'description' => 'Permet de supprimer les livres appartenant au compte administrateur.',
            ],

            // ==========================================
            // MON ESPACE AUTEUR - AVIS
            // ==========================================
            [
                'name' => 'author_reviews.view',
                'label' => 'Voir les avis lecteurs du compte administrateur',
                'description' => 'Permet de consulter les avis sur les livres du compte administrateur.',
            ],

            // ==========================================
            // MON ESPACE AUTEUR - PORTEFEUILLE
            // ==========================================
            [
                'name' => 'author_wallet.view',
                'label' => 'Voir le portefeuille du compte administrateur',
                'description' => 'Permet de consulter le portefeuille du compte administrateur.',
            ],

            // ==========================================
            // MON ESPACE AUTEUR - REVENUS
            // ==========================================
            [
                'name' => 'author_revenues.view',
                'label' => 'Voir les revenus du compte administrateur',
                'description' => 'Permet de consulter les revenus générés par les livres du compte administrateur.',
            ],

            // ==========================================
            // NOTIFICATIONS
            // ==========================================
            [
                'name' => 'notifications.view',
                'label' => 'Voir les notifications',
                'description' => 'Permet de consulter les notifications.',
            ],
            [
                'name' => 'notifications.show',
                'label' => 'Voir le détail d’une notification',
                'description' => 'Permet de consulter le contenu détaillé d’une notification.',
            ],
            [
                'name' => 'notifications.delete',
                'label' => 'Supprimer une notification',
                'description' => 'Permet de supprimer une notification.',
            ],

            // ==========================================
            // UTILISATEURS
            // ==========================================
            [
                'name' => 'users.view',
                'label' => 'Voir les utilisateurs',
                'description' => 'Permet de consulter la liste des utilisateurs.',
            ],
            [
                'name' => 'users.create',
                'label' => 'Ajouter un utilisateur',
                'description' => 'Permet de créer un nouvel utilisateur.',
            ],
            [
                'name' => 'users.show',
                'label' => 'Voir le profil d’un utilisateur',
                'description' => 'Permet de consulter les informations détaillées d’un utilisateur.',
            ],
            [
                'name' => 'users.edit',
                'label' => 'Modifier un utilisateur',
                'description' => 'Permet de modifier les informations d’un utilisateur.',
            ],
            [
                'name' => 'users.delete',
                'label' => 'Supprimer un utilisateur',
                'description' => 'Permet de supprimer un utilisateur.',
            ],

            // ==========================================
            // PORTEFEUILLE KAMA
            // ==========================================
            [
                'name' => 'platform_wallet.view',
                'label' => 'Voir le portefeuille KaMa',
                'description' => 'Permet de consulter le portefeuille financier de KaMa.',
            ],

            // ==========================================
            // RETRAITS
            // ==========================================
            [
                'name' => 'withdrawals.view',
                'label' => 'Voir les retraits',
                'description' => 'Permet de consulter les demandes de retrait.',
            ],
            [
                'name' => 'withdrawals.process',
                'label' => 'Traiter un retrait',
                'description' => 'Permet de mettre une demande de retrait en cours de traitement.',
            ],
            [
                'name' => 'withdrawals.complete',
                'label' => 'Valider un retrait',
                'description' => 'Permet de marquer une demande de retrait comme terminée.',
            ],
            [
                'name' => 'withdrawals.reject',
                'label' => 'Rejeter un retrait',
                'description' => 'Permet de rejeter une demande de retrait.',
            ],

            // ==========================================
            // PARAMÈTRES - COMMERCE
            // ==========================================
            [
                'name' => 'settings.commerce.view',
                'label' => 'Voir les paramètres commerciaux',
                'description' => 'Permet de consulter les paramètres liés au commerce.',
            ],
            [
                'name' => 'settings.commerce.edit',
                'label' => 'Modifier les paramètres commerciaux',
                'description' => 'Permet de modifier les frais et paramètres commerciaux.',
            ],

            // ==========================================
            // PARAMÈTRES - MOBILE MONEY
            // ==========================================
            [
                'name' => 'settings.mobile_money.view',
                'label' => 'Voir les paramètres Mobile Money',
                'description' => 'Permet de consulter les paramètres Mobile Money.',
            ],
            [
                'name' => 'settings.mobile_money.refresh_rates',
                'label' => 'Actualiser les taux Mobile Money',
                'description' => 'Permet d’actualiser les taux de change Mobile Money.',
            ],

            // ==========================================
            // PARAMÈTRES - PROFIL
            // ==========================================
            [
                'name' => 'settings.profile.view',
                'label' => 'Voir mon profil',
                'description' => 'Permet de consulter les informations du profil.',
            ],
            [
                'name' => 'settings.profile.edit',
                'label' => 'Modifier mon profil',
                'description' => 'Permet de modifier les informations du profil.',
            ],

            // ==========================================
            // PARAMÈTRES - SÉCURITÉ
            // ==========================================
            [
                'name' => 'settings.security.view',
                'label' => 'Voir les paramètres de sécurité',
                'description' => 'Permet de consulter les paramètres de sécurité.',
            ],
            [
                'name' => 'settings.security.change_password',
                'label' => 'Modifier mon mot de passe',
                'description' => 'Permet de modifier le mot de passe du compte.',
            ],

            // ==========================================
            // RÔLES ET PERMISSIONS
            // ==========================================
            [
                'name' => 'roles.view',
                'label' => 'Voir les rôles',
                'description' => 'Permet de consulter les rôles administratifs.',
            ],
            [
                'name' => 'roles.create',
                'label' => 'Créer un rôle',
                'description' => 'Permet de créer un rôle administratif personnalisé.',
            ],
            [
                'name' => 'roles.edit',
                'label' => 'Modifier un rôle',
                'description' => 'Permet de modifier un rôle administratif et ses permissions.',
            ],
            [
                'name' => 'roles.delete',
                'label' => 'Supprimer un rôle',
                'description' => 'Permet de supprimer un rôle administratif personnalisé.',
            ],
        ];

        /*
         * Création ou mise à jour des permissions.
         */
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'label' => $permission['label'],
                    'description' => $permission['description'],
                ]
            );
        }

        /*
         * Le rôle "admin" possède toutes les permissions.
         */
        $adminRole = Role::query()->where('name', 'admin')->first();

        if ($adminRole) {

            $permissionIds = Permission::pluck('id');

            $adminRole->permissions()->sync($permissionIds);

            /*
             * On attribue le rôle administratif "admin"
             * aux utilisateurs ayant actuellement le rôle principal "admin".
             */
            User::whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })->each(function ($user) use ($adminRole) {

                $user->adminRoles()->syncWithoutDetaching([
                    $adminRole->id
                ]);
            });
        }
    }
}