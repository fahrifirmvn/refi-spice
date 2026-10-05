<?php

namespace Tests\Feature;

use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(): array
    {
        $product = Product::factory()->create(['is_active' => true]);

        return [
            'full_name' => 'Dr. Julian Vance',
            'email' => 'julian.vance@batavia.example',
            'phone' => '+31 6 1234 5678',
            'company_name' => 'Batavia Botanicals Ltd.',
            'destination_country' => 'Rotterdam, NL',
            'commodity_interest' => $product->name,
            'volume_estimate' => '5-20 MT',
            'additional_notes' => 'Butuh sertifikasi EU Organic.',
        ];
    }

    public function test_valid_submission_is_stored_and_redirects_with_success(): void
    {
        Mail::fake();

        $payload = $this->validPayload();

        $response = $this->post(route('inquiry.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('inquiries', [
            'full_name' => 'Dr. Julian Vance',
            'email' => 'julian.vance@batavia.example',
            'phone' => '+31 6 1234 5678',
            'company_name' => 'Batavia Botanicals Ltd.',
            'status' => 'new',
        ]);
        $this->assertNotNull(Inquiry::where('email', 'julian.vance@batavia.example')->value('email_queued_at'));

        Mail::assertQueued(NewInquiryNotification::class, fn ($mail) => $mail->inquiry->full_name === 'Dr. Julian Vance');
    }

    public function test_missing_required_fields_fails_validation_and_does_not_store(): void
    {
        $response = $this->post(route('inquiry.store'), []);

        $response->assertSessionHasErrors([
            'full_name',
            'email',
            'company_name',
            'destination_country',
            'commodity_interest',
            'volume_estimate',
        ]);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_invalid_email_fails_validation_and_does_not_store(): void
    {
        Mail::fake();

        $response = $this->post(route('inquiry.store'), [
            ...$this->validPayload(),
            'email' => 'alamat-email-tidak-valid',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Masukkan alamat email yang valid.',
        ]);

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    public function test_filled_honeypot_field_is_silently_rejected(): void
    {
        Mail::fake();

        $payload = array_merge($this->validPayload(), ['website' => 'http://spam.example']);

        $response = $this->post(route('inquiry.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    public function test_sixth_submission_within_ten_minutes_from_same_ip_is_rate_limited(): void
    {
        Mail::fake();

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

    public function test_mail_server_failure_still_stores_inquiry_and_records_failure_reason(): void
    {
        $inquiry = Inquiry::factory()->create([
            'email_sent_at' => null,
            'email_failed_reason' => null,
        ]);

        $failingMailer = \Mockery::mock(Mailer::class);
        $failingMailer->shouldReceive('send')->once()->andThrow(new \RuntimeException('Connection to SMTP host failed'));

        $notification = new NewInquiryNotification($inquiry);

        try {
            $notification->send($failingMailer);
            $this->fail('Expected the mail transport exception to bubble to the queue worker.');
        } catch (\RuntimeException $exception) {
            $notification->failed($exception);
        }

        $inquiry->refresh();
        $this->assertNotNull($inquiry->email_failed_reason);
        $this->assertStringContainsString('Connection to SMTP host failed', $inquiry->email_failed_reason);
        $this->assertNull($inquiry->email_sent_at);
        $this->assertNotNull($inquiry->email_queued_at);
        $this->assertNotNull($inquiry->email_failed_at);
    }

    public function test_notification_marks_inquiry_as_sent_when_mailer_accepts_it(): void
    {
        $inquiry = Inquiry::factory()->create([
            'email_sent_at' => null,
            'email_failed_reason' => 'Pengiriman sebelumnya gagal.',
        ]);
        $mailer = \Mockery::mock(Mailer::class);
        $mailer->shouldReceive('send')->once()->andReturnNull();

        (new NewInquiryNotification($inquiry))->send($mailer);

        $inquiry->refresh();
        $this->assertNotNull($inquiry->email_sent_at);
        $this->assertNotNull($inquiry->email_queued_at);
        $this->assertNull($inquiry->email_failed_at);
        $this->assertNull($inquiry->email_failed_reason);
    }

    public function test_queue_scheduling_failure_is_recorded_without_disrupting_submission(): void
    {
        $payload = $this->validPayload();

        Mail::shouldReceive('to')->once()->with(config('mail.sales_notification_address'))->andReturnSelf();
        Mail::shouldReceive('queue')->once()->andThrow(new \RuntimeException('Queue storage unavailable'));

        $response = $this->post(route('inquiry.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inquiries', [
            'email' => 'julian.vance@batavia.example',
            'email_failed_reason' => 'Queue storage unavailable',
        ]);
        $this->assertNotNull(Inquiry::where('email', 'julian.vance@batavia.example')->value('email_failed_at'));
    }

    public function test_notification_uses_buyer_email_as_reply_to_and_renders_contact_details(): void
    {
        $inquiry = Inquiry::factory()->create([
            'full_name' => 'Dr. Julian Vance',
            'email' => 'julian.vance@batavia.example',
            'phone' => '+31 6 1234 5678',
        ]);

        $notification = new NewInquiryNotification($inquiry);
        $replyTo = $notification->envelope()->replyTo;

        $this->assertSame('julian.vance@batavia.example', $replyTo[0]->address);
        $this->assertSame('Dr. Julian Vance', $replyTo[0]->name);
        $notification->assertSeeInHtml('julian.vance@batavia.example');
        $notification->assertSeeInHtml('+31 6 1234 5678');
        $notification->assertSeeInHtml('Buka Detail di Panel Admin');
        $notification->assertSeeInText('Tindak lanjuti inquiry ini di panel admin:');
    }

    public function test_notification_escapes_buyer_supplied_content(): void
    {
        $inquiry = Inquiry::factory()->create([
            'additional_notes' => '<script>alert("xss")</script>',
        ]);

        $notification = new NewInquiryNotification($inquiry);

        $notification->assertSeeInHtml('&lt;script&gt;', escape: false);
        $notification->assertDontSeeInHtml('<script>alert("xss")</script>', escape: false);
    }
}
