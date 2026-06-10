<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployeeProfileConfirmed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole(UserRole::Employee->value)) {
            return $next($request);
        }

        $employee = $user->employee;

        if (! $employee || $employee->profile_confirmed_at) {
            return $next($request);
        }

        if ($request->routeIs(
            'ess.profile',
            'ess.profile.confirm',
            'ess.profile.bank',
            'ess.profile.guarantors',
            'profile.edit',
            'profile.update',
            'logout',
            'password.update',
            'verification.notice',
            'verification.verify',
            'verification.send',
        )) {
            return $next($request);
        }

        return redirect()
            ->route('ess.profile')
            ->with('error', 'Please review and confirm your profile details to continue.');
    }
}
