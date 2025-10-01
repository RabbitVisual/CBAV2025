<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;

class HomeConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurações básicas da igreja
        $configuracoes = [
            // Informações da Igreja
            'igreja_nome' => 'Congregação Batista Avenida',
            'igreja_slogan' => 'Uma igreja comprometida com o amor de Cristo',
            'igreja_endereco' => 'Rua da Avenida, 123 - Centro',
            'igreja_telefone' => '(11) 1234-5678',
            'igreja_email' => 'contato@cbav.com',
            'igreja_website' => 'https://cbav.com',
            'igreja_facebook' => 'cbav.igreja',
            'igreja_instagram' => '@cbav_igreja',
            'igreja_youtube' => 'CBAV Igreja',
            
            // Seções da página
            'secao_sobre_ativa' => '1',
            'secao_ministerios_ativa' => '1',
            'secao_cultos_ativa' => '1',
            'secao_aniversariantes_ativa' => '1',
            'secao_doacao_ativa' => '1',
            'secao_contato_ativa' => '1',
            
            // Títulos e subtítulos
            'sobre_titulo' => 'Sobre Nossa Igreja',
            'cultos_titulo' => 'Horários de Cultos',
            'cultos_subtitulo' => 'Venha adorar conosco e crescer na fé através da Palavra de Deus',
            'doacao_titulo' => 'Faça uma Doação',
            'doacao_subtitulo' => 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!',
            'contato_titulo' => 'Entre em Contato',
            'contato_subtitulo' => 'Estamos aqui para você. Entre em contato conosco!',
            
            // Princípios Batistas
            'principios_batistas_ativa' => '1',
            'principios_batistas_titulo' => 'Nossos Princípios Batistas',
            'principio_1_titulo' => 'Sola Scriptura',
            'principio_1_descricao' => 'A Bíblia como única regra de fé e prática',
            'principio_2_titulo' => 'Sacerdócio Universal',
            'principio_2_descricao' => 'Todo crente tem acesso direto a Deus',
            'principio_3_titulo' => 'Batismo por Imersão',
            'principio_3_descricao' => 'Para crentes professos, por imersão',
            'principio_4_titulo' => 'Autonomia Local',
            'principio_4_descricao' => 'Cada igreja é independente e autônoma',
            
            // Horários de Cultos
            'culto_domingo_manha_titulo' => 'Culto de Domingo - Manhã',
            'culto_domingo_manha_horario' => '09:00h',
            'culto_domingo_manha_descricao' => 'Culto de adoração e pregação da Palavra de Deus',
            'culto_domingo_manha_item1' => 'Louvor e Adoração',
            'culto_domingo_manha_item2' => 'Pregação da Palavra',
            'culto_domingo_manha_item3' => 'Oração e Intercessão',
            
            'culto_domingo_noite_titulo' => 'Culto de Domingo - Noite',
            'culto_domingo_noite_horario' => '18:00h',
            'culto_domingo_noite_descricao' => 'Culto de celebração e edificação espiritual',
            'culto_domingo_noite_item1' => 'Louvor e Adoração',
            'culto_domingo_noite_item2' => 'Pregação da Palavra',
            'culto_domingo_noite_item3' => 'Oração e Intercessão',
            
            'culto_quarta_titulo' => 'Culto de Quarta-feira',
            'culto_quarta_horario' => '19:30h',
            'culto_quarta_descricao' => 'Culto de oração e estudo bíblico',
            'culto_quarta_item1' => 'Oração e Intercessão',
            'culto_quarta_item2' => 'Estudo Bíblico',
            'culto_quarta_item3' => 'Comunhão',
            
            // Escola Dominical
            'escola_dominical_ativa' => '1',
            'escola_dominical_titulo' => 'Escola Dominical',
            'escola_dominical_horario' => 'Domingo às 08:00h',
            'escola_dominical_descricao' => 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.',
            'escola_dominical_classe1' => 'Classes Infantis',
            'escola_dominical_classe2' => 'Classes de Jovens',
            'escola_dominical_classe3' => 'Classes de Adultos',
            
            // Doação
            'doacao_dizimo_titulo' => 'Dízimo',
            'doacao_dizimo_descricao' => 'Contribua com o dízimo para a manutenção da igreja.',
            'doacao_dizimo_botao' => 'Doar Dízimo',
            'doacao_oferta_titulo' => 'Oferta',
            'doacao_oferta_descricao' => 'Ofereça com amor para as necessidades da igreja.',
            'doacao_oferta_botao' => 'Fazer Oferta',
            'doacao_campanhas_titulo' => 'Campanhas',
            'doacao_campanhas_descricao' => 'Participe de nossas campanhas especiais.',
            'doacao_campanhas_botao' => 'Ver Campanhas',
            
            // Footer
            'footer_links_titulo' => 'Links Rápidos',
            'footer_link_sobre' => 'Sobre',
            'footer_link_ministerios' => 'Ministérios',
            'footer_link_cultos' => 'Cultos',
            'footer_link_aniversariantes' => 'Aniversariantes',
            'footer_link_doacao' => 'Doação',
            'igreja_whatsapp' => '(11) 99999-9999',
            
            // Cores
            'cor_primaria' => '#1e40af',
            'cor_secundaria' => '#3b82f6',
            'cor_destaque' => '#10b981',
            'cor_texto' => '#1f2937',
            'cor_fundo' => '#f9fafb',
            'cor_card' => '#ffffff',
            'cor_borda' => '#e5e7eb',
            
            // Hero Section
            'hero_titulo' => 'Bem-vindo à Nossa Igreja',
            'hero_subtitulo' => 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!',
            'home_descricao' => 'Uma igreja comprometida com o amor de Cristo e o serviço ao próximo.',
            'home_botao_texto' => 'Conheça Nossa Igreja',
            'home_botao_link' => '#sobre',
            
            // Seções
            'secao_sobre_ativa' => '1',
            'secao_sobre_titulo' => 'Sobre Nossa Igreja',
            'secao_sobre_descricao' => 'Conheça nossa história, missão e valores.',
            
            'secao_ministerios_ativa' => '1',
            'secao_ministerios_titulo' => 'Nossos Ministérios',
            'secao_ministerios_descricao' => 'Descubra como você pode servir e crescer.',
            
            'secao_cultos_ativa' => '1',
            'secao_cultos_titulo' => 'Horários de Cultos',
            'secao_cultos_descricao' => 'Venha adorar conosco.',
            
            'secao_aniversariantes_ativa' => '1',
            'secao_aniversariantes_titulo' => 'Aniversariantes do Mês',
            'secao_aniversariantes_descricao' => 'Celebre conosco os aniversariantes.',
            'aniversariantes_mostrar' => '1',
            
            'secao_doacao_ativa' => '1',
            'secao_doacao_titulo' => 'Faça Sua Doação',
            'secao_doacao_descricao' => 'Apoie nossa missão com sua oferta.',
            
            'secao_contato_ativa' => '1',
            'secao_contato_titulo' => 'Entre em Contato',
            'secao_contato_descricao' => 'Estamos aqui para você.',
            
            // Horários
            'culto_domingo' => 'Domingo: 09:00 e 18:00',
            'culto_meio_semana' => 'Quarta: 19:30',
            
            // Doação
            'doacao_ativa' => '1',
            'doacao_sem_login' => '1',
            'doacao_valor_minimo' => '1',
            'doacao_valor_maximo' => '10000',
            'doacao_titulo' => 'Faça uma Doação',
            'doacao_subtitulo' => 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!',
            'doacao_dica_seguranca' => 'Seus dados estão protegidos com criptografia SSL',
            'doacao_dica_comprovante' => 'Receba um comprovante por email após a confirmação',
            'doacao_dica_transparencia' => 'Todas as doações são registradas e auditadas',
            
            // SEO
            'meta_title' => 'Congregação Batista Avenida - Uma igreja comprometida com o amor de Cristo',
            'meta_description' => 'Venha fazer parte da nossa família! Uma comunidade de fé, amor e esperança.',
            'meta_keywords' => 'igreja, batista, avenida, culto, comunidade, fé, amor',
        ];
        
        foreach ($configuracoes as $chave => $valor) {
            Configuracao::set($chave, $valor);
        }
        
        $this->command->info('Configurações da homepage criadas com sucesso!');
    }
} 