<?php

namespace Tests\Feature;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_editor_cannot_manage_user_accounts(): void
    {
        $editor = User::factory()->contentEditor()->create();
        $targetUser = User::factory()->create();
        $policy = app(UserPolicy::class);

        $this->assertFalse($policy->viewAny($editor));
        $this->assertFalse($policy->view($editor, $targetUser));
        $this->assertFalse($policy->create($editor));
        $this->assertFalse($policy->update($editor, $targetUser));
        $this->assertFalse($policy->delete($editor, $targetUser));
    }

    public function test_super_admin_can_manage_another_user_account(): void
    {
        $superAdmin = User::factory()->create();
        $targetUser = User::factory()->contentEditor()->create();
        $policy = app(UserPolicy::class);

        $this->assertTrue($policy->viewAny($superAdmin));
        $this->assertTrue($policy->view($superAdmin, $targetUser));
        $this->assertTrue($policy->create($superAdmin));
        $this->assertTrue($policy->update($superAdmin, $targetUser));
        $this->assertTrue($policy->delete($superAdmin, $targetUser));
    }

    public function test_super_admin_cannot_delete_self_or_the_only_super_admin(): void
    {
        $superAdmin = User::factory()->create();
        $actingSuperAdmin = User::factory()->make();
        $policy = app(UserPolicy::class);

        $this->assertFalse($policy->delete($superAdmin, $superAdmin));
        $this->assertFalse($policy->delete($actingSuperAdmin, $superAdmin));
    }
}
