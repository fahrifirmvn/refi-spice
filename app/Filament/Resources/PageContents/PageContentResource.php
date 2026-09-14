<?php

namespace App\Filament\Resources\PageContents;

use App\Filament\Resources\PageContents\Pages\CreatePageContent;
use App\Filament\Resources\PageContents\Pages\EditPageContent;
use App\Filament\Resources\PageContents\Pages\ListPageContents;
use App\Filament\Resources\PageContents\Schemas\PageContentForm;
use App\Filament\Resources\PageContents\Tables\PageContentsTable;
use App\Models\PageContent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PageContentResource extends Resource
{
    protected static ?string $model = PageContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PageContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageContents::route('/'),
            'create' => CreatePageContent::route('/create'),
            'edit' => EditPageContent::route('/{record}/edit'),
        ];
    }

    /**
     * Page content rows are seeded fixtures (section_key/field_key are wired
     * into the public views), so admins may only edit field_value — not
     * create new rows or delete existing ones.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
