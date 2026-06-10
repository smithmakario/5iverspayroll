<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Validation\ValidationException;

class EmployeeNumberGenerator
{
    public const MIN = 1;

    public const MAX = 9999;

    public function next(): string
    {
        $max = Employee::query()
            ->lockForUpdate()
            ->pluck('employee_number')
            ->map(function (string $number): ?int {
                if (! preg_match('/^\d{1,4}$/', $number)) {
                    return null;
                }

                return (int) $number;
            })
            ->filter(fn (?int $value) => $value !== null)
            ->max();

        $next = $max !== null ? $max + 1 : self::MIN;

        if ($next > self::MAX) {
            throw ValidationException::withMessages([
                'employee_number' => 'Employee number limit reached (0001–9999).',
            ]);
        }

        return str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
