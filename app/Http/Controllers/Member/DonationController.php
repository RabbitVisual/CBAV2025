<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Transacao, Campanha, Membro, Notificacao};
use App\Services\NotificacaoService;
use App\Models\Configuracao;

class DonationController extends Controller
{
    /**
     * Página pública de doação (não logado)
     */
    public function publicIndex()
    {
        $campanhas = Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('data_fim', 'asc')
            ->get();

        return view('member.donations.public', compact('campanhas'));
    }

    /**
     * Processar doação pública
     */
    public function processPublic(Request $request)
    {
        // Preparar o valor para validação
        $valor = $request->valor;
        if (is_string($valor)) {
            // Remover R$, espaços e pontos de milhares, converter vírgula para ponto
            $valor = str_replace(['R$', ' '], '', $valor);
            $valor = preg_replace('/\.(?=.*,)/', '', $valor); // Remove pontos de milhares
            $valor = str_replace(',', '.', $valor);
            $valor = (float) $valor; // Garantir que é um número
            $request->merge(['valor' => $valor]);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'valor' => 'required|numeric|min:0.01',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'gateway' => 'required|in:stripe,mercadopago,pix',
            'descricao' => 'nullable|string|max:500',
        ]);

        try {
            // Criar transação
            $transacao = Transacao::create([
                'tipo' => 'entrada', // Corrigido: doações são receitas (entrada)
                'valor' => $request->valor,
                'descricao' => $request->descricao ?? 'Doação pública',
                'status' => 'pendente',
                'data' => now(),
                'campanha_id' => $request->campanha_id,
                'dados_extras' => [
                    'nome' => $request->nome,
                    'email' => $request->email,
                    'tipo_doador' => 'publico',
                    'gateway' => $request->gateway
                ]
            ]);

            // Redirecionar para gateway de pagamento
            return $this->redirecionarParaGateway($request->gateway, $transacao);

        } catch (\Exception $e) {
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
        return view('member.donations.confirmation', compact('transacao'));
    }

    /**
     * Estatísticas públicas
     */
    public function statistics()
    {
        $estatisticas = [
            'total_arrecadado' => Transacao::where('status', 'confirmado')->sum('valor'),
            'total_transacoes' => Transacao::where('status', 'confirmado')->count(),
            'campanhas_ativas' => Campanha::where('ativo', true)->where('data_fim', '>=', now())->count(),
        ];

        return view('member.donations.statistics', compact('estatisticas'));
    }

