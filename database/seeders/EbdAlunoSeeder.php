<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdAluno;
use App\Models\EbdTurma;

class EbdAlunoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👨‍🎓 Criando alunos da EBD demonstrativos...');

        // Obter turmas para associar os alunos
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

        $alunos = [
            // Berçário
            [
                'nome' => 'João Pedro Silva',
                'data_nascimento' => '2022-03-15',
                'nome_responsavel' => 'Maria Silva',
                'telefone_responsavel' => '(11) 99999-1001',
                'email' => 'maria.silva@email.com',
                'turma_id' => $bercario->id,
                'data_matricula' => '2024-01-15',
                'status' => 'ativo',
                'observacoes' => 'Aluno muito ativo e participativo'
            ],
            [
                'nome' => 'Ana Clara Santos',
                'data_nascimento' => '2022-07-20',
                'nome_responsavel' => 'Carlos Santos',
                'telefone_responsavel' => '(11) 99999-1002',
                'email' => 'carlos.santos@email.com',
                'turma_id' => $bercario->id,
                'data_matricula' => '2024-01-20',
                'status' => 'ativo',
                'observacoes' => 'Primeira vez na EBD'
            ],

            // Maternal
            [
                'nome' => 'Lucas Oliveira',
                'data_nascimento' => '2021-05-10',
                'nome_responsavel' => 'Pedro Oliveira',
                'telefone_responsavel' => '(11) 99999-1003',
                'email' => 'pedro.oliveira@email.com',
                'turma_id' => $maternal->id,
                'data_matricula' => '2024-01-10',
                'status' => 'ativo',
                'observacoes' => 'Gosta muito de histórias bíblicas'
            ],
            [
                'nome' => 'Isabella Costa',
                'data_nascimento' => '2021-09-25',
                'nome_responsavel' => 'Fernanda Costa',
                'telefone_responsavel' => '(11) 99999-1004',
                'email' => 'fernanda.costa@email.com',
                'turma_id' => $maternal->id,
                'data_matricula' => '2024-01-12',
                'status' => 'ativo',
                'observacoes' => 'Muito criativa nas atividades'
            ],

            // Jardim
            [
                'nome' => 'Gabriel Almeida',
                'data_nascimento' => '2019-12-05',
                'nome_responsavel' => 'Ricardo Almeida',
                'telefone_responsavel' => '(11) 99999-1005',
                'email' => 'ricardo.almeida@email.com',
                'turma_id' => $jardim->id,
                'data_matricula' => '2024-01-08',
                'status' => 'ativo',
                'observacoes' => 'Excelente memorização de versículos'
            ],
            [
                'nome' => 'Sofia Lima',
                'data_nascimento' => '2019-08-15',
                'nome_responsavel' => 'Roberto Lima',
                'telefone_responsavel' => '(11) 99999-1006',
                'email' => 'roberto.lima@email.com',
                'turma_id' => $jardim->id,
                'data_matricula' => '2024-01-05',
                'status' => 'ativo',
                'observacoes' => 'Muito participativa nas atividades'
            ],

            // Primários
            [
                'nome' => 'Matheus Costa',
                'data_nascimento' => '2017-03-22',
                'nome_responsavel' => 'Ana Costa',
                'telefone_responsavel' => '(11) 99999-1007',
                'email' => 'ana.costa@email.com',
                'turma_id' => $primarios->id,
                'data_matricula' => '2024-01-03',
                'status' => 'ativo',
                'observacoes' => 'Interessado em história bíblica'
            ],
            [
                'nome' => 'Julia Santos',
                'data_nascimento' => '2017-11-08',
                'nome_responsavel' => 'Patricia Santos',
                'telefone_responsavel' => '(11) 99999-1008',
                'email' => 'patricia.santos@email.com',
                'turma_id' => $primarios->id,
                'data_matricula' => '2024-01-07',
                'status' => 'ativo',
                'observacoes' => 'Gosta de atividades práticas'
            ],

            // Juniores
            [
                'nome' => 'Pedro Almeida',
                'data_nascimento' => '2014-06-12',
                'nome_responsavel' => 'Carlos Almeida',
                'telefone_responsavel' => '(11) 99999-1009',
                'email' => 'carlos.almeida@email.com',
                'turma_id' => $juniores->id,
                'data_matricula' => '2024-01-02',
                'status' => 'ativo',
                'observacoes' => 'Muito questionador e curioso'
            ],
            [
                'nome' => 'Mariana Silva',
                'data_nascimento' => '2014-09-30',
                'nome_responsavel' => 'Fernando Silva',
                'telefone_responsavel' => '(11) 99999-1010',
                'email' => 'fernando.silva@email.com',
                'turma_id' => $juniores->id,
                'data_matricula' => '2024-01-04',
                'status' => 'ativo',
                'observacoes' => 'Excelente participação'
            ],

            // Adolescentes
            [
                'nome' => 'Rafael Oliveira',
                'data_nascimento' => '2011-04-18',
                'nome_responsavel' => 'Paulo Oliveira',
                'telefone_responsavel' => '(11) 99999-1011',
                'email' => 'paulo.oliveira@email.com',
                'turma_id' => $adolescentes->id,
                'data_matricula' => '2024-01-01',
                'status' => 'ativo',
                'observacoes' => 'Interessado em temas atuais'
            ],
            [
                'nome' => 'Carolina Lima',
                'data_nascimento' => '2011-12-03',
                'nome_responsavel' => 'Roberto Lima',
                'telefone_responsavel' => '(11) 99999-1012',
                'email' => 'roberto.lima@email.com',
                'turma_id' => $adolescentes->id,
                'data_matricula' => '2024-01-06',
                'status' => 'ativo',
                'observacoes' => 'Muito envolvida nas discussões'
            ],

            // Jovens
            [
                'nome' => 'Thiago Costa',
                'data_nascimento' => '2006-07-25',
                'nome_responsavel' => 'Antonio Costa',
                'telefone_responsavel' => '(11) 99999-1013',
                'email' => 'antonio.costa@email.com',
                'turma_id' => $jovens->id,
                'data_matricula' => '2024-01-01',
                'status' => 'ativo',
                'observacoes' => 'Estudos bíblicos profundos'
            ],
            [
                'nome' => 'Amanda Santos',
                'data_nascimento' => '2006-03-14',
                'nome_responsavel' => 'Marcos Santos',
                'telefone_responsavel' => '(11) 99999-1014',
                'email' => 'marcos.santos@email.com',
                'turma_id' => $jovens->id,
                'data_matricula' => '2024-01-05',
                'status' => 'ativo',
                'observacoes' => 'Muito dedicada aos estudos'
            ],

            // Adultos
            [
                'nome' => 'Roberto Silva',
                'data_nascimento' => '1985-08-10',
                'nome_responsavel' => null,
                'telefone_responsavel' => null,
                'email' => 'roberto.silva@email.com',
                'telefone' => '(11) 99999-1015',
                'turma_id' => $adultos->id,
                'data_matricula' => '2024-01-01',
                'status' => 'ativo',
                'observacoes' => 'Membro ativo da igreja'
            ],
            [
                'nome' => 'Lucia Ferreira',
                'data_nascimento' => '1988-12-05',
                'nome_responsavel' => null,
                'telefone_responsavel' => null,
                'email' => 'lucia.ferreira@email.com',
                'telefone' => '(11) 99999-1016',
                'turma_id' => $adultos->id,
                'data_matricula' => '2024-01-03',
                'status' => 'ativo',
                'observacoes' => 'Muito participativa'
            ],

            // Senhores
            [
                'nome' => 'João Pedro Costa',
                'data_nascimento' => '1955-05-20',
                'nome_responsavel' => null,
                'telefone_responsavel' => null,
                'email' => 'joao.costa@email.com',
                'telefone' => '(11) 99999-1017',
                'turma_id' => $senhores->id,
                'data_matricula' => '2024-01-01',
                'status' => 'ativo',
                'observacoes' => 'Membro sênior da igreja'
            ],
            [
                'nome' => 'Maria Helena Silva',
                'data_nascimento' => '1958-09-15',
                'nome_responsavel' => null,
                'telefone_responsavel' => null,
                'email' => 'maria.helena@email.com',
                'telefone' => '(11) 99999-1018',
                'turma_id' => $senhores->id,
                'data_matricula' => '2024-01-02',
                'status' => 'ativo',
                'observacoes' => 'Muito dedicada aos estudos'
            ]
        ];

        foreach ($alunos as $aluno) {
            EbdAluno::updateOrCreate(
                ['email' => $aluno['email']],
                $aluno
            );
        }

        $this->command->info('✅ Alunos da EBD demonstrativos criados com sucesso');
        $this->command->info('📊 Total de alunos: ' . count($alunos));
        
        // Estatísticas
        $ativos = collect($alunos)->where('status', 'ativo')->count();
        $infantil = collect($alunos)->whereIn('turma_id', [$bercario->id, $maternal->id, $jardim->id, $primarios->id, $juniores->id])->count();
        $adultos = collect($alunos)->whereIn('turma_id', [$adultos->id, $senhores->id])->count();
        $jovens = collect($alunos)->whereIn('turma_id', [$adolescentes->id, $jovens->id])->count();
        
        $this->command->info("✅ Ativos: {$ativos}");
        $this->command->info("👶 Infantil: {$infantil}");
        $this->command->info("👨‍🦳 Adultos: {$adultos}");
        $this->command->info("🎯 Jovens: {$jovens}");
    }
} 