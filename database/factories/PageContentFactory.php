<?php

namespace Database\Factories;

use App\Models\PageContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageContent>
 */
class PageContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_key' => $this->faker->word(),
            'field_key' => $this->faker->word(),
            'field_value' => $this->faker->sentence(),
        ];
    }
}
