<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_guest_cannot_access_user_resource(): void
    {
        $this->get('/admin/users')->assertRedirect('/admin/login');
        $this->get('/admin/users/create')->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_user_list(): void
    {
        $admin = $this->admin();
        $employee = User::factory()->contentEditor()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@refi-spice.com',
        ]);

        $this->actingAs($admin);

        $this->get('/admin/users')->assertSuccessful();

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords([$admin, $employee]);
    }

    public function test_admin_can_create_new_employee_user(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Budi Santoso',
                'email' => 'budi@refi-spice.com',
                'password' => 'ValidPassword!123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'email' => 'budi@refi-spice.com',
            'role' => User::ROLE_CONTENT_EDITOR,
        ]);

        $createdUser = User::where('email', 'budi@refi-spice.com')->firstOrFail();
        $this->assertTrue(Hash::check('ValidPassword!123', $createdUser->password));
    }

    public function test_email_must_be_unique_and_valid(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Duplicate Email User',
                'email' => $admin->email,
                'password' => 'ValidPassword!123',
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'unique']);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Invalid Email User',
                'email' => 'not-an-email',
                'password' => 'ValidPassword!123',
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'email']);
    }

    public function test_new_user_password_must_meet_the_strength_requirements(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Weak Password User',
                'email' => 'weak-password@refi-spice.com',
                'password' => 'weakpassword',
            ])
            ->call('create')
            ->assertHasFormErrors(['password']);
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $this->actingAs($this->admin());

        $targetUser = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'staff@refi-spice.com',
            'password' => Hash::make('original_pass'),
        ]);

        Livewire::test(EditUser::class, ['record' => $targetUser->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Name',
                'email' => 'staff@refi-spice.com',
                'password' => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $refreshed = $targetUser->fresh();
        $this->assertSame('Updated Name', $refreshed->name);
        $this->assertTrue(Hash::check('original_pass', $refreshed->password));
    }

    public function test_admin_can_update_user_password(): void
    {
        $this->actingAs($this->admin());

        $targetUser = User::factory()->create([
            'password' => Hash::make('old_secret_123'),
        ]);

        Livewire::test(EditUser::class, ['record' => $targetUser->getRouteKey()])
            ->fillForm([
                'name' => $targetUser->name,
                'email' => $targetUser->email,
                'password' => 'ValidPassword!123',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $refreshed = $targetUser->fresh();
        $this->assertTrue(Hash::check('ValidPassword!123', $refreshed->password));
    }

    public function test_admin_cannot_delete_their_own_account_on_edit_page(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $admin->getRouteKey()])
            ->assertActionHidden(DeleteAction::class);
    }

    public function test_admin_can_delete_another_user_account_on_edit_page(): void
    {
        $admin = $this->admin();
        $otherUser = User::factory()->contentEditor()->create();
        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $otherUser->getRouteKey()])
            ->assertActionVisible(DeleteAction::class)
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($otherUser);
    }

    public function test_content_editor_cannot_access_user_management(): void
    {
        $editor = User::factory()->contentEditor()->create();

        $this->actingAs($editor);

        $this->get('/admin')->assertSuccessful();
        $this->get('/admin/users')->assertForbidden();
        $this->get('/admin/users/create')->assertForbidden();
    }
}
