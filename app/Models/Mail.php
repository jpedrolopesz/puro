<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;

    protected $fillable = [
        "sender_id",
        "receiver_id",
        "name",
        "email",
        "subject",
        "text",
        "read",
        "labels",
    ];

    protected $casts = [
        "read" => "boolean",
        "labels" => "array", // Converte o JSON para um array quando acessado
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }
}
