<?php

namespace App\Actions\Central\Stripe\Payment;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RetrieveFilteredPaymentsAction
{
    public function execute(string $filter = "all"): Collection
    {
        $query = Payment::query();

        switch ($filter) {
            case "succeeded":
                $query
                    ->where("status", "succeeded")
                    ->where("refunded", false)
                    ->where("disputed", false);
                break;
            case "refunded":
                $query->where("refunded", true);
                break;
            case "disputed":
                $query->where("disputed", true);
                break;
            case "failed":
                $query->where("status", "failed");
                break;
            case "uncaptured":
                $query->where("captured", false);
                break;
            case "all":
            default:
                // No filter, return all payments
                break;
        }

        return $query->latest()->get();
    }

    public function getStatistics(): array
    {
        return [
            "total_payments" => Payment::count(),
            "succeeded_payments" => Payment::where("status", "succeeded")
                ->where("refunded", false)
                ->where("disputed", false)
                ->count(),
            "refunded_payments" => Payment::where("refunded", true)->count(),
            "disputed_payments" => Payment::where("disputed", true)->count(),
            "failed_payments" => Payment::where("status", "failed")->count(),
            "uncaptured_payments" => Payment::where("captured", false)->count(),
            "total_amount" => Payment::sum("amount"),
            "refunded_amount" => Payment::sum("amount_refunded"),
            "average_payment_amount" => Payment::avg("amount"),
            "most_common_currency" =>
                Payment::select("currency")
                    ->groupBy("currency")
                    ->orderByRaw("COUNT(*) DESC")
                    ->first()->currency ?? "N/A",
            "total_payments_last_30_days" => Payment::where(
                "created_at",
                ">=",
                now()->subDays(30)
            )->count(),
        ];
    }
}
