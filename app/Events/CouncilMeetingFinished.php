<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;

class CouncilMeetingFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;

    public function __construct(Conselho $council)
    {
        $this->council = $council;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 