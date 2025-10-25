<?php

namespace Database\Seeders;

use App\Modules\Inscription\Models\Cycle;
use App\Modules\Inscription\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Génie Civil',
                'abbreviation' => 'GC',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Génie Electrique',
                'abbreviation' => 'GE',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Géomètre Topographe',
                'abbreviation' => 'GT',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Production Animale',
                'abbreviation' => 'PA',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Production Végétale',
                'abbreviation' => 'PV',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Génie de l\'Environnement',
                'abbreviation' => 'Gen',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Hygiène et Contrôle de Qualité',
                'abbreviation' => 'HCQ',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Biohygiène et Sécurité Sanitaire',
                'abbreviation' => 'BSS',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Analyses Biomédicales',
                'abbreviation' => 'ABM',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Nutrition, Diététique et Technologie Alimentaire',
                'abbreviation' => 'NDTA',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Génie Rural',
                'abbreviation' => 'GR',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Maintenance Industrielle',
                'abbreviation' => 'MI',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Mécanique Automobile',
                'abbreviation' => 'MA',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Hydraulique',
                'abbreviation' => 'HYD',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Fabrication Mécanique',
                'abbreviation' => 'FM',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Froid et Climatisation',
                'abbreviation' => 'FC',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Génie Mécanique et Energétique',
                'abbreviation' => 'GME',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Génie Mécanique et Productique',
                'abbreviation' => 'GMP',
                'cycle_name' => 'Licence Professionnelle',
            ],
            [
                'name' => 'Production Végétale et Post-Récolte',
                'abbreviation' => 'PVPR',
                'cycle_name' => 'Master Professionnel',
            ],
            [
                'name' => 'Génie Civil',
                'abbreviation' => 'GC',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Géomètre Topographe',
                'abbreviation' => 'GT',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Génie Electrique',
                'abbreviation' => 'GE',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Génie Mécanique et Energétique',
                'abbreviation' => 'GME',
                'cycle_name' => 'Ingénierie',
            ],
            // Départements Prépa pour chaque filière du cycle ingénieurs
            [
                'name' => 'Prépa Génie Civil',
                'abbreviation' => 'Prépa GC',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Prépa Géomètre Topographe',
                'abbreviation' => 'Prépa GT',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Prépa Génie Electrique',
                'abbreviation' => 'Prépa GE',
                'cycle_name' => 'Ingénierie',
            ],
            [
                'name' => 'Prépa Génie Mécanique et Energétique',
                'abbreviation' => 'Prépa GME',
                'cycle_name' => 'Ingénierie',
            ],
        ];

        foreach ($departments as $departmentData) {
            $cycle = Cycle::where('name', $departmentData['cycle_name'])->first();
            if ($cycle) {
                Department::create([
                    'name' => $departmentData['name'],
                    'abbreviation' => $departmentData['abbreviation'],
                    'cycle_id' => $cycle->id,
                ]);
            }
        }
    }
}
