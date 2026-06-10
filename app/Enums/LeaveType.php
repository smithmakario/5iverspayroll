<?php

namespace App\Enums;

enum LeaveType: string
{
    case Vacation = 'vacation';
    case Sick = 'sick';
    case Personal = 'personal';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
