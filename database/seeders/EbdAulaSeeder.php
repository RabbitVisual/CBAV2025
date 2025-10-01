<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdAula;
use App\Models\EbdTurma;
use App\Models\EbdLicao;
use App\Models\EbdProfessor;

class EbdAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📚 Criando aulas da EBD demonstrativas...');

        // Obter dados necessários
        $turmas = EbdTurma::all();
        $licoes = EbdLicao::all();
        $professores = EbdProfessor::all();

        if ($turmas->isEmpty() || $licoes->isEmpty() || $professores->isEmpty()) {
            $this->command->warn('⚠️ Dados necessários não encontrados. Pulando criação de aulas...');
            return;
        }

        $adultos = $turmas->where('nome', 'Adultos')->first();
        $jovens = $turmas->where('nome', 'Jovens')->first();
        $adolescentes = $turmas->where('nome', 'Adolescentes')->first();
        $primarios = $turmas->where('nome', 'Primários')->first();
        $jardim = $turmas->where('nome', 'Jardim')->first();

        $criacao = $licoes->where('titulo', 'A Criação do Mundo')->first();
        $queda = $licoes->where('titulo', 'A Queda do Homem')->first();
        $abraham = $licoes->where('titulo', 'A Fé de Abraão')->first();
        $jose = $licoes->where('titulo', 'José: Do Poço ao Palácio')->first();
        $moises = $licoes->where('titulo', 'Moisés: O Libertador')->first();
        $mandamentos = $licoes->where('titulo', 'Os Dez Mandamentos')->first();
        $davi = $licoes->where('titulo', 'Davi: Um Homem Segundo o Coração de Deus')->first();
        $profetas = $licoes->where('titulo', 'Os Profetas: Voz de Deus')->first();
        $nascimento = $licoes->where('titulo', 'O Nascimento de Jesus')->first();
        $milagres = $licoes->where('titulo', 'Os Milagres de Jesus')->first();
        $morte = $licoes->where('titulo', 'A Morte e Ressurreição de Jesus')->first();
        $igreja = $licoes->where('titulo', 'A Igreja Primitiva')->first();

        // Obter professores (usando o primeiro professor de cada turma)
        $professor_adultos = $professores->where('turma_id', $adultos->id)->first();
        $professor_jovens = $professores->where('turma_id', $jovens->id)->first();
        $professor_adolescentes = $professores->where('turma_id', $adolescentes->id)->first();
        $professor_primarios = $professores->where('turma_id', $primarios->id)->first();
        $professor_jardim = $professores->where('turma_id', $jardim->id)->first();

        // Se não há professores para alguma turma, usar o primeiro professor disponível
        $professor_padrao = $professores->first();
        $professor_adultos = $professor_adultos ?: $professor_padrao;
        $professor_jovens = $professor_jovens ?: $professor_padrao;
        $professor_adolescentes = $professor_adolescentes ?: $professor_padrao;
        $professor_primarios = $professor_primarios ?: $professor_padrao;
        $professor_jardim = $professor_jardim ?: $professor_padrao;

        $aulas = [
            // Aulas para Adultos
            [
                'turma_id' => $adultos->id,
                'licao_id' => $criacao->id,
                'professor_id' => $professor_adultos->id,
                'data_aula' => '2024-01-07',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Aula muito produtiva, alunos engajados. Discussão sobre criação vs evolução.'
            ],
            [
                'turma_id' => $adultos->id,
                'licao_id' => $abraham->id,
                'professor_id' => $professor_adultos->id,
                'data_aula' => '2024-01-14',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Aula transformadora para muitos alunos. Muitos testemunhos pessoais.'
            ],
            [
                'turma_id' => $adultos->id,
                'licao_id' => $mandamentos->id,
                'professor_id' => $professor_adultos->id,
                'data_aula' => '2024-01-21',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Discussões acaloradas sobre aplicação dos mandamentos na vida moderna.'
            ],

            // Aulas para Jovens
            [
                'turma_id' => $jovens->id,
                'licao_id' => $jose->id,
                'professor_id' => $professor_jovens->id,
                'data_aula' => '2024-01-07',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Excelente participação. Jovens identificaram-se com a história de José.'
            ],
            [
                'turma_id' => $jovens->id,
                'licao_id' => $davi->id,
                'professor_id' => $professor_jovens->id,
                'data_aula' => '2024-01-14',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Foco especial nos salmos de Davi e sua relação com Deus.'
            ],
            [
                'turma_id' => $jovens->id,
                'licao_id' => $milagres->id,
                'professor_id' => $professor_jovens->id,
                'data_aula' => '2024-01-21',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Discussão sobre milagres na atualidade e poder de Jesus.'
            ],

            // Aulas para Adolescentes
            [
                'turma_id' => $adolescentes->id,
                'licao_id' => $queda->id,
                'professor_id' => $professor_adolescentes->id,
                'data_aula' => '2024-01-07',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Aula com dramatização da história. Adolescentes muito participativos.'
            ],
            [
                'turma_id' => $adolescentes->id,
                'licao_id' => $moises->id,
                'professor_id' => $professor_adolescentes->id,
                'data_aula' => '2024-01-14',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Foco na liderança e coragem de Moisés. Aplicação para vida dos adolescentes.'
            ],
            [
                'turma_id' => $adolescentes->id,
                'licao_id' => $nascimento->id,
                'professor_id' => $professor_adolescentes->id,
                'data_aula' => '2024-01-21',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Aula especial sobre o Natal. Músicas e atividades criativas.'
            ],

            // Aulas para Primários
            [
                'turma_id' => $primarios->id,
                'licao_id' => $criacao->id,
                'professor_id' => $professor_primarios->id,
                'data_aula' => '2024-01-07',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Atividades manuais sobre a criação. Crianças muito criativas.'
            ],
            [
                'turma_id' => $primarios->id,
                'licao_id' => $jose->id,
                'professor_id' => $professor_primarios->id,
                'data_aula' => '2024-01-14',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Dramatização da história de José. Fantoches e atividades interativas.'
            ],
            [
                'turma_id' => $primarios->id,
                'licao_id' => $mandamentos->id,
                'professor_id' => $professor_primarios->id,
                'data_aula' => '2024-01-21',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Memorização dos mandamentos com músicas e jogos.'
            ],

            // Aulas para Jardim
            [
                'turma_id' => $jardim->id,
                'licao_id' => $criacao->id,
                'professor_id' => $professor_jardim->id,
                'data_aula' => '2024-01-07',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'História com figuras coloridas. Atividades de pintura sobre a criação.'
            ],
            [
                'turma_id' => $jardim->id,
                'licao_id' => $nascimento->id,
                'professor_id' => $professor_jardim->id,
                'data_aula' => '2024-01-14',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Presépio montado pelas crianças. Músicas natalinas.'
            ],
            [
                'turma_id' => $jardim->id,
                'licao_id' => $milagres->id,
                'professor_id' => $professor_jardim->id,
                'data_aula' => '2024-01-21',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'realizada',
                'observacoes' => 'Milagres contados de forma simples e lúdica.'
            ],

            // Aulas agendadas para o futuro
            [
                'turma_id' => $adultos->id,
                'licao_id' => $profetas->id,
                'professor_id' => $professor_adultos->id,
                'data_aula' => '2024-02-04',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'agendada',
                'observacoes' => 'Aula sobre os profetas e suas mensagens para hoje.'
            ],
            [
                'turma_id' => $jovens->id,
                'licao_id' => $morte->id,
                'professor_id' => $professor_jovens->id,
                'data_aula' => '2024-02-04',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'agendada',
                'observacoes' => 'Estudo sobre a Páscoa e o significado da morte e ressurreição.'
            ],
            [
                'turma_id' => $adolescentes->id,
                'licao_id' => $igreja->id,
                'professor_id' => $professor_adolescentes->id,
                'data_aula' => '2024-02-04',
                'horario_inicio' => '09:00',
                'horario_fim' => '09:45',
                'status' => 'agendada',
                'observacoes' => 'História da igreja primitiva e como podemos viver como eles.'
            ]
        ];

        foreach ($aulas as $aula) {
            EbdAula::create($aula);
        }

        $this->command->info('✅ Aulas da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de aulas: ' . count($aulas));
        
        // Estatísticas
        $realizadas = collect($aulas)->where('status', 'realizada')->count();
        $agendadas = collect($aulas)->where('status', 'agendada')->count();
        $canceladas = collect($aulas)->where('status', 'cancelada')->count();
        $adiadas = collect($aulas)->where('status', 'adiada')->count();
        
        $this->command->info("✅ Realizadas: {$realizadas}");
        $this->command->info("📅 Agendadas: {$agendadas}");
        $this->command->info("❌ Canceladas: {$canceladas}");
        $this->command->info("⏰ Adiadas: {$adiadas}");
    }
} 