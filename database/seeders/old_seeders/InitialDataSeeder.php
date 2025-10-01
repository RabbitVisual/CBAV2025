<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ministerio;
use App\Models\Departamento;
use App\Models\Cargo;
use App\Models\Configuracao;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        $admin = User::create([
            'name' => 'Reinan Rodrigues',
            'email' => 'admin@cbav.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Super Admin');

        // Criar ministérios
        $ministerios = [
            [
                'nome' => 'Louvor e Adoração',
                'descricao' => 'Ministério responsável pela música e adoração',
                'cor' => '#3B82F6',
                'icone' => 'music'
            ],
            [
                'nome' => 'Jovens',
                'descricao' => 'Ministério de jovens e adolescentes',
                'cor' => '#10B981',
                'icone' => 'users'
            ],
            [
                'nome' => 'Crianças',
                'descricao' => 'Ministério infantil',
                'cor' => '#F59E0B',
                'icone' => 'child'
            ],
            [
                'nome' => 'Evangelismo',
                'descricao' => 'Ministério de evangelismo e missões',
                'cor' => '#EF4444',
                'icone' => 'cross'
            ],
            [
                'nome' => 'Ação Social',
                'descricao' => 'Ministério de ação social e caridade',
                'cor' => '#8B5CF6',
                'icone' => 'heart'
            ]
        ];

        foreach ($ministerios as $ministerioData) {
            $ministerio = Ministerio::create($ministerioData);
            
            // Criar departamentos para cada ministério
            $departamentos = [
                [
                    'nome' => 'Coordenação',
                    'descricao' => 'Coordenação geral do ministério',
                    'ministerio_id' => $ministerio->id
                ],
                [
                    'nome' => 'Apoio',
                    'descricao' => 'Equipe de apoio do ministério',
                    'ministerio_id' => $ministerio->id
                ]
            ];

            foreach ($departamentos as $departamentoData) {
                $departamento = Departamento::create($departamentoData);
                
                // Criar cargos para cada departamento
                $cargos = [
                    [
                        'nome' => 'Coordenador',
                        'descricao' => 'Coordenador do departamento',
                        'departamento_id' => $departamento->id
                    ],
                    [
                        'nome' => 'Auxiliar',
                        'descricao' => 'Auxiliar do departamento',
                        'departamento_id' => $departamento->id
                    ]
                ];

                foreach ($cargos as $cargoData) {
                    Cargo::create($cargoData);
                }
            }
        }

        // Criar configurações iniciais
        $configuracoes = [
            [
                'chave' => 'igreja_nome',
                'valor' => 'Congregação Batista Avenida',
                'tipo' => 'string',
                'descricao' => 'Nome da igreja'
            ],
            [
                'chave' => 'igreja_endereco',
                'valor' => 'Rua da Avenida, 123 - Centro',
                'tipo' => 'string',
                'descricao' => 'Endereço da igreja'
            ],
            [
                'chave' => 'igreja_telefone',
                'valor' => '(11) 1234-5678',
                'tipo' => 'string',
                'descricao' => 'Telefone da igreja'
            ],
            [
                'chave' => 'igreja_email',
                'valor' => 'contato@cbav.com',
                'tipo' => 'string',
                'descricao' => 'Email da igreja'
            ],
            [
                'chave' => 'stripe_public_key',
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Chave pública do Stripe'
            ],
            [
                'chave' => 'stripe_secret_key',
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Chave secreta do Stripe'
            ],
            [
                'chave' => 'mercadopago_public_key',
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Chave pública do Mercado Pago'
            ],
            [
                'chave' => 'mercadopago_access_token',
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Token de acesso do Mercado Pago'
            ],
            [
                'chave' => 'pix_chave',
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Chave PIX para doações'
            ]
        ];

        foreach ($configuracoes as $config) {
            Configuracao::create($config);
        }
    }
}
