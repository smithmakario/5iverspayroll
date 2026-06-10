<?php

namespace App\Models;

use App\Enums\PayPeriodFrequency;
use Illuminate\Database\Eloquent\Model;

class PayPeriodSetting extends Model
{
    protected $fillable = [
        'frequency',
        'overtime_threshold_hours',
        'default_overtime_multiplier',
    ];

    protected function casts(): array
    {
        return [
            'frequency' => PayPeriodFrequency::class,
            'overtime_threshold_hours' => 'integer',
            'default_overtime_multiplier' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'frequency' => PayPeriodFrequency::Monthly,
            'overtime_threshold_hours' => 40,
            'default_overtime_multiplier' => 1.5,
        ]);
    }
}
