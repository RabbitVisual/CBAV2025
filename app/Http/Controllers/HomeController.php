<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;
use App\Models\Ministerio;
use App\Models\Membro;
use App\Models\Transacao;
use App\Models\Campanha;
use App\Models\Devocional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Página inicial do site
     */
    public function index()
    {
        try {
            // Buscar configurações da igreja como objetos
            $configuracoes = [
            // Informações básicas da igreja
            'igreja_nome' => (object)['valor' => Configuracao::get('igreja_nome', 'Congregação Batista Avenida')],
            'igreja_slogan' => (object)['valor' => Configuracao::get('igreja_slogan', 'Uma igreja comprometida com o amor de Cristo')],
            'igreja_endereco' => (object)['valor' => Configuracao::get('igreja_endereco', 'Endereço da igreja')],
            'igreja_telefone' => (object)['valor' => Configuracao::get('igreja_telefone', '(11) 99999-9999')],
            'igreja_email' => (object)['valor' => Configuracao::get('igreja_email', 'contato@igreja.com')],
            'igreja_website' => (object)['valor' => Configuracao::get('igreja_website', '')],
            'igreja_facebook' => (object)['valor' => Configuracao::get('igreja_facebook', '')],
            'igreja_instagram' => (object)['valor' => Configuracao::get('igreja_instagram', '')],
            'igreja_youtube' => (object)['valor' => Configuracao::get('igreja_youtube', '')],
            'igreja_whatsapp' => (object)['valor' => Configuracao::get('igreja_whatsapp', '')],
            
            // Horários de culto (configurações antigas para compatibilidade)
            'culto_domingo' => (object)['valor' => Configuracao::get('culto_domingo', 'Domingo: 09:00 e 18:00')],
            'culto_meio_semana' => (object)['valor' => Configuracao::get('culto_meio_semana', 'Quarta: 19:30')],
            
            // Horários detalhados de cultos
            'culto_domingo_manha_titulo' => (object)['valor' => Configuracao::get('culto_domingo_manha_titulo', 'Culto de Domingo - Manhã')],
            'culto_domingo_manha_horario' => (object)['valor' => Configuracao::get('culto_domingo_manha_horario', '09:00h')],
            'culto_domingo_manha_descricao' => (object)['valor' => Configuracao::get('culto_domingo_manha_descricao', 'Culto de adoração e pregação da Palavra de Deus')],
            'culto_domingo_manha_item1' => (object)['valor' => Configuracao::get('culto_domingo_manha_item1', 'Louvor e Adoração')],
            'culto_domingo_manha_item2' => (object)['valor' => Configuracao::get('culto_domingo_manha_item2', 'Pregação da Palavra')],
            'culto_domingo_manha_item3' => (object)['valor' => Configuracao::get('culto_domingo_manha_item3', 'Oração e Intercessão')],
            'culto_domingo_noite_titulo' => (object)['valor' => Configuracao::get('culto_domingo_noite_titulo', 'Culto de Domingo - Noite')],
            'culto_domingo_noite_horario' => (object)['valor' => Configuracao::get('culto_domingo_noite_horario', '18:00h')],
            'culto_domingo_noite_descricao' => (object)['valor' => Configuracao::get('culto_domingo_noite_descricao', 'Culto de celebração e edificação espiritual')],
            'culto_domingo_noite_item1' => (object)['valor' => Configuracao::get('culto_domingo_noite_item1', 'Louvor e Adoração')],
            'culto_domingo_noite_item2' => (object)['valor' => Configuracao::get('culto_domingo_noite_item2', 'Pregação da Palavra')],
            'culto_domingo_noite_item3' => (object)['valor' => Configuracao::get('culto_domingo_noite_item3', 'Oração e Intercessão')],
            'culto_quarta_titulo' => (object)['valor' => Configuracao::get('culto_quarta_titulo', 'Culto de Quarta-feira')],
            'culto_quarta_horario' => (object)['valor' => Configuracao::get('culto_quarta_horario', '19:30h')],
            'culto_quarta_descricao' => (object)['valor' => Configuracao::get('culto_quarta_descricao', 'Culto de oração e estudo bíblico')],
            'culto_quarta_item1' => (object)['valor' => Configuracao::get('culto_quarta_item1', 'Oração e Intercessão')],
            'culto_quarta_item2' => (object)['valor' => Configuracao::get('culto_quarta_item2', 'Estudo Bíblico')],
            'culto_quarta_item3' => (object)['valor' => Configuracao::get('culto_quarta_item3', 'Comunhão')],

            // Conteúdo e seções
            'contato_titulo' => (object)['valor' => Configuracao::get('contato_titulo', 'Entre em Contato')],
            'contato_subtitulo' => (object)['valor' => Configuracao::get('contato_subtitulo', 'Estamos aqui para você. Entre em contato conosco!')],
            'hero_titulo' => (object)['valor' => Configuracao::get('hero_titulo', 'Bem-vindo à Nossa Igreja')],
            'hero_subtitulo' => (object)['valor' => Configuracao::get('hero_subtitulo', 'Uma comunidade de fé, amor e esperança. Venha fazer parte da nossa família!')],
            'home_botao_texto' => (object)['valor' => Configuracao::get('home_botao_texto', 'Conheça-nos')],
            'home_botao_link' => (object)['valor' => Configuracao::get('home_botao_link', '#sobre')],
            'home_botao_doacao_texto' => (object)['valor' => Configuracao::get('home_botao_doacao_texto', 'Faça uma Doação')],
            'ministerios_titulo' => (object)['valor' => Configuracao::get('ministerios_titulo', 'Nossos Ministérios')],
            'ministerios_subtitulo' => (object)['valor' => Configuracao::get('ministerios_subtitulo', 'Conheça nossos ministérios e participe da obra de Deus.')],
            'aniversariantes_titulo' => (object)['valor' => Configuracao::get('aniversariantes_titulo', 'Aniversariantes do Mês')],
            'aniversariantes_subtitulo' => (object)['valor' => Configuracao::get('aniversariantes_subtitulo', 'Celebrando a vida dos nossos membros')],
            'aniversariantes_mostrar' => (object)['valor' => Configuracao::get('aniversariantes_mostrar', '1')],
            'secao_sobre_ativa' => (object)['valor' => Configuracao::get('secao_sobre_ativa', '1')],
            'secao_ministerios_ativa' => (object)['valor' => Configuracao::get('secao_ministerios_ativa', '1')],
            'secao_cultos_ativa' => (object)['valor' => Configuracao::get('secao_cultos_ativa', '1')],
            'secao_contato_ativa' => (object)['valor' => Configuracao::get('secao_contato_ativa', '1')],
            'secao_doacao_ativa' => (object)['valor' => Configuracao::get('secao_doacao_ativa', '1')],
            'secao_eventos_ativa' => (object)['valor' => Configuracao::get('secao_eventos_ativa', '1')],
            'secao_aniversariantes_ativa' => (object)['valor' => Configuracao::get('secao_aniversariantes_ativa', '1')],

            // Cores
            'cor_primaria' => (object)['valor' => Configuracao::get('cor_primaria', '#1e40af')],
            'cor_secundaria' => (object)['valor' => Configuracao::get('cor_secundaria', '#3b82f6')],
            'cor_destaque' => (object)['valor' => Configuracao::get('cor_destaque', '#10b981')],
            'cor_texto' => (object)['valor' => Configuracao::get('cor_texto', '#1f2937')],

            // Logo
            'logo' => (object)['valor' => Configuracao::get('logo', '')],

            // Foto de fundo do hero
            'hero_foto_fundo_ativa' => (object)['valor' => Configuracao::get('hero_foto_fundo_ativa', '0')],
            'hero_foto_fundo' => (object)['valor' => Configuracao::get('hero_foto_fundo', '')],
            'hero_overlay_opacidade' => (object)['valor' => Configuracao::get('hero_overlay_opacidade', '0.6')],
            'hero_overlay_cor' => (object)['valor' => Configuracao::get('hero_overlay_cor', '#1e40af')],

            // SEO
            'meta_title' => (object)['valor' => Configuracao::get('meta_title', null)],
            'meta_description' => (object)['valor' => Configuracao::get('meta_description', null)],
            'meta_keywords' => (object)['valor' => Configuracao::get('meta_keywords', null)],

            // Doação e gateways
            'doacao_ativa' => (object)['valor' => Configuracao::get('doacao_ativa', '1')],
            'doacao_sem_login' => (object)['valor' => Configuracao::get('doacao_sem_login', '1')],
            'doacao_valor_minimo' => (object)['valor' => Configuracao::get('doacao_valor_minimo', 1)],
            'doacao_valor_maximo' => (object)['valor' => Configuracao::get('doacao_valor_maximo', 10000)],
            'pix_chave' => (object)['valor' => Configuracao::get('pix_chave', '')],
            'stripe_key' => (object)['valor' => Configuracao::get('stripe_key', '')],
            'mercadopago_key' => (object)['valor' => Configuracao::get('mercadopago_key', '')],
            'doacao_titulo' => (object)['valor' => Configuracao::get('doacao_titulo', 'Faça uma Doação')],
            'doacao_subtitulo' => (object)['valor' => Configuracao::get('doacao_subtitulo', 'Sua doação ajuda a manter nossa igreja e a expandir a obra de Deus. Seja uma bênção!')],
            'doacao_dica_seguranca' => (object)['valor' => Configuracao::get('doacao_dica_seguranca', 'Seus dados estão protegidos com criptografia SSL')],
            'doacao_dica_comprovante' => (object)['valor' => Configuracao::get('doacao_dica_comprovante', 'Receba um comprovante por email após a confirmação')],
            'doacao_dica_transparencia' => (object)['valor' => Configuracao::get('doacao_dica_transparencia', 'Todas as doações são registradas e auditadas')],

            // Escola Dominical
            'escola_dominical_titulo' => (object)['valor' => Configuracao::get('escola_dominical_titulo', 'Escola Dominical')],
            'escola_dominical_horario' => (object)['valor' => Configuracao::get('escola_dominical_horario', 'Domingo às 08:00h')],
            'escola_dominical_descricao' => (object)['valor' => Configuracao::get('escola_dominical_descricao', 'Venha estudar a Bíblia conosco! A Escola Dominical é um momento especial para aprender mais sobre a Palavra de Deus, crescer na fé e fortalecer nossa comunhão.')],
            'escola_dominical_classe1' => (object)['valor' => Configuracao::get('escola_dominical_classe1', 'Classes Infantis')],
            'escola_dominical_classe2' => (object)['valor' => Configuracao::get('escola_dominical_classe2', 'Classes de Jovens')],
            'escola_dominical_classe3' => (object)['valor' => Configuracao::get('escola_dominical_classe3', 'Classes de Adultos')],
            'escola_dominical_ativa' => (object)['valor' => Configuracao::get('escola_dominical_ativa', '1')],

            // Configurações do Header
            'header_logo_ativa' => (object)['valor' => Configuracao::get('header_logo_ativa', '1')],
            'header_nome_igreja_ativa' => (object)['valor' => Configuracao::get('header_nome_igreja_ativa', '1')],
            'header_slogan_ativa' => (object)['valor' => Configuracao::get('header_slogan_ativa', '1')],
            'header_menu_ativa' => (object)['valor' => Configuracao::get('header_menu_ativa', '1')],
            'header_area_usuario_ativa' => (object)['valor' => Configuracao::get('header_area_usuario_ativa', '1')],
            'header_texto_area_membro' => (object)['valor' => Configuracao::get('header_texto_area_membro', 'Área do Membro')],
            'header_link_sobre' => (object)['valor' => Configuracao::get('header_link_sobre', 'Sobre')],
            'header_link_ministerios' => (object)['valor' => Configuracao::get('header_link_ministerios', 'Ministérios')],
            'header_link_cultos' => (object)['valor' => Configuracao::get('header_link_cultos', 'Cultos')],
            'header_link_aniversariantes' => (object)['valor' => Configuracao::get('header_link_aniversariantes', 'Aniversariantes')],
            'header_link_eventos' => (object)['valor' => Configuracao::get('header_link_eventos', 'Eventos')],
            'header_link_doacao' => (object)['valor' => Configuracao::get('header_link_doacao', 'Doação')],
            'header_link_contato' => (object)['valor' => Configuracao::get('header_link_contato', 'Contato')],

            // Seção Sobre e Princípios
            'sobre_titulo' => (object)['valor' => Configuracao::get('sobre_titulo', 'Sobre Nossa Igreja')],
            'principios_batistas_ativa' => (object)['valor' => Configuracao::get('principios_batistas_ativa', '1')],
            'principios_batistas_titulo' => (object)['valor' => Configuracao::get('principios_batistas_titulo', 'Nossos Princípios Batistas')],
            'principio_1_titulo' => (object)['valor' => Configuracao::get('principio_1_titulo', 'Sola Scriptura')],
            'principio_1_descricao' => (object)['valor' => Configuracao::get('principio_1_descricao', 'A Bíblia como única regra de fé e prática')],
            'principio_2_titulo' => (object)['valor' => Configuracao::get('principio_2_titulo', 'Sacerdócio Universal')],
            'principio_2_descricao' => (object)['valor' => Configuracao::get('principio_2_descricao', 'Todo crente tem acesso direto a Deus')],
            'principio_3_titulo' => (object)['valor' => Configuracao::get('principio_3_titulo', 'Batismo por Imersão')],
            'principio_3_descricao' => (object)['valor' => Configuracao::get('principio_3_descricao', 'Para crentes professos, por imersão')],
            'principio_4_titulo' => (object)['valor' => Configuracao::get('principio_4_titulo', 'Autonomia Local')],
            'principio_4_descricao' => (object)['valor' => Configuracao::get('principio_4_descricao', 'Cada igreja é independente e autônoma')],

            // Seção Cultos (título/subtítulo)
            'cultos_titulo' => (object)['valor' => Configuracao::get('cultos_titulo', 'Horários de Cultos')],
            'cultos_subtitulo' => (object)['valor' => Configuracao::get('cultos_subtitulo', 'Venha adorar conosco e crescer na fé através da Palavra de Deus')],

            // Cartões de Doação auxiliares
            'doacao_dizimo_titulo' => (object)['valor' => Configuracao::get('doacao_dizimo_titulo', 'Dízimo')],
            'doacao_dizimo_descricao' => (object)['valor' => Configuracao::get('doacao_dizimo_descricao', 'Contribua com o dízimo para a manutenção da igreja.')],
            'doacao_dizimo_botao' => (object)['valor' => Configuracao::get('doacao_dizimo_botao', 'Doar Dízimo')],
            'doacao_oferta_titulo' => (object)['valor' => Configuracao::get('doacao_oferta_titulo', 'Oferta')],
            'doacao_oferta_descricao' => (object)['valor' => Configuracao::get('doacao_oferta_descricao', 'Ofereça com amor para as necessidades da igreja.')],
            'doacao_oferta_botao' => (object)['valor' => Configuracao::get('doacao_oferta_botao', 'Fazer Oferta')],
            'doacao_campanhas_titulo' => (object)['valor' => Configuracao::get('doacao_campanhas_titulo', 'Campanhas')],
            'doacao_campanhas_descricao' => (object)['valor' => Configuracao::get('doacao_campanhas_descricao', 'Participe de nossas campanhas especiais.')],
            'doacao_campanhas_botao' => (object)['valor' => Configuracao::get('doacao_campanhas_botao', 'Ver Campanhas')],

            // Configurações do Footer
            'footer_ativa' => (object)['valor' => Configuracao::get('footer_ativa', '1')],
            'footer_descricao' => (object)['valor' => Configuracao::get('footer_descricao', 'Uma comunidade de fé dedicada ao amor de Cristo e ao serviço ao próximo. Venha fazer parte da nossa família!')],
            'footer_links_titulo' => (object)['valor' => Configuracao::get('footer_links_titulo', 'Links Rápidos')],
            'footer_contato_titulo' => (object)['valor' => Configuracao::get('footer_contato_titulo', 'Contato')],
            'footer_horarios_titulo' => (object)['valor' => Configuracao::get('footer_horarios_titulo', 'Horários')],
            'footer_copyright_texto' => (object)['valor' => Configuracao::get('footer_copyright_texto', 'Todos os direitos reservados.')],
            'footer_link_sobre' => (object)['valor' => Configuracao::get('footer_link_sobre', 'Sobre')],
            'footer_link_ministerios' => (object)['valor' => Configuracao::get('footer_link_ministerios', 'Ministérios')],
            'footer_link_cultos' => (object)['valor' => Configuracao::get('footer_link_cultos', 'Cultos')],
            'footer_link_eventos' => (object)['valor' => Configuracao::get('footer_link_eventos', 'Eventos')],
            'footer_link_aniversariantes' => (object)['valor' => Configuracao::get('footer_link_aniversariantes', 'Aniversariantes')],
            'footer_link_doacao' => (object)['valor' => Configuracao::get('footer_link_doacao', 'Doação')],
            'footer_redes_sociais_ativa' => (object)['valor' => Configuracao::get('footer_redes_sociais_ativa', '1')],
            'footer_link_creditos_ativa' => (object)['valor' => Configuracao::get('footer_link_creditos_ativa', '1')],
            'footer_link_creditos_texto' => (object)['valor' => Configuracao::get('footer_link_creditos_texto', 'Reinan Rodrigues')],
            'footer_link_vertex_ativa' => (object)['valor' => Configuracao::get('footer_link_vertex_ativa', '1')],
            'footer_link_vertex_texto' => (object)['valor' => Configuracao::get('footer_link_vertex_texto', 'Vertex Solutions')],
            'footer_link_privacidade_ativa' => (object)['valor' => Configuracao::get('footer_link_privacidade_ativa', '0')],
            'footer_link_privacidade_texto' => (object)['valor' => Configuracao::get('footer_link_privacidade_texto', 'Política de Privacidade')],
            'footer_link_termos_ativa' => (object)['valor' => Configuracao::get('footer_link_termos_ativa', '0')],
            'footer_link_termos_texto' => (object)['valor' => Configuracao::get('footer_link_termos_texto', 'Termos de Uso')],
            'footer_horario_domingo_manha' => (object)['valor' => Configuracao::get('footer_horario_domingo_manha', 'Domingo Manhã')],
            'footer_horario_domingo_noite' => (object)['valor' => Configuracao::get('footer_horario_domingo_noite', 'Domingo Noite')],
            'footer_horario_quarta' => (object)['valor' => Configuracao::get('footer_horario_quarta', 'Quarta-feira')],
            'footer_horario_escola_dominical' => (object)['valor' => Configuracao::get('footer_horario_escola_dominical', 'Escola Dominical')],
        ];

        // Buscar ministérios ativos
        $ministerios = Ministerio::where('ativo', true)
            ->withCount('membros')
            ->orderBy('nome')
            ->get();

        // Buscar campanhas ativas
        $campanhas = Campanha::where('ativo', true)
            ->where('status', 'ativa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Buscar aniversariantes do mês atual (se habilitado)
        $aniversariantes = collect();
        if (($configuracoes['aniversariantes_mostrar']->valor ?? '1') == '1') {
            $mesAtual = Carbon::now()->month;
            $aniversariantes = Membro::whereMonth('data_nascimento', $mesAtual)
                ->where('ativo', true)
                ->orderByRaw('DAY(data_nascimento)')
                ->limit(6)
                ->get();
        }

        // Buscar estatísticas básicas
        $estatisticas = [
            'total_membros' => Membro::where('ativo', true)->count(),
            'total_ministerios' => Ministerio::where('ativo', true)->count(),
            'doacoes_mes' => Transacao::where('tipo', 'entrada')
                ->where('status', 'confirmado')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('valor'),
        ];

        return view('welcome', compact('configuracoes', 'ministerios', 'campanhas', 'aniversariantes', 'estatisticas'));
        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao carregar página inicial: ' . $e->getMessage());
            
            // Retornar dados padrão em caso de erro
            $configuracoes = [
                'igreja_nome' => (object)['valor' => 'Congregação Batista Avenida'],
                'igreja_slogan' => (object)['valor' => 'Uma igreja comprometida com o amor de Cristo'],
                'cor_primaria' => (object)['valor' => '#1e40af'],
                'cor_secundaria' => (object)['valor' => '#3b82f6'],
                'cor_destaque' => (object)['valor' => '#10b981'],
            ];
            
            return view('welcome', compact('configuracoes'));
        }
    }
} 