<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PedidoOracao;
use App\Services\IntercessorService;
use Illuminate\Support\Facades\Auth;

class PedidoOracaoController extends Controller
{
    protected $intercessorService;

    public function __construct(IntercessorService $intercessorService)
    {
        $this->intercessorService = $intercessorService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->membro) {
            return redirect()->route('member.dashboard')->with('error', 'Perfil de membro não encontrado.');
        }

        $pedidos = $this->intercessorService->getMemberPedidos($user->membro, $request);
        $pedidosCompartilhados = $this->intercessorService->getSharedPedidos($user->membro);

        // As estatísticas podem ser movidas para o service também no futuro.
        $estatisticas = [
            'total' => $user->membro->pedidosOracao()->count(),
            'pendentes' => $user->membro->pedidosOracao()->where('status', 'pendente')->count(),
            'em_oracao' => $user->membro->pedidosOracao()->where('status', 'em_oracao')->count(),
            'atendidos' => $user->membro->pedidosOracao()->where('status', 'atendido')->count(),
        ];

        return view('member.pedidos-oracao.index', compact('pedidos', 'pedidosCompartilhados', 'estatisticas'));
    }

    public function create()
    {
        return view('member.pedidos-oracao.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $this->intercessorService->createPedido($data, Auth::user());
        return redirect()->route('member.pedidos-oracao.index')->with('success', 'Pedido de oração criado com sucesso!');
    }

    public function show(PedidoOracao $pedido)
    {
        $pedido->load('intercessores.user');
        $isProprietario = $pedido->membro_id === Auth::user()->membro->id;
        return view('member.pedidos-oracao.show', compact('pedido', 'isProprietario'));
    }

    public function edit(PedidoOracao $pedido)
    {
        if ($pedido->membro_id !== Auth::user()->membro->id || $pedido->status !== 'pendente') {
            abort(403, 'Acesso negado.');
        }
        return view('member.pedidos-oracao.edit', compact('pedido'));
    }

    public function update(Request $request, PedidoOracao $pedido)
    {
        try {
            $data = $request->validate($this->rules());
            $this->intercessorService->updatePedido($pedido, $data, Auth::user());
            return redirect()->route('member.pedidos-oracao.show', $pedido)->with('success', 'Pedido de oração atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(PedidoOracao $pedido)
    {
        try {
            $this->intercessorService->deletePedido($pedido, Auth::user());
            return redirect()->route('member.pedidos-oracao.index')->with('success', 'Pedido de oração excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function marcarAtendido(PedidoOracao $pedido)
    {
        try {
            $this->intercessorService->marcarAtendido($pedido, Auth::user());
            return redirect()->route('member.pedidos-oracao.show', $pedido)->with('success', 'Pedido marcado como atendido!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function participarIntercessao(Request $request, PedidoOracao $pedido)
    {
        $data = $request->validate([
            'tipo_oracao' => 'required|in:individual,grupo,igreja',
            'tempo_oracao' => 'nullable|integer|min:1',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->intercessorService->participarIntercessao($pedido, $data, Auth::user());
            return redirect()->back()->with('success', 'Sua intercessão foi registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'categoria' => 'required|in:saude,familia,trabalho,espiritual,outros',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'anonimo' => 'boolean',
            'pode_compartilhar' => 'boolean'
        ];
    }
}