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
            'category_label' => ucwords($this->faker->words(2, true)),
            'description' => $this->faker->paragraph(),
            'image_path' => null,
            'image_alt' => null,
            'image_caption' => ucwords($this->faker->words(2, true)),
            'data_label' => ucfirst($this->faker->word()),
            'data_value' => (string) $this->faker->numberBetween(1, 100),
            'is_active' => true,
            'order' => 0,
        ];
    }
}
