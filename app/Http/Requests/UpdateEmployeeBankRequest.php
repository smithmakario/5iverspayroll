<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->employee !== null;
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:30'],
            'bank_routing_number' => ['nullable', 'string', 'max:30'],
            'tax_id' => ['nullable', 'string', 'max:30'],
        ];
    }
}
