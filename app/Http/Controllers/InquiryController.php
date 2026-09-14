<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\NewInquiryNotification;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request)
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

        // Dispatched as ShouldQueue (PRD 12.2): non-blocking in production
        // (QUEUE_CONNECTION=database), and any send failure is caught inside
        // the mailable itself so it never surfaces as an HTTP error here.
        Mail::to(config('mail.sales_notification_address'))
            ->queue(new NewInquiryNotification($inquiry));

        return back()->with('success', 'Permintaan inquiry Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
    }
}
