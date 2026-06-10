<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Confirm Guarantor</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-2xl">
            <x-flash-messages />
            <div class="card card-body space-y-6">
                <div>
                    <h1 class="text-h3">Confirm guarantor {{ $guarantor->slot }}</h1>
                    <p class="text-body-sm text-on-surface-variant mt-1">
                        For {{ $employee->fullName() }} ({{ $employee->employee_number }})
                    </p>
                </div>

                <dl class="grid gap-4 sm:grid-cols-2 text-body-md">
                    <div>
                        <dt class="text-on-surface-variant">Full Name</dt>
                        <dd class="font-medium">{{ $guarantor->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-on-surface-variant">Email</dt>
                        <dd class="font-medium">{{ $guarantor->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-on-surface-variant">Phone</dt>
                        <dd class="font-medium">{{ $guarantor->phone }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-on-surface-variant">Address</dt>
                        <dd class="font-medium">{{ $guarantor->address }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('employees.guarantors.confirm.store', [$employee, $guarantor]) }}" class="flex flex-wrap gap-3">
                    @csrf
                    <button type="submit" class="btn-primary">Confirm Guarantor</button>
                    <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
