<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Models\Inquiry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan buyer')
                    ->description('Informasi ini berasal dari formulir website dan tidak dapat diubah dari panel admin.')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('company_name')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Email Buyer')
                            ->email()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('phone')
                            ->label('Telepon / WhatsApp Buyer')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('destination_country')
                            ->label('Negara Tujuan')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('commodity_interest')
                            ->label('Komoditas yang Diminati')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('volume_estimate')
                            ->label('Estimasi Volume')
                            ->required()
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('additional_notes')
                            ->label('Catatan Tambahan dari Buyer')
                            ->default(null)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Tindak lanjut')
                    ->description('Perbarui status dan tulis langkah berikutnya agar tim selalu mengetahui perkembangan buyer.')
                    ->schema([
                        Select::make('status')
                            ->label('Status Tindak Lanjut')
                            ->options(['new' => 'Baru', 'contacted' => 'Sudah Dihubungi', 'closed' => 'Selesai'])
                            ->default('new')
                            ->required(),
                        DateTimePicker::make('follow_up_at')
                            ->label('Jadwal Follow-up Berikutnya')
                            ->helperText('Kosongkan jika tidak ada jadwal lanjutan.')
                            ->minutesStep(15)
                            ->seconds(false),
                        Textarea::make('follow_up_notes')
                            ->label('Catatan Tindak Lanjut')
                            ->helperText('Contoh: kirim katalog harga atau hubungi kembali setelah buyer berdiskusi dengan timnya.')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Informasi sistem')
                    ->description('Gunakan bagian ini hanya saat memeriksa kendala notifikasi email.')
                    ->schema([
                        Placeholder::make('email_delivery_status')
                            ->label('Status Notifikasi Email')
                            ->content(fn (?Inquiry $record): string => $record?->emailDeliveryStatusLabel() ?? 'Menunggu dikirim')
                            ->columnSpanFull(),
                        DateTimePicker::make('email_queued_at')
                            ->label('Dijadwalkan Pada')
                            ->disabled()
                            ->dehydrated(false),
                        DateTimePicker::make('email_sent_at')
                            ->label('Notifikasi Terkirim Pada')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('email_failed_reason')
                            ->label('Catatan Teknis Kegagalan Email')
                            ->helperText('Gunakan tombol “Kirim ulang notifikasi email” di bagian atas setelah masalah email teratasi.')
                            ->default(null)
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (?Inquiry $record): bool => $record?->canRetryEmail() ?? false)
                            ->columnSpanFull(),
                        DateTimePicker::make('email_failed_at')
                            ->label('Gagal Pada')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (?Inquiry $record): bool => $record?->canRetryEmail() ?? false),
                        TextInput::make('ip_address')
                            ->label('Alamat IP Pengirim')
                            ->default(null)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
