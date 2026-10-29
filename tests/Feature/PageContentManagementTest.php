<?php

namespace Tests\Feature;

use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Tests\TestCase;

class PageContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_the_uploaded_open_graph_image(): void
    {
        Storage::fake('public');

        $openGraphContent = PageContent::factory()->create([
            'section_key' => 'seo',
            'field_key' => 'og_image_path',
            'field_value' => '',
        ]);
        $media = $openGraphContent
            ->addMedia(UploadedFile::fake()->image('refi-spice-og.png', 1200, 630))
            ->toMediaCollection(PageContent::OPEN_GRAPH_IMAGE_COLLECTION);

        $this->assertTrue($media->hasGeneratedConversion('open_graph'));

        $response = $this->get('/');

        $response->assertSee($media->getUrl('open_graph'), escape: false);
        $response->assertSee('<meta property="og:image"', escape: false);
    }

    public function test_homepage_falls_back_to_the_bundled_logo_and_social_image(): void
    {
        $response = $this->get('/');

        $response->assertSee(asset('images/brand/refi-logo-192.webp'), escape: false);
        $response->assertSee('<meta property="og:image" content="'.asset('images/brand/og-default.jpg').'">', escape: false);
        $response->assertSee('"logo":"'.asset('images/brand/refi-logo-512.webp').'"', escape: false);
    }

    public function test_homepage_uses_the_uploaded_logo(): void
    {
        Storage::fake('public');

        $logoContent = PageContent::factory()->create([
            'section_key' => 'brand',
            'field_key' => 'logo_path',
            'field_value' => '',
        ]);
        $media = $logoContent
            ->addMedia(UploadedFile::fake()->image('logo.png', 400, 400))
            ->toMediaCollection(PageContent::LOGO_COLLECTION);

        $this->assertTrue($media->hasGeneratedConversion('logo'));

        $this->get('/')
            ->assertSee('src="'.$media->getUrl('logo').'"', escape: false)
            ->assertSee('"logo":"'.$media->getUrl('logo').'"', escape: false);
    }

    public function test_open_graph_collection_rejects_unsupported_files(): void
    {
        Storage::fake('public');

        $openGraphContent = PageContent::factory()->create([
            'section_key' => 'seo',
            'field_key' => 'og_image_path',
        ]);

        $this->expectException(FileUnacceptableForCollection::class);

        $openGraphContent
            ->addMedia(UploadedFile::fake()->create('social-preview.svg', 10, 'image/svg+xml'))
            ->toMediaCollection(PageContent::OPEN_GRAPH_IMAGE_COLLECTION);
    }

    public function test_json_ld_encodes_admin_managed_contact_content_safely(): void
    {
        $unsafeContent = '</script><script>window.alert("xss")</script>';

        PageContent::factory()->create([
            'section_key' => 'contact',
            'field_key' => 'email',
            'field_value' => $unsafeContent,
        ]);

        $response = $this->get('/');

        $response->assertDontSee($unsafeContent, false);
        $response->assertSee('\\u003C/script\\u003E\\u003Cscript\\u003E', false);
    }
}
