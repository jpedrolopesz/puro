<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "mail_id",
        "sender_id",
        "sender_type",
        "receiver_id",
        "receiver_type",
        "text",
        "date",
    ];
    protected $primaryKey = "id";

    public $timestamps = false;

    public function mail()
    {
        return $this->belongsTo(Mail::class, "mail_id");
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }
}
