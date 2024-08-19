<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = "id";

    protected $fillable = [
        "sender_id",
        "receiver_id",
        "name",
        "email",
        "subject",
        "text",
    ];

    protected $casts = [
        "labels" => "array",
        "read" => "boolean",
    ];

    public $timestamps = false;

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }
}
