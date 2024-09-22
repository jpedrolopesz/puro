<?php

namespace App\Actions\Central\Tenants;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class CountStripeCustomerPaymentsAction
{
    public function execute($customerId)
    {
        $totals = $this->fetchPaymentTotals($customerId);
        $payments = $this->fetchPayments($customerId);
        $subscriptionData = $this->fetchSubscriptionData($customerId);

        return [
            "totals" => $totals,
            "payments" => $payments,
            "current_subscription" => $subscriptionData["current_subscription"],
            "current_plan" => $subscriptionData["current_plan"],
        ];
    }

    private function fetchPaymentTotals($customerId)
    {
        return DB::table("payments")
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
    }

    private function fetchPayments($customerId)
    {
        return DB::table("payments")
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
    }

    private function fetchSubscriptionData($customerId)
    {
        $tenant = Tenant::whereHas("creator", function ($query) use (
            $customerId
        ) {
            $query->where("stripe_id", $customerId);
        })->first();

        if (!$tenant || !$tenant->creator) {
            return ["current_subscription" => null, "current_plan" => null];
        }

        try {
            $stripe = new \Stripe\StripeClient(config("cashier.secret"));
            $subscriptions = $stripe->subscriptions->all([
                "customer" => $tenant->creator->stripe_id,
                "status" => "active",
                "limit" => 1,
            ]);

            if (empty($subscriptions->data)) {
                return ["current_subscription" => null, "current_plan" => null];
            }

            $currentSubscription = $subscriptions->data[0];
            $currentPlan = $this->extractPlanDetails(
                $currentSubscription->plan
            );

            return [
                "current_subscription" => $this->extractSubscriptionDetails(
                    $currentSubscription,
                    $currentPlan
                ),
                "current_plan" => $currentPlan,
            ];
        } catch (ApiErrorException $e) {
            Log::error(
                "Erro ao buscar assinatura do Stripe: " . $e->getMessage()
            );
            return ["current_subscription" => null, "current_plan" => null];
        }
    }

    private function extractPlanDetails($plan)
    {
        return [
            "id" => $plan->id,
            "name" => $plan->nickname,
            "amount" => $plan->amount,
            "currency" => $plan->currency,
            "interval" => $plan->interval,
        ];
    }

    private function extractSubscriptionDetails($subscription, $plan)
    {
        return [
            "id" => $subscription->id,
            "status" => $subscription->status,
            "current_period_start" => date(
                "Y-m-d H:i:s",
                $subscription->current_period_start
            ),
            "current_period_end" => date(
                "Y-m-d H:i:s",
                $subscription->current_period_end
            ),
            "plan" => $plan,
        ];
    }
}
