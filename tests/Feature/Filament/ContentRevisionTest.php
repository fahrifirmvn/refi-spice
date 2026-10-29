<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ContentRevisions\ContentRevisionResource;
use App\Filament\Resources\ContentRevisions\Pages\ListContentRevisions;
use App\Models\ContentRevision;
use App\Models\PageContent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContentRevisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_changes_by_a_logged_in_user_are_recorded_with_old_and_new_values(): void
    {
        $editor = User::factory()->create(['role' => User::ROLE_CONTENT_EDITOR]);
        $product = Product::factory()->create(['name' => 'Pala Lama']);

        $this->actingAs($editor);
        $product->update(['name' => 'Pala Baru']);

        $revision = ContentRevision::where('event', 'updated')->sole();

        $this->assertSame($editor->id, $revision->user_id);
        $this->assertSame(['name' => 'Pala Lama'], $revision->old_values);
        $this->assertSame(['name' => 'Pala Baru'], $revision->new_values);
    }

    public function test_system_changes_and_reordering_are_not_recorded(): void
    {
        $product = Product::factory()->create();
        $product->update(['name' => 'Diubah seeder']);

        $this->actingAs(User::factory()->create());
        $product->update(['order' => 9]);

        $this->assertSame(0, ContentRevision::count());
    }

    public function test_a_change_can_be_restored_from_the_history_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_CONTENT_EDITOR]));
        $content = PageContent::factory()->create(['field_value' => 'Judul lama']);
        $content->update(['field_value' => 'Judul salah ketik']);

        $revision = ContentRevision::where('event', 'updated')->sole();

        Livewire::test(ListContentRevisions::class)
            ->assertCanSeeTableRecords([$revision])
            ->callTableAction('restore', $revision);

        $this->assertSame('Judul lama', $content->fresh()->field_value);
    }

    public function test_history_page_is_available_to_content_editors(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_CONTENT_EDITOR]));

        $this->get(ContentRevisionResource::getUrl('index'))->assertSuccessful();
    }
}
