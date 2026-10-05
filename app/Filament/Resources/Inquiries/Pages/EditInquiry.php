<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class EditInquiry extends EditRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('retryEmailNotification')
                ->label('Kirim ulang notifikasi email')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Kirim ulang notifikasi email?')
                ->modalDescription('Email akan dijadwalkan ulang ke tim ekspor. Gunakan setelah pengaturan email atau koneksi mailer telah diperbaiki.')
                ->visible(fn (Inquiry $record): bool => $record->canRetryEmail())
                ->action(function (Inquiry $record): void {
                    $record->markEmailQueued();

                    try {
                        Mail::to(config('mail.sales_notification_address'))
                            ->queue((new NewInquiryNotification($record))->afterCommit());
                    } catch (Throwable $exception) {
                        report($exception);

                        $record->markEmailFailed(Str::limit($exception->getMessage(), 2000));

                        Notification::make()
                            ->title('Notifikasi email belum dapat dijadwalkan ulang')
                            ->body('Periksa pengaturan email, lalu coba kembali.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Notifikasi email dijadwalkan ulang')
                        ->body('Status akan berubah menjadi terkirim setelah mailer menerima email.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
