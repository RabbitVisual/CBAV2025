<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PedidoOracao;
use App\Services\IntercessorService;
use Illuminate\Support\Facades\Auth;

class IntercessorController extends Controller
{
    protected $intercessorService;

    public function __construct(IntercessorService $intercessorService)
    {
        $this->intercessorService = $intercessorService;
    }

    public function index(Request $request)
    {
        $pedidos = $this->intercessorService->getPedidos($request);
        return view('admin.intercessor.index', compact('pedidos'));
    }

    public function dashboard()
    {
        $data = $this->intercessorService->getDashboardData();
        return view('admin.intercessor.dashboard', $data);
    }

    public function show(PedidoOracao $pedido)
    {
        $pedido->load(['membro.user', 'intercessores.user']);
        return view('admin.intercessor.show', compact('pedido'));
    }

    public function registrarIntercessao(Request $request, PedidoOracao $pedido)
    {
        $data = $request->validate([
            'tipo_oracao' => 'required|in:individual,grupo,igreja',
            'tempo_oracao' => 'nullable|integer|min:1|max:480',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $this->intercessorService->registrarIntercessao($pedido, $data, Auth::user());

        return redirect()->back()->with('success', 'Intercessão registrada com sucesso!');
    }

    public function atualizarStatus(Request $request, PedidoOracao $pedido)
    {
        $data = $request->validate([
            'novo_status' => 'required|in:pendente,em_oracao,atendido',
        ]);

        $this->intercessorService->atualizarStatus($pedido, $data['novo_status']);

        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }

    public function destroy(PedidoOracao $pedido)
    {
        $pedido->delete();
        return redirect()->route('admin.intercessor.index')->with('success', 'Pedido excluído com sucesso!');
    }
}