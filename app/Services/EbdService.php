<?php

namespace App\Services;

use App\Models\{
    Disciplina,
    Atividade,
    EntregaAtividade,
    EbdTurma,
    EbdProfessor,
    EbdAluno,
    EbdLicao,
    EbdAula,
    EbdAvaliacao,
    EbdQuestao,
    EbdCertificado,
    Membro,
    User
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbdService
{
    // Métodos existentes (Turmas, Professores, etc.) permanecem aqui.
    // ...

    // --- MÉTODOS DE DISCIPLINAS ---

    public function getAllDisciplinas()
    {
        return Disciplina::with('professorResponsavel')->orderBy('nome')->get();
    }

    public function getDisciplinaFormData(): array
    {
        $professores = User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'professor']))->orderBy('name')->get();
        return compact('professores');
    }

    public function createDisciplina(array $data): Disciplina
    {
        $data['codigo_disciplina'] = $data['codigo_disciplina'] ?? strtoupper(Str::random(8));
        return Disciplina::create($data);
    }

    public function updateDisciplina(Disciplina $disciplina, array $data): bool
    {
        return $disciplina->update($data);
    }

    public function deleteDisciplina(Disciplina $disciplina): ?bool
    {
        // Adicionar verificação se a disciplina tem lições associadas antes de excluir.
        if ($disciplina->licoes()->count() > 0) {
            throw new \Exception('Não é possível excluir uma disciplina que já possui lições.');
        }
        return $disciplina->delete();
    }


    // --- MÉTODOS DE ATIVIDADES PRÁTICAS ---

    public function getAtividadesPorLicao(EbdLicao $licao)
    {
        return $licao->atividades()->orderBy('created_at')->get();
    }

    public function createAtividade(array $data): Atividade
    {
        return Atividade::create($data);
    }

    public function updateAtividade(Atividade $atividade, array $data): bool
    {
        return $atividade->update($data);
    }

    public function deleteAtividade(Atividade $atividade): ?bool
    {
        return $atividade->delete();
    }

    // --- MÉTODOS DE ENTREGA E AVALIAÇÃO DE ATIVIDADES ---

    public function getEntregasPorAtividade(Atividade $atividade)
    {
        return $atividade->entregas()->with('aluno.user')->orderBy('data_entrega', 'desc')->get();
    }

    public function submitAtividade(Atividade $atividade, EbdAluno $aluno, array $data): EntregaAtividade
    {
        if (isset($data['arquivo'])) {
            $data['arquivo_path'] = $data['arquivo']->store('entregas_atividades', 'public');
        }
        $data['aluno_id'] = $aluno->id;
        $data['data_entrega'] = now();
        $data['status'] = 'entregue';

        return $atividade->entregas()->create($data);
    }

    public function avaliarEntrega(EntregaAtividade $entrega, array $data): bool
    {
        $data['data_avaliacao'] = now();
        $data['status'] = 'avaliado';
        return $entrega->update($data);
    }

    // --- MÉTODOS DE PROGRESSO DO ALUNO ---

    public function getProgressoAluno(EbdAluno $aluno)
    {
        // Lógica para calcular o progresso do aluno por disciplina
        // Exemplo simples:
        $disciplinas = Disciplina::with('licoes.atividades.entregas')->get();
        $progresso = [];

        foreach ($disciplinas as $disciplina) {
            $totalAtividades = $disciplina->licoes->sum(fn($licao) => $licao->atividades->count());
            $atividadesEntregues = $disciplina->licoes->flatMap->atividades->flatMap->entregas
                ->where('aluno_id', $aluno->id)
                ->count();

            $progresso[$disciplina->nome] = [
                'total_atividades' => $totalAtividades,
                'atividades_entregues' => $atividadesEntregues,
                'percentual' => $totalAtividades > 0 ? round(($atividadesEntregues / $totalAtividades) * 100) : 0,
            ];
        }

        return $progresso;
    }

    // --- Métodos de Turmas, Professores, Alunos, Lições, etc. (já existentes) ---
    public function getAllTurmas() { return EbdTurma::orderBy('nome')->get(); }
    public function createTurma(array $data): EbdTurma { return EbdTurma::create($data); }
    public function updateTurma(EbdTurma $turma, array $data): bool { return $turma->update($data); }
    public function deleteTurma(EbdTurma $turma): ?bool { return $turma->delete(); }
    public function getAllProfessores() { return EbdProfessor::with(['membro', 'turma'])->orderBy('created_at', 'desc')->get(); }
    public function getProfessorFormData(): array { $membros = Membro::orderBy('nome')->get(); $turmas = EbdTurma::ativas()->orderBy('nome')->get(); return compact('membros', 'turmas'); }
    public function createProfessor(array $data): EbdProfessor { return EbdProfessor::create($data); }
    public function updateProfessor(EbdProfessor $professor, array $data): bool { return $professor->update($data); }
    public function deleteProfessor(EbdProfessor $professor): ?bool { return $professor->delete(); }
    public function getAllAlunos() { return EbdAluno::with(['membro', 'turma'])->orderBy('nome')->get(); }
    public function getAlunoFormData(): array { $membros = Membro::orderBy('nome')->get(); $turmas = EbdTurma::ativas()->orderBy('nome')->get(); return compact('membros', 'turmas'); }
    public function createAluno(array $data): EbdAluno { return EbdAluno::create($data); }
    public function updateAluno(EbdAluno $aluno, array $data): bool { return $aluno->update($data); }
    public function deleteAluno(EbdAluno $aluno): ?bool { return $aluno->delete(); }
    public function getAllLicoes() { return EbdLicao::orderBy('titulo')->get(); }
    public function createLicao(array $data): EbdLicao { return EbdLicao::create($data); }
    public function updateLicao(EbdLicao $licao, array $data): bool { return $licao->update($data); }
    public function deleteLicao(EbdLicao $licao): ?bool { return $licao->delete(); }
    public function getAllAulas() { return EbdAula::with(['turma', 'licao', 'professor'])->orderBy('data_aula', 'desc')->get(); }
    public function getAulaFormData(): array { $turmas = EbdTurma::ativas()->orderBy('nome')->get(); $licoes = EbdLicao::ativas()->orderBy('titulo')->get(); $professores = EbdProfessor::ativos()->with('membro')->get(); return compact('turmas', 'licoes', 'professores'); }
    public function createAula(array $data): EbdAula { $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio']; $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim']; return EbdAula::create($data); }
    public function updateAula(EbdAula $aula, array $data): bool { $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio']; $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim']; return $aula->update($data); }
    public function deleteAula(EbdAula $aula): ?bool { return $aula->delete(); }
}