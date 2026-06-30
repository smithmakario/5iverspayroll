<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payslip;
use App\Models\User;

class PayslipPolicy
{
    public function view(User $user, Payslip $payslip): bool
    {
        if ($user->hasAnyRole([UserRole::Admin->value, UserRole::Accountant->value, UserRole::HrManager->value])) {
            return true;
        }

        return $this->employeeOwnsPayslip($user, $payslip);
    }

    private function employeeOwnsPayslip(User $user, Payslip $payslip): bool
    {
        $payslip->loadMissing('employee');

        if ($payslip->employee?->user_id !== null
            && (int) $payslip->employee->user_id === (int) $user->id) {
            return true;
        }

        if ($user->employee !== null
            && (int) $user->employee->id === (int) $payslip->employee_id) {
            return true;
        }

        $employeeEmail = strtolower($payslip->employee?->email ?? '');
        $userEmail = strtolower($user->email);

        return $employeeEmail !== '' && $employeeEmail === $userEmail;
    }

    public function download(User $user, Payslip $payslip): bool
    {
        return $this->view($user, $payslip);
    }
}
