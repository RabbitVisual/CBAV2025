<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migração remove as tabelas do sistema de notificação customizado
     * para unificar o uso no sistema nativo do Laravel.
     */
    public function up(): void
    {
        // Remove a tabela principal do sistema de notificação customizado.
        // O nome está com um erro de digitação ('notificacaos').
        Schema::dropIfExists('notificacaos');

        // Remove tabelas auxiliares relacionadas ao sistema customizado.
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notificacao_user_statuses');
        Schema::dropIfExists('notification_reads'); // Assumindo que esta tabela existia com base no model NotificationRead.
    }

    /**
     * Reverse the migrations.
     *
     * O rollback desta migração não é recomendado, pois o objetivo é
     * remover permanentemente o sistema antigo. Se necessário, as tabelas
     * teriam que ser recriadas a partir de suas migrações originais.
     */
    public function down(): void
    {
        // Não há ação de rollback para manter a consistência da limpeza.
        // Se for necessário reverter, as migrações originais devem ser executadas.
        logger('A tentativa de reverter a migração de limpeza de notificações foi ignorada para manter a integridade do banco de dados.');
    }
};