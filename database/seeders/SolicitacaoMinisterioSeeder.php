<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SolicitacaoMinisterio;
use App\Models\Membro;
use App\Models\Ministerio;
use App\Models\Cargo;
use App\Models\User;

class SolicitacaoMinisterioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Criando solicitações de ministério demonstrativas...');

        // Obter dados necessários
        $membros = Membro::all();
        $ministerios = Ministerio::all();
        $cargos = Cargo::all();
        $admin = User::where('email', 'admin@cbav.com')->first();

        if ($membros->isEmpty() || $ministerios->isEmpty() || $cargos->isEmpty()) {
            $this->command->warn('⚠️ Dados necessários não encontrados. Criando solicitações sem associação...');
            return;
        }

        $solicitacoes = [
            [
                'membro_id' => $membros->where('nome', 'João Silva Santos')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Louvor e Adoração')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Músico')->first()->id,
                'motivo' => 'Tenho dom musical e quero servir no ministério de louvor. Toco violão há 10 anos e canto desde criança. Disponível domingos e ensaios durante a semana.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito talentoso, já participa dos ensaios',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Maria Santos Oliveira')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Infantil')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Professor EBD')->first()->id,
                'motivo' => 'Tenho paixão por trabalhar com crianças e gostaria de servir neste ministério. Sou professora de educação infantil e tenho experiência com crianças. Disponível domingos e sábados para preparação.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Formação em pedagogia, muito qualificada',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Pedro Costa Lima')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Louvor e Adoração')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Músico')->first()->id,
                'motivo' => 'Sou músico e gostaria de usar meu dom para glorificar a Deus. Toco bateria há 8 anos e já participei de bandas. Disponível todos os ensaios e cultos.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Excelente músico, muito comprometido',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Ana Paula Ferreira')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Louvor e Adoração')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Cantor')->first()->id,
                'motivo' => 'Tenho o dom do canto e quero usar para adorar ao Senhor. Canto desde criança e já participei de corais. Disponível domingos e ensaios.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Voz muito bonita, muito dedicada',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Carlos Eduardo Almeida')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Intercessão')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Intercessor')->first()->id,
                'motivo' => 'Tenho o dom da intercessão e gostaria de orar pela igreja. Já participei de grupos de oração em outras igrejas. Disponível quartas-feiras e outros horários.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito espiritual, dedicado à oração',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Lucia Helena Rodrigues')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Ensino')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Professor EBD')->first()->id,
                'motivo' => 'Gostaria de ensinar na Escola Dominical. Sou professora aposentada e tenho experiência em ensino. Disponível domingos pela manhã.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muita experiência em ensino, muito qualificada',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Roberto Alves Silva')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Ensino')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Professor EBD')->first()->id,
                'motivo' => 'Quero ensinar a Palavra de Deus na Escola Dominical. Estudo a Bíblia há muitos anos e tenho formação teológica. Disponível domingos e preparação durante a semana.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Conhecimento bíblico sólido',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Fernanda Costa Santos')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Jovens')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Líder de Ministério')->first()->id,
                'motivo' => 'Sou jovem e quero liderar outros jovens para Cristo. Já participei de ministérios de jovens em outras igrejas. Disponível sábados e eventos especiais.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Líder nata, muito carismática',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Ricardo Oliveira Lima')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Tecnologia')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Técnico de Som')->first()->id,
                'motivo' => 'Tenho conhecimento em tecnologia e quero ajudar na transmissão. Trabalho com TI e tenho experiência com equipamentos. Disponível domingos e manutenção durante a semana.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito técnico, conhecimento avançado',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Patricia Santos Almeida')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Hospitalidade')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Auxiliar')->first()->id,
                'motivo' => 'Gosto de receber pessoas e quero servir neste ministério. Trabalho com atendimento ao cliente. Disponível domingos e eventos especiais.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito simpática e atenciosa',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Marcelo Ferreira Costa')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Evangelismo')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Evangelista')->first()->id,
                'motivo' => 'Tenho paixão por evangelizar e ganhar almas para Cristo. Já participei de evangelismo de rua e visitas. Disponível domingos e durante a semana.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito evangelista, coração missionário',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Juliana Lima Santos')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Infantil')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Auxiliar')->first()->id,
                'motivo' => 'Amo crianças e quero servir neste ministério. Sou babá e tenho experiência com crianças. Disponível domingos e preparação.',
                'status' => 'pendente',
                'resposta' => null,
                'respondido_por' => null,
                'data_resposta' => null
            ],
            [
                'membro_id' => $membros->where('nome', 'Thiago Alves Costa')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Intercessão')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Intercessor')->first()->id,
                'motivo' => 'Tenho o dom da oração e quero interceder pela igreja. Participei de grupos de oração há anos. Disponível quartas-feiras e outros horários.',
                'status' => 'aprovada',
                'resposta' => 'Aprovado! Muito espiritual e dedicado',
                'respondido_por' => $admin->id,
                'data_resposta' => now()
            ],
            [
                'membro_id' => $membros->where('nome', 'Camila Rodrigues Silva')->first()->id,
                'ministerio_id' => $ministerios->where('nome', 'Jovens')->first()->id,
                'cargo_id' => $cargos->where('nome', 'Auxiliar')->first()->id,
                'motivo' => 'Sou jovem e quero participar do ministério de jovens. Nova convertida, muito animada. Disponível sábados e eventos.',
                'status' => 'pendente',
                'resposta' => null,
                'respondido_por' => null,
                'data_resposta' => null
            ]
        ];

        foreach ($solicitacoes as $solicitacao) {
            SolicitacaoMinisterio::create($solicitacao);
        }

        $this->command->info('✅ Solicitações de ministério criadas com sucesso');
        $this->command->info('📊 Total de solicitações: ' . count($solicitacoes));
        
        // Estatísticas
        $aprovadas = collect($solicitacoes)->where('status', 'aprovada')->count();
        $pendentes = collect($solicitacoes)->where('status', 'pendente')->count();
        
        $this->command->info("✅ Aprovadas: {$aprovadas}");
        $this->command->info("⏳ Pendentes: {$pendentes}");
    }
} 