<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('latin_name')
                    ->default(null),
                TextInput::make('lot_number')
                    ->default(null),
                TextInput::make('origin_region')
                    ->default(null),
                TextInput::make('origin_label')
                    ->default(null),
                Textarea::make('sensory_profile')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('export_readiness')
                    ->default(null),
                FileUpload::make('primary_image_path')
                    ->image()
                    ->directory('products'),
                Toggle::make('is_featured')
                    ->required()
                    ->default(false),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Repeater::make('specifications')
                    ->relationship()
                    ->schema([
                        TextInput::make('spec_key')
                            ->label('Nama Spesifikasi')
                            ->required(),
                        TextInput::make('spec_value')
                            ->label('Nilai')
                            ->required(),
                    ])
                    ->orderColumn('order')
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Spesifikasi')
                    ->columns(2)
                    ->columnSpanFull()
                    ->defaultItems(0),
            ]);
    }
}
