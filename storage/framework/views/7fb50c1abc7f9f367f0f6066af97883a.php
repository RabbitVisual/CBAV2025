<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(isset($configuracoes['meta_title']) && $configuracoes['meta_title']->valor ? $configuracoes['meta_title']->valor : (isset($configuracoes['igreja_nome']) && $configuracoes['igreja_nome']->valor ? $configuracoes['igreja_nome']->valor : 'Congregação Batista Avenida')); ?></title>
    <meta name="description" content="<?php echo e($configuracoes['meta_description']->valor ?? 'Uma igreja comprometida com o amor de Cristo'); ?>" />
    <meta name="keywords" content="<?php echo e($configuracoes['meta_keywords']->valor ?? 'igreja, batista, comunidade, fé'); ?>" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, <?php echo e($configuracoes['cor_primaria']->valor ?? '#1e40af'); ?> 0%, <?php echo e($configuracoes['cor_secundaria']->valor ?? '#3b82f6'); ?> 100%);
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Hero Section - Fundo azul apenas para o hero */
        .hero-section {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%) !important;
        }
        
        /* Garantir que o texto seja legível em fundos claros */
        .text-gray-900 {
            color: #111827 !important;
        }
        
        .text-gray-600 {
            color: #4b5563 !important;
        }
        
        .text-gray-700 {
            color: #374151 !important;
        }
        
        /* Garantir que o texto seja branco apenas no hero */
        .hero-section .text-white {
            color: white !important;
        }
        
        .hero-section .text-opacity-90 {
            opacity: 0.9 !important;
        }
        
        /* Garantir que os links sejam visíveis */
        .hero-section a.text-white {
            color: white !important;
        }
        
        .hero-section a.text-white:hover {
            color: #bfdbfe !important;
        }
        
        /* ESTILOS ESPECÍFICOS DO HEADER - PRIORIDADE MÁXIMA */
        /* Garantir que a logo apareça corretamente */
        header .relative.group {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        header .relative.group img {
            width: 3rem !important;
            height: 3rem !important;
            object-fit: contain !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        header .relative.group:hover img {
            transform: scale(1.05) !important;
        }
        
        /* Links de navegação desktop - Elegantes e profissionais */
        .nav-link-elegant {
            background: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: 0.75rem !important;
            font-weight: 500 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.025em !important;
        }
        
        .nav-link-elegant:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            color: white !important;
        }
        
        .nav-link-elegant i {
            font-size: 1.125rem !important;
            margin-right: 0.5rem !important;
            opacity: 0.9 !important;
        }
        
        /* Botões principais - Elegantes */
        .btn-elegant-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.025em !important;
        }
        
        .btn-elegant-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }
        
        .btn-elegant-primary i {
            font-size: 1.125rem !important;
            margin-right: 0.5rem !important;
            opacity: 0.9 !important;
        }
        
        /* Botões secundários - Elegantes */
        .btn-elegant-secondary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
        }
        
        .btn-elegant-secondary:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 30px rgba(239, 68, 68, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }
        
        .btn-elegant-secondary i {
            font-size: 1.125rem !important;
            opacity: 0.9 !important;
        }
        
        /* Botão mobile - Elegante */
        .btn-mobile-elegant {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            padding: 0.875rem !important;
            border-radius: 0.75rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .btn-mobile-elegant:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        
        .btn-mobile-elegant i {
            font-size: 1.25rem !important;
            opacity: 0.9 !important;
        }
        
        /* Links mobile - Elegantes */
        .mobile-nav-link-elegant {
            display: flex !important;
            align-items: center !important;
            background: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            padding: 1rem 1.25rem !important;
            border-radius: 0.75rem !important;
            font-weight: 500 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.025em !important;
        }
        
        .mobile-nav-link-elegant:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            transform: translateX(4px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            color: white !important;
        }
        
        .mobile-nav-link-elegant i {
            font-size: 1.125rem !important;
            margin-right: 0.75rem !important;
            opacity: 0.9 !important;
            width: 1.5rem !important;
            text-align: center !important;
        }
        
        /* Botões mobile - Elegantes */
        .mobile-btn-elegant-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            padding: 1rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-align: center !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.025em !important;
        }
        
        .mobile-btn-elegant-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4) !important;
            color: white !important;
        }
        
        .mobile-btn-elegant-primary i {
            font-size: 1.125rem !important;
            margin-right: 0.5rem !important;
            opacity: 0.9 !important;
        }
        
        .mobile-btn-elegant-secondary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
            padding: 1rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-align: center !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3) !important;
            backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.025em !important;
        }
        
        .mobile-btn-elegant-secondary:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 30px rgba(239, 68, 68, 0.4) !important;
            color: white !important;
        }
        
        .mobile-btn-elegant-secondary i {
            font-size: 1.125rem !important;
            margin-right: 0.5rem !important;
            opacity: 0.9 !important;
        }
        
        /* Animação do menu mobile */
        #mobileMenu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform-origin: top !important;
        }
        
        #mobileMenu:not(.hidden) {
            animation: slideDown 0.3s ease-out !important;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0 !important;
                transform: translateY(-10px) scale(0.95) !important;
            }
            to {
                opacity: 1 !important;
                transform: translateY(0) scale(1) !important;
            }
        }
        
        /* Efeito de scroll no header */
        header {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        header.scrolled {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(30, 64, 175, 0.95) 100%) !important;
            backdrop-filter: blur(20px) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Melhorias de acessibilidade */
        .nav-link-elegant:focus,
        .mobile-nav-link-elegant:focus,
        .btn-elegant-primary:focus,
        .btn-elegant-secondary:focus,
        .btn-mobile-elegant:focus,
        .mobile-btn-elegant-primary:focus,
        .mobile-btn-elegant-secondary:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5) !important;
            outline-offset: 2px !important;
        }
        
        /* Espaçamento otimizado para o header */
        header nav { padding: 0.8rem 0 !important; }
        
        /* Logo e área do usuário */
        header .flex.items-center { gap: 1rem !important; }
        
        /* Garantir que o menu mobile não apareça no desktop */
        @media (min-width: 1024px) {
            .lg\\:hidden { display: none !important; }
            #mobileMenu { display: none !important; }
        }
        
        /* Melhorar responsividade do header */
        @media (max-width: 1023px) {
            header .hidden.lg\\:flex { display: none !important; }
        }
        
        /* Animações suaves para o menu mobile */
        #mobileMenu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform-origin: top !important;
        }
        
        #mobileMenu:not(.hidden) {
            animation: slideDown 0.3s ease-out !important;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0 !important;
                transform: translateY(-10px) scale(0.95) !important;
            }
            to {
                opacity: 1 !important;
                transform: translateY(0) scale(1) !important;
            }
        }
        
        /* Área do usuário logado */
        header .bg-white\/10 { padding: 0.4rem 0.6rem !important; border-radius: 0.75rem !important; }
        header .bg-white\/10 img { width: 2rem !important; height: 2rem !important; }
        header .bg-white\/10 span { font-size: 0.85rem !important; }
        
        /* Menu mobile container */
        header #mobileMenu .bg-white\/10 { padding: 1.5rem !important; border-radius: 1.5rem !important; }
        
        .hero-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
        
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        .text-shadow { text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); }
        
        .accent-color { color: <?php echo e($configuracoes['cor_destaque']->valor ?? '#10b981'); ?>; }
        
        .secondary-color { color: <?php echo e($configuracoes['cor_secundaria']->valor ?? '#3b82f6'); ?>; }
        
        .glass-effect { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        
        .floating-animation { animation: float 6s ease-in-out infinite; }
        
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        
        .gradient-text { background: linear-gradient(135deg, <?php echo e($configuracoes['cor_primaria']->valor ?? '#1e40af'); ?>, <?php echo e($configuracoes['cor_destaque']->valor ?? '#10b981'); ?>); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        /* Melhorar legibilidade dos textos */
        .bg-white .text-gray-900 { color: #111827 !important; }
        .bg-gray-50 .text-gray-900 { color: #111827 !important; }
        .bg-white .text-gray-600 { color: #4b5563 !important; }
        .bg-gray-50 .text-gray-600 { color: #4b5563 !important; }
        
        /* Garantir que os botões tenham contraste adequado */
        .bg-blue-600 { background-color: #2563eb !important; }
        .bg-green-600 { background-color: #059669 !important; }
        .bg-purple-600 { background-color: #9333ea !important; }
        
        /* Garantir que os textos dos botões sejam brancos */
        .bg-blue-600, .bg-green-600, .bg-purple-600 { color: white !important; }
        
        /* Ajustes finos do header */
        /* Esconder botão de menu no desktop */
        @media (min-width: 1024px) { .btn-mobile-elegant { display: none !important; } }
        
        /* Links menores e mais discretos */
        .nav-link-elegant { padding: 0.45rem 0.85rem !important; border-radius: 0.6rem !important; font-size: 0.9rem !important; background: rgba(255,255,255,0.08) !important; border: 1px solid rgba(255,255,255,0.12) !important; box-shadow: none !important; }
        .nav-link-elegant i { font-size: 0.95rem !important; margin-right: 0.45rem !important; }
        .nav-link-elegant:hover { transform: translateY(-1px) !important; box-shadow: 0 6px 18px rgba(0,0,0,.12) !important; }
        
        /* Botões mais compactos */
        .btn-elegant-primary, .btn-elegant-secondary { padding: 0.55rem 1rem !important; border-radius: 0.65rem !important; font-size: 0.92rem !important; }
        .btn-elegant-primary i, .btn-elegant-secondary i { font-size: 1rem !important; margin-right: 0.45rem !important; }
        
        /* Logo sem bolha (garantia) */
        header img[alt="Logo"] { background: transparent !important; border: 0 !important; box-shadow: none !important; padding: 0 !important; width: 2.5rem !important; height: 2.5rem !important; object-fit: contain !important; display: block !important; }
        /* Garantia extra para a logo */
        header img[alt="Logo"] { display: block !important; visibility: visible !important; opacity: 1 !important; }
        header .mr-4 { display: flex !important; align-items: center !important; }
    </style>
</head>
<?php
    // Garantir que $configuracoes existe e tem valores padrão
    if (!isset($configuracoes) || !is_array($configuracoes)) {
        $configuracoes = [
            'igreja_nome' => (object)['valor' => 'Congregação Batista Avenida'],
            'igreja_slogan' => (object)['valor' => 'Uma igreja comprometida com o amor de Cristo'],
            'cor_primaria' => (object)['valor' => '#1e40af'],
            'cor_secundaria' => (object)['valor' => '#3b82f6'],
            'cor_destaque' => (object)['valor' => '#10b981'],
            'hero_titulo' => (object)['valor' => 'Bem-vindo à Nossa Igreja'],
            'hero_subtitulo' => (object)['valor' => 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!'],
        ];
    }
?>
<body class="bg-gray-50">
    <!-- Header Profissional -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 hero-pattern sticky top-0 z-50 shadow-xl" style="background: linear-gradient(135deg, <?php echo e($configuracoes['cor_primaria']->valor ?? '#1e40af'); ?> 0%, <?php echo e($configuracoes['cor_secundaria']->valor ?? '#3b82f6'); ?> 100%);">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <?php if(($configuracoes['header_logo_ativa']->valor ?? '1') == '1'): ?>
                        <?php if(isset($configuracoes['logo']) && $configuracoes['logo']->valor): ?>
                            <img src="<?php echo e(Storage::url($configuracoes['logo']->valor)); ?>" alt="Logo" class="w-12 h-12 object-contain bg-white rounded-xl p-1 shadow-lg" onerror="this.onerror=null;this.src='<?php echo e(asset('img/logo.png')); ?>'">
                        <?php else: ?>
                            <div class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center floating-animation">
                                <i class="fas fa-church text-white text-2xl"></i>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div>
                        <?php if(($configuracoes['header_nome_igreja_ativa']->valor ?? '1') == '1'): ?>
                            <h1 class="text-2xl font-bold text-white text-shadow"><?php echo e($configuracoes['igreja_nome']->valor ?? 'Congregação Batista Avenida'); ?></h1>
                        <?php endif; ?>
                        <?php if(($configuracoes['header_slogan_ativa']->valor ?? '1') == '1'): ?>
                            <p class="text-white text-opacity-90 text-sm font-medium"><?php echo e($configuracoes['igreja_slogan']->valor ?? 'Uma igreja comprometida com o amor de Cristo'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Menu Desktop - Apenas em telas médias e grandes -->
                <div class="hidden lg:flex items-center space-x-6">
                    <?php if(($configuracoes['header_menu_ativa']->valor ?? '1') == '1'): ?>
                        <a href="#sobre" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_sobre']->valor ?? 'Sobre'); ?></a>
                        <a href="#ministerios" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_ministerios']->valor ?? 'Ministérios'); ?></a>
                        <a href="#cultos" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_cultos']->valor ?? 'Cultos'); ?></a>
                        <a href="#aniversariantes" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_aniversariantes']->valor ?? 'Aniversariantes'); ?></a>
                        <a href="<?php echo e(route('public.eventos.index')); ?>" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_eventos']->valor ?? 'Eventos'); ?></a>
                        <a href="<?php echo e(route('doacao.index')); ?>" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_doacao']->valor ?? 'Doação'); ?></a>
                        <a href="#contato" class="text-white hover:text-blue-200 transition-all duration-300 font-medium hover:scale-105"><?php echo e($configuracoes['header_link_contato']->valor ?? 'Contato'); ?></a>
                    <?php endif; ?>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <!-- Usuário Logado -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <?php if(auth()->user()->foto_existe): ?>
                                    <img src="<?php echo e(auth()->user()->foto_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" 
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-lg">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        <?php echo e(auth()->user()->iniciais); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-white font-medium text-sm"><?php echo e(auth()->user()->name); ?></span>
                            </div>
                            <div class="flex space-x-2">
                                <?php if(auth()->user()->hasRole('Membro')): ?>
                                    <a href="<?php echo e(route('member.dashboard')); ?>" class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 text-sm font-medium">
                                        <i class="fas fa-user mr-1"></i><?php echo e($configuracoes['header_texto_area_membro']->valor ?? 'Área do Membro'); ?>

                                    </a>
                                <?php elseif(auth()->user()->hasAnyRole(['Super Admin', 'Pastor', 'Líder'])): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 text-sm font-medium">
                                        <i class="fas fa-cog mr-1"></i>Painel Admin
                                    </a>
                                <?php elseif(auth()->user()->hasRole('Tesoureiro')): ?>
                                    <a href="/tesouraria/dashboard" class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 text-sm font-medium">
                                        <i class="fas fa-calculator mr-1"></i>Tesouraria
                                    </a>
                                <?php endif; ?>
                                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="glass-effect text-white px-3 py-2 rounded-lg hover:bg-red-500 hover:text-white transition-all duration-300 text-sm">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Usuário Não Logado -->
                        <a href="<?php echo e(route('login')); ?>" class="glass-effect text-white px-6 py-3 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">
                            <i class="fas fa-sign-in-alt mr-2"></i><?php echo e($configuracoes['header_texto_area_membro']->valor ?? 'Área do Membro'); ?>

                        </a>
                    <?php endif; ?>
                </div>
                <!-- Menu mobile - Apenas em telas pequenas -->
                <div class="lg:hidden">
                    <button class="text-white glass-effect p-2 rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            <!-- Menu mobile dropdown - Apenas em telas pequenas -->
            <div id="mobileMenu" class="lg:hidden hidden mt-4 pb-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-xl">
                    <div class="flex flex-col space-y-3">
                        <?php if(($configuracoes['header_menu_ativa']->valor ?? '1') == '1'): ?>
                            <a href="#sobre" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_sobre']->valor ?? 'Sobre'); ?></a>
                            <a href="#ministerios" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_ministerios']->valor ?? 'Ministérios'); ?></a>
                            <a href="#cultos" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_cultos']->valor ?? 'Cultos'); ?></a>
                            <a href="#aniversariantes" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_aniversariantes']->valor ?? 'Aniversariantes'); ?></a>
                            <a href="<?php echo e(route('public.eventos.index')); ?>" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_eventos']->valor ?? 'Eventos'); ?></a>
                            <a href="<?php echo e(route('doacao.index')); ?>" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_doacao']->valor ?? 'Doação'); ?></a>
                            <a href="#contato" class="text-white hover:text-blue-200 transition-colors font-medium py-2 px-3 rounded-lg hover:bg-white/10"><?php echo e($configuracoes['header_link_contato']->valor ?? 'Contato'); ?></a>
                        <?php endif; ?>
                    <?php if(auth()->guard()->check()): ?>
                        <div class="pt-4 border-t border-white/20 mt-4">
                            <div class="flex items-center space-x-3 mb-4">
                                <?php if(auth()->user()->foto_existe): ?>
                                    <img src="<?php echo e(auth()->user()->foto_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" 
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white/30">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        <?php echo e(auth()->user()->iniciais); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-white font-medium"><?php echo e(auth()->user()->name); ?></span>
                            </div>
                            <?php if(auth()->user()->hasRole('Membro')): ?>
                                <a href="<?php echo e(route('member.dashboard')); ?>" class="block w-full bg-white/10 text-white px-4 py-3 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold text-center mb-3">
                                    <i class="fas fa-user mr-2"></i><?php echo e($configuracoes['header_texto_area_membro']->valor ?? 'Área do Membro'); ?>

                                </a>
                            <?php elseif(auth()->user()->hasAnyRole(['Super Admin', 'Pastor', 'Líder'])): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block w-full bg-white/10 text-white px-4 py-3 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold text-center mb-3">
                                    <i class="fas fa-cog mr-2"></i>Painel Admin
                                </a>
                            <?php elseif(auth()->user()->hasRole('Tesoureiro')): ?>
                                <a href="/tesouraria/dashboard" class="block w-full bg-white/10 text-white px-4 py-3 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold text-center mb-3">
                                    <i class="fas fa-calculator mr-2"></i>Tesouraria
                                </a>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full bg-red-500/20 text-white px-4 py-3 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 font-semibold text-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="pt-4 border-t border-white/20 mt-4">
                            <a href="<?php echo e(route('login')); ?>" class="block w-full bg-white/10 text-white px-4 py-3 rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i><?php echo e($configuracoes['header_texto_area_membro']->valor ?? 'Área do Membro'); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>



    <!-- Hero Section -->
    <?php
        $heroFotoFundoAtiva = $configuracoes['hero_foto_fundo_ativa']->valor ?? '0';
        $heroFotoFundo = $configuracoes['hero_foto_fundo']->valor ?? null;
    ?>
    
    <section class="relative py-20 hero-pattern" 
             style="<?php if($heroFotoFundoAtiva == '1' && $heroFotoFundo): ?> 
                        background-image: url('<?php echo e(Storage::url($heroFotoFundo)); ?>'); 
                        background-size: cover; 
                        background-position: center; 
                        background-repeat: no-repeat;
                    <?php else: ?>
                        background: linear-gradient(135deg, <?php echo e($configuracoes['cor_primaria']->valor ?? '#1e40af'); ?> 0%, <?php echo e($configuracoes['cor_secundaria']->valor ?? '#3b82f6'); ?> 100%);
                    <?php endif; ?>">
        
        <!-- Sobreposição para legibilidade -->
        <?php if($heroFotoFundoAtiva == '1' && $heroFotoFundo): ?>
            <div class="absolute inset-0" 
                 style="background-color: <?php echo e($configuracoes['hero_overlay_cor']->valor ?? '#1e40af'); ?>; 
                        opacity: <?php echo e($configuracoes['hero_overlay_opacidade']->valor ?? '0.6'); ?>;"></div>
        <?php endif; ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-white text-shadow mb-6">
                    <?php echo e(isset($configuracoes['hero_titulo']) && $configuracoes['hero_titulo']->valor ? $configuracoes['hero_titulo']->valor : 'Bem-vindo à Nossa Igreja'); ?>

                </h1>
                <p class="text-xl md:text-2xl text-white text-opacity-90 mb-8 max-w-3xl mx-auto">
                    <?php echo e(isset($configuracoes['hero_subtitulo']) && $configuracoes['hero_subtitulo']->valor ? $configuracoes['hero_subtitulo']->valor : 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!'); ?>

                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e($configuracoes['home_botao_link']->valor ?? '#sobre'); ?>" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-info-circle mr-2"></i><?php echo e($configuracoes['home_botao_texto']->valor ?? 'Conheça-nos'); ?>

                    </a>
                    <?php if(($configuracoes['doacao_ativa']->valor ?? '1') == '1'): ?>
                    <a href="<?php echo e(route('doacao.index')); ?>" class="glass-effect text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-heart mr-2"></i><?php echo e($configuracoes['home_botao_doacao_texto']->valor ?? 'Faça uma Doação'); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Sobre -->
    <?php if(($configuracoes['secao_sobre_ativa']->valor ?? '1') == '1'): ?>
    <section id="sobre" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e($configuracoes['sobre_titulo']->valor ?? 'Sobre Nossa Igreja'); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['igreja_slogan']->valor ?? 'Uma igreja comprometida com o amor de Cristo e o serviço ao próximo.'); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center card-hover bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-pray text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Adoração</h3>
                    <p class="text-gray-600">Celebramos a Deus com alegria e reverência, através de louvores, orações e pregação da Palavra de Deus.</p>
                </div>
                
                <div class="text-center card-hover bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-hands-helping text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Comunhão</h3>
                    <p class="text-gray-600">Vivemos em comunidade, compartilhando amor e apoio uns com os outros, seguindo os princípios bíblicos.</p>
                </div>
                
                <div class="text-center card-hover bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book-open text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Ensino</h3>
                    <p class="text-gray-600">Estudamos a Palavra de Deus para crescer em conhecimento e fé, formando discípulos de Cristo.</p>
                </div>
            </div>
            
            <!-- Princípios Batistas -->
            <?php if(($configuracoes['principios_batistas_ativa']->valor ?? '1') == '1'): ?>
            <div class="mt-16 bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                <h3 class="text-2xl font-bold text-center mb-8"><?php echo e($configuracoes['principios_batistas_titulo']->valor ?? 'Nossos Princípios Batistas'); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bible text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold mb-2"><?php echo e($configuracoes['principio_1_titulo']->valor ?? 'Sola Scriptura'); ?></h4>
                        <p class="text-sm text-blue-100"><?php echo e($configuracoes['principio_1_descricao']->valor ?? 'A Bíblia como única regra de fé e prática'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold mb-2"><?php echo e($configuracoes['principio_2_titulo']->valor ?? 'Sacerdócio Universal'); ?></h4>
                        <p class="text-sm text-blue-100"><?php echo e($configuracoes['principio_2_descricao']->valor ?? 'Todo crente tem acesso direto a Deus'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-water text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold mb-2"><?php echo e($configuracoes['principio_3_titulo']->valor ?? 'Batismo por Imersão'); ?></h4>
                        <p class="text-sm text-blue-100"><?php echo e($configuracoes['principio_3_descricao']->valor ?? 'Para crentes professos, por imersão'); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <h4 class="font-semibold mb-2"><?php echo e($configuracoes['principio_4_titulo']->valor ?? 'Autonomia Local'); ?></h4>
                        <p class="text-sm text-blue-100"><?php echo e($configuracoes['principio_4_descricao']->valor ?? 'Cada igreja é independente e autônoma'); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção Ministérios -->
    <?php if(($configuracoes['secao_ministerios_ativa']->valor ?? '1') == '1'): ?>
    <section id="ministerios" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e($configuracoes['ministerios_titulo']->valor ?? 'Nossos Ministérios'); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['ministerios_subtitulo']->valor ?? 'Conheça nossos ministérios e participe da obra de Deus.'); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $ministerios ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ministerio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-hover bg-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4" style="background-color: <?php echo e($ministerio->cor); ?>">
                            <i class="fas fa-hands-helping text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($ministerio->nome); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo e($ministerio->membros_count ?? 0); ?> membros</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm"><?php echo e($ministerio->descricao ?? 'Ministério dedicado ao serviço de Deus.'); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção Horários de Culto -->
    <?php if(($configuracoes['secao_cultos_ativa']->valor ?? '1') == '1'): ?>
    <section id="cultos" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e($configuracoes['cultos_titulo']->valor ?? 'Horários de Cultos'); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['cultos_subtitulo']->valor ?? 'Venha adorar conosco e crescer na fé através da Palavra de Deus'); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card-hover bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-8 border border-yellow-100">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-sun text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['culto_domingo_manha_titulo']->valor ?? 'Culto de Domingo - Manhã'); ?></h3>
                    <p class="text-2xl font-bold text-yellow-600 mb-4"><?php echo e($configuracoes['culto_domingo_manha_horario']->valor ?? '09:00h'); ?></p>
                    <p class="text-gray-600"><?php echo e($configuracoes['culto_domingo_manha_descricao']->valor ?? 'Culto de adoração e pregação da Palavra de Deus'); ?></p>
                    <div class="mt-4 text-sm text-gray-500">
                        <p><i class="fas fa-music mr-2"></i><?php echo e($configuracoes['culto_domingo_manha_item1']->valor ?? 'Louvor e Adoração'); ?></p>
                        <p><i class="fas fa-bible mr-2"></i><?php echo e($configuracoes['culto_domingo_manha_item2']->valor ?? 'Pregação da Palavra'); ?></p>
                        <p><i class="fas fa-pray mr-2"></i><?php echo e($configuracoes['culto_domingo_manha_item3']->valor ?? 'Oração e Intercessão'); ?></p>
                    </div>
                </div>
                
                <div class="card-hover bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-moon text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['culto_domingo_noite_titulo']->valor ?? 'Culto de Domingo - Noite'); ?></h3>
                    <p class="text-2xl font-bold text-blue-600 mb-4"><?php echo e($configuracoes['culto_domingo_noite_horario']->valor ?? '18:00h'); ?></p>
                    <p class="text-gray-600"><?php echo e($configuracoes['culto_domingo_noite_descricao']->valor ?? 'Culto de celebração e edificação espiritual'); ?></p>
                    <div class="mt-4 text-sm text-gray-500">
                        <p><i class="fas fa-music mr-2"></i><?php echo e($configuracoes['culto_domingo_noite_item1']->valor ?? 'Louvor e Adoração'); ?></p>
                        <p><i class="fas fa-bible mr-2"></i><?php echo e($configuracoes['culto_domingo_noite_item2']->valor ?? 'Pregação da Palavra'); ?></p>
                        <p><i class="fas fa-pray mr-2"></i><?php echo e($configuracoes['culto_domingo_noite_item3']->valor ?? 'Oração e Intercessão'); ?></p>
                    </div>
                </div>
                
                <div class="card-hover bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-week text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['culto_quarta_titulo']->valor ?? 'Culto de Quarta-feira'); ?></h3>
                    <p class="text-2xl font-bold text-green-600 mb-4"><?php echo e($configuracoes['culto_quarta_horario']->valor ?? '19:30h'); ?></p>
                    <p class="text-gray-600"><?php echo e($configuracoes['culto_quarta_descricao']->valor ?? 'Culto de oração e estudo bíblico'); ?></p>
                    <div class="mt-4 text-sm text-gray-500">
                        <p><i class="fas fa-pray mr-2"></i><?php echo e($configuracoes['culto_quarta_item1']->valor ?? 'Oração e Intercessão'); ?></p>
                        <p><i class="fas fa-book-open mr-2"></i><?php echo e($configuracoes['culto_quarta_item2']->valor ?? 'Estudo Bíblico'); ?></p>
                        <p><i class="fas fa-hands-helping mr-2"></i><?php echo e($configuracoes['culto_quarta_item3']->valor ?? 'Comunhão'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Escola Dominical -->
            <?php if(($configuracoes['escola_dominical_ativa']->valor ?? '1') == '1'): ?>
            <div class="mt-12 bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl p-8 text-white">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4"><?php echo e($configuracoes['escola_dominical_titulo']->valor ?? 'Escola Dominical'); ?></h3>
                    <p class="text-xl mb-6"><?php echo e($configuracoes['escola_dominical_horario']->valor ?? 'Domingo às 08:00h'); ?></p>
                    <p class="text-purple-100 max-w-2xl mx-auto">
                        <?php echo e($configuracoes['escola_dominical_descricao']->valor ?? 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.'); ?>

                    </p>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <i class="fas fa-child text-2xl mb-2"></i>
                            <p><?php echo e($configuracoes['escola_dominical_classe1']->valor ?? 'Classes Infantis'); ?></p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p><?php echo e($configuracoes['escola_dominical_classe2']->valor ?? 'Classes de Jovens'); ?></p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-user-friends text-2xl mb-2"></i>
                            <p><?php echo e($configuracoes['escola_dominical_classe3']->valor ?? 'Classes de Adultos'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>



    <!-- Seção Eventos -->
    <?php if(($configuracoes['secao_eventos_ativa']->valor ?? '1') == '1'): ?>
    <section id="eventos" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Próximos Eventos</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Participe dos nossos eventos e fortaleça sua fé em comunidade
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = \App\Models\Evento::where('ativo', true)->where('destaque', true)->where('data_inicio', '>=', now())->orderBy('data_inicio')->limit(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                    <?php if($evento->imagem): ?>
                        <img src="<?php echo e(Storage::url($evento->imagem)); ?>" alt="<?php echo e($evento->titulo); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <?php if($evento->destaque): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>Destaque
                                </span>
                            <?php endif; ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?php echo e($evento->gratuito ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                <?php echo e($evento->gratuito ? 'Gratuito' : 'R$ ' . number_format($evento->valor_inscricao, 2, ',', '.')); ?>

                            </span>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3"><?php echo e($evento->titulo); ?></h3>
                        <p class="text-gray-600 text-sm mb-4"><?php echo e($evento->descricao_curta ?: Str::limit($evento->descricao, 120)); ?></p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-2"></i>
                                <?php echo e($evento->data_inicio->format('d/m/Y')); ?>

                                <?php if($evento->hora_inicio): ?>
                                    às <?php echo e($evento->hora_inicio->format('H:i')); ?>

                                <?php endif; ?>
                            </div>
                            
                            <?php if($evento->local): ?>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <?php echo e($evento->local); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo e(route('public.eventos.show', $evento)); ?>" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-medium transition-colors text-center">
                            <i class="fas fa-info-circle mr-2"></i>Ver Detalhes
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum evento no momento</h3>
                    <p class="text-gray-600">Não há eventos programados no momento.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-8">
                <a href="<?php echo e(route('public.eventos.index')); ?>" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-calendar-alt mr-2"></i>Ver Todos os Eventos
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção Aniversariantes -->
    <?php if(($configuracoes['secao_aniversariantes_ativa']->valor ?? '1') == '1' && ($configuracoes['aniversariantes_mostrar']->valor ?? '1') == '1'): ?>
    <section id="aniversariantes" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e($configuracoes['aniversariantes_titulo']->valor ?? 'Aniversariantes do Mês'); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['aniversariantes_subtitulo']->valor ?? 'Celebrando a vida dos nossos membros'); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $aniversariantes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aniversariante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card-hover bg-gradient-to-br from-pink-50 to-rose-50 rounded-2xl p-6 border border-pink-100">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-birthday-cake text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($aniversariante->nome); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo e($aniversariante->data_nascimento->format('d/m')); ?></p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Que Deus abençoe seu aniversário!</p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-birthday-cake text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum aniversariante este mês</h3>
                    <p class="text-gray-600">Não há aniversariantes registrados para este mês.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção Doação -->
    <?php
        // Verificar configurações de doação de forma robusta
        $secao_doacao_ativa = isset($configuracoes['secao_doacao_ativa']) ? $configuracoes['secao_doacao_ativa']->valor : '1';
        $doacao_ativa = isset($configuracoes['doacao_ativa']) ? $configuracoes['doacao_ativa']->valor : '1';
        $doacao_sem_login = isset($configuracoes['doacao_sem_login']) ? $configuracoes['doacao_sem_login']->valor : '1';
        
        // Verificar se deve mostrar a seção (aceitar 1, '1', true, 'true')
        $mostrar_doacao = in_array($secao_doacao_ativa, [1, '1', true, 'true']) && 
                         in_array($doacao_ativa, [1, '1', true, 'true']);
    ?>
    
    <?php if($mostrar_doacao): ?>
    <section id="doacao" class="py-20 bg-gradient-to-br from-green-600 to-emerald-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4"><?php echo e($configuracoes['doacao_titulo']->valor ?? 'Faça uma Doação'); ?></h2>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['doacao_subtitulo']->valor ?? 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!'); ?>

                </p>
            </div>
            
            <!-- Seção de Doação Profissional -->
            <?php if(in_array($doacao_sem_login, [1, '1', true, 'true']) && in_array($doacao_ativa, [1, '1', true, 'true'])): ?>
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl shadow-2xl p-8 mb-8 border border-green-100">
                <!-- Header da Seção -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Faça sua Doação</h3>
                    <p class="text-lg text-gray-600"><?php echo e($configuracoes['doacao_titulo']->valor ?? 'Contribua para a obra de Deus e seja uma bênção para nossa comunidade'); ?></p>
                </div>

                <!-- Dicas e Informações -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pagamento Seguro</h4>
                        <p class="text-sm text-gray-600"><?php echo e($configuracoes['doacao_dica_seguranca']->valor ?? 'Seus dados estão protegidos com criptografia SSL'); ?></p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Comprovante</h4>
                        <p class="text-sm text-gray-600"><?php echo e($configuracoes['doacao_dica_comprovante']->valor ?? 'Receba um comprovante por email após a confirmação'); ?></p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-church text-purple-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Transparência</h4>
                        <p class="text-sm text-gray-600"><?php echo e($configuracoes['doacao_dica_transparencia']->valor ?? 'Todas as doações são registradas e auditadas'); ?></p>
                    </div>
                </div>

                <!-- Formulário de Doação -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form method="POST" action="<?php echo e(route('doacao.process')); ?>" class="max-w-lg mx-auto">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-6">
                            <!-- Valor da Doação -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Valor da Doação</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">R$</span>
                                    <input type="number" 
                                           name="valor" 
                                           step="0.01" 
                                           min="<?php echo e($configuracoes['doacao_valor_minimo']->valor ?? '1'); ?>" 
                                           max="<?php echo e($configuracoes['doacao_valor_maximo']->valor ?? '10000'); ?>"
                                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-lg"
                                           placeholder="0,00"
                                           required>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    Valor mínimo: R$ <?php echo e(number_format($configuracoes['doacao_valor_minimo']->valor ?? 1, 2, ',', '.')); ?> | 
                                    Valor máximo: R$ <?php echo e(number_format($configuracoes['doacao_valor_maximo']->valor ?? 10000, 2, ',', '.')); ?>

                                </p>
                            </div>
                            
                            <!-- Destino da Doação -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Destino da Doação</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tipo_destino" value="igreja" class="sr-only" checked>
                                        <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-colors destino-option">
                                            <i class="fas fa-church text-2xl text-green-600 mb-2"></i>
                                            <div class="font-semibold text-gray-900">Igreja</div>
                                            <div class="text-sm text-gray-600">Manutenção geral</div>
                                        </div>
                                    </label>
                                    
                                    <?php if(isset($campanhas) && $campanhas->count() > 0): ?>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tipo_destino" value="campanha" class="sr-only">
                                        <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-colors destino-option">
                                            <i class="fas fa-bullhorn text-2xl text-blue-600 mb-2"></i>
                                            <div class="font-semibold text-gray-900">Campanha</div>
                                            <div class="text-sm text-gray-600">Projetos específicos</div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($ministerios) && $ministerios->count() > 0): ?>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="tipo_destino" value="ministerio" class="sr-only">
                                        <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-colors destino-option">
                                            <i class="fas fa-hands-helping text-2xl text-purple-600 mb-2"></i>
                                            <div class="font-semibold text-gray-900">Ministério</div>
                                            <div class="text-sm text-gray-600">Ministérios específicos</div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Seleção de Campanha -->
                            <div id="selecao_campanha" class="hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Selecione a Campanha</label>
                                <select name="destino_id" id="campanha_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Selecione uma campanha...</option>
                                    <?php if(isset($campanhas) && $campanhas->count() > 0): ?>
                                        <?php $__currentLoopData = $campanhas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campanha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($campanha->id); ?>"><?php echo e($campanha->titulo); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <!-- Seleção de Ministério -->
                            <div id="selecao_ministerio" class="hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Selecione o Ministério</label>
                                <select name="destino_id" id="ministerio_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Selecione um ministério...</option>
                                    <?php if(isset($ministerios) && $ministerios->count() > 0): ?>
                                        <?php $__currentLoopData = $ministerios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ministerio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($ministerio->id); ?>"><?php echo e($ministerio->nome); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <!-- Forma de Pagamento -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Forma de Pagamento</label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php if(!empty($configuracoes['stripe_key']->valor ?? '')): ?>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gateway" value="stripe" class="sr-only" checked>
                                        <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-colors gateway-option">
                                            <i class="fab fa-cc-stripe text-2xl text-blue-600 mb-2"></i>
                                            <div class="font-semibold text-gray-900">Cartão de Crédito</div>
                                            <div class="text-sm text-gray-600">Visa, Mastercard, etc.</div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                    
                                    <?php if(!empty($configuracoes['mercadopago_key']->valor ?? '')): ?>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gateway" value="mercadopago" class="sr-only">
                                        <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-green-500 transition-colors gateway-option">
                                            <i class="fas fa-wallet text-2xl text-purple-600 mb-2"></i>
                                            <div class="font-semibold text-gray-900">Mercado Pago</div>
                                            <div class="text-sm text-gray-600">Cartão, boleto, PIX</div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                    
                                    <!-- Mensagem quando nenhum gateway está configurado -->
                                    <?php if(empty($configuracoes['stripe_key']->valor ?? '') && empty($configuracoes['mercadopago_key']->valor ?? '')): ?>
                                    <div class="col-span-2 text-center py-8">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
                                        <p class="text-gray-600">Nenhum gateway de pagamento configurado.</p>
                                        <p class="text-sm text-gray-500 mt-2">Entre em contato com o administrador.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Identificação Opcional -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <input type="checkbox" name="identificar" id="identificar" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <label for="identificar" class="ml-3 text-sm font-semibold text-gray-700">Desejo me identificar (opcional)</label>
                                </div>
                                
                                <div id="campos_identificacao" class="hidden space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                            <input type="text" name="nome_doador" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                            <input type="email" name="email_doador" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botão de Envio -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-green-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-heart mr-2"></i>
                                    Fazer Doação
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Controle dos destinos
                const destinoOptions = document.querySelectorAll('input[name="tipo_destino"]');
                const selecaoCampanha = document.getElementById('selecao_campanha');
                const selecaoMinisterio = document.getElementById('selecao_ministerio');
                const campanhaId = document.getElementById('campanha_id');
                const ministerioId = document.getElementById('ministerio_id');
                
                function atualizarSelecao() {
                    const valorSelecionado = document.querySelector('input[name="tipo_destino"]:checked')?.value;
                    
                    // Ocultar todos os campos de seleção
                    selecaoCampanha.classList.add('hidden');
                    selecaoMinisterio.classList.add('hidden');
                    
                    // Limpar valores dos selects
                    if (campanhaId) campanhaId.value = '';
                    if (ministerioId) ministerioId.value = '';
                    
                    // Mostrar campo apropriado
                    if (valorSelecionado === 'campanha') {
                        selecaoCampanha.classList.remove('hidden');
                    } else if (valorSelecionado === 'ministerio') {
                        selecaoMinisterio.classList.remove('hidden');
                    }
                }
                
                // Adicionar evento de mudança para todos os radio buttons
                destinoOptions.forEach(option => {
                    option.addEventListener('change', atualizarSelecao);
                });
                
                // Controle da identificação
                const identificarCheckbox = document.getElementById('identificar');
                const camposIdentificacao = document.getElementById('campos_identificacao');
                
                identificarCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        camposIdentificacao.classList.remove('hidden');
                    } else {
                        camposIdentificacao.classList.add('hidden');
                    }
                });
                
                // Estilização dos botões de opção
                const destinoOptionsDivs = document.querySelectorAll('.destino-option');
                destinoOptions.forEach((option, index) => {
                    option.addEventListener('change', function() {
                        destinoOptionsDivs.forEach(div => {
                            div.classList.remove('border-green-500', 'bg-green-50');
                            div.classList.add('border-gray-200');
                        });
                        if (this.checked) {
                            destinoOptionsDivs[index].classList.remove('border-gray-200');
                            destinoOptionsDivs[index].classList.add('border-green-500', 'bg-green-50');
                        }
                    });
                });
                
                const gatewayOptionsDivs = document.querySelectorAll('.gateway-option');
                const gatewayOptions = document.querySelectorAll('input[name="gateway"]');
                gatewayOptions.forEach((option, index) => {
                    option.addEventListener('change', function() {
                        gatewayOptionsDivs.forEach(div => {
                            div.classList.remove('border-green-500', 'bg-green-50');
                            div.classList.add('border-gray-200');
                        });
                        if (this.checked) {
                            gatewayOptionsDivs[index].classList.remove('border-gray-200');
                            gatewayOptionsDivs[index].classList.add('border-green-500', 'bg-green-50');
                        }
                    });
                });
                
                // Executar na carga inicial
                atualizarSelecao();
            });
            </script>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center card-hover bg-white rounded-2xl p-8">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-church text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['doacao_dizimo_titulo']->valor ?? 'Dízimo'); ?></h3>
                    <p class="text-gray-600 mb-6"><?php echo e($configuracoes['doacao_dizimo_descricao']->valor ?? 'Contribua com o dízimo para a manutenção da igreja.'); ?></p>
                    <a href="<?php echo e(route('doacao.index')); ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-all duration-300">
                        <?php echo e($configuracoes['doacao_dizimo_botao']->valor ?? 'Doar Dízimo'); ?>

                    </a>
                </div>
                
                <div class="text-center card-hover bg-white rounded-2xl p-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['doacao_oferta_titulo']->valor ?? 'Oferta'); ?></h3>
                    <p class="text-gray-600 mb-6"><?php echo e($configuracoes['doacao_oferta_descricao']->valor ?? 'Ofereça com amor para as necessidades da igreja.'); ?></p>
                    <a href="<?php echo e(route('doacao.index')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300">
                        <?php echo e($configuracoes['doacao_oferta_botao']->valor ?? 'Fazer Oferta'); ?>

                    </a>
                </div>
                
                <div class="text-center card-hover bg-white rounded-2xl p-8">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-hands-helping text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($configuracoes['doacao_campanhas_titulo']->valor ?? 'Campanhas'); ?></h3>
                    <p class="text-gray-600 mb-6"><?php echo e($configuracoes['doacao_campanhas_descricao']->valor ?? 'Participe de nossas campanhas especiais.'); ?></p>
                    <a href="<?php echo e(route('doacao.index')); ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-all duration-300">
                        <?php echo e($configuracoes['doacao_campanhas_botao']->valor ?? 'Ver Campanhas'); ?>

                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção Contato -->
    <?php if(($configuracoes['secao_contato_ativa']->valor ?? '1') == '1'): ?>
    <section id="contato" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4"><?php echo e($configuracoes['contato_titulo']->valor ?? 'Entre em Contato'); ?></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e($configuracoes['contato_subtitulo']->valor ?? 'Estamos aqui para você. Entre em contato conosco!'); ?>

                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Informações de Contato</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Endereço</p>
                                <p class="text-gray-600"><?php echo e($configuracoes['igreja_endereco']->valor ?? 'Endereço da igreja'); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Telefone</p>
                                <p class="text-gray-600"><?php echo e($configuracoes['igreja_telefone']->valor ?? '(11) 99999-9999'); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Email</p>
                                <p class="text-gray-600"><?php echo e($configuracoes['igreja_email']->valor ?? 'contato@igreja.com'); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Horários</p>
                                <p class="text-gray-600"><?php echo e($configuracoes['culto_domingo']->valor ?? 'Domingo: 09:00 e 18:00'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Envie uma Mensagem</h3>
                    <form class="space-y-4">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                            <input type="text" id="nome" name="nome" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="mensagem" class="block text-sm font-medium text-gray-700 mb-2">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300">
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer Profissional -->
    <?php echo $__env->make('components.footer-welcome', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Banner de Cookies -->
    <div id="cookieBanner" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50 transform translate-y-full transition-transform duration-300" style="display: block; z-index: 9999;">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="flex items-start space-x-3">
                <i class="fas fa-cookie-bite text-amber-400 text-2xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-lg mb-2">🍪 Este site usa cookies</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Utilizamos cookies para melhorar sua experiência de navegação, personalizar conteúdo e analisar nosso tráfego. 
                        Ao continuar navegando, você concorda com nossa 
                        <a href="<?php echo e(route('politica-cookies')); ?>" class="text-blue-400 hover:text-blue-300 underline">Política de Cookies</a> e 
                        <a href="<?php echo e(route('politica-privacidade')); ?>" class="text-blue-400 hover:text-blue-300 underline">Política de Privacidade</a>.
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 min-w-fit">
                <button onclick="rejeitarCookies()" 
                        class="px-6 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Recusar
                </button>
                <button onclick="aceitarCookies()" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-check mr-2"></i>Aceitar
                </button>
                <button onclick="configurarCookies()" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-cog mr-2"></i>Configurar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Configuração de Cookies -->
    <div id="cookieModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-cookie-bite text-amber-500 mr-2"></i>
                        Configurações de Cookies
                    </h2>
                    <button onclick="fecharModalCookies()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Cookies Essenciais -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-shield-alt text-red-600"></i>
                                <h3 class="font-semibold text-gray-900">Cookies Essenciais</h3>
                            </div>
                            <div class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-sm font-medium">
                                Sempre Ativo
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Necessários para o funcionamento básico do site. Não podem ser desabilitados.
                        </p>
                    </div>

                    <!-- Cookies de Funcionalidade -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-cogs text-blue-600"></i>
                                <h3 class="font-semibold text-gray-900">Cookies de Funcionalidade</h3>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="cookiesFuncionalidade" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Melhoram a funcionalidade e personalização do site (lembrar preferências, etc.).
                        </p>
                    </div>

                    <!-- Cookies de Performance -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-line text-green-600"></i>
                                <h3 class="font-semibold text-gray-900">Cookies de Performance</h3>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="cookiesPerformance" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Ajudam a analisar como os visitantes usam o site para melhorar a experiência.
                        </p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-8">
                    <button onclick="salvarConfigCookies()" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                    <button onclick="aceitarTodosCookies()" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-check mr-2"></i>Aceitar Todos
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('politica-cookies')); ?>" class="text-blue-600 hover:text-blue-700 text-sm underline">
                        Ver Política de Cookies Completa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" 
            class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-all duration-300 transform scale-0 z-40"
            onclick="voltarAoTopo()"
            title="Voltar ao topo"
            style="display: block; z-index: 9998;">
        <i class="fas fa-arrow-up text-xl"></i>
    </button>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // ============ GESTÃO DE COOKIES ============
        let cookieBannerMostrado = false;

        // Verificar se já existe preferência de cookies
        function verificarCookies() {
            try {
                const cookieConsent = localStorage.getItem('cookieConsent');
                const cookieBanner = document.getElementById('cookieBanner');
                
                if (!cookieConsent && !cookieBannerMostrado && cookieBanner) {
                    // Mostrar banner após 1 segundo
                    setTimeout(() => {
                        cookieBanner.style.transform = 'translateY(0)';
                        cookieBanner.classList.remove('translate-y-full');
                        cookieBannerMostrado = true;
                    }, 1000);
                }
            } catch (error) {
                console.error('Erro ao verificar cookies:', error);
            }
        }

        // Aceitar cookies
        function aceitarCookies() {
            const cookieSettings = {
                essenciais: true,
                funcionalidade: true,
                performance: true,
                aceito: true,
                timestamp: new Date().toISOString()
            };
            
            localStorage.setItem('cookieConsent', JSON.stringify(cookieSettings));
            ocultarBannerCookies();
            
            // Mostrar notificação de sucesso
            mostrarNotificacao('✅ Cookies aceitos com sucesso!', 'success');
        }

        // Rejeitar cookies (apenas essenciais)
        function rejeitarCookies() {
            const confirmacao = confirm(
                '⚠️ ATENÇÃO!\n\n' +
                'Ao rejeitar cookies, você será redirecionado para fora do site, pois alguns recursos podem não funcionar corretamente.\n\n' +
                'Deseja realmente continuar?'
            );
            
            if (confirmacao) {
                const cookieSettings = {
                    essenciais: true,
                    funcionalidade: false,
                    performance: false,
                    aceito: false,
                    timestamp: new Date().toISOString()
                };
                
                localStorage.setItem('cookieConsent', JSON.stringify(cookieSettings));
                
                // Mostrar mensagem e redirecionar
                alert('🍪 Você rejeitou os cookies.\n\nObrigado pela visita!');
                window.location.href = 'https://www.google.com/search?q=igrejas+evangelicas';
            }
        }

        // Configurar cookies (abrir modal)
        function configurarCookies() {
            const modal = document.getElementById('cookieModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Carregar configurações existentes
            const cookieConsent = localStorage.getItem('cookieConsent');
            if (cookieConsent) {
                const settings = JSON.parse(cookieConsent);
                document.getElementById('cookiesFuncionalidade').checked = settings.funcionalidade || false;
                document.getElementById('cookiesPerformance').checked = settings.performance || false;
            }
        }

        // Fechar modal de cookies
        function fecharModalCookies() {
            const modal = document.getElementById('cookieModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Salvar configurações personalizadas
        function salvarConfigCookies() {
            const funcionalidade = document.getElementById('cookiesFuncionalidade').checked;
            const performance = document.getElementById('cookiesPerformance').checked;
            
            const cookieSettings = {
                essenciais: true,
                funcionalidade: funcionalidade,
                performance: performance,
                aceito: true,
                timestamp: new Date().toISOString()
            };
            
            localStorage.setItem('cookieConsent', JSON.stringify(cookieSettings));
            fecharModalCookies();
            ocultarBannerCookies();
            
            mostrarNotificacao('⚙️ Configurações de cookies salvas!', 'success');
        }

        // Aceitar todos os cookies (do modal)
        function aceitarTodosCookies() {
            document.getElementById('cookiesFuncionalidade').checked = true;
            document.getElementById('cookiesPerformance').checked = true;
            salvarConfigCookies();
        }

        // Ocultar banner de cookies
        function ocultarBannerCookies() {
            const cookieBanner = document.getElementById('cookieBanner');
            if (cookieBanner) {
                cookieBanner.style.transform = 'translateY(100%)';
                cookieBanner.classList.add('translate-y-full');
                // Ocultar completamente após a animação
                setTimeout(() => {
                    cookieBanner.style.display = 'none';
                }, 300);
            }
        }

        // Mostrar notificação
        function mostrarNotificacao(mensagem, tipo = 'info') {
            const notificacao = document.createElement('div');
            notificacao.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${
                tipo === 'success' ? 'bg-green-600 text-white' : 
                tipo === 'error' ? 'bg-red-600 text-white' : 
                'bg-blue-600 text-white'
            }`;
            notificacao.innerHTML = `
                <div class="flex items-center space-x-2">
                    <span>${mensagem}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notificacao);
            
            // Animar entrada
            setTimeout(() => {
                notificacao.classList.remove('translate-x-full');
            }, 100);
            
            // Remover automaticamente após 5 segundos
            setTimeout(() => {
                notificacao.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notificacao.parentElement) {
                        notificacao.remove();
                    }
                }, 300);
            }, 5000);
        }

        // ============ BOTÃO VOLTAR AO TOPO ============
        function voltarAoTopo() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Mostrar/ocultar botão baseado no scroll
        function gerenciarBotaoTopo() {
            const botao = document.getElementById('backToTop');
            const header = document.querySelector('header');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (botao) {
                if (scrollTop > 300) {
                    botao.style.transform = 'scale(1)';
                    botao.classList.remove('scale-0');
                    botao.classList.add('scale-100');
                } else {
                    botao.style.transform = 'scale(0)';
                    botao.classList.add('scale-0');
                    botao.classList.remove('scale-100');
                }
            }
            
            // Adicionar classe de scroll no header
            if (header) {
                if (scrollTop > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
        }

        // ============ INICIALIZAÇÃO ============
        document.addEventListener('DOMContentLoaded', function() {
            
            // Verificar cookies ao carregar
            verificarCookies();
            
            // Event listener para scroll (botão topo + header) - com throttle
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(gerenciarBotaoTopo, 10);
            });
            
            // Executar uma vez no carregamento
            gerenciarBotaoTopo();
            
            // Smooth scroll para links internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Fechar modal de cookies clicando fora
            const cookieModal = document.getElementById('cookieModal');
            if (cookieModal) {
                cookieModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        fecharModalCookies();
                    }
                });
            }

            // Tecla ESC para fechar modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fecharModalCookies();
                }
            });
        });

        // ============ FUNÇÕES AUXILIARES ============
        // Obter configurações de cookies
        function obterConfigCookies() {
            const cookieConsent = localStorage.getItem('cookieConsent');
            return cookieConsent ? JSON.parse(cookieConsent) : null;
        }

        // Verificar se um tipo de cookie está permitido
        function cookiePermitido(tipo) {
            const config = obterConfigCookies();
            return config ? config[tipo] : false;
        }

        // Limpar todas as configurações de cookies (para debug)
        function limparCookies() {
            localStorage.removeItem('cookieConsent');
            location.reload();
        }

        // Função de teste para verificar se os elementos estão funcionando
        function testarElementos() {
            const cookieBanner = document.getElementById('cookieBanner');
            const backToTop = document.getElementById('backToTop');
            
            console.log('Teste de elementos:');
            console.log('Cookie Banner:', cookieBanner ? 'Encontrado' : 'Não encontrado');
            console.log('Botão Voltar ao Topo:', backToTop ? 'Encontrado' : 'Não encontrado');
            
            if (cookieBanner) {
                console.log('Cookie Banner classes:', cookieBanner.className);
                console.log('Cookie Banner style:', cookieBanner.style.cssText);
            }
            
            if (backToTop) {
                console.log('Back to Top classes:', backToTop.className);
                console.log('Back to Top style:', backToTop.style.cssText);
            }
        }

        // Executar teste após 3 segundos (apenas para debug)
        setTimeout(testarElementos, 3000);
    </script>
</body>
</html>
<?php /**PATH C:\wamp64\www\CBAV2025\resources\views/welcome.blade.php ENDPATH**/ ?>