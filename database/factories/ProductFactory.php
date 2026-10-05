<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'slug' => Str::slug($name),
            'name' => ucwords($name),
            'latin_name' => ucwords($this->faker->words(2, true)),
            'lot_number' => strtoupper($this->faker->bothify('LOT-####-??')),
            'origin_region' => $this->faker->city(),
            'origin_label' => $this->faker->city().', Indonesia',
            'sensory_profile' => $this->faker->sentence(12),
            'export_readiness' => $this->faker->randomElement(['Ready to Export', 'Made to Order', 'Seasonal']),
            'primary_image_path' => null,
            'primary_image_alt' => null,
            'is_featured' => $this->faker->boolean(30),
            'is_active' => true,
            'order' => 0,
        ];
    }
}
