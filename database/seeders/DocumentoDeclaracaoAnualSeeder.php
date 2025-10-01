<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentoDeclaracaoAnual;

class DocumentoDeclaracaoAnualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📄 Criando documentos de declaração anual demonstrativos...');

        $documentos = [
            [
                'igreja_id' => 1,
                'ano_exercicio' => 2024,
                'tipo_documento' => 'DECLARACAO_ANUAL',
                'numero_documento' => 'DA-2024-001',
                'protocolo_receita' => '2024DA00123456789',
                'data_emissao' => '2024-01-15 10:00:00',
                'data_vencimento' => '2024-04-30 23:59:59',
                'valor_total' => 150000.00,
                'valor_doacoes' => 80000.00,
                'valor_dizimos' => 60000.00,
                'valor_outros' => 10000.00,
                'hash_documento' => 'hash_2024_declaracao_anual_001_abc123def456',
                'status' => 'PENDENTE',
                'observacoes' => 'Declaração anual de 2024 - Todos os valores declarados corretamente'
            ],
            [
                'igreja_id' => 1,
                'ano_exercicio' => 2023,
                'tipo_documento' => 'DECLARACAO_ANUAL',
                'numero_documento' => 'DA-2023-001',
                'protocolo_receita' => '2023DA00123456789',
                'data_emissao' => '2023-01-15 10:00:00',
                'data_vencimento' => '2023-04-30 23:59:59',
                'valor_total' => 120000.00,
                'valor_doacoes' => 65000.00,
                'valor_dizimos' => 45000.00,
                'valor_outros' => 10000.00,
                'hash_documento' => 'hash_2023_declaracao_anual_001_abc123def456',
                'status' => 'PROCESSADO',
                'validado_em' => '2023-05-15 14:30:00',
                'validado_por' => 1,
                'observacoes' => 'Declaração anual de 2023 - Processada pela Receita Federal'
            ],
            [
                'igreja_id' => 1,
                'ano_exercicio' => 2022,
                'tipo_documento' => 'DECLARACAO_ANUAL',
                'numero_documento' => 'DA-2022-001',
                'protocolo_receita' => '2022DA00123456789',
                'data_emissao' => '2022-01-15 10:00:00',
                'data_vencimento' => '2022-04-30 23:59:59',
                'valor_total' => 95000.00,
                'valor_doacoes' => 50000.00,
                'valor_dizimos' => 35000.00,
                'valor_outros' => 10000.00,
                'hash_documento' => 'hash_2022_declaracao_anual_001_abc123def456',
                'status' => 'PROCESSADO',
                'validado_em' => '2022-05-15 14:30:00',
                'validado_por' => 1,
                'observacoes' => 'Declaração anual de 2022 - Processada pela Receita Federal'
            ],
            [
                'igreja_id' => 1,
                'ano_exercicio' => 2021,
                'tipo_documento' => 'DECLARACAO_ANUAL',
                'numero_documento' => 'DA-2021-001',
                'protocolo_receita' => '2021DA00123456789',
                'data_emissao' => '2021-01-15 10:00:00',
                'data_vencimento' => '2021-04-30 23:59:59',
                'valor_total' => 85000.00,
                'valor_doacoes' => 45000.00,
                'valor_dizimos' => 30000.00,
                'valor_outros' => 10000.00,
                'hash_documento' => 'hash_2021_declaracao_anual_001_abc123def456',
                'status' => 'PROCESSADO',
                'validado_em' => '2021-05-15 14:30:00',
                'validado_por' => 1,
                'observacoes' => 'Declaração anual de 2021 - Processada pela Receita Federal'
            ],
            [
                'igreja_id' => 1,
                'ano_exercicio' => 2020,
                'tipo_documento' => 'DECLARACAO_ANUAL',
                'numero_documento' => 'DA-2020-001',
                'protocolo_receita' => '2020DA00123456789',
                'data_emissao' => '2020-01-15 10:00:00',
                'data_vencimento' => '2020-04-30 23:59:59',
                'valor_total' => 75000.00,
                'valor_doacoes' => 40000.00,
                'valor_dizimos' => 25000.00,
                'valor_outros' => 10000.00,
                'hash_documento' => 'hash_2020_declaracao_anual_001_abc123def456',
                'status' => 'PROCESSADO',
                'validado_em' => '2020-05-15 14:30:00',
                'validado_por' => 1,
                'observacoes' => 'Declaração anual de 2020 - Processada pela Receita Federal'
            ]
        ];

        foreach ($documentos as $documento) {
            DocumentoDeclaracaoAnual::updateOrCreate(
                ['protocolo_receita' => $documento['protocolo_receita']],
                $documento
            );
        }

        $this->command->info('✅ Documentos de declaração anual demonstrativos criados com sucesso');
        $this->command->info('📊 Total de documentos: ' . count($documentos));
        
        // Estatísticas
        $pendentes = collect($documentos)->where('status', 'PENDENTE')->count();
        $processados = collect($documentos)->where('status', 'PROCESSADO')->count();
        $totalValor = collect($documentos)->sum('valor_total');
        
        $this->command->info("📄 Pendentes: {$pendentes}");
        $this->command->info("✅ Processados: {$processados}");
        $this->command->info("💰 Valor total: R$ " . number_format($totalValor, 2, ',', '.'));
    }
} 