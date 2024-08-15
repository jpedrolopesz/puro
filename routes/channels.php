<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    "sync-payment",
    function ($user) {
        return true;
    },
    ["guards" => ["web", "admin"]]
);
