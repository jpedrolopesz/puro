<?php

namespace App\Actions\Central\Tenants;

use Illuminate\Support\Facades\DB;

class CountStripeCustomerPaymentsAction
{
    public function execute($customerId)
    {
        $totals = DB::table("payments")
            ->select([
                DB::raw("COUNT(DISTINCT stripe_payment_id) as payment_count"),
                DB::raw("SUM(amount) as total_amount"),
                DB::raw(
                    "COUNT(DISTINCT CASE WHEN refunded = true THEN stripe_payment_id END) as refund_count"
                ),
                DB::raw(
                    "SUM(CASE WHEN refunded = true THEN amount_refunded ELSE 0 END) as total_refunded"
                ),
            ])
            ->where("customer_id", $customerId)
            ->where("stripe_payment_id", "LIKE", "ch_%")
            ->where("status", "succeeded")
            ->first();

        $payments = DB::table("payments")
            ->select([
                "id",
                "stripe_payment_id",
                "amount",
                "amount_refunded",
                "currency",
                "refunded",
                "status",
                "payment_date",
                "description",
            ])
            ->where("customer_id", $customerId)
            ->where("stripe_payment_id", "LIKE", "ch_%")
            ->where("status", "succeeded")
            ->orderBy("payment_date", "desc")
            ->get();

        return [
            "totals" => $totals,
            "payments" => $payments,
        ];
    }
}
