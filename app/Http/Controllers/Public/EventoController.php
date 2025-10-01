<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Evento, EventoInscricao, EventoPagamento};
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Listar eventos públicos
     */
    public function index(Request $request)
    {
        $query = Evento::with(['organizador', 'ministerio'])
                       ->where('ativo', true)
                       ->where('status', 'ativo')
                       ->whereIn('tipo_publico', ['publico', 'ambos']);

        // Filtros
        if ($request->filled('tipo_evento')) {
            $query->where('tipo_evento', $request->tipo_evento);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('local', 'like', "%{$search}%");
            });
        }

        // Ordenar por destaque e data
        $eventos = $query->orderBy('destaque', 'desc')
                        ->orderBy('data_inicio', 'asc')
                        ->paginate(12);

        $eventosDestaque = Evento::with(['organizador', 'ministerio'])
                                 ->where('ativo', true)
                                 ->where('status', 'ativo')
                                 ->where('destaque', true)
                                 ->whereIn('tipo_publico', ['publico', 'ambos'])
                                 ->orderBy('data_inicio', 'asc')
                                 ->limit(3)
                                 ->get();

        return view('public.eventos.index', compact('eventos', 'eventosDestaque'));
    }

    /**
     * Visualizar evento público
     */
    public function show(Evento $evento)
    {
        // Verificar se o evento é público
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se o evento está ativo
        if (!$evento->ativo || $evento->status !== 'ativo') {
            abort(404, 'Evento não encontrado.');
        }

        $evento->load(['organizador', 'ministerio']);

        // Verificar se o usuário está inscrito (se logado)
        $inscricao = null;
        $user = Auth::user();
        if ($user) {
            $inscricao = $evento->inscricoes()->where('user_id', $user->id)->first();
        }

        // Verificar se pode se inscrever
        $podeInscricao = $evento->podeInscricaoUsuario($user);

        return view('public.eventos.show', compact('evento', 'inscricao', 'podeInscricao'));
    }

    /**
     * Formulário de inscrição pública
     */
    public function inscrever(Evento $evento)
    {
        // Verificar se o evento é público
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se o evento está ativo
        if (!$evento->ativo || $evento->status !== 'ativo') {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se pode se inscrever
        if (!$evento->podeInscricao()) {
            return back()->with('error', 'Não é possível se inscrever neste evento.');
        }

        return view('public.eventos.inscrever', compact('evento'));
    }

    /**
     * Processar inscrição pública
     */
    public function processarInscricao(Request $request, Evento $evento)
    {
        // Verificar se o evento é público
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se o evento está ativo
        if (!$evento->ativo || $evento->status !== 'ativo') {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se pode se inscrever
        if (!$evento->podeInscricao()) {
            return back()->with('error', 'Não é possível se inscrever neste evento.');
        }

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'data_nascimento' => 'nullable|date',
            'endereco' => 'nullable|string|max:500',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'observacoes' => 'nullable|string',
        ]);

        // Verificar se o email já está inscrito
        if ($evento->inscricoes()->where('email', $data['email'])->exists()) {
            return back()->with('error', 'Este email já está inscrito neste evento.');
        }

        // Criar inscrição
        $inscricao = $evento->inscricoes()->create($data);

        // Se o evento é gratuito, confirmar automaticamente
        if ($evento->gratuito) {
            $inscricao->confirmar();
            
            // Atualizar vagas disponíveis
            if ($evento->vagas_disponiveis !== null) {
                $evento->decrement('vagas_disponiveis');
            }

            return redirect()->route('public.eventos.inscricao.confirmacao', $inscricao)
                            ->with('success', 'Inscrição realizada com sucesso!');
        }

        // Se é pago, redirecionar para pagamento
        return redirect()->route('public.eventos.pagamento', $inscricao);
    }

    /**
     * Página de pagamento público
     */
    public function pagamento(EventoInscricao $inscricao)
    {
        // Verificar se a inscrição pertence a um evento público
        $evento = $inscricao->evento;
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        // Verificar se já foi pago
        if ($inscricao->valor_pago > 0) {
            return redirect()->route('public.eventos.inscricao.confirmacao', $inscricao)
                            ->with('info', 'Esta inscrição já foi paga.');
        }

        return view('public.eventos.pagamento', compact('inscricao', 'evento'));
    }

    /**
     * Processar pagamento público
     */
    public function processarPagamento(Request $request, EventoInscricao $inscricao)
    {
        // Verificar se a inscrição pertence a um evento público
        $evento = $inscricao->evento;
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        $request->validate([
            'forma_pagamento' => 'required|in:pix,stripe,mercadopago,dinheiro,transferencia',
        ]);

        // Criar pagamento
        $pagamento = EventoPagamento::create([
            'evento_id' => $evento->id,
            'inscricao_id' => $inscricao->id,
            'user_id' => null, // Usuário não logado
            'valor' => $evento->valor_inscricao,
            'forma_pagamento' => $request->forma_pagamento,
            'status' => 'pendente',
        ]);

        // Redirecionar para gateway de pagamento
        switch ($request->forma_pagamento) {
            case 'pix':
                return redirect()->route('public.eventos.pagamento.pix', $pagamento);
            case 'stripe':
                return redirect()->route('public.eventos.pagamento.stripe', $pagamento);
            case 'mercadopago':
                return redirect()->route('public.eventos.pagamento.mercadopago', $pagamento);
            default:
                return redirect()->route('public.eventos.pagamento.manual', $pagamento);
        }
    }

    /**
     * Confirmação de inscrição pública
     */
    public function confirmacao(EventoInscricao $inscricao)
    {
        // Verificar se a inscrição pertence a um evento público
        $evento = $inscricao->evento;
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        return view('public.eventos.confirmacao', compact('inscricao', 'evento'));
    }

    /**
     * Verificar status de inscrição
     */
    public function verificarInscricao(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'evento_id' => 'required|exists:eventos,id'
        ]);

        $evento = Evento::findOrFail($request->evento_id);
        
        // Verificar se o evento é público
        if (!in_array($evento->tipo_publico, ['publico', 'ambos'])) {
            abort(404, 'Evento não encontrado.');
        }

        $inscricao = $evento->inscricoes()->where('email', $request->email)->first();

        if (!$inscricao) {
            return back()->with('error', 'Nenhuma inscrição encontrada para este email.');
        }

        return view('public.eventos.verificar-inscricao', compact('inscricao', 'evento'));
    }
} 