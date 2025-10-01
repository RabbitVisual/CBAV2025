<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conselho;

class CouncilAgendaItemAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $council;
    public $agendaItem;

    public function __construct(Conselho $council, $agendaItem)
    {
        $this->council = $council;
        $this->agendaItem = $agendaItem;
    }

    public function broadcastOn()
    {
        return new Channel('council.' . $this->council->id);
    }
} 