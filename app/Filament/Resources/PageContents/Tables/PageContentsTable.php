<?php

namespace App\Filament\Resources\PageContents\Tables;

use App\Filament\Support\PageContentCatalog;
use App\Models\PageContent;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PageContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section_key')
                    ->label('Bagian')
                    ->formatStateUsing(fn (string $state): string => PageContentCatalog::sectionLabel($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field_key')
                    ->label('Nama Field')
                    ->formatStateUsing(fn (string $state, PageContent $record): string => PageContentCatalog::fieldLabel($record->section_key, $state))
                    ->description(fn (PageContent $record): ?string => PageContentCatalog::fieldHelper($record->section_key, $record->field_key))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field_value')
                    ->label('Isi Saat Ini')
                    ->limit(60)
                    ->placeholder('Gambar dikelola melalui upload')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('section_key')
            ->defaultGroup(
                Group::make('section_key')
                    ->label('Bagian')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (PageContent $record): string => PageContentCatalog::sectionLabel($record->section_key))
            )
            ->filters([
                SelectFilter::make('section_key')
                    ->label('Tampilkan Bagian')
                    ->options(PageContentCatalog::sectionOptions()),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah isi'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
