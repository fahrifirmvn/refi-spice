<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request)
    {
        // Honeypot: real users never see/fill this field; a filled value means a bot.
        // Pretend success without persisting or leaking any signal to the bot.
        if ($request->filled('website')) {
            return back()->with('success', 'Permintaan inquiry Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
        }

        Inquiry::create([
            ...$request->validated(),
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Permintaan inquiry Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
    }
}
