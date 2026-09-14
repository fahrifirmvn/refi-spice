<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class NewInquiryNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf(
                'Inquiry Ekspor Baru — %s (%s)',
                $this->inquiry->commodity_interest,
                $this->inquiry->company_name,
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiries.new-inquiry',
            with: ['inquiry' => $this->inquiry],
        );
    }

    /**
     * Send the message, recording the outcome on the inquiry itself (PRD 12.2)
     * so a delivery failure never bubbles up as an HTTP error to the user.
     */
    public function send($mailer): mixed
    {
        try {
            $sent = parent::send($mailer);

            $this->inquiry->forceFill([
                'email_sent_at' => now(),
                'email_failed_reason' => null,
            ])->save();

            return $sent;
        } catch (Throwable $e) {
            Log::channel('mail')->error('Failed to send new inquiry notification email.', [
                'inquiry_id' => $this->inquiry->id,
                'exception' => $e->getMessage(),
            ]);

            $this->inquiry->forceFill([
                'email_failed_reason' => Str::limit($e->getMessage(), 2000),
            ])->save();

            return null;
        }
    }
}
