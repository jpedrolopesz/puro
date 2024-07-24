<?php

namespace App\Http\Controllers\Subscription;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeCheckoutController extends Controller
{
    public function subscription(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "price_id" => "required|string",
            "payment_method_id" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $priceId = $request->input("price_id");
        $paymentMethodId = $request->input("payment_method_id");

        try {
            $user = $request->user();

            $user->updateDefaultPaymentMethod($paymentMethodId);

            $user
                ->newSubscription("default", $priceId)
                ->create($paymentMethodId);

            return response()->json(["status" => "success"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
