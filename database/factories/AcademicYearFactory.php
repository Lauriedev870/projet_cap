<?php

namespace Database\Factories;

use App\Modules\Inscription\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Inscription\Models\AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->unique()->numberBetween(2020, 2035);
        
        return [
            'academic_year' => $year . '-' . ($year + 1),
            'year_start' => $year . '-09-01',
            'year_end' => ($year + 1) . '-06-30',
            'submission_start' => $year . '-06-01',
            'submission_end' => $year . '-08-31',
            'is_current' => false,
        ];
    }
}
