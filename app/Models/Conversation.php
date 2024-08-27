<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $connection = "mysql";

    protected $fillable = [
        "id",
        "initiator_id",
        "initiator_type",
        "recipient_id",
        "recipient_type",
        "subject",
    ];

    public function getTypeFromIdentifier()
    {
        $identifier = $this->identifier;

        $prefix = substr($identifier, 0, 3);

        $types = [
            "USR" => "user",
            "ADM" => "admin",
        ];

        return $types[$prefix] ?? null;
    }

    public function initiator()
    {
        return $this->morphTo();
    }

    public function recipient()
    {
        return $this->morphTo();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
