<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdAvaliacao;
use App\Models\EbdAula;
use App\Models\EbdTurma;
use App\Models\EbdLicao;

class EbdAvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Criando avaliações da EBD demonstrativas...');

        // Obter dados necessários
        $aulas = EbdAula::all();
        $turmas = EbdTurma::all();
        $licoes = EbdLicao::all();

        if ($aulas->isEmpty() || $turmas->isEmpty() || $licoes->isEmpty()) {
            $this->command->warn('⚠️ Dados necessários não encontrados. Pulando criação de avaliações...');
            return;
        }

        $adultos = $turmas->where('nome', 'Adultos')->first();
        $jovens = $turmas->where('nome', 'Jovens')->first();
        $adolescentes = $turmas->where('nome', 'Adolescentes')->first();
        $primarios = $turmas->where('nome', 'Primários')->first();

        $criacao = $licoes->where('titulo', 'A Criação do Mundo')->first();
        $abraham = $licoes->where('titulo', 'A Fé de Abraão')->first();
        $mandamentos = $licoes->where('titulo', 'Os Dez Mandamentos')->first();
        $jose = $licoes->where('titulo', 'José: Do Poço ao Palácio')->first();
        $davi = $licoes->where('titulo', 'Davi: Um Homem Segundo o Coração de Deus')->first();
        $moises = $licoes->where('titulo', 'Moisés: O Libertador')->first();

        // Obter aulas específicas
        $aula_adultos_criacao = $aulas->where('turma_id', $adultos->id)->where('licao_id', $criacao->id)->first();
        $aula_adultos_abraham = $aulas->where('turma_id', $adultos->id)->where('licao_id', $abraham->id)->first();
        $aula_adultos_mandamentos = $aulas->where('turma_id', $adultos->id)->where('licao_id', $mandamentos->id)->first();
        $aula_jovens_jose = $aulas->where('turma_id', $jovens->id)->where('licao_id', $jose->id)->first();
        $aula_jovens_davi = $aulas->where('turma_id', $jovens->id)->where('licao_id', $davi->id)->first();
        $aula_adolescentes_moises = $aulas->where('turma_id', $adolescentes->id)->where('licao_id', $moises->id)->first();

        $avaliacoes = [
            // Avaliação para Adultos - Lição: A Criação do Mundo
            [
                'aula_id' => $aula_adultos_criacao->id,
                'titulo' => 'Quiz sobre a Criação',
                'descricao' => 'Avaliação sobre o conteúdo da lição A Criação do Mundo',
                'tipo' => 'quiz',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],
            [
                'aula_id' => $aula_adultos_criacao->id,
                'titulo' => 'Participação na Aula - Criação',
                'descricao' => 'Avaliação da participação dos alunos na discussão sobre a criação',
                'tipo' => 'participacao',
                'pontuacao_maxima' => 5,
                'obrigatoria' => false
            ],

            // Avaliação para Adultos - Lição: A Fé de Abraão
            [
                'aula_id' => $aula_adultos_abraham->id,
                'titulo' => 'Prova sobre a Fé de Abraão',
                'descricao' => 'Avaliação sobre o conteúdo da lição A Fé de Abraão',
                'tipo' => 'prova',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],
            [
                'aula_id' => $aula_adultos_abraham->id,
                'titulo' => 'Trabalho sobre Aplicação da Fé',
                'descricao' => 'Trabalho prático sobre como aplicar a fé de Abraão na vida',
                'tipo' => 'trabalho',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],

            // Avaliação para Adultos - Lição: Os Dez Mandamentos
            [
                'aula_id' => $aula_adultos_mandamentos->id,
                'titulo' => 'Quiz sobre os Dez Mandamentos',
                'descricao' => 'Avaliação sobre o conteúdo da lição Os Dez Mandamentos',
                'tipo' => 'quiz',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],

            // Avaliação para Jovens - Lição: José
            [
                'aula_id' => $aula_jovens_jose->id,
                'titulo' => 'Quiz sobre a História de José',
                'descricao' => 'Avaliação sobre o conteúdo da lição José: Do Poço ao Palácio',
                'tipo' => 'quiz',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],
            [
                'aula_id' => $aula_jovens_jose->id,
                'titulo' => 'Dramatização da História de José',
                'descricao' => 'Avaliação da participação na dramatização da história de José',
                'tipo' => 'participacao',
                'pontuacao_maxima' => 5,
                'obrigatoria' => false
            ],

            // Avaliação para Jovens - Lição: Davi
            [
                'aula_id' => $aula_jovens_davi->id,
                'titulo' => 'Prova sobre a Vida de Davi',
                'descricao' => 'Avaliação sobre o conteúdo da lição Davi: Um Homem Segundo o Coração de Deus',
                'tipo' => 'prova',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],

            // Avaliação para Adolescentes - Lição: Moisés
            [
                'aula_id' => $aula_adolescentes_moises->id,
                'titulo' => 'Quiz sobre Moisés',
                'descricao' => 'Avaliação sobre o conteúdo da lição Moisés: O Libertador',
                'tipo' => 'quiz',
                'pontuacao_maxima' => 10,
                'obrigatoria' => true
            ],
            [
                'aula_id' => $aula_adolescentes_moises->id,
                'titulo' => 'Trabalho sobre Liderança',
                'descricao' => 'Trabalho sobre como aplicar os princípios de liderança de Moisés',
                'tipo' => 'trabalho',
                'pontuacao_maxima' => 10,
                'obrigatoria' => false
            ]
        ];

        foreach ($avaliacoes as $avaliacao) {
            EbdAvaliacao::create($avaliacao);
        }

        $this->command->info('✅ Avaliações da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de avaliações: ' . count($avaliacoes));
        
        // Estatísticas
        $quiz = collect($avaliacoes)->where('tipo', 'quiz')->count();
        $prova = collect($avaliacoes)->where('tipo', 'prova')->count();
        $trabalho = collect($avaliacoes)->where('tipo', 'trabalho')->count();
        $participacao = collect($avaliacoes)->where('tipo', 'participacao')->count();
        $obrigatorias = collect($avaliacoes)->where('obrigatoria', true)->count();
        $opcionais = collect($avaliacoes)->where('obrigatoria', false)->count();
        
        $this->command->info("📝 Quiz: {$quiz}");
        $this->command->info("📄 Prova: {$prova}");
        $this->command->info("📋 Trabalho: {$trabalho}");
        $this->command->info("👥 Participação: {$participacao}");
        $this->command->info("✅ Obrigatórias: {$obrigatorias}");
        $this->command->info("📊 Opcionais: {$opcionais}");
    }
} 