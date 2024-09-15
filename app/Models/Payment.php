<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "stripe_payment_id",
        "user_id",
        "amount",
        "currency",
        "status",
        "description",
        "payment_date",
        "customer_name",
        "customer_email",
        "refunded",
        "amount_refunded",
        "disputed",
        "failure_code",
        "failure_message",
        "captured",
        "payment_method_last4",
        "payment_method_brand",
    ];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
