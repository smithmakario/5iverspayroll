<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attendance = $this->route('attendance');

        return [
            'employee_id'    => ['required', 'exists:employees,id'],
            'date'           => ['required', 'date', Rule::unique('attendances')->where('employee_id', $this->employee_id)->ignore($attendance)],
            'clock_in'       => ['nullable', 'date_format:H:i'],
            'clock_out'      => ['nullable', 'date_format:H:i', 'after:clock_in'],
            'break_minutes'  => ['nullable', 'integer', 'min:0'],
            'status'         => ['required', Rule::enum(AttendanceStatus::class)],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
