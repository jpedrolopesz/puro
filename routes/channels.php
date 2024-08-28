<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    "sync-payment",
    function ($admin) {
        return true;
    },
    ["guards" => ["admin"]]
);

Broadcast::channel(
    "chat.{userId}",
    function ($user, $id) {
        return (int) $user->id === (int) $id;
    },
    ["guards" => ["web", "admin"]]
);

Broadcast::channel(
    "conversation.{conversationId}",
    function ($user, $conversationId) {
        return $user->conversations->contains($conversationId);
    },
    ["guards" => ["web", "admin"]]
);
