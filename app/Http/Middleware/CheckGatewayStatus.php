<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CheckGatewayStatus
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
        // Verificar se os gateways estão ativos via .env
        $gatewaysStatus = [
            'stripe' => env('STRIPE_ENABLED', false),
            'mercadopago' => env('MERCADOPAGO_ENABLED', false),
            'pix' => env('PIX_ENABLED', false)
        ];

        // Adicionar status dos gateways ao request para uso posterior
        $request->merge(['gateways_status' => $gatewaysStatus]);

        // Log para debug (apenas em desenvolvimento)
        if (config('app.debug')) {
            Log::debug('Gateway Status Check', [
                'route' => $request->route()->getName(),
                'gateways' => $gatewaysStatus
            ]);
        }

        // Verificar se a rota é relacionada a pagamentos e se algum gateway está ativo
        if ($this->isPaymentRoute($request)) {
            $hasActiveGateway = array_filter($gatewaysStatus);

            if (empty($hasActiveGateway)) {
                // Se nenhum gateway está ativo, redirecionar ou retornar erro
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Nenhum gateway de pagamento está ativo no momento.',
                        'code' => 'NO_ACTIVE_GATEWAYS'
                    ], 503);
                }

                return redirect()->back()->with('error', 'Sistema de pagamentos temporariamente indisponível.');
            }
        }

        return $next($request);
    }

    /**
     * Verificar se a rota é relacionada a pagamentos
     */
    private function isPaymentRoute(Request $request): bool
    {
        $paymentRoutes = [
            'payment.*',
            'donation.*',
            'checkout.*',
            'stripe.*',
            'mercadopago.*',
            'pix.*'
        ];

        $currentRoute = $request->route()->getName();

        foreach ($paymentRoutes as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar se um gateway específico está ativo
     */
    public static function isGatewayActive(string $gateway): bool
    {
        $envKey = strtoupper($gateway) . '_ENABLED';
        return filter_var(env($envKey, false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Obter lista de gateways ativos
     */
    public static function getActiveGateways(): array
    {
        $gateways = ['stripe', 'mercadopago', 'pix'];
        $activeGateways = [];

        foreach ($gateways as $gateway) {
            if (self::isGatewayActive($gateway)) {
                $activeGateways[] = $gateway;
            }
        }

        return $activeGateways;
    }
}
