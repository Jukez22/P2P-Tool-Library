<?php

namespace App\Mail;

use App\Models\LateReturnEscalation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateReturnMail extends Mailable
{
    use Queueable, SerializesModels;

    public $escalation;

    public function __construct(LateReturnEscalation $escalation)
    {
        $this->escalation = $escalation->load(['borrow.tool', 'borrow.borrower']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Late Return Notice: ' . ucfirst(str_replace('_', ' ', $this->escalation->escalation_level)),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.late_return',
        );
    }
}
