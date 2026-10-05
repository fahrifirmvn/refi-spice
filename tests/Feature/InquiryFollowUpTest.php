<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryFollowUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_open_inquiry_with_past_schedule_is_marked_overdue(): void
    {
        $this->travelTo('2026-09-15 10:00:00');

        $inquiry = Inquiry::factory()->create([
            'status' => 'contacted',
            'follow_up_at' => '2026-09-15 09:00:00',
        ]);

        $this->assertSame('overdue', $inquiry->followUpStatus());
        $this->assertSame('Terlambat', $inquiry->followUpStatusLabel());
    }

    public function test_overdue_follow_up_scope_excludes_closed_and_future_inquiries(): void
    {
        $this->travelTo('2026-09-15 10:00:00');

        $overdueInquiry = Inquiry::factory()->create([
            'status' => 'contacted',
            'follow_up_at' => '2026-09-15 09:00:00',
        ]);
        Inquiry::factory()->create([
            'status' => 'closed',
            'follow_up_at' => '2026-09-15 09:00:00',
        ]);
        Inquiry::factory()->create([
            'status' => 'contacted',
            'follow_up_at' => '2026-09-15 11:00:00',
        ]);

        $this->assertSame([$overdueInquiry->id], Inquiry::query()->overdueFollowUp()->pluck('id')->all());
    }
}
