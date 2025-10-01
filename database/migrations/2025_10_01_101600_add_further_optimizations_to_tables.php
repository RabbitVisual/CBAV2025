<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Transações
        $this->addIndexIfNotExists('transacoes', 'user_id');
        $this->addIndexIfNotExists('transacoes', 'campanha_id');
        $this->addIndexIfNotExists('transacoes', 'status');

        // Chat
        $this->addIndexIfNotExists('chat_messages', 'chat_room_id');
        $this->addIndexIfNotExists('chat_messages', 'user_id');
        $this->addIndexIfNotExists('chat_participants', ['chat_room_id', 'user_id'], 'chat_participants_room_user_unique');

        // EBD
        $this->addIndexIfNotExists('ebd_aulas', 'turma_id');
        $this->addIndexIfNotExists('ebd_aulas', 'licao_id');
        $this->addIndexIfNotExists('ebd_aulas', 'professor_id');
        $this->addIndexIfNotExists('ebd_avaliacoes', 'aula_id');
        $this->addIndexIfNotExists('ebd_questoes', 'avaliacao_id');
        $this->addIndexIfNotExists('ebd_alunos', 'turma_id');
        $this->addIndexIfNotExists('ebd_alunos', 'membro_id');
        $this->addIndexIfNotExists('ebd_alunos', 'status');

        // Devocionais
        $this->addIndexIfNotExists('devocionais', ['data', 'tipo', 'ativo']);

        // Notificações (Polimórfico)
        $this->addIndexIfNotExists('notifications', ['notifiable_type', 'notifiable_id']);
    }

    /**
     * Adicionar índice se não existir.
     */
    private function addIndexIfNotExists(string $table, string|array $columns, ?string $indexName = null): void
    {
        // O nome do índice é gerado automaticamente pelo Laravel se não for fornecido.
        // O importante é verificar se um índice para a(s) coluna(s) já existe.
        // Esta verificação é simplificada, mas eficaz para a maioria dos casos.
        if (!$this->hasIndex($table, $columns)) {
            Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                if ($indexName) {
                    $table->index($columns, $indexName);
                } else {
                    $table->index($columns);
                }
            });
        }
    }

    /**
     * Verificar se um índice para as colunas especificadas já existe.
     */
    private function hasIndex(string $table, string|array $columns): bool
    {
        $columns = (array) $columns;
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $sm->listTableIndexes($table);

        foreach ($indexes as $index) {
            $indexColumns = array_map('strtolower', $index->getColumns());
            $columns = array_map('strtolower', $columns);

            if (count($indexColumns) === count($columns) && !array_diff($indexColumns, $columns)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // As remoções de índice podem ser complexas e, em geral,
        // não são necessárias para rollbacks simples. Manter os índices não causa problemas.
    }
};