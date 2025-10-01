<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PedidoOracao;
use App\Models\Membro;

class PedidoOracaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🙏 Criando pedidos de oração demonstrativos...');

        // Obter membros
        $membros = Membro::all();
        
        if ($membros->isEmpty()) {
            $this->command->warn('⚠️ Nenhum membro encontrado. Criando pedidos sem associação...');
            return;
        }

        $pedidos = [
            [
                'membro_id' => $membros->where('nome', 'João Silva Santos')->first()->id,
                'titulo' => 'Oração pela Família',
                'descricao' => 'Peço oração pela minha família. Estamos passando por dificuldades financeiras e precisamos da provisão de Deus.',
                'categoria' => 'familia',
                'prioridade' => 'alta',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(2),
                'observacoes' => 'Membro ativo, muito fiel',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Maria Santos Oliveira')->first()->id,
                'titulo' => 'Oração pela Saúde',
                'descricao' => 'Peço oração pela minha saúde. Tenho um problema de pressão alta e preciso de cura.',
                'categoria' => 'saude',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'data_pedido' => now()->subDays(1),
                'observacoes' => 'Membro dedicada, líder do ministério infantil',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Pedro Costa Lima')->first()->id,
                'titulo' => 'Oração pelo Trabalho',
                'descricao' => 'Peço oração pelo meu trabalho. Estou buscando uma nova oportunidade profissional.',
                'categoria' => 'trabalho',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(3),
                'observacoes' => 'Músico do ministério de louvor',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Ana Paula Ferreira')->first()->id,
                'titulo' => 'Oração pela Conversão',
                'descricao' => 'Peço oração pela minha conversão. Quero entregar minha vida a Jesus.',
                'categoria' => 'espiritual',
                'prioridade' => 'alta',
                'status' => 'pendente',
                'data_pedido' => now()->subHours(12),
                'observacoes' => 'Visitante, muito interessada',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Carlos Eduardo Almeida')->first()->id,
                'titulo' => 'Oração pela Família',
                'descricao' => 'Peço oração pela minha família. Meu filho está com problemas na escola.',
                'categoria' => 'familia',
                'prioridade' => 'media',
                'status' => 'atendido',
                'data_pedido' => now()->subDays(5),
                'data_atendimento' => now()->subDays(2),
                'observacoes' => 'Diácono da igreja',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Lucia Helena Rodrigues')->first()->id,
                'titulo' => 'Oração pela Igreja',
                'descricao' => 'Peço oração pela igreja. Que Deus abençoe todos os ministérios e líderes.',
                'categoria' => 'espiritual',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'data_pedido' => now()->subDays(1),
                'observacoes' => 'Presbítera da igreja',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Roberto Alves Silva')->first()->id,
                'titulo' => 'Oração pelo Evangelismo',
                'descricao' => 'Peço oração pelo evangelismo. Que Deus abra portas para compartilhar o evangelho.',
                'categoria' => 'espiritual',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(3),
                'observacoes' => 'Líder do ministério de evangelismo',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Juliana Lima Santos')->first()->id,
                'titulo' => 'Oração pela Família',
                'descricao' => 'Peço oração pela minha família. Meus pais estão doentes e precisamos de cura.',
                'categoria' => 'familia',
                'prioridade' => 'alta',
                'status' => 'pendente',
                'data_pedido' => now()->subHours(24),
                'observacoes' => 'Auxiliar do ministério infantil',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Fernanda Costa Santos')->first()->id,
                'titulo' => 'Oração pela Igreja',
                'descricao' => 'Peço oração pela igreja. Que Deus envie mais pessoas para conhecer a Cristo.',
                'categoria' => 'espiritual',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(2),
                'observacoes' => 'Líder do ministério de intercessão',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Camila Rodrigues Silva')->first()->id,
                'titulo' => 'Oração pelos Estudos',
                'descricao' => 'Peço oração pelos meus estudos. Estou no último ano da faculdade.',
                'categoria' => 'trabalho',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(4),
                'observacoes' => 'Membro novo, muito animada',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'membro_id' => $membros->where('nome', 'Thiago Alves Costa')->first()->id,
                'titulo' => 'Oração pela Ação Social',
                'descricao' => 'Peço oração pela ação social. Que Deus nos ajude a ajudar mais pessoas.',
                'categoria' => 'outros',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_pedido' => now()->subDays(1),
                'observacoes' => 'Líder do ministério de ação social',
                'anonimo' => false,
                'pode_compartilhar' => true
            ]
        ];

        foreach ($pedidos as $pedido) {
            PedidoOracao::updateOrCreate(
                ['titulo' => $pedido['titulo'], 'membro_id' => $pedido['membro_id']],
                $pedido
            );
        }

        $this->command->info('✅ Pedidos de oração demonstrativos criados com sucesso');
        $this->command->info('📊 Total de pedidos: ' . count($pedidos));
        
        // Estatísticas
        $altas = collect($pedidos)->where('prioridade', 'alta')->count();
        $medias = collect($pedidos)->where('prioridade', 'media')->count();
        $familia = collect($pedidos)->where('categoria', 'familia')->count();
        $saude = collect($pedidos)->where('categoria', 'saude')->count();
        $espiritual = collect($pedidos)->where('categoria', 'espiritual')->count();
        
        $this->command->info("🔴 Alta prioridade: {$altas}");
        $this->command->info("🟡 Média prioridade: {$medias}");
        $this->command->info("👨‍👩‍👧‍👦 Família: {$familia}");
        $this->command->info("🏥 Saúde: {$saude}");
        $this->command->info("🙏 Espiritual: {$espiritual}");
    }
} 