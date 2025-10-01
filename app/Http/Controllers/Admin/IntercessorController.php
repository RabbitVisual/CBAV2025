<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PedidoOracao;
use App\Models\Intercessao;
use Illuminate\Support\Facades\Auth;

class IntercessorController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoOracao::with(['membro', 'intercessores']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('descricao', 'like', '%' . $request->search . '%')
                  ->orWhereHas('membro', function($q) use ($request) {
                      $q->where('nome', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.intercessor.index', compact('pedidos'));
    }

    public function dashboard()
    {
        $estatisticas = [
            'total_pendentes' => PedidoOracao::where('status', 'pendente')->count(),
            'total_em_oracao' => PedidoOracao::where('status', 'em_oracao')->count(),
            'total_atendidos' => PedidoOracao::where('status', 'atendido')->count(),
            'minhas_intercessoes' => Intercessao::where('user_id', Auth::id())->count(),
        ];

        $pedidosPendentes = PedidoOracao::where('status', 'pendente')
            ->with(['membro', 'intercessores.user'])
            ->orderBy('prioridade', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        $pedidosEmOracao = PedidoOracao::where('status', 'em_oracao')
            ->with(['membro', 'intercessores.user'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.intercessor.dashboard', compact('estatisticas', 'pedidosPendentes', 'pedidosEmOracao'));
    }

    public function show(PedidoOracao $pedido)
    {
        $pedido->load(['membro', 'intercessores.user']);
        
        return view('admin.intercessor.show', compact('pedido'));
    }

    public function registrarIntercessao(Request $request, PedidoOracao $pedido)
    {
        $request->validate([
            'tipo_oracao' => 'required|in:individual,grupo,igreja',
            'tempo_oracao' => 'nullable|integer|min:1|max:480',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $intercessao = new Intercessao();
        $intercessao->pedido_id = $pedido->id;
        $intercessao->user_id = Auth::id();
        $intercessao->tipo_oracao = $request->tipo_oracao;
        $intercessao->tempo_oracao = $request->tempo_oracao ?? 0;
        $intercessao->observacoes = $request->observacoes;
        $intercessao->data_oracao = now();
        $intercessao->save();

        // Atualizar status do pedido para "em oração" se estiver pendente
        if ($pedido->status === 'pendente') {
            $pedido->status = 'em_oracao';
            $pedido->save();
        }

        return redirect()->back()->with('success', 'Intercessão registrada com sucesso!');
    }

    public function atualizarStatus(Request $request, PedidoOracao $pedido)
    {
        $request->validate([
            'novo_status' => 'required|in:pendente,em_oracao,atendido',
        ]);

        $pedido->status = $request->novo_status;
        $pedido->save();

        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }

    public function export(PedidoOracao $pedido)
    {
        // Implementar exportação do pedido
        return response()->json(['message' => 'Exportação implementada']);
    }

    public function destroy(PedidoOracao $pedido)
    {
        $pedido->delete();
        return redirect()->route('admin.intercessor.index')->with('success', 'Pedido excluído com sucesso!');
    }
} 