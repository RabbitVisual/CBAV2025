<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentoDeclaracaoAnual;
use App\Models\Igreja;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\DocumentoDeclaracaoAnualExport;
use Maatwebsite\Excel\Facades\Excel;

class DocumentoDeclaracaoAnualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * Listar documentos de declaração anual
     */
    public function index(Request $request)
    {
        $query = DocumentoDeclaracaoAnual::with(['igreja', 'validadoPor']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('protocolo_receita', 'like', "%{$search}%")
                  ->orWhere('numero_documento', 'like', "%{$search}%")
                  ->orWhereHas('igreja', function($q) use ($search) {
                      $q->where('nome', 'like', "%{$search}%");
                  });
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

        if ($request->filled('igreja_id')) {
            $query->where('igreja_id', $request->igreja_id);
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estatísticas
        $estatisticas = [
            'total_documentos' => DocumentoDeclaracaoAnual::count(),
            'documentos_validados' => DocumentoDeclaracaoAnual::where('status', 'VALIDADO')->count(),
            'documentos_pendentes' => DocumentoDeclaracaoAnual::where('status', 'PENDENTE')->count(),
            'documentos_vencidos' => DocumentoDeclaracaoAnual::vencidos()->count(),
            'valor_total_validados' => DocumentoDeclaracaoAnual::where('status', 'VALIDADO')->sum('valor_total'),
            'igrejas_ativas' => Igreja::ativas()->count()
        ];

        $igrejas = Igreja::ativas()->orderBy('nome')->get();

        return view('admin.finance.documentos-declaracao-anual.index', compact(
            'documentos',
            'estatisticas',
            'igrejas'
        ));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create(Request $request)
    {
        $igrejas = Igreja::ativas()->orderBy('nome')->get();
        
        // Estatísticas para o ano selecionado
        $ano = $request->get('ano_exercicio', date('Y'));
        $igrejaId = $request->get('igreja_id');
        
        $estatisticas = [
            'total_transacoes' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereYear('created_at', $ano)->count(),
            'valor_total_transacoes' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereYear('created_at', $ano)->sum('valor'),
            'transacoes_hoje' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereDate('created_at', today())->count(),
            'transacoes_semana' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return view('admin.finance.documentos-declaracao-anual.create', compact(
            'igrejas',
            'estatisticas',
            'ano',
            'igrejaId'
        ));
    }

    /**
     * Salvar novo documento
     */
    public function store(Request $request)
    {
        $request->validate([
            'igreja_id' => 'required|exists:igrejas,id',
            'ano_exercicio' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'tipo_documento' => 'required|in:' . implode(',', array_keys(DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO)),
            'numero_documento' => 'required|string|max:50',
            'data_emissao' => 'required|date|before_or_equal:today',
            'data_vencimento' => 'nullable|date|after:data_emissao',
            'valor_total' => 'required|numeric|min:0.01',
            'valor_doacoes' => 'nullable|numeric|min:0',
            'valor_dizimos' => 'nullable|numeric|min:0',
            'valor_outros' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'arquivo_comprovante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $documento = new DocumentoDeclaracaoAnual($request->all());
            $documento->status = 'PENDENTE';
            
            // Gerar protocolo da Receita Federal
            $documento->protocolo_receita = $documento->gerarProtocoloReceita();
            
            // Gerar hash de validação
            $documento->hash_documento = $documento->gerarHashDocumento();
            
            // Gerar QR Code
            $documento->qr_code = $documento->gerarQRCode();
            
            // Gerar código de barras
            $documento->codigo_barras = $documento->gerarCodigoBarras();
            
            // Gerar certificado digital
            $documento->certificado_digital = $documento->gerarCertificadoDigital();
            
            // Gerar assinatura digital
            $documento->assinatura_digital = $documento->gerarAssinaturaDigital();

            // Upload do arquivo comprovante
            if ($request->hasFile('arquivo_comprovante')) {
                $path = $request->file('arquivo_comprovante')->store('documentos-declaracao-anual', 'public');
                $documento->arquivo_comprovante = $path;
            }

            $documento->save();

            DB::commit();

            return redirect()
                ->route('admin.finance.documentos-declaracao-anual.show', $documento)
                ->with('success', 'Documento de declaração anual criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar documento: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar documento
     */
    public function show(DocumentoDeclaracaoAnual $documento)
    {
        $documento->load(['igreja', 'validadoPor']);
        
        return view('admin.finance.documentos-declaracao-anual.show', compact('documento'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(DocumentoDeclaracaoAnual $documento)
    {
        $igrejas = Igreja::ativas()->orderBy('nome')->get();
        
        return view('admin.finance.documentos-declaracao-anual.edit', compact('documento', 'igrejas'));
    }

    /**
     * Atualizar documento
     */
    public function update(Request $request, DocumentoDeclaracaoAnual $documento)
    {
        $request->validate([
            'igreja_id' => 'required|exists:igrejas,id',
            'ano_exercicio' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'tipo_documento' => 'required|in:' . implode(',', array_keys(DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO)),
            'numero_documento' => 'required|string|max:50',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'nullable|date|after:data_emissao',
            'valor_total' => 'required|numeric|min:0.01',
            'valor_doacoes' => 'nullable|numeric|min:0',
            'valor_dizimos' => 'nullable|numeric|min:0',
            'valor_outros' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'arquivo_comprovante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $documento->fill($request->except(['arquivo_comprovante']));
            
            // Regerar hash se dados importantes mudaram
            $documento->hash_documento = $documento->gerarHashDocumento();
            $documento->qr_code = $documento->gerarQRCode();
            $documento->codigo_barras = $documento->gerarCodigoBarras();
            $documento->certificado_digital = $documento->gerarCertificadoDigital();
            $documento->assinatura_digital = $documento->gerarAssinaturaDigital();

            // Upload do arquivo comprovante
            if ($request->hasFile('arquivo_comprovante')) {
                // Remover arquivo antigo
                if ($documento->arquivo_comprovante) {
                    Storage::disk('public')->delete($documento->arquivo_comprovante);
                }
                
                $path = $request->file('arquivo_comprovante')->store('documentos-declaracao-anual', 'public');
                $documento->arquivo_comprovante = $path;
            }

            $documento->save();

            DB::commit();

            return redirect()
                ->route('admin.finance.documentos-declaracao-anual.show', $documento)
                ->with('success', 'Documento atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar documento: ' . $e->getMessage());
        }
    }

    /**
     * Excluir documento
     */
    public function destroy(DocumentoDeclaracaoAnual $documento)
    {
        try {
            // Remover arquivo comprovante
            if ($documento->arquivo_comprovante) {
                Storage::disk('public')->delete($documento->arquivo_comprovante);
            }

            $documento->delete();

            return redirect()
                ->route('admin.finance.documentos-declaracao-anual.index')
                ->with('success', 'Documento excluído com sucesso!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir documento: ' . $e->getMessage());
        }
    }

    /**
     * Validar documento
     */
    public function validar(DocumentoDeclaracaoAnual $documento)
    {
        try {
            if ($documento->validar()) {
                $documento->marcarComoValidado(auth()->user());
                
                return response()->json([
                    'success' => true,
                    'valido' => true,
                    'mensagem' => 'Documento validado com sucesso!',
                    'protocolo' => $documento->protocolo_receita,
                    'hash' => $documento->hash_documento
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'valido' => false,
                    'mensagem' => 'Documento não pôde ser validado. Verifique os dados.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'valido' => false,
                'mensagem' => 'Erro ao validar documento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Gerar PDF do documento
     */
    public function pdf(DocumentoDeclaracaoAnual $documento)
    {
        $documento->load(['igreja', 'validadoPor']);
        
        $pdf = \PDF::loadView('pdf.documento-declaracao-anual', compact('documento'));
        
        return $pdf->download("documento-declaracao-anual-{$documento->protocolo_receita}.pdf");
    }

    /**
     * Gerar código de barras
     */
    public function codigoBarras(DocumentoDeclaracaoAnual $documento)
    {
        return response()->json([
            'success' => true,
            'codigo_barras' => $documento->codigo_barras,
            'codigo_barras_svg' => $documento->gerarCodigoBarrasSVG(),
            'valor' => number_format($documento->valor_total, 2, ',', '.'),
            'vencimento' => $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : 'N/A',
            'numero_documento' => $documento->numero_documento
        ]);
    }

    /**
     * Gerar QR Code
     */
    public function qrCode(DocumentoDeclaracaoAnual $documento)
    {
        return response()->json([
            'success' => true,
            'qr_code_svg' => $documento->gerarQRCodeSVG(),
            'qr_code_data' => $documento->qr_code,
            'protocolo' => $documento->protocolo_receita,
            'hash' => $documento->hash_documento,
            'tipo' => DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento,
            'ano' => $documento->ano_exercicio,
            'valor' => number_format($documento->valor_total, 2, ',', '.'),
            'data' => $documento->data_emissao->format('d/m/Y')
        ]);
    }

    /**
     * Calcular multa e juros
     */
    public function calcularMultaJuros(DocumentoDeclaracaoAnual $documento)
    {
        $calculo = $documento->calcularMultaJuros();
        
        return response()->json([
            'success' => true,
            'valor_original' => number_format($calculo['valor_original'], 2, ',', '.'),
            'multa' => number_format($calculo['multa'], 2, ',', '.'),
            'juros' => number_format($calculo['juros'], 2, ',', '.'),
            'multa_juros' => number_format($calculo['multa'] + $calculo['juros'], 2, ',', '.'),
            'valor_total' => number_format($calculo['total'], 2, ',', '.'),
            'dias_vencido' => $calculo['dias_vencido'],
            'taxa_juros' => '1% ao mês',
            'taxa_multa' => '2%'
        ]);
    }

    /**
     * Exportar documentos
     */
    public function export(Request $request)
    {
        $documentos = DocumentoDeclaracaoAnual::with(['igreja', 'validadoPor'])
            ->when($request->filled('ano_exercicio'), function($q) use ($request) {
                $q->where('ano_exercicio', $request->ano_exercicio);
            })
            ->when($request->filled('tipo_documento'), function($q) use ($request) {
                $q->where('tipo_documento', $request->tipo_documento);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        return Excel::download(
            new DocumentoDeclaracaoAnualExport($documentos),
            'documentos-declaracao-anual-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Obter estatísticas por igreja
     */
    public function estatisticasPorIgreja(Request $request)
    {
        $igrejaId = $request->get('igreja_id');
        $ano = $request->get('ano_exercicio', date('Y'));

        $estatisticas = [
            'total_transacoes' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereYear('created_at', $ano)->count(),
            'valor_total_transacoes' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereYear('created_at', $ano)->sum('valor'),
            'transacoes_hoje' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereDate('created_at', today())->count(),
            'transacoes_semana' => Transacao::when($igrejaId, function($q) use ($igrejaId) {
                $q->where('igreja_id', $igrejaId);
            })->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return response()->json($estatisticas);
    }
} 