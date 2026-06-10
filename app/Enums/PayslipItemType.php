<?php

namespace App\Enums;

enum PayslipItemType: string
{
    case Earning = 'earning';
    case Deduction = 'deduction';
}
