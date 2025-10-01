<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Devocional;

class DevocionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📖 Criando devocionais demonstrativos...');

        $devocionais = [
            [
                'titulo' => 'A Fé que Move Montanhas',
                'texto' => 'Jesus disse aos seus discípulos: "Porque a verdade vos digo que, se tivésseis fé como um grão de mostarda, diríeis a este monte: Passa daqui para acolá, e ele passaria. Nada vos seria impossível."',
                'versiculo' => 'Mateus 17:20',
                'reflexao' => 'A fé é um dom precioso que Deus nos deu. Não é o tamanho da fé que importa, mas sim a qualidade dela. Uma fé genuína, mesmo que pequena como um grão de mostarda, pode realizar coisas extraordinárias. Hoje, reflita sobre sua fé. Você tem confiado completamente em Deus? Ou tem deixado a dúvida e o medo tomarem conta do seu coração? Que possamos cultivar uma fé que não apenas acredita em Deus, mas que confia plenamente em Sua palavra e em Seu poder.',
                'data' => now()->subDays(5)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 1
            ],
            [
                'titulo' => 'O Amor que Transforma',
                'texto' => 'Aquele que não ama não conhece a Deus; porque Deus é amor.',
                'versiculo' => '1 João 4:8',
                'reflexao' => 'O amor é a essência do caráter de Deus. Tudo o que Ele faz é motivado pelo amor. Como filhos de Deus, somos chamados a refletir esse amor em nossas vidas. O amor de Deus não é apenas um sentimento, mas uma escolha. É decidir colocar o bem do outro acima dos nossos próprios interesses. É perdoar quando somos feridos, servir quando somos servidos, e dar quando não temos muito. Que possamos hoje refletir o amor de Deus em todas as nossas ações e relacionamentos.',
                'data' => now()->subDays(4)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 2
            ],
            [
                'titulo' => 'A Gratidão no Coração',
                'texto' => 'Em tudo dai graças, porque esta é a vontade de Deus em Cristo Jesus para convosco.',
                'versiculo' => '1 Tessalonicenses 5:18',
                'reflexao' => 'A gratidão é uma atitude que transforma nossa perspectiva da vida. Quando somos gratos, mesmo nas dificuldades, descobrimos que Deus está trabalhando em nosso favor. A gratidão nos ajuda a focar nas bênçãos ao invés dos problemas, na provisão ao invés da escassez, na esperança ao invés do desespero. Que possamos cultivar um coração grato, reconhecendo que tudo o que temos vem de Deus e que Ele é fiel em todas as circunstâncias.',
                'data' => now()->subDays(3)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 3
            ],
            [
                'titulo' => 'A Força na Fraqueza',
                'texto' => 'E disse-me: A minha graça te basta, porque o meu poder se aperfeiçoa na fraqueza.',
                'versiculo' => '2 Coríntios 12:9',
                'reflexao' => 'Deus não escolhe os capacitados, mas capacita os escolhidos. Nossas fraquezas não são obstáculos para Deus, mas oportunidades para Ele demonstrar Seu poder. Quando nos sentimos inadequados ou incapazes, é exatamente nesses momentos que Deus quer trabalhar através de nós. Sua graça é suficiente para suprir todas as nossas necessidades. Que possamos confiar que Deus pode usar nossas fraquezas para Sua glória.',
                'data' => now()->subDays(2)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 4
            ],
            [
                'titulo' => 'A Paz que Excede Todo Entendimento',
                'texto' => 'E a paz de Deus, que excede todo o entendimento, guardará os vossos corações e os vossos sentimentos em Cristo Jesus.',
                'versiculo' => 'Filipenses 4:7',
                'reflexao' => 'A paz de Deus é diferente da paz do mundo. Ela não depende das circunstâncias, mas da presença de Deus em nossas vidas. Quando entregamos nossas ansiedades a Deus em oração, Ele nos dá uma paz que não conseguimos explicar. É uma paz que guarda nossos corações e mentes. Que possamos buscar essa paz através da oração e da confiança em Deus.',
                'data' => now()->subDays(1)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 5
            ],
            [
                'titulo' => 'O Fruto do Espírito',
                'texto' => 'Mas o fruto do Espírito é: amor, gozo, paz, longanimidade, benignidade, bondade, fé, mansidão, temperança.',
                'versiculo' => 'Gálatas 5:22-23',
                'reflexao' => 'O fruto do Espírito é o resultado natural de uma vida cheia do Espírito Santo. Não são características que desenvolvemos por esforço próprio, mas que brotam naturalmente quando permitimos que o Espírito Santo trabalhe em nós. Cada fruto representa uma qualidade do caráter de Cristo que deve ser manifestada em nossas vidas. Que possamos permitir que o Espírito Santo produza esses frutos em nossas vidas.',
                'data' => now()->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 6
            ],
            [
                'titulo' => 'A Esperança que Não Falha',
                'texto' => 'E a esperança não traz confusão, porquanto o amor de Deus está derramado em nossos corações pelo Espírito Santo que nos foi dado.',
                'versiculo' => 'Romanos 5:5',
                'reflexao' => 'A esperança cristã não é um desejo vago, mas uma certeza baseada no amor de Deus e nas promessas de Sua palavra. Mesmo quando as circunstâncias parecem desfavoráveis, podemos ter esperança porque sabemos que Deus está no controle e que Ele trabalha todas as coisas para o bem daqueles que O amam. Que possamos manter nossa esperança viva, confiando no amor e na fidelidade de Deus.',
                'data' => now()->subDays(6)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 7
            ],
            [
                'titulo' => 'A Humildade de Cristo',
                'texto' => 'De sorte que haja em vós o mesmo sentimento que houve também em Cristo Jesus, que, sendo em forma de Deus, não teve por usurpação ser igual a Deus, mas esvaziou-se a si mesmo, tomando a forma de servo.',
                'versiculo' => 'Filipenses 2:5-8',
                'reflexao' => 'Cristo é nosso exemplo supremo de humildade. Ele, sendo Deus, escolheu se tornar servo e dar Sua vida por nós. A humildade não é fraqueza, mas força. É reconhecer que tudo o que temos vem de Deus e usar nossos dons para servir aos outros. Que possamos seguir o exemplo de Cristo, servindo uns aos outros com humildade.',
                'data' => now()->subDays(7)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 8
            ],
            [
                'titulo' => 'A Perseverança na Fé',
                'texto' => 'Bem-aventurado o homem que suporta a tentação; porque, quando for provado, receberá a coroa da vida, a qual o Senhor tem prometido aos que o amam.',
                'versiculo' => 'Tiago 1:12',
                'reflexao' => 'A perseverança é essencial na vida cristã. As provações não são castigos, mas oportunidades para crescer na fé e desenvolver o caráter de Cristo. Deus promete uma coroa de vida para aqueles que perseveram. Essa promessa nos motiva a continuar firmes, mesmo quando as circunstâncias são difíceis. Que possamos perseverar na fé, confiando que Deus está trabalhando em nós através das provações.',
                'data' => now()->subDays(8)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 9
            ],
            [
                'titulo' => 'A Alegria do Senhor',
                'texto' => 'A alegria do Senhor é a vossa força.',
                'versiculo' => 'Neemias 8:10',
                'reflexao' => 'A alegria do Senhor não depende das circunstâncias, mas da presença de Deus em nossas vidas. É uma alegria que vem de saber que somos amados e aceitos por Deus. Essa alegria nos fortalece para enfrentar os desafios da vida. Ela nos lembra que, independentemente das circunstâncias, Deus está conosco e nos ama. Que possamos buscar a alegria do Senhor, que é nossa força e nossa fortaleza.',
                'data' => now()->subDays(9)->toDateString(),
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 10
            ]
        ];

        foreach ($devocionais as $devocional) {
            Devocional::updateOrCreate(
                ['titulo' => $devocional['titulo'], 'data' => $devocional['data']],
                $devocional
            );
        }

        $this->command->info('✅ Devocionais demonstrativos criados com sucesso');
        $this->command->info('📊 Total de devocionais: ' . count($devocionais));
        
        // Estatísticas
        $ativos = collect($devocionais)->where('ativo', true)->count();
        $devocionais_tipo = collect($devocionais)->where('tipo', 'devocional')->count();
        
        $this->command->info("📖 Ativos: {$ativos}");
        $this->command->info("📖 Tipo devocional: {$devocionais_tipo}");
    }
} 