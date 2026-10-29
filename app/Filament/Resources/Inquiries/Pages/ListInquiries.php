<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Ekspor CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->action(fn () => $this->exportCsv()),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'new' => Tab::make('Baru')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'new'))
                ->badge(Inquiry::query()->where('status', 'new')->count() ?: null)
                ->badgeColor('warning'),
            'overdue' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->overdueFollowUp())
                ->badge(Inquiry::query()->overdueFollowUp()->count() ?: null)
                ->badgeColor('danger'),
            'contacted' => Tab::make('Sudah Dihubungi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'contacted')),
            'closed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'closed')),
        ];
    }

    /** Mengekspor hasil yang sedang difilter/dicari di tabel. */
    private function exportCsv(): StreamedResponse
    {
        $query = $this->getFilteredSortedTableQuery();
        $columns = [
            'created_at' => 'Masuk', 'full_name' => 'Nama', 'company_name' => 'Perusahaan', 'email' => 'Email',
            'phone' => 'Telepon', 'destination_country' => 'Negara Tujuan', 'commodity_interest' => 'Komoditas',
            'volume_estimate' => 'Volume', 'status' => 'Status', 'follow_up_at' => 'Follow-up', 'additional_notes' => 'Catatan Buyer',
        ];

        return response()->streamDownload(function () use ($query, $columns): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_values($columns));

            $query->clone()->each(function (Inquiry $inquiry) use ($out, $columns): void {
                fputcsv($out, array_map(
                    fn (string $column): string => self::csvSafe($inquiry->{$column}),
                    array_keys($columns),
                ));
            });

            fclose($out);
        }, 'pesan-masuk-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Mencegah sel diawali rumus agar aman dibuka di Excel/Sheets. */
    private static function csvSafe(mixed $value): string
    {
        $text = (string) $value;

        return $text !== '' && in_array($text[0], ['=', '+', '-', '@', "\t", "\r"], true) ? "'".$text : $text;
    }
}
