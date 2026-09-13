<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductSpecification>
 */
class ProductSpecificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'spec_key' => $this->faker->randomElement(['Moisture Content', 'Purity', 'Packaging', 'Certification']),
            'spec_value' => $this->faker->words(3, true),
            'order' => 0,
        ];
    }
}
