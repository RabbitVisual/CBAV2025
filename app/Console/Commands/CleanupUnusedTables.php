<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanupUnusedTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:cleanup-unused-tables {--force : Forçar a execução sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove tabelas que não fazem parte do projeto CBAV';

    /**
     * Tabelas que devem ser mantidas (parte do projeto CBAV)
     */
    private array $tablesToKeep = [
        // Tabelas do Laravel
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'personal_access_tokens',
        'migrations',
        
        // Tabelas de permissões
        'permissions',
        'roles',
        'model_has_permissions',
        'model_has_roles',
        'role_has_permissions',
        
        // Tabelas do projeto CBAV
        'campanhas',
        'cargos',
        'configuracoes',
        'conselhos',
        'departamentos',
        'devocionais',
        'ebd_alunos',
        'ebd_aulas',
        'ebd_avaliacoes',
        'ebd_certificados',
        'ebd_configuracoes',
        'ebd_licoes',
        'ebd_notas',
        'ebd_presencas',
        'ebd_professores',
        'ebd_questoes',
        'ebd_quiz_perguntas',
        'ebd_quiz_respostas',
        'ebd_quiz_sessoes',
        'ebd_relatorios',
        'ebd_respostas_alunos',
        'ebd_turmas',
        'membro_cargo',
        'membros',
        'ministerios',
        'notificacaos',
        'pagamentos',
        'participante_conselhos',
        'pauta_conselhos',
        'solicitacoes_ministerio',
        'template_item_pautas',
        'template_pautas',
        'transacoes',
        'user_cargo',
        'votacao_conselhos',
        'voto_conselhos',
    ];

    /**
     * Tabelas que devem ser removidas (não fazem parte do projeto)
     */
    private array $tablesToRemove = [
        'categorias_despesa',
        'configuracoes_ir',
        'declaracoes_ir',
        'orcamento_itens',
        'orcamentos',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->runningUnitTests()) {
            $this->info('Ambiente de testes: ignorando CleanupUnusedTables.');
            return 0;
        }
        $this->info('🔍 Analisando tabelas do banco de dados...');

        // Obter todas as tabelas existentes
        $existingTables = $this->getExistingTables();
        
        // Identificar tabelas que devem ser removidas
        $tablesToRemove = array_intersect($existingTables, $this->tablesToRemove);
        
        if (empty($tablesToRemove)) {
            $this->info('✅ Nenhuma tabela desnecessária encontrada. O banco já está limpo!');
            return;
        }

        $this->warn('⚠️  Tabelas que serão removidas:');
        foreach ($tablesToRemove as $table) {
            $this->line("   - {$table}");
        }

        // Confirmar execução
        if (!$this->option('force')) {
            if (!$this->confirm('Tem certeza que deseja remover essas tabelas? Esta ação não pode ser desfeita.')) {
                $this->info('❌ Operação cancelada.');
                return 0;
            }
        }

        $this->info('🗑️  Iniciando remoção das tabelas...');

        try {
            // Desabilitar verificação de chaves estrangeiras
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $removedCount = 0;
            foreach ($tablesToRemove as $table) {
                if (Schema::hasTable($table)) {
                    Schema::dropIfExists($table);
                    $this->line("   ✅ Removida: {$table}");
                    $removedCount++;
                } else {
                    $this->line("   ⚠️  Tabela não encontrada: {$table}");
                }
            }

            // Reabilitar verificação de chaves estrangeiras
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            $this->info("✅ Limpeza concluída! {$removedCount} tabela(s) removida(s).");

        } catch (\Exception $e) {
            $this->error('❌ Erro durante a limpeza: ' . $e->getMessage());
            
            // Reabilitar verificação de chaves estrangeiras em caso de erro
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    /**
     * Obter todas as tabelas existentes no banco
     */
    private function getExistingTables(): array
    {
        $tables = [];
        
        try {
            $result = DB::select('SHOW TABLES');
            foreach ($result as $row) {
                $tables[] = array_values((array) $row)[0];
            }
        } catch (\Exception $e) {
            $this->error('Erro ao obter lista de tabelas: ' . $e->getMessage());
        }

        return $tables;
    }
} 