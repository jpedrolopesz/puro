<?php

namespace App\Events\Central;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncPaymentStripeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $progress;

    /**
     * Create a new event instance.
     */
    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return new PrivateChannel("sync-payment");
    }

    public function broadcastWith()
    {
        Log::info("Broadcasting SyncPaymentStripeEvent with data:", [
            "progress" => $this->progress,
        ]);
        return ["progress" => $this->progress, "test" => "Test message"];
    }
}
