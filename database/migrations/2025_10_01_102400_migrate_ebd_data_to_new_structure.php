<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\EBD\Turma;
use App\Models\EBD\Grupo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Migrate Turmas
        $oldTurmas = DB::table('ebd_turmas_old')->get();
        foreach ($oldTurmas as $oldTurma) {
            Turma::create([
                'id' => $oldTurma->id,
                'nome' => $oldTurma->nome,
                'descricao' => $oldTurma->descricao,
                'faixa_etaria' => $oldTurma->faixa_etaria,
                'cor' => $oldTurma->cor,
                'capacidade_maxima' => $oldTurma->capacidade_maxima,
                'ativo' => $oldTurma->ativo,
                'created_at' => $oldTurma->created_at,
                'updated_at' => $oldTurma->updated_at,
            ]);
        }

        // Step 2: Migrate Alunos to ebd_turma_user pivot
        $oldAlunos = DB::table('ebd_alunos_old')->whereNotNull('membro_id')->get();
        foreach ($oldAlunos as $oldAluno) {
            $user = User::whereHas('profile', function ($query) use ($oldAluno) {
                $query->where('id', $oldAluno->membro_id);
            })->first();

            if ($user) {
                DB::table('ebd_turma_user')->insert([
                    'turma_id' => $oldAluno->turma_id,
                    'user_id' => $user->id,
                    'funcao' => 'aluno',
                    'created_at' => $oldAluno->created_at,
                    'updated_at' => $oldAluno->updated_at,
                ]);
            }
        }

        // Step 3: Migrate Professores to ebd_turma_user pivot
        $oldProfessores = DB::table('ebd_professores_old')->whereNotNull('membro_id')->get();
        foreach ($oldProfessores as $oldProfessor) {
             $user = User::whereHas('profile', function ($query) use ($oldProfessor) {
                $query->where('id', $oldProfessor->membro_id);
            })->first();

            if ($user) {
                DB::table('ebd_turma_user')->insert([
                    'turma_id' => $oldProfessor->turma_id,
                    'user_id' => $user->id,
                    'funcao' => 'professor',
                    'created_at' => $oldProfessor->created_at,
                    'updated_at' => $oldProfessor->updated_at,
                ]);
            }
        }

        // Step 4: Migrate Grupos de Estudo
        $oldGrupos = DB::table('ebd_grupos_estudo_old')->get();
        foreach ($oldGrupos as $oldGrupo) {
            // Find the leader's new user_id
            $liderUser = null;
            if ($oldGrupo->lider_id) {
                $oldLider = DB::table('ebd_alunos_old')->find($oldGrupo->lider_id);
                if ($oldLider && $oldLider->membro_id) {
                     $liderUser = User::whereHas('profile', function ($query) use ($oldLider) {
                        $query->where('id', $oldLider->membro_id);
                    })->first();
                }
            }

            Grupo::create([
                'id' => $oldGrupo->id,
                'turma_id' => $oldGrupo->turma_id,
                'lider_id' => $liderUser ? $liderUser->id : null,
                'nome' => $oldGrupo->nome,
                'descricao' => $oldGrupo->descricao,
                'cor' => $oldGrupo->cor,
                'capacidade_maxima' => $oldGrupo->capacidade_maxima,
                'ativo' => $oldGrupo->ativo,
                'created_at' => $oldGrupo->created_at,
                'updated_at' => $oldGrupo->updated_at,
            ]);
        }

        // Step 5: Migrate Membros de Grupos to ebd_grupo_user pivot
        $oldMembrosGrupo = DB::table('ebd_grupo_membros_old')->get();
        foreach ($oldMembrosGrupo as $oldMembro) {
            $oldAluno = DB::table('ebd_alunos_old')->find($oldMembro->aluno_id);
            if ($oldAluno && $oldAluno->membro_id) {
                 $user = User::whereHas('profile', function ($query) use ($oldAluno) {
                    $query->where('id', $oldAluno->membro_id);
                })->first();

                if ($user) {
                    DB::table('ebd_grupo_user')->insert([
                        'grupo_id' => $oldMembro->grupo_id,
                        'user_id' => $user->id,
                        'created_at' => $oldMembro->created_at,
                        'updated_at' => $oldMembro->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clean up the new tables
        DB::table('ebd_grupo_user')->truncate();
        DB::table('ebd_turma_user')->truncate();
        DB::table('ebd_grupos')->truncate();
        DB::table('ebd_turmas')->truncate();
    }
};