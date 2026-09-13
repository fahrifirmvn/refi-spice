<?php

namespace Database\Factories;

use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year' => (string) $this->faker->numberBetween(1998, 2025),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
            'order' => 0,
        ];
    }
}
