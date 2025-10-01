<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Transacao, Campanha, Membro};
use App\Services\FinanceService;

class FinanceController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
        $this->middleware('permission:finance.access');
    }

    public function index()
    {
        $data = $this->financeService->getAdminDashboardData();
        return view('admin.finance.dashboard', $data);
    }

    public function transactions(Request $request)
    {
        $data = $this->financeService->getTransactions($request);
        return view('admin.finance.transactions.index', $data);
    }

    public function createTransaction()
    {
        $membros = Membro::ativo()->get();
        $campanhas = Campanha::ativa()->get();
        return view('admin.finance.transactions.create', compact('membros', 'campanhas'));
    }

    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:entrada,saida',
            'descricao' => 'required|string|max:255',
            'membro_id' => 'nullable|exists:membros,id',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'data_transacao' => 'required|date',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        $this->financeService->createTransaction($data);

        return redirect()->route('admin.finance.transactions.index')->with('success', 'Transação criada com sucesso!');
    }

    public function showTransaction(Transacao $transacao)
    {
        $transacao->load(['membro.user', 'campanha', 'pagamentos']);
        return view('admin.finance.transactions.show', compact('transacao'));
    }

    public function editTransaction(Transacao $transacao)
    {
        $membros = Membro::ativo()->get();
        $campanhas = Campanha::all();
        return view('admin.finance.transactions.edit', compact('transacao', 'membros', 'campanhas'));
    }

    public function updateTransaction(Request $request, Transacao $transacao)
    {
        $data = $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:entrada,saida',
            'descricao' => 'required|string|max:255',
            'membro_id' => 'nullable|exists:membros,id',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'data_transacao' => 'required|date',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        $this->financeService->updateTransaction($transacao, $data);

        return redirect()->route('admin.finance.transactions.index')->with('success', 'Transação atualizada com sucesso!');
    }

    public function campaigns(Request $request)
    {
        $campanhas = $this->financeService->getCampaigns($request);
        return view('admin.finance.campaigns.index', compact('campanhas'));
    }

    public function createCampaign()
    {
        return view('admin.finance.campaigns.create');
    }

    public function storeCampaign(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'meta_valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:construcao,missao,social,equipamentos,outros',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'status' => 'required|in:ativa,pausada,finalizada',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->financeService->createCampaign($data);

        return redirect()->route('admin.finance.campaigns.index')->with('success', 'Campanha criada com sucesso!');
    }

    public function showCampaign(Campanha $campanha)
    {
        return view('admin.finance.campaigns.show', compact('campanha'));
    }

    public function editCampaign(Campanha $campanha)
    {
        return view('admin.finance.campaigns.edit', compact('campanha'));
    }

    public function updateCampaign(Request $request, Campanha $campanha)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'meta_valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:construcao,missao,social,equipamentos,outros',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'status' => 'required|in:ativa,pausada,finalizada',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->financeService->updateCampaign($campanha, $data);

        return redirect()->route('admin.finance.campaigns.index')->with('success', 'Campanha atualizada com sucesso!');
    }
}