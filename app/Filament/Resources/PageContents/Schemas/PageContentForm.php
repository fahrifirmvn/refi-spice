<?php

namespace App\Filament\Resources\PageContents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PageContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('section_key')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('field_key')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('field_value')
                    ->rows(4)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
