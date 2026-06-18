@php
    $statuses = \App\Enums\EmploymentStatus::cases();
    $employmentTypes = \App\Enums\EmploymentType::cases();
    $compensationTypes = \App\Enums\CompensationType::cases();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    @if ($employee)
        <div>
            <label class="form-label">Employee Number</label>
            <p class="form-input bg-surface-container-low text-on-surface font-mono">{{ $employee->employee_number }}</p>
            <p class="text-body-sm text-on-surface-variant mt-1">Auto-assigned and cannot be changed.</p>
        </div>
    @else
        <div class="sm:col-span-2">
            <p class="text-body-sm text-on-surface-variant bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3">
                Employee number will be assigned automatically (format <span class="font-mono">0001</span>–<span class="font-mono">9999</span>).
            </p>
        </div>
    @endif
    <div>
        <label class="form-label" for="job_title">Job Title</label>
        <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $employee?->job_title) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="first_name">First Name *</label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee?->first_name) }}" class="form-input" required>
        @error('first_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="last_name">Last Name *</label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee?->last_name) }}" class="form-input" required>
        @error('last_name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="email">Email *</label>
        <input type="email" name="email" id="email" value="{{ old('email', $employee?->email) }}" class="form-input" required>
        @error('email') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="phone">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee?->phone) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="department_id">Department</label>
        <select name="department_id" id="department_id" class="form-select">
            <option value="">— Select —</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $employee?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="location_id">Location</label>
        <select name="location_id" id="location_id" class="form-select">
            <option value="">— Select —</option>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}" {{ old('location_id', $employee?->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="pay_grade_id">Pay Grade</label>
        <select name="pay_grade_id" id="pay_grade_id" class="form-select">
            <option value="">— Select —</option>
            @foreach ($payGrades as $grade)
                <option value="{{ $grade->id }}" {{ old('pay_grade_id', $employee?->pay_grade_id) == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }} ({{ number_format($grade->base_salary, 2) }} {{ $grade->currency }})
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="hire_date">Hire Date *</label>
        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $employee?->hire_date?->format('Y-m-d')) }}" class="form-input" required>
        @error('hire_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="termination_date">Termination Date</label>
        <input type="date" name="termination_date" id="termination_date" value="{{ old('termination_date', $employee?->termination_date?->format('Y-m-d')) }}" class="form-input">
        <p class="mt-1 text-body-sm text-on-surface-variant">Set for mid-month leavers. Salary will be prorated for this period.</p>
        @error('termination_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="employment_status">Employment Status *</label>
        <select name="employment_status" id="employment_status" class="form-select" required>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" {{ old('employment_status', $employee?->employment_status?->value) === $status->value ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $status->value)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="employment_type">Employment Type *</label>
        <select name="employment_type" id="employment_type" class="form-select" required>
            @foreach ($employmentTypes as $type)
                <option value="{{ $type->value }}" {{ old('employment_type', $employee?->employment_type?->value ?? 'full_time') === $type->value ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $type->value)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="compensation_type">Compensation Type *</label>
        <select name="compensation_type" id="compensation_type" class="form-select" required>
            @foreach ($compensationTypes as $type)
                <option value="{{ $type->value }}" {{ old('compensation_type', $employee?->compensation_type?->value ?? 'salary') === $type->value ? 'selected' : '' }}>
                    {{ ucfirst($type->value) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="base_salary">Base Salary</label>
        <input type="number" step="0.01" name="base_salary" id="base_salary" value="{{ old('base_salary', $employee?->base_salary) }}" class="form-input" placeholder="Overrides pay grade if set">
    </div>
    <div>
        <label class="form-label" for="hourly_rate">Hourly Rate</label>
        <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $employee?->hourly_rate) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="overtime_multiplier">Overtime Multiplier</label>
        <input type="number" step="0.1" name="overtime_multiplier" id="overtime_multiplier" value="{{ old('overtime_multiplier', $employee?->overtime_multiplier ?? 1.5) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="pto_balance">PTO Balance (days)</label>
        <input type="number" step="0.5" name="pto_balance" id="pto_balance" value="{{ old('pto_balance', $employee?->pto_balance ?? 0) }}" class="form-input">
    </div>
</div>

<hr class="my-6 border-outline-variant">
<p class="form-label mb-3">Bank & Tax Details (Direct Deposit)</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label class="form-label" for="bank_name">Bank Name</label>
        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $employee?->bank_name) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="bank_account_number">Account Number</label>
        <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $employee?->bank_account_number) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="bank_routing_number">Routing Number</label>
        <input type="text" name="bank_routing_number" id="bank_routing_number" value="{{ old('bank_routing_number', $employee?->bank_routing_number) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="tax_id">Tax ID</label>
        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $employee?->tax_id) }}" class="form-input">
    </div>
</div>
