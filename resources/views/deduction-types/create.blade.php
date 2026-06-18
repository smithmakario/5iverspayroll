<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">New Deduction Type</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-3xl">
            <x-flash-messages />
            <div class="card card-body">
                <form method="POST" action="{{ route('deduction-types.store') }}" class="space-y-6">
                    @csrf
                    @include('deduction-types._form', ['deductionType' => null])
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">Create</button>
                        <a href="{{ route('deduction-types.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
