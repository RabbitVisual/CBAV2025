<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PermissionHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar o Laravel Excel
        $this->app->register(\Maatwebsite\Excel\ExcelServiceProvider::class);
        
        // Registrar o Laravel DomPDF
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        
        // Registrar o Spatie Permission
        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar locale do Carbon para português
        \Carbon\Carbon::setLocale('pt_BR');
        
        // Registrar observers
        \App\Models\EbdQuizSessao::observe(\App\Observers\EbdQuizSessaoObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        
        // Registrar helpers de permissão
        Blade::directive('canAccess', function ($expression) {
            return "<?php if (App\\Helpers\\PermissionHelper::canAccess{$expression}): ?>";
        });

        Blade::directive('endCanAccess', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('canAccessSection', function ($expression) {
            return "<?php if (App\\Helpers\\PermissionHelper::isSectionAvailable{$expression}): ?>";
        });

        Blade::directive('endCanAccessSection', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('canAccessItem', function ($expression) {
            return "<?php if (App\\Helpers\\PermissionHelper::isItemAvailable{$expression}): ?>";
        });

        Blade::directive('endCanAccessItem', function () {
            return "<?php endif; ?>";
        });
    }
}
