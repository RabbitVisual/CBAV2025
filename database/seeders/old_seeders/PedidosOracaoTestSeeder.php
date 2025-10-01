<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{PedidoOracao, Intercessao, User, Membro};
use Illuminate\Support\Facades\Hash;

class PedidosOracaoTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Populando dados de teste para Sistema de Pedidos de Oração...');

        // Criar membros de teste se não existirem
        $membros = $this->criarMembrosTeste();
        
        // Criar usuários intercessores se não existirem
        $intercessores = $this->criarIntercessoresTeste();

        // Criar pedidos de oração de teste
        $this->criarPedidosTeste($membros);

        // Criar intercessões de teste
        $this->criarIntercessoesTeste($intercessores);

        $this->command->info('✅ Dados de teste criados com sucesso!');
        $this->command->info('');
        $this->command->info('📋 Dados criados:');
        $this->command->info('- ' . count($membros) . ' membros de teste');
        $this->command->info('- ' . count($intercessores) . ' intercessores de teste');
        $this->command->info('- 15 pedidos de oração de teste');
        $this->command->info('- 8 intercessões de teste');
        $this->command->info('');
        $this->command->info('🔑 Credenciais de teste:');
        $this->command->info('Membro: membro@teste.com / senha123');
        $this->command->info('Intercessor: intercessor@teste.com / senha123');
    }

    /**
     * Criar membros de teste
     */
    private function criarMembrosTeste()
    {
        $membros = [];

        // Membro 1
        $membro1 = Membro::firstOrCreate(
            ['email' => 'membro@teste.com'],
            [
                'nome' => 'João Silva',
                'email' => 'membro@teste.com',
                'telefone' => '(11) 99999-9999',
                'data_nascimento' => '1985-05-15',
                'endereco' => 'Rua das Flores, 123',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567',
                'ativo' => true,
                'data_batismo' => '2010-06-20'
            ]
        );

        // Criar usuário para o membro
        $user1 = User::firstOrCreate(
            ['email' => 'membro@teste.com'],
            [
                'name' => 'João Silva',
                'email' => 'membro@teste.com',
                'password' => Hash::make('senha123')
            ]
        );

        $membros[] = $membro1;

        // Membro 2
        $membro2 = Membro::firstOrCreate(
            ['email' => 'maria@teste.com'],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria@teste.com',
                'telefone' => '(11) 88888-8888',
                'data_nascimento' => '1990-03-10',
                'endereco' => 'Av. Paulista, 456',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01310-000',
                'ativo' => true,
                'data_batismo' => '2015-08-15'
            ]
        );

        // Criar usuário para o membro
        $user2 = User::firstOrCreate(
            ['email' => 'maria@teste.com'],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@teste.com',
                'password' => Hash::make('senha123')
            ]
        );

        $membros[] = $membro2;

        return $membros;
    }

    /**
     * Criar intercessores de teste
     */
    private function criarIntercessoresTeste()
    {
        $intercessores = [];

        // Intercessor 1
        $intercessor1 = User::firstOrCreate(
            ['email' => 'intercessor@teste.com'],
            [
                'name' => 'Pedro Intercessor',
                'email' => 'intercessor@teste.com',
                'password' => Hash::make('senha123')
            ]
        );

        // Atribuir role de intercessor
        $intercessor1->assignRole('intercessor');
        $intercessores[] = $intercessor1;

        // Intercessor 2
        $intercessor2 = User::firstOrCreate(
            ['email' => 'ana.intercessor@teste.com'],
            [
                'name' => 'Ana Intercessora',
                'email' => 'ana.intercessor@teste.com',
                'password' => Hash::make('senha123')
            ]
        );

        // Atribuir role de intercessor
        $intercessor2->assignRole('intercessor');
        $intercessores[] = $intercessor2;

        return $intercessores;
    }

    /**
     * Criar pedidos de oração de teste
     */
    private function criarPedidosTeste($membros)
    {
        $categorias = ['saude', 'familia', 'trabalho', 'espiritual', 'outros'];
        $prioridades = ['baixa', 'media', 'alta', 'urgente'];
        $status = ['pendente', 'em_oracao', 'atendido'];

        $pedidos = [
            [
                'titulo' => 'Cura para minha mãe',
                'descricao' => 'Minha mãe está com problemas de saúde e precisa de oração para se recuperar.',
                'categoria' => 'saude',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Reconciliação familiar',
                'descricao' => 'Preciso de oração para reconciliação com minha família.',
                'categoria' => 'familia',
                'prioridade' => 'urgente',
                'status' => 'pendente',
                'anonimo' => true,
                'pode_compartilhar' => false
            ],
            [
                'titulo' => 'Novo emprego',
                'descricao' => 'Estou desempregado e preciso de um novo trabalho.',
                'categoria' => 'trabalho',
                'prioridade' => 'media',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Crescimento espiritual',
                'descricao' => 'Quero crescer mais na fé e ter uma vida mais próxima de Deus.',
                'categoria' => 'espiritual',
                'prioridade' => 'media',
                'status' => 'pendente',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Prova da faculdade',
                'descricao' => 'Tenho uma prova importante na faculdade e preciso de sabedoria.',
                'categoria' => 'outros',
                'prioridade' => 'baixa',
                'status' => 'atendido',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Cura do diabetes',
                'descricao' => 'Preciso de oração para cura do diabetes.',
                'categoria' => 'saude',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Casamento em crise',
                'descricao' => 'Meu casamento está passando por dificuldades.',
                'categoria' => 'familia',
                'prioridade' => 'urgente',
                'status' => 'pendente',
                'anonimo' => true,
                'pode_compartilhar' => false
            ],
            [
                'titulo' => 'Promoção no trabalho',
                'descricao' => 'Estou concorrendo a uma promoção no trabalho.',
                'categoria' => 'trabalho',
                'prioridade' => 'media',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Libertação de vícios',
                'descricao' => 'Preciso de oração para vencer vícios.',
                'categoria' => 'espiritual',
                'prioridade' => 'alta',
                'status' => 'pendente',
                'anonimo' => true,
                'pode_compartilhar' => false
            ],
            [
                'titulo' => 'Viagem missionária',
                'descricao' => 'Vou participar de uma viagem missionária e preciso de oração.',
                'categoria' => 'outros',
                'prioridade' => 'media',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Cirurgia do pai',
                'descricao' => 'Meu pai vai fazer uma cirurgia cardíaca.',
                'categoria' => 'saude',
                'prioridade' => 'urgente',
                'status' => 'pendente',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Filho rebelde',
                'descricao' => 'Meu filho está com problemas de comportamento.',
                'categoria' => 'familia',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Mudança de carreira',
                'descricao' => 'Estou pensando em mudar de carreira.',
                'categoria' => 'trabalho',
                'prioridade' => 'media',
                'status' => 'pendente',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Batismo do Espírito Santo',
                'descricao' => 'Quero receber o batismo do Espírito Santo.',
                'categoria' => 'espiritual',
                'prioridade' => 'alta',
                'status' => 'em_oracao',
                'anonimo' => false,
                'pode_compartilhar' => true
            ],
            [
                'titulo' => 'Compra de casa',
                'descricao' => 'Estou tentando comprar uma casa.',
                'categoria' => 'outros',
                'prioridade' => 'media',
                'status' => 'pendente',
                'anonimo' => false,
                'pode_compartilhar' => true
            ]
        ];

        foreach ($pedidos as $index => $pedido) {
            $membro = $membros[$index % count($membros)];
            
            PedidoOracao::firstOrCreate(
                [
                    'membro_id' => $membro->id,
                    'titulo' => $pedido['titulo']
                ],
                [
                    'membro_id' => $membro->id,
                    'titulo' => $pedido['titulo'],
                    'descricao' => $pedido['descricao'],
                    'categoria' => $pedido['categoria'],
                    'prioridade' => $pedido['prioridade'],
                    'status' => $pedido['status'],
                    'data_pedido' => now()->subDays(rand(1, 30)),
                    'data_atendimento' => $pedido['status'] === 'atendido' ? now()->subDays(rand(1, 7)) : null,
                    'anonimo' => $pedido['anonimo'],
                    'pode_compartilhar' => $pedido['pode_compartilhar']
                ]
            );
        }
    }

    /**
     * Criar intercessões de teste
     */
    private function criarIntercessoesTeste($intercessores)
    {
        $pedidos = PedidoOracao::whereIn('status', ['em_oracao', 'atendido'])->get();
        $tipos = ['individual', 'grupo', 'igreja'];

        foreach ($pedidos as $index => $pedido) {
            $intercessor = $intercessores[$index % count($intercessores)];
            
            Intercessao::firstOrCreate(
                [
                    'pedido_id' => $pedido->id,
                    'user_id' => $intercessor->id
                ],
                [
                    'pedido_id' => $pedido->id,
                    'user_id' => $intercessor->id,
                    'data_oracao' => now()->subDays(rand(1, 7)),
                    'observacoes' => 'Orei por este pedido com fé e confiança.',
                    'tempo_oracao' => rand(5, 30),
                    'tipo_oracao' => $tipos[array_rand($tipos)]
                ]
            );
        }
    }
} 