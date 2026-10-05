<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\PageContents\PageContentResource;
use App\Filament\Resources\PageContents\Pages\EditPageContent;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use App\Models\PageContent;
use App\Models\Product;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_guest_is_redirected_away_from_admin_panel(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_view_admin_dashboard(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertSuccessful();
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        RateLimiter::clear('livewire-rate-limiter:'.sha1(Login::class.'|authenticate|127.0.0.1'));

        $admin = $this->admin();

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->fillForm(['email' => $admin->email, 'password' => 'wrong-password'])
                ->call('authenticate');
        }

        $this->assertGuest();

        // The 6th attempt is rate-limited (PRD 11.2/14.1: "Rate-limit login aktif").
        Livewire::test(Login::class)
            ->fillForm(['email' => $admin->email, 'password' => 'wrong-password'])
            ->call('authenticate');

        $this->assertGuest();
    }

    public function test_inquiry_resource_cannot_be_created_or_deleted(): void
    {
        $this->assertFalse(InquiryResource::canCreate());

        $inquiry = Inquiry::factory()->create();
        $this->assertFalse(InquiryResource::canDelete($inquiry));
        $this->assertFalse(InquiryResource::canDeleteAny());
    }

    public function test_inquiry_status_can_be_updated_via_admin_panel(): void
    {
        $this->actingAs($this->admin());

        $inquiry = Inquiry::factory()->create(['status' => 'new']);

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getRouteKey()])
            ->fillForm(['status' => 'contacted'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('contacted', $inquiry->fresh()->status);
    }

    public function test_admin_can_schedule_an_inquiry_follow_up(): void
    {
        $this->actingAs($this->admin());

        $inquiry = Inquiry::factory()->create(['status' => 'new']);
        $followUpAt = now()->addDay()->startOfMinute();

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getRouteKey()])
            ->fillForm([
                'status' => 'contacted',
                'follow_up_at' => $followUpAt->format('Y-m-d H:i:s'),
                'follow_up_notes' => 'Kirim katalog harga untuk review buyer.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'status' => 'contacted',
            'follow_up_at' => $followUpAt->format('Y-m-d H:i:s'),
            'follow_up_notes' => 'Kirim katalog harga untuk review buyer.',
        ]);
    }

    public function test_admin_can_retry_a_failed_inquiry_email_notification(): void
    {
        Mail::fake();

        $this->actingAs($this->admin());
        $inquiry = Inquiry::factory()->create([
            'email_sent_at' => null,
            'email_failed_reason' => 'SMTP server tidak dapat dihubungi.',
        ]);

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getRouteKey()])
            ->callAction('retryEmailNotification');

        $this->assertNull($inquiry->fresh()->email_failed_reason);
        Mail::assertQueued(NewInquiryNotification::class, fn (NewInquiryNotification $mail): bool => $mail->inquiry->is($inquiry));
    }

    public function test_retry_email_action_is_hidden_after_notification_is_sent(): void
    {
        $this->actingAs($this->admin());
        $inquiry = Inquiry::factory()->create(['email_sent_at' => now()]);

        Livewire::test(EditInquiry::class, ['record' => $inquiry->getRouteKey()])
            ->assertActionHidden('retryEmailNotification');
    }

    public function test_page_content_resource_cannot_be_created_or_deleted(): void
    {
        $this->assertFalse(PageContentResource::canCreate());

        $pageContent = PageContent::factory()->create();
        $this->assertFalse(PageContentResource::canDelete($pageContent));
        $this->assertFalse(PageContentResource::canDeleteAny());
    }

    public function test_page_content_value_can_be_updated_via_admin_panel(): void
    {
        $this->actingAs($this->admin());

        $pageContent = PageContent::factory()->create([
            'section_key' => 'hero',
            'field_key' => 'headline',
            'field_value' => 'Original headline',
        ]);

        Livewire::test(EditPageContent::class, ['record' => $pageContent->getRouteKey()])
            ->fillForm(['field_value' => 'Updated headline'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Updated headline', $pageContent->fresh()->field_value);
        // section_key/field_key are locked and must remain unchanged.
        $this->assertSame('hero', $pageContent->fresh()->section_key);
        $this->assertSame('headline', $pageContent->fresh()->field_key);
    }

    public function test_product_can_be_created_with_nested_specifications(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'slug' => 'lada-hitam-lampung',
                'name' => 'Lada Hitam Lampung',
                'is_featured' => false,
                'is_active' => true,
                'order' => 0,
                'specifications' => [
                    ['spec_key' => 'Kadar Air', 'spec_value' => '≤ 12%'],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::where('slug', 'lada-hitam-lampung')->firstOrFail();
        $this->assertCount(1, $product->specifications);
        $this->assertSame('Kadar Air', $product->specifications->first()->spec_key);
    }
}
