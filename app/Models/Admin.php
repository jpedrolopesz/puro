<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Admin extends Authenticatable
{
    use CentralConnection, Notifiable, HasFactory;

    protected $primaryKey = "id";

    protected $fillable = ["name", "password", "email", "role"];

    protected $hidden = ["password", "remember_token"];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->identifier =
                "ADM-" . str_pad(self::max("id") + 1, 5, "0", STR_PAD_LEFT);
        });
    }

    public function initiatedConversations()
    {
        return $this->morphMany(Conversation::class, "initiator");
    }

    public function receivedConversations()
    {
        return $this->morphMany(Conversation::class, "recipient");
    }

    public function messages()
    {
        return $this->morphMany(Message::class, "sender");
    }
}
