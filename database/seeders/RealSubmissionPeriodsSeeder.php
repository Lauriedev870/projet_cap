<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Inscription\Models\AcademicYear;
use App\Modules\Inscription\Models\SubmissionPeriod;
use Carbon\Carbon;

class RealSubmissionPeriodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crée des périodes de soumission pour les vraies filières du CAP
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des périodes de soumission pour les filières du CAP...');

        // Récupérer l'année académique 2025-2026
        $academicYear = AcademicYear::where('academic_year', '2025-2026')->first();

        if (!$academicYear) {
            $this->command->error('❌ Année académique 2025-2026 non trouvée. Lancez d\'abord TestSubmissionPeriodSeeder.');
            return;
        }

        $now = Carbon::now();

        // Configuration des périodes par cycle
        $periods = [
            'licence_professionnelle' => [
                'start' => $now->copy()->subDays(10),  // Commencé il y a 10 jours
                'end' => $now->copy()->addDays(45),    // Finit dans 45 jours
                'departments' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] // IDs 1-18
            ],
            'master_professionnel' => [
                'start' => $now->copy()->addDays(15),  // Commence dans 15 jours
                'end' => $now->copy()->addDays(75),    // Finit dans 75 jours
                'departments' => [19] // ID 19
            ],
            'ingenierie' => [
                'start' => $now->copy()->subDays(5),   // Commencé il y a 5 jours
                'end' => $now->copy()->addDays(60),    // Finit dans 60 jours
                'departments' => [20, 21, 22, 23, 24, 25, 26, 27] // IDs 20-27
            ],
        ];

        $totalCreated = 0;

        foreach ($periods as $cycle => $config) {
            $this->command->info("📝 Création des périodes pour : $cycle");

            foreach ($config['departments'] as $departmentId) {
                try {
                    SubmissionPeriod::updateOrCreate(
                        [
                            'academic_year_id' => $academicYear->id,
                            'department_id' => $departmentId,
                        ],
                        [
                            'start_date' => $config['start']->format('Y-m-d'),
                            'end_date' => $config['end']->format('Y-m-d'),
                        ]
                    );
                    $totalCreated++;
                } catch (\Exception $e) {
                    $this->command->warn("⚠️  Erreur pour le département ID $departmentId: {$e->getMessage()}");
                }
            }

            $this->command->info("   ✅ {$config['start']->format('Y-m-d')} → {$config['end']->format('Y-m-d')}");
        }

        $this->command->info('');
        $this->command->info("🎉 $totalCreated périodes de soumission créées avec succès !");
        $this->command->info('');
        $this->command->info('📊 Résumé des périodes :');
        $this->command->info("   → Licence Professionnelle (18 filières): inscriptions ouvertes jusqu'au {$periods['licence_professionnelle']['end']->format('d/m/Y')}");
        $this->command->info("   → Master Professionnel (1 filière): prochainement (à partir du {$periods['master_professionnel']['start']->format('d/m/Y')})");
        $this->command->info("   → Ingénierie (8 filières): inscriptions ouvertes jusqu'au {$periods['ingenierie']['end']->format('d/m/Y')}");
        $this->command->info('');
        $this->command->info('🔗 Testez les APIs:');
        $this->command->info('   curl http://127.0.0.1:8000/api/next-deadline');
        $this->command->info('   curl http://127.0.0.1:8000/api/filieres');
    }
}
