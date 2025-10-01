<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Evento, EventoInscricao, EventoPagamento, Ministerio, User};
use Illuminate\Support\Facades\{Storage, Auth, DB};
use Carbon\Carbon;

class EventoController extends Controller
{
    /**
     * Listar todos os eventos
     */
    public function index(Request $request)
    {
        $query = Evento::with(['organizador', 'ministerio']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo_evento')) {
            $query->where('tipo_evento', $request->tipo_evento);
        }

        if ($request->filled('tipo_publico')) {
            $query->where('tipo_publico', $request->tipo_publico);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('local', 'like', "%{$search}%");
            });
        }

        $eventos = $query->orderBy('data_inicio', 'desc')->paginate(15);

        $estatisticas = [
            'total' => Evento::count(),
            'ativos' => Evento::where('status', 'ativo')->count(),
            'futuros' => Evento::futuros()->count(),
            'passados' => Evento::passados()->count(),
        ];

        return view('admin.eventos.index', compact('eventos', 'estatisticas'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $ministerios = Ministerio::ativos()->orderBy('nome')->get();
        $organizadores = User::where('ativo', true)->orderBy('name')->get();

        return view('admin.eventos.create', compact('ministerios', 'organizadores'));
    }

    /**
     * Salvar novo evento
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'descricao_curta' => 'nullable|string|max:500',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fim' => 'nullable|date_format:H:i|after:hora_inicio',
            'local' => 'nullable|string|max:255',
            'endereco' => 'nullable|string|max:500',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'tipo_publico' => 'required|in:membros,publico,ambos',
            'tipo_evento' => 'required|in:culto,estudo,reuniao,conferencia,outro',
            'status' => 'required|in:rascunho,ativo,cancelado,finalizado',
            'gratuito' => 'required|boolean',
            'valor_inscricao' => 'nullable|numeric|min:0',
            'vagas_totais' => 'nullable|integer|min:1',
            'inscricao_obrigatoria' => 'required|boolean',
            'inscricao_ate' => 'nullable|date|after:today',
            'organizador_id' => 'nullable|exists:users,id',
            'ministerio_id' => 'nullable|exists:ministerios,id',
            'regulamento' => 'nullable|string',
            'informacoes_adicionais' => 'nullable|string',
            'tags' => 'nullable|array',
            'destaque' => 'boolean',
            'ativo' => 'boolean',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Processar imagem
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        // Processar tags
        if ($request->filled('tags')) {
            $data['tags'] = array_filter($request->tags);
        }

        // Definir vagas disponíveis
        if ($request->filled('vagas_totais')) {
            $data['vagas_disponiveis'] = $data['vagas_totais'];
        }

        $data['criado_por'] = Auth::id();

        $evento = Evento::create($data);

        return redirect()->route('admin.eventos.index')
                        ->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Visualizar evento
     */
    public function show(Evento $evento)
    {
        $evento->load(['organizador', 'ministerio', 'inscricoes.user', 'pagamentos']);

        $estatisticas = [
            'total_inscricoes' => $evento->inscricoes()->count(),
            'inscricoes_confirmadas' => $evento->inscricoes()->where('status', 'confirmada')->count(),
            'inscricoes_pendentes' => $evento->inscricoes()->where('status', 'pendente')->count(),
            'presentes' => $evento->inscricoes()->where('status', 'presente')->count(),
            'pagamentos_aprovados' => $evento->pagamentos()->where('status', 'aprovado')->count(),
            'valor_total_arrecadado' => $evento->pagamentos()->where('status', 'aprovado')->sum('valor'),
        ];

        return view('admin.eventos.show', compact('evento', 'estatisticas'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Evento $evento)
    {
        $ministerios = Ministerio::ativos()->orderBy('nome')->get();
        $organizadores = User::where('ativo', true)->orderBy('name')->get();

        return view('admin.eventos.edit', compact('evento', 'ministerios', 'organizadores'));
    }

    /**
     * Atualizar evento
     */
    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'descricao_curta' => 'nullable|string|max:500',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fim' => 'nullable|date_format:H:i|after:hora_inicio',
            'local' => 'nullable|string|max:255',
            'endereco' => 'nullable|string|max:500',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'tipo_publico' => 'required|in:membros,publico,ambos',
            'tipo_evento' => 'required|in:culto,estudo,reuniao,conferencia,outro',
            'status' => 'required|in:rascunho,ativo,cancelado,finalizado',
            'gratuito' => 'required|boolean',
            'valor_inscricao' => 'nullable|numeric|min:0',
            'vagas_totais' => 'nullable|integer|min:1',
            'inscricao_obrigatoria' => 'required|boolean',
            'inscricao_ate' => 'nullable|date|after:today',
            'organizador_id' => 'nullable|exists:users,id',
            'ministerio_id' => 'nullable|exists:ministerios,id',
            'regulamento' => 'nullable|string',
            'informacoes_adicionais' => 'nullable|string',
            'tags' => 'nullable|array',
            'destaque' => 'boolean',
            'ativo' => 'boolean',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Processar imagem
        if ($request->hasFile('imagem')) {
            // Remover imagem antiga
            if ($evento->imagem) {
                Storage::disk('public')->delete($evento->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('eventos', 'public');
        }

        // Processar tags
        if ($request->filled('tags')) {
            $data['tags'] = array_filter($request->tags);
        }

        // Atualizar vagas disponíveis se necessário
        if ($request->filled('vagas_totais') && $request->vagas_totais != $evento->vagas_totais) {
            $inscritos = $evento->inscricoes()->where('status', 'confirmada')->count();
            $data['vagas_disponiveis'] = max(0, $request->vagas_totais - $inscritos);
        }

        $data['atualizado_por'] = Auth::id();

        $evento->update($data);

        return redirect()->route('admin.eventos.index')
                        ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Excluir evento
     */
    public function destroy(Evento $evento)
    {
        // Verificar se há inscrições
        if ($evento->inscricoes()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um evento que possui inscrições.');
        }

        // Remover imagem
        if ($evento->imagem) {
            Storage::disk('public')->delete($evento->imagem);
        }

        $evento->delete();

        return redirect()->route('admin.eventos.index')
                        ->with('success', 'Evento excluído com sucesso!');
    }

    /**
     * Gerenciar inscrições
     */
    public function inscricoes(Evento $evento)
    {
        $inscricoes = $evento->inscricoes()
                             ->with('user')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        $totalInscricoes = $evento->inscricoes()->count();
        $inscricoesConfirmadas = $evento->inscricoes()->where('status', 'confirmada')->count();
        $inscricoesPendentes = $evento->inscricoes()->where('status', 'pendente')->count();
        $inscricoesPresentes = $evento->inscricoes()->where('status', 'presente')->count();
        $inscricoesCanceladas = $evento->inscricoes()->where('status', 'cancelada')->count();

        $estatisticas = [
            'total' => $totalInscricoes,
            'confirmadas' => $inscricoesConfirmadas,
            'pendentes' => $inscricoesPendentes,
            'presentes' => $inscricoesPresentes,
            'canceladas' => $inscricoesCanceladas,
        ];

        return view('admin.eventos.inscricoes', compact('evento', 'inscricoes', 'estatisticas', 'totalInscricoes', 'inscricoesConfirmadas', 'inscricoesPendentes', 'inscricoesPresentes', 'inscricoesCanceladas'));
    }

    /**
     * Gerenciar pagamentos
     */
    public function pagamentos(Evento $evento)
    {
        $pagamentos = $evento->pagamentos()
                             ->with(['user', 'inscricao'])
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        $totalPagamentos = $evento->pagamentos()->count();
        $pagamentosAprovados = $evento->pagamentos()->where('status', 'aprovado')->count();
        $pagamentosPendentes = $evento->pagamentos()->where('status', 'pendente')->count();
        $pagamentosProcessando = $evento->pagamentos()->where('status', 'processando')->count();
        $totalAprovado = $evento->pagamentos()->where('status', 'aprovado')->sum('valor');

        $estatisticas = [
            'total' => $totalPagamentos,
            'aprovados' => $pagamentosAprovados,
            'pendentes' => $pagamentosPendentes,
            'processando' => $pagamentosProcessando,
            'valor_total' => $totalAprovado,
        ];

        return view('admin.eventos.pagamentos', compact('evento', 'pagamentos', 'estatisticas', 'totalPagamentos', 'pagamentosAprovados', 'pagamentosPendentes', 'pagamentosProcessando', 'totalAprovado'));
    }

    /**
     * Exportar lista de inscritos
     */
    public function exportInscricoes(Evento $evento)
    {
        $inscricoes = $evento->inscricoes()->with('user')->get();
        $totalInscricoes = $inscricoes->count();
        $confirmadas = $inscricoes->where('status', 'confirmada')->count();
        $pendentes = $inscricoes->where('status', 'pendente')->count();
        $presentes = $inscricoes->where('presenca', true)->count();
        $ausentes = $inscricoes->where('presenca', false)->count();

        $filename = "inscricoes_{$evento->titulo}_" . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($inscricoes, $evento, $totalInscricoes, $confirmadas, $pendentes, $presentes, $ausentes) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho do relatório
            fputcsv($file, ['RELATÓRIO DE INSCRIÇÕES - ' . strtoupper($evento->titulo)]);
            fputcsv($file, ['']);
            fputcsv($file, ['Data do Relatório:', date('d/m/Y H:i:s')]);
            fputcsv($file, ['Evento:', $evento->titulo]);
            fputcsv($file, ['Data do Evento:', $evento->data_inicio->format('d/m/Y')]);
            fputcsv($file, ['Local:', $evento->local ?: 'Não informado']);
            fputcsv($file, ['']);
            
            // Estatísticas
            fputcsv($file, ['ESTATÍSTICAS']);
            fputcsv($file, ['Total de Inscrições:', $totalInscricoes]);
            fputcsv($file, ['Inscrições Confirmadas:', $confirmadas]);
            fputcsv($file, ['Inscrições Pendentes:', $pendentes]);
            fputcsv($file, ['Presentes:', $presentes]);
            fputcsv($file, ['Ausentes:', $ausentes]);
            fputcsv($file, ['']);
            
            // Cabeçalho da tabela
            fputcsv($file, [
                'Nº', 'Nome Completo', 'E-mail', 'Telefone', 'CPF', 'Data de Nascimento',
                'Endereço', 'Cidade', 'Estado', 'CEP', 'Status da Inscrição',
                'Data da Inscrição', 'Valor Pago', 'Forma de Pagamento', 'Status do Pagamento',
                'Presença', 'Data da Presença', 'Observações'
            ]);

            // Dados
            $numero = 1;
            foreach ($inscricoes as $inscricao) {
                fputcsv($file, [
                    $numero++,
                    $inscricao->nome,
                    $inscricao->email,
                    $inscricao->telefone,
                    $inscricao->cpf,
                    $inscricao->data_nascimento ? $inscricao->data_nascimento->format('d/m/Y') : '',
                    $inscricao->endereco ?: '',
                    $inscricao->cidade ?: '',
                    $inscricao->estado ?: '',
                    $inscricao->cep ?: '',
                                         $inscricao->status_formatado,
                     $inscricao->created_at ? $inscricao->created_at->format('d/m/Y H:i') : '',
                     $inscricao->valor_pago_formatado,
                     $inscricao->forma_pagamento_formatada,
                     $inscricao->status_pagamento_formatado,
                     $inscricao->presenca_formatado,
                     $inscricao->data_presenca_formatada,
                     $inscricao->observacoes ?: ''
                ]);
            }

            // Resumo final
            fputcsv($file, ['']);
            fputcsv($file, ['RESUMO FINAL']);
            fputcsv($file, ['Total de Inscritos:', $totalInscricoes]);
            fputcsv($file, ['Confirmados:', $confirmadas]);
            fputcsv($file, ['Pendentes:', $pendentes]);
            fputcsv($file, ['Presentes:', $presentes]);
            fputcsv($file, ['Ausentes:', $ausentes]);
            fputcsv($file, ['Taxa de Confirmação:', $totalInscricoes > 0 ? round(($confirmadas / $totalInscricoes) * 100, 2) . '%' : '0%']);
            fputcsv($file, ['Taxa de Presença:', $totalInscricoes > 0 ? round(($presentes / $totalInscricoes) * 100, 2) . '%' : '0%']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar lista de pagamentos
     */
    public function exportPagamentos(Evento $evento)
    {
        $pagamentos = $evento->pagamentos()->with(['inscricao'])->get();
        $totalPagamentos = $pagamentos->count();
        $aprovados = $pagamentos->where('status', 'aprovado')->count();
        $pendentes = $pagamentos->where('status', 'pendente')->count();
        $processando = $pagamentos->where('status', 'processando')->count();
        $totalAprovado = $pagamentos->where('status', 'aprovado')->sum('valor');

        $filename = "pagamentos_{$evento->titulo}_" . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($pagamentos, $evento, $totalPagamentos, $aprovados, $pendentes, $processando, $totalAprovado) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho do relatório
            fputcsv($file, ['RELATÓRIO DE PAGAMENTOS - ' . strtoupper($evento->titulo)]);
            fputcsv($file, ['']);
            fputcsv($file, ['Data do Relatório:', date('d/m/Y H:i:s')]);
            fputcsv($file, ['Evento:', $evento->titulo]);
            fputcsv($file, ['Data do Evento:', $evento->data_inicio->format('d/m/Y')]);
            fputcsv($file, ['Local:', $evento->local ?: 'Não informado']);
            fputcsv($file, ['']);
            
            // Estatísticas
            fputcsv($file, ['ESTATÍSTICAS']);
            fputcsv($file, ['Total de Pagamentos:', $totalPagamentos]);
            fputcsv($file, ['Pagamentos Aprovados:', $aprovados]);
            fputcsv($file, ['Pagamentos Pendentes:', $pendentes]);
            fputcsv($file, ['Pagamentos Processando:', $processando]);
            fputcsv($file, ['Valor Total Aprovado:', 'R$ ' . number_format($totalAprovado, 2, ',', '.')]);
            fputcsv($file, ['']);
            
            // Cabeçalho da tabela
            fputcsv($file, [
                'Nº', 'Participante', 'E-mail', 'Valor', 'Forma de Pagamento', 'Status',
                'Data do Pagamento', 'ID da Transação', 'Observações'
            ]);

            // Dados
            $numero = 1;
            foreach ($pagamentos as $pagamento) {
                fputcsv($file, [
                    $numero++,
                    $pagamento->inscricao->nome,
                    $pagamento->inscricao->email,
                    'R$ ' . number_format($pagamento->valor, 2, ',', '.'),
                    ucfirst($pagamento->forma_pagamento),
                    ucfirst($pagamento->status),
                    $pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y H:i') : '',
                    $pagamento->gateway_transaction_id ?: '',
                    $pagamento->observacoes ?: ''
                ]);
            }

            // Resumo final
            fputcsv($file, ['']);
            fputcsv($file, ['RESUMO FINAL']);
            fputcsv($file, ['Total de Pagamentos:', $totalPagamentos]);
            fputcsv($file, ['Aprovados:', $aprovados]);
            fputcsv($file, ['Pendentes:', $pendentes]);
            fputcsv($file, ['Processando:', $processando]);
            fputcsv($file, ['Valor Total Aprovado:', 'R$ ' . number_format($totalAprovado, 2, ',', '.')]);
            fputcsv($file, ['Taxa de Aprovação:', $totalPagamentos > 0 ? round(($aprovados / $totalPagamentos) * 100, 2) . '%' : '0%']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle status do evento
     */
    public function toggleStatus(Evento $evento)
    {
        $novoStatus = $evento->status === 'ativo' ? 'rascunho' : 'ativo';
        $evento->update(['status' => $novoStatus]);

        return back()->with('success', "Status do evento alterado para {$novoStatus}!");
    }

    /**
     * Toggle destaque do evento
     */
    public function toggleDestaque(Evento $evento)
    {
        $evento->update(['destaque' => !$evento->destaque]);

        return back()->with('success', 'Destaque do evento alterado!');
    }
} 