<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\ProcessSteps\ProcessStepResource;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Certification;
use App\Models\Partner;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SimplifiedContentFormsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_product_needs_only_a_name_and_gets_slug_and_order_automatically(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm(['name' => 'Pala Banda Super'])
            ->call('create')
            ->assertHasNoFormErrors();

        Livewire::test(CreateProduct::class)
            ->fillForm(['name' => 'Pala Banda Super'])
            ->call('create')
            ->assertHasNoFormErrors();

        $products = Product::orderBy('id')->get();

        $this->assertSame(['pala-banda-super', 'pala-banda-super-2'], $products->pluck('slug')->all());
        $this->assertSame(1, $products[0]->order);
        $this->assertSame(2, $products[1]->order);
    }

    public function test_product_name_is_still_required(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm(['name' => ''])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_new_partner_is_appended_after_existing_ones(): void
    {
        Partner::factory()->create(['order' => 5]);

        Livewire::test(CreatePartner::class)
            ->fillForm(['name' => 'Nordic Spice Co.'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(6, Partner::where('name', 'Nordic Spice Co.')->value('order'));
    }

    public function test_edit_pages_with_image_uploads_render(): void
    {
        $product = Product::factory()->create();
        $partner = Partner::factory()->create();
        $step = ProcessStep::factory()->create();
        $certification = Certification::factory()->create();

        foreach ([
            ProductResource::getUrl('edit', ['record' => $product]),
            PartnerResource::getUrl('edit', ['record' => $partner]),
            ProcessStepResource::getUrl('edit', ['record' => $step]),
            CertificationResource::getUrl('edit', ['record' => $certification]),
            PartnerResource::getUrl('index'),
            ProcessStepResource::getUrl('index'),
            CertificationResource::getUrl('index'),
        ] as $url) {
            $this->get($url)->assertSuccessful();
        }
    }
}
