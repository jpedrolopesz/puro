<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Admin extends Authenticatable
{
    use CentralConnection, Notifiable, HasFactory;

    protected $table = "admins";

    protected $fillable = ["name", "password", "email", "role"];

    protected $hidden = ["password", "remember_token"];

    public function sentMessages()
    {
        return $this->morphMany(Message::class, "sender");
    }
}
