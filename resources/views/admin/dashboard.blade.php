<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading text-h3">Admin Console</h2>
    </x-slot>

    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card card-body">
                    <x-page-header title="Pay Period Settings" subtitle="Configure payroll frequency and overtime rules" />
                    <dl class="space-y-3 text-body-md">
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Frequency</dt><dd class="font-medium">{{ $settings->frequency->label() }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Overtime Threshold</dt><dd class="font-medium">{{ $settings->overtime_threshold_hours }} hrs/week</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Overtime Multiplier</dt><dd class="font-medium">{{ $settings->default_overtime_multiplier }}x</dd></div>
                    </dl>
                    <a href="{{ route('admin.pay-period-settings.edit') }}" class="btn-secondary mt-6 inline-flex">Edit Settings</a>
                </div>

                <div class="card card-body">
                    <x-page-header title="Compliance" subtitle="Immutable payroll audit trail" />
                    <p class="text-body-sm text-on-surface-variant">All payroll actions are logged for financial compliance and year-end reporting.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-page-header title="Audit Log" subtitle="Recent payroll system activity" />
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>User</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td><span class="chip-neutral">{{ $log->action }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-8 text-on-surface-variant">No audit entries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
