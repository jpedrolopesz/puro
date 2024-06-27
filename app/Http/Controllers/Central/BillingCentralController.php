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

    // Abordagem que  vou fazer vai ser a verificaóes se as chaces estao no config/service.
    // Após a verificaçao eu irei disponibilizar o conteudo na tela.
    public function connectStripe()
    {
        $stripeUrl =
            "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_FkyHCg7X8mlvCUdMDao4mMxagUfhIwXb&scope=read_write";

        return redirect($stripeUrl);
    }
}
