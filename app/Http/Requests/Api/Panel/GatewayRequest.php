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
        //basic rules for Filtering list
        $rules = [];

        //check method Request for change rule to forms
        if ($this->method() != 'GET') {
            $rules = [
                'title' => ['required', 'string', 'max:30'],
                'key' => ['required', 'alpha', 'max:15', 'unique:gateways,key,' . $this->route('gateway')],
                'params' => ['required', 'array'],
                'params.*' => ['string'],
            ];

            if ($this->method() == 'PATCH')
                $rules['is_active'] = ['required', 'boolean'];
        }
        return $rules;
    }
}
