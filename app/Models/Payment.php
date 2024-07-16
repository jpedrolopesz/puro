<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "amount",
        "currency",
        "status",
        "description",
        "payment_date",
        "customer_name",
        "customer_email",
        "receipt_email",
        "application_fee_amount",
        "capture_method",
    ];

    protected $guarded = [
        "stripe_payment_id",
        "payment_method_type",
        "payment_method_last4",
        "payment_method_brand",
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
