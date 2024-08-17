<?php

namespace App\Events\Central;

use App\Models\Mail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
        $userId = $this->mail->receiver_id;
        return new Channel("chat.$userId");
    }
}
