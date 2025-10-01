<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;

class CouncilVotingStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;
    public $voting;

    public function __construct(Conselho $council, $voting)
    {
        $this->council = $council;
        $this->voting = $voting;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 