<?php

namespace App\Filament\Resources\ProcessSteps\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProcessStepForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('step_number')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('category_label')
                    ->default(null),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->directory('process-steps')
                    ->default(null),
                TextInput::make('image_caption')
                    ->default(null),
                TextInput::make('data_label')
                    ->default(null),
                TextInput::make('data_value')
                    ->default(null),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
