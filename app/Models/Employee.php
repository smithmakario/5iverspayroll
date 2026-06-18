<?php

namespace App\Models;

use App\Enums\CompensationType;
use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\GuarantorStatus;
use App\Models\PayrollRun;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'location_id',
        'pay_grade_id',
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'termination_date',
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
            'termination_date' => 'date',
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

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
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

    public function isEligibleForPayrollPeriod(Carbon $periodStart, Carbon $periodEnd): bool
    {
        if ($this->employment_status === EmploymentStatus::Active) {
            if ($this->hire_date->gt($periodEnd)) {
                return false;
            }

            if ($this->termination_date && $this->termination_date->lt($periodStart)) {
                return false;
            }

            return true;
        }

        if ($this->employment_status === EmploymentStatus::Terminated && $this->termination_date) {
            return $this->termination_date->between($periodStart, $periodEnd);
        }

        return false;
    }

    public static function eligibleForPayrollRun(PayrollRun $run): \Illuminate\Database\Eloquent\Collection
    {
        $periodStart = $run->period_start->copy()->startOfDay();
        $periodEnd = $run->period_end->copy()->startOfDay();

        return static::with(['payGrade', 'deductions.deductionType', 'earnings.earningType'])
            ->where(function ($query) use ($periodStart, $periodEnd) {
                $query->where('employment_status', EmploymentStatus::Active)
                    ->where('hire_date', '<=', $periodEnd)
                    ->where(function ($q) use ($periodStart) {
                        $q->whereNull('termination_date')
                            ->orWhere('termination_date', '>=', $periodStart);
                    });
            })
            ->orWhere(function ($query) use ($periodStart, $periodEnd) {
                $query->where('employment_status', EmploymentStatus::Terminated)
                    ->whereNotNull('termination_date')
                    ->whereBetween('termination_date', [$periodStart, $periodEnd]);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }
}
