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

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->identifier =
                "ADM-" . str_pad(self::max("id") + 1, 5, "0", STR_PAD_LEFT);
        });
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, "receiver");
    }

    public function sentMails()
    {
        return $this->morphMany(Mail::class, "sender");
    }
}
