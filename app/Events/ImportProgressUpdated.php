<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportProgressUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventName;
    public $payload;

    public function __construct($eventName, $payload)
    {
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new Channel("import-progress");
    }

    public function broadcastAs()
    {
        return $this->eventName;
    }
}
