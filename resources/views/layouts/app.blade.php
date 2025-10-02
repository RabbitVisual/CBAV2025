<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }"
      class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // Usando o model de configuração com cache para eficiência
        $appName = \App\Models\Configuracao::get('app_name', config('app.name', 'Painel'));
        $faviconUrl = \App\Models\Configuracao::get('app_favicon')
                      ? Illuminate\Support\Facades\Storage::url(\App\Models\Configuracao::get('app_favicon'))
                      : asset('favicon.ico');
    @endphp

    <title>@yield('title', 'Dashboard') - {{ $appName }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="h-full font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">

        @auth
            @if(request()->is('admin*'))
                <x-admin.sidebar />
            @else
                {{-- O sidebar do membro pode ser componentizado da mesma forma no futuro --}}
                @include('layouts.partials.member-sidebar')
            @endif
        @endauth

        <div class="lg:pl-72">
            @auth
                @if(request()->is('admin*'))
                    <x-admin.header />
                @else
                     {{-- O header do membro pode ser componentizado da mesma forma no futuro --}}
                    @include('layouts.partials.member-header')
                @endif
            @endauth
            
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @include('layouts.partials.feedback-messages')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>