<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Mail extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = "id";

    protected $fillable = [
        "id",
        "sender_id",
        "receiver_id",
        "name",
        "email",
        "subject",
        "text",
        "date",
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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }

            if ($model->date) {
                $model->date = Carbon::parse(
                    $model->date,
                    config("app.timezone")
                )
                    ->setTimezone("UTC")
                    ->format("Y-m-d H:i:s");
            }
        });
        static::retrieved(function ($model) {
            if ($model->date) {
                $model->date = Carbon::parse($model->date, "UTC")
                    ->setTimezone(config("app.timezone"))
                    ->format("Y-m-d H:i:s");
            }
        });
    }
}
