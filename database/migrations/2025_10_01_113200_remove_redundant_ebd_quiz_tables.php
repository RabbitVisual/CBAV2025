<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migração remove as tabelas do antigo sistema de quiz,
     * unificando a lógica no sistema de avaliações existente.
     */
    public function up(): void
    {
        Schema::dropIfExists('ebd_quiz_respostas');
        Schema::dropIfExists('ebd_quiz_sessoes');
        Schema::dropIfExists('ebd_quiz_perguntas');
    }

    /**
     * Reverse the migrations.
     *
     * O rollback desta migração não é recomendado para manter a consistência
     * da nova estrutura unificada.
     */
    public function down(): void
    {
        // Não recriar as tabelas no rollback para forçar o uso do novo sistema.
        logger('A tentativa de reverter a migração de limpeza das tabelas de quiz foi ignorada.');
    }
};