<?php

namespace Database\Factories;

use App\Modules\Inscription\Models\AcademicYear;
use App\Modules\Inscription\Models\Department;
use App\Modules\Inscription\Models\PendingStudent;
use App\Modules\Inscription\Models\PersonalInformation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Inscription\Models\PendingStudent>
 */
class PendingStudentFactory extends Factory
{
    protected $model = PendingStudent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'approved', 'rejected']);
        
        return [
            'personal_information_id' => PersonalInformation::factory(),
            'tracking_code' => strtoupper(Str::random(10)),
            'department_id' => Department::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'level' => fake()->randomElement(['L1', 'L2', 'L3', 'M1', 'M2']),
            'status' => $status,
            // cuca_opinion doit être null pour pending, favorable/défavorable pour approved/rejected
            'cuca_opinion' => $status === 'pending' ? null : fake()->randomElement(['favorable', 'défavorable']),
            'cuca_comment' => fake()->optional()->sentence(),
            // sponsorise doit être 0 ou 1, pas boolean
            'sponsorise' => fake()->randomElement([0, 1]),
            'documents' => [],
        ];
    }
}
