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
        // Tabela de Grupos de Estudo EBD
        Schema::create('ebd_grupos_estudo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('cor')->default('#3b82f6');
            $table->integer('capacidade_maxima')->default(8);
            $table->foreignId('lider_id')->nullable()->constrained('ebd_alunos')->onDelete('set null');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de Membros dos Grupos de Estudo
        Schema::create('ebd_grupo_membros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('ebd_grupos_estudo')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->date('data_entrada');
            $table->date('data_saida')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
            
            // Índice único para evitar duplicatas
            $table->unique(['grupo_id', 'aluno_id']);
        });

        // Tabela de Avaliações em Grupo
        Schema::create('ebd_avaliacoes_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('ebd_avaliacoes')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('ebd_grupos_estudo')->onDelete('cascade');
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])->default('pendente');
            $table->timestamp('iniciada_em')->nullable();
            $table->timestamp('concluida_em')->nullable();
            $table->integer('pontuacao_total')->default(0);
            $table->decimal('percentual', 5, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela de Respostas do Grupo
        Schema::create('ebd_respostas_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_grupo_id')->constrained('ebd_avaliacoes_grupo')->onDelete('cascade');
            $table->foreignId('questao_id')->constrained('ebd_questoes')->onDelete('cascade');
            $table->text('resposta_consenso'); // Resposta final do grupo
            $table->json('discussao')->nullable(); // Histórico da discussão
            $table->boolean('correta')->default(false);
            $table->integer('pontuacao_obtida')->default(0);
            $table->foreignId('respondido_por')->nullable()->constrained('ebd_alunos')->onDelete('set null'); // Quem submeteu a resposta final
            $table->timestamp('tempo_inicio')->nullable();
            $table->timestamp('tempo_fim')->nullable();
            $table->timestamps();
        });

        // Tabela de Contribuições Individuais nas Respostas do Grupo
        Schema::create('ebd_contribuicoes_resposta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_grupo_id')->constrained('ebd_respostas_grupo')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->text('contribuicao');
            $table->enum('tipo', ['sugestao', 'discussao', 'voto', 'lideranca'])->default('sugestao');
            $table->boolean('aceita_pelo_grupo')->default(false);
            $table->timestamps();
        });

        // Adicionar campo para identificar avaliações que permitem grupos
        Schema::table('ebd_avaliacoes', function (Blueprint $table) {
            $table->boolean('permite_grupos')->default(false)->after('obrigatoria');
            $table->integer('tempo_limite_minutos')->nullable()->after('permite_grupos');
            $table->enum('modo_avaliacao', ['individual', 'grupo', 'ambos'])->default('individual')->after('tempo_limite_minutos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebd_avaliacoes', function (Blueprint $table) {
            $table->dropColumn(['permite_grupos', 'tempo_limite_minutos', 'modo_avaliacao']);
        });
        
        Schema::dropIfExists('ebd_contribuicoes_resposta');
        Schema::dropIfExists('ebd_respostas_grupo');
        Schema::dropIfExists('ebd_avaliacoes_grupo');
        Schema::dropIfExists('ebd_grupo_membros');
        Schema::dropIfExists('ebd_grupos_estudo');
    }
};