<?php

namespace App\Enums;

enum GuarantorStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
}
