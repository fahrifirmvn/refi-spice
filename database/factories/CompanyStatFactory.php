<?php

namespace Database\Factories;

use App\Models\CompanyStat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyStat>
 */
class CompanyStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => ucwords($this->faker->words(3, true)),
            'code_label' => strtoupper($this->faker->words(2, true)),
            'value' => (string) $this->faker->numberBetween(1, 500),
            'suffix' => $this->faker->randomElement(['+', '%', ' MT', null]),
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'order' => 0,
        ];
    }
}
