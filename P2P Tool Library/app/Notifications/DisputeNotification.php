<?php

namespace App\Notifications;

use App\Models\Dispute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisputeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $dispute;
    protected $event;

    public function __construct(Dispute $dispute, string $event)
    {
        $this->dispute = $dispute;
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $messages = [
            'created'  => "A new dispute has been opened for your recent transaction.",
            'evidence' => "New evidence has been uploaded for dispute #{$this->dispute->id}.",
            'review'   => "Your dispute #{$this->dispute->id} is now under official review.",
            'resolved' => "Dispute #{$this->dispute->id} has been resolved. Decision: {$this->dispute->resolution}",
            'rejected' => "Dispute #{$this->dispute->id} has been rejected/dismissed.",
        ];

        return (new MailMessage)
            ->subject("Dispute Update: " . ucfirst($this->event))
            ->line($messages[$this->event] ?? "There is an update on your dispute.")
            ->action('View Dispute Details', url('/disputes/' . $this->dispute->id))
            ->line('Thank you for using our P2P library!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'dispute_id' => $this->dispute->id,
            'event'      => $this->event,
            'message'    => "Dispute #{$this->dispute->id} has been updated: {$this->event}",
        ];
    }
}
