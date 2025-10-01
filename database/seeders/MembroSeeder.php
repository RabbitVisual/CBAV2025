<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membro;

class MembroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Criando membros demonstrativos...');

        $membros = [
            [
                'nome' => 'João Silva Santos',
                'email' => 'joao.silva@email.com',
                'telefone' => '(11) 99999-1111',
                'data_nascimento' => '1985-03-15',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Rua das Flores, 123',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567',
                'ativo' => true,
                'data_batismo' => '2010-06-20',
                'data_ingresso' => '2008-09-15',
                'observacoes' => 'Membro ativo, participa do ministério de louvor'
            ],
            [
                'nome' => 'Maria Santos Oliveira',
                'email' => 'maria.santos@email.com',
                'telefone' => '(11) 99999-2222',
                'data_nascimento' => '1990-07-22',
                'sexo' => 'F',
                'estado_civil' => 'casado',
                'endereco' => 'Avenida Principal, 456',
                'bairro' => 'Vila Nova',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-789',
                'ativo' => true,
                'data_batismo' => '2012-12-10',
                'data_ingresso' => '2010-03-25',
                'observacoes' => 'Líder do ministério infantil'
            ],
            [
                'nome' => 'Pedro Costa Lima',
                'email' => 'pedro.costa@email.com',
                'telefone' => '(11) 99999-3333',
                'data_nascimento' => '1988-11-08',
                'sexo' => 'M',
                'estado_civil' => 'solteiro',
                'endereco' => 'Rua do Comércio, 789',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-012',
                'ativo' => true,
                'data_batismo' => '2015-04-15',
                'data_ingresso' => '2013-08-30',
                'observacoes' => 'Músico do ministério de louvor'
            ],
            [
                'nome' => 'Ana Paula Ferreira',
                'email' => 'ana.ferreira@email.com',
                'telefone' => '(11) 99999-4444',
                'data_nascimento' => '1992-05-12',
                'sexo' => 'F',
                'estado_civil' => 'casado',
                'endereco' => 'Rua das Palmeiras, 321',
                'bairro' => 'Jardim São Paulo',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-345',
                'ativo' => true,
                'data_batismo' => '2018-09-20',
                'data_ingresso' => '2016-12-05',
                'observacoes' => 'Cantora do ministério de louvor'
            ],
            [
                'nome' => 'Carlos Eduardo Almeida',
                'email' => 'carlos.almeida@email.com',
                'telefone' => '(11) 99999-5555',
                'data_nascimento' => '1983-12-03',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Avenida das Indústrias, 654',
                'bairro' => 'Vila Industrial',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-678',
                'ativo' => true,
                'data_batismo' => '2005-11-08',
                'data_ingresso' => '2003-06-15',
                'observacoes' => 'Diácono da igreja'
            ],
            [
                'nome' => 'Lucia Helena Rodrigues',
                'email' => 'lucia.rodrigues@email.com',
                'telefone' => '(11) 99999-6666',
                'data_nascimento' => '1975-08-25',
                'sexo' => 'F',
                'estado_civil' => 'casado',
                'endereco' => 'Rua dos Ipês, 987',
                'bairro' => 'Jardim das Flores',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-901',
                'ativo' => true,
                'data_batismo' => '2000-03-12',
                'data_ingresso' => '1998-09-20',
                'observacoes' => 'Presbítera da igreja'
            ],
            [
                'nome' => 'Roberto Alves Silva',
                'email' => 'roberto.alves@email.com',
                'telefone' => '(11) 99999-7777',
                'data_nascimento' => '1980-01-30',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Rua das Acácias, 456',
                'bairro' => 'Vila Madalena',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-234',
                'ativo' => true,
                'data_batismo' => '2008-07-18',
                'data_ingresso' => '2006-11-10',
                'observacoes' => 'Líder do ministério de evangelismo'
            ],
            [
                'nome' => 'Fernanda Costa Santos',
                'email' => 'fernanda.costa@email.com',
                'telefone' => '(11) 99999-8888',
                'data_nascimento' => '1987-09-14',
                'sexo' => 'F',
                'estado_civil' => 'casado',
                'endereco' => 'Avenida Paulista, 1234',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-456',
                'ativo' => true,
                'data_batismo' => '2011-05-25',
                'data_ingresso' => '2009-02-15',
                'observacoes' => 'Líder do ministério de ação social'
            ],
            [
                'nome' => 'Ricardo Oliveira Lima',
                'email' => 'ricardo.oliveira@email.com',
                'telefone' => '(11) 99999-9999',
                'data_nascimento' => '1982-06-08',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Rua Augusta, 789',
                'bairro' => 'Consolação',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-789',
                'ativo' => true,
                'data_batismo' => '2007-12-03',
                'data_ingresso' => '2005-08-20',
                'observacoes' => 'Líder do ministério de ensino'
            ],
            [
                'nome' => 'Patricia Santos Almeida',
                'email' => 'patricia.santos@email.com',
                'telefone' => '(11) 99999-0000',
                'data_nascimento' => '1989-04-22',
                'sexo' => 'F',
                'estado_civil' => 'solteiro',
                'endereco' => 'Rua Oscar Freire, 567',
                'bairro' => 'Jardins',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-012',
                'ativo' => true,
                'data_batismo' => '2013-10-12',
                'data_ingresso' => '2011-07-05',
                'observacoes' => 'Líder do ministério de hospitalidade'
            ],
            [
                'nome' => 'Marcelo Ferreira Costa',
                'email' => 'marcelo.ferreira@email.com',
                'telefone' => '(11) 99999-1111',
                'data_nascimento' => '1984-11-18',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Rua Haddock Lobo, 890',
                'bairro' => 'Cerqueira César',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-345',
                'ativo' => true,
                'data_batismo' => '2009-03-30',
                'data_ingresso' => '2007-01-15',
                'observacoes' => 'Líder do ministério de tecnologia'
            ],
            [
                'nome' => 'Camila Rodrigues Silva',
                'email' => 'camila.rodrigues@email.com',
                'telefone' => '(11) 99999-2222',
                'data_nascimento' => '1991-12-05',
                'sexo' => 'F',
                'estado_civil' => 'casado',
                'endereco' => 'Rua Pamplona, 123',
                'bairro' => 'Jardins',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-678',
                'ativo' => true,
                'data_batismo' => '2014-08-15',
                'data_ingresso' => '2012-05-20',
                'observacoes' => 'Líder do ministério de comunicação'
            ],
            [
                'nome' => 'Thiago Alves Costa',
                'email' => 'thiago.alves@email.com',
                'telefone' => '(11) 99999-3333',
                'data_nascimento' => '1986-07-12',
                'sexo' => 'M',
                'estado_civil' => 'casado',
                'endereco' => 'Rua Bela Cintra, 456',
                'bairro' => 'Consolação',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-901',
                'ativo' => true,
                'data_batismo' => '2010-11-08',
                'data_ingresso' => '2008-06-25',
                'observacoes' => 'Líder do ministério de jovens'
            ],
            [
                'nome' => 'Juliana Lima Santos',
                'email' => 'juliana.lima@email.com',
                'telefone' => '(11) 99999-4444',
                'data_nascimento' => '1993-02-28',
                'sexo' => 'F',
                'estado_civil' => 'solteiro',
                'endereco' => 'Rua Estados Unidos, 789',
                'bairro' => 'Jardim América',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-234',
                'ativo' => true,
                'data_batismo' => '2016-04-10',
                'data_ingresso' => '2014-09-15',
                'observacoes' => 'Líder do ministério de crianças'
            ]
        ];

        foreach ($membros as $membro) {
            Membro::updateOrCreate(
                ['email' => $membro['email']],
                $membro
            );
        }

        $this->command->info('✅ Membros demonstrativos criados com sucesso');
        $this->command->info('📊 Total de membros: ' . count($membros));
        
        // Estatísticas
        $ativos = collect($membros)->where('ativo', true)->count();
        $homens = collect($membros)->where('sexo', 'M')->count();
        $mulheres = collect($membros)->where('sexo', 'F')->count();
        $casados = collect($membros)->where('estado_civil', 'casado')->count();
        $solteiros = collect($membros)->where('estado_civil', 'solteiro')->count();
        
        $this->command->info("✅ Ativos: {$ativos}");
        $this->command->info("👨 Homens: {$homens}");
        $this->command->info("👩 Mulheres: {$mulheres}");
        $this->command->info("💑 Casados: {$casados}");
        $this->command->info("💍 Solteiros: {$solteiros}");
    }
} 