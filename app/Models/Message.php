<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
    protected $keyType = "string";
    protected $connection = "mysql";

    public $timestamps = false;

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
