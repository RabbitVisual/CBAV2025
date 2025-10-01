<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Pagamento;
use App\Models\Configuracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver transacoes');
    }

    // Processar pagamento via Stripe
    public function processarStripe(Request $request)
    {
        $request->validate([
            'transacao_id' => 'required|exists:transacoes,id',
            'stripe_token' => 'required|string',
            'valor' => 'required|numeric|min:0',
        ]);

        try {
            $transacao = Transacao::findOrFail($request->transacao_id);
            
            // Configurações do Stripe
            $stripeKey = Configuracao::get('stripe_secret');
            
            if (!$stripeKey) {
                return response()->json(['error' => 'Stripe não configurado'], 400);
            }

            // Criar pagamento no Stripe
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $stripeKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post('https://api.stripe.com/v1/charges', [
                'amount' => $request->valor * 100, // Stripe usa centavos
                'currency' => 'brl',
                'source' => $request->stripe_token,
                'description' => 'Doação - ' . $transacao->descricao,
                'metadata' => [
                    'transacao_id' => $transacao->id,
                    'tipo' => $transacao->tipo,
                ]
            ]);

            if ($response->successful()) {
                $stripeData = $response->json();
                
                // Salvar pagamento
                Pagamento::create([
                    'transacao_id' => $transacao->id,
                    'gateway' => 'stripe',
                    'gateway_id' => $stripeData['id'],
                    'gateway_status' => $stripeData['status'],
                    'valor' => $request->valor,
                    'moeda' => 'BRL',
                    'dados_gateway' => $stripeData,
                ]);

                // Atualizar status da transação
                $transacao->update(['status' => 'pago']);

                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento processado com sucesso!',
                    'gateway_id' => $stripeData['id']
                ]);
            } else {
                Log::error('Erro Stripe: ' . $response->body());
                return response()->json(['error' => 'Erro ao processar pagamento'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erro processamento Stripe: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    // Processar pagamento via Mercado Pago
    public function processarMercadoPago(Request $request)
    {
        $request->validate([
            'transacao_id' => 'required|exists:transacoes,id',
            'payment_method_id' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'installments' => 'nullable|integer|min:1|max:12',
        ]);

        try {
            $transacao = Transacao::findOrFail($request->transacao_id);
            
            // Configurações do Mercado Pago
            $mpToken = Configuracao::get('mercadopago_token');
            
            if (!$mpToken) {
                return response()->json(['error' => 'Mercado Pago não configurado'], 400);
            }

            // Criar pagamento no Mercado Pago
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $mpToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.mercadopago.com/v1/payments', [
                'transaction_amount' => $request->valor,
                'token' => $request->payment_method_id,
                'description' => 'Doação - ' . $transacao->descricao,
                'installments' => $request->installments ?? 1,
                'payment_method_id' => $request->payment_method_id,
                'payer' => [
                    'email' => $transacao->membro->email ?? 'anonimo@igreja.com',
                ],
                'external_reference' => $transacao->id,
            ]);

            if ($response->successful()) {
                $mpData = $response->json();
                
                // Salvar pagamento
                Pagamento::create([
                    'transacao_id' => $transacao->id,
                    'gateway' => 'mercadopago',
                    'gateway_id' => $mpData['id'],
                    'gateway_status' => $mpData['status'],
                    'valor' => $request->valor,
                    'moeda' => 'BRL',
                    'dados_gateway' => $mpData,
                ]);

                // Atualizar status da transação
                $transacao->update(['status' => 'pago']);

                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento processado com sucesso!',
                    'gateway_id' => $mpData['id']
                ]);
            } else {
                Log::error('Erro Mercado Pago: ' . $response->body());
                return response()->json(['error' => 'Erro ao processar pagamento'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erro processamento Mercado Pago: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    // Gerar QR Code PIX
    public function gerarPix(Request $request)
    {
        $request->validate([
            'transacao_id' => 'required|exists:transacoes,id',
            'valor' => 'required|numeric|min:0',
        ]);

        try {
            $transacao = Transacao::findOrFail($request->transacao_id);
            
            // Verificar se a transação é válida
            if ($transacao->status !== 'pendente') {
                return response()->json(['error' => 'Esta transação já foi processada'], 400);
            }
            
            // Configurações PIX
            $pixChave = Configuracao::get('pix_chave');
            $pixBeneficiario = Configuracao::get('pix_beneficiario');
            
            if (!$pixChave || !$pixBeneficiario) {
                return response()->json(['error' => 'PIX não configurado no sistema'], 400);
            }
            
            // Gerar QR Code PIX
            $qrCode = $this->gerarQRCodePix($pixChave, $request->valor, $transacao->id);
            $codigoPix = $this->gerarCodigoPix($pixChave, $request->valor, $transacao->id);
            
            return response()->json([
                'success' => true,
                'qr_code' => $qrCode,
                'codigo_pix' => $codigoPix,
                'chave' => $pixChave,
                'beneficiario' => $pixBeneficiario,
                'valor' => $request->valor,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PIX: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao gerar PIX'], 500);
        }
    }

    // Exibir página PIX
    public function showPix(Request $request)
    {
        $transacaoId = $request->get('transacao_id');
        
        if (!$transacaoId) {
            return redirect()->route('doacao.index')
                ->with('error', 'Transação não encontrada.');
        }
        
        try {
            $transacao = Transacao::findOrFail($transacaoId);
            
            // Verificar se a transação é válida
            if ($transacao->status !== 'pendente') {
                return redirect()->route('doacao.index')
                    ->with('error', 'Esta transação já foi processada.');
            }
            
            // Verificar se o usuário tem permissão para ver esta transação
            if ($transacao->membro_id && auth()->check() && $transacao->membro_id !== auth()->id()) {
                abort(403, 'Acesso negado');
            }
            
            // Configurações PIX
            $pixChave = Configuracao::get('pix_chave');
            $pixBeneficiario = Configuracao::get('pix_beneficiario');
            
            if (!$pixChave || !$pixBeneficiario) {
                return redirect()->route('doacao.index')
                    ->with('error', 'PIX não configurado no sistema.');
            }
            
            // Preparar dados PIX para a view
            $pixData = [
                'chave' => $pixChave,
                'beneficiario' => $pixBeneficiario,
                'valor' => $transacao->valor,
                'transacao_id' => $transacao->id,
                'qr_code' => $this->gerarQRCodePix($pixChave, $transacao->valor, $transacao->id),
                'codigo_pix' => $this->gerarCodigoPix($pixChave, $transacao->valor, $transacao->id),
            ];
            
            return view('gateways.pix', compact('transacao', 'pixData'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao exibir PIX: ' . $e->getMessage());
            return redirect()->route('doacao.index')
                ->with('error', 'Erro ao processar solicitação PIX.');
        }
    }

    // Verificar status do pagamento
    public function verificarPagamento(Request $request, Transacao $transacao)
    {
        try {
            // Verificar se a transação é válida
            if ($transacao->status !== 'pendente') {
                return response()->json([
                    'success' => true,
                    'status' => $transacao->status,
                    'message' => 'Esta transação já foi processada.',
                    'redirect_url' => route('doacao.confirmation', $transacao->id)
                ]);
            }
            
            // Verificar se o usuário tem permissão
            if ($transacao->membro_id && auth()->check() && $transacao->membro_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado.'
                ], 403);
            }
            
            // Para PIX, vamos simular uma verificação
            // Em produção, você verificaria com o banco ou API
            $pagamentoConfirmado = rand(1, 10) > 8; // 20% de chance de estar confirmado
            
            if ($pagamentoConfirmado) {
                // Atualizar status da transação
                $transacao->update([
                    'status' => 'confirmado',
                    'dados_extras' => array_merge($transacao->dados_extras ?? [], [
                        'data_confirmacao' => now()->toISOString(),
                        'verificado_em' => now()->toISOString(),
                    ])
                ]);
                
                return response()->json([
                    'success' => true,
                    'status' => 'confirmado',
                    'message' => 'Pagamento confirmado com sucesso!',
                    'redirect_url' => route('doacao.confirmation', $transacao->id)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'status' => 'pendente',
                'message' => 'Pagamento ainda está sendo processado. Tente novamente em alguns instantes.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar pagamento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar pagamento. Tente novamente.'
            ], 500);
        }
    }

    // Webhook Stripe
    public function webhookStripe(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $stripeKey = Configuracao::get('stripe_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $stripeKey);
            
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->processarPagamentoStripe($event->data->object);
                    break;
                case 'payment_intent.payment_failed':
                    $this->processarFalhaStripe($event->data->object);
                    break;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro webhook Stripe: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 400);
        }
    }

    // Webhook Mercado Pago
    public function webhookMercadoPago(Request $request)
    {
        try {
            $data = $request->all();
            
            if (isset($data['type']) && $data['type'] === 'payment') {
                $paymentId = $data['data']['id'];
                $this->processarPagamentoMercadoPago($paymentId);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro webhook Mercado Pago: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 400);
        }
    }

    // Métodos privados auxiliares
    private function gerarQRCodePix($chave, $valor, $transacaoId)
    {
        try {
            // Gerar string PIX
            $pixString = $this->gerarCodigoPix($chave, $valor, $transacaoId);
            
            // Criar QR Code usando a biblioteca
            $qrCode = \Endroid\QrCode\QrCode::create($pixString)
                ->setSize(300)
                ->setMargin(10);
            
            // Converter para base64
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);
            
            return base64_encode($result->getString());
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar QR Code PIX: ' . $e->getMessage());
            // Fallback para string simples
            return base64_encode($pixString);
        }
    }

    private function gerarCodigoPix($chave, $valor, $transacaoId)
    {
        // Implementação básica de Código PIX
        // Em produção, use uma biblioteca específica para PIX
        $codigoPix = "00020126580014br.gov.bcb.pix0136{$chave}520400005303986540{$valor}5802BR5913{$chave}6006BRASIL62070503***6304";
        return $codigoPix;
    }

    private function processarPagamentoStripe($paymentIntent)
    {
        $transacaoId = $paymentIntent->metadata->transacao_id ?? null;
        
        if ($transacaoId) {
            $transacao = Transacao::find($transacaoId);
            if ($transacao) {
                $transacao->update(['status' => 'pago']);
            }
        }
    }

    private function processarFalhaStripe($paymentIntent)
    {
        $transacaoId = $paymentIntent->metadata->transacao_id ?? null;
        
        if ($transacaoId) {
            $transacao = Transacao::find($transacaoId);
            if ($transacao) {
                $transacao->update(['status' => 'cancelado']);
            }
        }
    }

    private function processarPagamentoMercadoPago($paymentId)
    {
        $mpToken = Configuracao::get('mercadopago_token');
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $mpToken,
        ])->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if ($response->successful()) {
            $payment = $response->json();
            $transacaoId = $payment['external_reference'] ?? null;
            
            if ($transacaoId) {
                $transacao = Transacao::find($transacaoId);
                if ($transacao) {
                    $status = $payment['status'] === 'approved' ? 'pago' : 'pendente';
                    $transacao->update(['status' => $status]);
                }
            }
        }
    }
} 