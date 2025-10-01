<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PedidoOracao;
use App\Models\Intercessao;
use App\Models\User;
use App\Services\NotificacaoService;

class PedidoOracaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar pedidos do membro
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                           ->with('error', 'Membro não encontrado.');
        }

        // Query para pedidos próprios
        $query = PedidoOracao::where('membro_id', $membro->id);

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->prioridade) {
            $query->where('prioridade', $request->prioridade);
        }

        $pedidos = $query->orderBy('created_at', 'desc')
                        ->paginate(10);

        // Buscar pedidos compartilhados de outros membros
        $pedidosCompartilhados = PedidoOracao::where('membro_id', '!=', $membro->id)
            ->where('pode_compartilhar', true)
            ->where('anonimo', false)
            ->with('membro')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estatísticas
        $estatisticas = [
            'total' => PedidoOracao::where('membro_id', $membro->id)->count(),
            'pendentes' => PedidoOracao::where('membro_id', $membro->id)->pendentes()->count(),
            'em_oracao' => PedidoOracao::where('membro_id', $membro->id)->emOracao()->count(),
            'atendidos' => PedidoOracao::where('membro_id', $membro->id)->atendidos()->count(),
        ];

        return view('member.pedidos-oracao.index', compact('pedidos', 'pedidosCompartilhados', 'estatisticas'));
    }

    /**
     * Formulário para criar pedido
     */
    public function create()
    {
        return view('member.pedidos-oracao.create');
    }

    /**
     * Salvar novo pedido
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                           ->with('error', 'Membro não encontrado.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'categoria' => 'required|in:saude,familia,trabalho,espiritual,outros',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'anonimo' => 'boolean',
            'pode_compartilhar' => 'boolean'
        ]);

        $pedido = PedidoOracao::create([
            'membro_id' => $membro->id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'categoria' => $request->categoria,
            'prioridade' => $request->prioridade,
            'status' => 'pendente',
            'data_pedido' => now(),
            'anonimo' => $request->boolean('anonimo'),
            'pode_compartilhar' => $request->boolean('pode_compartilhar')
        ]);

        // Notificar líderes e intercessores
        $this->notificarIntercessores($pedido);

        return redirect()->route('member.pedidos-oracao.index')
                        ->with('success', 'Pedido de oração criado com sucesso!');
    }

    /**
     * Visualizar pedido
     */
    public function show(PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                           ->with('error', 'Membro não encontrado.');
        }

        // Verificar se o pedido pertence ao membro OU se é compartilhado
        $podeVisualizar = false;
        $isProprietario = false;

        if ($pedido->membro_id === $membro->id) {
            $podeVisualizar = true;
            $isProprietario = true;
        } elseif ($pedido->pode_compartilhar && !$pedido->anonimo) {
            $podeVisualizar = true;
            $isProprietario = false;
        }

        if (!$podeVisualizar) {
            abort(403, 'Você não tem permissão para visualizar este pedido.');
        }

        $intercessores = $pedido->intercessores()->with('user')->get();

        return view('member.pedidos-oracao.show', compact('pedido', 'intercessores', 'isProprietario'));
    }

    /**
     * Participar da intercessão
     */
    public function participarIntercessao(Request $request, PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                           ->with('error', 'Membro não encontrado.');
        }

        // Verificar se pode participar (pedido compartilhado ou é o proprietário)
        $podeParticipar = false;
        
        if ($pedido->membro_id === $membro->id) {
            $podeParticipar = true;
        } elseif ($pedido->pode_compartilhar && !$pedido->anonimo) {
            $podeParticipar = true;
        }

        if (!$podeParticipar) {
            return redirect()->back()
                           ->with('error', 'Você não pode participar da intercessão deste pedido.');
        }

        $request->validate([
            'tipo_oracao' => 'required|in:individual,grupo,igreja',
            'tempo_oracao' => 'nullable|integer|min:1|max:480',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        // Verificar se já participou
        $jaParticipou = $pedido->intercessores()
            ->where('user_id', $user->id)
            ->exists();

        if ($jaParticipou) {
            return redirect()->back()
                           ->with('error', 'Você já participou da intercessão deste pedido.');
        }

        // Criar intercessão
        $intercessao = new \App\Models\Intercessao();
        $intercessao->pedido_id = $pedido->id;
        $intercessao->user_id = $user->id;
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

        return redirect()->back()
                        ->with('success', 'Sua intercessão foi registrada com sucesso!');
    }

    /**
     * Formulário para editar pedido
     */
    public function edit(PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        // Verificar se o pedido pertence ao membro
        if ($pedido->membro_id !== $membro->id) {
            abort(403);
        }

        // Só pode editar se estiver pendente
        if ($pedido->status !== 'pendente') {
            return redirect()->route('member.pedidos-oracao.show', $pedido)
                           ->with('error', 'Não é possível editar este pedido.');
        }

        return view('member.pedidos-oracao.edit', compact('pedido'));
    }

    /**
     * Atualizar pedido
     */
    public function update(Request $request, PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        // Verificar se o pedido pertence ao membro
        if ($pedido->membro_id !== $membro->id) {
            abort(403);
        }

        // Só pode editar se estiver pendente
        if ($pedido->status !== 'pendente') {
            return redirect()->route('member.pedidos-oracao.show', $pedido)
                           ->with('error', 'Não é possível editar este pedido.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'categoria' => 'required|in:saude,familia,trabalho,espiritual,outros',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'anonimo' => 'boolean',
            'pode_compartilhar' => 'boolean'
        ]);

        $pedido->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'categoria' => $request->categoria,
            'prioridade' => $request->prioridade,
            'anonimo' => $request->boolean('anonimo'),
            'pode_compartilhar' => $request->boolean('pode_compartilhar')
        ]);

        return redirect()->route('member.pedidos-oracao.show', $pedido)
                        ->with('success', 'Pedido de oração atualizado com sucesso!');
    }

    /**
     * Excluir pedido
     */
    public function destroy(PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        // Verificar se o pedido pertence ao membro
        if ($pedido->membro_id !== $membro->id) {
            abort(403);
        }

        // Só pode excluir se estiver pendente
        if ($pedido->status !== 'pendente') {
            return redirect()->route('member.pedidos-oracao.show', $pedido)
                           ->with('error', 'Não é possível excluir este pedido.');
        }

        $pedido->delete();

        return redirect()->route('member.pedidos-oracao.index')
                        ->with('success', 'Pedido de oração excluído com sucesso!');
    }

    /**
     * Marcar como atendido
     */
    public function marcarAtendido(PedidoOracao $pedido)
    {
        $user = auth()->user();
        $membro = $user->membro;

        // Verificar se o pedido pertence ao membro
        if ($pedido->membro_id !== $membro->id) {
            abort(403);
        }

        $pedido->update([
            'status' => 'atendido',
            'data_atendimento' => now()
        ]);

        return redirect()->route('member.pedidos-oracao.show', $pedido)
                        ->with('success', 'Pedido marcado como atendido!');
    }

    /**
     * Notificar intercessores sobre novo pedido
     */
    private function notificarIntercessores(PedidoOracao $pedido)
    {
        // Buscar usuários com permissão de intercessor
        $intercessores = User::whereHas('roles', function($query) {
            $query->where('name', 'intercessor');
        })->get();

        foreach ($intercessores as $intercessor) {
            // Criar notificação
            NotificacaoService::criarNotificacao(
                $intercessor->id,
                'Novo Pedido de Oração',
                "Novo pedido de oração: {$pedido->titulo}",
                'pedido_oracao',
                $pedido->id
            );
        }
    }
} 