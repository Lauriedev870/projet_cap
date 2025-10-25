<?php

namespace Database\Seeders;

use App\Modules\Stockage\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Permissions pour les fichiers (stockage)
            [
                'name' => 'files.read',
                'display_name' => 'Lire les fichiers',
                'description' => 'Permet de lire et télécharger des fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'read',
            ],
            [
                'name' => 'files.write',
                'display_name' => 'Écrire des fichiers',
                'description' => 'Permet de créer et modifier des fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'write',
            ],
            [
                'name' => 'files.delete',
                'display_name' => 'Supprimer des fichiers',
                'description' => 'Permet de supprimer des fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'delete',
            ],
            [
                'name' => 'files.share',
                'display_name' => 'Partager des fichiers',
                'description' => 'Permet de partager des fichiers avec d\'autres utilisateurs',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'share',
            ],

            // Permissions pour les dossiers
            [
                'name' => 'folders.read',
                'display_name' => 'Lire les dossiers',
                'description' => 'Permet de voir le contenu des dossiers',
                'module' => 'stockage',
                'resource' => 'folders',
                'action' => 'read',
            ],
            [
                'name' => 'folders.write',
                'display_name' => 'Écrire dans les dossiers',
                'description' => 'Permet de créer et modifier des dossiers',
                'module' => 'stockage',
                'resource' => 'folders',
                'action' => 'write',
            ],
            [
                'name' => 'folders.delete',
                'display_name' => 'Supprimer des dossiers',
                'description' => 'Permet de supprimer des dossiers',
                'module' => 'stockage',
                'resource' => 'folders',
                'action' => 'delete',
            ],

            // Permissions pour les étudiants (inscription)
            [
                'name' => 'students.read',
                'display_name' => 'Voir les étudiants',
                'description' => 'Permet de consulter les informations des étudiants',
                'module' => 'inscription',
                'resource' => 'students',
                'action' => 'read',
            ],
            [
                'name' => 'students.write',
                'display_name' => 'Gérer les étudiants',
                'description' => 'Permet de créer et modifier les informations des étudiants',
                'module' => 'inscription',
                'resource' => 'students',
                'action' => 'write',
            ],
            [
                'name' => 'students.delete',
                'display_name' => 'Supprimer des étudiants',
                'description' => 'Permet de supprimer des étudiants',
                'module' => 'inscription',
                'resource' => 'students',
                'action' => 'delete',
            ],

            // Permissions pour les inscriptions
            [
                'name' => 'inscriptions.read',
                'display_name' => 'Voir les inscriptions',
                'description' => 'Permet de consulter les demandes d\'inscription',
                'module' => 'inscription',
                'resource' => 'inscriptions',
                'action' => 'read',
            ],
            [
                'name' => 'inscriptions.write',
                'display_name' => 'Gérer les inscriptions',
                'description' => 'Permet de valider ou rejeter les demandes d\'inscription',
                'module' => 'inscription',
                'resource' => 'inscriptions',
                'action' => 'write',
            ],

            // Permissions pour les cycles et départements
            [
                'name' => 'cycles.read',
                'display_name' => 'Voir les cycles',
                'description' => 'Permet de consulter les cycles d\'études',
                'module' => 'inscription',
                'resource' => 'cycles',
                'action' => 'read',
            ],
            [
                'name' => 'cycles.write',
                'display_name' => 'Gérer les cycles',
                'description' => 'Permet de créer et modifier les cycles',
                'module' => 'inscription',
                'resource' => 'cycles',
                'action' => 'write',
            ],
            [
                'name' => 'departments.read',
                'display_name' => 'Voir les départements',
                'description' => 'Permet de consulter les départements',
                'module' => 'inscription',
                'resource' => 'departments',
                'action' => 'read',
            ],
            [
                'name' => 'departments.write',
                'display_name' => 'Gérer les départements',
                'description' => 'Permet de créer et modifier les départements',
                'module' => 'inscription',
                'resource' => 'departments',
                'action' => 'write',
            ],

            // Permissions pour les finances
            [
                'name' => 'transactions.read',
                'display_name' => 'Voir les transactions',
                'description' => 'Permet de consulter les transactions financières',
                'module' => 'finance',
                'resource' => 'transactions',
                'action' => 'read',
            ],
            [
                'name' => 'transactions.write',
                'display_name' => 'Gérer les transactions',
                'description' => 'Permet de créer et modifier les transactions',
                'module' => 'finance',
                'resource' => 'transactions',
                'action' => 'write',
            ],
            [
                'name' => 'amounts.read',
                'display_name' => 'Voir les montants',
                'description' => 'Permet de consulter les montants et frais',
                'module' => 'finance',
                'resource' => 'amounts',
                'action' => 'read',
            ],
            [
                'name' => 'amounts.write',
                'display_name' => 'Gérer les montants',
                'description' => 'Permet de définir les montants et frais',
                'module' => 'finance',
                'resource' => 'amounts',
                'action' => 'write',
            ],
            [
                'name' => 'exonerations.read',
                'display_name' => 'Voir les exonérations',
                'description' => 'Permet de consulter les exonérations',
                'module' => 'finance',
                'resource' => 'exonerations',
                'action' => 'read',
            ],
            [
                'name' => 'exonerations.write',
                'display_name' => 'Gérer les exonérations',
                'description' => 'Permet de créer et modifier les exonérations',
                'module' => 'finance',
                'resource' => 'exonerations',
                'action' => 'write',
            ],

            // Permissions administratives
            [
                'name' => 'users.read',
                'display_name' => 'Voir les utilisateurs',
                'description' => 'Permet de consulter les utilisateurs du système',
                'module' => 'admin',
                'resource' => 'users',
                'action' => 'read',
            ],
            [
                'name' => 'users.write',
                'display_name' => 'Gérer les utilisateurs',
                'description' => 'Permet de créer et modifier les utilisateurs',
                'module' => 'admin',
                'resource' => 'users',
                'action' => 'write',
            ],
            [
                'name' => 'roles.read',
                'display_name' => 'Voir les rôles',
                'description' => 'Permet de consulter les rôles et permissions',
                'module' => 'admin',
                'resource' => 'roles',
                'action' => 'read',
            ],
            [
                'name' => 'roles.write',
                'display_name' => 'Gérer les rôles',
                'description' => 'Permet de créer et modifier les rôles et permissions',
                'module' => 'admin',
                'resource' => 'roles',
                'action' => 'write',
            ],
            [
                'name' => 'reports.read',
                'display_name' => 'Voir les rapports',
                'description' => 'Permet de consulter les rapports et statistiques',
                'module' => 'admin',
                'resource' => 'reports',
                'action' => 'read',
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }
    }
}
