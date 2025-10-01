<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;

class CouncilVotingFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;
    public $voting;
    public $results;

    public function __construct(Conselho $council, $voting, $results)
    {
        $this->council = $council;
        $this->voting = $voting;
        $this->results = $results;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 