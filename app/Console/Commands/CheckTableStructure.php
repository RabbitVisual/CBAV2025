<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'check:table-structure';
    protected $description = 'Verificar estrutura da tabela intercessor_oracaos';

    public function handle()
    {
        $this->info('🔍 Verificando estrutura da tabela intercessor_oracaos...');
        $this->newLine();

        if (Schema::hasTable('intercessor_oracaos')) {
            $this->line('✅ Tabela existe');
            
            $columns = Schema::getColumnListing('intercessor_oracaos');
            $this->line('📋 Colunas:');
            foreach ($columns as $column) {
                $this->line("  - {$column}");
            }
            
            $this->newLine();
            $this->info('🔍 Verificando dados...');
            $count = DB::table('intercessor_oracaos')->count();
            $this->line("📊 Total de registros: {$count}");
            
            if ($count > 0) {
                $sample = DB::table('intercessor_oracaos')->first();
                $this->line('📋 Exemplo de registro:');
                foreach ($sample as $key => $value) {
                    $this->line("  - {$key}: {$value}");
                }
            }
        } else {
            $this->error('❌ Tabela não existe');
        }
        
        $this->newLine();
        $this->info('🔍 Verificando tabela pedido_oracaos...');
        
        if (Schema::hasTable('pedido_oracaos')) {
            $this->line('✅ Tabela pedido_oracaos existe');
            $columns = Schema::getColumnListing('pedido_oracaos');
            $this->line('📋 Colunas:');
            foreach ($columns as $column) {
                $this->line("  - {$column}");
            }
        } else {
            $this->error('❌ Tabela pedido_oracaos não existe');
        }
        
        return 0;
    }
} 