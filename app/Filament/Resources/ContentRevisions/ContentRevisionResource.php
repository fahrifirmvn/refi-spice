<?php

namespace App\Filament\Resources\ContentRevisions;

use App\Filament\Resources\ContentRevisions\Pages\ListContentRevisions;
use App\Models\ContentRevision;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ContentRevisionResource extends Resource
{
    protected static ?string $model = ContentRevision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Riwayat Perubahan';

    protected static string|UnitEnum|null $navigationGroup = 'Akun & Pengaturan';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Perubahan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Riwayat Perubahan';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        $events = [
            ContentRevision::EVENT_CREATED => 'Dibuat',
            ContentRevision::EVENT_UPDATED => 'Diubah',
            ContentRevision::EVENT_DELETED => 'Dihapus',
        ];

        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Oleh')
                    ->placeholder('Sistem'),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $events[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        ContentRevision::EVENT_CREATED => 'success',
                        ContentRevision::EVENT_DELETED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('subject_label')
                    ->label('Konten')
                    ->description(fn (ContentRevision $record): string => $record->typeLabel())
                    ->searchable()
                    ->wrap(),
                TextColumn::make('summary')
                    ->label('Perubahan')
                    ->state(fn (ContentRevision $record): array => self::summaryLines($record))
                    ->listWithLineBreaks()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('event')->label('Aksi')->options($events),
                SelectFilter::make('subject_type')->label('Jenis Konten')->options(ContentRevision::TYPE_LABELS),
                SelectFilter::make('user_id')->label('Oleh')->options(fn (): array => User::query()->pluck('name', 'id')->all()),
            ])
            ->recordActions([
                Action::make('restore')
                    ->label('Kembalikan')
                    ->icon(Heroicon::OutlinedArrowUturnLeft)
                    ->requiresConfirmation()
                    ->modalHeading('Kembalikan ke isi sebelumnya?')
                    ->modalDescription('Isi konten akan dikembalikan ke nilai sebelum perubahan ini. Tindakan ini juga dicatat di riwayat.')
                    ->visible(fn (ContentRevision $record): bool => $record->canBeRestored())
                    ->action(function (ContentRevision $record): void {
                        $record->restore();

                        Notification::make()->title('Isi konten dikembalikan')->success()->send();
                    }),
            ]);
    }

    /** @return list<string> */
    private static function summaryLines(ContentRevision $revision): array
    {
        $old = $revision->old_values ?? [];

        return collect($revision->new_values ?? [])
            ->map(fn ($new, string $field): string => Str::headline($field).': '
                .Str::limit((string) ($old[$field] ?? '—'), 50).' → '.Str::limit((string) $new, 50))
            ->values()
            ->all();
    }

    public static function getPages(): array
    {
        return ['index' => ListContentRevisions::route('/')];
    }
}
