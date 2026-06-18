@props(['active' => false, 'label'])

@php
$triggerClasses = $active
    ? 'inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-primary text-body-sm font-semibold leading-5 text-primary transition duration-150 ease-in-out'
    : 'inline-flex items-center gap-1 px-1 pt-1 border-b-2 border-transparent text-body-sm font-medium leading-5 text-on-surface-variant hover:text-on-surface hover:border-outline-variant transition duration-150 ease-in-out';
@endphp

<x-dropdown align="left" width="48" contentClasses="py-1 bg-surface-container-lowest">
    <x-slot name="trigger">
        <button type="button" class="{{ $triggerClasses }}">
            {{ $label }}
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        {{ $slot }}
    </x-slot>
</x-dropdown>
