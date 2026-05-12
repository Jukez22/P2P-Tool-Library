<?php

namespace App\Notifications;

use App\Models\InsuranceClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InsuranceClaimNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $claim;
    protected $event;

    public function __construct(InsuranceClaim $claim, string $event)
    {
        $this->claim = $claim;
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusMessages = [
            'created'   => "Your insurance claim for '{$this->claim->tool->title}' has been submitted successfully.",
            'evidence'  => "New evidence has been attached to your insurance claim #{$this->claim->id}.",
            'approved'  => "Great news! Your insurance claim #{$this->claim->id} has been approved.",
            'rejected'  => "Unfortunately, your insurance claim #{$this->claim->id} has been rejected.",
            'completed' => "Your insurance claim #{$this->claim->id} has been finalized and completed.",
        ];

        return (new MailMessage)
            ->subject("Insurance Claim Update: " . ucfirst($this->event))
            ->line($statusMessages[$this->event] ?? "There is an update on your insurance claim.")
            ->action('View Claim Status', url('/insurance/claims/' . $this->claim->id))
            ->line('Thank you for using our P2P Tool Library service!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'claim_id' => $this->claim->id,
            'event'    => $this->event,
            'message'  => "Insurance claim #{$this->claim->id} status: {$this->event}",
        ];
    }
}
