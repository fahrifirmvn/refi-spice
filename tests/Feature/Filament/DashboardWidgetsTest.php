<?php

namespace Tests\Feature\Filament;

use App\Filament\Widgets\ContentHealthWidget;
use App\Filament\Widgets\InquiriesNeedingActionWidget;
use App\Filament\Widgets\SiteSummaryWidget;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_every_widget_for_an_editor(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin')
            ->assertSuccessful()
            ->assertSeeLivewire(SiteSummaryWidget::class)
            ->assertSeeLivewire(InquiriesNeedingActionWidget::class)
            ->assertSeeLivewire(ContentHealthWidget::class);
    }

    public function test_action_widget_lists_open_inquiries_and_can_mark_contacted(): void
    {
        $this->actingAs(User::factory()->create());

        $open = Inquiry::factory()->create(['status' => 'new']);
        $closed = Inquiry::factory()->create(['status' => 'closed']);

        Livewire::test(InquiriesNeedingActionWidget::class)
            ->assertCanSeeTableRecords([$open])
            ->assertCanNotSeeTableRecords([$closed])
            ->callTableAction('markContacted', $open);

        $this->assertSame('contacted', $open->refresh()->status);
    }

    public function test_content_health_widget_flags_active_products_without_photo(): void
    {
        $this->actingAs(User::factory()->create());

        Product::factory()->create(['is_active' => true, 'primary_image_path' => null]);

        Livewire::test(ContentHealthWidget::class)->assertSee('Produk aktif belum punya foto');
    }
}
