<?php

namespace App\Http\Requests\Api\Panel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GatewayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //todo: policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()) {

            'GET' => [],

            'POST', 'PUT' => [
                'title' => ['required', 'string', 'max:30'],
                'key' => ['required', 'alpha', 'max:15', 'unique:gateways,key,' . ($this->route('gateway')['id'] ?? null)],
                'params' => ['required', 'array'],
                'params.*' => ['string'],
                'is_active' => ['nullable', 'boolean'],
            ],

            'PATCH' => [
                'is_active' => ['required', 'boolean'],
            ],

            default => [],
        };

    }
}
