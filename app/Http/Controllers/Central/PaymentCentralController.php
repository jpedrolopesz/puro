<?php

namespace App\Http\Controllers\Central;

use App\Services\Stripe\StripePaymentIntentsManager;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;

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
    public function details($paymentsId)
    {
        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(
                "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
            );

            // Faz uma chamada à API para buscar o PaymentIntent com o ID fornecido
            $paymentIntent = PaymentIntent::retrieve($paymentsId);

            // Verifica se encontrou o PaymentIntent
            if ($paymentIntent) {
                // Inicializa variáveis para armazenar dados do cliente
                $customerName = null;
                $customerEmail = null;
                $customerAddress = null;
                $customerPhone = null;

                // Inicializa variáveis para armazenar dados do método de pagamento
                $cardBrand = null;
                $cardLast4 = null;
                $expMonth = null;
                $expYear = null;

                // Verifica se há um customer associado ao PaymentIntent
                if ($paymentIntent->customer) {
                    // Faz uma chamada à API para buscar o Customer
                    $customer = Customer::retrieve($paymentIntent->customer);

                    // Define as variáveis de nome, email, endereço e telefone do cliente
                    $customerName = $customer->name;
                    $customerEmail = $customer->email;
                    $customerAddress = $customer->address;
                    $customerPhone = $customer->phone;
                }

                // Verifica se há um payment method associado ao PaymentIntent
                if ($paymentIntent->payment_method) {
                    // Faz uma chamada à API para buscar o PaymentMethod
                    $paymentMethod = PaymentMethod::retrieve(
                        $paymentIntent->payment_method
                    );

                    // Define as variáveis do cartão
                    if ($paymentMethod->type == "card") {
                        $cardBrand = $paymentMethod->card->brand;
                        $cardLast4 = $paymentMethod->card->last4;
                        $expMonth = $paymentMethod->card->exp_month;
                        $expYear = $paymentMethod->card->exp_year;
                    }
                }
                $applicationFee = isset($paymentIntent->application_fee_amount)
                    ? $paymentIntent->application_fee_amount
                    : 0;

                $paymentDetails = [
                    "id" => $paymentIntent->id,
                    "amount" => $paymentIntent->amount,
                    "currency" => $paymentIntent->currency,
                    "status" => $paymentIntent->status,
                    "created" => $paymentIntent->created,
                    "customer_name" => $customerName,
                    "customer_email" => $customerEmail,
                    "customer_address" => $customerAddress,
                    "customer_phone" => $customerPhone,
                    "description" => $paymentIntent->description,
                    "payment_method" => $paymentIntent->payment_method,
                    "card_brand" => $cardBrand,
                    "card_last4" => $cardLast4,
                    "card_exp_month" => $expMonth,
                    "card_exp_year" => $expYear,
                    "receipt_email" => $paymentIntent->receipt_email,
                    "metadata" => $paymentIntent->metadata,
                    "application_fee_amount" => $applicationFee,
                    "capture_method" => $paymentIntent->capture_method,
                ];

                return Inertia::render("Central/Payments/PaymentsViewDetails", [
                    "paymentDetails" => $paymentDetails,
                ]);
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Payment not found.",
                    ],
                    404
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to retrieve payment details.",
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
