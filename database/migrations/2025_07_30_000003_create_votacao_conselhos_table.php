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
        Schema::create('votacao_conselhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conselho_id')->constrained('conselhos')->onDelete('cascade');
            $table->foreignId('pauta_id')->nullable()->constrained('pauta_conselhos')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo_votacao', ['aprovacao_rejeicao', 'multipla_escolha', 'escala'])->default('aprovacao_rejeicao');
            $table->json('opcoes_votacao')->nullable();
            $table->integer('votos_favoraveis')->default(0);
            $table->integer('votos_contrarios')->default(0);
            $table->integer('votos_abstencao')->default(0);
            $table->integer('total_votos')->default(0);
            $table->integer('quorum_necessario')->default(5);
            $table->enum('status', ['pendente', 'em_andamento', 'finalizada', 'cancelada'])->default('pendente');
            $table->enum('resultado', ['aprovado', 'rejeitado', 'empate', 'sem_quorum'])->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_fim')->nullable();
            $table->integer('tempo_limite')->nullable(); // em minutos
            $table->boolean('voto_secreto')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votacao_conselhos');
    }
}; 