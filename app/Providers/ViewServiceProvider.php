<?php

namespace App\Providers;

use App\Http\View\Composers\MemberLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usando um View Composer para compartilhar dados com o layout do membro e seus componentes.
        // Isso evita a repetição de lógica e consultas ao banco de dados nas views.
        View::composer(
            ['layouts.member', 'components.member.header', 'components.member.sidebar'],
            MemberLayoutComposer::class
        );
    }
}