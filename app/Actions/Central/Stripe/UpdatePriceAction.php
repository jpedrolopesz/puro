<?php

namespace App\Actions\Central\Stripe;

use Stripe\Price;
use Exception;

class UpdatePriceAction
{
    public function execute(string $priceId, array $data): Price
    {
        try {
            $price = Price::retrieve($priceId);
            $price->active = !$data["active"];
            $price->save();
            return $price;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar preço: " . $e->getMessage());
        }
    }
}
