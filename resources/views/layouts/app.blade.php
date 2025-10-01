@php
    $appName = \App\Helpers\SystemConfigHelper::get('app_name', config('app.name'));
    $faviconUrl = \App\Helpers\SystemConfigHelper::get('app_favicon') ? asset('storage/' . \App\Helpers\SystemConfigHelper::get('app_favicon')) : asset('favicon.ico');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') - {{ $appName }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Assets Compilados -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @keydown.window.escape="sidebarOpen = false">

        @auth
            <!-- Sidebar -->
            @if(request()->is('admin*'))
                @include('layouts.partials.admin-sidebar')
            @else
                @include('layouts.partials.member-sidebar')
            @endif
        @endauth

        <!-- Main Content -->
        <div class="transition-all duration-300 @auth lg:pl-72 @endauth">
            @auth
                <!-- Header -->
                @if(request()->is('admin*'))
                    @include('layouts.partials.admin-header')
                @else
                    @include('layouts.partials.member-header')
                @endif
            @endauth
            
            <main class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    @include('layouts.partials.feedback-messages')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>