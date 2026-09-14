<?php

namespace App\Console\Commands;

use App\Models\Inquiry;
use Illuminate\Console\Command;

/**
 * PRD Section 12.3 — cheap operational signal for the manual decision of
 * whether to move from direct-send SMTP to a relay provider.
 */
class MailHealthCheckCommand extends Command
{
    protected $signature = 'mail:health-check {--days=7 : Number of trailing days to analyze}';

    protected $description = 'Report the inquiry notification email failure ratio over the last N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $since = now()->subDays($days);

        $attempted = Inquiry::query()
            ->where('created_at', '>=', $since)
            ->where(function ($query) {
                $query->whereNotNull('email_sent_at')->orWhereNotNull('email_failed_reason');
            })
            ->count();

        $failed = Inquiry::query()
            ->where('created_at', '>=', $since)
            ->whereNotNull('email_failed_reason')
            ->count();

        if ($attempted === 0) {
            $this->info("No inquiry notification emails attempted in the last {$days} day(s).");

            return self::SUCCESS;
        }

        $ratio = round(($failed / $attempted) * 100, 1);

        $this->info("Inquiry email delivery — last {$days} day(s): {$failed}/{$attempted} failed ({$ratio}%).");

        if ($ratio >= 10.0) {
            $this->warn('Failure ratio is 10% or higher — consider switching to an SMTP relay (see PRD Section 12.4).');
        }

        return self::SUCCESS;
    }
}
