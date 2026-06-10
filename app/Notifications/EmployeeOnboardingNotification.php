<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class EmployeeOnboardingNotification extends Notification
{
    use Queueable;

    public function __construct(public Employee $employee) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $token = Password::createToken($notifiable);

        $passwordUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $profileUrl = url(route('ess.profile', [], false));

        return (new MailMessage)
            ->subject('Welcome to 5ivers Payroll — Complete Your Onboarding')
            ->view('emails.employee-onboarding', [
                'name' => $notifiable->name,
                'employeeNumber' => $this->employee->employee_number,
                'passwordUrl' => $passwordUrl,
                'profileUrl' => $profileUrl,
                'logoUrl' => asset('asset/logo/logo.png'),
                'expireMinutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]);
    }
}
