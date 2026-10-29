<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\ChartWidget;

class InquiryTrendChart extends ChartWidget
{
    protected static ?int $sort = -1;

    protected ?string $heading = 'Pesan masuk per minggu';

    protected ?string $description = '8 minggu terakhir';

    protected ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $weeks = collect(range(7, 0))->map(fn (int $weeksAgo) => now()->subWeeks($weeksAgo)->startOfWeek());

        $counts = Inquiry::query()
            ->where('created_at', '>=', $weeks->first())
            ->pluck('created_at')
            ->countBy(fn ($createdAt): string => $createdAt->copy()->startOfWeek()->toDateString());

        return [
            'datasets' => [
                [
                    'label' => 'Pesan masuk',
                    'data' => $weeks->map(fn ($week): int => $counts->get($week->toDateString(), 0))->all(),
                    'backgroundColor' => '#2f5224',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $weeks->map(fn ($week): string => $week->translatedFormat('d M'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
