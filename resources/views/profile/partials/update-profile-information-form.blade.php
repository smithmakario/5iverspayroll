<section>
    <header class="mb-6">
        <h2 class="font-heading text-h3 text-on-surface">{{ __('Profile Information') }}</h2>
        <p class="mt-1 text-body-sm text-on-surface-variant">
            {{ __("Update your account's profile information, avatar, and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row items-start gap-6">
            <x-user-avatar :user="$user" size="lg" />
            <div class="flex-1 space-y-4 w-full">
                <div>
                    <label class="form-label" for="avatar">{{ __('Profile Photo') }}</label>
                    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="form-input">
                    <p class="mt-1 text-body-sm text-on-surface-variant">JPG, PNG, or WebP. Max 10 MB — resized to 320px automatically.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>
                @if ($user->avatar_path)
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-outline-variant text-primary focus:ring-primary">
                        <span class="text-body-sm text-on-surface-variant">{{ __('Remove current photo') }}</span>
                    </label>
                @endif
            </div>
        </div>

        <div>
            <label class="form-label" for="name">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label class="form-label" for="email">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-body-sm text-on-surface-variant">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="link-primary">{{ __('Click here to re-send the verification email.') }}</button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-body-sm text-emerald-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-body-sm text-on-surface-variant"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
