<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyzeDatabaseStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:analyze-structure {--fix : Tentar corrigir problemas encontrados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analisa a estrutura do banco de dados e identifica redundâncias e problemas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analisando estrutura do banco de dados...');

        $existingTables = $this->getExistingTables();
        $migrationTables = $this->getMigrationTables();
        
        $this->analyzeTableStructure($existingTables, $migrationTables);
        $this->analyzeRedundancies($existingTables);
        $this->suggestImprovements($existingTables);
    }

    /**
     * Obter tabelas existentes no banco
     */
    private function getExistingTables(): array
    {
        $tables = [];
        $result = DB::select('SHOW TABLES');
        
        foreach ($result as $row) {
            $tables[] = array_values((array) $row)[0];
        }

        return $tables;
    }

    /**
     * Obter tabelas definidas nas migrações
     */
    private function getMigrationTables(): array
    {
        $migrationFiles = glob(database_path('migrations/*.php'));
        $tables = [];

        foreach ($migrationFiles as $file) {
            $content = file_get_contents($file);
            
            // Extrair nomes de tabelas das migrações
            preg_match_all('/Schema::create\([\'"]([^\'"]+)[\'"]/', $content, $matches);
            foreach ($matches[1] as $table) {
                $tables[] = $table;
            }
        }

        return array_unique($tables);
    }

    /**
     * Analisar estrutura das tabelas
     */
    private function analyzeTableStructure(array $existingTables, array $migrationTables): void
    {
        $this->info("\n📋 ANÁLISE DE ESTRUTURA:");

        // Tabelas sem migrações
        $tablesWithoutMigrations = array_diff($existingTables, $migrationTables);
        if (!empty($tablesWithoutMigrations)) {
            $this->warn("\n⚠️  TABELAS SEM MIGRAÇÕES:");
            foreach ($tablesWithoutMigrations as $table) {
                $this->line("   - {$table}");
            }
        }

        // Migrações sem tabelas
        $migrationsWithoutTables = array_diff($migrationTables, $existingTables);
        if (!empty($migrationsWithoutTables)) {
            $this->warn("\n⚠️  MIGRAÇÕES SEM TABELAS CORRESPONDENTES:");
            foreach ($migrationsWithoutTables as $table) {
                $this->line("   - {$table}");
            }
        }

        if (empty($tablesWithoutMigrations) && empty($migrationsWithoutTables)) {
            $this->info("✅ Todas as tabelas têm migrações correspondentes!");
        }
    }

    /**
     * Analisar redundâncias
     */
    private function analyzeRedundancies(array $tables): void
    {
        $this->info("\n🔍 ANÁLISE DE REDUNDÂNCIAS:");

        // Verificar tabelas de relacionamento similares
        $relationshipTables = array_filter($tables, function($table) {
            return str_contains($table, '_') && 
                   (str_contains($table, 'cargo') || 
                    str_contains($table, 'permission') || 
                    str_contains($table, 'role') ||
                    str_contains($table, 'ministerio') ||
                    str_contains($table, 'conselho'));
        });

        if (!empty($relationshipTables)) {
            $this->warn("\n⚠️  TABELAS DE RELACIONAMENTO IDENTIFICADAS:");
            foreach ($relationshipTables as $table) {
                $this->line("   - {$table}");
            }
        }

        // Verificar tabelas de configuração
        $configTables = array_filter($tables, function($table) {
            return str_contains($table, 'configuracao');
        });

        if (count($configTables) > 1) {
            $this->warn("\n⚠️  MÚLTIPLAS TABELAS DE CONFIGURAÇÃO:");
            foreach ($configTables as $table) {
                $this->line("   - {$table}");
            }
        }

        // Verificar tabelas de notificação
        $notificationTables = array_filter($tables, function($table) {
            return str_contains($table, 'notificacao');
        });

        if (count($notificationTables) > 1) {
            $this->warn("\n⚠️  MÚLTIPLAS TABELAS DE NOTIFICAÇÃO:");
            foreach ($notificationTables as $table) {
                $this->line("   - {$table}");
            }
        }
    }

    /**
     * Sugerir melhorias
     */
    private function suggestImprovements(array $tables): void
    {
        $this->info("\n💡 SUGESTÕES DE MELHORIAS:");

        // Verificar redundância entre membro_cargo e user_cargo
        if (in_array('membro_cargo', $tables) && in_array('user_cargo', $tables)) {
            $this->warn("\n⚠️  REDUNDÂNCIA DETECTADA:");
            $this->line("   - membro_cargo e user_cargo fazem funções similares");
            $this->line("   - Sugestão: Unificar em uma única tabela de relacionamento");
        }

        // Verificar tabelas de configuração
        if (in_array('configuracoes', $tables) && in_array('ebd_configuracoes', $tables)) {
            $this->warn("\n⚠️  CONFIGURAÇÕES SEPARADAS:");
            $this->line("   - configuracoes e ebd_configuracoes poderiam ser unificadas");
            $this->line("   - Sugestão: Usar uma tabela única com campo 'modulo'");
        }

        // Verificar estrutura de permissões
        $permissionTables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'];
        $existingPermissionTables = array_intersect($tables, $permissionTables);
        
        if (count($existingPermissionTables) === count($permissionTables)) {
            $this->info("✅ Sistema de permissões completo (Spatie Laravel Permission)");
        } else {
            $this->warn("⚠️  Sistema de permissões incompleto");
        }

        // Verificar tabelas do Laravel
        $laravelTables = ['users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'personal_access_tokens', 'migrations'];
        $existingLaravelTables = array_intersect($tables, $laravelTables);
        
        if (count($existingLaravelTables) === count($laravelTables)) {
            $this->info("✅ Tabelas do Laravel completas");
        } else {
            $this->warn("⚠️  Tabelas do Laravel incompletas");
        }

        // Verificar estrutura EBD
        $ebdTables = array_filter($tables, function($table) {
            return str_starts_with($table, 'ebd_');
        });

        if (count($ebdTables) >= 10) {
            $this->info("✅ Sistema EBD bem estruturado");
        } else {
            $this->warn("⚠️  Sistema EBD pode estar incompleto");
        }

        // Verificar estrutura financeira
        $financeTables = ['transacoes', 'campanhas', 'pagamentos'];
        $existingFinanceTables = array_intersect($tables, $financeTables);
        
        if (count($existingFinanceTables) === count($financeTables)) {
            $this->info("✅ Sistema financeiro completo");
        } else {
            $this->warn("⚠️  Sistema financeiro pode estar incompleto");
        }
    }
} 