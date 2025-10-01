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
        $this->command->info('🏠 Configurando página inicial...');

        $configuracoes = [
            // Informações da Igreja
            ['chave' => 'igreja_nome', 'valor' => 'Congregação Batista Avenida', 'tipo' => 'string', 'descricao' => 'Nome da igreja'],
            ['chave' => 'igreja_slogan', 'valor' => 'Uma igreja comprometida com o amor de Cristo', 'tipo' => 'string', 'descricao' => 'Slogan da igreja'],
            ['chave' => 'igreja_endereco', 'valor' => 'Rua da Avenida, 123 - Centro, São Paulo - SP', 'tipo' => 'string', 'descricao' => 'Endereço da igreja'],
            ['chave' => 'igreja_telefone', 'valor' => '(11) 99999-9999', 'tipo' => 'string', 'descricao' => 'Telefone da igreja'],
            ['chave' => 'igreja_email', 'valor' => 'contato@cbav.com', 'tipo' => 'string', 'descricao' => 'Email da igreja'],
            ['chave' => 'igreja_website', 'valor' => 'https://cbav.com', 'tipo' => 'string', 'descricao' => 'Website da igreja'],
            ['chave' => 'igreja_facebook', 'valor' => 'cbav.igreja', 'tipo' => 'string', 'descricao' => 'Facebook da igreja'],
            ['chave' => 'igreja_instagram', 'valor' => '@cbav_igreja', 'tipo' => 'string', 'descricao' => 'Instagram da igreja'],
            ['chave' => 'igreja_youtube', 'valor' => 'CBAV Igreja', 'tipo' => 'string', 'descricao' => 'YouTube da igreja'],
            ['chave' => 'igreja_whatsapp', 'valor' => '(11) 99999-9999', 'tipo' => 'string', 'descricao' => 'WhatsApp da igreja'],
            
            // Seções da página
            ['chave' => 'secao_sobre_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção sobre ativa'],
            ['chave' => 'secao_ministerios_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção ministérios ativa'],
            ['chave' => 'secao_cultos_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção cultos ativa'],
            ['chave' => 'secao_aniversariantes_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção aniversariantes ativa'],
            ['chave' => 'secao_doacao_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção doação ativa'],
            ['chave' => 'secao_contato_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Seção contato ativa'],
            
            // Títulos e subtítulos
            ['chave' => 'sobre_titulo', 'valor' => 'Sobre Nossa Igreja', 'tipo' => 'string', 'descricao' => 'Título da seção sobre'],
            ['chave' => 'cultos_titulo', 'valor' => 'Horários de Cultos', 'tipo' => 'string', 'descricao' => 'Título da seção cultos'],
            ['chave' => 'cultos_subtitulo', 'valor' => 'Venha adorar conosco e crescer na fé através da Palavra de Deus', 'tipo' => 'string', 'descricao' => 'Subtítulo da seção cultos'],
            ['chave' => 'doacao_titulo', 'valor' => 'Faça uma Doação', 'tipo' => 'string', 'descricao' => 'Título da seção doação'],
            ['chave' => 'doacao_subtitulo', 'valor' => 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!', 'tipo' => 'string', 'descricao' => 'Subtítulo da seção doação'],
            ['chave' => 'contato_titulo', 'valor' => 'Entre em Contato', 'tipo' => 'string', 'descricao' => 'Título da seção contato'],
            ['chave' => 'contato_subtitulo', 'valor' => 'Estamos aqui para você. Entre em contato conosco!', 'tipo' => 'string', 'descricao' => 'Subtítulo da seção contato'],
            
            // Princípios Batistas
            ['chave' => 'principios_batistas_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Princípios batistas ativos'],
            ['chave' => 'principios_batistas_titulo', 'valor' => 'Nossos Princípios Batistas', 'tipo' => 'string', 'descricao' => 'Título dos princípios batistas'],
            ['chave' => 'principio_1_titulo', 'valor' => 'Sola Scriptura', 'tipo' => 'string', 'descricao' => 'Título do primeiro princípio'],
            ['chave' => 'principio_1_descricao', 'valor' => 'A Bíblia como única regra de fé e prática', 'tipo' => 'string', 'descricao' => 'Descrição do primeiro princípio'],
            ['chave' => 'principio_2_titulo', 'valor' => 'Sacerdócio Universal', 'tipo' => 'string', 'descricao' => 'Título do segundo princípio'],
            ['chave' => 'principio_2_descricao', 'valor' => 'Todo crente tem acesso direto a Deus', 'tipo' => 'string', 'descricao' => 'Descrição do segundo princípio'],
            ['chave' => 'principio_3_titulo', 'valor' => 'Batismo por Imersão', 'tipo' => 'string', 'descricao' => 'Título do terceiro princípio'],
            ['chave' => 'principio_3_descricao', 'valor' => 'Para crentes professos, por imersão', 'tipo' => 'string', 'descricao' => 'Descrição do terceiro princípio'],
            ['chave' => 'principio_4_titulo', 'valor' => 'Autonomia Local', 'tipo' => 'string', 'descricao' => 'Título do quarto princípio'],
            ['chave' => 'principio_4_descricao', 'valor' => 'Cada igreja é independente e autônoma', 'tipo' => 'string', 'descricao' => 'Descrição do quarto princípio'],
            
            // Horários de Cultos
            ['chave' => 'culto_domingo_manha_titulo', 'valor' => 'Culto de Domingo - Manhã', 'tipo' => 'string', 'descricao' => 'Título do culto domingo manhã'],
            ['chave' => 'culto_domingo_manha_horario', 'valor' => '09:00h', 'tipo' => 'string', 'descricao' => 'Horário do culto domingo manhã'],
            ['chave' => 'culto_domingo_manha_descricao', 'valor' => 'Culto de adoração e pregação da Palavra de Deus', 'tipo' => 'string', 'descricao' => 'Descrição do culto domingo manhã'],
            ['chave' => 'culto_domingo_manha_item1', 'valor' => 'Louvor e Adoração', 'tipo' => 'string', 'descricao' => 'Item 1 do culto domingo manhã'],
            ['chave' => 'culto_domingo_manha_item2', 'valor' => 'Pregação da Palavra', 'tipo' => 'string', 'descricao' => 'Item 2 do culto domingo manhã'],
            ['chave' => 'culto_domingo_manha_item3', 'valor' => 'Oração e Intercessão', 'tipo' => 'string', 'descricao' => 'Item 3 do culto domingo manhã'],
            
            ['chave' => 'culto_domingo_noite_titulo', 'valor' => 'Culto de Domingo - Noite', 'tipo' => 'string', 'descricao' => 'Título do culto domingo noite'],
            ['chave' => 'culto_domingo_noite_horario', 'valor' => '18:00h', 'tipo' => 'string', 'descricao' => 'Horário do culto domingo noite'],
            ['chave' => 'culto_domingo_noite_descricao', 'valor' => 'Culto de celebração e edificação espiritual', 'tipo' => 'string', 'descricao' => 'Descrição do culto domingo noite'],
            ['chave' => 'culto_domingo_noite_item1', 'valor' => 'Louvor e Adoração', 'tipo' => 'string', 'descricao' => 'Item 1 do culto domingo noite'],
            ['chave' => 'culto_domingo_noite_item2', 'valor' => 'Pregação da Palavra', 'tipo' => 'string', 'descricao' => 'Item 2 do culto domingo noite'],
            ['chave' => 'culto_domingo_noite_item3', 'valor' => 'Oração e Intercessão', 'tipo' => 'string', 'descricao' => 'Item 3 do culto domingo noite'],
            
            ['chave' => 'culto_quarta_titulo', 'valor' => 'Culto de Quarta-feira', 'tipo' => 'string', 'descricao' => 'Título do culto quarta'],
            ['chave' => 'culto_quarta_horario', 'valor' => '19:30h', 'tipo' => 'string', 'descricao' => 'Horário do culto quarta'],
            ['chave' => 'culto_quarta_descricao', 'valor' => 'Culto de oração e estudo bíblico', 'tipo' => 'string', 'descricao' => 'Descrição do culto quarta'],
            ['chave' => 'culto_quarta_item1', 'valor' => 'Oração e Intercessão', 'tipo' => 'string', 'descricao' => 'Item 1 do culto quarta'],
            ['chave' => 'culto_quarta_item2', 'valor' => 'Estudo Bíblico', 'tipo' => 'string', 'descricao' => 'Item 2 do culto quarta'],
            ['chave' => 'culto_quarta_item3', 'valor' => 'Comunhão', 'tipo' => 'string', 'descricao' => 'Item 3 do culto quarta'],
            
            // Escola Dominical
            ['chave' => 'escola_dominical_ativa', 'valor' => '1', 'tipo' => 'boolean', 'descricao' => 'Escola dominical ativa'],
            ['chave' => 'escola_dominical_titulo', 'valor' => 'Escola Dominical', 'tipo' => 'string', 'descricao' => 'Título da escola dominical'],
            ['chave' => 'escola_dominical_horario', 'valor' => 'Domingo às 08:00h', 'tipo' => 'string', 'descricao' => 'Horário da escola dominical'],
            ['chave' => 'escola_dominical_descricao', 'valor' => 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.', 'tipo' => 'string', 'descricao' => 'Descrição da escola dominical'],
            ['chave' => 'escola_dominical_classe1', 'valor' => 'Classes Infantis', 'tipo' => 'string', 'descricao' => 'Classe 1 da escola dominical'],
            ['chave' => 'escola_dominical_classe2', 'valor' => 'Classes de Jovens', 'tipo' => 'string', 'descricao' => 'Classe 2 da escola dominical'],
            ['chave' => 'escola_dominical_classe3', 'valor' => 'Classes de Adultos', 'tipo' => 'string', 'descricao' => 'Classe 3 da escola dominical'],
            
            // Doação
            ['chave' => 'doacao_dizimo_titulo', 'valor' => 'Dízimo', 'tipo' => 'string', 'descricao' => 'Título do dízimo'],
            ['chave' => 'doacao_dizimo_descricao', 'valor' => 'Contribua com o dízimo para a manutenção da igreja.', 'tipo' => 'string', 'descricao' => 'Descrição do dízimo'],
            ['chave' => 'doacao_dizimo_botao', 'valor' => 'Doar Dízimo', 'tipo' => 'string', 'descricao' => 'Botão do dízimo'],
            ['chave' => 'doacao_oferta_titulo', 'valor' => 'Oferta', 'tipo' => 'string', 'descricao' => 'Título da oferta'],
            ['chave' => 'doacao_oferta_descricao', 'valor' => 'Ofereça com amor para as necessidades da igreja.', 'tipo' => 'string', 'descricao' => 'Descrição da oferta'],
            ['chave' => 'doacao_oferta_botao', 'valor' => 'Fazer Oferta', 'tipo' => 'string', 'descricao' => 'Botão da oferta'],
            ['chave' => 'doacao_campanhas_titulo', 'valor' => 'Campanhas', 'tipo' => 'string', 'descricao' => 'Título das campanhas'],
            ['chave' => 'doacao_campanhas_descricao', 'valor' => 'Participe de nossas campanhas especiais.', 'tipo' => 'string', 'descricao' => 'Descrição das campanhas'],
            ['chave' => 'doacao_campanhas_botao', 'valor' => 'Ver Campanhas', 'tipo' => 'string', 'descricao' => 'Botão das campanhas'],
            
            // Footer
            ['chave' => 'footer_links_titulo', 'valor' => 'Links Rápidos', 'tipo' => 'string', 'descricao' => 'Título dos links rápidos'],
            ['chave' => 'footer_link_sobre', 'valor' => 'Sobre', 'tipo' => 'string', 'descricao' => 'Link sobre'],
            ['chave' => 'footer_link_ministerios', 'valor' => 'Ministérios', 'tipo' => 'string', 'descricao' => 'Link ministérios'],
            ['chave' => 'footer_link_cultos', 'valor' => 'Cultos', 'tipo' => 'string', 'descricao' => 'Link cultos'],
            ['chave' => 'footer_link_aniversariantes', 'valor' => 'Aniversariantes', 'tipo' => 'string', 'descricao' => 'Link aniversariantes'],
            ['chave' => 'footer_link_doacao', 'valor' => 'Doação', 'tipo' => 'string', 'descricao' => 'Link doação'],
            
            // Cores
            ['chave' => 'cor_primaria', 'valor' => '#1e40af', 'tipo' => 'string', 'descricao' => 'Cor primária'],
            ['chave' => 'cor_secundaria', 'valor' => '#3b82f6', 'tipo' => 'string', 'descricao' => 'Cor secundária'],
            ['chave' => 'cor_destaque', 'valor' => '#10b981', 'tipo' => 'string', 'descricao' => 'Cor de destaque'],
            ['chave' => 'cor_texto', 'valor' => '#1f2937', 'tipo' => 'string', 'descricao' => 'Cor do texto'],
            ['chave' => 'cor_fundo', 'valor' => '#f9fafb', 'tipo' => 'string', 'descricao' => 'Cor de fundo'],
            ['chave' => 'cor_card', 'valor' => '#ffffff', 'tipo' => 'string', 'descricao' => 'Cor do card'],
            ['chave' => 'cor_borda', 'valor' => '#e5e7eb', 'tipo' => 'string', 'descricao' => 'Cor da borda'],
            
            // Hero Section
            ['chave' => 'hero_titulo', 'valor' => 'Bem-vindo à Nossa Igreja', 'tipo' => 'string', 'descricao' => 'Título do hero'],
            ['chave' => 'hero_subtitulo', 'valor' => 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!', 'tipo' => 'string', 'descricao' => 'Subtítulo do hero'],
            ['chave' => 'home_descricao', 'valor' => 'Uma igreja comprometida com o amor de Cristo e o serviço ao próximo.', 'tipo' => 'string', 'descricao' => 'Descrição da home'],
            ['chave' => 'home_botao_texto', 'valor' => 'Conheça Nossa Igreja', 'tipo' => 'string', 'descricao' => 'Texto do botão da home'],
            ['chave' => 'home_botao_link', 'valor' => '#sobre', 'tipo' => 'string', 'descricao' => 'Link do botão da home'],
            
            // SEO
            ['chave' => 'meta_title', 'valor' => 'Congregação Batista Avenida - Uma igreja comprometida com o amor de Cristo', 'tipo' => 'string', 'descricao' => 'Meta title'],
            ['chave' => 'meta_description', 'valor' => 'Venha fazer parte da nossa família! Uma comunidade de fé, amor e esperança.', 'tipo' => 'string', 'descricao' => 'Meta description'],
            ['chave' => 'meta_keywords', 'valor' => 'igreja, batista, avenida, culto, comunidade, fé, amor', 'tipo' => 'string', 'descricao' => 'Meta keywords'],
        ];

        foreach ($configuracoes as $config) {
            Configuracao::updateOrCreate(
                ['chave' => $config['chave']],
                $config
            );
        }

        $this->command->info('✅ Configurações da página inicial definidas');
    }
} 