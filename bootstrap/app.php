<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'permission.any' => \App\Http\Middleware\CheckAnyPermission::class,
            'role.redirect' => \App\Http\Middleware\RedirectBasedOnRole::class,
            'admin.access' => \App\Http\Middleware\AdminAccess::class,
            'ebd.access' => \App\Http\Middleware\EbdAccess::class,
            'notificacoes' => \App\Http\Middleware\NotificacoesMiddleware::class,
            'check.installation' => \App\Http\Middleware\CheckInstallation::class,
            'ensure.member.access' => \App\Http\Middleware\EnsureMemberAccess::class,
            'check.payment.gateways' => \App\Http\Middleware\CheckPaymentGateways::class,
            'gateway.status' => \App\Http\Middleware\CheckGatewayStatus::class,
            'websocket' => \App\Http\Middleware\WebSocketMiddleware::class,
        ]);

        // Substituir o middleware ValidatePostSize padrão pelo personalizado
        $middleware->replace(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, \App\Http\Middleware\CustomValidatePostSize::class);

        // Aplicar middleware de verificação de instalação globalmente
        $middleware->prepend(\App\Http\Middleware\CheckInstallation::class);

        // Aplicar middleware de redirecionamento baseado em role globalmente
        $middleware->append(\App\Http\Middleware\RedirectBasedOnRole::class);

        // Aplicar middleware de garantia de acesso de membro globalmente
        $middleware->append(\App\Http\Middleware\EnsureMemberAccess::class);

        // Aplicar middleware de notificações globalmente
        $middleware->append(\App\Http\Middleware\NotificacoesMiddleware::class);

        // Aplicar middleware de configurações do sistema globalmente
        $middleware->append(\App\Http\Middleware\ApplySystemConfig::class);

        // Aplicar middleware de timezone globalmente
        $middleware->append(\App\Http\Middleware\ApplyTimezone::class);

        // Registrar middleware CSRF personalizado
        $middleware->replace(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
        \App\Providers\ViewServiceProvider::class,
    ])
    ->create();
