<?php

namespace App\Actions\Central\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CalculateAnnualSubscriberTotals
{
    public function execute(): array
    {
        try {
            // Estatísticas de Inscritos
            $subscribersStats = $this->getSubscriberStatistics();

            // Estatísticas de Não Inscritos
            $nonSubscribersStats = $this->getNonSubscriberStatistics();

            return [
                "subscribers" => [
                    "total" => $subscribersStats["total"],
                    "yearly_totals" => $subscribersStats["yearly_totals"],
                    "chart_data" => $subscribersStats["chart_data"],
                ],
                "non_subscribers" => [
                    "total" => $nonSubscribersStats["total"],
                ],
                "total_users" =>
                    $subscribersStats["total"] + $nonSubscribersStats["total"],
            ];
        } catch (\Exception $e) {
            return [
                "subscribers" => [
                    "total" => 0,
                    "yearly_totals" => [],
                    "chart_data" => [],
                ],
                "non_subscribers" => [
                    "total" => 0,
                ],
                "total_users" => 0,
            ];
        }
    }

    private function getSubscriberStatistics(): array
    {
        $query = User::query()
            ->whereNotNull("stripe_id")
            ->whereExists(function ($query) {
                $query
                    ->select(DB::raw(1))
                    ->from("payments")
                    ->whereColumn("payments.customer_id", "users.stripe_id");
            });

        $totalSubscribers = $query->count();
        $yearlyTotals = $this->calculateYearlyTotals($query);

        return [
            "total" => $totalSubscribers,
            "yearly_totals" => $this->formatYearlyTotals($yearlyTotals),
            "chart_data" => $this->formatChartData($yearlyTotals),
        ];
    }

    private function getNonSubscriberStatistics(): array
    {
        $query = User::query()->where(function ($query) {
            $query
                ->whereNull("stripe_id")
                ->orWhereNotExists(function ($subquery) {
                    $subquery
                        ->select(DB::raw(1))
                        ->from("payments")
                        ->whereColumn(
                            "payments.customer_id",
                            "users.stripe_id"
                        );
                });
        });

        return [
            "total" => $query->count(),
        ];
    }

    private function getValidSubscribersQuery()
    {
        return User::query()
            ->whereNotNull("stripe_id")
            ->whereExists(function ($query) {
                $query
                    ->select(DB::raw(1))
                    ->from("payments")
                    ->whereColumn("payments.customer_id", "users.stripe_id");
            });
    }

    private function calculateTotalSubscribers($query): int
    {
        return $query->count();
    }

    private function calculateYearlyTotals($query): Collection
    {
        return $query
            ->select(
                DB::raw("YEAR(created_at) as year"),
                DB::raw("COUNT(*) as total_subscribers")
            )
            ->groupBy(DB::raw("YEAR(created_at)"))
            ->orderBy(DB::raw("YEAR(created_at)"))
            ->get();
    }

    private function formatYearlyTotals(Collection $yearlyTotals): array
    {
        return $yearlyTotals
            ->mapWithKeys(function ($item) {
                return [$item->year => $item->total_subscribers];
            })
            ->toArray();
    }

    private function formatChartData(Collection $yearlyTotals): array
    {
        return $yearlyTotals
            ->map(function ($item) {
                return [
                    "year" => $item->year,
                    "subscribers" => $item->total_subscribers,
                ];
            })
            ->values()
            ->toArray();
    }
}
