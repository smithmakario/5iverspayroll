@if ($errors->any())
    <div class="mb-4 rounded-lg bg-error-container border border-error/20 p-4 text-body-sm text-on-error-container">
        <p class="font-semibold">Please fix the following:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-body-sm text-emerald-900">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-lg bg-error-container border border-error/20 p-4 text-body-sm text-on-error-container">
        {{ session('error') }}
    </div>
@endif
