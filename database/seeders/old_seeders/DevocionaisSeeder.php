<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Devocional;
use Carbon\Carbon;

class DevocionaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hoje = Carbon::today();
        
        // Criar devocionais para os próximos 30 dias
        for ($i = 0; $i < 30; $i++) {
            $data = $hoje->copy()->addDays($i);
            
            // Devocional do dia
            Devocional::create([
                'titulo' => $this->getTituloDevocional($i),
                'texto' => $this->getTextoDevocional($i),
                'versiculo' => $this->getVersiculoDevocional($i),
                'reflexao' => $this->getReflexaoDevocional($i),
                'data' => $data,
                'tipo' => 'devocional',
                'ativo' => true,
                'ordem' => 0
            ]);
            
            // Versículo do dia
            Devocional::create([
                'titulo' => 'Versículo do Dia',
                'texto' => $this->getTextoVersiculo($i),
                'versiculo' => $this->getReferenciaVersiculo($i),
                'reflexao' => $this->getReflexaoVersiculo($i),
                'data' => $data,
                'tipo' => 'versiculo',
                'ativo' => true,
                'ordem' => 0
            ]);
            
            // Oração do dia
            Devocional::create([
                'titulo' => 'Oração do Dia',
                'texto' => $this->getTextoOracao($i),
                'versiculo' => '',
                'reflexao' => '',
                'data' => $data,
                'tipo' => 'oracao',
                'ativo' => true,
                'ordem' => 0
            ]);
        }
    }
    
    private function getTituloDevocional($index)
    {
        $titulos = [
            'Gratidão',
            'Confiança em Deus',
            'Amor Incondicional',
            'Fé e Perseverança',
            'Serviço ao Próximo',
            'Comunhão',
            'Esperança',
            'Perdão',
            'Humildade',
            'Alegria no Senhor',
            'Paz Interior',
            'Sabedoria Divina',
            'Coragem',
            'Paciência',
            'Generosidade',
            'Fidelidade',
            'Oração',
            'Adoração',
            'Discipulado',
            'Evangelismo',
            'Santidade',
            'Gratidão',
            'Confiança em Deus',
            'Amor Incondicional',
            'Fé e Perseverança',
            'Serviço ao Próximo',
            'Comunhão',
            'Esperança',
            'Perdão',
            'Humildade'
        ];
        
        return $titulos[$index % count($titulos)];
    }
    
    private function getTextoDevocional($index)
    {
        $textos = [
            'Em tudo dai graças, porque esta é a vontade de Deus em Cristo Jesus para convosco.',
            'Confia no Senhor de todo o teu coração e não te estribes no teu próprio entendimento.',
            'Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito.',
            'A fé vem pelo ouvir, e o ouvir pela palavra de Deus.',
            'Assim como o Filho do homem não veio para ser servido, mas para servir.',
            'E consideremo-nos uns aos outros para nos estimularmos ao amor e às boas obras.',
            'A esperança não confunde, porque o amor de Deus foi derramado em nossos corações.',
            'Perdoa-nos as nossas dívidas, assim como nós perdoamos aos nossos devedores.',
            'Humilhai-vos perante o Senhor, e ele vos exaltará.',
            'Alegrai-vos sempre no Senhor; outra vez digo, alegrai-vos.',
            'A paz de Deus, que excede todo o entendimento, guardará os vossos corações.',
            'Se algum de vós tem falta de sabedoria, peça-a a Deus.',
            'Sê forte e corajoso; não temas, nem te espantes, porque o Senhor teu Deus está contigo.',
            'O Senhor é bom para os que esperam por ele, para a alma que o busca.',
            'Dai, e ser-vos-á dado; boa medida, recalcada, sacudida e transbordando.',
            'Sê fiel até à morte, e dar-te-ei a coroa da vida.',
            'Orai sem cessar.',
            'Adorai ao Senhor na beleza da sua santidade.',
            'Ide, pois, e ensinai todas as nações, batizando-as em nome do Pai.',
            'Pregai o evangelho a toda criatura.',
            'Sede santos, porque eu sou santo.',
            'Em tudo dai graças, porque esta é a vontade de Deus em Cristo Jesus para convosco.',
            'Confia no Senhor de todo o teu coração e não te estribes no teu próprio entendimento.',
            'Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito.',
            'A fé vem pelo ouvir, e o ouvir pela palavra de Deus.',
            'Assim como o Filho do homem não veio para ser servido, mas para servir.',
            'E consideremo-nos uns aos outros para nos estimularmos ao amor e às boas obras.',
            'A esperança não confunde, porque o amor de Deus foi derramado em nossos corações.',
            'Perdoa-nos as nossas dívidas, assim como nós perdoamos aos nossos devedores.',
            'Humilhai-vos perante o Senhor, e ele vos exaltará.'
        ];
        
        return $textos[$index % count($textos)];
    }
    
    private function getVersiculoDevocional($index)
    {
        $versiculos = [
            '1 Tessalonicenses 5:18',
            'Provérbios 3:5-6',
            'João 3:16',
            'Romanos 10:17',
            'Mateus 20:28',
            'Hebreus 10:24',
            'Romanos 5:5',
            'Mateus 6:12',
            'Tiago 4:10',
            'Filipenses 4:4',
            'Filipenses 4:7',
            'Tiago 1:5',
            'Josué 1:9',
            'Lamentações 3:25',
            'Lucas 6:38',
            'Apocalipse 2:10',
            '1 Tessalonicenses 5:17',
            'Salmos 29:2',
            'Mateus 28:19',
            'Marcos 16:15',
            '1 Pedro 1:16',
            '1 Tessalonicenses 5:18',
            'Provérbios 3:5-6',
            'João 3:16',
            'Romanos 10:17',
            'Mateus 20:28',
            'Hebreus 10:24',
            'Romanos 5:5',
            'Mateus 6:12',
            'Tiago 4:10'
        ];
        
        return $versiculos[$index % count($versiculos)];
    }
    
    private function getReflexaoDevocional($index)
    {
        $reflexoes = [
            'A gratidão transforma nossa perspectiva e nos ajuda a ver as bênçãos mesmo nas dificuldades.',
            'Quando confiamos em Deus, Ele nos guia pelos caminhos certos, mesmo quando não entendemos.',
            'O amor de Deus é tão grande que Ele deu o que tinha de mais precioso por nós.',
            'A fé cresce quando ouvimos e meditamos na palavra de Deus.',
            'Jesus nos ensinou que o verdadeiro líder é aquele que serve aos outros.',
            'A comunhão com outros cristãos nos fortalece e nos encoraja.',
            'A esperança em Cristo nos sustenta mesmo nos momentos mais difíceis.',
            'O perdão liberta tanto quem perdoa quanto quem é perdoado.',
            'A humildade nos aproxima de Deus e nos torna mais semelhantes a Cristo.',
            'A alegria no Senhor é nossa força, independente das circunstâncias.',
            'A paz de Deus é um presente que supera toda compreensão humana.',
            'Deus nos dá sabedoria quando a pedimos com fé.',
            'Com Deus ao nosso lado, podemos enfrentar qualquer desafio.',
            'Deus recompensa aqueles que esperam nEle com paciência.',
            'Quando damos generosamente, recebemos ainda mais de volta.',
            'A fidelidade a Deus é recompensada com a vida eterna.',
            'A oração é nossa linha direta de comunicação com Deus.',
            'Adorar a Deus é reconhecer Sua grandeza e majestade.',
            'Fazer discípulos é nossa missão como seguidores de Cristo.',
            'Compartilhar o evangelho é nossa responsabilidade como cristãos.',
            'A santidade é o padrão de Deus para nossas vidas.',
            'A gratidão transforma nossa perspectiva e nos ajuda a ver as bênçãos mesmo nas dificuldades.',
            'Quando confiamos em Deus, Ele nos guia pelos caminhos certos, mesmo quando não entendemos.',
            'O amor de Deus é tão grande que Ele deu o que tinha de mais precioso por nós.',
            'A fé cresce quando ouvimos e meditamos na palavra de Deus.',
            'Jesus nos ensinou que o verdadeiro líder é aquele que serve aos outros.',
            'A comunhão com outros cristãos nos fortalece e nos encoraja.',
            'A esperança em Cristo nos sustenta mesmo nos momentos mais difíceis.',
            'O perdão liberta tanto quem perdoa quanto quem é perdoado.',
            'A humildade nos aproxima de Deus e nos torna mais semelhantes a Cristo.'
        ];
        
        return $reflexoes[$index % count($reflexoes)];
    }
    
    private function getTextoVersiculo($index)
    {
        $textos = [
            'Porque para Deus nada é impossível.',
            'Tudo posso naquele que me fortalece.',
            'O Senhor é o meu pastor, nada me faltará.',
            'Buscai primeiro o reino de Deus e a sua justiça.',
            'Deus é o nosso refúgio e fortaleza, socorro bem presente na angústia.',
            'Em tudo dai graças, porque esta é a vontade de Deus.',
            'Confia no Senhor de todo o teu coração.',
            'Porque Deus amou o mundo de tal maneira.',
            'A fé vem pelo ouvir, e o ouvir pela palavra de Deus.',
            'Alegrai-vos sempre no Senhor.',
            'A paz de Deus, que excede todo o entendimento.',
            'Se algum de vós tem falta de sabedoria, peça-a a Deus.',
            'Sê forte e corajoso; não temas, nem te espantes.',
            'O Senhor é bom para os que esperam por ele.',
            'Dai, e ser-vos-á dado; boa medida, recalcada.',
            'Sê fiel até à morte, e dar-te-ei a coroa da vida.',
            'Orai sem cessar.',
            'Adorai ao Senhor na beleza da sua santidade.',
            'Ide, pois, e ensinai todas as nações.',
            'Pregai o evangelho a toda criatura.',
            'Sede santos, porque eu sou santo.',
            'Porque para Deus nada é impossível.',
            'Tudo posso naquele que me fortalece.',
            'O Senhor é o meu pastor, nada me faltará.',
            'Buscai primeiro o reino de Deus e a sua justiça.',
            'Deus é o nosso refúgio e fortaleza, socorro bem presente na angústia.',
            'Em tudo dai graças, porque esta é a vontade de Deus.',
            'Confia no Senhor de todo o teu coração.',
            'Porque Deus amou o mundo de tal maneira.',
            'A fé vem pelo ouvir, e o ouvir pela palavra de Deus.'
        ];
        
        return $textos[$index % count($textos)];
    }
    
    private function getReferenciaVersiculo($index)
    {
        $referencias = [
            'Lucas 1:37',
            'Filipenses 4:13',
            'Salmos 23:1',
            'Mateus 6:33',
            'Salmos 46:1',
            '1 Tessalonicenses 5:18',
            'Provérbios 3:5',
            'João 3:16',
            'Romanos 10:17',
            'Filipenses 4:4',
            'Filipenses 4:7',
            'Tiago 1:5',
            'Josué 1:9',
            'Lamentações 3:25',
            'Lucas 6:38',
            'Apocalipse 2:10',
            '1 Tessalonicenses 5:17',
            'Salmos 29:2',
            'Mateus 28:19',
            'Marcos 16:15',
            '1 Pedro 1:16',
            'Lucas 1:37',
            'Filipenses 4:13',
            'Salmos 23:1',
            'Mateus 6:33',
            'Salmos 46:1',
            '1 Tessalonicenses 5:18',
            'Provérbios 3:5',
            'João 3:16',
            'Romanos 10:17'
        ];
        
        return $referencias[$index % count($referencias)];
    }
    
    private function getReflexaoVersiculo($index)
    {
        $reflexoes = [
            'Deus tem poder para fazer o impossível acontecer em nossas vidas.',
            'Com Cristo, somos capazes de superar qualquer desafio.',
            'Deus cuida de nós como um pastor cuida de suas ovelhas.',
            'Quando priorizamos Deus, Ele cuida de todas as outras necessidades.',
            'Deus está sempre presente para nos ajudar em momentos de dificuldade.',
            'A gratidão é a chave para uma vida abençoada.',
            'Confiar em Deus é a base de uma vida de fé.',
            'O amor de Deus é a maior demonstração de Sua graça.',
            'A palavra de Deus é o alimento da nossa fé.',
            'A alegria no Senhor é nossa força constante.',
            'A paz de Deus é um presente que guarda nossos corações.',
            'Deus nos dá sabedoria quando a pedimos com fé.',
            'Com Deus ao nosso lado, não precisamos temer.',
            'Deus recompensa aqueles que esperam nEle.',
            'A generosidade é recompensada por Deus.',
            'A fidelidade a Deus é recompensada com a vida eterna.',
            'A oração é nossa comunicação vital com Deus.',
            'Adorar a Deus é reconhecer Sua majestade.',
            'Fazer discípulos é nossa missão como cristãos.',
            'Compartilhar o evangelho é nossa responsabilidade.',
            'A santidade é o padrão de Deus para nós.',
            'Deus tem poder para fazer o impossível acontecer em nossas vidas.',
            'Com Cristo, somos capazes de superar qualquer desafio.',
            'Deus cuida de nós como um pastor cuida de suas ovelhas.',
            'Quando priorizamos Deus, Ele cuida de todas as outras necessidades.',
            'Deus está sempre presente para nos ajudar em momentos de dificuldade.',
            'A gratidão é a chave para uma vida abençoada.',
            'Confiar em Deus é a base de uma vida de fé.',
            'O amor de Deus é a maior demonstração de Sua graça.',
            'A palavra de Deus é o alimento da nossa fé.'
        ];
        
        return $reflexoes[$index % count($reflexoes)];
    }
    
    private function getTextoOracao($index)
    {
        $oracoes = [
            'Senhor, hoje quero agradecer por mais um dia de vida. Ajuda-me a ser uma bênção para outras pessoas e a glorificar o Teu nome em tudo o que fizer.',
            'Pai celestial, guia os meus passos hoje. Que eu possa refletir o Teu amor e ser um instrumento nas Tuas mãos.',
            'Deus de amor, fortalece a minha fé e ajuda-me a confiar em Ti em todas as circunstâncias da vida.',
            'Senhor Jesus, que eu possa ser luz neste mundo e levar esperança a quem precisa.',
            'Pai, ensina-me a ser grato por todas as bênçãos que recebo diariamente e a compartilhar com os outros.',
            'Senhor, dá-me sabedoria para tomar as decisões certas hoje e que eu possa honrar-Te em tudo.',
            'Pai celestial, ajuda-me a ser paciente e a confiar no Teu tempo para todas as coisas.',
            'Deus de misericórdia, ensina-me a perdoar como Tu me perdoas e a amar como Tu me amas.',
            'Senhor, que eu possa ser humilde como Jesus foi e sempre reconhecer que tudo vem de Ti.',
            'Pai, enche o meu coração de alegria e gratidão, mesmo nas dificuldades.',
            'Deus de paz, guarda o meu coração e mente em Cristo Jesus e dá-me a Tua paz que excede todo entendimento.',
            'Senhor, dá-me sabedoria para discernir o que é certo e a coragem para seguir o Teu caminho.',
            'Pai celestial, fortalece-me para enfrentar os desafios de hoje com coragem e fé.',
            'Deus de esperança, ajuda-me a esperar em Ti e a confiar que Tu tens o melhor para mim.',
            'Senhor, ensina-me a ser generoso como Tu és e a compartilhar o que tenho com os outros.',
            'Pai, ajuda-me a ser fiel a Ti em todas as áreas da minha vida.',
            'Deus de graça, ensina-me a orar sem cessar e a manter uma comunicação constante contigo.',
            'Senhor, que eu possa adorar-Te em espírito e em verdade, reconhecendo a Tua grandeza.',
            'Pai celestial, usa-me para fazer discípulos e compartilhar o Teu amor com outros.',
            'Deus de missão, dá-me coragem para pregar o evangelho e ser testemunha de Cristo.',
            'Senhor, santifica-me e ajuda-me a viver uma vida santa como Tu és santo.',
            'Senhor, hoje quero agradecer por mais um dia de vida. Ajuda-me a ser uma bênção para outras pessoas.',
            'Pai celestial, guia os meus passos hoje. Que eu possa refletir o Teu amor.',
            'Deus de amor, fortalece a minha fé e ajuda-me a confiar em Ti.',
            'Senhor Jesus, que eu possa ser luz neste mundo e levar esperança.',
            'Pai, ensina-me a ser grato por todas as bênçãos que recebo.',
            'Senhor, dá-me sabedoria para tomar as decisões certas hoje.',
            'Pai celestial, ajuda-me a ser paciente e a confiar no Teu tempo.',
            'Deus de misericórdia, ensina-me a perdoar como Tu me perdoas.',
            'Senhor, que eu possa ser humilde como Jesus foi.'
        ];
        
        return $oracoes[$index % count($oracoes)];
    }
}
