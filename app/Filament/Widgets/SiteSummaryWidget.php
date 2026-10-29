<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteSummaryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    protected ?string $pollingInterval = '60s';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $newCount = Inquiry::query()->where('status', 'new')->count();
        $overdueCount = Inquiry::query()->overdueFollowUp()->count();
        $failedEmailCount = Inquiry::query()->whereNotNull('email_failed_reason')->whereNull('email_sent_at')->count();
        $recentCount = Inquiry::query()->where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('Pesan Baru', $newCount)
                ->description($newCount > 0 ? 'Belum ditindaklanjuti' : 'Semua sudah ditangani')
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->color($newCount > 0 ? 'warning' : 'success')
                ->url(InquiryResource::getUrl()),
            Stat::make('Follow-up Terlambat', $overdueCount)
                ->description($overdueCount > 0 ? 'Jadwal sudah lewat, hubungi buyer' : 'Tidak ada yang terlambat')
                ->icon(Heroicon::OutlinedClock)
                ->color($overdueCount > 0 ? 'danger' : 'success')
                ->url(InquiryResource::getUrl(parameters: ['filters' => ['follow_up' => ['value' => 'overdue']]])),
            Stat::make('Email Notifikasi Gagal', $failedEmailCount)
                ->description($failedEmailCount > 0 ? 'Buka pesan lalu kirim ulang' : 'Semua email terkirim')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->color($failedEmailCount > 0 ? 'danger' : 'success')
                ->url(InquiryResource::getUrl(parameters: ['filters' => ['email_delivery' => ['value' => 'failed']]])),
            Stat::make('Inquiry 30 Hari Terakhir', $recentCount)
                ->description('Total pesan dari website')
                ->icon(Heroicon::OutlinedChartBar)
                ->color('info')
                ->chart($this->dailyCounts(14)),
        ];
    }

    /**
     * @return array<int, int>
     */
    private function dailyCounts(int $days): array
    {
        $counts = Inquiry::query()
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->pluck('created_at')
            ->countBy(fn ($createdAt): string => $createdAt->toDateString());

        return collect(range($days - 1, 0))
            ->map(fn (int $daysAgo): int => $counts->get(now()->subDays($daysAgo)->toDateString(), 0))
            ->all();
    }
}
