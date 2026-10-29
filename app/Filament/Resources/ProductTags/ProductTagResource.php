<?php

namespace App\Filament\Resources\ProductTags;

use App\Filament\Resources\ProductTags\Pages\ManageProductTags;
use App\Filament\Resources\ProductTags\Schemas\ProductTagForm;
use App\Filament\Resources\ProductTags\Tables\ProductTagsTable;
use App\Models\ProductTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Label Produk';

    protected static string|UnitEnum|null $navigationGroup = 'Produk & Kepercayaan';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getModelLabel(): string
    {
        return 'Label Produk';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Label Produk';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductTagsTable::configure($table);
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
            'index' => ManageProductTags::route('/'),
        ];
    }
}
