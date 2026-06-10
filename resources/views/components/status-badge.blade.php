@props(['status'])

@php
    $colors = [
        'active'      => 'chip-success',
        'on_leave'    => 'chip-warning',
        'terminated'  => 'chip-danger',
        'draft'       => 'chip-neutral',
        'processing'  => 'chip-info',
        'completed'   => 'chip-success',
        'locked'      => 'chip-neutral',
        'present'     => 'chip-success',
        'absent'      => 'chip-danger',
        'late'        => 'chip-warning',
        'half_day'    => 'chip-warning',
        'on_leave_att' => 'chip-info',
        'pending'     => 'chip-warning',
        'confirmed'   => 'chip-success',
        'approved'    => 'chip-success',
        'rejected'    => 'chip-danger',
        'full_time'   => 'chip-info',
        'part_time'   => 'chip-neutral',
        'contractor'  => 'chip-warning',
        'salary'      => 'chip-info',
        'hourly'      => 'chip-neutral',
    ];
    $raw   = is_object($status) ? $status->value : $status;
    $class = $colors[$raw] ?? 'chip-neutral';
    $label = ucwords(str_replace('_', ' ', $raw));
@endphp

<span class="{{ $class }}">
    {{ $label }}
</span>
