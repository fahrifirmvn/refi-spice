<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn (User $record): bool => $record->id === auth()->id()),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (
            isset($data['role'])
            && $this->record->isLastSuperAdmin()
            && $data['role'] !== User::ROLE_SUPER_ADMIN
        ) {
            throw ValidationException::withMessages([
                'role' => 'Harus ada setidaknya satu Super Admin aktif.',
            ]);
        }

        return $data;
    }
}
