<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(): array
    {
        $product = Product::factory()->create(['is_active' => true]);

        return [
            'full_name' => 'Dr. Julian Vance',
            'company_name' => 'Batavia Botanicals Ltd.',
            'destination_country' => 'Rotterdam, NL',
            'commodity_interest' => $product->name,
            'volume_estimate' => '5-20 MT',
            'additional_notes' => 'Butuh sertifikasi EU Organic.',
        ];
    }

    public function test_valid_submission_is_stored_and_redirects_with_success(): void
    {
        $payload = $this->validPayload();

        $response = $this->post(route('inquiry.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('inquiries', [
            'full_name' => 'Dr. Julian Vance',
            'company_name' => 'Batavia Botanicals Ltd.',
            'status' => 'new',
        ]);
    }

    public function test_missing_required_fields_fails_validation_and_does_not_store(): void
    {
        $response = $this->post(route('inquiry.store'), []);

        $response->assertSessionHasErrors([
            'full_name',
            'company_name',
            'destination_country',
            'commodity_interest',
            'volume_estimate',
        ]);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_filled_honeypot_field_is_silently_rejected(): void
    {
        $payload = array_merge($this->validPayload(), ['website' => 'http://spam.example']);

        $response = $this->post(route('inquiry.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_sixth_submission_within_ten_minutes_from_same_ip_is_rate_limited(): void
    {
        $payload = $this->validPayload();

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('inquiry.store'), $payload);
            $response->assertStatus(302);
            $response->assertSessionHasNoErrors();
        }

        $sixthResponse = $this->post(route('inquiry.store'), $payload);

        $sixthResponse->assertStatus(429);
        $this->assertSame(5, Inquiry::count());
    }
}
