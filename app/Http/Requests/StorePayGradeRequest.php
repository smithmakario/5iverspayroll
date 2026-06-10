<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePayGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $payGrade = $this->route('pay_grade');

        return [
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:20', Rule::unique('pay_grades', 'code')->ignore($payGrade)],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'currency'    => ['required', 'string', 'size:3'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ];
    }
}
