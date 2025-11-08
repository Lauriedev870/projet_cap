<?php

namespace Database\Factories;

use App\Modules\Inscription\Models\Cycle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Inscription\Models\Cycle>
 */
class CycleFactory extends Factory
{
    protected $model = Cycle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement(['Licence', 'Master', 'Ingénieur']);
        
        return [
            'name' => $name,
            'abbreviation' => strtoupper(substr($name, 0, 1)),
            'years_count' => fake()->numberBetween(3, 5),
            'is_lmd' => true,
            'type' => fake()->randomElement(['licence', 'master', 'ingenierie']),
        ];
    }
}
