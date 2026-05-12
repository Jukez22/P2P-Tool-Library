<?php

namespace App\Notifications;

use App\Models\LateReturnEscalation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LateReturnNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $escalation;

    public function __construct(LateReturnEscalation $escalation)
    {
        $this->escalation = $escalation->load(['borrow.tool']);
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $toolName = $this->escalation->borrow->tool->title;
        $level = ucfirst(str_replace('_', ' ', $this->escalation->escalation_level));

        $mail = (new MailMessage)
            ->subject("Late Return Notice: {$level}")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a {$level} regarding the tool: {$toolName}.");

        if ($this->escalation->penalty_amount > 0) {
            $mail->line("A penalty of ${$this->escalation->penalty_amount} has been applied to your account.");
        }

        $mail->line("Please return the tool immediately to avoid further escalations.")
            ->action('View My Borrows', url('/my-borrows'))
            ->line('Thank you for being part of our community!');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'escalation_id'   => $this->escalation->id,
            'level'           => $this->escalation->escalation_level,
            'penalty_amount'  => $this->escalation->penalty_amount,
            'message'         => "Late return escalation reached: " . $this->escalation->escalation_level,
        ];
    }

    public function toSms(object $notifiable)
    {

    }
}
