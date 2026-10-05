<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        // Honeypot: real users never see/fill this field; a filled value means a bot.
        // Pretend success without persisting or leaking any signal to the bot.
        if ($request->filled('website')) {
            return back()->with('success', 'Permintaan inquiry Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
        }

        $inquiry = Inquiry::create([
            ...$request->validated(),
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        $inquiry->markEmailQueued();

        try {
            Mail::to(config('mail.sales_notification_address'))
                ->queue((new NewInquiryNotification($inquiry))->afterCommit());
        } catch (Throwable $exception) {
            report($exception);

            $inquiry->markEmailFailed(Str::limit($exception->getMessage(), 2000));
        }

        return back()->with('success', 'Permintaan inquiry Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
    }
}
