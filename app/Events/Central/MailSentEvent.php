<?php

namespace App\Events\Central;

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

    public $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return [new Channel("chat.{$this->mail->receiver_id}")];
    }

    public function broadcastWith()
    {
        return [
            "mail" => $this->mail,
        ];
    }
}
