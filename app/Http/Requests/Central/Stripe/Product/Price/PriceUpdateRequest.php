<?php

namespace App\Http\Requests\Central\Stripe\Product\Price;

use Illuminate\Foundation\Http\FormRequest;

class PriceUpdateRequest extends FormRequest
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
            "active" => ["required", "boolean"],
            "nickname" => ["nullable", "string", "max:250"],
            "priceDefault" => ["string"],
        ];
    }
}