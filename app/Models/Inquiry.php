<?php

namespace App\Models;

use Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** @use HasFactory<InquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company_name',
        'destination_country',
        'commodity_interest',
        'volume_estimate',
        'additional_notes',
        'status',
        'follow_up_at',
        'follow_up_notes',
        'email_queued_at',
        'email_sent_at',
        'email_failed_at',
        'email_failed_reason',
        'ip_address',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'email_queued_at' => 'datetime',
        'email_failed_at' => 'datetime',
        'follow_up_at' => 'datetime',
    ];

    public function scopeOverdueFollowUp(Builder $query): Builder
    {
        return $query
            ->where('status', '!=', 'closed')
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<=', now());
    }

    public function followUpStatus(): string
    {
        if ($this->status === 'closed') {
            return 'closed';
        }

        if ($this->follow_up_at === null) {
            return 'unscheduled';
        }

        return $this->follow_up_at->isPast() ? 'overdue' : 'scheduled';
    }

    public function followUpStatusLabel(): string
    {
        return match ($this->followUpStatus()) {
            'closed' => 'Selesai',
            'overdue' => 'Terlambat',
            'scheduled' => 'Terjadwal',
            default => 'Belum dijadwalkan',
        };
    }

    public function emailDeliveryStatus(): string
    {
        if ($this->email_sent_at !== null) {
            return 'sent';
        }

        if (filled($this->email_failed_reason)) {
            return 'failed';
        }

        return 'pending';
    }

    public function emailDeliveryStatusLabel(): string
    {
        return match ($this->emailDeliveryStatus()) {
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            default => 'Menunggu dikirim',
        };
    }

    public function canRetryEmail(): bool
    {
        return $this->emailDeliveryStatus() === 'failed';
    }

    public function markEmailQueued(): void
    {
        $this->forceFill([
            'email_queued_at' => now(),
            'email_sent_at' => null,
            'email_failed_at' => null,
            'email_failed_reason' => null,
        ])->save();
    }

    public function markEmailDelivered(): void
    {
        $this->forceFill([
            'email_queued_at' => $this->email_queued_at ?? now(),
            'email_sent_at' => now(),
            'email_failed_at' => null,
            'email_failed_reason' => null,
        ])->save();
    }

    public function markEmailFailed(string $reason): void
    {
        $this->forceFill([
            'email_queued_at' => $this->email_queued_at ?? now(),
            'email_sent_at' => null,
            'email_failed_at' => now(),
            'email_failed_reason' => $reason,
        ])->save();
    }
}
