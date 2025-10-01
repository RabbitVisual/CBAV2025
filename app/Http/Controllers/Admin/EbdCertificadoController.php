<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbdCertificado;
use App\Models\EbdAluno;
use App\Models\EbdAvaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfService;

class EbdCertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ebd.access');
    }

    /**
     * Lista de certificados
     */
    public function index(Request $request)
    {
        $query = EbdCertificado::with(['aluno.membro', 'avaliacao']);

        // Filtros
        if ($request->filled('aluno_id')) {
            $query->where('aluno_id', $request->aluno_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('aluno.membro', function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%');
            });
        }

        $certificados = $query->orderBy('created_at', 'desc')->paginate(15);
        $alunos = EbdAluno::with('membro')->ativos()->get();
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();

        return view('admin.ebd.certificados.index', compact('certificados', 'alunos', 'avaliacoes'));
    }

    /**
     * Formulário para criar certificado
     */
    public function create()
    {
        $alunos = EbdAluno::with('membro')->ativos()->get();
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = [
            'conclusao' => 'Conclusão de Curso',
            'participacao' => 'Participação',
            'excelencia' => 'Excelência',
            'presenca' => 'Presença',
            'avaliacao' => 'Avaliação'
        ];

        return view('admin.ebd.certificados.create', compact('alunos', 'avaliacoes', 'tipos'));
    }

    /**
     * Salvar novo certificado
     */
    public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'avaliacao_id' => 'nullable|exists:ebd_avaliacoes,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao',
            'descricao' => 'nullable|string|max:1000',
            'conteudo' => 'required|string',
            'carga_horaria' => 'nullable|integer|min:1',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date|after:data_emissao',
            'assinatura_coordenador' => 'nullable|string|max:255',
            'assinatura_pastor' => 'nullable|string|max:255',
            'ativo' => 'boolean'
        ]);

        // Gerar código único
        $codigo = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');

        $certificado = EbdCertificado::create(array_merge($request->all(), [
            'codigo' => $codigo
        ]));

        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado criado com sucesso!');
    }

    /**
     * Mostrar certificado
     */
    public function show(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);
        
        return view('admin.ebd.certificados.show', compact('certificado'));
    }

    /**
     * Formulário para editar certificado
     */
    public function edit(EbdCertificado $certificado)
    {
        $alunos = EbdAluno::with('membro')->ativos()->get();
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = [
            'conclusao' => 'Conclusão de Curso',
            'participacao' => 'Participação',
            'excelencia' => 'Excelência',
            'presenca' => 'Presença',
            'avaliacao' => 'Avaliação'
        ];

        return view('admin.ebd.certificados.edit', compact('certificado', 'alunos', 'avaliacoes', 'tipos'));
    }

    /**
     * Atualizar certificado
     */
    public function update(Request $request, EbdCertificado $certificado)
    {
        $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'avaliacao_id' => 'nullable|exists:ebd_avaliacoes,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao',
            'descricao' => 'nullable|string|max:1000',
            'conteudo' => 'required|string',
            'carga_horaria' => 'nullable|integer|min:1',
            'nota_final' => 'nullable|numeric|min:0|max:100',
            'data_emissao' => 'required|date',
            'data_validade' => 'nullable|date|after:data_emissao',
            'assinatura_coordenador' => 'nullable|string|max:255',
            'assinatura_pastor' => 'nullable|string|max:255',
            'ativo' => 'boolean'
        ]);

        $certificado->update($request->all());

        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado atualizado com sucesso!');
    }

    /**
     * Excluir certificado
     */
    public function destroy(EbdCertificado $certificado)
    {
        $certificado->delete();

        return redirect()->route('admin.ebd.certificados.index')
            ->with('success', 'Certificado excluído com sucesso!');
    }

    /**
     * Gerar certificado automaticamente
     */
    public function gerarAutomatico(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id',
            'tipo' => 'required|in:conclusao,participacao,excelencia,presenca,avaliacao'
        ]);

        $aluno = EbdAluno::with(['membro', 'turma', 'avaliacoes'])->ativos()->findOrFail($request->aluno_id);

        try {
            $certificado = $this->criarCertificadoAutomatico($aluno, $request->tipo);

            return redirect()->route('admin.ebd.certificados.show', $certificado)
                ->with('success', 'Certificado gerado automaticamente com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao gerar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Baixar certificado em PDF
     */
    public function download(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);

        try {
            $pdf = PdfService::gerarCertificadoEbd($certificado);
            
            $filename = "certificado_{$certificado->codigo}.pdf";
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Visualizar certificado em PDF
     */
    public function visualizar(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);

        try {
            $pdf = PdfService::gerarCertificadoEbd($certificado);
            
            return $pdf->stream("certificado_{$certificado->codigo}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Criar certificado automaticamente
     */
    private function criarCertificadoAutomatico($aluno, $tipo)
    {
        $titulo = $this->gerarTituloAutomatico($tipo, $aluno);
        $conteudo = $this->gerarConteudoAutomatico($tipo, $aluno);
        $cargaHoraria = $this->calcularCargaHoraria($aluno);
        $notaFinal = $this->calcularNotaFinal($aluno);

        $codigo = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');

        return EbdCertificado::create([
            'aluno_id' => $aluno->id,
            'titulo' => $titulo,
            'tipo' => $tipo,
            'descricao' => "Certificado gerado automaticamente para {$aluno->membro->nome}",
            'conteudo' => $conteudo,
            'carga_horaria' => $cargaHoraria,
            'nota_final' => $notaFinal,
            'data_emissao' => now(),
            'data_validade' => now()->addYears(5),
            'codigo' => $codigo,
            'ativo' => true
        ]);
    }

    /**
     * Gerar título automático
     */
    private function gerarTituloAutomatico($tipo, $aluno)
    {
        $nomes = [
            'conclusao' => 'Conclusão do Curso',
            'participacao' => 'Participação no Curso',
            'excelencia' => 'Excelência Acadêmica',
            'presenca' => 'Certificado de Presença',
            'avaliacao' => 'Certificado de Avaliação'
        ];

        return $nomes[$tipo] ?? 'Certificado';
    }

    /**
     * Gerar conteúdo automático
     */
    private function gerarConteudoAutomatico($tipo, $aluno)
    {
        $nome = $aluno->membro->nome;
        $turma = $aluno->turma->nome ?? 'Turma EBD';
        $data = now()->format('d/m/Y');

        $conteudos = [
            'conclusao' => "Certificamos que {$nome} concluiu com êxito o curso da Escola Bíblica Dominical na {$turma}, demonstrando dedicação e compromisso com o estudo da Palavra de Deus.",
            'participacao' => "Certificamos que {$nome} participou ativamente do curso da Escola Bíblica Dominical na {$turma}, contribuindo para o crescimento espiritual da comunidade.",
            'excelencia' => "Certificamos que {$nome} demonstrou excelência acadêmica no curso da Escola Bíblica Dominical na {$turma}, destacando-se pelo seu desempenho e dedicação.",
            'presenca' => "Certificamos que {$nome} manteve excelente frequência no curso da Escola Bíblica Dominical na {$turma}, demonstrando compromisso com o aprendizado.",
            'avaliacao' => "Certificamos que {$nome} participou das avaliações do curso da Escola Bíblica Dominical na {$turma}, demonstrando conhecimento e compreensão dos conteúdos."
        ];

        return $conteudos[$tipo] ?? "Certificamos que {$nome} participou do curso da Escola Bíblica Dominical na {$turma}.";
    }

    /**
     * Calcular carga horária
     */
    private function calcularCargaHoraria($aluno)
    {
        // Implementar cálculo baseado nas aulas e presenças
        return $aluno->presencas()->count() * 2; // 2 horas por aula
    }

    /**
     * Calcular nota final
     */
    private function calcularNotaFinal($aluno)
    {
        $notas = $aluno->notas;
        if ($notas->count() > 0) {
            return round($notas->avg('nota'), 1);
        }
        return null;
    }

    /**
     * Exportar certificados
     */
    public function export(Request $request)
    {
        $request->validate([
            'formato' => 'required|in:csv,xlsx,pdf',
            'filtros' => 'nullable|array'
        ]);

        $query = EbdCertificado::with(['aluno.membro', 'avaliacao']);

        // Aplicar filtros
        if ($request->filled('filtros.aluno_id')) {
            $query->where('aluno_id', $request->filtros['aluno_id']);
        }

        if ($request->filled('filtros.tipo')) {
            $query->where('tipo', $request->filtros['tipo']);
        }

        if ($request->filled('filtros.status')) {
            $query->where('ativo', $request->filtros['status'] === 'ativo');
        }

        $certificados = $query->get();

        if ($request->formato === 'csv') {
            return $this->exportarCSV($certificados);
        } elseif ($request->formato === 'xlsx') {
            return $this->exportarExcel($certificados);
        } else {
            return $this->exportarPDF($certificados);
        }
    }

    /**
     * Exportar CSV
     */
    private function exportarCSV($certificados)
    {
        $filename = "certificados_ebd_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($certificados) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalhos
            fputcsv($file, ['Código', 'Aluno', 'Título', 'Tipo', 'Data Emissão', 'Data Validade', 'Status']);
            
            // Dados
            foreach ($certificados as $certificado) {
                fputcsv($file, [
                    $certificado->codigo,
                    $certificado->aluno->membro->nome ?? 'N/A',
                    $certificado->titulo,
                    $certificado->tipo,
                    $certificado->data_emissao->format('d/m/Y'),
                    $certificado->data_validade ? $certificado->data_validade->format('d/m/Y') : 'N/A',
                    $certificado->ativo ? 'Ativo' : 'Inativo'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar Excel
     */
    private function exportarExcel($certificados)
    {
        // Implementar exportação Excel se necessário
        throw new \Exception('Exportação Excel ainda não implementada');
    }

    /**
     * Exportar PDF
     */
    private function exportarPDF($certificados)
    {
        // Implementar exportação PDF se necessário
        throw new \Exception('Exportação PDF ainda não implementada');
    }
} 