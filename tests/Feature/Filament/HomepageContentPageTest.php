<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\EditHomepageContent;
use App\Filament\Resources\Milestones\MilestoneResource;
use App\Models\PageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageContentPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_update_several_sections_in_one_save(): void
    {
        $this->actingAs(User::factory()->create());

        PageContent::factory()->create(['section_key' => 'hero', 'field_key' => 'headline', 'field_value' => 'Lama']);
        PageContent::factory()->create(['section_key' => 'contact', 'field_key' => 'email', 'field_value' => 'lama@example.com']);

        Livewire::test(EditHomepageContent::class)
            ->assertFormSet(['fields.hero.headline' => 'Lama'])
            ->fillForm([
                'fields.hero.headline' => 'Warisan Rempah',
                'fields.contact.email' => 'baru@example.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Warisan Rempah', PageContent::get('hero', 'headline'));
        $this->assertSame('baru@example.com', PageContent::get('contact', 'email'));
    }

    public function test_logo_upload_is_stored_on_the_brand_record(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(EditHomepageContent::class)
            ->fillForm(['logo' => UploadedFile::fake()->image('logo.png', 400, 400)])
            ->call('save')
            ->assertHasNoFormErrors();

        $brandLogo = PageContent::query()->where('section_key', 'brand')->where('field_key', 'logo_path')->firstOrFail();

        $this->assertNotNull($brandLogo->logoUrl());
    }

    public function test_story_photo_can_be_replaced_and_is_shown_on_the_homepage(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
        PageContent::factory()->create(['section_key' => 'cerita_kami', 'field_key' => 'archive_photo_path', 'field_value' => '']);

        Livewire::test(EditHomepageContent::class)
            ->fillForm(['fields.cerita_kami.archive_photo_path' => UploadedFile::fake()->image('kebun.jpg', 800, 1000)])
            ->call('save')
            ->assertHasNoFormErrors();

        $path = PageContent::get('cerita_kami', 'archive_photo_path');

        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        $this->get(route('home'))->assertSee(Storage::disk('public')->url($path), escape: false);
    }

    public function test_story_badge_and_signature_texts_come_from_the_database(): void
    {
        PageContent::factory()->create(['section_key' => 'cerita_kami', 'field_key' => 'photo_badge_title', 'field_value' => 'Lencana Uji 123']);
        PageContent::query()->where('section_key', 'cerita_kami')->where('field_key', 'signature_name')->update(['field_value' => 'PT Nama Baru']);

        $this->get(route('home'))
            ->assertSee('Lencana Uji 123')
            ->assertSee('PT Nama Baru');
    }

    public function test_related_lists_are_reachable_from_the_homepage_tabs_and_link_back(): void
    {
        $this->actingAs(User::factory()->create());
        PageContent::factory()->create(['section_key' => 'kemitraan', 'field_key' => 'headline']);

        $this->get(EditHomepageContent::getUrl())
            ->assertOk()
            ->assertSee('Kelola Tonggak Sejarah')
            ->assertSee('Kelola Testimoni Klien');

        $this->get(MilestoneResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Kembali ke Isi Halaman Depan');
    }
}
