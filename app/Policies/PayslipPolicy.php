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

        return $user->employee?->id === $payslip->employee_id;
    }

    public function download(User $user, Payslip $payslip): bool
    {
        return $this->view($user, $payslip);
    }
}
