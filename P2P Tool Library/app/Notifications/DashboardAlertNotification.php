<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class DashboardAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $data;

    // Constructor
    public function __construct(string $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    // Deliver via DB and Broadcast
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    // DB representation
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'message' => $this->getAlertMessage(),
            'data'    => $this->data,
        ];
    }

    // Broadcast representation
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type'    => $this->type,
            'message' => $this->getAlertMessage(),
            'data'    => $this->data,
        ]);
    }

    // Get message string
    protected function getAlertMessage(): string
    {
        return match ($this->type) {
            'overdue'       => "URGENT: Overdue rental detected for tool #{$this->data['tool_id']}.",
            'pending'       => "Reminder: Rental for tool #{$this->data['tool_id']} is due soon.",
            'completed'     => "Rental #{$this->data['reservation_id']} has been successfully completed.",
            'high_activity' => "Alert: High system activity detected on the dashboard.",
            default         => "System notification: Update on dashboard metrics.",
        };
    }
}
