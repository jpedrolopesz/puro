<?php

namespace App\Http\Requests\Central\Stripe\Product\Price;

use Illuminate\Foundation\Http\FormRequest;

class PriceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "product_id" => ["required", "string"],
            "price" => ["required", "numeric", "min:0"],
            "currency" => ["required", "string", "size:3"],
            "recurring" => ["required", "in:month,year"],
            "nickname" => ["nullable", "string", "max:250"],
        ];
    }
}
