<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Configuracao;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentGateways
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se é uma rota de doação pública
        $isPublicDonation = $request->routeIs('doacao.*');
        
        if ($isPublicDonation) {
            // Para doações públicas, apenas Stripe e Mercado Pago são permitidos
            $stripeEnabled = Configuracao::get('stripe_enabled') === '1' && 
                            Configuracao::get('stripe_key') && 
                            Configuracao::get('stripe_secret');
            
            $mercadopagoEnabled = Configuracao::get('mercadopago_enabled') === '1' && 
                                 Configuracao::get('mercadopago_key') && 
                                 Configuracao::get('mercadopago_token');
            
            if (!$stripeEnabled && !$mercadopagoEnabled) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Nenhum gateway de pagamento configurado para doações públicas. Configure Stripe ou Mercado Pago.',
                        'code' => 'PUBLIC_PAYMENT_GATEWAYS_NOT_CONFIGURED'
                    ], 503);
                }
                
                return redirect()->route('admin.system.settings.index')->with('error', 
                    'Nenhum gateway de pagamento está configurado para doações públicas. Configure pelo menos um gateway (Stripe ou Mercado Pago) para aceitar doações.');
            }
        } else {
            // Para membros logados, verificar todos os gateways (incluindo PIX)
            $stripeEnabled = Configuracao::get('stripe_enabled') === '1' && 
                            Configuracao::get('stripe_key') && 
                            Configuracao::get('stripe_secret');
            
            $mercadopagoEnabled = Configuracao::get('mercadopago_enabled') === '1' && 
                                 Configuracao::get('mercadopago_key') && 
                                 Configuracao::get('mercadopago_token');
            
            $pixEnabled = Configuracao::get('pix_enabled') === '1' && 
                         Configuracao::get('pix_chave') && 
                         Configuracao::get('pix_beneficiario');
            
            if (!$stripeEnabled && !$mercadopagoEnabled && !$pixEnabled) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Nenhum gateway de pagamento configurado. Entre em contato com o administrador.',
                        'code' => 'PAYMENT_GATEWAYS_NOT_CONFIGURED'
                    ], 503);
                }
                
                return redirect()->route('admin.system.settings.index')->with('error', 
                    'Nenhum gateway de pagamento está configurado. Configure pelo menos um gateway para aceitar doações.');
            }
        }
        
        return $next($request);
    }
} 