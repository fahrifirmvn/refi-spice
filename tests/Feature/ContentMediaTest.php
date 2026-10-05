<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Partner;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Tests\TestCase;

class ContentMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_image_generates_required_webp_conversions(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $media = $product
            ->addMedia(UploadedFile::fake()->image('pala-banda.jpg', 1800, 1200))
            ->withResponsiveImages()
            ->toMediaCollection('primary_image');

        $this->assertTrue($media->hasGeneratedConversion('thumbnail'));
        $this->assertTrue($media->hasGeneratedConversion('medium'));
        $this->assertTrue($media->hasGeneratedConversion('large'));
        $this->assertStringEndsWith('.webp', $media->getPathRelativeToRoot('medium'));
        Storage::disk('public')->assertExists($media->getPathRelativeToRoot('medium'));
    }

    public function test_content_image_collections_reject_unsupported_file_types(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $this->expectException(FileUnacceptableForCollection::class);

        $product
            ->addMedia(UploadedFile::fake()->create('dangerous.svg', 10, 'image/svg+xml'))
            ->toMediaCollection('primary_image');
    }

    public function test_products_can_be_filtered_by_their_image_completeness(): void
    {
        Storage::fake('public');

        $productWithoutImage = Product::factory()->create(['primary_image_path' => null]);
        $productWithLegacyImage = Product::factory()->create(['primary_image_path' => 'products/legacy-pala.jpg']);
        $productWithManagedImage = Product::factory()->create();
        $productWithManagedImage
            ->addMedia(UploadedFile::fake()->image('managed-pala.jpg', 1200, 900))
            ->toMediaCollection('primary_image');

        $this->assertSame([$productWithoutImage->id], Product::query()->missingContentImage()->pluck('id')->all());
        $this->assertSame(
            [$productWithLegacyImage->id, $productWithManagedImage->id],
            Product::query()->withContentImage()->orderBy('id')->pluck('id')->all(),
        );
    }

    public function test_public_page_renders_managed_images_and_product_labels(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'name' => 'Pala Banda',
            'origin_region' => 'Maluku',
            'primary_image_alt' => 'Biji pala Banda siap ekspor',
            'is_featured' => true,
            'is_active' => true,
        ]);
        $productMedia = $product
            ->addMedia(UploadedFile::fake()->image('product.jpg', 1200, 900))
            ->toMediaCollection('primary_image');

        $tag = ProductTag::factory()->create([
            'name' => 'Single-Origin',
            'is_active' => true,
        ]);
        $product->tags()->attach($tag);

        $processStep = ProcessStep::factory()->create([
            'title' => 'Dipanen Tangan',
            'image_alt' => 'Petani memanen rempah secara manual',
        ]);
        $processMedia = $processStep
            ->addMedia(UploadedFile::fake()->image('process.jpg', 1200, 900))
            ->toMediaCollection('illustration');

        $partner = Partner::factory()->create(['name' => 'Mitra Nusantara — Jakarta']);
        $partnerMedia = $partner
            ->addMedia(UploadedFile::fake()->image('partner.jpg', 600, 600))
            ->toMediaCollection('logo');

        $certification = Certification::factory()->create([
            'name' => 'Sertifikasi Contoh',
            'is_verified' => true,
        ]);
        $certificationMedia = $certification
            ->addMedia(UploadedFile::fake()->image('certification.jpg', 600, 600))
            ->toMediaCollection('logo');

        $response = $this->get('/');

        $response
            ->assertSee($productMedia->getUrl('medium'), escape: false)
            ->assertSee($processMedia->getUrl('medium'), escape: false)
            ->assertSee($partnerMedia->getUrl('thumbnail'), escape: false)
            ->assertSee($certificationMedia->getUrl('thumbnail'), escape: false)
            ->assertSee('Biji pala Banda siap ekspor')
            ->assertSee('Petani memanen rempah secara manual')
            ->assertSee('Single-Origin');
    }

    public function test_unverified_certifications_and_testimonials_are_hidden_from_the_public_page(): void
    {
        $certification = Certification::factory()->create([
            'name' => 'Sertifikasi Belum Diverifikasi',
            'is_active' => true,
            'is_verified' => false,
        ]);
        $testimonial = Testimonial::factory()->create([
            'quote' => 'Testimoni ini belum boleh dipublikasikan.',
            'is_active' => true,
            'is_verified' => false,
        ]);

        $response = $this->get('/');

        $response
            ->assertDontSee($certification->name)
            ->assertDontSee($testimonial->quote);
    }
}
