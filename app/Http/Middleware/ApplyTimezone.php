<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SystemConfigHelper;
use Carbon\Carbon;

class ApplyTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Aplicar fuso horário configurado
        $timezone = SystemConfigHelper::getCurrentTimezone();
        
        if ($timezone) {
            // Aplicar no PHP
            date_default_timezone_set($timezone);
            
            // Log apenas em debug
            if (config('app.debug')) {
                \Log::debug('Timezone aplicado via middleware', [
                    'timezone' => $timezone,
                    'current_time' => now()->format('Y-m-d H:i:s'),
                    'url' => $request->fullUrl()
                ]);
            }
        }
        
        return $next($request);
    }
} 