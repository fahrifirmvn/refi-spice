<?php

namespace Database\Factories;

use App\Models\ProcessStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProcessStep>
 */
class ProcessStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'step_number' => $this->faker->unique()->numberBetween(1, 6),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image_path' => null,
            'data_label' => ucfirst($this->faker->word()),
            'data_value' => (string) $this->faker->numberBetween(1, 100),
            'is_active' => true,
            'order' => 0,
        ];
    }
}
