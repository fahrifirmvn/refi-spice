<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote' => $this->faker->paragraph(2),
            'author_name' => $this->faker->name(),
            'author_title' => $this->faker->jobTitle(),
            'author_company' => $this->faker->company(),
            'standard_badge' => $this->faker->optional()->randomElement(['ISO 22000', 'HACCP']),
            'is_active' => true,
            'is_verified' => false,
            'order' => 0,
        ];
    }
}
