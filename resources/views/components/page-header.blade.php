@props(['title', 'subtitle' => null, 'actionLabel' => null, 'actionRoute' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-h3 md:text-h2">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-body-md text-on-surface-variant mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($actionRoute)
        <a href="{{ $actionRoute }}" class="btn-primary shrink-0">
            {{ $actionLabel }}
        </a>
    @endif
</div>
