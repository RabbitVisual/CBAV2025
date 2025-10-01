<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela de Turmas EBD
        Schema::create('ebd_turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('faixa_etaria')->nullable(); // Ex: 3-6 anos, 7-12 anos, 13-17 anos, Adultos
            $table->string('cor')->default('#3b82f6');
            $table->integer('capacidade_maxima')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de Professores EBD
        Schema::create('ebd_professores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->enum('tipo', ['principal', 'auxiliar'])->default('auxiliar');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de Alunos EBD
        Schema::create('ebd_alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->nullable()->constrained('membros')->onDelete('set null');
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('nome_responsavel')->nullable();
            $table->string('telefone_responsavel')->nullable();
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->date('data_matricula');
            $table->date('data_saida')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'transferido'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela de Lições EBD
        Schema::create('ebd_licoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->text('objetivos')->nullable();
            $table->text('versiculo_chave')->nullable();
            $table->text('conteudo');
            $table->text('aplicacao_pratica')->nullable();
            $table->text('oracao')->nullable();
            $table->string('material_necessario')->nullable();
            $table->integer('duracao_minutos')->default(60);
            $table->enum('dificuldade', ['facil', 'medio', 'dificil'])->default('medio');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de Aulas EBD
        Schema::create('ebd_aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->foreignId('licao_id')->constrained('ebd_licoes')->onDelete('cascade');
            $table->foreignId('professor_id')->nullable()->constrained('ebd_professores')->onDelete('set null');
            $table->date('data_aula');
            $table->time('horario_inicio');
            $table->time('horario_fim');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['agendada', 'realizada', 'cancelada', 'adiada'])->default('agendada');
            $table->timestamps();
        });

        // Tabela de Presença EBD
        Schema::create('ebd_presencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('ebd_aulas')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->enum('status', ['presente', 'ausente', 'justificado', 'atrasado'])->default('presente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela de Avaliações EBD
        Schema::create('ebd_avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('ebd_aulas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['quiz', 'prova', 'trabalho', 'participacao'])->default('quiz');
            $table->integer('pontuacao_maxima')->default(10);
            $table->boolean('obrigatoria')->default(true);
            $table->timestamps();
        });

        // Tabela de Questões EBD
        Schema::create('ebd_questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('ebd_avaliacoes')->onDelete('cascade');
            $table->text('pergunta');
            $table->enum('tipo', ['multipla_escolha', 'verdadeiro_falso', 'dissertativa', 'correspondencia'])->default('multipla_escolha');
            $table->json('opcoes')->nullable(); // Para questões de múltipla escolha
            $table->string('resposta_correta')->nullable();
            $table->integer('pontuacao')->default(1);
            $table->text('explicacao')->nullable();
            $table->integer('ordem')->default(1);
            $table->timestamps();
        });

        // Tabela de Respostas dos Alunos EBD
        Schema::create('ebd_respostas_alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('ebd_avaliacoes')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->foreignId('questao_id')->constrained('ebd_questoes')->onDelete('cascade');
            $table->text('resposta');
            $table->boolean('correta')->default(false);
            $table->integer('pontuacao_obtida')->default(0);
            $table->text('comentario_professor')->nullable();
            $table->timestamps();
        });

        // Tabela de Notas EBD
        Schema::create('ebd_notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('ebd_avaliacoes')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->integer('nota');
            $table->integer('pontuacao_maxima');
            $table->decimal('percentual', 5, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela de Relatórios EBD
        Schema::create('ebd_relatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['presenca', 'notas', 'progresso', 'geral'])->default('geral');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->json('dados')->nullable();
            $table->timestamps();
        });

        // Tabela de Certificados EBD
        Schema::create('ebd_certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_conclusao');
            $table->string('codigo_verificacao')->unique();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de Configurações EBD
        Schema::create('ebd_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->text('valor');
            $table->string('tipo')->default('string');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebd_certificados');
        Schema::dropIfExists('ebd_relatorios');
        Schema::dropIfExists('ebd_notas');
        Schema::dropIfExists('ebd_respostas_alunos');
        Schema::dropIfExists('ebd_questoes');
        Schema::dropIfExists('ebd_avaliacoes');
        Schema::dropIfExists('ebd_presencas');
        Schema::dropIfExists('ebd_aulas');
        Schema::dropIfExists('ebd_licoes');
        Schema::dropIfExists('ebd_alunos');
        Schema::dropIfExists('ebd_professores');
        Schema::dropIfExists('ebd_turmas');
        Schema::dropIfExists('ebd_configuracoes');
    }
}; 