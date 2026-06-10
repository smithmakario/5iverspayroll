@props(['user', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-14 w-14 text-base',
        'xl' => 'h-16 w-16 text-lg',
        'xxl' => 'h-20 w-20 text-xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if ($user->avatarUrl())
    <img
        src="{{ $user->avatarUrl() }}"
        alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "rounded object-cover shrink-0 {$sizeClass}"]) }}
    >
@else
    <span
        {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full bg-primary font-semibold text-on-primary shrink-0 {$sizeClass}"]) }}
        aria-hidden="true"
    >
        {{ $user->avatarInitials() }}
    </span>
@endif
