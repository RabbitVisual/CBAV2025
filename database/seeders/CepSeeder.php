<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cep;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Para usar este seeder:
     * 1. Baixe o arquivo ceps.xlsx do repositório: https://github.com/Maahzuka/database-CEPS
     * 2. Coloque o arquivo na pasta storage/app/
     * 3. Execute: php artisan db:seed --class=CepSeeder
     */
    public function run(): void
    {
        $this->command->info('Iniciando importação de CEPs...');
        
        $filePath = storage_path('app/ceps.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error('Arquivo ceps.xlsx não encontrado em storage/app/');
            $this->command->info('Para usar este seeder:');
            $this->command->info('1. Baixe o arquivo do repositório: https://github.com/Maahzuka/database-CEPS');
            $this->command->info('2. Coloque o arquivo ceps.xlsx na pasta storage/app/');
            $this->command->info('3. Execute novamente: php artisan db:seed --class=CepSeeder');
            return;
        }
        
        try {
            // Limpar tabela antes da importação
            $this->command->info('Limpando tabela de CEPs...');
            DB::table('ceps')->truncate();
            
            $this->command->info('Importando dados do arquivo Excel...');
            
            // Importar dados usando Laravel Excel
            Excel::import(new class implements \Maatwebsite\Excel\Concerns\ToModel, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                public function model(array $row)
                {
                    // Pular linhas vazias
                    if (empty($row['uf']) || empty($row['localidade'])) {
                        return null;
                    }
                    
                    return new Cep([
                        'uf' => $row['uf'] ?? '',
                        'regiao' => $row['regiao'] ?? '',
                        'localidade' => $row['localidade'] ?? '',
                        'localidade_sem_acentos' => $row['localidade_sem_acentos'] ?? '',
                        'faixa_de_cep' => $row['faixa_de_cep'] ?? '',
                        'cep_inicial' => str_replace('-', '', $row['cep_inicial'] ?? ''),
                        'cep_final' => str_replace('-', '', $row['cep_final'] ?? ''),
                        'situacao' => $row['situacao'] ?? '',
                        'tipo_de_faixa' => $row['tipo_de_faixa'] ?? '',
                        'latitude' => is_numeric($row['latitude'] ?? null) ? $row['latitude'] : null,
                        'longitude' => is_numeric($row['longitude'] ?? null) ? $row['longitude'] : null,
                        'cod_geografico_subdivisao' => $row['cod_geografico_subdivisao'] ?? null,
                        'cod_geografico_distrito' => $row['cod_geografico_distrito'] ?? null,
                        'cod_ibge' => $row['cod_ibge'] ?? '',
                        'microrregiao' => $row['microrregiao'] ?? null,
                        'mesorregiao' => $row['mesorregiao'] ?? null,
                        'categoria' => $row['categoria'] ?? '',
                        'altitude' => is_numeric($row['altitude'] ?? null) ? (int)$row['altitude'] : null,
                        'localizacao' => $row['localizacao'] ?? null,
                        'localizacao_sem_acentos' => $row['localizacao_sem_acentos'] ?? null,
                    ]);
                }
            }, $filePath);
            
            $totalCeps = Cep::count();
            $this->command->info("Importação concluída! Total de CEPs importados: {$totalCeps}");
            
        } catch (\Exception $e) {
            $this->command->error('Erro durante a importação: ' . $e->getMessage());
            Log::error('Erro na importação de CEPs: ' . $e->getMessage());
        }
    }
}
