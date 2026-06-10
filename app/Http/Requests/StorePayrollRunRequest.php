<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePayrollRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'period_start' => ['required', 'date'],
            'period_end'   => [
                'required',
                'date',
                'after_or_equal:period_start',
                Rule::unique('payroll_runs')->where(fn ($query) => $query->where('period_start', $this->input('period_start'))),
            ],
            'payment_date' => ['nullable', 'date', 'after_or_equal:period_end'],
            'notes'        => ['nullable', 'string'],
            'process_now'  => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'period_end.unique' => 'A payroll run already exists for this pay period.',
            'payment_date.after_or_equal' => 'Payment date must be on or after the period end date.',
        ];
    }
}
