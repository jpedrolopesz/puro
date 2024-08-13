<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    "sync-payment",
    function () {
        return true
    },
    ["guards" => ["web", "admin"]]
);
