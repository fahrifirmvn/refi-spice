<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->placeholder('Contoh: Budi Santoso')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->placeholder('Contoh: staff@refi-spice.com')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->label('Peran Akses')
                    ->options(User::roleOptions())
                    ->default(User::ROLE_CONTENT_EDITOR)
                    ->required()
                    ->helperText('Editor Konten dapat mengelola isi website. Super Admin juga dapat mengelola akun pengguna.')
                    ->visible(fn (?User $record): bool => ! $record?->is(auth()->user())),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password saat ini.' : 'Minimal 12 karakter, dengan huruf besar, huruf kecil, angka, dan simbol.')
                    ->rules([Password::min(12)->mixedCase()->numbers()->symbols()])
                    ->minLength(12)
                    ->maxLength(255),
            ]);
    }
}
