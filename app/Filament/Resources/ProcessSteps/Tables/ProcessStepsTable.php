<?php

namespace App\Filament\Resources\ProcessSteps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProcessStepsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('step_number')
                    ->label('Nomor Tahap')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul Tahap')
                    ->searchable(),
                TextColumn::make('category_label')
                    ->label('Label Kategori')
                    ->searchable(),
                ImageColumn::make('image_path')
                    ->label('Foto'),
                TextColumn::make('image_caption')
                    ->label('Keterangan Foto')
                    ->searchable(),
                TextColumn::make('data_label')
                    ->label('Label Angka')
                    ->searchable(),
                TextColumn::make('data_value')
                    ->label('Nilai Angka')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
