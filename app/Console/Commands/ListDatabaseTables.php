<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:list-tables {--format=table : Formato de saída (table, json, csv)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todas as tabelas do banco de dados e as categoriza';

    /**
     * Tabelas que fazem parte do projeto CBAV
     */
    private array $projectTables = [
        // Tabelas do Laravel
        'users' => 'Laravel - Usuários',
        'password_reset_tokens' => 'Laravel - Tokens de reset de senha',
        'sessions' => 'Laravel - Sessões',
        'cache' => 'Laravel - Cache',
        'cache_locks' => 'Laravel - Locks do cache',
        'jobs' => 'Laravel - Jobs em fila',
        'job_batches' => 'Laravel - Lotes de jobs',
        'failed_jobs' => 'Laravel - Jobs falhados',
        'personal_access_tokens' => 'Laravel - Tokens de acesso pessoal',
        'migrations' => 'Laravel - Migrações',
        
        // Tabelas de permissões
        'permissions' => 'Spatie - Permissões',
        'roles' => 'Spatie - Funções',
        'model_has_permissions' => 'Spatie - Relação modelo-permissão',
        'model_has_roles' => 'Spatie - Relação modelo-função',
        'role_has_permissions' => 'Spatie - Relação função-permissão',
        
        // Tabelas do projeto CBAV
        'campanhas' => 'CBAV - Campanhas de doação',
        'cargos' => 'CBAV - Cargos dos membros',
        'configuracoes' => 'CBAV - Configurações do sistema',
        'conselhos' => 'CBAV - Conselhos da igreja',
        'departamentos' => 'CBAV - Departamentos',
        'devocionais' => 'CBAV - Devocionais diários',
        'ebd_alunos' => 'CBAV - Alunos da EBD',
        'ebd_aulas' => 'CBAV - Aulas da EBD',
        'ebd_avaliacoes' => 'CBAV - Avaliações da EBD',
        'ebd_certificados' => 'CBAV - Certificados da EBD',
        'ebd_configuracoes' => 'CBAV - Configurações da EBD',
        'ebd_licoes' => 'CBAV - Lições da EBD',
        'ebd_notas' => 'CBAV - Notas dos alunos da EBD',
        'ebd_presencas' => 'CBAV - Presenças da EBD',
        'ebd_professores' => 'CBAV - Professores da EBD',
        'ebd_questoes' => 'CBAV - Questões das avaliações da EBD',
        'ebd_quiz_perguntas' => 'CBAV - Perguntas do quiz bíblico',
        'ebd_quiz_respostas' => 'CBAV - Respostas do quiz bíblico',
        'ebd_quiz_sessoes' => 'CBAV - Sessões do quiz bíblico',
        'ebd_relatorios' => 'CBAV - Relatórios da EBD',
        'ebd_respostas_alunos' => 'CBAV - Respostas dos alunos da EBD',
        'ebd_turmas' => 'CBAV - Turmas da EBD',
        'membro_cargo' => 'CBAV - Relação membro-cargo',
        'membros' => 'CBAV - Membros da igreja',
        'ministerios' => 'CBAV - Ministérios',
        'notificacaos' => 'CBAV - Notificações do sistema',
        'pagamentos' => 'CBAV - Pagamentos',
        'participante_conselhos' => 'CBAV - Participantes dos conselhos',
        'pauta_conselhos' => 'CBAV - Pautas dos conselhos',
        'solicitacoes_ministerio' => 'CBAV - Solicitações de ministério',
        'template_item_pautas' => 'CBAV - Itens de template de pauta',
        'template_pautas' => 'CBAV - Templates de pauta',
        'transacoes' => 'CBAV - Transações financeiras',
        'user_cargo' => 'CBAV - Relação usuário-cargo',
        'votacao_conselhos' => 'CBAV - Votações dos conselhos',
        'voto_conselhos' => 'CBAV - Votos dos conselhos',
    ];

    /**
     * Tabelas que não fazem parte do projeto (devem ser removidas)
     */
    private array $unusedTables = [
        'categorias_despesa' => 'Não utilizada - Categorias de despesa',
        'configuracoes_ir' => 'Não utilizada - Configurações de IR',
        'declaracoes_ir' => 'Não utilizada - Declarações de IR',
        'orcamento_itens' => 'Não utilizada - Itens de orçamento',
        'orcamentos' => 'Não utilizada - Orçamentos',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analisando tabelas do banco de dados...');

        try {
            $allTables = $this->getAllTables();
            
            if (empty($allTables)) {
                $this->warn('Nenhuma tabela encontrada no banco de dados.');
                return;
            }

            $projectTablesFound = [];
            $unusedTablesFound = [];
            $unknownTables = [];

            foreach ($allTables as $table) {
                if (isset($this->projectTables[$table])) {
                    $projectTablesFound[$table] = $this->projectTables[$table];
                } elseif (isset($this->unusedTables[$table])) {
                    $unusedTablesFound[$table] = $this->unusedTables[$table];
                } else {
                    $unknownTables[] = $table;
                }
            }

            $this->displayResults($projectTablesFound, $unusedTablesFound, $unknownTables);

        } catch (\Exception $e) {
            $this->error('Erro ao analisar tabelas: ' . $e->getMessage());
        }
    }

    /**
     * Obter todas as tabelas do banco
     */
    private function getAllTables(): array
    {
        $tables = [];
        $result = DB::select('SHOW TABLES');
        
        foreach ($result as $row) {
            $tables[] = array_values((array) $row)[0];
        }

        return $tables;
    }

    /**
     * Exibir resultados
     */
    private function displayResults(array $projectTables, array $unusedTables, array $unknownTables): void
    {
        $format = $this->option('format');

        switch ($format) {
            case 'json':
                $this->displayJsonResults($projectTables, $unusedTables, $unknownTables);
                break;
            case 'csv':
                $this->displayCsvResults($projectTables, $unusedTables, $unknownTables);
                break;
            default:
                $this->displayTableResults($projectTables, $unusedTables, $unknownTables);
        }
    }

    /**
     * Exibir resultados em formato de tabela
     */
    private function displayTableResults(array $projectTables, array $unusedTables, array $unknownTables): void
    {
        $this->info("\n📊 RESUMO DAS TABELAS:");
        $this->line("Total de tabelas: " . (count($projectTables) + count($unusedTables) + count($unknownTables)));
        $this->line("Tabelas do projeto: " . count($projectTables));
        $this->line("Tabelas não utilizadas: " . count($unusedTables));
        $this->line("Tabelas desconhecidas: " . count($unknownTables));

        if (!empty($projectTables)) {
            $this->info("\n✅ TABELAS DO PROJETO CBAV:");
            $headers = ['Tabela', 'Descrição'];
            $rows = [];
            foreach ($projectTables as $table => $description) {
                $rows[] = [$table, $description];
            }
            $this->table($headers, $rows);
        }

        if (!empty($unusedTables)) {
            $this->warn("\n🗑️  TABELAS NÃO UTILIZADAS (podem ser removidas):");
            $headers = ['Tabela', 'Descrição'];
            $rows = [];
            foreach ($unusedTables as $table => $description) {
                $rows[] = [$table, $description];
            }
            $this->table($headers, $rows);
        }

        if (!empty($unknownTables)) {
            $this->error("\n❓ TABELAS DESCONHECIDAS:");
            foreach ($unknownTables as $table) {
                $this->line("   - {$table}");
            }
        }

        if (!empty($unusedTables)) {
            $this->warn("\n💡 Para remover as tabelas não utilizadas, execute:");
            $this->line("   php artisan db:cleanup-unused-tables");
        }
    }

    /**
     * Exibir resultados em JSON
     */
    private function displayJsonResults(array $projectTables, array $unusedTables, array $unknownTables): void
    {
        $data = [
            'summary' => [
                'total' => count($projectTables) + count($unusedTables) + count($unknownTables),
                'project_tables' => count($projectTables),
                'unused_tables' => count($unusedTables),
                'unknown_tables' => count($unknownTables),
            ],
            'project_tables' => $projectTables,
            'unused_tables' => $unusedTables,
            'unknown_tables' => $unknownTables,
        ];

        $this->line(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Exibir resultados em CSV
     */
    private function displayCsvResults(array $projectTables, array $unusedTables, array $unknownTables): void
    {
        $this->line("Tabela,Descrição,Categoria");
        
        foreach ($projectTables as $table => $description) {
            $this->line("\"{$table}\",\"{$description}\",\"Projeto\"");
        }
        
        foreach ($unusedTables as $table => $description) {
            $this->line("\"{$table}\",\"{$description}\",\"Não utilizada\"");
        }
        
        foreach ($unknownTables as $table) {
            $this->line("\"{$table}\",\"\",\"Desconhecida\"");
        }
    }
} 