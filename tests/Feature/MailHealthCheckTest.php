<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailHealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_a_recent_failure_for_an_inquiry_created_before_the_reporting_window(): void
    {
        $this->travelTo('2026-09-15 10:00:00');

        $inquiry = Inquiry::factory()->create([
            'created_at' => '2026-08-15 10:00:00',
            'updated_at' => '2026-08-15 10:00:00',
        ]);
        $inquiry->markEmailQueued();
        $inquiry->markEmailFailed('SMTP sedang tidak dapat dihubungi.');

        $this->artisan('mail:health-check', ['--days' => 7])
            ->expectsOutput('Inquiry email delivery — last 7 day(s): 1/1 failed (100%).')
            ->expectsOutput('Failure ratio is 10% or higher — consider switching to an SMTP relay (see PRD Section 12.4).')
            ->assertExitCode(0);
    }
}
