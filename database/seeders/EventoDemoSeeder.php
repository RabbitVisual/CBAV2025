<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\User;
use App\Models\Ministerio;
use Carbon\Carbon;

class EventoDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar usuário admin e ministério
        $admin = User::where('email', 'admin@igreja.com')->first() ?? User::first();
        $ministerio = Ministerio::first();

        // Evento Demonstrativo
        Evento::create([
            'titulo' => 'Conferência de Jovens 2024',
            'descricao' => 'Uma conferência especial para jovens com palestras, workshops e momentos de adoração. Venha participar de um evento que vai transformar sua vida!',
            'descricao_curta' => 'Conferência especial para jovens com palestras e workshops',
            'data_inicio' => Carbon::now()->addDays(30),
            'data_fim' => Carbon::now()->addDays(30),
            'hora_inicio' => Carbon::createFromTime(19, 0),
            'hora_fim' => Carbon::createFromTime(22, 0),
            'local' => 'Auditório Principal da Igreja',
            'endereco' => 'Rua das Flores, 123 - Centro',
            'tipo_evento' => 'conferencia',
            'tipo_publico' => 'ambos',
            'ativo' => true,
            'destaque' => true,
            'gratuito' => false,
            'valor_inscricao' => 25.00,
            'vagas_totais' => 100,
            'inscricao_obrigatoria' => true,
            'inscricao_ate' => Carbon::now()->addDays(25),
            'regulamento' => "1. Chegar com 30 minutos de antecedência\n2. Trazer documento de identificação\n3. Respeitar os horários estabelecidos\n4. Participar de todas as atividades\n5. Manter o ambiente limpo e organizado",
            'informacoes_adicionais' => "O evento inclui:\n- Coffee break\n- Material didático\n- Certificado de participação\n- Networking entre jovens\n- Momento de oração e adoração",
            'organizador_id' => $admin->id,
            'ministerio_id' => $ministerio->id,
            'criado_por' => $admin->id,
            'atualizado_por' => $admin->id,
        ]);

        // Evento Gratuito
        Evento::create([
            'titulo' => 'Culto de Celebração',
            'descricao' => 'Um culto especial de celebração e agradecimento. Venha louvar e adorar a Deus em comunidade!',
            'descricao_curta' => 'Culto especial de celebração e agradecimento',
            'data_inicio' => Carbon::now()->addDays(7),
            'data_fim' => Carbon::now()->addDays(7),
            'hora_inicio' => Carbon::createFromTime(18, 30),
            'hora_fim' => Carbon::createFromTime(20, 30),
            'local' => 'Templo Principal',
            'endereco' => 'Rua das Flores, 123 - Centro',
            'tipo_evento' => 'culto',
            'tipo_publico' => 'ambos',
            'ativo' => true,
            'destaque' => false,
            'gratuito' => true,
            'valor_inscricao' => 0.00,
            'vagas_totais' => 200,
            'inscricao_obrigatoria' => false,
            'inscricao_ate' => Carbon::now()->addDays(6),
            'regulamento' => "1. Chegar com antecedência\n2. Manter silêncio durante o culto\n3. Participar com reverência\n4. Respeitar o próximo",
            'informacoes_adicionais' => "Traga sua família e amigos para participar deste momento especial de adoração!",
            'organizador_id' => $admin->id,
            'ministerio_id' => $ministerio->id,
            'criado_por' => $admin->id,
            'atualizado_por' => $admin->id,
        ]);

        // Evento para Membros
        Evento::create([
            'titulo' => 'Reunião de Líderes',
            'descricao' => 'Reunião mensal dos líderes de ministérios para alinhamento e planejamento das atividades.',
            'descricao_curta' => 'Reunião mensal dos líderes de ministérios',
            'data_inicio' => Carbon::now()->addDays(14),
            'data_fim' => Carbon::now()->addDays(14),
            'hora_inicio' => Carbon::createFromTime(20, 0),
            'hora_fim' => Carbon::createFromTime(21, 30),
            'local' => 'Sala de Reuniões',
            'endereco' => 'Rua das Flores, 123 - Centro',
            'tipo_evento' => 'reuniao',
            'tipo_publico' => 'membros',
            'ativo' => true,
            'destaque' => false,
            'gratuito' => true,
            'valor_inscricao' => 0.00,
            'vagas_totais' => 50,
            'inscricao_obrigatoria' => true,
            'inscricao_ate' => Carbon::now()->addDays(12),
            'regulamento' => "1. Apenas líderes de ministérios\n2. Chegar pontualmente\n3. Trazer material de anotações\n4. Participar ativamente",
            'informacoes_adicionais' => "Traga suas ideias e sugestões para o planejamento dos próximos meses!",
            'organizador_id' => $admin->id,
            'ministerio_id' => $ministerio->id,
            'criado_por' => $admin->id,
            'atualizado_por' => $admin->id,
        ]);

        $this->command->info('Eventos demonstrativos criados com sucesso!');
    }
} 