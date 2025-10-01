<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Intercessao;
use App\Models\PedidoOracao;
use App\Models\User;

class IntercessaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🙏 Criando intercessões demonstrativas...');

        // Obter pedidos de oração e usuários
        $pedidos = PedidoOracao::all();
        $users = User::all();
        
        if ($pedidos->isEmpty() || $users->isEmpty()) {
            $this->command->warn('⚠️ Nenhum pedido de oração ou usuário encontrado. Criando intercessões sem associação...');
            return;
        }

        $intercessoes = [
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(1),
                'tempo_oracao' => 30,
                'observacoes' => 'Orei pela provisão financeira da família. Deus é fiel!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subHours(12),
                'tempo_oracao' => 45,
                'observacoes' => 'Orei pela cura da pressão alta. Deus tem poder para curar!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subHours(6),
                'tempo_oracao' => 60,
                'observacoes' => 'Orei pela conversão. Que Deus toque o coração dela!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(1),
                'tempo_oracao' => 40,
                'observacoes' => 'Orei pela igreja e todos os ministérios. Que Deus abençoe!',
                'tipo_oracao' => 'igreja'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subHours(8),
                'tempo_oracao' => 35,
                'observacoes' => 'Orei pela proteção da gravidez. Que Deus guarde mãe e bebê!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subHours(4),
                'tempo_oracao' => 50,
                'observacoes' => 'Orei pela saúde mental. Que Deus dê paz e conforto!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subHours(2),
                'tempo_oracao' => 25,
                'observacoes' => 'Orei pela cura dos pais. Deus tem poder para curar!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(2),
                'tempo_oracao' => 30,
                'observacoes' => 'Orei pelo trabalho. Que Deus abra as portas certas!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(3),
                'tempo_oracao' => 40,
                'observacoes' => 'Orei pelos estudos de teologia. Que Deus dê sabedoria!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(1),
                'tempo_oracao' => 35,
                'observacoes' => 'Orei pelo ministério de jovens. Que Deus levante líderes!',
                'tipo_oracao' => 'grupo'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(2),
                'tempo_oracao' => 45,
                'observacoes' => 'Orei pelo evangelismo. Que Deus abra portas!',
                'tipo_oracao' => 'igreja'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(3),
                'tempo_oracao' => 30,
                'observacoes' => 'Orei pelos estudos. Que Deus dê força e sabedoria!',
                'tipo_oracao' => 'individual'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(1),
                'tempo_oracao' => 40,
                'observacoes' => 'Orei pela ação social. Que Deus nos ajude a ajudar!',
                'tipo_oracao' => 'grupo'
            ],
            [
                'pedido_id' => $pedidos->first()->id,
                'user_id' => $users->first()->id,
                'data_oracao' => now()->subDays(4),
                'tempo_oracao' => 35,
                'observacoes' => 'Orei pela família e pelo filho na escola. Que Deus ajude!',
                'tipo_oracao' => 'individual'
            ]
        ];

        foreach ($intercessoes as $intercessao) {
            Intercessao::updateOrCreate(
                ['pedido_id' => $intercessao['pedido_id'], 'user_id' => $intercessao['user_id'], 'data_oracao' => $intercessao['data_oracao']],
                $intercessao
            );
        }

        $this->command->info('✅ Intercessões demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de intercessões: ' . count($intercessoes));
        
        // Estatísticas
        $individual = collect($intercessoes)->where('tipo_oracao', 'individual')->count();
        $grupo = collect($intercessoes)->where('tipo_oracao', 'grupo')->count();
        $igreja = collect($intercessoes)->where('tipo_oracao', 'igreja')->count();
        $totalMinutos = collect($intercessoes)->sum('tempo_oracao');
        $mediaMinutos = $totalMinutos / count($intercessoes);
        
        $this->command->info("🙏 Individual: {$individual}");
        $this->command->info("👥 Grupo: {$grupo}");
        $this->command->info("⛪ Igreja: {$igreja}");
        $this->command->info("⏱️ Total de minutos: {$totalMinutos}");
        $this->command->info("📊 Média por intercessão: " . number_format($mediaMinutos, 1) . " minutos");
    }
} 