<?php

namespace App\Events;

use App\Models\Mail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\PrivateChannel;

class MailSentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Mail $mail)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn(): array
    {
        return new PrivateChannel("chat." . $this->mail->receiver_id);
    }
}
