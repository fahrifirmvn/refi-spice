<?php

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['HACCP', 'ISO 22000', 'USDA Organic', 'EU Organic', 'Halal Indonesia']),
            'logo_path' => null,
            'is_active' => true,
            'is_verified' => false,
            'order' => 0,
        ];
    }
}
