<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class InquiriesNeedingActionWidget extends TableWidget
{
    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pesan yang perlu ditindaklanjuti';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inquiry::query()
                ->where('status', '!=', 'closed')
                ->orderByRaw(
                    "CASE WHEN follow_up_at IS NOT NULL AND follow_up_at <= ? THEN 0 WHEN status = 'new' THEN 1 ELSE 2 END",
                    [now()],
                )
                ->latest()
                ->limit(8))
            ->paginated(false)
            ->emptyStateHeading('Tidak ada pesan yang menunggu')
            ->emptyStateDescription('Semua pesan sudah selesai ditangani.')
            ->emptyStateIcon(Heroicon::OutlinedCheckCircle)
            ->recordUrl(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('full_name')
                    ->label('Buyer')
                    ->description(fn (Inquiry $record): string => collect([$record->company_name, $record->destination_country])->filter()->implode(' · ')),
                TextColumn::make('commodity_interest')
                    ->label('Kebutuhan')
                    ->description(fn (Inquiry $record): ?string => $record->volume_estimate),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Baru',
                        'contacted' => 'Sudah Dihubungi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => $state === 'new' ? 'warning' : 'info'),
                TextColumn::make('follow_up_status')
                    ->label('Follow-up')
                    ->state(fn (Inquiry $record): string => $record->followUpStatusLabel())
                    ->badge()
                    ->color(fn (Inquiry $record): string => match ($record->followUpStatus()) {
                        'overdue' => 'danger',
                        'scheduled' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since(),
            ])
            ->recordActions([
                Action::make('markContacted')
                    ->label('Tandai dihubungi')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (Inquiry $record): bool => $record->status === 'new')
                    ->action(function (Inquiry $record): void {
                        $record->update(['status' => 'contacted']);

                        Notification::make()->title('Status diubah menjadi Sudah Dihubungi')->success()->send();
                    }),
            ]);
    }
}
