<?php

namespace App\Filament\Resources\CompanyStats;

use App\Filament\Resources\CompanyStats\Pages\CreateCompanyStat;
use App\Filament\Resources\CompanyStats\Pages\EditCompanyStat;
use App\Filament\Resources\CompanyStats\Pages\ListCompanyStats;
use App\Filament\Resources\CompanyStats\Schemas\CompanyStatForm;
use App\Filament\Resources\CompanyStats\Tables\CompanyStatsTable;
use App\Models\CompanyStat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompanyStatResource extends Resource
{
    protected static ?string $model = CompanyStat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Statistik Perusahaan';

    protected static string|UnitEnum|null $navigationGroup = 'Website & Konten';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Statistik Perusahaan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Statistik Perusahaan';
    }

    public static function form(Schema $schema): Schema
    {
        return CompanyStatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyStatsTable::configure($table);
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
            'index' => ListCompanyStats::route('/'),
            'create' => CreateCompanyStat::route('/create'),
            'edit' => EditCompanyStat::route('/{record}/edit'),
        ];
    }
}
