<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

Broadcast::channel(
    "sync-payment",
    function ($admin) {
        return true;
    },
    ["guards" => ["admin"]]
);

Broadcast::channel("chat.{userId}", function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
