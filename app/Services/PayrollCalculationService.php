<?php

namespace App\Services;

use App\Enums\CompensationType;
use App\Enums\PayslipItemType;
use App\Models\Employee;
use App\Models\PayPeriodSetting;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipItem;
use Carbon\Carbon;

class PayrollCalculationService
{
    public function calculateGrossPay(Employee $employee, PayrollRun $run): array
    {
        $settings = PayPeriodSetting::current();
        $periodStart = Carbon::parse($run->period_start);
        $periodEnd = Carbon::parse($run->period_end);

        $attendance = $employee->attendances()
            ->where('is_approved', true)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->get();

        $totalHours = (float) $attendance->sum('hours_worked');
        $daysWorked = $attendance->count();

        $regularHours = min($totalHours, $settings->overtime_threshold_hours);
        $overtimeHours = max(0, $totalHours - $settings->overtime_threshold_hours);
        $multiplier = (float) ($employee->overtime_multiplier ?: $settings->default_overtime_multiplier);

        $earnings = [];

        if ($employee->compensation_type === CompensationType::Hourly) {
            $hourlyRate = (float) ($employee->hourly_rate ?? 0);
            $regularPay = round($regularHours * $hourlyRate, 2);
            $overtimePay = round($overtimeHours * $hourlyRate * $multiplier, 2);

            if ($regularPay > 0) {
                $earnings[] = ['description' => 'Regular Hours', 'amount' => $regularPay];
            }
            if ($overtimePay > 0) {
                $earnings[] = ['description' => 'Overtime ('.$multiplier.'x)', 'amount' => $overtimePay];
            }
        } else {
            $baseSalary = (float) ($employee->base_salary ?? $employee->payGrade?->base_salary ?? 0);
            $earnings[] = ['description' => 'Basic Salary', 'amount' => $baseSalary];

            if ($overtimeHours > 0 && $employee->hourly_rate) {
                $overtimePay = round($overtimeHours * (float) $employee->hourly_rate * $multiplier, 2);
                $earnings[] = ['description' => 'Overtime ('.$multiplier.'x)', 'amount' => $overtimePay];
            }
        }

        $grossPay = round(collect($earnings)->sum('amount'), 2);

        return [
            'earnings' => $earnings,
            'base_gross_pay' => $grossPay,
            'days_worked' => $daysWorked,
            'hours_worked' => $totalHours,
        ];
    }

    public function applyAdditionalEarnings(Employee $employee, float $baseGrossPay, Carbon $periodEnd): array
    {
        $earnings = [];
        $total = 0;

        $activeEarnings = $employee->earnings()
            ->with('earningType')
            ->where('is_active', true)
            ->where(function ($query) use ($periodEnd) {
                $query->whereNull('effective_from')->orWhere('effective_from', '<=', $periodEnd);
            })
            ->where(function ($query) use ($periodEnd) {
                $query->whereNull('effective_to')->orWhere('effective_to', '>=', $periodEnd);
            })
            ->get();

        foreach ($activeEarnings as $earning) {
            $type = $earning->earningType;

            if (! $type->is_active) {
                continue;
            }

            $amount = $earning->amount
                ?? ($earning->rate ? ($earning->rate / 100 * $baseGrossPay) : null)
                ?? ($type->default_amount ?? ($type->default_rate ? ($type->default_rate / 100 * $baseGrossPay) : 0));

            $amount = round((float) $amount, 2);

            if ($amount <= 0) {
                continue;
            }

            $earnings[] = [
                'earning_type_id' => $type->id,
                'description' => $type->name,
                'amount' => $amount,
                'category' => $type->category->value,
            ];

            $total += $amount;
        }

        return [
            'earnings' => $earnings,
            'total_additional' => round($total, 2),
        ];
    }

    public function applyDeductions(Employee $employee, float $grossPay, Carbon $periodEnd): array
    {
        $deductions = [];
        $totalTax = 0;
        $totalOther = 0;

        $activeDeductions = $employee->deductions()
            ->with('deductionType')
            ->where('is_active', true)
            ->where(function ($query) use ($periodEnd) {
                $query->whereNull('effective_from')->orWhere('effective_from', '<=', $periodEnd);
            })
            ->where(function ($query) use ($periodEnd) {
                $query->whereNull('effective_to')->orWhere('effective_to', '>=', $periodEnd);
            })
            ->get();

        foreach ($activeDeductions as $deduction) {
            $type = $deduction->deductionType;
            $amount = $deduction->amount
                ?? ($deduction->rate ? ($deduction->rate / 100 * $grossPay) : null)
                ?? ($type->default_amount ?? ($type->default_rate ? ($type->default_rate / 100 * $grossPay) : 0));

            $amount = round((float) $amount, 2);

            $deductions[] = [
                'deduction_type_id' => $type->id,
                'description' => $type->name,
                'amount' => $amount,
                'category' => $type->category->value,
            ];

            if ($type->category->value === 'tax') {
                $totalTax += $amount;
            } else {
                $totalOther += $amount;
            }
        }

        return [
            'deductions' => $deductions,
            'total_tax' => round($totalTax, 2),
            'total_deductions' => round($totalOther, 2),
        ];
    }

    public function generatePayslip(Employee $employee, PayrollRun $run): Payslip
    {
        $periodEnd = Carbon::parse($run->period_end);
        $payData = $this->calculateGrossPay($employee, $run);
        $additionalData = $this->applyAdditionalEarnings($employee, $payData['base_gross_pay'], $periodEnd);

        $grossPay = round($payData['base_gross_pay'] + $additionalData['total_additional'], 2);
        $deductionData = $this->applyDeductions($employee, $grossPay, $periodEnd);

        $totalEarnings = $grossPay;
        $netPay = round($totalEarnings - $deductionData['total_tax'] - $deductionData['total_deductions'], 2);

        $payslip = Payslip::create([
            'payroll_run_id' => $run->id,
            'employee_id' => $employee->id,
            'pay_grade_id' => $employee->pay_grade_id,
            'gross_pay' => $grossPay,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $deductionData['total_deductions'],
            'total_tax' => $deductionData['total_tax'],
            'net_pay' => $netPay,
            'days_worked' => $payData['days_worked'],
            'hours_worked' => $payData['hours_worked'],
        ]);

        foreach ($payData['earnings'] as $earning) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'type' => PayslipItemType::Earning,
                'description' => $earning['description'],
                'amount' => $earning['amount'],
            ]);
        }

        foreach ($additionalData['earnings'] as $earning) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'earning_type_id' => $earning['earning_type_id'],
                'type' => PayslipItemType::Earning,
                'description' => $earning['description'],
                'amount' => $earning['amount'],
            ]);
        }

        foreach ($deductionData['deductions'] as $deduction) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'deduction_type_id' => $deduction['deduction_type_id'],
                'type' => PayslipItemType::Deduction,
                'description' => $deduction['description'],
                'amount' => $deduction['amount'],
            ]);
        }

        return $payslip;
    }
}
