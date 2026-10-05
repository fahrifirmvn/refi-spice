<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_CONTENT_EDITOR = 'content_editor';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Every account is manually provisioned. Content editors can manage
     * website content, while only super admins can manage user accounts.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_CONTENT_EDITOR], true);
    }

    /**
     * @return array<string, string>
     */
    public static function roleOptions(): array
    {
        return [
            self::ROLE_CONTENT_EDITOR => 'Editor Konten',
            self::ROLE_SUPER_ADMIN => 'Super Admin',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isLastSuperAdmin(): bool
    {
        return $this->isSuperAdmin()
            && static::query()->where('role', self::ROLE_SUPER_ADMIN)->count() === 1;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
