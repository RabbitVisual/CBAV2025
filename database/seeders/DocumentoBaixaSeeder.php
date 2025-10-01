<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentoBaixa;
use App\Models\Transacao;

class DocumentoBaixaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📋 Criando documentos de baixa demonstrativos...');

        // Obter transações para associar
        $transacoes = Transacao::all();
        
        if ($transacoes->isEmpty()) {
            $this->command->warn('⚠️ Nenhuma transação encontrada. Criando documentos sem associação...');
            return;
        }

        $documentos = [
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DARF',
                'numero_documento' => 'BAIXA-2024-001',
                'ano_exercicio' => 2024,
                'data_emissao' => '2024-01-15',
                'data_vencimento' => '2024-02-15',
                'valor_documento' => 5000.00,
                'valor_pago' => 5000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para reforma do templo',
                'hash_documento' => 'hash_baixa_2024_001_abc123def456',
                'protocolo_receita' => 'RF2024BAIXA001'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'GPS',
                'numero_documento' => 'BAIXA-2024-002',
                'ano_exercicio' => 2024,
                'data_emissao' => '2024-02-01',
                'data_vencimento' => '2024-03-01',
                'valor_documento' => 3000.00,
                'valor_pago' => 3000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para compra de instrumentos',
                'hash_documento' => 'hash_baixa_2024_002_abc123def456',
                'protocolo_receita' => 'RF2024BAIXA002'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DAS',
                'numero_documento' => 'BAIXA-2024-003',
                'ano_exercicio' => 2024,
                'data_emissao' => '2024-03-01',
                'data_vencimento' => '2024-04-01',
                'valor_documento' => 2000.00,
                'valor_pago' => 0.00,
                'status' => 'PENDENTE',
                'observacoes' => 'Documento de baixa para ação social',
                'hash_documento' => 'hash_baixa_2024_003_abc123def456',
                'protocolo_receita' => 'RF2024BAIXA003'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DARF',
                'numero_documento' => 'BAIXA-2023-001',
                'ano_exercicio' => 2023,
                'data_emissao' => '2023-01-15',
                'data_vencimento' => '2023-02-15',
                'valor_documento' => 4000.00,
                'valor_pago' => 4000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para manutenção do sistema de som',
                'hash_documento' => 'hash_baixa_2023_001_abc123def456',
                'protocolo_receita' => 'RF2023BAIXA001'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'GPS',
                'numero_documento' => 'BAIXA-2023-002',
                'ano_exercicio' => 2023,
                'data_emissao' => '2023-02-01',
                'data_vencimento' => '2023-03-01',
                'valor_documento' => 2500.00,
                'valor_pago' => 2500.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para material didático',
                'hash_documento' => 'hash_baixa_2023_002_abc123def456',
                'protocolo_receita' => 'RF2023BAIXA002'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DAS',
                'numero_documento' => 'BAIXA-2023-003',
                'ano_exercicio' => 2023,
                'data_emissao' => '2023-03-01',
                'data_vencimento' => '2023-04-01',
                'valor_documento' => 3500.00,
                'valor_pago' => 3500.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para equipamentos de tecnologia',
                'hash_documento' => 'hash_baixa_2023_003_abc123def456',
                'protocolo_receita' => 'RF2023BAIXA003'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DARF',
                'numero_documento' => 'BAIXA-2022-001',
                'ano_exercicio' => 2022,
                'data_emissao' => '2022-01-15',
                'data_vencimento' => '2022-02-15',
                'valor_documento' => 3000.00,
                'valor_pago' => 3000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para pintura do templo',
                'hash_documento' => 'hash_baixa_2022_001_abc123def456',
                'protocolo_receita' => 'RF2022BAIXA001'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'GPS',
                'numero_documento' => 'BAIXA-2022-002',
                'ano_exercicio' => 2022,
                'data_emissao' => '2022-02-01',
                'data_vencimento' => '2022-03-01',
                'valor_documento' => 1500.00,
                'valor_pago' => 1500.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para material de evangelismo',
                'hash_documento' => 'hash_baixa_2022_002_abc123def456',
                'protocolo_receita' => 'RF2022BAIXA002'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'DAS',
                'numero_documento' => 'BAIXA-2021-001',
                'ano_exercicio' => 2021,
                'data_emissao' => '2021-01-15',
                'data_vencimento' => '2021-02-15',
                'valor_documento' => 2000.00,
                'valor_pago' => 2000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para manutenção geral',
                'hash_documento' => 'hash_baixa_2021_001_abc123def456',
                'protocolo_receita' => 'RF2021BAIXA001'
            ],
            [
                'transacao_id' => $transacoes->first()->id,
                'tipo_documento' => 'GPS',
                'numero_documento' => 'BAIXA-2021-002',
                'ano_exercicio' => 2021,
                'data_emissao' => '2021-02-01',
                'data_vencimento' => '2021-03-01',
                'valor_documento' => 1000.00,
                'valor_pago' => 1000.00,
                'status' => 'PAGO',
                'observacoes' => 'Documento de baixa para material de limpeza',
                'hash_documento' => 'hash_baixa_2021_002_abc123def456',
                'protocolo_receita' => 'RF2021BAIXA002'
            ]
        ];

        foreach ($documentos as $documento) {
            DocumentoBaixa::updateOrCreate(
                ['protocolo_receita' => $documento['protocolo_receita']],
                $documento
            );
        }

        $this->command->info('✅ Documentos de baixa demonstrativos criados com sucesso');
        $this->command->info('📊 Total de documentos: ' . count($documentos));
        
        // Estatísticas
        $pendentes = collect($documentos)->where('status', 'PENDENTE')->count();
        $pagos = collect($documentos)->where('status', 'PAGO')->count();
        $totalValor = collect($documentos)->sum('valor_documento');
        
        $this->command->info("📄 Pendentes: {$pendentes}");
        $this->command->info("✅ Pagos: {$pagos}");
        $this->command->info("💰 Valor total: R$ " . number_format($totalValor, 2, ',', '.'));
    }
} 