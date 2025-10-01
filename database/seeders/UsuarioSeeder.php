<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Membro;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Criando usuários demonstrativos...');

        // Obter roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $pastorRole = Role::where('name', 'Pastor')->first();
        $tesoureiroRole = Role::where('name', 'Tesoureiro')->first();
        $membroRole = Role::where('name', 'Membro')->first();

        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@cbav.com',
                'password' => Hash::make('password'),
                'role' => $superAdminRole,
                'membro_data' => [
                    'nome' => 'Administrador Sistema',
                    'email' => 'admin@cbav.com',
                    'telefone' => '(11) 99999-0000',
                    'data_nascimento' => '1980-01-01',
                    'sexo' => 'M',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua do Sistema, 1',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-000',
                    'ativo' => true,
                    'data_batismo' => '1990-01-01',
                    'data_ingresso' => '1985-01-01',
                    'observacoes' => 'Usuário administrador do sistema'
                ]
            ],
            [
                'name' => 'Pastor João',
                'email' => 'pastor@cbav.com',
                'password' => Hash::make('password'),
                'role' => $pastorRole,
                'membro_data' => [
                    'nome' => 'Pr. João Silva',
                    'email' => 'pastor@cbav.com',
                    'telefone' => '(11) 99999-8888',
                    'data_nascimento' => '1975-05-15',
                    'sexo' => 'M',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua do Pastor, 123',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-123',
                    'ativo' => true,
                    'data_batismo' => '1990-06-20',
                    'data_ingresso' => '1988-03-15',
                    'observacoes' => 'Pastor da igreja'
                ]
            ],
            [
                'name' => 'Tesoureiro Maria',
                'email' => 'tesoureiro@cbav.com',
                'password' => Hash::make('password'),
                'role' => $tesoureiroRole,
                'membro_data' => [
                    'nome' => 'Maria Santos',
                    'email' => 'tesoureiro@cbav.com',
                    'telefone' => '(11) 99999-7777',
                    'data_nascimento' => '1982-08-20',
                    'sexo' => 'F',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua da Tesoureira, 456',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-456',
                    'ativo' => true,
                    'data_batismo' => '1995-12-10',
                    'data_ingresso' => '1992-07-05',
                    'observacoes' => 'Tesoureira da igreja'
                ]
            ],
            [
                'name' => 'Membro Teste',
                'email' => 'membro@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Carlos Oliveira',
                    'email' => 'membro@cbav.com',
                    'telefone' => '(11) 99999-6666',
                    'data_nascimento' => '1990-12-25',
                    'sexo' => 'M',
                    'estado_civil' => 'solteiro',
                    'endereco' => 'Rua do Membro, 789',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-789',
                    'ativo' => true,
                    'data_batismo' => '2010-04-15',
                    'data_ingresso' => '2008-09-20',
                    'observacoes' => 'Membro da igreja'
                ]
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Ana Costa',
                    'email' => 'ana@cbav.com',
                    'telefone' => '(11) 99999-5555',
                    'data_nascimento' => '1988-03-10',
                    'sexo' => 'F',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua da Ana, 321',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-321',
                    'ativo' => true,
                    'data_batismo' => '2005-08-12',
                    'data_ingresso' => '2003-11-30',
                    'observacoes' => 'Líder do ministério de louvor'
                ]
            ],
            [
                'name' => 'Pedro Santos',
                'email' => 'pedro@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Pedro Santos',
                    'email' => 'pedro@cbav.com',
                    'telefone' => '(11) 99999-4444',
                    'data_nascimento' => '1985-07-18',
                    'sexo' => 'M',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua do Pedro, 654',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-654',
                    'ativo' => true,
                    'data_batismo' => '2007-03-25',
                    'data_ingresso' => '2005-06-10',
                    'observacoes' => 'Líder do ministério de evangelismo'
                ]
            ],
            [
                'name' => 'Lucia Ferreira',
                'email' => 'lucia@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Lucia Ferreira',
                    'email' => 'lucia@cbav.com',
                    'telefone' => '(11) 99999-3333',
                    'data_nascimento' => '1978-11-05',
                    'sexo' => 'F',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua da Lucia, 987',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-987',
                    'ativo' => true,
                    'data_batismo' => '2000-09-15',
                    'data_ingresso' => '1998-12-20',
                    'observacoes' => 'Líder do ministério de ação social'
                ]
            ],
            [
                'name' => 'Roberto Lima',
                'email' => 'roberto@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Roberto Lima',
                    'email' => 'roberto@cbav.com',
                    'telefone' => '(11) 99999-2222',
                    'data_nascimento' => '1983-04-22',
                    'sexo' => 'M',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua do Roberto, 147',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-147',
                    'ativo' => true,
                    'data_batismo' => '2003-05-30',
                    'data_ingresso' => '2001-08-15',
                    'observacoes' => 'Líder do ministério de ensino'
                ]
            ],
            [
                'name' => 'Fernanda Costa',
                'email' => 'fernanda@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Fernanda Costa',
                    'email' => 'fernanda@cbav.com',
                    'telefone' => '(11) 99999-1111',
                    'data_nascimento' => '1992-09-08',
                    'sexo' => 'F',
                    'estado_civil' => 'solteiro',
                    'endereco' => 'Rua da Fernanda, 258',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-258',
                    'ativo' => true,
                    'data_batismo' => '2012-07-14',
                    'data_ingresso' => '2010-10-25',
                    'observacoes' => 'Líder do ministério de hospitalidade'
                ]
            ],
            [
                'name' => 'Ricardo Almeida',
                'email' => 'ricardo@cbav.com',
                'password' => Hash::make('password'),
                'role' => $membroRole,
                'membro_data' => [
                    'nome' => 'Ricardo Almeida',
                    'email' => 'ricardo@cbav.com',
                    'telefone' => '(11) 99999-0000',
                    'data_nascimento' => '1987-12-03',
                    'sexo' => 'M',
                    'estado_civil' => 'casado',
                    'endereco' => 'Rua do Ricardo, 369',
                    'bairro' => 'Centro',
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'cep' => '01234-369',
                    'ativo' => true,
                    'data_batismo' => '2009-11-08',
                    'data_ingresso' => '2007-02-12',
                    'observacoes' => 'Líder do ministério de tecnologia'
                ]
            ]
        ];

        foreach ($usuarios as $usuarioData) {
            // Criar usuário
            $user = User::updateOrCreate(
                ['email' => $usuarioData['email']],
                [
                    'name' => $usuarioData['name'],
                    'email' => $usuarioData['email'],
                    'password' => $usuarioData['password'],
                    'email_verified_at' => now()
                ]
            );

            // Atribuir role
            if ($usuarioData['role']) {
                $user->assignRole($usuarioData['role']);
            }

            // Criar membro
            $membro = Membro::updateOrCreate(
                ['email' => $usuarioData['membro_data']['email']],
                $usuarioData['membro_data']
            );
        }

        $this->command->info('✅ Usuários demonstrativos criados com sucesso');
        $this->command->info('📊 Total de usuários: ' . count($usuarios));
        $this->command->info('🔑 Senha padrão para todos os usuários: password');
    }
} 