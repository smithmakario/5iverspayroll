<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Edit Attendance</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-2xl">
            <x-flash-messages />
            <div class="card card-body">
                <form method="POST" action="{{ route('attendance.update', $attendance) }}" class="space-y-6">
                    @csrf @method('PUT')
                    @include('attendance._form', ['attendance' => $attendance])
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">Update</button>
                        <a href="{{ route('attendance.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
