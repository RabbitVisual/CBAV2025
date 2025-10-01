<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdQuizPergunta;

class EbdQuizPerguntasSeeder extends Seeder
{
    public function run()
    {
        $perguntas = [
            // PERGUNTAS FÁCEIS
            [
                'pergunta' => 'Quantas pragas foram enviadas ao Egito?',
                'opcao_a' => '7 Pragas',
                'opcao_b' => '10 Pragas',
                'opcao_c' => '3 Pragas',
                'opcao_d' => '12 Pragas',
                'resposta_correta' => 'b',
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'explicacao' => 'Deus enviou 10 pragas ao Egito para libertar o povo de Israel.',
                'referencia_biblica' => 'Êxodo 7-10',
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quem foi lançado na cova dos leões?',
                'opcao_a' => 'Paulo',
                'opcao_b' => 'José',
                'opcao_c' => 'Daniel',
                'opcao_d' => 'Davi',
                'resposta_correta' => 'c',
                'nivel' => 'facil',
                'categoria' => 'personagens',
                'explicacao' => 'Daniel foi lançado na cova dos leões por continuar orando a Deus.',
                'referencia_biblica' => 'Daniel 6:16',
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Qual instrumento Davi gostava de tocar?',
                'opcao_a' => 'Tambor',
                'opcao_b' => 'Harpa',
                'opcao_c' => 'Flauta',
                'opcao_d' => 'Trombeta',
                'resposta_correta' => 'b',
                'nivel' => 'facil',
                'categoria' => 'personagens',
                'explicacao' => 'Davi era conhecido por tocar harpa e acalmar o rei Saul.',
                'referencia_biblica' => '1 Samuel 16:23',
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Quando Jesus nasceu, onde Ele foi colocado?',
                'opcao_a' => 'Foi colocado numa cama',
                'opcao_b' => 'Foi colocado numa manjedoura',
                'opcao_c' => 'Foi colocado em um trono',
                'opcao_d' => 'Foi colocado no chão',
                'resposta_correta' => 'b',
                'nivel' => 'facil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'Jesus foi colocado numa manjedoura porque não havia lugar na hospedaria.',
                'referencia_biblica' => 'Lucas 2:16',
                'pontuacao' => 10
            ],
            [
                'pergunta' => 'Na parábola do semeador, quais sementes cresceram e deram boa colheita?',
                'opcao_a' => 'As sementes que caíram nas pedras',
                'opcao_b' => 'As sementes que caíram em boa terra',
                'opcao_c' => 'As sementes que caíram entre os espinhos',
                'opcao_d' => 'As sementes que caíram no caminho',
                'resposta_correta' => 'b',
                'nivel' => 'facil',
                'categoria' => 'parabolas',
                'explicacao' => 'As sementes que caíram em boa terra representam aqueles que recebem a palavra e produzem fruto.',
                'referencia_biblica' => 'Mateus 13:23',
                'pontuacao' => 10
            ],
            
            // PERGUNTAS MÉDIAS
            [
                'pergunta' => 'O que são os "pentateucos"?',
                'opcao_a' => 'Os Cinco Primeiros Livros da Bíblia',
                'opcao_b' => 'Os Cinco Primeiros Profetas da Bíblia',
                'opcao_c' => 'Pentateucos eram profetas que cuidavam dos pentaucos nos Tabernáculos',
                'opcao_d' => 'Rituais do povo de Israel',
                'resposta_correta' => 'a',
                'nivel' => 'medio',
                'categoria' => 'antigo_testamento',
                'explicacao' => 'Pentateuco significa "cinco livros" e se refere aos primeiros cinco livros da Bíblia: Gênesis, Êxodo, Levítico, Números e Deuteronômio.',
                'referencia_biblica' => 'Gênesis, Êxodo, Levítico, Números, Deuteronômio',
                'pontuacao' => 15
            ],
            [
                'pergunta' => '"Do que come veio algo para comer, do que é forte veio algo doce". Quem fez e qual a resposta do enigma?',
                'opcao_a' => 'Sansão fez, a resposta é o Leão e o Favo de Mel',
                'opcao_b' => 'Sansão fez, a resposta é o Urso e o Mel',
                'opcao_c' => 'Davi fez, e a resposta é o Leão e a Uva',
                'opcao_d' => 'Davi fez, e a resposta é o Lobo e o Favo de Mel',
                'resposta_correta' => 'a',
                'nivel' => 'medio',
                'categoria' => 'personagens',
                'explicacao' => 'Sansão propôs este enigma sobre o leão que matou e do qual saiu mel.',
                'referencia_biblica' => 'Juízes 14:14',
                'pontuacao' => 15
            ],
            [
                'pergunta' => 'A Quem o profeta se refere quando fala "A voz que clama no deserto"?',
                'opcao_a' => 'Ezequiel',
                'opcao_b' => 'Jesus Cristo',
                'opcao_c' => 'Paulo de Tarso',
                'opcao_d' => 'João Batista',
                'resposta_correta' => 'd',
                'nivel' => 'medio',
                'categoria' => 'profetas',
                'explicacao' => 'João Batista é a voz que clama no deserto, preparando o caminho do Senhor.',
                'referencia_biblica' => 'Isaías 40:3-5',
                'pontuacao' => 15
            ],
            [
                'pergunta' => 'Qual desses itens NÃO estava dentro da arca da aliança?',
                'opcao_a' => 'Maná',
                'opcao_b' => 'Vara de Arão',
                'opcao_c' => 'Manto Sacerdotal de Arão',
                'opcao_d' => 'Tábua dos Dez Mandamentos',
                'resposta_correta' => 'c',
                'nivel' => 'medio',
                'categoria' => 'antigo_testamento',
                'explicacao' => 'A arca continha as tábuas da lei, o maná e a vara de Arão, mas não o manto sacerdotal.',
                'referencia_biblica' => 'Hebreus 9:4',
                'pontuacao' => 15
            ],
            [
                'pergunta' => 'Quantos anos Jesus começou, de fato, seu ministério?',
                'opcao_a' => '12 anos',
                'opcao_b' => '40 anos',
                'opcao_c' => '38 anos',
                'opcao_d' => '30 anos',
                'resposta_correta' => 'd',
                'nivel' => 'medio',
                'categoria' => 'novo_testamento',
                'explicacao' => 'Jesus tinha aproximadamente 30 anos quando começou seu ministério público.',
                'referencia_biblica' => 'Lucas 3:23',
                'pontuacao' => 15
            ],
            
            // PERGUNTAS DIFÍCEIS
            [
                'pergunta' => 'Qual a cor do primeiro cavalo dos quatro cavaleiros do Apocalipse?',
                'opcao_a' => 'Amarelo',
                'opcao_b' => 'Preto',
                'opcao_c' => 'Vermelho',
                'opcao_d' => 'Branco',
                'resposta_correta' => 'd',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'O primeiro cavaleiro monta um cavalo branco e representa a vitória.',
                'referencia_biblica' => 'Apocalipse 6:2',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'O que o terceiro cavaleiro do Apocalipse carregava em suas mãos?',
                'opcao_a' => 'Foice',
                'opcao_b' => 'Espada',
                'opcao_c' => 'Balança',
                'opcao_d' => 'Arco e Flecha',
                'resposta_correta' => 'c',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'O terceiro cavaleiro carrega uma balança, representando a fome e a escassez.',
                'referencia_biblica' => 'Apocalipse 6:5-6',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Qual desses é dito na Bíblia como o pecado imperdoável?',
                'opcao_a' => 'Rebeldia',
                'opcao_b' => 'Rasgar o livro sagrado',
                'opcao_c' => 'Adorar ídolos',
                'opcao_d' => 'Blasfemar contra o Espírito Santo',
                'resposta_correta' => 'd',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'A blasfêmia contra o Espírito Santo é considerada o pecado imperdoável.',
                'referencia_biblica' => 'Mateus 12:31-32',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Quantos anos estipula-se que Noé levou para construir a arca?',
                'opcao_a' => 'Cerca de 120 anos',
                'opcao_b' => 'Cerca de 100 anos',
                'opcao_c' => 'Cerca de 90 anos',
                'opcao_d' => 'Cerca de 200 anos',
                'resposta_correta' => 'a',
                'nivel' => 'dificil',
                'categoria' => 'antigo_testamento',
                'explicacao' => 'Acredita-se que Noé levou cerca de 120 anos para construir a arca.',
                'referencia_biblica' => 'Gênesis 6:3',
                'pontuacao' => 20
            ],
            [
                'pergunta' => '"Tetelestai" palavra dita por Jesus na cruz, cujo significado é:',
                'opcao_a' => '"chegou minha hora"',
                'opcao_b' => '"está consumado"',
                'opcao_c' => '"até o fim"',
                'opcao_d' => '"Deus seja louvado"',
                'resposta_correta' => 'b',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => '"Tetelestai" significa "está consumado" em grego, indicando que a obra da salvação foi completada.',
                'referencia_biblica' => 'João 19:30',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Em que forma o Espírito Santo desceu sobre Jesus quando este se batizou?',
                'opcao_a' => 'Mosca',
                'opcao_b' => 'Pomba Branca',
                'opcao_c' => 'Pardal',
                'opcao_d' => 'Pomba Cinza',
                'resposta_correta' => 'b',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'O Espírito Santo desceu como uma pomba branca sobre Jesus durante seu batismo.',
                'referencia_biblica' => 'Mateus 3:16',
                'pontuacao' => 20
            ],
            [
                'pergunta' => '"Emanuel" significa:',
                'opcao_a' => '"Deus conosco"',
                'opcao_b' => '"Deus meu"',
                'opcao_c' => '"Filho de Deus"',
                'opcao_d' => '"Deus único"',
                'resposta_correta' => 'a',
                'nivel' => 'dificil',
                'categoria' => 'antigo_testamento',
                'explicacao' => 'Emanuel significa "Deus conosco" e é um dos nomes proféticos de Jesus.',
                'referencia_biblica' => 'Isaías 7:14',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Complete: O_____ não vem senão a roubar, matar, e destruir; eu vim para que tenham vida, e a tenham com abundancia.',
                'opcao_a' => 'falso profeta',
                'opcao_b' => 'ladrão',
                'opcao_c' => 'demônio',
                'opcao_d' => 'diabo',
                'resposta_correta' => 'b',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'Jesus disse que o ladrão vem para roubar, matar e destruir, mas Ele veio para dar vida.',
                'referencia_biblica' => 'João 10:10',
                'pontuacao' => 20
            ],
            [
                'pergunta' => '" Perseguia, mas agora sou perseguido; matava, mas agora morrerei pelo mesmo motivo de meus assassinatos" Quem sou eu?',
                'opcao_a' => 'Paulo de Tarso',
                'opcao_b' => 'João Batista',
                'opcao_c' => 'Mardoqueu',
                'opcao_d' => 'Jeremias',
                'resposta_correta' => 'a',
                'nivel' => 'dificil',
                'categoria' => 'apostolos',
                'explicacao' => 'Paulo perseguia os cristãos antes de sua conversão, mas depois foi perseguido por pregar o evangelho.',
                'referencia_biblica' => 'Atos 9:1-2',
                'pontuacao' => 20
            ],
            [
                'pergunta' => 'Resumidamente, o que fala sobre Atos 29?',
                'opcao_a' => 'Não existem Atos 29',
                'opcao_b' => 'Existem Atos 29, porém foi retirado da bíblia a muito tempo',
                'opcao_c' => 'Fala sobre os sofrimentos atuais e como devemos resistir',
                'opcao_d' => 'Não existe Atos 29, mas simbolicamente são os atos na geração atual',
                'resposta_correta' => 'd',
                'nivel' => 'dificil',
                'categoria' => 'novo_testamento',
                'explicacao' => 'Atos 29 não existe na Bíblia, mas simbolicamente representa os atos dos cristãos na geração atual.',
                'referencia_biblica' => 'Atos 28',
                'pontuacao' => 20
            ]
        ];

        foreach ($perguntas as $pergunta) {
            EbdQuizPergunta::create($pergunta);
        }

        $this->command->info('Perguntas do Quiz Bíblico criadas com sucesso!');
    }
} 