<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Stockage\Models\Role;
use Illuminate\Support\Facades\Hash;

class SoutienInformatiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée les membres du soutien informatique
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des membres du soutien informatique...');

        // S'assurer que le rôle existe
        $role = Role::firstOrCreate(
            ['name' => 'soutien_informatique'],
            [
                'display_name' => 'Soutien Informatique',
                'description' => 'Membre du support technique et développement',
                'is_system' => true,
            ]
        );

        $membres = [
            [
                'first_name' => 'Ulrich',
                'last_name' => 'GOHOUE',
                'email' => 'ulrich@gmail.com',
                'phone' => '0145853662',
                'poste' => 'Ing Télécoms, Software Engineer',
            ],
            [
                'first_name' => 'Fadel',
                'last_name' => 'SEWADE',
                'email' => 'fadelsew@gmail.com',
                'phone' => '0152697137',
                'poste' => 'Software Engineer, AI Developer',
            ],
            [
                'first_name' => 'Prince',
                'last_name' => 'AVOHOU',
                'email' => 'avohouprince@gmail.com',
                'phone' => '0161528962',
                'poste' => 'Software Engineer',
            ],
            [
                'first_name' => 'Florian',
                'last_name' => 'KOTANMI',
                'email' => 'floriankot@gmail.com',
                'phone' => '0152747455',
                'poste' => 'Software Engineer, AI Developer',
            ],
        ];

        $created = 0;
        foreach ($membres as $membreData) {
            try {
                // Créer ou mettre à jour l'utilisateur
                $user = User::updateOrCreate(
                    ['email' => $membreData['email']],
                    [
                        'first_name' => $membreData['first_name'],
                        'last_name' => $membreData['last_name'],
                        'phone' => $membreData['phone'],
                        'password' => Hash::make('password123'), // Mot de passe par défaut
                        'email_verified_at' => now(),
                    ]
                );

                // Assigner le rôle s'il ne l'a pas déjà
                if (!$user->roles()->where('role_id', $role->id)->exists()) {
                    $user->roles()->attach($role->id);
                }

                $this->command->info("   ✅ {$membreData['first_name']} {$membreData['last_name']} - {$membreData['poste']}");
                $created++;
            } catch (\Exception $e) {
                $this->command->warn("   ⚠️  Erreur pour {$membreData['first_name']} {$membreData['last_name']}: {$e->getMessage()}");
            }
        }

        $this->command->info('');
        $this->command->info("🎉 $created membres du soutien informatique créés avec succès !");
        $this->command->info('');
        $this->command->info('🔗 Testez l\'API: curl http://127.0.0.1:8000/api/auth/soutien-informatique');
    }
}
