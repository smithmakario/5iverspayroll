<x-guest-layout full-width title="Login | 5ivers Payroll">
    <div class="relative w-full max-w-[1100px] min-h-[680px] bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col md:flex-row shadow-card">
        <div class="hidden md:flex md:w-1/2 relative bg-on-background items-center justify-center p-12 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-30 bg-gradient-to-br from-primary-container to-on-background"></div>
            <div class="relative z-10 text-white max-w-md">
                <x-application-logo class="h-12 w-auto mb-6 brightness-0 invert" />
                <h2 class="font-heading text-h2 text-white mb-4">Precision payroll for modern teams.</h2>
                <p class="text-body-lg text-white/80 leading-relaxed">
                    Manage employees, process payroll with approval workflows, and give your team a self-service portal — all in one efficient platform.
                </p>
                <div class="mt-8 flex gap-4 items-center p-4 bg-white/10 rounded-lg border border-white/10">
                    <span class="chip-success">In Transit</span>
                    <span class="chip-warning">Pending Approval</span>
                    <span class="chip-info">Delivered</span>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-center p-8 md:p-16 lg:p-20 bg-surface-container-lowest">
            <div class="mb-10 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="font-heading text-h3 text-on-background">5ivers Payroll</span>
                </div>
                <h1 class="font-heading text-h3 text-on-background">Sign in to your account</h1>
                <p class="text-body-md text-on-surface-variant mt-1">Enter your credentials to access the payroll workspace.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-primary-fixed text-on-primary-fixed rounded-lg text-body-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
                @csrf
                <div class="space-y-2">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-input @error('email') border-error @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus autocomplete="username" />
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="form-label" for="password">Password</label>
                    <div class="relative">
                        <input class="form-input pr-12 @error('password') border-error @enderror" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="current-password" />
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface" type="button" id="toggle-password" aria-label="Toggle password visibility">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-body-md text-on-surface-variant">
                        <input class="rounded border-outline-variant text-primary focus:ring-primary" id="remember_me" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} />
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-body-md font-medium text-primary hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <button class="btn-primary w-full" type="submit" id="login-submit">Sign In</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
        document.getElementById('login-form')?.addEventListener('submit', function () {
            const btn = document.getElementById('login-submit');
            btn.disabled = true;
            btn.textContent = 'Authenticating...';
        });
    </script>
</x-guest-layout>
