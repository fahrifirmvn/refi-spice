<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use ReflectionMethod;
use Tests\TestCase;

class InquiryHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_link_normalizes_local_and_international_numbers(): void
    {
        $this->assertStringStartsWith('https://wa.me/628123456789?', Inquiry::factory()->make(['phone' => '0812-3456-789'])->whatsappUrl());
        $this->assertStringStartsWith('https://wa.me/4915112345678?', Inquiry::factory()->make(['phone' => '+49 151 1234 5678'])->whatsappUrl());
        $this->assertNull(Inquiry::factory()->make(['phone' => null])->whatsappUrl());
        $this->assertNull(Inquiry::factory()->make(['phone' => '123'])->whatsappUrl());
    }

    public function test_reply_links_appear_on_the_inquiry_page(): void
    {
        $this->actingAs(User::factory()->create());
        $inquiry = Inquiry::factory()->create(['email' => 'buyer@example.com', 'phone' => '081234567890']);

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getKey()])
            ->assertActionVisible('replyByEmail')
            ->assertActionVisible('chatWhatsapp');
    }

    public function test_status_and_follow_up_can_be_changed_with_one_click(): void
    {
        $this->actingAs(User::factory()->create());
        $inquiry = Inquiry::factory()->create();

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getKey()])
            ->callAction('markContacted')
            ->callAction('followUpTomorrow');

        $inquiry->refresh();

        $this->assertSame('contacted', $inquiry->status);
        $this->assertTrue($inquiry->follow_up_at->isTomorrow());
    }

    public function test_csv_export_downloads_filtered_rows_and_neutralizes_formulas(): void
    {
        $this->actingAs(User::factory()->create());
        Inquiry::factory()->create(['full_name' => '=HYPERLINK("http://evil")', 'destination_country' => 'Jerman']);
        Inquiry::factory()->create(['destination_country' => 'Jepang']);

        Livewire::test(ListInquiries::class)
            ->filterTable('destination_country', 'Jerman')
            ->callAction('exportCsv')
            ->assertFileDownloaded();

        $csvSafe = new ReflectionMethod(ListInquiries::class, 'csvSafe');

        $this->assertSame("'=HYPERLINK(\"http://evil\")", $csvSafe->invoke(null, '=HYPERLINK("http://evil")'));
        $this->assertSame('Jerman', $csvSafe->invoke(null, 'Jerman'));
    }
}
