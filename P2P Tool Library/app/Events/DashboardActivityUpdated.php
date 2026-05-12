<?php

namespace App\Events;

use App\Models\DashboardActivityLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardActivityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    // Constructor
    public function __construct(DashboardActivityLog $activity)
    {
        $this->activity = $activity->load(['user', 'tool', 'borrow']);
    }

    // Broadcast channels
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.dashboard'),
        ];
    }

    // Broadcast name
    public function broadcastAs(): string
    {
        return 'activity.updated';
    }
}
