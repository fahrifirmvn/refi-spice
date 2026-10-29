<?php

namespace App\Filament\Resources\Milestones\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MilestoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->label('Tahun')
                    ->required(),
                TextInput::make('title')
                    ->label('Judul Tonggak Sejarah')
                    ->required(),
                Textarea::make('description')
                    ->label('Penjelasan Singkat')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Tampilkan di Website')
                    ->helperText('Jika nonaktif, tonggak sejarah ini disembunyikan dari halaman publik.')
                    ->default(true),
            ]);
    }
}
