<?php

namespace App\Data\DTO;

class PaymentStripeDetailsDTO
{
    public $id;
    public $amount;
    public $currency;
    public $status;
    public $created;
    public $customer_name;
    public $customer_email;
    public $customer_address;
    public $customer_phone;
    public $description;
    public $payment_method;
    public $card_brand;
    public $card_last4;
    public $card_exp_month;
    public $card_exp_year;
    public $receipt_email;
    public $application_fee_amount;
    public $capture_method;

    public function __construct(
        $id,
        $amount,
        $currency,
        $status,
        $created,
        $customer_name,
        $customer_email,
        $customer_address,
        $customer_phone,
        $description,
        $payment_method,
        $card_brand,
        $card_last4,
        $card_exp_month,
        $card_exp_year,
        $receipt_email,
        $application_fee_amount,
        $capture_method
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->created = $created;
        $this->customer_name = $customer_name;
        $this->customer_email = $customer_email;
        $this->customer_address = $customer_address;
        $this->customer_phone = $customer_phone;
        $this->description = $description;
        $this->payment_method = $payment_method;
        $this->card_brand = $card_brand;
        $this->card_last4 = $card_last4;
        $this->card_exp_month = $card_exp_month;
        $this->card_exp_year = $card_exp_year;
        $this->receipt_email = $receipt_email;
        $this->application_fee_amount = $application_fee_amount;
        $this->capture_method = $capture_method;
    }
}
