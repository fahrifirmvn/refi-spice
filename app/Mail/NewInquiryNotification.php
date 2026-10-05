<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class NewInquiryNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(public Inquiry $inquiry) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: $this->inquiry->email
                ? [new Address($this->inquiry->email, $this->inquiry->full_name)]
                : [],
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
            text: 'emails.inquiries.new-inquiry-text',
            with: ['inquiry' => $this->inquiry],
        );
    }

    /**
     * Record accepted deliveries while allowing transient transport failures to
     * bubble to the queue worker for retry.
     */
    public function send($mailer): mixed
    {
        $sent = parent::send($mailer);

        $this->inquiry->markEmailDelivered();

        return $sent;
    }

    public function failed(Throwable $exception): void
    {
        Log::channel('mail')->error('Inquiry notification email exhausted all delivery attempts.', [
            'inquiry_id' => $this->inquiry->id,
            'exception' => $exception->getMessage(),
        ]);

        $this->inquiry->markEmailFailed(Str::limit($exception->getMessage(), 2000));
    }
}
