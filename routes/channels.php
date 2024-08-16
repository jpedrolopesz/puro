<?php

use App\Events\Central\MessageSent;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    "sync-payment",
    function ($user) {
        return true;
    },
    ["guards" => ["web", "admin"]]
);

Broadcast::channel(
    "chat",
    function ($user) {
        return true; // Permitir acesso ao canal
    },
    ["guards" => ["web", "admin"]]
);

broadcast(new MessageSent(["text" => "Test message"]));
