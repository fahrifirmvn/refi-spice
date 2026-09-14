<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or reset) the Filament admin account used to log in to /admin.
     *
     * Idempotent via updateOrCreate() keyed on email, so it survives
     * `migrate:fresh` + `db:seed` cycles without leaving the admin panel
     * without a usable account. Credentials are configurable via env for
     * local/staging convenience but default to a clearly-local placeholder —
     * change ADMIN_PASSWORD before seeding any shared/production environment.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@refi-spice.local')],
            [
                'name' => 'Refi Spice Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );
    }
}
