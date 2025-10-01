<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdLicao;

class EbdLicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📖 Criando lições da EBD demonstrativas...');

        $licoes = [
            [
                'titulo' => 'A Criação do Mundo',
                'descricao' => 'Estudo sobre a criação do mundo em seis dias',
                'objetivos' => 'Compreender que Deus é o criador de todas as coisas',
                'versiculo_chave' => 'Gênesis 1:1',
                'conteudo' => 'Esta lição aborda a criação do mundo em seis dias, mostrando o poder e a sabedoria de Deus. Vamos estudar cada dia da criação e suas implicações para nossa fé.',
                'aplicacao_pratica' => 'Reconhecer que tudo foi criado por Deus e que devemos cuidar da criação',
                'oracao' => 'Senhor, obrigado por criar todas as coisas. Ajuda-nos a cuidar da sua criação.',
                'material_necessario' => 'Bíblia, cartazes da criação, atividades manuais',
                'duracao_minutos' => 45,
                'dificuldade' => 'facil',
                'ativo' => true
            ],
            [
                'titulo' => 'A Queda do Homem',
                'descricao' => 'Estudo sobre a entrada do pecado no mundo',
                'objetivos' => 'Entender as consequências do pecado e a necessidade de redenção',
                'versiculo_chave' => 'Gênesis 3:6',
                'conteudo' => 'Estudaremos como o pecado entrou no mundo através da desobediência de Adão e Eva, e as consequências que isso trouxe para toda a humanidade.',
                'aplicacao_pratica' => 'Reconhecer a importância da obediência a Deus e as consequências do pecado',
                'oracao' => 'Senhor, perdoa nossos pecados e ajuda-nos a obedecer à tua vontade.',
                'material_necessario' => 'Bíblia, figuras da história, dramatização',
                'duracao_minutos' => 50,
                'dificuldade' => 'facil',
                'ativo' => true
            ],
            [
                'titulo' => 'A Fé de Abraão',
                'descricao' => 'Estudo sobre a vida de fé de Abraão',
                'objetivos' => 'Aprender sobre a fé e obediência através da vida de Abraão',
                'versiculo_chave' => 'Gênesis 12:1',
                'conteudo' => 'Vamos estudar como Abraão demonstrou fé ao deixar sua terra e seguir a direção de Deus, tornando-se o pai da fé.',
                'aplicacao_pratica' => 'Desenvolver uma fé que confia em Deus mesmo quando não entendemos o caminho',
                'oracao' => 'Senhor, aumenta nossa fé para confiar em ti como Abraão confiou.',
                'material_necessario' => 'Bíblia, mapa da jornada de Abraão, atividades de confiança',
                'duracao_minutos' => 55,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'José: Do Poço ao Palácio',
                'descricao' => 'Estudo sobre a vida de José e a providência divina',
                'objetivos' => 'Ver como Deus trabalha em todas as circunstâncias para o bem',
                'versiculo_chave' => 'Gênesis 50:20',
                'conteudo' => 'A história de José mostra como Deus pode transformar situações difíceis em bênçãos, usando tudo para cumprir Seu propósito.',
                'aplicacao_pratica' => 'Confiar que Deus está trabalhando mesmo nas dificuldades',
                'oracao' => 'Senhor, ajuda-nos a confiar que tu trabalhas em todas as coisas para o nosso bem.',
                'material_necessario' => 'Bíblia, fantoches, dramatização da história',
                'duracao_minutos' => 60,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'Moisés: O Libertador',
                'descricao' => 'Estudo sobre a vida de Moisés e a libertação do Egito',
                'objetivos' => 'Entender como Deus usa pessoas comuns para realizar grandes feitos',
                'versiculo_chave' => 'Êxodo 14:14',
                'conteudo' => 'A vida de Moisés mostra como Deus prepara e usa Seus servos para libertar Seu povo e cumprir Suas promessas.',
                'aplicacao_pratica' => 'Reconhecer que Deus pode usar cada um de nós para Sua obra',
                'oracao' => 'Senhor, usa-nos como instrumentos para tua obra, assim como usaste Moisés.',
                'material_necessario' => 'Bíblia, figuras das pragas, dramatização',
                'duracao_minutos' => 65,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'Os Dez Mandamentos',
                'descricao' => 'Estudo sobre os dez mandamentos de Deus',
                'objetivos' => 'Compreender a importância dos mandamentos de Deus',
                'versiculo_chave' => 'Êxodo 20:2-3',
                'conteudo' => 'Estudaremos os dez mandamentos como guia para uma vida que agrada a Deus e promove o bem-estar da sociedade.',
                'aplicacao_pratica' => 'Aplicar os princípios dos mandamentos em nossa vida diária',
                'oracao' => 'Senhor, ajuda-nos a guardar os teus mandamentos com amor e gratidão.',
                'material_necessario' => 'Bíblia, cartazes dos mandamentos, atividades práticas',
                'duracao_minutos' => 70,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'Davi: Um Homem Segundo o Coração de Deus',
                'descricao' => 'Estudo sobre a vida de Davi e suas qualidades',
                'objetivos' => 'Aprender sobre o caráter que agrada a Deus',
                'versiculo_chave' => '1 Samuel 16:7',
                'conteudo' => 'A vida de Davi mostra as qualidades que Deus valoriza: coragem, fé, arrependimento e dependência de Deus.',
                'aplicacao_pratica' => 'Desenvolver um caráter que agrada a Deus',
                'oracao' => 'Senhor, molda nosso coração para ser segundo o teu coração, como foi Davi.',
                'material_necessario' => 'Bíblia, história de Davi e Golias, atividades',
                'duracao_minutos' => 60,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'Os Profetas: Voz de Deus',
                'descricao' => 'Estudo sobre os profetas do Antigo Testamento',
                'objetivos' => 'Entender o papel dos profetas e a importância de ouvir a voz de Deus',
                'versiculo_chave' => 'Isaías 6:8',
                'conteudo' => 'Estudaremos como os profetas foram usados por Deus para falar ao Seu povo, chamando ao arrependimento e anunciando a esperança.',
                'aplicacao_pratica' => 'Estar atento à voz de Deus em nossa vida',
                'oracao' => 'Senhor, abre nossos ouvidos para ouvir tua voz e nosso coração para obedecer.',
                'material_necessario' => 'Bíblia, profecias messiânicas, atividades',
                'duracao_minutos' => 75,
                'dificuldade' => 'dificil',
                'ativo' => true
            ],
            [
                'titulo' => 'O Nascimento de Jesus',
                'descricao' => 'Estudo sobre o nascimento de Jesus Cristo',
                'objetivos' => 'Celebrar o nascimento de Jesus e seu significado',
                'versiculo_chave' => 'Lucas 2:11',
                'conteudo' => 'A história do nascimento de Jesus mostra como Deus cumpriu Suas promessas e veio ao mundo para salvar a humanidade.',
                'aplicacao_pratica' => 'Celebrar Jesus como o Salvador prometido',
                'oracao' => 'Senhor Jesus, obrigado por vir ao mundo para nos salvar. Ajuda-nos a celebrar teu nascimento com gratidão.',
                'material_necessario' => 'Bíblia, presépio, músicas natalinas',
                'duracao_minutos' => 50,
                'dificuldade' => 'facil',
                'ativo' => true
            ],
            [
                'titulo' => 'Os Milagres de Jesus',
                'descricao' => 'Estudo sobre os milagres de Jesus Cristo',
                'objetivos' => 'Compreender o poder e a compaixão de Jesus através de Seus milagres',
                'versiculo_chave' => 'João 20:30-31',
                'conteudo' => 'Vamos estudar alguns dos principais milagres de Jesus, mostrando Seu poder divino e Sua compaixão pelos necessitados.',
                'aplicacao_pratica' => 'Confiar no poder de Jesus para transformar vidas',
                'oracao' => 'Senhor Jesus, confiamos no teu poder para fazer milagres em nossas vidas.',
                'material_necessario' => 'Bíblia, figuras dos milagres, dramatizações',
                'duracao_minutos' => 60,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'A Morte e Ressurreição de Jesus',
                'descricao' => 'Estudo sobre o sacrifício e vitória de Jesus',
                'objetivos' => 'Entender o significado da morte e ressurreição de Jesus',
                'versiculo_chave' => 'João 3:16',
                'conteudo' => 'O sacrifício de Jesus na cruz e Sua ressurreição são o fundamento da fé cristã, mostrando o amor de Deus e a vitória sobre o pecado.',
                'aplicacao_pratica' => 'Aceitar o sacrifício de Jesus e viver em gratidão',
                'oracao' => 'Senhor Jesus, obrigado por teu sacrifício na cruz. Ajuda-nos a viver em gratidão pela tua ressurreição.',
                'material_necessario' => 'Bíblia, cruz, símbolos da Páscoa',
                'duracao_minutos' => 70,
                'dificuldade' => 'medio',
                'ativo' => true
            ],
            [
                'titulo' => 'A Igreja Primitiva',
                'descricao' => 'Estudo sobre o nascimento e desenvolvimento da igreja',
                'objetivos' => 'Entender como a igreja começou e se desenvolveu',
                'versiculo_chave' => 'Atos 2:42',
                'conteudo' => 'Estudaremos o nascimento da igreja, o derramamento do Espírito Santo e como os primeiros cristãos viviam e compartilhavam o evangelho.',
                'aplicacao_pratica' => 'Participar ativamente da vida da igreja',
                'oracao' => 'Senhor, ajuda-nos a viver em comunhão como a igreja primitiva.',
                'material_necessario' => 'Bíblia, mapa das viagens missionárias',
                'duracao_minutos' => 65,
                'dificuldade' => 'medio',
                'ativo' => true
            ]
        ];

        foreach ($licoes as $licao) {
            EbdLicao::updateOrCreate(
                ['titulo' => $licao['titulo']],
                $licao
            );
        }

        $this->command->info('✅ Lições da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de lições: ' . count($licoes));
        
        // Estatísticas
        $ativas = collect($licoes)->where('ativo', true)->count();
        $facil = collect($licoes)->where('dificuldade', 'facil')->count();
        $medio = collect($licoes)->where('dificuldade', 'medio')->count();
        $dificil = collect($licoes)->where('dificuldade', 'dificil')->count();
        
        $this->command->info("✅ Ativas: {$ativas}");
        $this->command->info("📚 Fácil: {$facil}");
        $this->command->info("📖 Médio: {$medio}");
        $this->command->info("🎓 Difícil: {$dificil}");
    }
} 