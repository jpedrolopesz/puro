<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeProduct extends Model
{
    use HasFactory;

    protected $connection = "mysql";

    protected $fillable = [
        "stripe_product_id",
        "name",
        "description",
        "is_visible",
        "order",
        "column_count",
        "monthly_price_id",
        "yearly_price_id",
        "monthly_unit_amount",
        "yearly_unit_amount",
        "monthly_recurring_interval",
        "yearly_recurring_interval",
        "features",
    ];

    protected $casts = [
        "order" => "integer",
        "column_count" => "integer",
        "monthly_unit_amount" => "integer",
        "yearly_unit_amount" => "integer",
        "features" => "array",
    ];
}
