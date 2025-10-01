<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Transacao, Campanha, Pagamento, Membro};
use App\Exports\{TransacoesExport, CampanhasExport, RelatorioFinanceiroExport};
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:finance.access');
    }

    /**
     * Dashboard da gestão financeira
     */
    public function index()
    {
        $estatisticas = [
            'total_transacoes' => Transacao::count(),
            'total_campanhas' => Campanha::count(),
            'total_recebido' => Transacao::where('status', 'confirmado')->sum('valor'),
            'total_pendente' => Transacao::where('status', 'pendente')->sum('valor'),
            'campanhas_ativas' => Campanha::where('status', 'ativa')->count(),
            'transacoes_hoje' => Transacao::whereDate('created_at', today())->count(),
        ];

        $transacoesRecentes = Transacao::with(['membro', 'campanha'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $campanhasAtivas = Campanha::where('status', 'ativa')
            ->withCount('transacoes')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.finance.dashboard', compact('estatisticas', 'transacoesRecentes', 'campanhasAtivas'));
    }

    /**
     * Lista de transações
     */
    public function transactions(Request $request)
    {
        $query = Transacao::with(['membro', 'campanha', 'pagamentos']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('descricao', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $transacoes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estatísticas para a view
        $receitas = Transacao::where('tipo', 'entrada')->where('status', 'confirmado')->sum('valor');
        $despesas = Transacao::where('tipo', 'saida')->where('status', 'confirmado')->sum('valor');
        
        $estatisticas = [
            'total_transacoes' => Transacao::count(),
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $receitas - $despesas,
            'pendentes' => Transacao::where('status', 'pendente')->sum('valor'),
            'confirmadas' => Transacao::where('status', 'confirmado')->sum('valor'),
            'canceladas' => Transacao::where('status', 'cancelado')->sum('valor'),
            'transacoes_hoje' => Transacao::whereDate('created_at', today())->count(),
            'transacoes_mes' => Transacao::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.finance.transactions.index', compact('transacoes', 'estatisticas'));
    }

    /**
     * Criar transação
     */
    public function createTransaction()
    {
        $membros = Membro::all();
        $campanhas = Campanha::where('status', 'ativa')->get();

        return view('admin.finance.transactions.create', compact('membros', 'campanhas'));
    }

    /**
     * Salvar transação
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:entrada,saida',
            'descricao' => 'required|string|max:255',
            'membro_id' => 'nullable|exists:membros,id',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'data_transacao' => 'required|date', // Corrigido: era 'data'
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        Transacao::create($request->all());

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transação criada com sucesso!');
    }

    /**
     * Visualizar transação
     */
    public function showTransaction(Transacao $transacao)
    {
        $transacao->load(['membro', 'campanha', 'pagamentos']);
        
        return view('admin.finance.transactions.show', compact('transacao'));
    }

    /**
     * Editar transação
     */
    public function editTransaction(Transacao $transacao)
    {
        $membros = Membro::all();
        $campanhas = Campanha::all();

        return view('admin.finance.transactions.edit', compact('transacao', 'membros', 'campanhas'));
    }

    /**
     * Atualizar transação
     */
    public function updateTransaction(Request $request, Transacao $transacao)
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
            'tipo' => 'required|in:entrada,saida',
            'descricao' => 'required|string|max:255',
            'membro_id' => 'nullable|exists:membros,id',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'data_transacao' => 'required|date', // Corrigido: era 'data'
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        // Preparar dados para atualização
        $dados = $request->all();
        $dados['data'] = $dados['data_transacao']; // Mapear data_transacao para data
        unset($dados['data_transacao']); // Remover campo extra
        
        // Processar campos opcionais
        if (empty($dados['membro_id'])) {
            $dados['membro_id'] = null;
        }
        if (empty($dados['campanha_id'])) {
            $dados['campanha_id'] = null;
        }
        if (empty($dados['categoria'])) {
            $dados['categoria'] = null;
        }
        if (empty($dados['metodo_pagamento'])) {
            $dados['metodo_pagamento'] = null;
        }
        if (empty($dados['observacoes'])) {
            $dados['observacoes'] = null;
        }

        $transacao->update($dados);

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transação atualizada com sucesso!');
    }

    /**
     * Lista de campanhas
     */
    public function campaigns(Request $request)
    {
        $query = Campanha::withCount('transacoes');

        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $campanhas = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estatísticas para a view
        $campanhasAtivas = Campanha::where('status', 'ativa')->count();
        $campanhasPausadas = Campanha::where('status', 'pausada')->count();
        $campanhasFinalizadas = Campanha::where('status', 'finalizada')->count();

        return view('admin.finance.campaigns.index', compact('campanhas', 'campanhasAtivas', 'campanhasPausadas', 'campanhasFinalizadas'));
    }

    /**
     * Criar campanha
     */
    public function createCampaign()
    {
        return view('admin.finance.campaigns.create');
    }

    /**
     * Salvar campanha
     */
    public function storeCampaign(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'meta_valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:construcao,missao,social,equipamentos,outros',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'status' => 'required|in:ativa,pausada,finalizada',
            'ativo' => 'boolean',
        ]);

        $dados = $request->all();
        $dados['ativo'] = $request->has('ativo');

        Campanha::create($dados);

        return redirect()->route('admin.finance.campaigns.index')
            ->with('success', 'Campanha criada com sucesso!');
    }

    /**
     * Editar campanha
     */
    public function editCampaign(Campanha $campanha)
    {
        return view('admin.finance.campaigns.edit', compact('campanha'));
    }

    /**
     * Atualizar campanha
     */
    public function updateCampaign(Request $request, Campanha $campanha)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'meta_valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:construcao,missao,social,equipamentos,outros',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'status' => 'required|in:ativa,pausada,finalizada',
            'ativo' => 'boolean',
        ]);

        $dados = $request->all();
        $dados['ativo'] = $request->has('ativo');

        $campanha->update($dados);

        return redirect()->route('admin.finance.campaigns.index')
            ->with('success', 'Campanha atualizada com sucesso!');
    }

    /**
     * Ver campanha
     */
    public function showCampaign(Campanha $campanha)
    {
        return view('admin.finance.campaigns.show', compact('campanha'));
    }

    /**
     * Relatórios financeiros
     */
    public function reports(Request $request)
    {
        $periodo = $request->get('periodo', 'mes');
        $ano = $request->get('ano', now()->year);
        $mes = $request->get('mes', now()->month);

        $dados = $this->getRelatorioData($periodo, $ano, $mes);

        return view('admin.finance.reports.index', compact('dados', 'periodo', 'ano', 'mes'));
    }



    /**
     * Configurações financeiras
     */
    public function settings()
    {
        $configuracoes = [
            'stripe_public_key' => config('services.stripe.public_key'),
            'stripe_secret_key' => config('services.stripe.secret_key'),
            'mercadopago_public_key' => config('services.mercadopago.public_key'),
            'mercadopago_access_token' => config('services.mercadopago.access_token'),
        ];

        return view('admin.finance.settings.index', compact('configuracoes'));
    }

    /**
     * Salvar configurações
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'mercadopago_public_key' => 'nullable|string',
            'mercadopago_access_token' => 'nullable|string',
        ]);

        // Atualizar configurações no banco ou arquivo .env
        foreach ($request->all() as $key => $value) {
            if ($value) {
                \App\Models\Configuracao::set($key, $value);
            }
        }

        return redirect()->route('admin.finance.settings.index')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Excluir transação
     */
    public function deleteTransaction(Transacao $transacao)
    {
        // Log para debug
        Log::info('Tentativa de exclusão de transação', [
            'transacao_id' => $transacao->id,
            'user_id' => auth()->id(),
            'user_permissions' => auth()->user()->permissions->pluck('name')->toArray(),
            'request_method' => request()->method(),
            'request_url' => request()->url()
        ]);

        // Verificar se a transação pode ser excluída (apenas pendentes)
        if ($transacao->status !== 'pendente') {
            return redirect()->route('admin.finance.transactions.index')
                ->with('error', 'Apenas transações pendentes podem ser excluídas.');
        }

        $transacao->delete();
        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Excluir transação via GET (apenas para super admin e tesoureiro)
     */
    public function deleteTransactionGet(Transacao $transacao)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user()->can('transactions.delete')) {
            abort(403, 'Você não tem permissão para excluir transações.');
        }

        // Log para debug
        Log::info('Tentativa de exclusão de transação via GET', [
            'transacao_id' => $transacao->id,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_roles' => auth()->user()->roles->pluck('name')->toArray(),
            'request_method' => request()->method(),
            'request_url' => request()->url()
        ]);

        // Verificar se a transação pode ser excluída (apenas pendentes)
        if ($transacao->status !== 'pendente') {
            return redirect()->route('admin.finance.transactions.index')
                ->with('error', 'Apenas transações pendentes podem ser excluídas.');
        }

        $transacao->delete();
        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Excluir transações em lote
     */
    public function bulkDeleteTransactions(Request $request)
    {
        // Verificar se o usuário é Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Apenas Super Administradores podem excluir transações em lote.');
        }

        // Validar dados recebidos
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:transacoes,id'
        ]);

        $ids = $request->input('ids');
        
        // Log para debug
        Log::info('Tentativa de exclusão em lote de transações', [
            'transacao_ids' => $ids,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_roles' => auth()->user()->roles->pluck('name')->toArray(),
            'request_method' => request()->method(),
            'request_url' => request()->url()
        ]);

        // Buscar transações
        $transacoes = Transacao::whereIn('id', $ids)->get();
        
        // Verificar se todas são pendentes
        $naoPendentes = $transacoes->where('status', '!=', 'pendente');
        if ($naoPendentes->count() > 0) {
            return redirect()->route('admin.finance.transactions.index')
                ->with('error', 'Apenas transações pendentes podem ser excluídas.');
        }

        // Excluir transações
        $deletedCount = $transacoes->count();
        $transacoes->each(function($transacao) {
            $transacao->delete();
        });

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', "{$deletedCount} transação(ões) excluída(s) com sucesso!");
    }

    /**
     * Exportar comprovante de transação específica
     */
    public function exportTransactionComprovante(Transacao $transacao)
    {
        // Carregar relacionamentos necessários
        $transacao->load(['membro.user', 'campanha']);
        
        // Gerar PDF do comprovante
        $pdf = \PDF::loadView('pdf.comprovante-doacao', compact('transacao'));
        
        return $pdf->download('comprovante_transacao_' . $transacao->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Exportar transações
     */
    public function exportTransactions(Request $request)
    {
        $filtros = $request->all();
        $formato = $request->get('formato', 'excel');
        
        // Aplicar filtros na query
        $query = Transacao::with(['membro', 'campanha', 'pagamentos']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('descricao', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $transacoes = $query->orderBy('created_at', 'desc')->get();
        
        // Calcular estatísticas
        $receitas = $transacoes->where('tipo', 'entrada')->where('status', 'confirmado')->sum('valor');
        $despesas = $transacoes->where('tipo', 'saida')->where('status', 'confirmado')->sum('valor');
        $saldo = $receitas - $despesas;
        
        $estatisticas = [
            'total_transacoes' => $transacoes->count(),
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $saldo,
            'pendentes' => $transacoes->where('status', 'pendente')->sum('valor'),
            'confirmadas' => $transacoes->where('status', 'confirmado')->sum('valor'),
            'canceladas' => $transacoes->where('status', 'cancelado')->sum('valor'),
        ];
        
        if ($formato === 'pdf') {
            $pdfService = new PdfService();
            $pdf = $pdfService->gerarRelatorioTransacoes($transacoes, $estatisticas, $filtros);
            return $pdf->download('relatorio_transacoes_' . now()->format('Y-m-d_H-i-s') . '.pdf');
        }
        
        return Excel::download(
            new TransacoesExport($filtros),
            'relatorio_transacoes_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * Excluir campanha
     */
    public function deleteCampaign(Campanha $campanha)
    {
        $campanha->delete();
        return redirect()->route('admin.finance.campaigns.index')
            ->with('success', 'Campanha excluída com sucesso!');
    }

    /**
     * Transações da campanha
     */
    public function campaignTransactions(Campanha $campanha)
    {
        $transacoes = $campanha->transacoes()
            ->with('membro')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.campaigns.transactions', compact('campanha', 'transacoes'));
    }

    /**
     * Exportar relatório da campanha em PDF
     */
    public function exportCampaignReport(Campanha $campanha)
    {
        try {
            // Carregar relacionamentos necessários
            $campanha->load(['transacoes.membro']);
            
            // Gerar PDF usando o serviço
            $pdfService = new PdfService();
            $pdf = $pdfService->gerarRelatorioCampanha($campanha);
            
            // Nome do arquivo
            $fileName = 'relatorio_campanha_' . Str::slug($campanha->titulo) . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

    /**
     * Criar campanhas em lote
     */
    public function createBatchCampaigns(Request $request)
    {
        $request->validate([
            'campanhas' => 'required|array',
            'campanhas.*.nome' => 'required|string|max:255',
            'campanhas.*.meta' => 'required|numeric|min:0.01',
            'campanhas.*.data_inicio' => 'required|date',
            'campanhas.*.data_fim' => 'required|date|after:campanhas.*.data_inicio',
        ]);

        foreach ($request->campanhas as $campanhaData) {
            Campanha::create($campanhaData);
        }

        return redirect()->route('admin.finance.campaigns.index')
            ->with('success', 'Campanhas criadas em lote com sucesso!');
    }

    /**
     * Exportar relatórios
     */
    public function exportReports(Request $request)
    {
        $tipo = $request->get('tipo', 'transacoes');
        $periodo = $request->get('periodo', 'mes');
        $ano = $request->get('ano', now()->year);
        $mes = $request->get('mes', now()->month);
        $formato = $request->get('formato', 'excel');

        if ($formato === 'pdf') {
            $dados = $this->getRelatorioData($periodo, $ano, $mes);
            $pdfService = new PdfService();
            return $pdfService->gerarRelatorioMensal($ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT), $dados['transacoes'], $dados['resumo'])->download("relatorio_financeiro_{$periodo}_{$ano}_{$mes}.pdf");
        }

        switch ($tipo) {
            case 'transacoes':
                return Excel::download(
                    new TransacoesExport($request->all()),
                    "relatorio_transacoes_{$periodo}_{$ano}_{$mes}.xlsx"
                );
            case 'campanhas':
                return Excel::download(
                    new CampanhasExport(),
                    "relatorio_campanhas_{$periodo}_{$ano}_{$mes}.xlsx"
                );
            case 'financeiro':
                $dados = $this->getRelatorioData($periodo, $ano, $mes);
                return Excel::download(
                    new RelatorioFinanceiroExport($dados, $periodo, $ano, $mes),
                    "relatorio_financeiro_{$periodo}_{$ano}_{$mes}.xlsx"
                );
            default:
                return back()->with('error', 'Tipo de relatório inválido');
        }
    }

    /**
     * Obter dados do relatório
     */
    private function getRelatorioData($periodo, $ano, $mes)
    {
        $query = Transacao::with(['membro', 'campanha']);

        switch ($periodo) {
            case 'mes':
                $query->whereYear('created_at', $ano)
                      ->whereMonth('created_at', $mes);
                break;
            case 'ano':
                $query->whereYear('created_at', $ano);
                break;
            case 'personalizado':
                $dataInicio = request('data_inicio');
                $dataFim = request('data_fim');
                if ($dataInicio && $dataFim) {
                    $query->whereBetween('created_at', [$dataInicio, $dataFim]);
                }
                break;
        }

        $transacoes = $query->get();

        $resumo = [
            'total_entrada' => $transacoes->where('tipo', 'entrada')->sum('valor'),
            'total_saida' => $transacoes->where('tipo', 'saida')->sum('valor'),
            'total_confirmado' => $transacoes->where('status', 'confirmado')->sum('valor'),
            'total_pendente' => $transacoes->where('status', 'pendente')->sum('valor'),
            'quantidade_transacoes' => $transacoes->count(),
        ];

        return [
            'transacoes' => $transacoes,
            'resumo' => $resumo,
        ];
    }
} 