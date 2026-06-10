<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case HrManager = 'HR Manager';
    case Accountant = 'Accountant';
    case Employee = 'Employee';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
