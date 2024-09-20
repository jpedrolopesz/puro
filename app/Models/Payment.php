<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "stripe_payment_id",
        "amount",
        "amount_refunded",
        "currency",
        "refunded",
        "disputed",
        "captured",
        "converted_amount",
        "converted_amount_refunded",
        "converted_currency",
        "decline_reason",
        "description",
        "fee",
        "refunded_date",
        "statement_descriptor",
        "status",
        "seller_message",
        "taxes_on_fee",
        "card_id",
        "customer_id",
        "customer_description",
        "customer_email",
        "invoice_id",
        "transfer",
        "additional_info",
        "payment_date",
    ];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function getNetAmountAttribute()
    {
        return $this->amount - $this->fee - $this->taxes_on_fee;
    }

    public function getIsFullyRefundedAttribute()
    {
        return $this->amount === $this->amount_refunded;
    }

    public function scopeSuccessful($query)
    {
        return $query->where("status", "succeeded");
    }

    public function scopeFailed($query)
    {
        return $query->where("status", "failed");
    }

    public function scopeDisputed($query)
    {
        return $query->where("disputed", true);
    }
}
