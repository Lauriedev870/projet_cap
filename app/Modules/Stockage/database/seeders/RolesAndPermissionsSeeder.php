<?php

namespace App\Modules\Stockage\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Stockage\Models\Role;
use App\Modules\Stockage\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions de base
        $permissions = [
            // Permissions pour les fichiers
            [
                'name' => 'stockage.files.read',
                'display_name' => 'Lire les fichiers',
                'description' => 'Permet de voir et télécharger les fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'read',
            ],
            [
                'name' => 'stockage.files.write',
                'display_name' => 'Modifier les fichiers',
                'description' => 'Permet de créer et modifier les fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'write',
            ],
            [
                'name' => 'stockage.files.delete',
                'display_name' => 'Supprimer les fichiers',
                'description' => 'Permet de supprimer les fichiers',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'delete',
            ],
            [
                'name' => 'stockage.files.share',
                'display_name' => 'Partager les fichiers',
                'description' => 'Permet de créer des liens de partage',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'share',
            ],
            [
                'name' => 'stockage.files.admin',
                'display_name' => 'Administration des fichiers',
                'description' => 'Accès complet aux fichiers et permissions',
                'module' => 'stockage',
                'resource' => 'files',
                'action' => 'admin',
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Créer les rôles de base
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrateur',
                'description' => 'Accès complet au système',
                'is_system' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Gestionnaire',
                'description' => 'Peut gérer les fichiers et permissions',
                'is_system' => true,
            ]
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'Utilisateur',
                'description' => 'Utilisateur standard',
                'is_system' => true,
            ]
        );

        $guestRole = Role::firstOrCreate(
            ['name' => 'guest'],
            [
                'display_name' => 'Invité',
                'description' => 'Accès limité en lecture seule',
                'is_system' => true,
            ]
        );

        // Attribuer les permissions aux rôles
        $adminRole->givePermissionTo([
            'stockage.files.read',
            'stockage.files.write',
            'stockage.files.delete',
            'stockage.files.share',
            'stockage.files.admin',
        ]);

        $managerRole->givePermissionTo([
            'stockage.files.read',
            'stockage.files.write',
            'stockage.files.delete',
            'stockage.files.share',
        ]);

        $userRole->givePermissionTo([
            'stockage.files.read',
            'stockage.files.write',
            'stockage.files.share',
        ]);

        $guestRole->givePermissionTo([
            'stockage.files.read',
        ]);

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}
