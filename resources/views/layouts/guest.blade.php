<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::getValue('site_name', config('app.name', 'Laravel')) }}</title>

        @php
            $favicon = \App\Models\Setting::getValue('site_favicon');
        @endphp
        @if($favicon)
            <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
        @endif

        @php
            $siteLogo = \App\Models\Setting::getValue('site_logo');
            $loginBg = \App\Models\Setting::getValue('login_bg');
            $primaryColor = \App\Models\Setting::getValue('primary_color', '#3B82F6');
        @endphp
        <style>
            :root { --primary-color: {{ $primaryColor }}; }
            .login-bg { background: {{ $loginBg ? "url('" . asset('storage/' . $loginBg) . "')" : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }} center/cover no-repeat; }
        </style>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="login-bg">
        <div class="font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
