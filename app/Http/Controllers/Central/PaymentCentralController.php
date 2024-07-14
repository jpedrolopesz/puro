<?php

namespace App\Http\Controllers\Central;

use App\Services\Stripe\StripePaymentIntentsManager;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Inertia\Inertia;

class PaymentCentralController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        try {
            return Inertia::render("Central/Payments/PaymentsCentral", [
                "paymentLists" => $payments,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to retrieve payments.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function syncPayments()
    {
        try {
            // Recupera todos os pagamentos do Stripe
            $paymentIntents = StripePaymentIntentsManager::getAllPaymentIntents();

            // Itera sobre os pagamentos para salvá-los no banco de dados
            foreach ($paymentIntents as $paymentIntent) {
                StripePaymentIntentsManager::savePaymentToDatabase(
                    $paymentIntent
                );
            }

            return response()->json([
                "success" => true,
                "message" => "Pagamentos sincronizados com sucesso.",
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Falha ao sincronizar pagamentos.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
