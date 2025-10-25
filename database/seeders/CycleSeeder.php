<?php

namespace Database\Seeders;

use App\Modules\Inscription\Models\Cycle;
use Illuminate\Database\Seeder;

class CycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cycles = [
            [
                'name' => 'Licence Professionnelle',
                'abbreviation' => 'DLP',
                'years_count' => 3,
                'is_lmd' => true,
                'type' => 'undergraduate',
            ],
            [
                'name' => 'Master Professionnel',
                'abbreviation' => 'DMP',
                'years_count' => 2,
                'is_lmd' => true,
                'type' => 'graduate',
            ],
            [
                'name' => 'Ingénierie',
                'abbreviation' => 'DIC',
                'years_count' => 5,
                'is_lmd' => false,
                'type' => 'professional',
            ],
        ];

        foreach ($cycles as $cycle) {
            Cycle::create($cycle);
        }
    }
}
