<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Models\Inquiry;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Buyer')
                    ->description(fn (Inquiry $record): string => collect([$record->company_name, $record->destination_country])
                        ->filter()
                        ->implode(' · '))
                    ->searchable(),
                TextColumn::make('commodity_interest')
                    ->label('Kebutuhan')
                    ->description(fn (Inquiry $record): ?string => $record->volume_estimate)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Baru',
                        'contacted' => 'Sudah Dihubungi',
                        'closed' => 'Selesai',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('follow_up_at')
                    ->label('Follow-up Berikutnya')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum dijadwalkan')
                    ->description(fn (Inquiry $record): string => $record->followUpStatusLabel())
                    ->badge()
                    ->color(fn (Inquiry $record): string => match ($record->followUpStatus()) {
                        'overdue' => 'danger',
                        'scheduled' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('email_delivery_status')
                    ->label('Notifikasi Email')
                    ->state(fn (Inquiry $record): string => $record->emailDeliveryStatusLabel())
                    ->description(fn (Inquiry $record): string => match ($record->emailDeliveryStatus()) {
                        'sent' => 'Notifikasi sudah diterima oleh mailer.',
                        'failed' => 'Gagal terkirim. Buka detail untuk mengirim ulang.',
                        default => 'Sedang menunggu diproses oleh sistem email.',
                    })
                    ->badge()
                    ->color(fn (Inquiry $record): string => match ($record->emailDeliveryStatus()) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Email Buyer')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Belum tersedia')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Telepon / WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Belum tersedia')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email_sent_at')
                    ->label('Terkirim Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(['new' => 'Baru', 'contacted' => 'Sudah Dihubungi', 'closed' => 'Selesai']),
                SelectFilter::make('email_delivery')
                    ->label('Notifikasi Email')
                    ->options([
                        'pending' => 'Menunggu dikirim',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'pending' => $query->whereNull('email_sent_at')->whereNull('email_failed_reason'),
                            'sent' => $query->whereNotNull('email_sent_at'),
                            'failed' => $query->whereNotNull('email_failed_reason'),
                            default => $query,
                        };
                    }),
                SelectFilter::make('follow_up')
                    ->label('Tindak Lanjut')
                    ->options([
                        'overdue' => 'Terlambat',
                        'scheduled' => 'Terjadwal',
                        'unscheduled' => 'Belum dijadwalkan',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'overdue' => $query->overdueFollowUp(),
                            'scheduled' => $query
                                ->where('status', '!=', 'closed')
                                ->whereNotNull('follow_up_at')
                                ->where('follow_up_at', '>', now()),
                            'unscheduled' => $query
                                ->where('status', '!=', 'closed')
                                ->whereNull('follow_up_at'),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                EditAction::make()->label('Tinjau'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
