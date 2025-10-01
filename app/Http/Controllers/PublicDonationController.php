<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\{Transacao, Campanha, Ministerio, Configuracao};
use App\Services\NotificacaoService;

class PublicDonationController extends Controller
{
    /**
     * Página pública de doação
     */
    public function index()
    {
        // Buscar configurações
        $configuracoes = $this->getConfiguracoes();
        
        // Buscar campanhas ativas
        $campanhas = Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('data_fim', 'asc')
            ->get();

        // Buscar ministérios ativos
        $ministerios = Ministerio::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('doacao.index', compact('configuracoes', 'campanhas', 'ministerios'));
    }

    /**
     * Processar doação pública
     */
    public function process(Request $request)
    {
        // Preparar o valor para validação
        $valor = $request->valor;
        if (is_string($valor)) {
            $valor = str_replace(['R$', ' ', '.'], '', $valor);
            $valor = str_replace(',', '.', $valor);
            $request->merge(['valor' => $valor]);
        }

        $request->validate([
            'valor' => 'required|numeric|min:1',
            'tipo_destino' => 'required|in:igreja,campanha,ministerio',
            'destino_id' => 'nullable|required_if:tipo_destino,campanha,ministerio',
            'gateway' => 'required|in:stripe,mercadopago',
            'nome_doador' => 'nullable|string|max:255',
            'email_doador' => 'nullable|email|max:255',
            'descricao' => 'nullable|string|max:500',
        ]);

        try {
            // Verificar se o gateway está configurado
            if (!$this->verificarGatewayConfigurado($request->gateway)) {
                return redirect()->back()
                    ->with('error', 'Gateway de pagamento não configurado.')
                    ->withInput();
            }

            // Determinar o destino
            $destino = $this->determinarDestino($request->tipo_destino, $request->destino_id);

            // Criar transação
            $transacao = Transacao::create([
                'tipo' => 'entrada',
                'valor' => $request->valor,
                'descricao' => $request->descricao ?? 'Doação pública',
                'status' => 'pendente',
                'data' => now(),
                'campanha_id' => $destino['campanha_id'] ?? null,
                'ministerio_id' => $destino['ministerio_id'] ?? null,
                'dados_extras' => [
                    'nome_doador' => $request->nome_doador,
                    'email_doador' => $request->email_doador,
                    'tipo_doador' => 'publico',
                    'gateway' => $request->gateway,
                    'tipo_destino' => $request->tipo_destino,
                    'destino_id' => $request->destino_id
                ]
            ]);

            // Redirecionar para gateway de pagamento
            return $this->redirecionarParaGateway($request->gateway, $transacao);

        } catch (\Exception $e) {
            Log::error('Erro ao processar doação pública: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao processar doação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Confirmação de doação
     */
    public function confirmation(Transacao $transacao)
    {
        return view('doacao.confirmacao', compact('transacao'));
    }

    /**
     * Verificar gateway configurado
     */
    private function verificarGatewayConfigurado($gateway)
    {
        $configuracoes = $this->getConfiguracoes();
        
        switch ($gateway) {
            case 'stripe':
                return !empty($configuracoes['stripe_key'] ?? '') && !empty($configuracoes['stripe_secret'] ?? '');
            case 'mercadopago':
                return !empty($configuracoes['mercadopago_key'] ?? '') && !empty($configuracoes['mercadopago_token'] ?? '');
            default:
                return false;
        }
    }

    /**
     * Determinar destino da doação
     */
    private function determinarDestino($tipoDestino, $destinoId)
    {
        switch ($tipoDestino) {
            case 'campanha':
                $campanha = Campanha::find($destinoId);
                return [
                    'campanha_id' => $campanha ? $campanha->id : null,
                    'ministerio_id' => null
                ];
            case 'ministerio':
                $ministerio = Ministerio::find($destinoId);
                return [
                    'campanha_id' => null,
                    'ministerio_id' => $ministerio ? $ministerio->id : null
                ];
            default:
                return [
                    'campanha_id' => null,
                    'ministerio_id' => null
                ];
        }
    }

    /**
     * Redirecionar para gateway de pagamento
     */
    private function redirecionarParaGateway($gateway, $transacao)
    {
        switch ($gateway) {
            case 'stripe':
                return redirect()->route('gateway.stripe', $transacao);
            case 'mercadopago':
                return redirect()->route('gateway.mercadopago', $transacao);
            default:
                return redirect()->back()
                    ->with('error', 'Gateway de pagamento não suportado.')
                    ->withInput();
        }
    }

    /**
     * Obter configurações do sistema
     */
    private function getConfiguracoes()
    {
        $configs = Configuracao::all()->keyBy('chave');
        
        return [
            'stripe_key' => $configs['stripe_key']->valor ?? '',
            'stripe_secret' => $configs['stripe_secret']->valor ?? '',
            'mercadopago_key' => $configs['mercadopago_key']->valor ?? '',
            'mercadopago_token' => $configs['mercadopago_token']->valor ?? '',
            'pix_chave' => $configs['pix_chave']->valor ?? '',
            'pix_beneficiario' => $configs['pix_beneficiario']->valor ?? '',
            'doacao_valor_minimo' => $configs['doacao_valor_minimo']->valor ?? 1,
            'doacao_valor_maximo' => $configs['doacao_valor_maximo']->valor ?? 10000,
            'doacao_sem_login' => $configs['doacao_sem_login']->valor ?? '1',
            'doacao_ativa' => $configs['doacao_ativa']->valor ?? '1',
        ];
    }
} 