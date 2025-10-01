<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdTurma;
use App\Models\EbdAluno;
use App\Models\EbdAula;
use App\Models\EbdAvaliacao;
use App\Models\EbdPresenca;
use App\Models\EbdNota;
use App\Exports\EbdPresencaExport;
use App\Exports\EbdNotasExport;
use App\Exports\EbdProgressoExport;
use App\Exports\EbdGeralExport;
use Maatwebsite\Excel\Facades\Excel;

class EbdRelatorioController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $turmas = EbdTurma::ativas()->withCount(['alunos', 'aulas'])->get();
        $totalAlunos = EbdAluno::ativos()->count();
        $totalAulas = EbdAula::realizadas()->count();
        $totalAvaliacoes = EbdAvaliacao::ativas()->count();
        $totalPresencas = EbdPresenca::where('status', 'presente')->count();
        
        return view('admin.ebd.relatorios.index', compact(
            'turmas', 
            'totalAlunos', 
            'totalAulas', 
            'totalAvaliacoes', 
            'totalPresencas'
        ));
    }

    public function exportar(Request $request)
    {
        $tipo = $request->get('tipo', 'geral');
        $turmaId = $request->get('turma_id');
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');

        // Validar tipo de relatório
        $tiposValidos = ['presenca', 'notas', 'progresso', 'geral'];
        if (!in_array($tipo, $tiposValidos)) {
            return back()->with('error', 'Tipo de relatório inválido.');
        }

        $filename = $this->getFilename($tipo, $turmaId, $dataInicio, $dataFim);

        switch ($tipo) {
            case 'presenca':
                return Excel::download(new EbdPresencaExport($turmaId, $dataInicio, $dataFim), $filename);
            case 'notas':
                return Excel::download(new EbdNotasExport($turmaId, $dataInicio, $dataFim), $filename);
            case 'progresso':
                return Excel::download(new EbdProgressoExport($turmaId), $filename);
            default:
                return Excel::download(new EbdGeralExport($turmaId, $dataInicio, $dataFim), $filename);
        }
    }

    private function getFilename($tipo, $turmaId, $dataInicio, $dataFim)
    {
        $turma = null;
        if ($turmaId) {
            $turma = EbdTurma::find($turmaId);
        }

        $tipoFormatado = match($tipo) {
            'presenca' => 'Presenca',
            'notas' => 'Notas',
            'progresso' => 'Progresso',
            default => 'Geral'
        };

        $turmaNome = $turma ? '_' . str_replace(' ', '_', $turma->nome) : '';
        $dataInicioFormatada = $dataInicio ? '_' . date('Y-m-d', strtotime($dataInicio)) : '';
        $dataFimFormatada = $dataFim ? '_' . date('Y-m-d', strtotime($dataFim)) : '';
        
        return "Relatorio_EBD_{$tipoFormatado}{$turmaNome}{$dataInicioFormatada}{$dataFimFormatada}.xlsx";
    }

    public function preview(Request $request)
    {
        $tipo = $request->get('tipo', 'geral');
        $turmaId = $request->get('turma_id');
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');

        // Validar tipo de relatório
        $tiposValidos = ['presenca', 'notas', 'progresso', 'geral'];
        if (!in_array($tipo, $tiposValidos)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de relatório inválido.'
            ]);
        }

        $dados = $this->getPreviewData($tipo, $turmaId, $dataInicio, $dataFim);

        return response()->json([
            'success' => true,
            'data' => $dados
        ]);
    }

    private function getPreviewData($tipo, $turmaId, $dataInicio, $dataFim)
    {
        switch ($tipo) {
            case 'presenca':
                return $this->getPresencaPreview($turmaId, $dataInicio, $dataFim);
            case 'notas':
                return $this->getNotasPreview($turmaId, $dataInicio, $dataFim);
            case 'progresso':
                return $this->getProgressoPreview($turmaId);
            default:
                return $this->getGeralPreview($turmaId, $dataInicio, $dataFim);
        }
    }

    private function getPresencaPreview($turmaId, $dataInicio, $dataFim)
    {
        $query = EbdPresenca::with(['aluno', 'aula.turma', 'aula.licao']);
        
        if ($turmaId) {
            $query->whereHas('aula', function($q) use ($turmaId) {
                $q->where('turma_id', $turmaId);
            });
        }
        
        if ($dataInicio) {
            $query->whereHas('aula', function($q) use ($dataInicio) {
                $q->where('data_aula', '>=', $dataInicio);
            });
        }
        
        if ($dataFim) {
            $query->whereHas('aula', function($q) use ($dataFim) {
                $q->where('data_aula', '<=', $dataFim);
            });
        }
        
        $presencas = $query->orderBy('created_at', 'desc')->limit(10)->get();
        
        return [
            'total' => $query->count(),
            'amostra' => $presencas->map(function($presenca) {
                return [
                    'aluno' => $presenca->aluno->nome ?? 'Aluno não encontrado',
                    'turma' => $presenca->aula->turma->nome ?? 'Turma não encontrada',
                    'data_aula' => $presenca->aula->data_aula ? $presenca->aula->data_aula->format('d/m/Y') : 'Data não informada',
                    'licao' => $presenca->aula->licao->titulo ?? 'Lição não encontrada',
                    'status' => $this->getStatusFormatado($presenca->status),
                    'observacoes' => $presenca->observacoes ?? 'Nenhuma observação'
                ];
            })
        ];
    }

    private function getNotasPreview($turmaId, $dataInicio, $dataFim)
    {
        $query = EbdNota::with(['aluno', 'avaliacao.aula.turma', 'avaliacao.aula.licao']);
        
        if ($turmaId) {
            $query->whereHas('avaliacao.aula', function($q) use ($turmaId) {
                $q->where('turma_id', $turmaId);
            });
        }
        
        $notas = $query->orderBy('created_at', 'desc')->limit(10)->get();
        
        return [
            'total' => $query->count(),
            'amostra' => $notas->map(function($nota) {
                return [
                    'aluno' => $nota->aluno->nome ?? 'Aluno não encontrado',
                    'turma' => $nota->avaliacao->aula->turma->nome ?? 'Turma não encontrada',
                    'avaliacao' => $nota->avaliacao->titulo ?? 'Avaliação não encontrada',
                    'tipo' => $nota->avaliacao->tipo_formatado ?? 'Tipo não informado',
                    'nota' => $nota->nota,
                    'percentual' => number_format($nota->percentual, 2) . '%',
                    'observacoes' => $nota->observacoes ?? 'Nenhuma observação'
                ];
            })
        ];
    }

    private function getProgressoPreview($turmaId)
    {
        $query = EbdAluno::with(['turma', 'presencas.aula', 'notas.avaliacao']);
        
        if ($turmaId) {
            $query->where('turma_id', $turmaId);
        }
        
        $alunos = $query->where('status', 'ativo')->orderBy('nome')->limit(10)->get();
        
        return [
            'total' => $query->where('status', 'ativo')->count(),
            'amostra' => $alunos->map(function($aluno) {
                $totalAulas = $aluno->presencas->count();
                $presencas = $aluno->presencas->where('status', 'presente')->count();
                $percentualPresenca = $totalAulas > 0 ? ($presencas / $totalAulas) * 100 : 0;
                $totalAvaliacoes = $aluno->notas->count();
                $mediaGeral = $totalAvaliacoes > 0 ? $aluno->notas->avg('percentual') : 0;

                return [
                    'aluno' => $aluno->nome,
                    'turma' => $aluno->turma->nome ?? 'Turma não encontrada',
                    'total_aulas' => $totalAulas,
                    'presencas' => $presencas,
                    'percentual_presenca' => number_format($percentualPresenca, 2) . '%',
                    'total_avaliacoes' => $totalAvaliacoes,
                    'media_geral' => number_format($mediaGeral, 2) . '%'
                ];
            })
        ];
    }

    private function getGeralPreview($turmaId, $dataInicio, $dataFim)
    {
        $totalTurmas = EbdTurma::ativas()->count();
        $totalAlunos = EbdAluno::ativos()->count();
        $totalAulas = EbdAula::realizadas()->count();
        $totalAvaliacoes = EbdAvaliacao::count();
        $totalPresencas = EbdPresenca::where('status', 'presente')->count();
        $totalFaltas = EbdPresenca::where('status', 'ausente')->count();
        $mediaPresenca = $totalPresencas + $totalFaltas > 0 ? ($totalPresencas / ($totalPresencas + $totalFaltas)) * 100 : 0;

        return [
            'estatisticas_gerais' => [
                'total_turmas' => $totalTurmas,
                'total_alunos' => $totalAlunos,
                'total_aulas' => $totalAulas,
                'total_avaliacoes' => $totalAvaliacoes,
                'total_presencas' => $totalPresencas,
                'total_faltas' => $totalFaltas,
                'media_presenca' => number_format($mediaPresenca, 2) . '%'
            ],
            'turmas' => EbdTurma::ativas()->withCount(['alunos', 'aulas'])->limit(5)->get()->map(function($turma) {
                $alunosTurma = $turma->alunos()->where('status', 'ativo')->count();
                $aulasTurma = $turma->aulas()->where('status', 'realizada')->count();
                $presencasTurma = EbdPresenca::whereHas('aula', function($q) use ($turma) {
                    $q->where('turma_id', $turma->id);
                })->where('status', 'presente')->count();
                $avaliacoesTurma = EbdAvaliacao::whereHas('aula', function($q) use ($turma) {
                    $q->where('turma_id', $turma->id);
                })->count();

                return [
                    'nome' => $turma->nome,
                    'alunos' => $alunosTurma,
                    'aulas' => $aulasTurma,
                    'presencas' => $presencasTurma,
                    'avaliacoes' => $avaliacoesTurma
                ];
            })
        ];
    }

    private function getStatusFormatado($status)
    {
        return match($status) {
            'presente' => 'Presente',
            'ausente' => 'Ausente',
            'justificado' => 'Justificado',
            'atrasado' => 'Atrasado',
            default => ucfirst($status)
        };
    }
} 