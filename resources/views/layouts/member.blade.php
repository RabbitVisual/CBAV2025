<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Área do Membro') - {{ \App\Models\Configuracao::get('app_name', 'CBAV') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts e Estilos compilados via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    
    <div x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">
        <!-- Sidebar -->
        @include('layouts.partials.member-sidebar')
        
        <!-- Main Content -->
        <div class="lg:pl-72">
            <!-- Header -->
            @include('layouts.partials.member-header')

            <!-- Page Content -->
            <main class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>