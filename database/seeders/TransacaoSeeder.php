<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;
use App\Models\Campanha;
use App\Models\Membro;

class TransacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💳 Criando transações demonstrativas...');

        // Obter dados necessários
        $campanhas = Campanha::all();
        $membros = Membro::all();

        if ($campanhas->isEmpty() || $membros->isEmpty()) {
            $this->command->warn('⚠️ Dados necessários não encontrados. Criando transações sem associação...');
            return;
        }

        $transacoes = [
            // Transações de Dízimo
            [
                'valor' => 500.00,
                'tipo' => 'dizimo',
                'descricao' => 'Dízimo mensal de João Silva',
                'data' => now()->subDays(5),
                'status' => 'confirmado',
                'membro_id' => $membros->where('nome', 'João Silva Santos')->first()->id,
                'campanha_id' => null
            ],
            [
                'valor' => 300.00,
                'tipo' => 'dizimo',
                'descricao' => 'Dízimo mensal de Maria Santos',
                'data' => now()->subDays(4),
                'status' => 'confirmado',
                'membro_id' => $membros->where('nome', 'Maria Santos Oliveira')->first()->id,
                'campanha_id' => null
            ],
            [
                'valor' => 200.00,
                'tipo' => 'dizimo',
                'descricao' => 'Dízimo mensal de Pedro Costa',
                'data' => now()->subDays(3),
                'status' => 'confirmado',
                'membro_id' => $membros->where('nome', 'Pedro Costa Lima')->first()->id,
                'campanha_id' => null
            ],

            // Transações de Oferta
            [
                'valor' => 50.00,
                'tipo' => 'oferta',
                'descricao' => 'Oferta especial para manutenção',
                'data' => now()->subDays(2),
                'status' => 'confirmado',
                'membro_id' => $membros->random()->id,
                'campanha_id' => null
            ],
            [
                'valor' => 100.00,
                'tipo' => 'oferta',
                'descricao' => 'Oferta para projetos especiais',
                'data' => now()->subDays(1),
                'status' => 'confirmado',
                'membro_id' => $membros->random()->id,
                'campanha_id' => null
            ],

            // Transações de Doação para Campanhas
            [
                'valor' => 100.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para reforma do templo',
                'data' => now()->subDays(3),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Reforma do Templo')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 150.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para compra de instrumentos',
                'data' => now()->subDays(8),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Compra de Instrumentos')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 75.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para ação social',
                'data' => now()->subDays(13),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Ação Social - Natal')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 200.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para tecnologia',
                'data' => now()->subDays(1),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Tecnologia - Transmissão')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 300.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para missões',
                'data' => now()->subDays(6),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Missões - África')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 120.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para evangelismo',
                'data' => now()->subDays(4),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Evangelismo - Projeto Luz')->first()->id,
                'membro_id' => $membros->random()->id
            ],

            // Transações Pendentes
            [
                'valor' => 80.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação pendente para jovens',
                'data' => now()->subHours(2),
                'status' => 'pendente',
                'campanha_id' => $campanhas->where('titulo', 'Jovens - Acampamento')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 150.00,
                'tipo' => 'dizimo',
                'descricao' => 'Dízimo pendente',
                'data' => now()->subHours(1),
                'status' => 'pendente',
                'membro_id' => $membros->random()->id,
                'campanha_id' => null
            ],

            // Transações Canceladas
            [
                'valor' => 250.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação cancelada',
                'data' => now()->subDays(20),
                'status' => 'cancelado',
                'campanha_id' => $campanhas->where('titulo', 'Infantil - Material Didático')->first()->id,
                'membro_id' => $membros->random()->id
            ],

            // Transações de Intercessão
            [
                'valor' => 1000.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para intercessão',
                'data' => now()->subDays(1),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Intercessão - Retiro')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 500.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para intercessão',
                'data' => now()->subDays(2),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Intercessão - Retiro')->first()->id,
                'membro_id' => $membros->random()->id
            ],

            // Transações de Hospitalidade
            [
                'valor' => 5000.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para hospitalidade',
                'data' => now()->subDays(5),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Hospitalidade - Cozinha')->first()->id,
                'membro_id' => $membros->random()->id
            ],
            [
                'valor' => 3000.00,
                'tipo' => 'doacao',
                'descricao' => 'Doação para hospitalidade',
                'data' => now()->subDays(10),
                'status' => 'confirmado',
                'campanha_id' => $campanhas->where('titulo', 'Hospitalidade - Cozinha')->first()->id,
                'membro_id' => $membros->random()->id
            ]
        ];

        foreach ($transacoes as $transacao) {
            Transacao::create($transacao);
        }

        $this->command->info('✅ Transações demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de transações: ' . count($transacoes));
        
        // Mostrar resumo das transações
        $totalConfirmadas = collect($transacoes)->where('status', 'confirmado')->sum('valor');
        $totalPendentes = collect($transacoes)->where('status', 'pendente')->sum('valor');
        $totalCanceladas = collect($transacoes)->where('status', 'cancelado')->sum('valor');
        
        $this->command->info("💰 Total confirmadas: R$ " . number_format($totalConfirmadas, 2, ',', '.'));
        $this->command->info("⏳ Total pendentes: R$ " . number_format($totalPendentes, 2, ',', '.'));
        $this->command->info("❌ Total canceladas: R$ " . number_format($totalCanceladas, 2, ',', '.'));
    }
} 