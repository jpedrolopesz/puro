<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        Log::info("Evento NewMessageEvent foi instanciado", [
            "message_id" => $message->id,
        ]);
    }

    public function broadcastOn()
    {
        $channel = new Channel(
            "conversation." . $this->message->conversation_id
        );
        Log::info("Canal de broadcast:", ["channel" => $channel]);
        return $channel;
    }

    public function broadcastAs()
    {
        return "new-message";
    }

    public function broadcastWith()
    {
        Log::info("Dados do evento broadcastWith:", [
            "message" => [
                "id" => $this->message->id,
                "conversation_id" => $this->message->conversation_id,
                "sender_id" => $this->message->sender_id,
                "sender_type" => $this->message->sender_type,
                "content" => $this->message->content,
                "created_at" => $this->message->created_at,
            ],
        ]);
        return [
            "message" => [
                "id" => $this->message->id,
                "conversation_id" => $this->message->conversation_id,
                "sender_id" => $this->message->sender_id,
                "sender_type" => $this->message->sender_type,
                "content" => $this->message->content,
                "created_at" => $this->message->created_at,
            ],
        ];
    }
}
