<?php

namespace App\Models;

use App\Enums\CompensationType;
use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\GuarantorStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'pay_grade_id',
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'employment_status',
        'employment_type',
        'compensation_type',
        'hourly_rate',
        'base_salary',
        'job_title',
        'bank_name',
        'bank_account_number',
        'bank_routing_number',
        'tax_id',
        'overtime_multiplier',
        'pto_balance',
        'profile_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'profile_confirmed_at' => 'datetime',
            'employment_status' => EmploymentStatus::class,
            'employment_type' => EmploymentType::class,
            'compensation_type' => CompensationType::class,
            'hourly_rate' => 'decimal:2',
            'base_salary' => 'decimal:2',
            'overtime_multiplier' => 'decimal:2',
            'pto_balance' => 'decimal:2',
        ];
    }

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function payGrade(): BelongsTo
    {
        return $this->belongsTo(PayGrade::class);
    }

    public function deductions(): HasMany
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(EmployeeEarning::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function guarantors(): HasMany
    {
        return $this->hasMany(EmployeeGuarantor::class)->orderBy('slot');
    }

    public function hasCompleteGuarantors(): bool
    {
        if ($this->guarantors()->count() < 2) {
            return false;
        }

        return $this->guarantors->every(
            fn (EmployeeGuarantor $guarantor) => filled($guarantor->full_name)
                && filled($guarantor->email)
                && filled($guarantor->phone)
                && filled($guarantor->address)
        );
    }

    public function allGuarantorsConfirmed(): bool
    {
        return $this->guarantors()->count() === 2
            && $this->guarantors()->where('status', GuarantorStatus::Confirmed)->count() === 2;
    }
}
