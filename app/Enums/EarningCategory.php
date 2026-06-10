<?php

namespace App\Enums;

enum EarningCategory: string
{
    case Bonus = 'bonus';
    case Commission = 'commission';
    case Allowance = 'allowance';

    public function label(): string
    {
        return match ($this) {
            self::Bonus => 'Bonus',
            self::Commission => 'Commission',
            self::Allowance => 'Allowance',
        };
    }
}
