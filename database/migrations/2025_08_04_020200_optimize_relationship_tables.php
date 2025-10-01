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
        // Otimizar tabela membro_cargo
        $this->addIndexIfNotExists('membro_cargo', ['membro_id', 'ativo'], 'membro_cargo_membro_id_ativo_index');
        $this->addIndexIfNotExists('membro_cargo', ['cargo_id', 'ativo'], 'membro_cargo_cargo_id_ativo_index');

        // Otimizar tabela user_cargo
        $this->addIndexIfNotExists('user_cargo', ['user_id', 'ativo'], 'user_cargo_user_id_ativo_index');
        $this->addIndexIfNotExists('user_cargo', ['cargo_id', 'ativo'], 'user_cargo_cargo_id_ativo_index');

        // Otimizar tabela participante_conselhos
        $this->addIndexIfNotExists('participante_conselhos', ['conselho_id', 'presente'], 'participante_conselhos_conselho_id_presente_index');
        $this->addIndexIfNotExists('participante_conselhos', ['user_id', 'presente'], 'participante_conselhos_user_id_presente_index');

        // Otimizar tabela solicitacoes_ministerio
        $this->addIndexIfNotExists('solicitacoes_ministerio', ['ministerio_id', 'status'], 'solicitacoes_ministerio_ministerio_id_status_index');
        $this->addIndexIfNotExists('solicitacoes_ministerio', ['membro_id', 'status'], 'solicitacoes_ministerio_membro_id_status_index');

        // Otimizar tabela votacao_conselhos
        $this->addIndexIfNotExists('votacao_conselhos', ['conselho_id', 'status'], 'votacao_conselhos_conselho_id_status_index');
        $this->addIndexIfNotExists('votacao_conselhos', ['pauta_id'], 'votacao_conselhos_pauta_id_index');

        // Otimizar tabela voto_conselhos
        $this->addIndexIfNotExists('voto_conselhos', ['votacao_id', 'voto'], 'voto_conselhos_votacao_id_voto_index');
        $this->addIndexIfNotExists('voto_conselhos', ['user_id'], 'voto_conselhos_user_id_index');

        // Otimizar tabela pauta_conselhos
        $this->addIndexIfNotExists('pauta_conselhos', ['conselho_id', 'status'], 'pauta_conselhos_conselho_id_status_index');
        $this->addIndexIfNotExists('pauta_conselhos', ['ordem'], 'pauta_conselhos_ordem_index');

        // Otimizar tabelas de permissões
        $this->addIndexIfNotExists('model_has_permissions', ['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
        $this->addIndexIfNotExists('model_has_roles', ['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
        $this->addIndexIfNotExists('role_has_permissions', ['role_id'], 'role_has_permissions_role_id_index');
        $this->addIndexIfNotExists('role_has_permissions', ['permission_id'], 'role_has_permissions_permission_id_index');
    }

    /**
     * Adicionar índice se não existir
     */
    private function addIndexIfNotExists($table, $columns, $indexName): void
    {
        if (!$this->hasIndex($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->index($columns);
            });
        }
    }

    /**
     * Verificar se um índice existe
     */
    private function hasIndex($table, $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            foreach ($indexes as $index) {
                if ($index->Key_name === $indexName) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não remover índices no rollback para evitar problemas com foreign keys
        // Os índices podem ser removidos manualmente se necessário
    }
}; 