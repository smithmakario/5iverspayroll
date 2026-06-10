<?php

namespace App\Models;

use App\Enums\GuarantorStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeGuarantor extends Model
{
    protected $fillable = [
        'employee_id',
        'slot',
        'full_name',
        'email',
        'phone',
        'address',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => GuarantorStatus::class,
            'confirmed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isConfirmed(): bool
    {
        return $this->status === GuarantorStatus::Confirmed;
    }
}
