<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Add Employee</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-4xl">
            <x-flash-messages />
            <div class="card card-body">
                <form method="POST" action="{{ route('employees.store') }}" class="space-y-6">
                    @csrf
                    @include('employees._form', ['employee' => null])
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">Save Employee</button>
                        <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
