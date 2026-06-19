@props(['column', 'label', 'sort', 'direction'])

@php
    $isActive = $sort === $column;
    $nextDirection = $isActive && $direction === 'asc' ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(array_merge(request()->query(), [
        'sort' => $column,
        'direction' => $nextDirection,
    ]));
@endphp

<th {{ $attributes->merge(['class' => 'sortable-th']) }}>
    <a href="{{ $url }}" @class(['sortable-th-link', 'is-active' => $isActive])">
        <span>{{ $label }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="sortable-th-icon" aria-hidden="true">
            @if ($isActive && $direction === 'desc')
                <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 0 1-1.08 1.04L10.75 5.612v10.638A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
            @else
                <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-4.158a.75.75 0 1 1 1.08 1.04l-5.25 5.5a.75.75 0 0 1-1.08 0l-5.25-5.5a.75.75 0 1 1 1.08-1.04l3.96 4.158V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
            @endif
        </svg>
    </a>
</th>
