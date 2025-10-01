<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdProfessor;
use App\Models\Membro;
use App\Models\EbdTurma;

class EbdProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👨‍🏫 Criando professores da EBD demonstrativos...');

        // Obter turmas para associar os professores
        $turmas = EbdTurma::all();
        $bercario = $turmas->where('nome', 'Berçário')->first();
        $maternal = $turmas->where('nome', 'Maternal')->first();
        $jardim = $turmas->where('nome', 'Jardim')->first();
        $primarios = $turmas->where('nome', 'Primários')->first();
        $juniores = $turmas->where('nome', 'Juniores')->first();
        $adolescentes = $turmas->where('nome', 'Adolescentes')->first();
        $jovens = $turmas->where('nome', 'Jovens')->first();
        $adultos = $turmas->where('nome', 'Adultos')->first();
        $senhores = $turmas->where('nome', 'Senhores')->first();
        $novos_convertidos = $turmas->where('nome', 'Novos Convertidos')->first();

        // Criar ou obter membros que serão professores
        $membros_professores = [
            [
                'nome' => 'Pr. João Silva',
                'email' => 'joao.silva@cbav.com',
                'telefone' => '(11) 99999-0001',
                'data_nascimento' => '1980-05-15',
                'turma_id' => $adultos->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria.santos@cbav.com',
                'telefone' => '(11) 99999-0002',
                'data_nascimento' => '1985-08-20',
                'turma_id' => $bercario->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Carlos Eduardo Almeida',
                'email' => 'carlos.almeida@cbav.com',
                'telefone' => '(11) 99999-0003',
                'data_nascimento' => '1975-12-10',
                'turma_id' => $primarios->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Lucia Helena Rodrigues',
                'email' => 'lucia.rodrigues@cbav.com',
                'telefone' => '(11) 99999-0004',
                'data_nascimento' => '1982-03-25',
                'turma_id' => $maternal->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Pedro Oliveira',
                'email' => 'pedro.oliveira@cbav.com',
                'telefone' => '(11) 99999-0005',
                'data_nascimento' => '1978-07-30',
                'turma_id' => $juniores->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Ana Paula Ferreira',
                'email' => 'ana.ferreira@cbav.com',
                'telefone' => '(11) 99999-0006',
                'data_nascimento' => '1990-01-15',
                'turma_id' => $bercario->id,
                'tipo' => 'auxiliar'
            ],
            [
                'nome' => 'Roberto Lima',
                'email' => 'roberto.lima@cbav.com',
                'telefone' => '(11) 99999-0007',
                'data_nascimento' => '1988-11-05',
                'turma_id' => $adolescentes->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Fernanda Lima Santos',
                'email' => 'fernanda.santos@cbav.com',
                'telefone' => '(11) 99999-0008',
                'data_nascimento' => '1987-04-18',
                'turma_id' => $maternal->id,
                'tipo' => 'auxiliar'
            ],
            [
                'nome' => 'Patricia Costa Silva',
                'email' => 'patricia.silva@cbav.com',
                'telefone' => '(11) 99999-0009',
                'data_nascimento' => '1983-09-22',
                'turma_id' => $jardim->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Roberto Silva Costa',
                'email' => 'roberto.costa@cbav.com',
                'telefone' => '(11) 99999-0010',
                'data_nascimento' => '1970-06-12',
                'turma_id' => $jardim->id,
                'tipo' => 'auxiliar'
            ],
            [
                'nome' => 'Ricardo Almeida Oliveira',
                'email' => 'ricardo.oliveira@cbav.com',
                'telefone' => '(11) 99999-0011',
                'data_nascimento' => '1985-02-28',
                'turma_id' => $primarios->id,
                'tipo' => 'auxiliar'
            ],
            [
                'nome' => 'Ana Costa',
                'email' => 'ana.costa@cbav.com',
                'telefone' => '(11) 99999-0012',
                'data_nascimento' => '1992-10-08',
                'turma_id' => $juniores->id,
                'tipo' => 'auxiliar'
            ],
            [
                'nome' => 'Pedro Costa',
                'email' => 'pedro.costa@cbav.com',
                'telefone' => '(11) 99999-0013',
                'data_nascimento' => '1965-12-03',
                'turma_id' => $senhores->id,
                'tipo' => 'principal'
            ],
            [
                'nome' => 'Lucia Ferreira',
                'email' => 'lucia.ferreira@cbav.com',
                'telefone' => '(11) 99999-0014',
                'data_nascimento' => '1972-08-14',
                'turma_id' => $senhores->id,
                'tipo' => 'auxiliar'
            ]
        ];

        $professores_criados = 0;

        foreach ($membros_professores as $professor_data) {
            // Criar ou obter o membro
            $membro = Membro::updateOrCreate(
                ['email' => $professor_data['email']],
                [
                    'nome' => $professor_data['nome'],
                    'telefone' => $professor_data['telefone'],
                    'data_nascimento' => $professor_data['data_nascimento'],
                    'ativo' => true
                ]
            );

            // Criar o professor associado ao membro
            EbdProfessor::updateOrCreate(
                [
                    'membro_id' => $membro->id,
                    'turma_id' => $professor_data['turma_id']
                ],
                [
                    'tipo' => $professor_data['tipo'],
                    'data_inicio' => '2024-01-01',
                    'ativo' => true
                ]
            );

            $professores_criados++;
        }

        $this->command->info('✅ Professores da EBD demonstrativos criados com sucesso');
        $this->command->info('📊 Total de professores: ' . $professores_criados);
        
        // Estatísticas
        $principais = EbdProfessor::where('tipo', 'principal')->count();
        $auxiliares = EbdProfessor::where('tipo', 'auxiliar')->count();
        $ativos = EbdProfessor::where('ativo', true)->count();
        
        $this->command->info("👨‍🏫 Principais: {$principais}");
        $this->command->info("👨‍🏫 Auxiliares: {$auxiliares}");
        $this->command->info("✅ Ativos: {$ativos}");
    }
} 