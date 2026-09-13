<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'destination_country' => $this->faker->country(),
            'commodity_interest' => $this->faker->randomElement(['Pala', 'Cengkeh', 'Kayu Manis', 'Lada Hitam', 'Lada Putih', 'Kapulaga', 'Vanili']),
            'volume_estimate' => $this->faker->randomElement(['1-5 MT', '5-20 MT', '20+ MT']),
            'additional_notes' => $this->faker->optional()->sentence(),
            'status' => 'new',
            'email_sent_at' => null,
            'email_failed_reason' => null,
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
