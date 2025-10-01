<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;
use App\Models\User;

class CouncilMeetingScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;
    public $scheduledBy;

    public function __construct(Conselho $council, User $scheduledBy)
    {
        $this->council = $council;
        $this->scheduledBy = $scheduledBy;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 