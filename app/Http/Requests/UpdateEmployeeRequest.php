<?php

namespace App\Http\Requests;

use App\Enums\CompensationType;
use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee)],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'pay_grade_id' => ['nullable', 'exists:pay_grades,id'],
            'hire_date' => ['required', 'date'],
            'employment_status' => ['required', Rule::enum(EmploymentStatus::class)],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'compensation_type' => ['required', Rule::enum(CompensationType::class)],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'base_salary' => ['nullable', 'numeric', 'min:0'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:30'],
            'bank_routing_number' => ['nullable', 'string', 'max:30'],
            'tax_id' => ['nullable', 'string', 'max:30'],
            'overtime_multiplier' => ['nullable', 'numeric', 'min:1', 'max:3'],
            'pto_balance' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
