<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Membro;
use App\Models\Ministerio;
use App\Models\Departamento;
use App\Models\Cargo;
use App\Models\Campanha;
use App\Models\Transacao;
use App\Models\Devocional;
use App\Models\Notification;
use App\Models\SolicitacaoMinisterio;
use App\Models\Configuracao;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DemoDataSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create('pt_BR');
    }

    public function run()
    {
        $this->command->info('🎭 Iniciando criação de dados demonstrativos...');

        // Limpar dados existentes (exceto configurações essenciais)
        $this->clearDemoData();

        // Criar dados em ordem
        $this->createUsers();
        $this->createMinisterios();
        $this->createDepartamentos();
        $this->createCargos();
        $this->createMembros();
        $this->createCampanhas();
        $this->createTransacoes();
        $this->createDevocionais();
        $this->createNotificacoes();
        $this->createSolicitacoesMinisterio();

        $this->command->info('✅ Dados demonstrativos criados com sucesso!');
        $this->showDemoInfo();
    }

    private function clearDemoData()
    {
        $this->command->info('🧹 Limpando dados existentes...');

        // Desabilitar verificações de foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Manter apenas o Super Admin
        DB::table('users')->where('email', '!=', 'admin@cbav.com')->delete();
        DB::table('membros')->truncate();
        DB::table('campanhas')->truncate();
        DB::table('transacoes')->truncate();
        DB::table('devocionais')->truncate();
        DB::table('notificacaos')->truncate();
        DB::table('solicitacoes_ministerio')->truncate();
        DB::table('membro_cargo')->truncate();
        DB::table('user_cargo')->truncate();

        // Limpar ministérios, departamentos e cargos (exceto sistema)
        DB::table('cargos')->where('sistema', false)->delete();
        DB::table('departamentos')->truncate();
        DB::table('ministerios')->truncate();

        // Reabilitar verificações de foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createUsers()
    {
        $this->command->info('👥 Criando usuários demonstrativos...');

        $users = [
            [
                'name' => 'Pastor João Silva',
                'email' => 'pastor@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Super Admin',
                'foto' => null,
                'telefone' => '(11) 99999-1111'
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Pastor',
                'foto' => null,
                'telefone' => '(11) 99999-2222'
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Líder',
                'foto' => null,
                'telefone' => '(11) 99999-3333'
            ],
            [
                'name' => 'Ana Oliveira',
                'email' => 'ana@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Tesoureiro',
                'foto' => null,
                'telefone' => '(11) 99999-4444'
            ],
            [
                'name' => 'Carlos Lima',
                'email' => 'carlos@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Líder',
                'foto' => null,
                'telefone' => '(11) 99999-5555'
            ],
            [
                'name' => 'Lucia Ferreira',
                'email' => 'lucia@igreja.com',
                'password' => Hash::make('12345678'),
                'role' => 'Tesoureiro',
                'foto' => null,
                'telefone' => '(11) 99999-6666'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::create($userData);
            $user->assignRole($role);
        }

        $this->command->info("✅ " . count($users) . " usuários criados");
    }

    private function createMinisterios()
    {
        $this->command->info('⛪ Criando ministérios...');

        $ministerios = [
            [
                'nome' => 'Louvor e Adoração',
                'descricao' => 'Ministério responsável pela música e adoração da igreja',
                'cor' => '#3b82f6',
                'ativo' => true
            ],
            [
                'nome' => 'Jovens',
                'descricao' => 'Ministério dedicado aos jovens e adolescentes',
                'cor' => '#10b981',
                'ativo' => true
            ],
            [
                'nome' => 'Crianças',
                'descricao' => 'Ministério infantil e escola bíblica',
                'cor' => '#f59e0b',
                'ativo' => true
            ],
            [
                'nome' => 'Intercessão',
                'descricao' => 'Ministério de oração e intercessão',
                'cor' => '#8b5cf6',
                'ativo' => true
            ],
            [
                'nome' => 'Ação Social',
                'descricao' => 'Ministério de ação social e caridade',
                'cor' => '#ef4444',
                'ativo' => true
            ],
            [
                'nome' => 'Ensino',
                'descricao' => 'Ministério de ensino e discipulado',
                'cor' => '#06b6d4',
                'ativo' => true
            ],
            [
                'nome' => 'Evangelismo',
                'descricao' => 'Ministério de evangelismo e missões',
                'cor' => '#84cc16',
                'ativo' => true
            ],
            [
                'nome' => 'Família',
                'descricao' => 'Ministério de aconselhamento familiar',
                'cor' => '#ec4899',
                'ativo' => true
            ]
        ];

        foreach ($ministerios as $ministerio) {
            Ministerio::create($ministerio);
        }

        $this->command->info("✅ " . count($ministerios) . " ministérios criados");
    }

    private function createDepartamentos()
    {
        $this->command->info('🏢 Criando departamentos...');

        $departamentos = [
            // Louvor e Adoração
            ['nome' => 'Músicos', 'ministerio_id' => 1, 'descricao' => 'Músicos e instrumentistas'],
            ['nome' => 'Coral', 'ministerio_id' => 1, 'descricao' => 'Coral da igreja'],
            ['nome' => 'Som', 'ministerio_id' => 1, 'descricao' => 'Equipe de som e áudio'],
            ['nome' => 'Multimídia', 'ministerio_id' => 1, 'descricao' => 'Projeção e multimídia'],

            // Jovens
            ['nome' => 'Liderança', 'ministerio_id' => 2, 'descricao' => 'Líderes do ministério jovem'],
            ['nome' => 'Eventos', 'ministerio_id' => 2, 'descricao' => 'Organização de eventos'],
            ['nome' => 'Evangelismo', 'ministerio_id' => 2, 'descricao' => 'Evangelismo jovem'],

            // Crianças
            ['nome' => 'Professores', 'ministerio_id' => 3, 'descricao' => 'Professores da escola bíblica'],
            ['nome' => 'Recreação', 'ministerio_id' => 3, 'descricao' => 'Atividades recreativas'],
            ['nome' => 'Teatro', 'ministerio_id' => 3, 'descricao' => 'Teatro infantil'],

            // Intercessão
            ['nome' => 'Oração', 'ministerio_id' => 4, 'descricao' => 'Grupo de oração'],
            ['nome' => 'Vigília', 'ministerio_id' => 4, 'descricao' => 'Vigílias de oração'],

            // Ação Social
            ['nome' => 'Assistência', 'ministerio_id' => 5, 'descricao' => 'Assistência social'],
            ['nome' => 'Alimentos', 'ministerio_id' => 5, 'descricao' => 'Distribuição de alimentos'],
            ['nome' => 'Visitação', 'ministerio_id' => 5, 'descricao' => 'Visitas hospitalares'],

            // Ensino
            ['nome' => 'Discipulado', 'ministerio_id' => 6, 'descricao' => 'Discipulado de novos convertidos'],
            ['nome' => 'Escola Bíblica', 'ministerio_id' => 6, 'descricao' => 'Escola bíblica dominical'],

            // Evangelismo
            ['nome' => 'Missões', 'ministerio_id' => 7, 'descricao' => 'Missões locais e internacionais'],
            ['nome' => 'Evangelismo', 'ministerio_id' => 7, 'descricao' => 'Evangelismo de rua'],

            // Família
            ['nome' => 'Aconselhamento', 'ministerio_id' => 8, 'descricao' => 'Aconselhamento familiar'],
            ['nome' => 'Casais', 'ministerio_id' => 8, 'descricao' => 'Ministério de casais']
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }

        $this->command->info("✅ " . count($departamentos) . " departamentos criados");
    }

    private function createCargos()
    {
        $this->command->info('👔 Criando cargos...');

        $cargos = [
            // Cargos do Louvor
            ['nome' => 'Músico', 'departamento_id' => 1, 'descricao' => 'Músico instrumental'],
            ['nome' => 'Vocalista', 'departamento_id' => 1, 'descricao' => 'Vocalista do grupo'],
            ['nome' => 'Técnico de Som', 'departamento_id' => 3, 'descricao' => 'Técnico de som'],
            ['nome' => 'Operador de Multimídia', 'departamento_id' => 4, 'descricao' => 'Operador de projeção'],

            // Cargos dos Jovens
            ['nome' => 'Líder de Jovens', 'departamento_id' => 5, 'descricao' => 'Líder do ministério jovem'],
            ['nome' => 'Organizador de Eventos', 'departamento_id' => 6, 'descricao' => 'Organizador de eventos'],
            ['nome' => 'Evangelista Jovem', 'departamento_id' => 7, 'descricao' => 'Evangelista jovem'],

            // Cargos das Crianças
            ['nome' => 'Professor Infantil', 'departamento_id' => 8, 'descricao' => 'Professor da escola bíblica'],
            ['nome' => 'Recreador', 'departamento_id' => 9, 'descricao' => 'Recreador infantil'],
            ['nome' => 'Ator Infantil', 'departamento_id' => 10, 'descricao' => 'Ator do teatro infantil'],

            // Cargos da Intercessão
            ['nome' => 'Intercessor', 'departamento_id' => 11, 'descricao' => 'Intercessor'],
            ['nome' => 'Coordenador de Vigília', 'departamento_id' => 12, 'descricao' => 'Coordenador de vigílias'],

            // Cargos da Ação Social
            ['nome' => 'Assistente Social', 'departamento_id' => 13, 'descricao' => 'Assistente social'],
            ['nome' => 'Distribuidor de Alimentos', 'departamento_id' => 14, 'descricao' => 'Distribuidor de alimentos'],
            ['nome' => 'Visitador Hospitalar', 'departamento_id' => 15, 'descricao' => 'Visitador hospitalar'],

            // Cargos do Ensino
            ['nome' => 'Discipulador', 'departamento_id' => 16, 'descricao' => 'Discipulador'],
            ['nome' => 'Professor da EBD', 'departamento_id' => 17, 'descricao' => 'Professor da escola bíblica'],

            // Cargos do Evangelismo
            ['nome' => 'Missionário', 'departamento_id' => 18, 'descricao' => 'Missionário'],
            ['nome' => 'Evangelista', 'departamento_id' => 19, 'descricao' => 'Evangelista'],

            // Cargos da Família
            ['nome' => 'Aconselhador', 'departamento_id' => 20, 'descricao' => 'Aconselhador familiar'],
            ['nome' => 'Coordenador de Casais', 'departamento_id' => 21, 'descricao' => 'Coordenador de casais']
        ];

        foreach ($cargos as $cargo) {
            Cargo::create($cargo);
        }

        $this->command->info("✅ " . count($cargos) . " cargos criados");
    }

    private function createMembros()
    {
        $this->command->info('👤 Criando membros demonstrativos...');

        $membros = [
            [
                'nome' => 'João Silva Santos',
                'email' => 'joao.silva@email.com',
                'telefone' => '(11) 99999-7777',
                'data_nascimento' => '1985-03-15',
                'endereco' => 'Rua das Flores, 123 - São Paulo, SP',
                'data_batismo' => '2010-06-20',
                'estado_civil' => 'casado',
                'ativo' => true
            ],
            [
                'nome' => 'Maria Santos Costa',
                'email' => 'maria.costa@email.com',
                'telefone' => '(11) 99999-8888',
                'data_nascimento' => '1990-07-22',
                'endereco' => 'Av. Paulista, 456 - São Paulo, SP',
                'data_batismo' => '2012-09-15',
                'estado_civil' => 'solteiro',
                'ativo' => true
            ],
            [
                'nome' => 'Pedro Costa Oliveira',
                'email' => 'pedro.oliveira@email.com',
                'telefone' => '(11) 99999-9999',
                'data_nascimento' => '1988-11-10',
                'endereco' => 'Rua Augusta, 789 - São Paulo, SP',
                'data_batismo' => '2011-03-08',
                'estado_civil' => 'casado',
                'ativo' => true
            ],
            [
                'nome' => 'Ana Oliveira Lima',
                'email' => 'ana.lima@email.com',
                'telefone' => '(11) 99999-0000',
                'data_nascimento' => '1992-04-05',
                'endereco' => 'Rua Consolação, 321 - São Paulo, SP',
                'data_batismo' => '2013-12-01',
                'estado_civil' => 'solteiro',
                'ativo' => true
            ],
            [
                'nome' => 'Carlos Lima Ferreira',
                'email' => 'carlos.ferreira@email.com',
                'telefone' => '(11) 99999-1111',
                'data_nascimento' => '1987-09-18',
                'endereco' => 'Av. Brigadeiro Faria Lima, 654 - São Paulo, SP',
                'data_batismo' => '2009-05-12',
                'estado_civil' => 'casado',
                'ativo' => true
            ],
            [
                'nome' => 'Lucia Ferreira Silva',
                'email' => 'lucia.silva@email.com',
                'telefone' => '(11) 99999-2222',
                'data_nascimento' => '1995-01-30',
                'endereco' => 'Rua Oscar Freire, 987 - São Paulo, SP',
                'data_batismo' => '2014-08-25',
                'estado_civil' => 'solteiro',
                'ativo' => true
            ],
            [
                'nome' => 'Roberto Almeida Santos',
                'email' => 'roberto.santos@email.com',
                'telefone' => '(11) 99999-3333',
                'data_nascimento' => '1983-12-08',
                'endereco' => 'Rua Pamplona, 147 - São Paulo, SP',
                'data_batismo' => '2008-11-03',
                'estado_civil' => 'casado',
                'ativo' => true
            ],
            [
                'nome' => 'Fernanda Costa Almeida',
                'email' => 'fernanda.almeida@email.com',
                'telefone' => '(11) 99999-4444',
                'data_nascimento' => '1991-06-14',
                'endereco' => 'Av. Jabaquara, 258 - São Paulo, SP',
                'data_batismo' => '2012-04-20',
                'estado_civil' => 'solteiro',
                'ativo' => true
            ],
            [
                'nome' => 'Marcos Oliveira Costa',
                'email' => 'marcos.costa@email.com',
                'telefone' => '(11) 99999-5555',
                'data_nascimento' => '1986-02-25',
                'endereco' => 'Rua Vergueiro, 369 - São Paulo, SP',
                'data_batismo' => '2010-09-10',
                'estado_civil' => 'casado',
                'ativo' => true
            ],
            [
                'nome' => 'Patricia Lima Santos',
                'email' => 'patricia.santos@email.com',
                'telefone' => '(11) 99999-6666',
                'data_nascimento' => '1993-10-12',
                'endereco' => 'Av. São João, 741 - São Paulo, SP',
                'data_batismo' => '2013-07-05',
                'estado_civil' => 'solteiro',
                'ativo' => true
            ]
        ];

        foreach ($membros as $membro) {
            Membro::create($membro);
        }

        // Associar membros a cargos
        $this->associateMembrosToCargos();

        $this->command->info("✅ " . count($membros) . " membros criados");
    }

    private function associateMembrosToCargos()
    {
        $membros = Membro::all();
        $cargos = Cargo::all();

        foreach ($membros as $index => $membro) {
            // Cada membro terá 1-3 cargos
            $numCargos = rand(1, 3);
            $cargosAleatorios = $cargos->random($numCargos);

            foreach ($cargosAleatorios as $cargo) {
                $membro->cargos()->attach($cargo->id, [
                    'data_inicio' => $this->faker->dateTimeBetween('-2 years', 'now'),
                    'ativo' => true
                ]);
            }
        }
    }

    private function createCampanhas()
    {
        $this->command->info('🎯 Criando campanhas demonstrativas...');

        $campanhas = [
            [
                'titulo' => 'Reforma do Templo',
                'descricao' => 'Campanha para reforma completa do templo principal, incluindo sistema de som, iluminação e conforto',
                'meta_valor' => 150000.00,
                'valor_arrecadado' => 87500.00,
                'data_inicio' => now()->subMonths(3),
                'data_fim' => now()->addMonths(2),
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Ação Social - Natal',
                'descricao' => 'Campanha para ajudar famílias carentes no Natal, com cestas básicas e brinquedos',
                'meta_valor' => 25000.00,
                'valor_arrecadado' => 18750.00,
                'data_inicio' => now()->subMonths(1),
                'data_fim' => now()->addDays(30),
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Missões Internacionais',
                'descricao' => 'Campanha para apoiar missionários em campo e projetos internacionais',
                'meta_valor' => 50000.00,
                'valor_arrecadado' => 32500.00,
                'data_inicio' => now()->subMonths(2),
                'data_fim' => now()->addMonths(4),
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Veículo para Missões',
                'descricao' => 'Campanha para aquisição de um veículo para facilitar o trabalho missionário',
                'meta_valor' => 80000.00,
                'valor_arrecadado' => 45000.00,
                'data_inicio' => now()->subMonths(4),
                'data_fim' => now()->addMonths(6),
                'status' => 'ativa',
                'ativo' => true
            ],
            [
                'titulo' => 'Sistema de Som',
                'descricao' => 'Campanha para modernização do sistema de som da igreja',
                'meta_valor' => 35000.00,
                'valor_arrecadado' => 28000.00,
                'data_inicio' => now()->subMonths(1),
                'data_fim' => now()->addMonths(3),
                'status' => 'ativa',
                'ativo' => true
            ]
        ];

        foreach ($campanhas as $campanha) {
            Campanha::create($campanha);
        }

        $this->command->info("✅ " . count($campanhas) . " campanhas criadas");
    }

    private function createTransacoes()
    {
        $this->command->info('💰 Criando transações demonstrativas...');

        $membros = Membro::all();
        $campanhas = Campanha::all();
        $gateways = ['stripe', 'mercadopago', 'pix'];

        // Criar 50 transações demonstrativas
        for ($i = 0; $i < 50; $i++) {
            $membro = $membros->random();
            $campanha = $campanhas->random();
            $gateway = $gateways[array_rand($gateways)];
            $valor = $this->faker->randomFloat(2, 10, 500);

            $transacao = Transacao::create([
                'membro_id' => $membro->id,
                'campanha_id' => $campanha->id,
                'valor' => $valor,
                'tipo' => 'entrada',
                'status' => 'confirmado',
                'descricao' => "Doação para {$campanha->titulo}",
                'data' => now()->subDays(rand(1, 90)),
                'dados_extras' => [
                    'gateway' => $gateway,
                    'payment_id' => 'demo_' . $this->faker->uuid,
                    'nome_doador' => $membro->nome,
                    'email_doador' => $membro->email
                ]
            ]);

            // Criar pagamento associado
            $transacao->pagamentos()->create([
                'valor' => $valor,
                'gateway' => $gateway,
                'gateway_id' => 'demo_' . $this->faker->uuid,
                'gateway_status' => 'confirmed',
                'dados_gateway' => [
                    'payment_id' => 'demo_' . $this->faker->uuid,
                    'nome_doador' => $membro->nome,
                    'email_doador' => $membro->email
                ]
            ]);
        }

        // Criar algumas transações anônimas
        for ($i = 0; $i < 15; $i++) {
            $campanha = $campanhas->random();
            $valor = $this->faker->randomFloat(2, 5, 200);

            Transacao::create([
                'campanha_id' => $campanha->id,
                'valor' => $valor,
                'tipo' => 'entrada',
                'status' => 'confirmado',
                'descricao' => "Doação anônima para {$campanha->titulo}",
                'data' => now()->subDays(rand(1, 90)),
                'dados_extras' => [
                    'gateway' => $gateways[array_rand($gateways)],
                    'payment_id' => 'demo_' . $this->faker->uuid,
                    'nome_doador' => 'Anônimo',
                    'email_doador' => 'anonimo@igreja.com'
                ]
            ]);
        }

        $this->command->info("✅ 65 transações criadas");
    }

    private function createDevocionais()
    {
        $this->command->info('📖 Criando devocionais demonstrativos...');

        $devocionais = [
            [
                'titulo' => 'A Fé que Move Montanhas',
                'texto' => 'Hoje vamos refletir sobre a importância da fé em nossas vidas. A fé é a certeza das coisas que se esperam e a convicção dos fatos que se não veem. Quando temos fé, podemos ver milagres acontecerem em nossas vidas.',
                'versiculo' => 'Marcos 11:23',
                'reflexao' => 'Senhor, aumenta nossa fé para que possamos ver milagres em nossas vidas. Que nossa confiança em Ti seja inabalável.',
                'data' => now()->subDays(5),
                'tipo' => 'devocional',
                'ativo' => true
            ],
            [
                'titulo' => 'O Amor de Deus',
                'texto' => 'O amor de Deus é incondicional e eterno. Ele nos ama não pelo que fazemos, mas pelo que somos. Seu amor é maior que qualquer erro que possamos cometer.',
                'versiculo' => 'João 3:16',
                'reflexao' => 'Pai, ensina-nos a amar como Tu nos amas. Que possamos refletir Teu amor em nossas vidas.',
                'data' => now()->subDays(4),
                'tipo' => 'devocional',
                'ativo' => true
            ],
            [
                'titulo' => 'Gratidão',
                'texto' => 'Em tudo dai graças, pois esta é a vontade de Deus em Cristo Jesus para convosco. A gratidão nos conecta com Deus e nos mantém focados nas bênçãos.',
                'versiculo' => '1 Tessalonicenses 5:18',
                'reflexao' => 'Senhor, ensina-nos a ser gratos em todas as circunstâncias. Que nossa gratidão seja constante.',
                'data' => now()->subDays(3),
                'tipo' => 'devocional',
                'ativo' => true
            ],
            [
                'titulo' => 'Perdão',
                'texto' => 'O perdão é uma das maiores demonstrações do amor de Deus. Quando perdoamos, nos libertamos e permitimos que Deus trabalhe em nossas vidas.',
                'versiculo' => 'Mateus 6:14-15',
                'reflexao' => 'Pai, ajuda-nos a perdoar como Tu nos perdoas. Que o perdão seja uma marca em nossas vidas.',
                'data' => now()->subDays(2),
                'tipo' => 'devocional',
                'ativo' => true
            ],
            [
                'titulo' => 'Perseverança',
                'texto' => 'A perseverança é essencial na vida cristã. Através dela, desenvolvemos caráter e esperança. Não desistimos diante das dificuldades.',
                'versiculo' => 'Romanos 5:3-4',
                'reflexao' => 'Senhor, fortalece-nos para perseverar. Que nossa fé seja inabalável em todas as situações.',
                'data' => now()->subDays(1),
                'tipo' => 'devocional',
                'ativo' => true
            ]
        ];

        foreach ($devocionais as $devocional) {
            Devocional::create($devocional);
        }

        $this->command->info("✅ " . count($devocionais) . " devocionais criados");
    }

    private function createNotificacoes()
    {
        $this->command->info('🔔 Criando notificações demonstrativas...');

        $usuarios = User::all();
        $membros = Membro::all();

        // Notificações para usuários
        foreach ($usuarios as $usuario) {
            Notificacao::create([
                'user_id' => $usuario->id,
                'titulo' => 'Bem-vindo ao Sistema CBAV',
                'mensagem' => 'Seja bem-vindo ao sistema de gestão ministerial CBAV!',
                'tipo' => 'sistema',
                'categoria' => 'boas_vindas',
                'prioridade' => 'normal',
                'lida' => false,
                'enviada_em' => now()
            ]);
        }

        // Notificações de aniversário
        foreach ($membros as $membro) {
            if ($membro->data_nascimento && $membro->data_nascimento->isBirthday()) {
                Notificacao::create([
                    'titulo' => 'Feliz Aniversário!',
                    'mensagem' => "Parabéns {$membro->nome}! Que Deus abençoe seu aniversário!",
                    'tipo' => 'aniversario',
                    'categoria' => 'pessoal',
                    'prioridade' => 'alta',
                    'lida' => false,
                    'enviada_em' => now()
                ]);
            }
        }

        // Notificações de campanhas
        $campanhas = Campanha::all();
        foreach ($campanhas as $campanha) {
            Notificacao::create([
                'titulo' => 'Nova Campanha: ' . $campanha->titulo,
                'mensagem' => "Uma nova campanha foi criada: {$campanha->titulo}. Participe!",
                'tipo' => 'campanha',
                'categoria' => 'financeiro',
                'prioridade' => 'normal',
                'lida' => false,
                'enviada_em' => now()
            ]);
        }

        $this->command->info("✅ Notificações criadas");
    }

    private function createSolicitacoesMinisterio()
    {
        $this->command->info('📝 Criando solicitações de ministério...');

        $membros = Membro::all();
        $ministerios = Ministerio::all();
        $cargos = Cargo::all();

        $statuses = ['pendente', 'aprovada', 'rejeitada'];

        for ($i = 0; $i < 15; $i++) {
            $membro = $membros->random();
            $ministerio = $ministerios->random();
            $cargo = $cargos->where('departamento.ministerio_id', $ministerio->id)->first() ?? $cargos->random();
            $status = $statuses[array_rand($statuses)];

            SolicitacaoMinisterio::create([
                'membro_id' => $membro->id,
                'ministerio_id' => $ministerio->id,
                'cargo_id' => $cargo->id,
                'motivo' => $this->faker->paragraph(),
                'status' => $status,
                'resposta' => $status === 'rejeitada' ? $this->faker->sentence() : null,
                'data_resposta' => $status !== 'pendente' ? now() : null,
                'respondido_por' => $status !== 'pendente' ? User::inRandomOrder()->first()->id : null
            ]);
        }

        $this->command->info("✅ 15 solicitações de ministério criadas");
    }

    private function showDemoInfo()
    {
        $this->command->info('');
        $this->command->info('🎭 DADOS DEMONSTRATIVOS CRIADOS');
        $this->command->info('================================');
        $this->command->info('');
        $this->command->info('👥 USUÁRIOS DE ACESSO:');
        $this->command->info('• Super Admin: admin@cbav.com / 12345678');
        $this->command->info('• Pastor: pastor@igreja.com / 12345678');
        $this->command->info('• Líder: pedro@igreja.com / 12345678');
        $this->command->info('• Membro: ana@igreja.com / 12345678');
        $this->command->info('• Membro: carlos@igreja.com / 12345678');
        $this->command->info('• Membro: lucia@igreja.com / 12345678');
        $this->command->info('');
        $this->command->info('📊 DADOS CRIADOS:');
        $this->command->info('• 8 Ministérios');
        $this->command->info('• 21 Departamentos');
        $this->command->info('• 21 Cargos');
        $this->command->info('• 10 Membros');
        $this->command->info('• 5 Campanhas');
        $this->command->info('• 65 Transações');
        $this->command->info('• 5 Devocionais');
        $this->command->info('• Várias Notificações');
        $this->command->info('• 15 Solicitações de Ministério');
        $this->command->info('');
        $this->command->info('🗑️ PARA REMOVER OS DADOS DEMO:');
        $this->command->info('php artisan demo:clear');
        $this->command->info('');
    }
}
