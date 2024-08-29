<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    "sync-payment",
    function ($admin) {
        return true;
    },
    ["guards" => ["admin"]]
);

Broadcast::channel(
    "user.{userId}",
    function ($user, $userId) {
        return (int) $user->id === (int) $userId;
    },
    ["guards" => ["web", "admin"]]
);

Broadcast::channel("conversation.{conversationId}", function (
    $user,
    $conversationId
) {
    return $user->conversations()->where("id", $conversationId)->exists();
});
