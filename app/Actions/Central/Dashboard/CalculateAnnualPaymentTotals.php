<?php

namespace App\Actions\Central\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CalculateAnnualPaymentTotals
{
    public function execute(): array
    {
        $validPaymentsQuery = $this->getValidPaymentsQuery();
        $totalAmount = $this->calculateTotalAmount($validPaymentsQuery);
        $yearlyTotals = $this->calculateYearlyTotals($validPaymentsQuery);

        return [
            "total_amount" => $this->formatPrice($totalAmount),
            "yearly_totals" => $this->formatYearlyTotals($yearlyTotals),
            "chart_data" => $this->formatChartData($yearlyTotals),
        ];

        return [
            "total_amount" => $this->formatPrice(0),
            "yearly_totals" => [],
            "chart_data" => [],
        ];
    }

    private function getValidPaymentsQuery()
    {
        return Payment::where("captured", true)
            ->where("status", "!=", "Failed")
            ->where(function ($query) {
                $query->where("refunded", false)->orWhere("amount_refunded", 0);
            });
    }

    private function calculateTotalAmount($query)
    {
        return $query->sum(DB::raw($this->getAmountCalculation()));
    }

    private function calculateYearlyTotals($query): Collection
    {
        return $query
            ->select(
                DB::raw("YEAR(payment_date) as year"),
                DB::raw("SUM({$this->getAmountCalculation()}) as total_amount")
            )
            ->groupBy(DB::raw("YEAR(payment_date)"))
            ->orderBy(DB::raw("YEAR(payment_date)"))
            ->get();
    }

    private function getAmountCalculation(): string
    {
        return "
            CASE
                WHEN converted_amount IS NOT NULL AND converted_amount > 0
                THEN converted_amount - IFNULL(fee, 0) - IFNULL(converted_amount_refunded, 0)
                ELSE amount - IFNULL(fee, 0) - IFNULL(amount_refunded, 0)
            END
        ";
    }

    private function formatYearlyTotals(Collection $yearlyTotals): array
    {
        return $yearlyTotals
            ->mapWithKeys(function ($item) {
                return [$item->year => $this->formatPrice($item->total_amount)];
            })
            ->toArray();
    }

    private function formatChartData(Collection $yearlyTotals): array
    {
        return $yearlyTotals
            ->map(function ($item) {
                return [
                    "year" => $item->year,
                    "amount" => round($item->total_amount, 2),
                ];
            })
            ->values()
            ->toArray();
    }

    private function formatPrice($amount): string
    {
        return number_format(max(0, $amount), 2, ",", ".");
    }
}
