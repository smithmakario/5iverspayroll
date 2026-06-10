<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_minutes',
        'hours_worked',
        'status',
        'notes',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hours_worked' => 'decimal:2',
            'status' => AttendanceStatus::class,
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isOnShift(): bool
    {
        return filled($this->clock_in) && blank($this->clock_out);
    }

    public function formattedClockIn(): ?string
    {
        return $this->formatClockTime($this->clock_in);
    }

    public function formattedClockOut(): ?string
    {
        return $this->formatClockTime($this->clock_out);
    }

    private function formatClockTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }

        return \Carbon\Carbon::createFromFormat(
            strlen($time) === 5 ? 'H:i' : 'H:i:s',
            $time
        )->format('h:i A');
    }
}
