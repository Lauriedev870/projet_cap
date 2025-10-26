<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Stockage\Models\Role;

class AssignChefContinueRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Assigne le rôle "Chef Division Formation Continue" à Max SANYA
     */
    public function run(): void
    {
        $this->command->info('🔄 Assignment du rôle Chef Division Formation Continue...');

        // Trouver Max SANYA (ID 1)
        $user = User::find(1);
        
        if (!$user) {
            $this->command->error('❌ Utilisateur ID 1 (Max SANYA) non trouvé');
            return;
        }

        // Trouver le rôle chef_division_continue
        $role = Role::where('name', 'chef_division_continue')->first();
        
        if (!$role) {
            $this->command->error('❌ Rôle chef_division_continue non trouvé');
            return;
        }

        // Assigner le rôle
        if (!$user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
            $this->command->info('✅ Rôle "Chef Division Formation Continue" assigné à Max SANYA');
        } else {
            $this->command->warn('⚠️  Max SANYA a déjà ce rôle');
        }

        // Afficher les rôles de l'utilisateur
        $this->command->info('');
        $this->command->info('📋 Rôles de Max SANYA :');
        foreach ($user->roles as $userRole) {
            $this->command->info("   → {$userRole->display_name} ({$userRole->name})");
        }
    }
}
