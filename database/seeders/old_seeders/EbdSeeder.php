<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdTurma;
use App\Models\EbdLicao;
use App\Models\EbdAula;
use App\Models\EbdAvaliacao;
use App\Models\EbdQuestao;
use App\Models\Membro;
use App\Models\EbdProfessor;
use App\Models\EbdAluno;
use Carbon\Carbon;

class EbdSeeder extends Seeder
{
    public function run()
    {
        // Criar turmas
        $turmas = [
            [
                'nome' => 'Maternal (3-6 anos)',
                'faixa_etaria' => '3-6 anos',
                'cor' => '#FF6B6B',
                'capacidade_maxima' => 15,
                'descricao' => 'Turma para crianças de 3 a 6 anos'
            ],
            [
                'nome' => 'Primários (7-12 anos)',
                'faixa_etaria' => '7-12 anos',
                'cor' => '#4ECDC4',
                'capacidade_maxima' => 20,
                'descricao' => 'Turma para crianças de 7 a 12 anos'
            ],
            [
                'nome' => 'Adolescentes (13-17 anos)',
                'faixa_etaria' => '13-17 anos',
                'cor' => '#45B7D1',
                'capacidade_maxima' => 25,
                'descricao' => 'Turma para adolescentes de 13 a 17 anos'
            ],
            [
                'nome' => 'Jovens (18-25 anos)',
                'faixa_etaria' => '18-25 anos',
                'cor' => '#96CEB4',
                'capacidade_maxima' => 30,
                'descricao' => 'Turma para jovens de 18 a 25 anos'
            ],
            [
                'nome' => 'Adultos',
                'faixa_etaria' => 'Adultos',
                'cor' => '#FFEAA7',
                'capacidade_maxima' => 40,
                'descricao' => 'Turma para adultos'
            ]
        ];

        foreach ($turmas as $turmaData) {
            EbdTurma::create($turmaData);
        }

        // Criar lições
        $licoes = [
            [
                'titulo' => 'A Criação do Mundo',
                'descricao' => 'Estudo sobre a criação do mundo conforme Gênesis',
                'objetivos' => 'Compreender a narrativa bíblica da criação',
                'versiculo_chave' => 'Gênesis 1:1 - "No princípio, criou Deus os céus e a terra."',
                'conteudo' => 'Nesta lição, estudaremos como Deus criou o mundo em seis dias...',
                'aplicacao_pratica' => 'Reconhecer a soberania de Deus sobre toda a criação',
                'oracao' => 'Senhor, ajuda-nos a ver tua glória na criação...',
                'material_necessario' => 'Bíblia, cartolinas, lápis de cor',
                'duracao_minutos' => 60,
                'dificuldade' => 'facil'
            ],
            [
                'titulo' => 'A Queda do Homem',
                'descricao' => 'Estudo sobre o pecado original e suas consequências',
                'objetivos' => 'Entender as consequências do pecado',
                'versiculo_chave' => 'Romanos 3:23 - "Porque todos pecaram e carecem da glória de Deus"',
                'conteudo' => 'Nesta lição, estudaremos como o pecado entrou no mundo...',
                'aplicacao_pratica' => 'Reconhecer nossa necessidade de salvação',
                'oracao' => 'Senhor, perdoa nossos pecados...',
                'material_necessario' => 'Bíblia, papel, caneta',
                'duracao_minutos' => 60,
                'dificuldade' => 'medio'
            ],
            [
                'titulo' => 'A Salvação em Cristo',
                'descricao' => 'Estudo sobre a obra redentora de Jesus',
                'objetivos' => 'Compreender o plano de salvação',
                'versiculo_chave' => 'João 3:16 - "Porque Deus amou o mundo..."',
                'conteudo' => 'Nesta lição, estudaremos como Jesus veio para salvar...',
                'aplicacao_pratica' => 'Aceitar Jesus como Salvador pessoal',
                'oracao' => 'Senhor Jesus, aceito-te como meu Salvador...',
                'material_necessario' => 'Bíblia, folhas de papel',
                'duracao_minutos' => 60,
                'dificuldade' => 'medio'
            ]
        ];

        foreach ($licoes as $licaoData) {
            EbdLicao::create($licaoData);
        }

        // Criar aulas (exemplo para a primeira turma)
        $turma = EbdTurma::first();
        $licao = EbdLicao::first();

        $aula = EbdAula::create([
            'turma_id' => $turma->id,
            'licao_id' => $licao->id,
            'data_aula' => Carbon::now()->addDays(7),
            'horario_inicio' => Carbon::now()->addDays(7)->setTime(9, 0),
            'horario_fim' => Carbon::now()->addDays(7)->setTime(10, 0),
            'status' => 'agendada'
        ]);

        // Criar avaliação
        $avaliacao = EbdAvaliacao::create([
            'aula_id' => $aula->id,
            'titulo' => 'Quiz: A Criação do Mundo',
            'descricao' => 'Avaliação sobre o conteúdo da lição',
            'tipo' => 'quiz',
            'pontuacao_maxima' => 10,
            'obrigatoria' => true
        ]);

        // Criar questões
        $questoes = [
            [
                'pergunta' => 'Quem criou o mundo?',
                'tipo' => 'multipla_escolha',
                'opcoes' => [
                    'a' => 'O homem',
                    'b' => 'Deus',
                    'c' => 'A natureza',
                    'd' => 'O acaso'
                ],
                'resposta_correta' => 'b',
                'pontuacao' => 2,
                'explicacao' => 'Deus é o criador de todas as coisas'
            ],
            [
                'pergunta' => 'Em quantos dias Deus criou o mundo?',
                'tipo' => 'multipla_escolha',
                'opcoes' => [
                    'a' => '5 dias',
                    'b' => '6 dias',
                    'c' => '7 dias',
                    'd' => '8 dias'
                ],
                'resposta_correta' => 'b',
                'pontuacao' => 2,
                'explicacao' => 'Deus criou o mundo em 6 dias e descansou no sétimo'
            ],
            [
                'pergunta' => 'O primeiro homem criado foi Adão',
                'tipo' => 'verdadeiro_falso',
                'resposta_correta' => 'verdadeiro',
                'pontuacao' => 2,
                'explicacao' => 'Adão foi o primeiro homem criado por Deus'
            ],
            [
                'pergunta' => 'Deus criou o homem do pó da terra',
                'tipo' => 'verdadeiro_falso',
                'resposta_correta' => 'verdadeiro',
                'pontuacao' => 2,
                'explicacao' => 'Deus formou o homem do pó da terra'
            ],
            [
                'pergunta' => 'Explique por que é importante conhecer a história da criação',
                'tipo' => 'dissertativa',
                'pontuacao' => 2,
                'explicacao' => 'A criação nos mostra a soberania e o amor de Deus'
            ]
        ];

        foreach ($questoes as $index => $questaoData) {
            EbdQuestao::create([
                'avaliacao_id' => $avaliacao->id,
                'pergunta' => $questaoData['pergunta'],
                'tipo' => $questaoData['tipo'],
                'opcoes' => $questaoData['opcoes'] ?? null,
                'resposta_correta' => $questaoData['resposta_correta'] ?? null,
                'pontuacao' => $questaoData['pontuacao'],
                'explicacao' => $questaoData['explicacao'],
                'ordem' => $index + 1
            ]);
        }

        // Criar professores (se houver membros)
        $membros = Membro::limit(3)->get();
        if ($membros->count() > 0) {
            foreach ($membros as $index => $membro) {
                EbdProfessor::create([
                    'membro_id' => $membro->id,
                    'turma_id' => $turma->id,
                    'tipo' => $index === 0 ? 'principal' : 'auxiliar',
                    'data_inicio' => Carbon::now(),
                    'ativo' => true
                ]);
            }
        }

        // Criar alunos (se houver membros)
        $membros = Membro::limit(5)->get();
        if ($membros->count() > 0) {
            foreach ($membros as $membro) {
                EbdAluno::create([
                    'membro_id' => $membro->id,
                    'nome' => $membro->nome,
                    'email' => $membro->email,
                    'telefone' => $membro->telefone,
                    'turma_id' => $turma->id,
                    'data_matricula' => Carbon::now(),
                    'status' => 'ativo'
                ]);
            }
        }
    }
} 