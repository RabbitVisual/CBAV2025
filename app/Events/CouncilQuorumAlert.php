<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;

class CouncilQuorumAlert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;
    public $currentQuorum;
    public $requiredQuorum;

    public function __construct(Conselho $council, $currentQuorum, $requiredQuorum)
    {
        $this->council = $council;
        $this->currentQuorum = $currentQuorum;
        $this->requiredQuorum = $requiredQuorum;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 