<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\EmployeeOnboardingNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeProvisioningService
{
    public function provision(Employee $employee, bool $sendEmail = true): User
    {
        if ($employee->user_id) {
            $user = $employee->user;

            if ($sendEmail) {
                $this->sendOnboardingEmail($user, $employee);
            }

            return $user;
        }

        $existingUser = User::where('email', $employee->email)->first();

        if ($existingUser) {
            if ($existingUser->employee && $existingUser->employee->id !== $employee->id) {
                throw ValidationException::withMessages([
                    'email' => 'This email is already linked to another employee account.',
                ]);
            }

            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $employee->fullName(),
                'email' => $employee->email,
                'password' => Hash::make(Str::random(40)),
            ]);

            if (! $user->hasRole(UserRole::Employee->value)) {
                $user->assignRole(UserRole::Employee->value);
            }
        }

        $employee->update([
            'user_id' => $user->id,
            'profile_confirmed_at' => null,
        ]);

        $this->syncUserFromEmployee($employee, $user);

        if ($sendEmail) {
            $this->sendOnboardingEmail($user, $employee);
        }

        PayrollAuditLogger::log('employee.user_provisioned', $employee, [
            'user_id' => $user->id,
        ]);

        return $user;
    }

    public function linkRegisteredUser(User $user): ?Employee
    {
        $employee = Employee::where('email', $user->email)
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')->orWhere('user_id', $user->id);
            })
            ->first();

        if (! $employee) {
            return null;
        }

        $employee->update(['user_id' => $user->id]);

        if (! $user->hasRole(UserRole::Employee->value)) {
            $user->assignRole(UserRole::Employee->value);
        }

        $this->syncUserFromEmployee($employee, $user);

        return $employee;
    }

    public function syncUserFromEmployee(Employee $employee, ?User $user = null): void
    {
        $user ??= $employee->user;

        if (! $user) {
            return;
        }

        $user->update([
            'name' => $employee->fullName(),
            'email' => $employee->email,
        ]);
    }

    public function sendOnboardingEmail(User $user, Employee $employee): void
    {
        $user->notify(new EmployeeOnboardingNotification($employee));
    }
}
