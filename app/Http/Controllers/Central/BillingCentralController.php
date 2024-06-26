<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class BillingCentralController extends Controller
{
    // Cliente e segredos da Stripe
    private $client_id = "pk_live_51LRjEpGQW0U1Pfqxp5tYLEJCH6jhJxjpAVTJo4yaOzeKmPz7EWvtffcFuJUo6lxERCRfMVRqGEumeNIHOzgXoNsi00SJ42iuNN";
    private $client_secret = "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";

    public function index()
    {
        return Inertia::render("Central/Billing/BillingCentral");
    }

    // Método para redirecionar o usuário para a Stripe
    public function connectStripe()
    {
        $stripeUrl = "https://connect.stripe.com/oauth/authorize?response_type=code&client_id={$this->client_id}&scope=read_write";
        return redirect($stripeUrl);
    }

    // Método para lidar com o callback da Stripe
    public function handleStripeCallback(Request $request)
    {
        if ($request->has("code")) {
            $code = $request->get("code");

            $response = Http::asForm()->post(
                "https://connect.stripe.com/oauth/token",
                [
                    "client_secret" => $this->client_secret,
                    "code" => $code,
                    "grant_type" => "authorization_code",
                ]
            );

            $response_data = $response->json();

            if (isset($response_data["access_token"])) {
                // Sucesso - Salvar os dados do usuário e token de acesso
                $access_token = $response_data["access_token"];
                $stripe_user_id = $response_data["stripe_user_id"];

                // Salvar esses detalhes no banco de dados conforme necessário
                // ...

                return response()->json([
                    "message" => "Conexão com Stripe realizada com sucesso!",
                ]);
            } else {
                // Falha - tratar erro
                return response()->json(
                    ["message" => "Erro ao conectar com Stripe!"],
                    500
                );
            }
        } else {
            // O código de autorização não foi recebido
            return response()->json(
                ["message" => "Código de autorização não recebido!"],
                400
            );
        }
    }
}
