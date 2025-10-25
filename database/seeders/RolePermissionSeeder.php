<?php

namespace Database\Seeders;

use App\Modules\Stockage\Models\Permission;
use App\Modules\Stockage\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les rôles
        $chefCap = Role::where('name', 'chef_cap')->first();
        $chefDivision = Role::where('name', 'chef_division')->first();
        $comptable = Role::where('name', 'comptable')->first();
        $secretaire = Role::where('name', 'secretaire')->first();
        $etudiant = Role::where('name', 'etudiant')->first();
        $responsable = Role::where('name', 'responsable')->first();

        // Permissions pour Chef CAP (toutes les permissions)
        if ($chefCap) {
            $allPermissions = Permission::all();
            $chefCap->permissions()->sync($allPermissions->pluck('id'));
        }

        // Permissions pour Chef de Division
        if ($chefDivision) {
            $divisionPermissions = Permission::whereIn('name', [
                // Permissions fichiers
                'files.read', 'files.write', 'files.share',
                'folders.read', 'folders.write',
                // Permissions étudiants
                'students.read', 'students.write',
                'inscriptions.read', 'inscriptions.write',
                // Permissions cycles et départements
                'cycles.read', 'cycles.write',
                'departments.read', 'departments.write',
                // Permissions finances
                'transactions.read', 'transactions.write',
                'amounts.read', 'amounts.write',
                'exonerations.read', 'exonerations.write',
                // Permissions admin limitées
                'users.read', 'reports.read',
            ])->get();
            $chefDivision->permissions()->sync($divisionPermissions->pluck('id'));
        }

        // Permissions pour Comptable
        if ($comptable) {
            $comptablePermissions = Permission::whereIn('name', [
                // Permissions fichiers limitées
                'files.read',
                'folders.read',
                // Permissions finances
                'transactions.read', 'transactions.write',
                'amounts.read', 'amounts.write',
                'exonerations.read', 'exonerations.write',
                // Permissions étudiants (lecture seule)
                'students.read',
                'inscriptions.read',
                // Permissions rapports
                'reports.read',
            ])->get();
            $comptable->permissions()->sync($comptablePermissions->pluck('id'));
        }

        // Permissions pour Secrétaire
        if ($secretaire) {
            $secretairePermissions = Permission::whereIn('name', [
                // Permissions fichiers
                'files.read', 'files.write', 'files.share',
                'folders.read', 'folders.write',
                // Permissions étudiants
                'students.read', 'students.write',
                'inscriptions.read', 'inscriptions.write',
                // Permissions cycles et départements (lecture seule)
                'cycles.read', 'departments.read',
                // Permissions utilisateurs limitées
                'users.read',
                // Permissions rapports
                'reports.read',
            ])->get();
            $secretaire->permissions()->sync($secretairePermissions->pluck('id'));
        }

        // Permissions pour Étudiant
        if ($etudiant) {
            $etudiantPermissions = Permission::whereIn('name', [
                // Permissions fichiers limitées
                'files.read',
                'folders.read',
                // Permissions personnelles (si implémenté)
                // Pour l'instant, permissions minimales
            ])->get();
            $etudiant->permissions()->sync($etudiantPermissions->pluck('id'));
        }

        // Permissions pour Responsable
        if ($responsable) {
            $responsablePermissions = Permission::whereIn('name', [
                // Permissions fichiers
                'files.read', 'files.write', 'files.share',
                'folders.read', 'folders.write',
                // Permissions étudiants
                'students.read', 'students.write',
                'inscriptions.read', 'inscriptions.write',
                // Permissions cycles et départements
                'cycles.read', 'cycles.write',
                'departments.read', 'departments.write',
                // Permissions rapports
                'reports.read',
            ])->get();
            $responsable->permissions()->sync($responsablePermissions->pluck('id'));
        }
    }
}
