<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
        $defaultTitle = $configuracoes['igreja_nome'] ?? 'Congregação Batista Avenida';
        $title = $configuracoes['meta_title'] ?? $defaultTitle;
        $description = $configuracoes['meta_description'] ?? 'Uma igreja comprometida com o amor de Cristo';
        $keywords = $configuracoes['meta_keywords'] ?? 'igreja, batista, comunidade, fé';
        $primaryColor = $configuracoes['cor_primaria'] ?? '#1E40AF';
    @endphp

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}" />
    <meta name="keywords" content="{{ $keywords }}" />
    <meta name="theme-color" content="{{ $primaryColor }}">

    <!-- Favicon -->
    @if(!empty($configuracoes['app_favicon']))
        <link rel="icon" href="{{ Storage::url($configuracoes['app_favicon']) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <x-site.header :configuracoes="$configuracoes" />

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-site.footer :configuracoes="$configuracoes" />
    </div>

    <!-- Cookie Banner -->
    @include('components.site.cookie-banner')

    <!-- Back to Top Button -->
    <button id="backToTop"
            class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 transform scale-0 z-40"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            title="Voltar ao topo">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

</body>
</html>