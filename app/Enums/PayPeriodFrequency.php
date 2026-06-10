<?php

namespace App\Enums;

enum PayPeriodFrequency: string
{
    case Weekly = 'weekly';
    case BiWeekly = 'bi_weekly';
    case SemiMonthly = 'semi_monthly';
    case Monthly = 'monthly';

    public function label(): string
    {
        return match ($this) {
            self::Weekly => 'Weekly',
            self::BiWeekly => 'Bi-weekly',
            self::SemiMonthly => 'Semi-monthly',
            self::Monthly => 'Monthly',
        };
    }
}
