<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Configuracao;
use App\Models\Campanha;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GatewayController extends Controller
{


    public function stripe(Request $request, Transacao $transacao)
    {
        // Verificar se a transação é anônima ou pertence ao usuário
        if ($transacao->membro_id && $transacao->membro_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        // Verificar se a transação ainda está pendente
        if ($transacao->status !== 'pendente') {
            return redirect()->route('home')
                ->with('error', 'Esta transação já foi processada.');
        }

        $stripeKey = Configuracao::get('stripe_key');

        if (!$stripeKey) {
            return redirect()->back()->with('error', 'Stripe não configurado no sistema.');
        }

        // Log do acesso ao Stripe
        Log::info('Acesso ao gateway Stripe', [
            'transacao_id' => $transacao->id,
            'valor' => $transacao->valor,
            'ip' => $request->ip(),
        ]);

        return view('gateways.stripe', compact('transacao', 'stripeKey'));
    }

    public function mercadopago(Request $request, Transacao $transacao)
    {
        // Verificar se a transação é anônima ou pertence ao usuário
        if ($transacao->membro_id && $transacao->membro_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        // Verificar se a transação ainda está pendente
        if ($transacao->status !== 'pendente') {
            return redirect()->route('home')
                ->with('error', 'Esta transação já foi processada.');
        }

        $mpKey = Configuracao::get('mercadopago_key');

        if (!$mpKey) {
            return redirect()->back()->with('error', 'Mercado Pago não configurado no sistema.');
        }

        // Log do acesso ao Mercado Pago
        Log::info('Acesso ao gateway Mercado Pago', [
            'transacao_id' => $transacao->id,
            'valor' => $transacao->valor,
            'ip' => $request->ip(),
        ]);

        return view('gateways.mercadopago', compact('transacao', 'mpKey'));
    }

    public function verifyPayment(Request $request, Transacao $transacao)
    {
        // Verificar se a transação é anônima ou pertence ao usuário
        if ($transacao->membro_id && $transacao->membro_id !== auth()->id()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        // Verificar se a transação ainda está pendente
        if ($transacao->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'status' => $transacao->status,
                'message' => 'Esta transação já foi processada.'
            ]);
        }

        try {
            $gateway = $transacao->dados_extras['gateway'] ?? 'stripe';

            switch ($gateway) {
                case 'stripe':
                    return $this->verificarPagamentoStripe($transacao, $request);
                case 'mercadopago':
                    return $this->verificarPagamentoMercadoPago($transacao, $request);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Gateway de pagamento não suportado. Apenas gateways oficiais são aceitos.'
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar pagamento', [
                'transacao_id' => $transacao->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar pagamento. Tente novamente.'
            ]);
        }
    }



    private function verificarPagamentoStripe(Transacao $transacao, Request $request)
    {
        $paymentIntentId = $request->input('payment_intent_id');

        if (!$paymentIntentId) {
            return response()->json([
                'success' => false,
                'message' => 'ID do pagamento não fornecido.'
            ]);
        }

        // Em produção, aqui você verificaria o status com a API do Stripe
        // Por enquanto, vamos simular
        $pagamentoConfirmado = rand(1, 10) > 5; // 50% de chance de estar confirmado

        if ($pagamentoConfirmado) {
            return $this->confirmarPagamento($transacao, 'stripe', $paymentIntentId);
        }

        return response()->json([
            'success' => false,
            'status' => 'pendente',
            'message' => 'Pagamento ainda está sendo processado.'
        ]);
    }

    private function verificarPagamentoMercadoPago(Transacao $transacao, Request $request)
    {
        $paymentId = $request->input('payment_id');

        if (!$paymentId) {
            return response()->json([
                'success' => false,
                'message' => 'ID do pagamento não fornecido.'
            ]);
        }

        // Em produção, aqui você verificaria o status com a API do Mercado Pago
        // Por enquanto, vamos simular
        $pagamentoConfirmado = rand(1, 10) > 5; // 50% de chance de estar confirmado

        if ($pagamentoConfirmado) {
            return $this->confirmarPagamento($transacao, 'mercadopago', $paymentId);
        }

        return response()->json([
            'success' => false,
            'status' => 'pendente',
            'message' => 'Pagamento ainda está sendo processado.'
        ]);
    }

    private function confirmarPagamento(Transacao $transacao, $gateway, $paymentId = null)
    {
        try {
            DB::beginTransaction();

            // Atualizar status da transação
            $transacao->update([
                'status' => 'confirmado',
                'dados_extras' => array_merge($transacao->dados_extras ?? [], [
                    'payment_id' => $paymentId,
                    'gateway_confirmado' => $gateway,
                    'data_confirmacao' => now()->toISOString(),
                ])
            ]);

            // Atualizar valor arrecadado da campanha se for uma doação
            if ($transacao->campanha_id && $transacao->tipo !== 'saida') {
                $campanha = Campanha::find($transacao->campanha_id);
                if ($campanha) {
                    $campanha->increment('valor_arrecadado', $transacao->valor);
                }
            }

            // Criar notificação de pagamento confirmado
            Notificacao::notificarPagamentoConfirmado($transacao);

            // Log da confirmação
            Log::info('Pagamento confirmado', [
                'transacao_id' => $transacao->id,
                'valor' => $transacao->valor,
                'gateway' => $gateway,
                'payment_id' => $paymentId,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'confirmado',
                'message' => 'Pagamento confirmado com sucesso!',
                'redirect_url' => route('doacao.confirmacao', $transacao->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao confirmar pagamento', [
                'transacao_id' => $transacao->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar pagamento. Tente novamente.'
            ]);
        }
    }
}
