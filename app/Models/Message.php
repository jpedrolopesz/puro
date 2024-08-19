<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ["id", "sender_id", "text", "receiver_id", "date"];

    public $timestamps = false;

    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
