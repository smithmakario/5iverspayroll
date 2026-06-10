<nav x-data="{ open: false }" class="bg-surface-container-lowest border-b border-outline-variant">
    <div class="container-app">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="h-9 w-auto" />
                </a>

                <div class="hidden sm:flex sm:space-x-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

                    @role(\App\Enums\UserRole::Employee->value)
                        <x-nav-link :href="route('ess.dashboard')" :active="request()->routeIs('ess.dashboard')">My Portal</x-nav-link>
                        <x-nav-link :href="route('ess.attendance')" :active="request()->routeIs('ess.attendance*')">Time Clock</x-nav-link>
                        <x-nav-link :href="route('ess.payslips')" :active="request()->routeIs('ess.payslips', 'payslips.show', 'payslips.pdf')">Payslips</x-nav-link>
                        <x-nav-link :href="route('ess.leave')" :active="request()->routeIs('ess.leave')">Leave</x-nav-link>
                        <x-nav-link :href="route('ess.profile')" :active="request()->routeIs('ess.profile', 'ess.profile.*')">My Profile</x-nav-link>
                    @endrole

                    @hasanyrole(\App\Enums\UserRole::Admin->value.'|'.\App\Enums\UserRole::HrManager->value)
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">Employees</x-nav-link>
                        <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">Departments</x-nav-link>
                        <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">Attendance</x-nav-link>
                        <x-nav-link :href="route('leave-requests.index')" :active="request()->routeIs('leave-requests.*')">Leave</x-nav-link>
                    @endhasanyrole

                    @hasanyrole(\App\Enums\UserRole::Admin->value.'|'.\App\Enums\UserRole::Accountant->value)
                        <x-nav-link :href="route('pay-grades.index')" :active="request()->routeIs('pay-grades.*')">Pay Grades</x-nav-link>
                        <x-nav-link :href="route('deduction-types.index')" :active="request()->routeIs('deduction-types.*')">Deductions</x-nav-link>
                        <x-nav-link :href="route('payroll-runs.index')" :active="request()->routeIs('payroll-runs.*')">Payroll</x-nav-link>
                    @endhasanyrole

                    @role(\App\Enums\UserRole::Admin->value)
                        <x-nav-link :href="route('earning-types.index')" :active="request()->routeIs('earning-types.*')">Bonuses</x-nav-link>
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-body-sm font-medium text-on-surface-variant hover:text-on-surface transition">
                            <x-user-avatar :user="Auth::user()" size="sm" />
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded text-on-surface-variant hover:bg-surface-container-low">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-outline-variant">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            @role(\App\Enums\UserRole::Employee->value)
                <x-responsive-nav-link :href="route('ess.dashboard')" :active="request()->routeIs('ess.dashboard')">My Portal</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ess.attendance')" :active="request()->routeIs('ess.attendance*')">Time Clock</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ess.payslips')" :active="request()->routeIs('ess.payslips', 'payslips.show', 'payslips.pdf')">Payslips</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ess.leave')" :active="request()->routeIs('ess.leave')">Leave</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ess.profile')" :active="request()->routeIs('ess.profile', 'ess.profile.*')">My Profile</x-responsive-nav-link>
            @endrole
            @hasanyrole(\App\Enums\UserRole::Admin->value.'|'.\App\Enums\UserRole::HrManager->value)
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">Employees</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">Departments</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">Attendance</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('leave-requests.index')" :active="request()->routeIs('leave-requests.*')">Leave</x-responsive-nav-link>
            @endhasanyrole
            @hasanyrole(\App\Enums\UserRole::Admin->value.'|'.\App\Enums\UserRole::Accountant->value)
                <x-responsive-nav-link :href="route('pay-grades.index')" :active="request()->routeIs('pay-grades.*')">Pay Grades</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('deduction-types.index')" :active="request()->routeIs('deduction-types.*')">Deductions</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payroll-runs.index')" :active="request()->routeIs('payroll-runs.*')">Payroll</x-responsive-nav-link>
            @endhasanyrole
            @role(\App\Enums\UserRole::Admin->value)
                <x-responsive-nav-link :href="route('earning-types.index')" :active="request()->routeIs('earning-types.*')">Bonuses</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-responsive-nav-link>
            @endrole
        </div>
        <div class="pt-4 pb-3 border-t border-outline-variant px-4">
            <div class="flex items-center gap-3 mb-2">
                <x-user-avatar :user="Auth::user()" size="md" />
                <div class="font-medium text-on-surface">{{ Auth::user()->name }}</div>
            </div>
            <div class="text-body-sm text-on-surface-variant">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
