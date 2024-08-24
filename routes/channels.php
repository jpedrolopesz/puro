<?php

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

Broadcast::channel("presence.chat", function ($user) {
    return ["id" => $user->id, "name" => $user->name];
});
