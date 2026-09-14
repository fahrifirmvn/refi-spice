<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('company_name')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('destination_country')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('commodity_interest')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('volume_estimate')
                    ->required()
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('additional_notes')
                    ->default(null)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'])
                    ->default('new')
                    ->required(),
                DateTimePicker::make('email_sent_at')
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('email_failed_reason')
                    ->default(null)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->default(null)
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
