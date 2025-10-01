<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{DocumentoBaixa, Transacao, Membro};
use App\Services\PdfService;
use App\Exports\DocumentosBaixaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentoBaixaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:finance.access');
    }

    /**
     * Lista de documentos de baixa
     */
    public function index(Request $request)
    {
        $query = DocumentoBaixa::with(['transacao.membro', 'transacao.campanha']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_documento', 'like', '%' . $request->search . '%')
                  ->orWhere('protocolo_receita', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ano_exercicio')) {
            $query->where('ano_exercicio', $request->ano_exercicio);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_emissao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_emissao', '<=', $request->data_fim);
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estatísticas detalhadas
        $documentosVencidos = DocumentoBaixa::vencidos()->get();
        $documentosPendentes = DocumentoBaixa::where('status', 'PENDENTE')->get();
        $documentosPagos = DocumentoBaixa::where('status', 'PAGO')->get();
        
        $estatisticas = [
            'total_documentos' => DocumentoBaixa::count(),
            'documentos_pendentes' => $documentosPendentes->count(),
            'documentos_pagos' => $documentosPagos->count(),
            'documentos_vencidos' => $documentosVencidos->count(),
            'valor_total_pendente' => $documentosPendentes->sum('valor_documento'),
            'valor_total_pago' => $documentosPagos->sum('valor_pago'),
            'multa_juros_total' => $documentosVencidos->sum(function($doc) {
                return $doc->calcularMultaJuros();
            }),
            'documentos_hoje' => DocumentoBaixa::whereDate('created_at', today())->count(),
            'documentos_semana' => DocumentoBaixa::where('created_at', '>=', now()->subWeek())->count(),
            'documentos_mes' => DocumentoBaixa::where('created_at', '>=', now()->subMonth())->count(),
            'tipos_documentos' => DocumentoBaixa::selectRaw('tipo_documento, COUNT(*) as total')
                ->groupBy('tipo_documento')
                ->pluck('total', 'tipo_documento')
        ];

        return view('admin.finance.documentos.index', compact('documentos', 'estatisticas'));
    }

    /**
     * Formulário para criar novo documento
     */
    public function create(Request $request)
    {
        try {
            // Buscar transações confirmadas sem documento de baixa (limitado para performance)
            $query = Transacao::where('status', 'confirmado')
                ->whereDoesntHave('documentoBaixa')
                ->with(['membro:id,nome', 'campanha:id,titulo']);

            // Filtrar por campanha se especificado
            if ($request->filled('campanha_id')) {
                $query->where('campanha_id', $request->campanha_id);
            }

            // Filtrar por membro se especificado
            if ($request->filled('membro_id')) {
                $query->where('membro_id', $request->membro_id);
            }

            // Filtrar por período se especificado
            if ($request->filled('data_inicio')) {
                $query->whereDate('created_at', '>=', $request->data_inicio);
            }

            if ($request->filled('data_fim')) {
                $query->whereDate('created_at', '<=', $request->data_fim);
            }

            // Limitar a 100 transações para performance
            $transacoes = $query->orderBy('created_at', 'desc')->limit(100)->get();

            // Carregar campanhas para filtro (apenas ativas)
            $campanhas = \App\Models\Campanha::select('id', 'titulo')
                ->where('status', 'ativa')
                ->orderBy('titulo')
                ->get();
            
            // Carregar membros para filtro (limitado)
            $membros = \App\Models\Membro::select('id', 'nome')
                ->orderBy('nome')
                ->limit(50)
                ->get();

            // Estatísticas otimizadas
            $estatisticas = [
                'total_transacoes_disponiveis' => Transacao::where('status', 'confirmado')
                    ->whereDoesntHave('documentoBaixa')
                    ->count(),
                'valor_total_disponivel' => Transacao::where('status', 'confirmado')
                    ->whereDoesntHave('documentoBaixa')
                    ->sum('valor'),
                'transacoes_hoje' => Transacao::where('status', 'confirmado')
                    ->whereDoesntHave('documentoBaixa')
                    ->whereDate('created_at', today())
                    ->count(),
                'transacoes_semana' => Transacao::where('status', 'confirmado')
                    ->whereDoesntHave('documentoBaixa')
                    ->where('created_at', '>=', now()->subWeek())
                    ->count(),
            ];

            return view('admin.finance.documentos.create', compact('transacoes', 'campanhas', 'membros', 'estatisticas'));

        } catch (\Exception $e) {
            \Log::error('Erro ao carregar formulário de criação de documento', [
                'error' => $e->getMessage(),
                'usuario' => auth()->user()->name ?? 'Sistema'
            ]);

            return back()->withErrors(['error' => 'Erro ao carregar dados. Tente novamente.']);
        }
    }

    /**
     * Salvar novo documento
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'transacao_id' => 'required|exists:transacoes,id',
                'tipo_documento' => 'required|in:' . implode(',', array_keys(DocumentoBaixa::TIPOS_DOCUMENTO)),
                'numero_documento' => 'required|string|max:50',
                'ano_exercicio' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
                'data_emissao' => 'nullable|date',
                'data_vencimento' => 'nullable|date',
                'valor_documento' => 'nullable|numeric|min:0.01',
                'observacoes' => 'nullable|string|max:1000',
                'arquivo_comprovante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Verificar se já existe documento para esta transação
            $documentoExistente = DocumentoBaixa::where('transacao_id', $request->transacao_id)->first();
            if ($documentoExistente) {
                return back()->withErrors(['transacao_id' => 'Já existe um documento de baixa para esta transação.'])->withInput();
            }

            // Carregar dados da transação automaticamente
            $transacao = Transacao::with(['membro', 'campanha'])->find($request->transacao_id);
            if (!$transacao) {
                return back()->withErrors(['transacao_id' => 'Transação não encontrada.'])->withInput();
            }

            // Preparar dados do documento
            $dadosDocumento = $request->all();
            
            // Carregar dados automaticamente da transação se não informados
            if (empty($dadosDocumento['valor_documento'])) {
                $dadosDocumento['valor_documento'] = $transacao->valor;
            }
            
            if (empty($dadosDocumento['data_emissao'])) {
                $dadosDocumento['data_emissao'] = $transacao->created_at->format('Y-m-d');
            }
            
            if (empty($dadosDocumento['ano_exercicio'])) {
                $dadosDocumento['ano_exercicio'] = date('Y');
            }

            // Validar formato do documento
            $documentoTemp = new DocumentoBaixa($dadosDocumento);
            if (!$documentoTemp->validarFormatoDocumento()) {
                return back()->withErrors(['numero_documento' => 'Formato do número do documento inválido para o tipo selecionado.'])->withInput();
            }

            $documento = new DocumentoBaixa($dadosDocumento);
            
            // Upload do arquivo
            if ($request->hasFile('arquivo_comprovante')) {
                $arquivo = $request->file('arquivo_comprovante');
                $nomeArquivo = 'documento_' . time() . '_' . Str::random(10) . '.' . $arquivo->getClientOriginalExtension();
                $caminho = $arquivo->storeAs('documentos_baixa', $nomeArquivo, 'public');
                $documento->arquivo_comprovante = $caminho;
            }

            $documento->save();

            // Log da criação
            \Log::info('Documento de baixa criado', [
                'documento_id' => $documento->id,
                'transacao_id' => $documento->transacao_id,
                'tipo_documento' => $documento->tipo_documento,
                'valor' => $documento->valor_documento,
                'usuario' => auth()->user()->name ?? 'Sistema'
            ]);

            return redirect()->route('admin.finance.documentos.index')
                ->with('success', 'Documento de baixa criado com sucesso! Protocolo: ' . $documento->protocolo_receita);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar documento de baixa', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'usuario' => auth()->user()->name ?? 'Sistema'
            ]);

            return back()->withErrors(['error' => 'Erro ao criar documento de baixa. Tente novamente.'])->withInput();
        }
    }

    /**
     * Visualizar documento
     */
    public function show(DocumentoBaixa $documento)
    {
        $documento->load(['transacao.membro', 'transacao.campanha']);
        
        return view('admin.finance.documentos.show', compact('documento'));
    }

    /**
     * Formulário para editar documento
     */
    public function edit(DocumentoBaixa $documento)
    {
        $transacoes = Transacao::where('status', 'confirmado')
            ->where(function($q) use ($documento) {
                $q->whereDoesntHave('documentoBaixa')
                  ->orWhereHas('documentoBaixa', function($subQ) use ($documento) {
                      $subQ->where('id', $documento->id);
                  });
            })
            ->with(['membro', 'campanha'])
            ->get();

        return view('admin.finance.documentos.edit', compact('documento', 'transacoes'));
    }

    /**
     * Atualizar documento
     */
    public function update(Request $request, DocumentoBaixa $documento)
    {
        $validator = Validator::make($request->all(), [
            'transacao_id' => 'required|exists:transacoes,id',
            'tipo_documento' => 'required|in:' . implode(',', array_keys(DocumentoBaixa::TIPOS_DOCUMENTO)),
            'numero_documento' => 'required|string|max:50',
            'ano_exercicio' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'data_emissao' => 'required|date',
            'data_vencimento' => 'nullable|date|after_or_equal:data_emissao',
            'valor_documento' => 'required|numeric|min:0.01',
            'valor_pago' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(DocumentoBaixa::STATUS)),
            'observacoes' => 'nullable|string|max:1000',
            'arquivo_comprovante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Upload do arquivo
        if ($request->hasFile('arquivo_comprovante')) {
            // Remove arquivo anterior
            if ($documento->arquivo_comprovante) {
                Storage::disk('public')->delete($documento->arquivo_comprovante);
            }
            
            $arquivo = $request->file('arquivo_comprovante');
            $nomeArquivo = 'documento_' . time() . '_' . Str::random(10) . '.' . $arquivo->getClientOriginalExtension();
            $caminho = $arquivo->storeAs('documentos_baixa', $nomeArquivo, 'public');
            $request->merge(['arquivo_comprovante' => $caminho]);
        }

        $documento->update($request->all());

        return redirect()->route('admin.finance.documentos.index')
            ->with('success', 'Documento de baixa atualizado com sucesso!');
    }

    /**
     * Excluir documento
     */
    public function destroy(DocumentoBaixa $documento)
    {
        // Remove arquivo
        if ($documento->arquivo_comprovante) {
            Storage::disk('public')->delete($documento->arquivo_comprovante);
        }

        $documento->delete();

        return redirect()->route('admin.finance.documentos.index')
            ->with('success', 'Documento de baixa excluído com sucesso!');
    }

    /**
     * Marcar documento como pago
     */
    public function marcarComoPago(Request $request, DocumentoBaixa $documento)
    {
        $request->validate([
            'valor_pago' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'comprovante_pagamento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $documento->status = 'PAGO';
        $documento->valor_pago = $request->valor_pago;
        $documento->data_pagamento = $request->data_pagamento;

        // Upload do comprovante
        if ($request->hasFile('comprovante_pagamento')) {
            $arquivo = $request->file('comprovante_pagamento');
            $nomeArquivo = 'comprovante_' . time() . '_' . Str::random(10) . '.' . $arquivo->getClientOriginalExtension();
            $caminho = $arquivo->storeAs('comprovantes_pagamento', $nomeArquivo, 'public');
            $documento->comprovante_pagamento = $caminho;
        }

        $documento->save();

        return redirect()->route('admin.finance.documentos.show', $documento)
            ->with('success', 'Documento marcado como pago com sucesso!');
    }

    /**
     * Gerar PDF do documento
     */
    public function gerarPdf(DocumentoBaixa $documento)
    {
        $documento->load(['transacao.membro', 'transacao.campanha']);
        
        $pdfService = new PdfService();
        $pdf = $pdfService->gerarDocumentoBaixa($documento);
        
        return $pdf->download("documento_baixa_{$documento->numero_documento}.pdf");
    }

    /**
     * MÉTODO REMOVIDO - ERA ILEGAL GERAR CÓDIGO FEBRABAN
     * Apenas organizações autorizadas podem gerar códigos FEBRABAN
     */
    public function gerarCodigoBarras(DocumentoBaixa $documento)
    {
        return response()->json([
            'error' => 'Funcionalidade removida por questões legais. Apenas organizações autorizadas podem gerar códigos FEBRABAN.'
        ], 400);
    }

    /**
     * Validar documento
     */
    public function validarDocumento(DocumentoBaixa $documento)
    {
        $valido = $documento->validarFormatoDocumento();
        
        return response()->json([
            'valido' => $valido,
            'tipo_documento' => $documento->tipo_documento,
            'numero_documento' => $documento->numero_documento,
            'mensagem' => $valido ? 'Documento válido' : 'Formato do documento inválido'
        ]);
    }

    /**
     * Calcular multa e juros
     */
    public function calcularMultaJuros(DocumentoBaixa $documento)
    {
        $multaJuros = $documento->calcularMultaJuros();
        
        return response()->json([
            'multa_juros' => $multaJuros,
            'valor_original' => $documento->valor_documento,
            'valor_total' => $documento->valor_documento + $multaJuros,
            'dias_vencido' => $documento->isVencido() ? (int) $documento->data_vencimento->diffInDays(now()) : 0
        ]);
    }

    /**
     * Exportar relatório
     */
    public function exportar(Request $request)
    {
        $query = DocumentoBaixa::with(['transacao.membro', 'transacao.campanha']);

        // Aplicar filtros
        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ano_exercicio')) {
            $query->where('ano_exercicio', $request->ano_exercicio);
        }

        $documentos = $query->get();

        return Excel::download(new DocumentosBaixaExport($documentos), 'documentos_baixa.xlsx');
    }
} 