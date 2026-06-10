<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">My Portal</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />

            <div class="flex flex-wrap items-center gap-5 mb-8">
                <x-user-avatar :user="auth()->user()" size="xxl" />
                <div>
                    <h1 class="text-h3 font-heading text-on-surface">Welcome, {{ $employee->first_name }}</h1>
                    <p class="text-body-sm text-on-surface-variant">Employee #{{ $employee->employee_number }} &bull; {{ $employee->job_title ?? 'Team Member' }}</p>
                    <a href="{{ route('profile.edit') }}" class="link-primary text-body-sm mt-1 inline-block">Update profile photo</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="stat-card">
                    <p class="stat-label">Latest Net Pay</p>
                    <p class="stat-value">{{ $latestPayslip ? number_format($latestPayslip->net_pay, 2) : '—' }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">YTD Earnings</p>
                    <p class="stat-value">{{ number_format($ytdNetPay, 2) }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">PTO Balance</p>
                    <p class="stat-value">{{ number_format($employee->pto_balance, 1) }} days</p>
                </div>
            </div>

            @php $todayAttendance = auth()->user()->employee?->attendances()->whereDate('date', today())->first(); @endphp
            @if ($todayAttendance?->isOnShift())
                <div class="mb-6 rounded-lg border border-primary/20 bg-primary-container/20 p-4 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-body-md text-on-surface">You are <strong>clocked in</strong> since {{ $todayAttendance->formattedClockIn() }}.</p>
                    <form method="POST" action="{{ route('ess.attendance.clock-out') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Clock Out</button>
                    </form>
                </div>
            @elseif (! $todayAttendance?->clock_out)
                <div class="mb-6 rounded-lg border border-outline-variant bg-surface-container-low p-4 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-body-md text-on-surface-variant">Start your shift for today.</p>
                    <form method="POST" action="{{ route('ess.attendance.clock-in') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Clock In</button>
                    </form>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('ess.attendance') }}" class="card-interactive card-body">
                    <h3 class="text-h3 mb-2">Time Clock</h3>
                    <p class="text-body-sm text-on-surface-variant">Clock in, clock out, and view your attendance history.</p>
                </a>
                <a href="{{ route('ess.payslips') }}" class="card-interactive card-body">
                    <h3 class="text-h3 mb-2">Payslips</h3>
                    <p class="text-body-sm text-on-surface-variant">Download historical payslips and tax documents.</p>
                </a>
                <a href="{{ route('ess.leave') }}" class="card-interactive card-body">
                    <h3 class="text-h3 mb-2">Time Off</h3>
                    <p class="text-body-sm text-on-surface-variant">{{ $pendingLeave }} pending request(s).</p>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <a href="{{ route('ess.profile') }}" class="card-interactive card-body">
                    <h3 class="text-h3 mb-2">My Profile</h3>
                    <p class="text-body-sm text-on-surface-variant">Update bank details and tax withholding.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
