<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
            Action::make('replyByEmail')
                ->label('Balas via Email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (Inquiry $record): ?string => $record->mailtoUrl())
                ->visible(fn (Inquiry $record): bool => $record->mailtoUrl() !== null),
            Action::make('chatWhatsapp')
                ->label('Chat WhatsApp')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('success')
                ->url(fn (Inquiry $record): ?string => $record->whatsappUrl(), shouldOpenInNewTab: true)
                ->visible(fn (Inquiry $record): bool => $record->whatsappUrl() !== null),
            Action::make('markContacted')
                ->label('Tandai Sudah Dihubungi')
                ->icon(Heroicon::OutlinedCheck)
                ->color('gray')
                ->visible(fn (Inquiry $record): bool => $record->status === 'new')
                ->action(function (Inquiry $record): void {
                    $record->update(['status' => 'contacted']);
                    $this->refreshFormData(['status']);

                    Notification::make()->title('Status diubah menjadi Sudah Dihubungi')->success()->send();
                }),
            ActionGroup::make([
                Action::make('followUpTomorrow')->label('Besok')->action(fn (Inquiry $record) => $this->scheduleFollowUp($record, 1)),
                Action::make('followUpThreeDays')->label('3 hari lagi')->action(fn (Inquiry $record) => $this->scheduleFollowUp($record, 3)),
                Action::make('followUpNextWeek')->label('Minggu depan')->action(fn (Inquiry $record) => $this->scheduleFollowUp($record, 7)),
            ])
                ->label('Jadwalkan Follow-up')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->button()
                ->color('gray')
                ->visible(fn (): bool => $this->getRecord()->status !== 'closed'),
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

    private function scheduleFollowUp(Inquiry $record, int $days): void
    {
        $record->update(['follow_up_at' => now()->addDays($days)->setTime(9, 0)]);
        $this->refreshFormData(['follow_up_at']);

        Notification::make()->title('Follow-up dijadwalkan')->success()->send();
    }
}
