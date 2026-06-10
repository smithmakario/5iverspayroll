<?php

namespace App\Http\Requests;

use App\Enums\CalculationType;
use App\Enums\DeductionCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeductionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $deductionType = $this->route('deduction_type');

        return [
            'name'             => ['required', 'string', 'max:100'],
            'code'             => ['required', 'string', 'max:20', Rule::unique('deduction_types', 'code')->ignore($deductionType)],
            'category'         => ['required', Rule::enum(DeductionCategory::class)],
            'calculation_type' => ['required', Rule::enum(CalculationType::class)],
            'default_amount'   => ['nullable', 'numeric', 'min:0'],
            'default_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description'      => ['nullable', 'string'],
            'is_active'        => ['boolean'],
        ];
    }
}
