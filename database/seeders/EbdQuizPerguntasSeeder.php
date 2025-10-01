<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdQuizPergunta;

class EbdQuizPerguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('❓ Criando perguntas de quiz bíblico da EBD demonstrativas...');

        $perguntas = [
            // ===== PERGUNTAS FÁCEIS =====
            
            // Antigo Testamento - Fácil
            [
                'pergunta' => 'Em quantos dias Deus criou o mundo?',
                'opcao_a' => '5 dias',
                'opcao_b' => '6 dias',
                'opcao_c' => '7 dias',
                'opcao_d' => '8 dias',
                'resposta_correta' => 'b',
                'explicacao' => 'Deus criou o mundo em 6 dias e descansou no sétimo dia (Gênesis 1:1-2:3).',
                'referencia_biblica' => 'Gênesis 1:1-2:3',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Qual foi a primeira coisa que Deus criou?',
                'opcao_a' => 'A luz',
                'opcao_b' => 'Os céus e a terra',
                'opcao_c' => 'O homem',
                'opcao_d' => 'Os animais',
                'resposta_correta' => 'b',
                'explicacao' => 'No princípio, criou Deus os céus e a terra (Gênesis 1:1).',
                'referencia_biblica' => 'Gênesis 1:1',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Qual era a profissão de Davi antes de ser rei?',
                'opcao_a' => 'Sacerdote',
                'opcao_b' => 'Pastor de ovelhas',
                'opcao_c' => 'Soldado',
                'opcao_d' => 'Ferreiro',
                'resposta_correta' => 'b',
                'explicacao' => 'Davi era pastor de ovelhas antes de ser ungido rei.',
                'referencia_biblica' => '1 Samuel 16:11',
                'nivel' => 'facil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Qual gigante Davi derrotou?',
                'opcao_a' => 'Golias',
                'opcao_b' => 'Sansão',
                'opcao_c' => 'Gideão',
                'opcao_d' => 'Josué',
                'resposta_correta' => 'a',
                'explicacao' => 'Davi derrotou o gigante Golias com uma pedra e uma funda.',
                'referencia_biblica' => '1 Samuel 17:49',
                'nivel' => 'facil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantos mandamentos Deus deu a Moisés?',
                'opcao_a' => '5 mandamentos',
                'opcao_b' => '10 mandamentos',
                'opcao_c' => '12 mandamentos',
                'opcao_d' => '15 mandamentos',
                'resposta_correta' => 'b',
                'explicacao' => 'Deus deu 10 mandamentos a Moisés no monte Sinai.',
                'referencia_biblica' => 'Êxodo 20:1-17',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantas pragas Deus enviou sobre o Egito?',
                'opcao_a' => '7 pragas',
                'opcao_b' => '10 pragas',
                'opcao_c' => '12 pragas',
                'opcao_d' => '15 pragas',
                'resposta_correta' => 'b',
                'explicacao' => 'Deus enviou 10 pragas sobre o Egito para libertar o povo de Israel.',
                'referencia_biblica' => 'Êxodo 7-12',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Onde Jesus nasceu?',
                'opcao_a' => 'Jerusalém',
                'opcao_b' => 'Belém',
                'opcao_c' => 'Nazaré',
                'opcao_d' => 'Galileia',
                'resposta_correta' => 'b',
                'explicacao' => 'Jesus nasceu em Belém, conforme profetizado nas Escrituras.',
                'referencia_biblica' => 'Mateus 2:1',
                'nivel' => 'facil',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantos discípulos Jesus tinha?',
                'opcao_a' => '10 discípulos',
                'opcao_b' => '12 discípulos',
                'opcao_c' => '15 discípulos',
                'opcao_d' => '20 discípulos',
                'resposta_correta' => 'b',
                'explicacao' => 'Jesus tinha 12 discípulos principais.',
                'referencia_biblica' => 'Lucas 6:12-16',
                'nivel' => 'facil',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Qual é o primeiro mandamento?',
                'opcao_a' => 'Não matarás',
                'opcao_b' => 'Não terás outros deuses diante de mim',
                'opcao_c' => 'Honra teu pai e tua mãe',
                'opcao_d' => 'Não roubarás',
                'resposta_correta' => 'b',
                'explicacao' => 'O primeiro mandamento é: "Não terás outros deuses diante de mim".',
                'referencia_biblica' => 'Êxodo 20:3',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Por que os irmãos de José o venderam?',
                'opcao_a' => 'Porque ele era preguiçoso',
                'opcao_b' => 'Por inveja e ciúmes',
                'opcao_c' => 'Porque ele roubava',
                'opcao_d' => 'Porque ele mentia',
                'resposta_correta' => 'b',
                'explicacao' => 'Os irmãos de José o venderam por inveja e ciúmes, pois ele era o favorito do pai.',
                'referencia_biblica' => 'Gênesis 37:4',
                'nivel' => 'facil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 10
            ],

            // ===== PERGUNTAS MÉDIAS =====
            
            // Antigo Testamento - Médio
            [
                'pergunta' => 'Qual era o nome original de Abraão?',
                'opcao_a' => 'Isaac',
                'opcao_b' => 'Abrão',
                'opcao_c' => 'Jacó',
                'opcao_d' => 'José',
                'resposta_correta' => 'b',
                'explicacao' => 'O nome original de Abraão era Abrão, que significa "pai exaltado".',
                'referencia_biblica' => 'Gênesis 17:5',
                'nivel' => 'medio',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual foi a primeira praga do Egito?',
                'opcao_a' => 'Sangue',
                'opcao_b' => 'Rãs',
                'opcao_c' => 'Piolhos',
                'opcao_d' => 'Morte dos primogênitos',
                'resposta_correta' => 'a',
                'explicacao' => 'A primeira praga foi transformar as águas do rio Nilo em sangue.',
                'referencia_biblica' => 'Êxodo 7:20',
                'nivel' => 'medio',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual mandamento fala sobre o sábado?',
                'opcao_a' => 'Terceiro mandamento',
                'opcao_b' => 'Quarto mandamento',
                'opcao_c' => 'Quinto mandamento',
                'opcao_d' => 'Sexto mandamento',
                'resposta_correta' => 'b',
                'explicacao' => 'O quarto mandamento fala sobre guardar o sábado como dia santo.',
                'referencia_biblica' => 'Êxodo 20:8',
                'nivel' => 'medio',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual foi o primeiro milagre de Jesus?',
                'opcao_a' => 'Multiplicar os pães',
                'opcao_b' => 'Transformar água em vinho',
                'opcao_c' => 'Curar um leproso',
                'opcao_d' => 'Ressuscitar Lázaro',
                'resposta_correta' => 'b',
                'explicacao' => 'O primeiro milagre de Jesus foi transformar água em vinho nas bodas de Caná.',
                'referencia_biblica' => 'João 2:1-11',
                'nivel' => 'medio',
                'categoria' => 'milagres',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quem foi o primeiro mártir da igreja?',
                'opcao_a' => 'Pedro',
                'opcao_b' => 'Paulo',
                'opcao_c' => 'Estevão',
                'opcao_d' => 'João',
                'resposta_correta' => 'c',
                'explicacao' => 'Estevão foi o primeiro mártir da igreja cristã.',
                'referencia_biblica' => 'Atos 7:54-60',
                'nivel' => 'medio',
                'categoria' => 'apostolos',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual dessas parábolas ensina sobre o amor ao próximo?',
                'opcao_a' => 'Parábola do Joio',
                'opcao_b' => 'Parábola da rede',
                'opcao_c' => 'Parábola do bom samaritano',
                'opcao_d' => 'Parábola dos dois filhos',
                'resposta_correta' => 'c',
                'explicacao' => 'A parábola do bom samaritano foi ensinada quando um mestre da Lei perguntou a Jesus quem seria o seu próximo.',
                'referencia_biblica' => 'Lucas 10:25-37',
                'nivel' => 'medio',
                'categoria' => 'parabolas',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quantos anos Moisés passou no deserto antes de libertar o povo?',
                'opcao_a' => '20 anos',
                'opcao_b' => '30 anos',
                'opcao_c' => '40 anos',
                'opcao_d' => '50 anos',
                'resposta_correta' => 'c',
                'explicacao' => 'Moisés passou 40 anos no deserto antes de libertar o povo de Israel.',
                'referencia_biblica' => 'Atos 7:30',
                'nivel' => 'medio',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual foi a prova mais difícil da fé de Abraão?',
                'opcao_a' => 'Deixar sua terra',
                'opcao_b' => 'Esperar pelo filho',
                'opcao_c' => 'Sacrificar Isaac',
                'opcao_d' => 'Viver em tendas',
                'resposta_correta' => 'c',
                'explicacao' => 'A prova mais difícil foi quando Deus pediu que Abraão sacrificasse seu filho Isaac.',
                'referencia_biblica' => 'Gênesis 22:1-19',
                'nivel' => 'medio',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'O que José interpretou para o faraó?',
                'opcao_a' => 'Um sonho sobre o sol',
                'opcao_b' => 'Um sonho sobre vacas e espigas',
                'opcao_c' => 'Um sonho sobre estrelas',
                'opcao_d' => 'Um sonho sobre rios',
                'resposta_correta' => 'b',
                'explicacao' => 'José interpretou o sonho do faraó sobre 7 vacas gordas e 7 vacas magras, 7 espigas cheias e 7 espigas vazias.',
                'referencia_biblica' => 'Gênesis 41:1-36',
                'nivel' => 'medio',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quantos foram batizados no dia de Pentecostes?',
                'opcao_a' => 'Cerca de 1.000 pessoas',
                'opcao_b' => 'Cerca de 2.000 pessoas',
                'opcao_c' => 'Cerca de 3.000 pessoas',
                'opcao_d' => 'Cerca de 5.000 pessoas',
                'resposta_correta' => 'c',
                'explicacao' => 'Cerca de 3.000 pessoas foram batizadas no dia de Pentecostes.',
                'referencia_biblica' => 'Atos 2:41',
                'nivel' => 'medio',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 20
            ],

            // ===== PERGUNTAS DIFÍCEIS =====
            
            // Antigo Testamento - Difícil
            [
                'pergunta' => 'Qual o nome e a idade da pessoa mais velha mencionada na Bíblia?',
                'opcao_a' => 'Enos, viveu 905 anos',
                'opcao_b' => 'Noé, viveu 990 anos',
                'opcao_c' => 'Matusalém, viveu 969 anos',
                'opcao_d' => 'Rainha Ester, viveu 859 anos',
                'resposta_correta' => 'c',
                'explicacao' => 'Matusalém (ou Metusalém) viveu 969 anos. Ele era filho de Enoque, que andou com Deus e foi o avô de Noé.',
                'referencia_biblica' => 'Gênesis 5:27',
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Qual desses não teve o seu nome mudado na Bíblia?',
                'opcao_a' => 'Sara',
                'opcao_b' => 'Abraão',
                'opcao_c' => 'Jacó',
                'opcao_d' => 'Davi',
                'resposta_correta' => 'd',
                'explicacao' => 'Davi não teve seu nome mudado. Sara era Sarai (Gn.17:15), Abraão era Abrão (Gn. 17:5), Jacó tornou-se Israel (Gn. 32.28).',
                'referencia_biblica' => 'Gênesis 17:5,15; 32:28',
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Qual dos nomes de Deus Moisés deveria dar aos israelitas, quando falasse de quem tinha lhe enviado?',
                'opcao_a' => '"Elohim"',
                'opcao_b' => '"El Shadday"',
                'opcao_c' => '"Eu sou o que sou"',
                'opcao_d' => '"Eu sou o Senhor"',
                'resposta_correta' => 'c',
                'explicacao' => '"Eu sou o que Sou" foi a resposta dada por Deus a Moisés, quando perguntou sobre o Seu nome.',
                'referencia_biblica' => 'Êxodo 3:13-14',
                'nivel' => 'dificil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Sobre Samuel, o que não é verdade?',
                'opcao_a' => 'Sua mãe se chamava Ana',
                'opcao_b' => 'Ungiu 3 reis de Israel: José, Saul e Davi',
                'opcao_c' => 'Sucedeu o profeta Eli',
                'opcao_d' => 'Teve uma visão enquanto ainda era muito novo',
                'resposta_correta' => 'b',
                'explicacao' => 'O profeta Samuel ungiu a Saul e Davi como reis de Israel. José não foi rei. Foi governador no Egito muitos anos antes.',
                'referencia_biblica' => '1 Samuel 10:1; 16:13',
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Que animal falou com Balaão?',
                'opcao_a' => 'jumenta',
                'opcao_b' => 'camelo',
                'opcao_c' => 'cordeiro',
                'opcao_d' => 'pomba',
                'resposta_correta' => 'a',
                'explicacao' => 'O Senhor fez a jumenta falar com Balaão quando este ia ao encontro de Balaque para amaldiçoar o povo de Deus em troca de riquezas.',
                'referencia_biblica' => 'Números 22:28',
                'nivel' => 'dificil',
                'categoria' => 'antigo_testamento',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Enquanto pastor de ovelhas, Davi protegeu seu rebanho de dois animais perigosos. Quais?',
                'opcao_a' => 'serpente e dromedário',
                'opcao_b' => 'urso e leão',
                'opcao_c' => 'cobra e lobo',
                'opcao_d' => 'urso e escorpião',
                'resposta_correta' => 'b',
                'explicacao' => 'Um leão e um urso foram os animais que Davi matou.',
                'referencia_biblica' => '1 Samuel 17:34-37',
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Quando bebê, como Moisés foi salvo do decreto infanticida do Faraó?',
                'opcao_a' => 'Foi levado às pressas para fora do Egito',
                'opcao_b' => 'Foi escondido dentro de uma caverna',
                'opcao_c' => 'Foi colocado num cesto e lançado no rio',
                'opcao_d' => 'Foi levado ao templo para servir a Deus',
                'resposta_correta' => 'c',
                'explicacao' => 'Moisés foi colocado num cestinho e deixado à beira rio. A filha do Faraó viu e adotou-o como seu filho.',
                'referencia_biblica' => 'Êxodo 2:3',
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Quantos eram os discípulos mais próximos de Jesus?',
                'opcao_a' => '10',
                'opcao_b' => '7',
                'opcao_c' => '5',
                'opcao_d' => '12',
                'resposta_correta' => 'd',
                'explicacao' => 'Jesus escolheu 12 discípulos que costumavam acompanhá-lo durante todo o seu ministério.',
                'referencia_biblica' => 'Lucas 6:12-16',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Complete o versículo: "Porque Deus tanto amou o mundo..."',
                'opcao_a' => 'que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna.',
                'opcao_b' => 'que enviou seu filho ao mundo, para que o mundo fosse salvo por ele.',
                'opcao_c' => 'ao ponto de sermos chamados filhos seus, e de fato somos.',
                'opcao_d' => 'que veio para o que era seu, mas o seus não o receberam.',
                'resposta_correta' => 'a',
                'explicacao' => '"Porque Deus tanto amou o mundo que deu o seu Filho Unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna."',
                'referencia_biblica' => 'João 3:16',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Qual o nome da ilha onde João escreveu o livro de Apocalipse?',
                'opcao_a' => 'Ilha de Creta',
                'opcao_b' => 'Ilha de Malta',
                'opcao_c' => 'Ilha de Patmos',
                'opcao_d' => 'Ilha de Pérgamo',
                'resposta_correta' => 'c',
                'explicacao' => 'Na ilha de Patmos, João teve a visão do que acontecerá no final dos tempos.',
                'referencia_biblica' => 'Apocalipse 1:9',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'ativo' => true,
                'pontuacao' => 30
            ],

            // ===== PERGUNTAS ADICIONAIS - MISTAS =====
            
            // Milagres
            [
                'pergunta' => 'Quantos pães Jesus multiplicou para alimentar 5.000 pessoas?',
                'opcao_a' => '3 pães',
                'opcao_b' => '5 pães',
                'opcao_c' => '7 pães',
                'opcao_d' => '12 pães',
                'resposta_correta' => 'b',
                'explicacao' => 'Jesus multiplicou 5 pães e 2 peixes para alimentar 5.000 homens, além de mulheres e crianças.',
                'referencia_biblica' => 'João 6:9',
                'nivel' => 'medio',
                'categoria' => 'milagres',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quantas pessoas Jesus ressuscitou durante seu ministério?',
                'opcao_a' => '1 pessoa',
                'opcao_b' => '2 pessoas',
                'opcao_c' => '3 pessoas',
                'opcao_d' => '4 pessoas',
                'resposta_correta' => 'c',
                'explicacao' => 'Jesus ressuscitou 3 pessoas: a filha de Jairo, o filho da viúva de Naim e Lázaro.',
                'referencia_biblica' => 'Marcos 5:41; Lucas 7:15; João 11:44',
                'nivel' => 'dificil',
                'categoria' => 'milagres',
                'ativo' => true,
                'pontuacao' => 30
            ],

            // Parábolas
            [
                'pergunta' => 'Qual parábola fala sobre um homem que foi assaltado no caminho?',
                'opcao_a' => 'Parábola do semeador',
                'opcao_b' => 'Parábola do bom samaritano',
                'opcao_c' => 'Parábola do filho pródigo',
                'opcao_d' => 'Parábola das dez virgens',
                'resposta_correta' => 'b',
                'explicacao' => 'A parábola do bom samaritano conta a história de um homem que foi assaltado e deixado ferido no caminho.',
                'referencia_biblica' => 'Lucas 10:30-37',
                'nivel' => 'facil',
                'categoria' => 'parabolas',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantas virgens havia na parábola das dez virgens?',
                'opcao_a' => '5 virgens',
                'opcao_b' => '10 virgens',
                'opcao_c' => '12 virgens',
                'opcao_d' => '15 virgens',
                'resposta_correta' => 'b',
                'explicacao' => 'A parábola fala sobre 10 virgens, sendo 5 prudentes e 5 insensatas.',
                'referencia_biblica' => 'Mateus 25:1-13',
                'nivel' => 'medio',
                'categoria' => 'parabolas',
                'ativo' => true,
                'pontuacao' => 20
            ],

            // Profetas
            [
                'pergunta' => 'Qual profeta foi engolido por um grande peixe?',
                'opcao_a' => 'Elias',
                'opcao_b' => 'Jonas',
                'opcao_c' => 'Daniel',
                'opcao_d' => 'Jeremias',
                'resposta_correta' => 'b',
                'explicacao' => 'Jonas foi engolido por um grande peixe quando tentou fugir da missão que Deus lhe deu.',
                'referencia_biblica' => 'Jonas 1:17',
                'nivel' => 'facil',
                'categoria' => 'profetas',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantos anos Daniel e seus amigos ficaram na Babilônia?',
                'opcao_a' => '50 anos',
                'opcao_b' => '60 anos',
                'opcao_c' => '70 anos',
                'opcao_d' => '80 anos',
                'resposta_correta' => 'c',
                'explicacao' => 'Daniel e seus amigos ficaram 70 anos na Babilônia durante o cativeiro.',
                'referencia_biblica' => 'Daniel 1:21',
                'nivel' => 'dificil',
                'categoria' => 'profetas',
                'ativo' => true,
                'pontuacao' => 30
            ],

            // Apóstolos
            [
                'pergunta' => 'Qual apóstolo negou Jesus três vezes?',
                'opcao_a' => 'João',
                'opcao_b' => 'Pedro',
                'opcao_c' => 'Tiago',
                'opcao_d' => 'André',
                'resposta_correta' => 'b',
                'explicacao' => 'Pedro negou Jesus três vezes antes do galo cantar, conforme Jesus havia predito.',
                'referencia_biblica' => 'Mateus 26:69-75',
                'nivel' => 'facil',
                'categoria' => 'apostolos',
                'ativo' => true,
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quantas viagens missionárias Paulo fez?',
                'opcao_a' => '2 viagens',
                'opcao_b' => '3 viagens',
                'opcao_c' => '4 viagens',
                'opcao_d' => '5 viagens',
                'resposta_correta' => 'b',
                'explicacao' => 'Paulo fez 3 viagens missionárias registradas no livro de Atos.',
                'referencia_biblica' => 'Atos 13-21',
                'nivel' => 'dificil',
                'categoria' => 'apostolos',
                'ativo' => true,
                'pontuacao' => 30
            ],

            // Perguntas Gerais
            [
                'pergunta' => 'Qual o nome do jardim plantado por Deus para o 1º casal criado?',
                'opcao_a' => 'Jardim do Getsêmani',
                'opcao_b' => 'Rosa de Sarom',
                'opcao_c' => 'Jardim do Éden',
                'opcao_d' => 'Paraíso',
                'resposta_correta' => 'c',
                'explicacao' => 'Jardim localizado no Éden. Esse foi o lugar preparado por Deus para Adão e Eva quando foram criados.',
                'referencia_biblica' => 'Gênesis 2:8',
                'nivel' => 'medio',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Em quais livros da Bíblia é narrada a história do nascimento de Jesus?',
                'opcao_a' => 'Gênesis e Salmos',
                'opcao_b' => 'Mateus e Marcos',
                'opcao_c' => 'Mateus e Lucas',
                'opcao_d' => 'João e Atos',
                'resposta_correta' => 'c',
                'explicacao' => 'A história do nascimento de Jesus é narrada nos evangelhos de Mateus e Lucas.',
                'referencia_biblica' => 'Mateus 1-2; Lucas 1-2',
                'nivel' => 'medio',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quantos discípulos foram comissionados por Jesus?',
                'opcao_a' => '3 discípulos - Pedro, Tiago e João',
                'opcao_b' => '10 discípulos',
                'opcao_c' => '40 discípulos',
                'opcao_d' => '70 discípulos',
                'resposta_correta' => 'd',
                'explicacao' => '70 foram enviados em missão para os lugares onde Jesus iria visitar.',
                'referencia_biblica' => 'Lucas 10:1',
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Que mãe pediu a Jesus que seus filhos estivessem à Sua direita e esquerda na glória?',
                'opcao_a' => 'A mãe de Tiago e José',
                'opcao_b' => 'A mulher siro-fenícia',
                'opcao_c' => 'A mãe dos filhos de Zebedeu, Tiago e João',
                'opcao_d' => 'A viúva de Naim',
                'resposta_correta' => 'c',
                'explicacao' => 'A mulher de Zebedeu, mãe de Tiago e João, fez esse pedido ao Senhor junto dos filhos.',
                'referencia_biblica' => 'Mateus 20:20',
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Num episódio com Jesus, quem é que estava preocupada e inquieta com muitas coisas? E quem escolheu a boa parte?',
                'opcao_a' => 'Maria Madalena e Joana mulher de Herodes',
                'opcao_b' => 'Maria de Betânia e Susana que ajudava com ofertas',
                'opcao_c' => 'Marta e Maria de Betânia',
                'opcao_d' => 'Maria de Betânia e Maria mãe de Jesus',
                'resposta_correta' => 'c',
                'explicacao' => 'Marta servia aos convidados enquanto Maria escolheu ouvir a Jesus. Ambas viviam em Betânia.',
                'referencia_biblica' => 'Lucas 10:38-41',
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Qual destes livros NÃO foi escrito pelo apóstolo Paulo?',
                'opcao_a' => 'Tito',
                'opcao_b' => 'Tiago',
                'opcao_c' => 'Romanos',
                'opcao_d' => 'Filemon',
                'resposta_correta' => 'b',
                'explicacao' => 'O livro de Tiago foi escrito por Tiago e não por Paulo. Tito, Romanos, Filemon e outros foram escritos por Paulo.',
                'referencia_biblica' => 'Tiago 1:1',
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 30
            ],
            [
                'pergunta' => 'Quem ressuscitou um jovem chamado Êutico?',
                'opcao_a' => 'Jesus',
                'opcao_b' => 'Pedro',
                'opcao_c' => 'Filipe',
                'opcao_d' => 'Paulo',
                'resposta_correta' => 'd',
                'explicacao' => 'Paulo consolou a igreja depois de ressuscitar Êutico. O jovem caira de uma janela do 3º andar.',
                'referencia_biblica' => 'Atos 20:9-12',
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'ativo' => true,
                'pontuacao' => 30
            ]
        ];

        foreach ($perguntas as $pergunta) {
            EbdQuizPergunta::updateOrCreate(
                ['pergunta' => $pergunta['pergunta']],
                $pergunta
            );
        }

        $this->command->info('✅ Perguntas de quiz bíblico da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de perguntas: ' . count($perguntas));
        
        // Estatísticas
        $ativas = collect($perguntas)->where('ativo', true)->count();
        $facil = collect($perguntas)->where('nivel', 'facil')->count();
        $medio = collect($perguntas)->where('nivel', 'medio')->count();
        $dificil = collect($perguntas)->where('nivel', 'dificil')->count();
        
        $antigo_testamento = collect($perguntas)->where('categoria', 'antigo_testamento')->count();
        $novo_testamento = collect($perguntas)->where('categoria', 'novo_testamento')->count();
        $personagens = collect($perguntas)->where('categoria', 'personagens')->count();
        $milagres = collect($perguntas)->where('categoria', 'milagres')->count();
        $parabolas = collect($perguntas)->where('categoria', 'parabolas')->count();
        $profetas = collect($perguntas)->where('categoria', 'profetas')->count();
        $apostolos = collect($perguntas)->where('categoria', 'apostolos')->count();
        $geral = collect($perguntas)->where('categoria', 'geral')->count();
        
        $this->command->info("✅ Ativas: {$ativas}");
        $this->command->info("🟢 Fácil: {$facil}");
        $this->command->info("🟡 Médio: {$medio}");
        $this->command->info("🔴 Difícil: {$dificil}");
        $this->command->info("📖 Antigo Testamento: {$antigo_testamento}");
        $this->command->info("📖 Novo Testamento: {$novo_testamento}");
        $this->command->info("👥 Personagens: {$personagens}");
        $this->command->info("✨ Milagres: {$milagres}");
        $this->command->info("📚 Parábolas: {$parabolas}");
        $this->command->info("🔮 Profetas: {$profetas}");
        $this->command->info("✝️ Apóstolos: {$apostolos}");
        $this->command->info("📖 Geral: {$geral}");
    }
} 