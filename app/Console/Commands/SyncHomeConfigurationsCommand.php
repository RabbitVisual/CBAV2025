<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracao;

class SyncHomeConfigurationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'home-config:sync {--force : Force sync all configurations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar todas as configurações de home config para garantir que estejam disponíveis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏠 Iniciando sincronização das configurações de home config...');
        
        $configuracoes = $this->getDefaultConfigurations();
        $count = 0;
        
        foreach ($configuracoes as $chave => $config) {
            $existe = Configuracao::where('chave', $chave)->exists();
            
            if (!$existe || $this->option('force')) {
                Configuracao::set($chave, $config['valor'], $config['tipo'], $config['descricao']);
                $count++;
                
                if (!$existe) {
                    $this->line("✅ Criada: {$chave}");
                } else {
                    $this->line("🔄 Atualizada: {$chave}");
                }
            }
        }
        
        $this->info("🎉 Sincronização concluída! {$count} configurações processadas.");
        
        return 0;
    }
    
    /**
     * Obter configurações padrão
     */
    private function getDefaultConfigurations()
    {
        return [
            // Informações básicas da igreja
            'igreja_nome' => [
                'valor' => 'Congregação Batista Avenida',
                'tipo' => 'string',
                'descricao' => 'Nome da igreja'
            ],
            'igreja_slogan' => [
                'valor' => 'Uma igreja comprometida com o amor de Cristo',
                'tipo' => 'string',
                'descricao' => 'Slogan da igreja'
            ],
            'igreja_endereco' => [
                'valor' => 'Endereço da igreja',
                'tipo' => 'string',
                'descricao' => 'Endereço completo da igreja'
            ],
            'igreja_telefone' => [
                'valor' => '(11) 99999-9999',
                'tipo' => 'string',
                'descricao' => 'Telefone de contato'
            ],
            'igreja_email' => [
                'valor' => 'contato@igreja.com',
                'tipo' => 'string',
                'descricao' => 'Email de contato'
            ],
            'igreja_website' => [
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Website da igreja'
            ],
            'igreja_facebook' => [
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Facebook da igreja'
            ],
            'igreja_instagram' => [
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'Instagram da igreja'
            ],
            'igreja_youtube' => [
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'YouTube da igreja'
            ],
            'igreja_whatsapp' => [
                'valor' => '',
                'tipo' => 'string',
                'descricao' => 'WhatsApp da igreja'
            ],
            
            // Cores
            'cor_primaria' => [
                'valor' => '#1e40af',
                'tipo' => 'string',
                'descricao' => 'Cor primária do site'
            ],
            'cor_secundaria' => [
                'valor' => '#3b82f6',
                'tipo' => 'string',
                'descricao' => 'Cor secundária do site'
            ],
            'cor_destaque' => [
                'valor' => '#10b981',
                'tipo' => 'string',
                'descricao' => 'Cor de destaque'
            ],
            'cor_texto' => [
                'valor' => '#1f2937',
                'tipo' => 'string',
                'descricao' => 'Cor principal do texto'
            ],
            
            // Hero Section
            'hero_titulo' => [
                'valor' => 'Bem-vindo à Nossa Igreja',
                'tipo' => 'string',
                'descricao' => 'Título principal da página inicial'
            ],
            'hero_subtitulo' => [
                'valor' => 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da página inicial'
            ],
            'home_botao_texto' => [
                'valor' => 'Conheça-nos',
                'tipo' => 'string',
                'descricao' => 'Texto do botão principal'
            ],
            'home_botao_link' => [
                'valor' => '#sobre',
                'tipo' => 'string',
                'descricao' => 'Link do botão principal'
            ],
            'home_botao_doacao_texto' => [
                'valor' => 'Faça uma Doação',
                'tipo' => 'string',
                'descricao' => 'Texto do botão de doação'
            ],
            
            // Seções ativas
            'secao_sobre_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Sobre'
            ],
            'secao_ministerios_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Ministérios'
            ],
            'secao_cultos_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Cultos'
            ],
            'secao_contato_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Contato'
            ],
            'secao_doacao_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Doação'
            ],
            'secao_eventos_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Eventos'
            ],
            'secao_aniversariantes_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção Aniversariantes'
            ],
            
            // Ministérios
            'ministerios_titulo' => [
                'valor' => 'Nossos Ministérios',
                'tipo' => 'string',
                'descricao' => 'Título da seção de ministérios'
            ],
            'ministerios_subtitulo' => [
                'valor' => 'Conheça nossos ministérios e participe da obra de Deus.',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da seção de ministérios'
            ],
            
            // Aniversariantes
            'aniversariantes_titulo' => [
                'valor' => 'Aniversariantes do Mês',
                'tipo' => 'string',
                'descricao' => 'Título da seção de aniversariantes'
            ],
            'aniversariantes_subtitulo' => [
                'valor' => 'Celebrando a vida dos nossos membros',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da seção de aniversariantes'
            ],
            'aniversariantes_mostrar' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar aniversariantes na página inicial'
            ],
            
            // Cultos
            'cultos_titulo' => [
                'valor' => 'Horários de Cultos',
                'tipo' => 'string',
                'descricao' => 'Título da seção de horários'
            ],
            'cultos_subtitulo' => [
                'valor' => 'Venha adorar conosco e crescer na fé através da Palavra de Deus',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da seção de horários'
            ],
            
            // Domingo Manhã
            'culto_domingo_manha_titulo' => [
                'valor' => 'Culto de Domingo - Manhã',
                'tipo' => 'string',
                'descricao' => 'Título do culto de domingo manhã'
            ],
            'culto_domingo_manha_horario' => [
                'valor' => '09:00h',
                'tipo' => 'string',
                'descricao' => 'Horário do culto de domingo manhã'
            ],
            'culto_domingo_manha_descricao' => [
                'valor' => 'Culto de adoração e pregação da Palavra de Deus',
                'tipo' => 'string',
                'descricao' => 'Descrição do culto de domingo manhã'
            ],
            'culto_domingo_manha_item1' => [
                'valor' => 'Louvor e Adoração',
                'tipo' => 'string',
                'descricao' => 'Item 1 do culto de domingo manhã'
            ],
            'culto_domingo_manha_item2' => [
                'valor' => 'Pregação da Palavra',
                'tipo' => 'string',
                'descricao' => 'Item 2 do culto de domingo manhã'
            ],
            'culto_domingo_manha_item3' => [
                'valor' => 'Oração e Intercessão',
                'tipo' => 'string',
                'descricao' => 'Item 3 do culto de domingo manhã'
            ],
            
            // Domingo Noite
            'culto_domingo_noite_titulo' => [
                'valor' => 'Culto de Domingo - Noite',
                'tipo' => 'string',
                'descricao' => 'Título do culto de domingo noite'
            ],
            'culto_domingo_noite_horario' => [
                'valor' => '18:00h',
                'tipo' => 'string',
                'descricao' => 'Horário do culto de domingo noite'
            ],
            'culto_domingo_noite_descricao' => [
                'valor' => 'Culto de celebração e edificação espiritual',
                'tipo' => 'string',
                'descricao' => 'Descrição do culto de domingo noite'
            ],
            'culto_domingo_noite_item1' => [
                'valor' => 'Louvor e Adoração',
                'tipo' => 'string',
                'descricao' => 'Item 1 do culto de domingo noite'
            ],
            'culto_domingo_noite_item2' => [
                'valor' => 'Pregação da Palavra',
                'tipo' => 'string',
                'descricao' => 'Item 2 do culto de domingo noite'
            ],
            'culto_domingo_noite_item3' => [
                'valor' => 'Oração e Intercessão',
                'tipo' => 'string',
                'descricao' => 'Item 3 do culto de domingo noite'
            ],
            
            // Quarta-feira
            'culto_quarta_titulo' => [
                'valor' => 'Culto de Quarta-feira',
                'tipo' => 'string',
                'descricao' => 'Título do culto de quarta-feira'
            ],
            'culto_quarta_horario' => [
                'valor' => '19:30h',
                'tipo' => 'string',
                'descricao' => 'Horário do culto de quarta-feira'
            ],
            'culto_quarta_descricao' => [
                'valor' => 'Culto de oração e estudo bíblico',
                'tipo' => 'string',
                'descricao' => 'Descrição do culto de quarta-feira'
            ],
            'culto_quarta_item1' => [
                'valor' => 'Oração e Intercessão',
                'tipo' => 'string',
                'descricao' => 'Item 1 do culto de quarta-feira'
            ],
            'culto_quarta_item2' => [
                'valor' => 'Estudo Bíblico',
                'tipo' => 'string',
                'descricao' => 'Item 2 do culto de quarta-feira'
            ],
            'culto_quarta_item3' => [
                'valor' => 'Comunhão',
                'tipo' => 'string',
                'descricao' => 'Item 3 do culto de quarta-feira'
            ],
            
            // Escola Dominical
            'escola_dominical_titulo' => [
                'valor' => 'Escola Dominical',
                'tipo' => 'string',
                'descricao' => 'Título da Escola Dominical'
            ],
            'escola_dominical_horario' => [
                'valor' => 'Domingo às 08:00h',
                'tipo' => 'string',
                'descricao' => 'Horário da Escola Dominical'
            ],
            'escola_dominical_descricao' => [
                'valor' => 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.',
                'tipo' => 'string',
                'descricao' => 'Descrição da Escola Dominical'
            ],
            'escola_dominical_classe1' => [
                'valor' => 'Classes Infantis',
                'tipo' => 'string',
                'descricao' => 'Primeira classe da Escola Dominical'
            ],
            'escola_dominical_classe2' => [
                'valor' => 'Classes de Jovens',
                'tipo' => 'string',
                'descricao' => 'Segunda classe da Escola Dominical'
            ],
            'escola_dominical_classe3' => [
                'valor' => 'Classes de Adultos',
                'tipo' => 'string',
                'descricao' => 'Terceira classe da Escola Dominical'
            ],
            'escola_dominical_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar seção da Escola Dominical'
            ],
            
            // Header
            'header_logo_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar logo no header'
            ],
            'header_nome_igreja_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar nome da igreja no header'
            ],
            'header_slogan_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar slogan no header'
            ],
            'header_menu_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar menu no header'
            ],
            'header_area_usuario_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar área do usuário no header'
            ],
            'header_texto_area_membro' => [
                'valor' => 'Área do Membro',
                'tipo' => 'string',
                'descricao' => 'Texto do link da área do membro'
            ],
            'header_link_sobre' => [
                'valor' => 'Sobre',
                'tipo' => 'string',
                'descricao' => 'Texto do link Sobre no header'
            ],
            'header_link_ministerios' => [
                'valor' => 'Ministérios',
                'tipo' => 'string',
                'descricao' => 'Texto do link Ministérios no header'
            ],
            'header_link_cultos' => [
                'valor' => 'Cultos',
                'tipo' => 'string',
                'descricao' => 'Texto do link Cultos no header'
            ],
            'header_link_aniversariantes' => [
                'valor' => 'Aniversariantes',
                'tipo' => 'string',
                'descricao' => 'Texto do link Aniversariantes no header'
            ],
            'header_link_eventos' => [
                'valor' => 'Eventos',
                'tipo' => 'string',
                'descricao' => 'Texto do link Eventos no header'
            ],
            'header_link_doacao' => [
                'valor' => 'Doação',
                'tipo' => 'string',
                'descricao' => 'Texto do link Doação no header'
            ],
            'header_link_contato' => [
                'valor' => 'Contato',
                'tipo' => 'string',
                'descricao' => 'Texto do link Contato no header'
            ],
            
            // Sobre
            'sobre_titulo' => [
                'valor' => 'Sobre Nossa Igreja',
                'tipo' => 'string',
                'descricao' => 'Título da seção Sobre'
            ],
            'principios_batistas_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar princípios batistas'
            ],
            'principios_batistas_titulo' => [
                'valor' => 'Nossos Princípios Batistas',
                'tipo' => 'string',
                'descricao' => 'Título dos princípios batistas'
            ],
            'principio_1_titulo' => [
                'valor' => 'Sola Scriptura',
                'tipo' => 'string',
                'descricao' => 'Título do primeiro princípio'
            ],
            'principio_1_descricao' => [
                'valor' => 'A Bíblia como única regra de fé e prática',
                'tipo' => 'string',
                'descricao' => 'Descrição do primeiro princípio'
            ],
            'principio_2_titulo' => [
                'valor' => 'Sacerdócio Universal',
                'tipo' => 'string',
                'descricao' => 'Título do segundo princípio'
            ],
            'principio_2_descricao' => [
                'valor' => 'Todo crente tem acesso direto a Deus',
                'tipo' => 'string',
                'descricao' => 'Descrição do segundo princípio'
            ],
            'principio_3_titulo' => [
                'valor' => 'Batismo por Imersão',
                'tipo' => 'string',
                'descricao' => 'Título do terceiro princípio'
            ],
            'principio_3_descricao' => [
                'valor' => 'Para crentes professos, por imersão',
                'tipo' => 'string',
                'descricao' => 'Descrição do terceiro princípio'
            ],
            'principio_4_titulo' => [
                'valor' => 'Autonomia Local',
                'tipo' => 'string',
                'descricao' => 'Título do quarto princípio'
            ],
            'principio_4_descricao' => [
                'valor' => 'Cada igreja é independente e autônoma',
                'tipo' => 'string',
                'descricao' => 'Descrição do quarto princípio'
            ],
            
            // Contato
            'contato_titulo' => [
                'valor' => 'Entre em Contato',
                'tipo' => 'string',
                'descricao' => 'Título da seção de contato'
            ],
            'contato_subtitulo' => [
                'valor' => 'Estamos aqui para você. Entre em contato conosco!',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da seção de contato'
            ],
            
            // Doação
            'doacao_titulo' => [
                'valor' => 'Faça uma Doação',
                'tipo' => 'string',
                'descricao' => 'Título da seção de doação'
            ],
            'doacao_subtitulo' => [
                'valor' => 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!',
                'tipo' => 'string',
                'descricao' => 'Subtítulo da seção de doação'
            ],
            'doacao_dica_seguranca' => [
                'valor' => 'Seus dados estão protegidos com criptografia SSL',
                'tipo' => 'string',
                'descricao' => 'Dica sobre segurança nas doações'
            ],
            'doacao_dica_comprovante' => [
                'valor' => 'Receba um comprovante por email após a confirmação',
                'tipo' => 'string',
                'descricao' => 'Dica sobre comprovante'
            ],
            'doacao_dica_transparencia' => [
                'valor' => 'Todas as doações são registradas e auditadas',
                'tipo' => 'string',
                'descricao' => 'Dica sobre transparência'
            ],
            'doacao_dizimo_titulo' => [
                'valor' => 'Dízimo',
                'tipo' => 'string',
                'descricao' => 'Título do card de dízimo'
            ],
            'doacao_dizimo_descricao' => [
                'valor' => 'Contribua com o dízimo para a manutenção da igreja.',
                'tipo' => 'string',
                'descricao' => 'Descrição do card de dízimo'
            ],
            'doacao_dizimo_botao' => [
                'valor' => 'Doar Dízimo',
                'tipo' => 'string',
                'descricao' => 'Texto do botão de dízimo'
            ],
            'doacao_oferta_titulo' => [
                'valor' => 'Oferta',
                'tipo' => 'string',
                'descricao' => 'Título do card de oferta'
            ],
            'doacao_oferta_descricao' => [
                'valor' => 'Ofereça com amor para as necessidades da igreja.',
                'tipo' => 'string',
                'descricao' => 'Descrição do card de oferta'
            ],
            'doacao_oferta_botao' => [
                'valor' => 'Fazer Oferta',
                'tipo' => 'string',
                'descricao' => 'Texto do botão de oferta'
            ],
            'doacao_campanhas_titulo' => [
                'valor' => 'Campanhas',
                'tipo' => 'string',
                'descricao' => 'Título do card de campanhas'
            ],
            'doacao_campanhas_descricao' => [
                'valor' => 'Participe de nossas campanhas especiais.',
                'tipo' => 'string',
                'descricao' => 'Descrição do card de campanhas'
            ],
            'doacao_campanhas_botao' => [
                'valor' => 'Ver Campanhas',
                'tipo' => 'string',
                'descricao' => 'Texto do botão de campanhas'
            ],
            
            // Footer
            'footer_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Ativar footer'
            ],
            'footer_descricao' => [
                'valor' => 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!',
                'tipo' => 'string',
                'descricao' => 'Descrição no footer'
            ],
            'footer_links_titulo' => [
                'valor' => 'Links Rápidos',
                'tipo' => 'string',
                'descricao' => 'Título da seção de links no footer'
            ],
            'footer_contato_titulo' => [
                'valor' => 'Contato',
                'tipo' => 'string',
                'descricao' => 'Título da seção de contato no footer'
            ],
            'footer_horarios_titulo' => [
                'valor' => 'Horários',
                'tipo' => 'string',
                'descricao' => 'Título da seção de horários no footer'
            ],
            'footer_copyright_texto' => [
                'valor' => 'Todos os direitos reservados.',
                'tipo' => 'string',
                'descricao' => 'Texto de copyright'
            ],
            'footer_link_sobre' => [
                'valor' => 'Sobre',
                'tipo' => 'string',
                'descricao' => 'Link Sobre no footer'
            ],
            'footer_link_ministerios' => [
                'valor' => 'Ministérios',
                'tipo' => 'string',
                'descricao' => 'Link Ministérios no footer'
            ],
            'footer_link_cultos' => [
                'valor' => 'Cultos',
                'tipo' => 'string',
                'descricao' => 'Link Cultos no footer'
            ],
            'footer_link_eventos' => [
                'valor' => 'Eventos',
                'tipo' => 'string',
                'descricao' => 'Link Eventos no footer'
            ],
            'footer_link_aniversariantes' => [
                'valor' => 'Aniversariantes',
                'tipo' => 'string',
                'descricao' => 'Link Aniversariantes no footer'
            ],
            'footer_link_doacao' => [
                'valor' => 'Doação',
                'tipo' => 'string',
                'descricao' => 'Link Doação no footer'
            ],
            'footer_redes_sociais_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar redes sociais no footer'
            ],
            'footer_link_creditos_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar link de créditos'
            ],
            'footer_link_creditos_texto' => [
                'valor' => 'Reinan Rodrigues',
                'tipo' => 'string',
                'descricao' => 'Texto do link de créditos'
            ],
            'footer_link_vertex_ativa' => [
                'valor' => '1',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar link da Vertex'
            ],
            'footer_link_vertex_texto' => [
                'valor' => 'Vertex Solutions',
                'tipo' => 'string',
                'descricao' => 'Texto do link da Vertex'
            ],
            'footer_link_privacidade_ativa' => [
                'valor' => '0',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar link de privacidade'
            ],
            'footer_link_privacidade_texto' => [
                'valor' => 'Política de Privacidade',
                'tipo' => 'string',
                'descricao' => 'Texto do link de privacidade'
            ],
            'footer_link_termos_ativa' => [
                'valor' => '0',
                'tipo' => 'boolean',
                'descricao' => 'Mostrar link de termos'
            ],
            'footer_link_termos_texto' => [
                'valor' => 'Termos de Uso',
                'tipo' => 'string',
                'descricao' => 'Texto do link de termos'
            ],
            'footer_horario_domingo_manha' => [
                'valor' => 'Domingo Manhã',
                'tipo' => 'string',
                'descricao' => 'Horário domingo manhã no footer'
            ],
            'footer_horario_domingo_noite' => [
                'valor' => 'Domingo Noite',
                'tipo' => 'string',
                'descricao' => 'Horário domingo noite no footer'
            ],
            'footer_horario_quarta' => [
                'valor' => 'Quarta-feira',
                'tipo' => 'string',
                'descricao' => 'Horário quarta no footer'
            ],
            'footer_horario_escola_dominical' => [
                'valor' => 'Escola Dominical',
                'tipo' => 'string',
                'descricao' => 'Horário escola dominical no footer'
            ]
        ];
    }
}
