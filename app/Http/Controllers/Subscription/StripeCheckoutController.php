<?php

namespace App\Http\Controllers\Subscription;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeCheckoutController extends Controller
{
    public function __construct()
    {
        // Configure o Stripe com sua chave secreta ao inicializar o controlador
        Stripe::setApiKey(
            "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
        );
    }

    public function index(Request $request)
    {
        return Inertia::render("Subscription/StripeCheckout");
    }

    public function subscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "price_id" => "required|string",
            "additional_info" => "required|string",
            "token" => "required|string", // Token gerado pelo Stripe Elements
            "amount" => "required|integer|min:1", // Valor enviado do frontend
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $priceId = $request->input("price_id");
        $additionalInfo = $request->input("additional_info");
        $token = $request->input("token");
        $amount = $request->input("amount");

        try {
            // Crie um novo PaymentIntent usando payment_method_data
            $paymentIntent = PaymentIntent::create([
                "amount" => $amount, // Valor enviado do frontend
                "currency" => "usd",
                "payment_method_data" => [
                    "type" => "card",
                    "card" => [
                        "token" => $token,
                    ],
                ],
                "confirmation_method" => "manual",
                "confirm" => true,
                "return_url" => route("tenant.dashboard"), // Defina a rota apropriada aqui
                "metadata" => [
                    "additional_info" => $additionalInfo,
                ],
            ]);

            // Verifique o status do pagamento
            if ($paymentIntent->status === "succeeded") {
                // Crie a assinatura ou a lógica de sucesso aqui
                return response()->json(["status" => "success"]);
            } else {
                return response()->json([
                    "status" => "requires_action",
                    "payment_intent_client_secret" =>
                        $paymentIntent->client_secret,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
