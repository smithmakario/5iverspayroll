<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', '5ivers Payroll') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('asset/logo/logo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body @class([
        'antialiased',
        'min-h-screen flex items-center justify-center p-4' => $fullWidth,
    ])>
        @if ($fullWidth)
            {{ $slot }}
            <div class="fixed inset-0 -z-10 bg-surface pointer-events-none overflow-hidden">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-fixed/30 rounded-full blur-[120px] translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-tertiary-fixed/20 rounded-full blur-[100px] -translate-x-1/2 translate-y-1/2"></div>
            </div>
        @else
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <a href="/" class="mb-6">
                    <x-application-logo class="h-20 w-auto" />
                </a>
                <div class="w-full sm:max-w-md px-6 py-8 card shadow-card">
                    {{ $slot }}
                </div>
            </div>
        @endif
    </body>
</html>
