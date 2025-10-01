<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Evento, EventoInscricao, EventoPagamento};
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Listar eventos disponíveis
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Evento::with(['organizador', 'ministerio'])
                       ->where('ativo', true)
                       ->where('status', 'ativo');

        // Filtrar por tipo de público
        if ($user) {
            // Usuário logado pode ver eventos para membros e ambos
            $query->whereIn('tipo_publico', ['membros', 'ambos']);
        } else {
            // Usuário não logado só pode ver eventos públicos
            $query->whereIn('tipo_publico', ['publico', 'ambos']);
        }

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
                                 ->whereIn('tipo_publico', $user ? ['membros', 'ambos'] : ['publico', 'ambos'])
                                 ->orderBy('data_inicio', 'asc')
                                 ->limit(3)
                                 ->get();

        return view('member.eventos.index', compact('eventos', 'eventosDestaque'));
    }

    /**
     * Visualizar evento
     */
    public function show(Evento $evento)
    {
        $user = Auth::user();

        // Verificar se o usuário pode visualizar o evento
        if (!$evento->podeVisualizar($user)) {
            abort(403, 'Você não tem permissão para visualizar este evento.');
        }

        $evento->load(['organizador', 'ministerio']);

        // Verificar se o usuário está inscrito
        $inscricaoUsuario = null;
        if ($user) {
            $inscricaoUsuario = $evento->inscricoes()->where('user_id', $user->id)->first();
        }

        // Verificar se pode se inscrever
        $podeInscricao = $evento->podeInscricaoUsuario($user);

        return view('member.eventos.show', compact('evento', 'inscricaoUsuario', 'podeInscricao'));
    }

    /**
     * Formulário de inscrição
     */
    public function inscrever(Evento $evento)
    {
        $user = Auth::user();

        // Verificar se pode se inscrever
        if (!$evento->podeInscricaoUsuario($user)) {
            return back()->with('error', 'Não é possível se inscrever neste evento.');
        }

        // Verificar se já está inscrito
        if ($user && $evento->inscricoes()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Você já está inscrito neste evento.');
        }

        // Buscar dados completos do membro
        $dadosMembro = null;
        if ($user) {
            // Buscar dados do membro na tabela membros
            $dadosMembro = \App\Models\Membro::where('email', $user->email)->first();
            
            // Se não encontrou pelo email, tentar pelo user_id
            if (!$dadosMembro && $user->membro) {
                $dadosMembro = $user->membro;
            }
        }

        return view('member.eventos.inscrever', compact('evento', 'dadosMembro'));
    }

    /**
     * Processar inscrição
     */
    public function processarInscricao(Request $request, Evento $evento)
    {
        $user = Auth::user();

        // Verificar se pode se inscrever
        if (!$evento->podeInscricaoUsuario($user)) {
            return back()->with('error', 'Não é possível se inscrever neste evento.');
        }

        // Verificar se já está inscrito
        if ($user && $evento->inscricoes()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Você já está inscrito neste evento.');
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

        // Se o usuário está logado, usar dados do perfil e membro
        if ($user) {
            $data['user_id'] = $user->id;
            $data['nome'] = $user->name;
            $data['email'] = $user->email;
            
            // Buscar dados completos do membro
            $dadosMembro = \App\Models\Membro::where('email', $user->email)->first();
            if (!$dadosMembro && $user->membro) {
                $dadosMembro = $user->membro;
            }
            
            // Preencher com dados do membro se disponível
            if ($dadosMembro) {
                $data['telefone'] = $dadosMembro->telefone ?? $user->telefone ?? $data['telefone'];
                $data['cpf'] = $dadosMembro->cpf ?? $data['cpf'];
                $data['data_nascimento'] = $dadosMembro->data_nascimento ?? $data['data_nascimento'];
                $data['endereco'] = $dadosMembro->endereco ?? $data['endereco'];
                $data['cidade'] = $dadosMembro->cidade ?? $data['cidade'];
                $data['estado'] = $dadosMembro->estado ?? $data['estado'];
                $data['cep'] = $dadosMembro->cep ?? $data['cep'];
                $data['sexo'] = $dadosMembro->sexo ?? $data['sexo'] ?? null;
            } else {
                // Usar dados do usuário se membro não encontrado
                $data['telefone'] = $user->telefone ?? $data['telefone'];
            }
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

            return redirect()->route('member.eventos.inscricao.confirmacao', $inscricao)
                            ->with('success', 'Inscrição realizada com sucesso!');
        }

        // Se é pago, redirecionar para pagamento
        return redirect()->route('member.eventos.pagamento', $inscricao);
    }

    /**
     * Página de pagamento
     */
    public function pagamento(EventoInscricao $inscricao)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($user && $inscricao->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para acessar esta inscrição.');
        }

        // Verificar se já foi pago
        if ($inscricao->valor_pago > 0) {
            return redirect()->route('member.eventos.inscricao.confirmacao', $inscricao)
                            ->with('info', 'Esta inscrição já foi paga.');
        }

        $evento = $inscricao->evento;

        return view('member.eventos.pagamento', compact('inscricao', 'evento'));
    }

    /**
     * Processar pagamento
     */
    public function processarPagamento(Request $request, EventoInscricao $inscricao)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($user && $inscricao->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para acessar esta inscrição.');
        }

        $request->validate([
            'forma_pagamento' => 'required|in:pix,stripe,mercadopago,dinheiro,transferencia',
        ]);

        $evento = $inscricao->evento;

        // Criar pagamento
        $pagamento = EventoPagamento::create([
            'evento_id' => $evento->id,
            'inscricao_id' => $inscricao->id,
            'user_id' => $user ? $user->id : null,
            'valor' => $evento->valor_inscricao,
            'forma_pagamento' => $request->forma_pagamento,
            'status' => 'pendente',
        ]);

        // Redirecionar para gateway de pagamento
        switch ($request->forma_pagamento) {
            case 'pix':
                return redirect()->route('member.eventos.pagamento.pix', $pagamento);
            case 'stripe':
                return redirect()->route('member.eventos.pagamento.stripe', $pagamento);
            case 'mercadopago':
                return redirect()->route('member.eventos.pagamento.mercadopago', $pagamento);
            default:
                return redirect()->route('member.eventos.pagamento.manual', $pagamento);
        }
    }

    /**
     * Confirmação de inscrição
     */
    public function confirmacao(EventoInscricao $inscricao)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($user && $inscricao->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para acessar esta inscrição.');
        }

        $evento = $inscricao->evento;

        return view('member.eventos.confirmacao', compact('inscricao', 'evento'));
    }

    /**
     * Minhas inscrições
     */
    public function minhasInscricoes()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Faça login para ver suas inscrições.');
        }

        $inscricoes = $user->eventoInscricoes()
                           ->with(['evento'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        // Calcular estatísticas
        $estatisticas = [
            'total' => $user->eventoInscricoes()->count(),
            'confirmadas' => $user->eventoInscricoes()->where('status', 'confirmada')->count(),
            'pendentes' => $user->eventoInscricoes()->where('status', 'pendente')->count(),
            'certificados' => $user->eventoInscricoes()->where('certificado_emitido', true)->count(),
        ];

        return view('member.eventos.minhas-inscricoes', compact('inscricoes', 'estatisticas'));
    }

    /**
     * Cancelar inscrição
     */
    public function cancelarInscricao(EventoInscricao $inscricao)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($inscricao->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para cancelar esta inscrição.');
        }

        // Verificar se pode cancelar
        if (!$inscricao->podeCancelar()) {
            return back()->with('error', 'Esta inscrição não pode ser cancelada.');
        }

        $inscricao->cancelar();

        // Atualizar vagas disponíveis do evento
        $evento = $inscricao->evento;
        if ($evento->vagas_disponiveis !== null && $inscricao->status === 'confirmada') {
            $evento->increment('vagas_disponiveis');
        }

        return back()->with('success', 'Inscrição cancelada com sucesso!');
    }

    /**
     * Download de certificado
     */
    public function downloadCertificado(EventoInscricao $inscricao)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($inscricao->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para baixar este certificado.');
        }

        // Verificar se o certificado foi emitido
        if (!$inscricao->certificado_emitido) {
            return back()->with('error', 'Certificado ainda não foi emitido.');
        }

        // Aqui você implementaria a geração do PDF do certificado
        // Por enquanto, retornamos uma mensagem
        return back()->with('info', 'Funcionalidade de certificado em desenvolvimento.');
    }
} 