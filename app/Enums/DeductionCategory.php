<?php

namespace App\Enums;

enum DeductionCategory: string
{
    case Tax = 'tax';
    case Statutory = 'statutory';
    case Voluntary = 'voluntary';
}
