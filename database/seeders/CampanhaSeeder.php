<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campanha;

class CampanhaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Criando campanhas demonstrativas...');

        $campanhas = [
            [
                'titulo' => 'Reforma do Templo',
                'descricao' => 'Campanha para reforma e manutenção do templo da igreja. Reforma do telhado, pintura e melhorias na estrutura',
                'meta_valor' => 50000.00,
                'valor_arrecadado' => 15000.00,
                'data_inicio' => '2024-01-01',
                'data_fim' => '2024-12-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Compra de Instrumentos',
                'descricao' => 'Campanha para aquisição de instrumentos musicais para o ministério de louvor. Guitarra, baixo, bateria e equipamentos de som',
                'meta_valor' => 15000.00,
                'valor_arrecadado' => 8000.00,
                'data_inicio' => '2024-02-01',
                'data_fim' => '2024-08-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Ação Social - Natal',
                'descricao' => 'Campanha para distribuição de cestas básicas e brinquedos no Natal. Cestas básicas, brinquedos e roupas para famílias carentes',
                'meta_valor' => 10000.00,
                'valor_arrecadado' => 5000.00,
                'data_inicio' => '2024-11-01',
                'data_fim' => '2024-12-25',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Evangelismo - Projeto Luz',
                'descricao' => 'Campanha para evangelização e distribuição de Bíblias. Compra de Bíblias, material evangelístico e eventos',
                'meta_valor' => 8000.00,
                'valor_arrecadado' => 3000.00,
                'data_inicio' => '2024-03-01',
                'data_fim' => '2024-09-30',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Tecnologia - Transmissão',
                'descricao' => 'Campanha para equipamentos de transmissão ao vivo. Câmeras, computador e equipamentos de transmissão',
                'meta_valor' => 25000.00,
                'valor_arrecadado' => 12000.00,
                'data_inicio' => '2024-01-15',
                'data_fim' => '2024-06-30',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Missões - África',
                'descricao' => 'Campanha para apoio missionário na África. Apoio ao missionário João Silva na África',
                'meta_valor' => 20000.00,
                'valor_arrecadado' => 15000.00,
                'data_inicio' => '2024-01-01',
                'data_fim' => '2024-12-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Jovens - Acampamento',
                'descricao' => 'Campanha para acampamento de jovens. Acampamento de jovens no final de semana',
                'meta_valor' => 5000.00,
                'valor_arrecadado' => 2500.00,
                'data_inicio' => '2024-05-01',
                'data_fim' => '2024-07-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Infantil - Material Didático',
                'descricao' => 'Campanha para material didático do ministério infantil. Material escolar, livros e brinquedos educativos',
                'meta_valor' => 3000.00,
                'valor_arrecadado' => 1500.00,
                'data_inicio' => '2024-02-15',
                'data_fim' => '2024-05-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Intercessão - Retiro',
                'descricao' => 'Campanha para retiro de intercessão. Retiro espiritual para o ministério de intercessão',
                'meta_valor' => 4000.00,
                'valor_arrecadado' => 2000.00,
                'data_inicio' => '2024-04-01',
                'data_fim' => '2024-06-30',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Hospitalidade - Cozinha',
                'descricao' => 'Campanha para equipamentos da cozinha da igreja. Geladeira, fogão e utensílios',
                'meta_valor' => 8000.00,
                'valor_arrecadado' => 4000.00,
                'data_inicio' => '2024-03-01',
                'data_fim' => '2024-08-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Ensino - Biblioteca',
                'descricao' => 'Campanha para biblioteca da igreja. Livros, estantes e material de estudo',
                'meta_valor' => 6000.00,
                'valor_arrecadado' => 3000.00,
                'data_inicio' => '2024-01-01',
                'data_fim' => '2024-10-31',
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Campanha Concluída - 2023',
                'descricao' => 'Campanha de 2023 que foi concluída com sucesso. Todos os objetivos foram atingidos',
                'meta_valor' => 10000.00,
                'valor_arrecadado' => 10000.00,
                'data_inicio' => '2023-01-01',
                'data_fim' => '2023-12-31',
                'status' => 'concluida',
                'ativo' => false
            ]
        ];

        foreach ($campanhas as $campanha) {
            Campanha::create($campanha);
        }

        $this->command->info('✅ Campanhas demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de campanhas: ' . count($campanhas));
        
        // Estatísticas
        $ativas = collect($campanhas)->where('status', 'ativa')->count();
        $concluidas = collect($campanhas)->where('status', 'concluida')->count();
        $total_meta = collect($campanhas)->sum('meta_valor');
        $total_arrecadado = collect($campanhas)->sum('valor_arrecadado');
        
        $this->command->info("✅ Ativas: {$ativas}");
        $this->command->info("✅ Concluídas: {$concluidas}");
        $this->command->info("💰 Meta total: R$ " . number_format($total_meta, 2, ',', '.'));
        $this->command->info("💰 Arrecadado total: R$ " . number_format($total_arrecadado, 2, ',', '.'));
    }
} 