<?php

namespace App\Http\Requests\Api\Paymeny;

use App\Enums\PayRequestStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRequestRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1000'],
            'order_code' => ['required', 'integer', 'digits_between:1,10',],
            'gateway_key' => ['required', 'string', 'exists:gateways,key'],
        ];
    }
}