    /**
     * Página principal de doações
     */
    public function index()
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Perfil de membro não encontrado.');
        }

        $doacoes = $membro->transacoes()
            ->with('campanha')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalDoado = $membro->transacoes()->where('status', 'confirmado')->sum('valor');
        
        // Estatísticas detalhadas
        $doacoesConfirmadas = $membro->transacoes()->where('status', 'confirmado')->count();
        $doacoesPendentes = $membro->transacoes()->where('status', 'pendente')->count();
        $doacoesMes = $membro->transacoes()
            ->where('status', 'confirmado')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('valor');

        // Campanhas para o filtro
        $campanhas = Campanha::where('ativo', true)
            ->orderBy('titulo')
            ->get();

        $estatisticas = [
            'total_doacoes' => $doacoesConfirmadas,
            'valor_total' => $totalDoado,
            'doacoes_pendentes' => $doacoesPendentes,
        ];

        return view('member.donations.index', compact(
            'doacoes', 
            'estatisticas', 
            'totalDoado',
            'doacoesConfirmadas',
            'doacoesPendentes',
            'doacoesMes',
            'campanhas'
        ));
    }

    /**
     * Histórico de doações
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Perfil de membro não encontrado.');
        }

        // Query base
        $query = $membro->transacoes()->with('campanha');

        // Aplicar filtros
        if ($request->filled('periodo')) {
            $dias = (int) $request->periodo;
            $query->where('created_at', '>=', now()->subDays($dias));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('descricao', 'like', "%{$search}%")
                  ->orWhereHas('campanha', function($campanhaQuery) use ($search) {
                      $campanhaQuery->where('titulo', 'like', "%{$search}%");
                  });
            });
        }

        // Obter transações paginadas
        $transacoes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calcular estatísticas
        $totalDoado = $membro->transacoes()
            ->where('tipo', 'entrada')
            ->where('status', 'confirmado')
            ->sum('valor');

        $totalDoacoes = $membro->transacoes()
            ->where('tipo', 'entrada')
            ->count();

        $doacoesMes = $membro->transacoes()
            ->where('tipo', 'entrada')
            ->where('status', 'confirmado')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('valor');

        $mediaMensal = $membro->transacoes()
            ->where('tipo', 'entrada')
            ->where('status', 'confirmado')
            ->where('created_at', '>=', now()->subMonths(6))
            ->avg('valor') ?? 0;

        // Dados para o gráfico (últimos 6 meses)
        $dadosGrafico = [];
        $labels = [];
        $valores = [];

        for ($i = 5; $i >= 0; $i--) {
            $data = now()->subMonths($i);
            $labels[] = $data->format('M/Y');
            
            $valor = $membro->transacoes()
                ->where('tipo', 'entrada')
                ->where('status', 'confirmado')
                ->whereYear('created_at', $data->year)
                ->whereMonth('created_at', $data->month)
                ->sum('valor');
            
            $valores[] = $valor;
        }

        $dadosGrafico = [
            'labels' => $labels,
            'valores' => $valores
        ];

        return view('member.donations.history', compact(
            'transacoes',
            'totalDoado',
            'totalDoacoes',
            'doacoesMes',
            'mediaMensal',
            'dadosGrafico'
        ));
    }

    /**
     * Campanhas disponíveis
     */
    public function campaigns()
    {
        $campanhas = Campanha::where('ativo', true)
            ->orderBy('data_fim', 'asc')
            ->get()
            ->map(function ($campanha) {
                $campanha->dias_restantes = $this->calcularDiasRestantes($campanha);
                return $campanha;
            });

        return view('member.donations.campaigns', compact('campanhas'));
    }

    /**
     * Exibir campanha específica
     */
    public function showCampaign(Campanha $campanha)
    {
        if (!$campanha->ativo) {
            return redirect()->route('member.donations.campaigns')
                ->with('error', 'Campanha não encontrada ou inativa.');
        }

        $campanha->dias_restantes = $this->calcularDiasRestantes($campanha);

        return view('member.donations.campaign', compact('campanha'));
    }

    /**
     * Página de doação
     */
    public function donate(Request $request)
    {
        $campanhaId = $request->get('campanha_id');
        $campanha = null;

        if ($campanhaId) {
            $campanha = Campanha::find($campanhaId);
        }

        $campanhas = Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('titulo')
            ->get();

        return view('member.donations.donate', compact('campanha', 'campanhas'));
    }

    /**
     * Processar doação do membro
     */
    public function processDonation(Request $request)
    {
        // Preparar o valor para validação
        $valor = $request->valor;
        if (is_string($valor)) {
            // Remover R$, espaços e pontos de milhares, converter vírgula para ponto
            $valor = str_replace(['R$', ' '], '', $valor);
            $valor = preg_replace('/\.(?=.*,)/', '', $valor); // Remove pontos de milhares
            $valor = str_replace(',', '.', $valor);
            $valor = (float) $valor; // Garantir que é um número
            $request->merge(['valor' => $valor]);
        }

        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'gateway' => 'required|in:stripe,mercadopago,pix',
            'descricao' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Perfil de membro não encontrado.');
        }

        try {
            // Verificar gateway configurado
            if (!$this->verificarGatewayConfigurado($request->gateway)) {
                return redirect()->back()
                    ->with('error', 'Gateway de pagamento não configurado.')
                    ->withInput();
            }

            // Criar transação com valor correto
            $transacao = Transacao::create([
                'membro_id' => $membro->id,
                'tipo' => 'entrada', // Corrigido: doações são receitas (entrada)
                'valor' => (float) $request->valor, // Garantir que é float
                'descricao' => $request->descricao ?? 'Doação de membro',
                'status' => 'pendente',
                'data' => now(),
                'campanha_id' => $request->campanha_id,
                'dados_extras' => [
                    'tipo_doador' => 'membro',
                    'gateway' => $request->gateway,
                    'valor_original' => $request->valor // Manter valor original
                ]
            ]);

            // Notificar sobre nova transação
            NotificacaoService::notificarNovaTransacao($transacao);

            // Redirecionar para gateway
            return $this->redirecionarParaGateway($request->gateway, $transacao);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao processar doação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Verificar se gateway está configurado
     */
    private function verificarGatewayConfigurado($gateway)
    {
        switch ($gateway) {
            case 'stripe':
                $stripeKey = Configuracao::get('stripe_key');
                $stripeSecret = Configuracao::get('stripe_secret');
                return !empty($stripeKey) && !empty($stripeSecret);
                
            case 'mercadopago':
                $mpKey = Configuracao::get('mercadopago_key');
                $mpToken = Configuracao::get('mercadopago_token');
                return !empty($mpKey) && !empty($mpToken);
                
            case 'pix':
                $pixChave = Configuracao::get('pix_chave');
                $pixBeneficiario = Configuracao::get('pix_beneficiario');
                return !empty($pixChave) && !empty($pixBeneficiario);
                
            default:
                return false;
        }
    }

    /**
     * Redirecionar para gateway de pagamento
     */
    private function redirecionarParaGateway($gateway, $transacao)
    {
        switch ($gateway) {
            case 'stripe':
                return redirect()->route('member.donations.stripe.show', ['transacao_id' => $transacao->id]);
            case 'mercadopago':
                return redirect()->route('member.donations.mercadopago.show', ['transacao_id' => $transacao->id]);
            case 'pix':
                return redirect()->route('member.donations.pix.show', ['transacao_id' => $transacao->id]);
            default:
                return redirect()->back()
                    ->with('error', 'Gateway de pagamento não suportado.')
                    ->withInput();
        }
    }

    /**
     * Mostrar página PIX para membros
     */
    public function showPix(Request $request)
    {
        $transacaoId = $request->transacao_id;
        $transacao = Transacao::findOrFail($transacaoId);
        
        // Verificar se o usuário tem permissão para ver esta transação
        $user = Auth::user();
        $membro = $user->membro;
        
        if (!$membro || $transacao->membro_id !== $membro->id) {
            abort(403, 'Você não tem permissão para acessar esta transação.');
        }

        // Gerar dados PIX
        $pixChave = Configuracao::get('pix_chave');
        $pixBeneficiario = Configuracao::get('pix_beneficiario');
        
        if (empty($pixChave) || empty($pixBeneficiario)) {
            return redirect()->back()
                ->with('error', 'Configurações PIX não encontradas.');
        }

        // Gerar código PIX
        $codigoPix = $this->gerarCodigoPix($pixChave, $transacao->valor, $transacao->id);
        $qrCode = $this->gerarQRCodePix($pixChave, $transacao->valor, $transacao->id);

        $pixData = [
            'chave' => $pixChave,
            'beneficiario' => $pixBeneficiario,
            'valor' => number_format($transacao->valor, 2, ',', '.'),
            'codigo_pix' => $codigoPix,
            'qr_code' => $qrCode,
            'transacao_id' => $transacao->id
        ];

        return view('member.donations.pix', compact('pixData', 'transacao'));
    }

    /**
     * Mostrar página Stripe para membros
     */
    public function showStripe(Request $request)
    {
        $transacaoId = $request->transacao_id;
        $transacao = Transacao::findOrFail($transacaoId);
        
        // Verificar se o usuário tem permissão para ver esta transação
        $user = Auth::user();
        $membro = $user->membro;
        
        if (!$membro || $transacao->membro_id !== $membro->id) {
            abort(403, 'Você não tem permissão para acessar esta transação.');
        }

        // Verificar configurações do Stripe
        $stripeKey = Configuracao::get('stripe_key');
        $stripeSecret = Configuracao::get('stripe_secret');
        
        if (empty($stripeKey) || empty($stripeSecret)) {
            return redirect()->back()
                ->with('error', 'Configurações do Stripe não encontradas.');
        }

        return view('member.donations.stripe', compact('transacao', 'stripeKey'));
    }

    /**
     * Mostrar página Mercado Pago para membros
     */
    public function showMercadoPago(Request $request)
    {
        $transacaoId = $request->transacao_id;
        $transacao = Transacao::findOrFail($transacaoId);
        
        // Verificar se o usuário tem permissão para ver esta transação
        $user = Auth::user();
        $membro = $user->membro;
        
        if (!$membro || $transacao->membro_id !== $membro->id) {
            abort(403, 'Você não tem permissão para acessar esta transação.');
        }

        // Verificar configurações do Mercado Pago
        $mpKey = Configuracao::get('mercadopago_key');
        $mpToken = Configuracao::get('mercadopago_token');
        
        if (empty($mpKey) || empty($mpToken)) {
            return redirect()->back()
                ->with('error', 'Configurações do Mercado Pago não encontradas.');
        }

        return view('member.donations.mercadopago', compact('transacao', 'mpKey'));
    }

    /**
     * Verificar status do pagamento PIX
     */
    public function verificarPagamentoPix(Request $request, $transacaoId)
    {
        try {
            $transacao = Transacao::findOrFail($transacaoId);
            
            // Verificar se o usuário tem permissão
            $user = Auth::user();
            $membro = $user->membro;
            
            if (!$membro || $transacao->membro_id !== $membro->id) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }

            // Verificar se há pagamento confirmado
            $pagamento = $transacao->pagamentos()
                ->where('status', 'confirmado')
                ->first();

            if ($pagamento) {
                $transacao->update(['status' => 'confirmado']);
                return response()->json([
                    'status' => 'confirmado',
                    'message' => 'Pagamento confirmado com sucesso!'
                ]);
            }

            return response()->json([
                'status' => 'pendente',
                'message' => 'Aguardando confirmação do pagamento...'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao verificar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gerar código PIX
     */
    private function gerarCodigoPix($chave, $valor, $transacaoId)
    {
        // Implementação básica do código PIX
        $beneficiario = Configuracao::get('pix_beneficiario');
        $cidade = Configuracao::get('pix_cidade') ?? 'São Paulo';
        $cep = Configuracao::get('pix_cep') ?? '00000-000';
        
        // Formato simplificado do PIX
        $pixString = "00020126580014br.gov.bcb.pix0136{$chave}5204000053039865405{$valor}5802BR5913{$beneficiario}6006{$cidade}62070503***6304";
        
        return $pixString;
    }

    /**
     * Gerar QR Code PIX
     */
    private function gerarQRCodePix($chave, $valor, $transacaoId)
    {
        try {
            $pixString = $this->gerarCodigoPix($chave, $valor, $transacaoId);
            $qrCode = \Endroid\QrCode\QrCode::create($pixString)
                ->setSize(300)
                ->setMargin(10);
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);
            return base64_encode($result->getString());
        } catch (\Exception $e) {
            Log::error('Erro ao gerar QR Code PIX: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calcular dias restantes de uma campanha
     */
    private function calcularDiasRestantes($campanha)
    {
        if (!$campanha->data_fim) {
            return null;
        }

        $dias = now()->diffInDays($campanha->data_fim, false);
        return $dias > 0 ? $dias : 0;
    }

    /**
     * Notificações do membro
     */
    public function notifications()
    {
        $user = Auth::user();
        $notificacoes = Notificacao::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.notifications.index', compact('notificacoes'));
    }

    /**
     * Mostrar notificação
     */
    public function showNotification(Notificacao $notificacao)
    {
        $user = Auth::user();
        
        if ($notificacao->user_id !== $user->id) {
            abort(403);
        }

        if (!$notificacao->lida) {
            $notificacao->update(['lida' => true]);
        }

        return view('member.notifications.show', compact('notificacao'));
    }

    /**
     * Marcar como lida
     */
    public function markAsRead(Request $request, Notificacao $notificacao)
    {
        $user = Auth::user();
        
        if ($notificacao->user_id !== $user->id) {
            abort(403);
        }

        $notificacao->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Excluir notificação
     */
    public function deleteNotification(Notificacao $notificacao)
    {
        $user = Auth::user();
        
        if ($notificacao->user_id !== $user->id) {
            abort(403);
        }

        $notificacao->delete();

        return redirect()->route('member.notifications.index')
            ->with('success', 'Notificação excluída com sucesso!');
    }

    /**
     * Limpar notificações
     */
    public function clearNotifications()
    {
        $user = Auth::user();
        
        Notificacao::where('user_id', $user->id)
            ->where('lida', true)
            ->delete();

        return redirect()->route('member.notifications.index')
            ->with('success', 'Notificações lidas foram limpas!');
    }

    /**
     * Mostrar detalhes da transação
     */
    public function showTransactionDetails($transacaoId)
    {
        $transacao = Transacao::with(['membro.user', 'campanha'])->findOrFail($transacaoId);
        
        // Verificar se o usuário tem permissão
        $user = Auth::user();
        $membro = $user->membro;
        
        if (!$membro || $transacao->membro_id !== $membro->id) {
            return view('member.donations.acesso-negado', [
                'mensagem' => 'Você não tem permissão para visualizar os detalhes desta transação. Apenas o usuário que emitiu o comprovante pode visualizar seus detalhes.',
                'transacao_id' => $transacaoId
            ]);
        }

        // Gerar dados PIX se for transação pendente
        $pixData = null;
        if ($transacao->status === 'pendente' && isset($transacao->dados_extras['gateway']) && $transacao->dados_extras['gateway'] === 'pix') {
            $pixChave = Configuracao::get('pix_chave');
            $pixBeneficiario = Configuracao::get('pix_beneficiario');
            
            if (!empty($pixChave) && !empty($pixBeneficiario)) {
                $codigoPix = $this->gerarCodigoPix($pixChave, $transacao->valor, $transacao->id);
                $qrCode = $this->gerarQRCodePix($pixChave, $transacao->valor, $transacao->id);

                $pixData = [
                    'chave' => $pixChave,
                    'beneficiario' => $pixBeneficiario,
                    'valor' => number_format($transacao->valor, 2, ',', '.'),
                    'codigo_pix' => $codigoPix,
                    'qr_code' => $qrCode,
                    'transacao_id' => $transacao->id
                ];
            }
        }

        return view('member.donations.transaction-details', compact('transacao', 'pixData'));
    }

    /**
     * Download do comprovante de doação
     */
    public function downloadComprovante($transacaoId)
    {
        $transacao = Transacao::with(['membro.user', 'campanha'])
            ->where('id', $transacaoId)
            ->whereHas('membro', function ($query) {
                $query->where('email', auth()->user()->email);
            })
            ->first();

        if (!$transacao) {
            return redirect()->back()->with('error', 'Comprovante não encontrado.');
        }

        $pdf = \PDF::loadView('pdf.comprovante-doacao', compact('transacao'));
        
        return $pdf->download("comprovante-{$transacao->id}.pdf");
    }

    /**
     * Verificar comprovante na área de membro
     */
    public function verificarComprovante(Request $request)
    {
        $codigo = $request->get('codigo');
        $transacao = null;
        $valido = false;
        $mensagem = '';
        $minhasTransacoes = collect();

        if ($codigo) {
            // Buscar apenas transações do membro logado
            $membro = Membro::where('email', auth()->user()->email)->first();
            
            if ($membro) {
                $transacoes = Transacao::with(['membro.user', 'campanha'])
                    ->where('membro_id', $membro->id)
                    ->get();
                
                foreach ($transacoes as $t) {
                    $codigoVerificacao = strtoupper(substr(md5($t->id . $t->created_at), 0, 8));
                    if ($codigoVerificacao === strtoupper($codigo)) {
                        $transacao = $t;
                        $valido = true;
                        break;
                    }
                }

                if (!$transacao) {
                    $mensagem = 'Código de verificação inválido ou comprovante não encontrado.';
                }

                // Preparar lista das transações do membro para sugestões
                $minhasTransacoes = $transacoes->take(5)->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'valor' => $t->valor,
                        'data' => $t->created_at->format('d/m/Y'),
                        'status' => $t->status,
                        'codigo' => strtoupper(substr(md5($t->id . $t->created_at), 0, 8))
                    ];
                });

                // Log da validação
                \Log::info('Validação de comprovante na área de membro', [
                    'codigo' => $codigo,
                    'encontrado' => $valido,
                    'transacao_id' => $transacao ? $transacao->id : null,
                    'membro_id' => $membro->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
        }

        return view('member.donations.verificar-comprovante', compact('transacao', 'valido', 'mensagem', 'codigo', 'minhasTransacoes'));
    }
} 