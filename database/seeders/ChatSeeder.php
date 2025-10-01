<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\User;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar salas de chat padrão
        $rooms = [
            [
                'nome' => 'Geral',
                'descricao' => 'Sala geral para todos os membros da igreja',
                'tipo' => 'publico',
                'cor' => '#3b82f6',
                'icone' => 'fas fa-comments',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Jovens',
                'descricao' => 'Sala específica para jovens da igreja',
                'tipo' => 'publico',
                'cor' => '#10b981',
                'icone' => 'fas fa-users',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Oração',
                'descricao' => 'Sala para compartilhar pedidos de oração',
                'tipo' => 'publico',
                'cor' => '#8b5cf6',
                'icone' => 'fas fa-pray',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Estudo Bíblico',
                'descricao' => 'Sala para discussões sobre estudos bíblicos',
                'tipo' => 'publico',
                'cor' => '#f59e0b',
                'icone' => 'fas fa-bible',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Administração',
                'descricao' => 'Sala para administradores da igreja',
                'tipo' => 'admin',
                'cor' => '#ef4444',
                'icone' => 'fas fa-shield-alt',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Ministério de Música',
                'descricao' => 'Sala para membros do ministério de música',
                'tipo' => 'ministerio',
                'cor' => '#06b6d4',
                'icone' => 'fas fa-music',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Ministério de Jovens',
                'descricao' => 'Sala para líderes do ministério de jovens',
                'tipo' => 'ministerio',
                'cor' => '#84cc16',
                'icone' => 'fas fa-child',
                'ativo' => true,
                'max_participantes' => null
            ],
            [
                'nome' => 'Ministério de Crianças',
                'descricao' => 'Sala para líderes do ministério de crianças',
                'tipo' => 'ministerio',
                'cor' => '#f97316',
                'icone' => 'fas fa-baby',
                'ativo' => true,
                'max_participantes' => null
            ]
        ];

        foreach ($rooms as $roomData) {
            $room = ChatRoom::create($roomData);
            
            // Adicionar usuários admin como participantes das salas admin
            if ($room->tipo === 'admin') {
                $adminUsers = User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
                
                foreach ($adminUsers as $admin) {
                    ChatParticipant::create([
                        'chat_room_id' => $room->id,
                        'user_id' => $admin->id,
                        'tipo' => 'admin',
                        'ativo' => true,
                        'ultimo_acesso' => now()
                    ]);
                }
            }
        }

        $this->command->info('Salas de chat criadas com sucesso!');
    }
} 