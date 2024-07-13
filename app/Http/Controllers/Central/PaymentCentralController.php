<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Central\Stripe\StripeGetAllAction;
use App\Models\Payment;
use Inertia\Inertia;

class PaymentCentralController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        try {
            // $paymentList = StripeGetAllAction::getAllPaymentIntents();

            return Inertia::render("Central/Payments/PaymentsCentral", [
                // "paymentLi" => $paymentList,
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
            $paymentIntents = StripeGetAllAction::getAllPaymentIntents();

            // Itera sobre os pagamentos para salvá-los no banco de dados
            foreach ($paymentIntents as $paymentIntent) {
                StripeGetAllAction::savePaymentToDatabase($paymentIntent);
            }

            return response()->json([
                "success" => true,
                "message" => "Pagamentos sincronizados com sucesso.",
            ]);
        } catch (\Exception $e) {
            // Trata qualquer exceção que possa ocorrer
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
