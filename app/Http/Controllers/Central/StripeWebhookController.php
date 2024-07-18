<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpoint_secret =
            "whsec_9a6809744cfe6820c5df82323a850a5eed5809be26d75f9622cd019dfc087d2a";

        // Payload recebido do webhook
        $payload = $request->getContent();

        // Verifica a assinatura do webhook
        $sig_header = $request->header("Stripe-Signature");
        $event = null;

        try {
            // Constrói o evento do webhook com base no payload, na assinatura e na chave secreta
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (SignatureVerificationException $e) {
            // Assinatura inválida
            return response()->json(["error" => "Assinatura inválida"], 400);
        }

        // Processa o evento conforme necessário
        if ($event->type === "balance.available") {
            $balance = $event->data->object;
            // Aqui você pode manipular os dados do saldo como necessário
        }

        // Responde ao Stripe para confirmar o recebimento do webhook
        return response()->json(["status" => "Recebido"]);
    }
}
