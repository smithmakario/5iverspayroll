<?php

namespace App\Http\Requests;

use App\Enums\CalculationType;
use App\Enums\EarningCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEarningTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $earningType = $this->route('earning_type');

        return [
            'name'             => ['required', 'string', 'max:100'],
            'code'             => ['required', 'string', 'max:20', Rule::unique('earning_types', 'code')->ignore($earningType)],
            'category'         => ['required', Rule::enum(EarningCategory::class)],
            'calculation_type' => ['required', Rule::enum(CalculationType::class)],
            'default_amount'   => ['nullable', 'numeric', 'min:0'],
            'default_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description'      => ['nullable', 'string'],
            'is_active'        => ['boolean'],
        ];
    }
}
