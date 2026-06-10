<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Edit Employee</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-4xl">
            <x-flash-messages />
            <div class="card card-body">
                <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-6">
                    @csrf @method('PATCH')
                    @include('employees._form', compact('employee'))
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">Update Employee</button>
                        <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
