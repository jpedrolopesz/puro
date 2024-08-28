<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel("conversation." . $this->message->conversation_id);
    }

    public function broadcastAs()
    {
        return "new-message";
    }

    public function broadcastWith()
    {
        return [
            "id" => $this->message->id,
            "conversation_id" => $this->message->conversation_id,
            "sender_id" => $this->message->sender_id,
            "sender_type" => $this->message->sender_type,
            "content" => $this->message->content,
            "created_at" => $this->message->created_at,
        ];
    }
}
