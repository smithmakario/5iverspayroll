<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeGuarantorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guarantors.1.full_name' => ['required', 'string', 'max:150'],
            'guarantors.1.email' => ['required', 'email', 'max:150'],
            'guarantors.1.phone' => ['required', 'string', 'max:30'],
            'guarantors.1.address' => ['required', 'string', 'max:500'],
            'guarantors.2.full_name' => ['required', 'string', 'max:150'],
            'guarantors.2.email' => ['required', 'email', 'max:150'],
            'guarantors.2.phone' => ['required', 'string', 'max:30'],
            'guarantors.2.address' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'guarantors.1.full_name.required' => 'Guarantor 1 full name is required.',
            'guarantors.2.full_name.required' => 'Guarantor 2 full name is required.',
            'guarantors.1.email.required' => 'Guarantor 1 email is required.',
            'guarantors.2.email.required' => 'Guarantor 2 email is required.',
            'guarantors.1.phone.required' => 'Guarantor 1 phone number is required.',
            'guarantors.2.phone.required' => 'Guarantor 2 phone number is required.',
            'guarantors.1.address.required' => 'Guarantor 1 address is required.',
            'guarantors.2.address.required' => 'Guarantor 2 address is required.',
        ];
    }
}
