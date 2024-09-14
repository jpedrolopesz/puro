<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        "payment_id",
        "stripe_refund_id",
        "amount",
        "status",
        "reason",
        "refund_date",
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
