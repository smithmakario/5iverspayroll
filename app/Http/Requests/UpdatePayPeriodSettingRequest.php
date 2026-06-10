<?php

namespace App\Http\Requests;

use App\Enums\PayPeriodFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayPeriodSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'frequency' => ['required', Rule::enum(PayPeriodFrequency::class)],
            'overtime_threshold_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'default_overtime_multiplier' => ['required', 'numeric', 'min:1', 'max:3'],
        ];
    }
}
