<?php

namespace App\Services;

use App\Models\EbdTurma;
use App\Models\EbdProfessor;
use App\Models\EbdAluno;
use App\Models\EbdLicao;
use App\Models\EbdAula;
use App\Models\EbdAvaliacao;
use App\Models\EbdQuestao;
use App\Models\EbdCertificado;
use App\Models\Membro;
use Illuminate\Http\Request;
use App\Services\PdfService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbdService
{
    // --- Métodos de Turmas ---
    public function getAllTurmas() { return EbdTurma::orderBy('nome')->get(); }
    public function createTurma(array $data): EbdTurma { return EbdTurma::create($data); }
    public function updateTurma(EbdTurma $turma, array $data): bool { return $turma->update($data); }
    public function deleteTurma(EbdTurma $turma): ?bool { return $turma->delete(); }

    // --- Métodos de Professores ---
    public function getAllProfessores() { return EbdProfessor::with(['membro', 'turma'])->orderBy('created_at', 'desc')->get(); }
    public function getProfessorFormData(): array { $membros = Membro::orderBy('nome')->get(); $turmas = EbdTurma::ativas()->orderBy('nome')->get(); return compact('membros', 'turmas'); }
    public function createProfessor(array $data): EbdProfessor { return EbdProfessor::create($data); }
    public function updateProfessor(EbdProfessor $professor, array $data): bool { return $professor->update($data); }
    public function deleteProfessor(EbdProfessor $professor): ?bool { return $professor->delete(); }

    // --- Métodos de Alunos ---
    public function getAllAlunos() { return EbdAluno::with(['membro', 'turma'])->orderBy('nome')->get(); }
    public function getAlunoFormData(): array { $membros = Membro::orderBy('nome')->get(); $turmas = EbdTurma::ativas()->orderBy('nome')->get(); return compact('membros', 'turmas'); }
    public function createAluno(array $data): EbdAluno { return EbdAluno::create($data); }
    public function updateAluno(EbdAluno $aluno, array $data): bool { return $aluno->update($data); }
    public function deleteAluno(EbdAluno $aluno): ?bool { return $aluno->delete(); }

    // --- Métodos de Lições ---
    public function getAllLicoes() { return EbdLicao::orderBy('titulo')->get(); }
    public function createLicao(array $data): EbdLicao { return EbdLicao::create($data); }
    public function updateLicao(EbdLicao $licao, array $data): bool { return $licao->update($data); }
    public function deleteLicao(EbdLicao $licao): ?bool { return $licao->delete(); }

    // --- Métodos de Aulas ---
    public function getAllAulas() { return EbdAula::with(['turma', 'licao', 'professor'])->orderBy('data_aula', 'desc')->get(); }
    public function getAulaFormData(): array { $turmas = EbdTurma::ativas()->orderBy('nome')->get(); $licoes = EbdLicao::ativas()->orderBy('titulo')->get(); $professores = EbdProfessor::ativos()->with('membro')->get(); return compact('turmas', 'licoes', 'professores'); }
    public function createAula(array $data): EbdAula { $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio']; $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim']; return EbdAula::create($data); }
    public function updateAula(EbdAula $aula, array $data): bool { $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio']; $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim']; return $aula->update($data); }
    public function deleteAula(EbdAula $aula): ?bool { return $aula->delete(); }

    // --- Métodos de Avaliações ---
    public function getAllAvaliacoes() { return EbdAvaliacao::with(['aula.turma', 'aula.licao'])->orderBy('created_at', 'desc')->get(); }
    public function getAvaliacaoFormData(): array { $aulas = EbdAula::with(['turma', 'licao'])->orderBy('data_aula', 'desc')->get(); return compact('aulas'); }
    public function createAvaliacao(array $data): EbdAvaliacao { $data['obrigatoria'] = $data['obrigatoria'] ?? false; $data['permite_grupos'] = $data['permite_grupos'] ?? false; if (!$data['permite_grupos']) { $data['modo_avaliacao'] = 'individual'; } return EbdAvaliacao::create($data); }
    public function updateAvaliacao(EbdAvaliacao $avaliacao, array $data): bool { $data['obrigatoria'] = $data['obrigatoria'] ?? false; $data['permite_grupos'] = $data['permite_grupos'] ?? false; if (!$data['permite_grupos']) { $data['modo_avaliacao'] = 'individual'; } return $avaliacao->update($data); }
    public function deleteAvaliacao(EbdAvaliacao $avaliacao): ?bool { return $avaliacao->delete(); }
    public function getAvaliacaoRelatorioData(EbdAvaliacao $avaliacao): array {
        $avaliacao->load(['aula.turma.alunos', 'aula.licao', 'questoes', 'notas.aluno', 'avaliacoesGrupo.grupo.membros']);
        $notas = $avaliacao->notas;
        $totalAlunos = $avaliacao->aula->turma->alunos->count();
        $alunosQueFizeram = $notas->count();
        $distribuicaoNotas = ['excelente' => $notas->where('percentual', '>=', 90)->count(), 'bom' => $notas->whereBetween('percentual', [70, 89])->count(), 'regular' => $notas->whereBetween('percentual', [50, 69])->count(), 'insuficiente' => $notas->where('percentual', '<', 50)->count()];
        $estatisticasGrupos = null;
        if ($avaliacao->permiteGrupos()) {
            $avaliacoesGrupo = $avaliacao->avaliacoesGrupo;
            $gruposConcluidos = $avaliacoesGrupo->where('status', 'concluida');
            $estatisticasGrupos = ['total_grupos' => $avaliacoesGrupo->count(), 'grupos_concluidos' => $gruposConcluidos->count(), 'grupos_em_andamento' => $avaliacoesGrupo->where('status', 'em_andamento')->count(), 'grupos_pendentes' => $avaliacoesGrupo->where('status', 'pendente')->count(), 'media_grupos' => $gruposConcluidos->avg('percentual') ?? 0, 'tempo_medio_grupos' => $gruposConcluidos->avg('tempo_gasto_minutos') ?? 0, 'total_alunos_grupos' => $avaliacoesGrupo->sum(fn($ag) => $ag->grupo->membros->count()), 'distribuicao_notas' => ['excelente' => $gruposConcluidos->where('percentual', '>=', 90)->count(), 'bom' => $gruposConcluidos->whereBetween('percentual', [70, 89])->count(), 'regular' => $gruposConcluidos->whereBetween('percentual', [50, 69])->count(), 'insuficiente' => $gruposConcluidos->where('percentual', '<', 50)->count()]];
        }
        return ['avaliacao' => $avaliacao, 'totalAlunos' => $totalAlunos, 'alunosQueFizeram' => $alunosQueFizeram, 'mediaGeral' => $notas->avg('percentual') ?? 0, 'maiorNota' => $notas->max('nota') ?? 0, 'menorNota' => $notas->min('nota') ?? 0, 'distribuicaoNotas' => $distribuicaoNotas, 'estatisticasGrupos' => $estatisticasGrupos];
    }

    // --- MÉTODOS DE QUESTÕES ---
    public function getQuestoes(Request $request): array
    {
        $query = EbdQuestao::with(['avaliacao']);
        if ($request->filled('avaliacao_id')) { $query->where('avaliacao_id', $request->avaliacao_id); }
        if ($request->filled('tipo')) { $query->where('tipo', $request->tipo); }
        if ($request->filled('search')) { $query->where('pergunta', 'like', '%' . $request->search . '%'); }
        $questoes = $query->orderBy('created_at', 'desc')->paginate(15);
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        return compact('questoes', 'avaliacoes');
    }

    public function getQuestaoFormData(Request $request): array
    {
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = ['multipla_escolha' => 'Múltipla Escolha', 'verdadeiro_falso' => 'Verdadeiro/Falso', 'dissertativa' => 'Dissertativa', 'completar' => 'Completar', 'associacao' => 'Associação'];
        $avaliacaoSelecionada = $request->has('avaliacao_id') ? EbdAvaliacao::find($request->avaliacao_id) : null;
        return compact('avaliacoes', 'tipos', 'avaliacaoSelecionada');
    }

    public function createQuestao(array $data): EbdQuestao
    {
        if (($data['tipo'] === 'multipla_escolha' || $data['tipo'] === 'associacao') && isset($data['opcoes'])) {
            $data['opcoes'] = array_filter($data['opcoes'], fn($o) => !empty(trim($o)));
        }
        return EbdQuestao::create($data);
    }

    public function updateQuestao(EbdQuestao $questao, array $data): bool
    {
        if (($data['tipo'] === 'multipla_escolha' || $data['tipo'] === 'associacao') && isset($data['opcoes'])) {
            $data['opcoes'] = array_filter($data['opcoes'], fn($o) => !empty(trim($o)));
        }
        return $questao->update($data);
    }

    public function deleteQuestao(EbdQuestao $questao): void
    {
        if ($questao->respostasAlunos()->count() > 0) {
            throw new \Exception('Não é possível excluir uma questão que já possui respostas de alunos.');
        }
        $questao->delete();
    }

    // --- MÉTODOS DE CERTIFICADOS ---
    public function getCertificados(Request $request): array
    {
        $query = EbdCertificado::with(['aluno.membro', 'avaliacao']);
        if ($request->filled('aluno_id')) { $query->where('aluno_id', $request->aluno_id); }
        if ($request->filled('tipo')) { $query->where('tipo', $request->tipo); }
        if ($request->filled('status')) { $query->where('ativo', $request->status === 'ativo'); }
        if ($request->filled('search')) {
            $query->whereHas('aluno.membro', fn($q) => $q->where('nome', 'like', '%' . $request->search . '%'));
        }
        $certificados = $query->orderBy('created_at', 'desc')->paginate(15);
        $alunos = EbdAluno::with('membro')->ativos()->get();
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        return compact('certificados', 'alunos', 'avaliacoes');
    }

    public function getCertificadoFormData(): array
    {
        $alunos = EbdAluno::with('membro')->ativos()->get();
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = ['conclusao' => 'Conclusão de Curso', 'participacao' => 'Participação', 'excelencia' => 'Excelência', 'presenca' => 'Presença', 'avaliacao' => 'Avaliação'];
        return compact('alunos', 'avaliacoes', 'tipos');
    }

    public function createCertificado(array $data): EbdCertificado
    {
        $data['codigo'] = 'CERT-' . strtoupper(Str::random(10)) . '-' . date('Y');
        return EbdCertificado::create($data);
    }

    public function updateCertificado(EbdCertificado $certificado, array $data): bool
    {
        return $certificado->update($data);
    }

    public function deleteCertificado(EbdCertificado $certificado): void
    {
        $certificado->delete();
    }

    public function gerarCertificadoAutomatico(Request $request): EbdCertificado
    {
        $aluno = EbdAluno::with(['membro', 'turma', 'avaliacoes', 'presencas'])->ativos()->findOrFail($request->aluno_id);
        $tipo = $request->tipo;
        $codigo = 'CERT-' . strtoupper(Str::random(10)) . '-' . date('Y');

        return EbdCertificado::create([
            'aluno_id' => $aluno->id,
            'titulo' => $this->gerarTituloAutomatico($tipo, $aluno),
            'tipo' => $tipo,
            'descricao' => "Certificado gerado automaticamente para {$aluno->membro->nome}",
            'conteudo' => $this->gerarConteudoAutomatico($tipo, $aluno),
            'carga_horaria' => $this->calcularCargaHoraria($aluno),
            'nota_final' => $this->calcularNotaFinal($aluno),
            'data_emissao' => now(),
            'data_validade' => now()->addYears(5),
            'codigo' => $codigo,
            'ativo' => true
        ]);
    }

    public function downloadCertificado(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);
        // A geração do PDF depende de um PdfService que não está no escopo da refatoração
        // mas a chamada ao serviço será mantida.
        // $pdf = PdfService::gerarCertificadoEbd($certificado);
        // return $pdf->download("certificado_{$certificado->codigo}.pdf");
        throw new \Exception("A geração de PDF precisa ser implementada ou conectada.");
    }

    public function visualizarCertificado(EbdCertificado $certificado)
    {
        $certificado->load(['aluno.membro', 'avaliacao']);
        // $pdf = PdfService::gerarCertificadoEbd($certificado);
        // return $pdf->stream("certificado_{$certificado->codigo}.pdf");
        throw new \Exception("A geração de PDF precisa ser implementada ou conectada.");
    }

    // --- MÉTODOS AUXILIARES PRIVADOS ---
    private function gerarTituloAutomatico($tipo, $aluno): string { $nomes = ['conclusao' => 'Conclusão de Curso', 'participacao' => 'Participação no Curso', 'excelencia' => 'Excelência Acadêmica', 'presenca' => 'Certificado de Presença', 'avaliacao' => 'Certificado de Avaliação']; return $nomes[$tipo] ?? 'Certificado'; }
    private function gerarConteudoAutomatico($tipo, $aluno): string { $nome = $aluno->membro->nome; $turma = $aluno->turma->nome ?? 'Turma EBD'; $conteudos = ['conclusao' => "Certificamos que {$nome} concluiu com êxito o curso da Escola Bíblica Dominical na {$turma}, demonstrando dedicação e compromisso com o estudo da Palavra de Deus.", 'participacao' => "Certificamos que {$nome} participou ativamente do curso da Escola Bíblica Dominical na {$turma}, contribuindo para o crescimento espiritual da comunidade.", 'excelencia' => "Certificamos que {$nome} demonstrou excelência acadêmica no curso da Escola Bíblica Dominical na {$turma}, destacando-se pelo seu desempenho e dedicação.", 'presenca' => "Certificamos que {$nome} manteve excelente frequência no curso da Escola Bíblica Dominical na {$turma}, demonstrando compromisso com o aprendizado.", 'avaliacao' => "Certificamos que {$nome} participou das avaliações do curso da Escola Bíblica Dominical na {$turma}, demonstrando conhecimento e compreensão dos conteúdos."]; return $conteudos[$tipo] ?? "Certificamos que {$nome} participou do curso da Escola Bíblica Dominical na {$turma}."; }
    private function calcularCargaHoraria($aluno): int { return isset($aluno->presencas) ? $aluno->presencas->count() * 2 : 0; }
    private function calcularNotaFinal($aluno): ?float { $notas = $aluno->notas; return isset($notas) && $notas->count() > 0 ? round($notas->avg('nota'), 1) : null; }
}