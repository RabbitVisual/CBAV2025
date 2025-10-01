<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracao;

class CheckConfigConflicts extends Command
{
    protected $signature = 'system:check-conflicts';
    protected $description = 'Verificar conflitos entre configurações do sistema e da homepage';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE CONFLITOS DE CONFIGURAÇÕES ===');
        
        // Configurações do sistema
        $systemConfigs = [
            'app_name', 'app_description', 'contact_email', 'contact_phone',
            'address', 'timezone', 'locale', 'app_logo', 'app_favicon',
            'social_facebook', 'social_instagram', 'social_youtube',
            'stripe_key', 'stripe_secret', 'mercadopago_key', 'mercadopago_token',
            'pix_chave', 'pix_beneficiario', 'doacao_valor_minimo', 'doacao_valor_maximo',
            'doacao_sem_login', 'doacao_ativa', 'mail_host', 'mail_port',
            'mail_username', 'mail_password', 'session_lifetime', 'max_login_attempts',
            'force_ssl', 'enable_2fa', 'cache_driver', 'session_driver', 'backup_enabled'
        ];
        
        // Configurações da homepage
        $homeConfigs = [
            'igreja_nome', 'igreja_slogan', 'igreja_endereco', 'igreja_telefone',
            'igreja_email', 'igreja_website', 'igreja_facebook', 'igreja_instagram',
            'igreja_youtube', 'logo', 'cor_primaria', 'cor_secundaria', 'cor_destaque',
            'secao_sobre_ativa', 'secao_ministerios_ativa', 'secao_cultos_ativa',
            'secao_aniversariantes_ativa', 'secao_doacao_ativa', 'secao_contato_ativa',
            'sobre_titulo', 'cultos_titulo', 'cultos_subtitulo', 'doacao_titulo',
            'doacao_subtitulo', 'contato_titulo', 'contato_subtitulo',
            'principios_batistas_ativa', 'principios_batistas_titulo',
            'principio_1_titulo', 'principio_1_descricao', 'principio_2_titulo',
            'principio_2_descricao', 'principio_3_titulo', 'principio_3_descricao',
            'principio_4_titulo', 'principio_4_descricao', 'culto_domingo_manha_titulo',
            'culto_domingo_manha_horario', 'culto_domingo_manha_descricao',
            'culto_domingo_manha_item1', 'culto_domingo_manha_item2', 'culto_domingo_manha_item3',
            'culto_domingo_noite_titulo', 'culto_domingo_noite_horario',
            'culto_domingo_noite_descricao', 'culto_domingo_noite_item1',
            'culto_domingo_noite_item2', 'culto_domingo_noite_item3',
            'culto_quarta_titulo', 'culto_quarta_horario', 'culto_quarta_descricao',
            'culto_quarta_item1', 'culto_quarta_item2', 'culto_quarta_item3',
            'escola_dominical_ativa', 'escola_dominical_titulo', 'escola_dominical_horario',
            'escola_dominical_descricao', 'escola_dominical_classe1', 'escola_dominical_classe2',
            'escola_dominical_classe3', 'doacao_dizimo_titulo', 'doacao_dizimo_descricao',
            'doacao_dizimo_botao', 'doacao_oferta_titulo', 'doacao_oferta_descricao',
            'doacao_oferta_botao', 'doacao_campanhas_titulo', 'doacao_campanhas_descricao',
            'doacao_campanhas_botao', 'footer_links_titulo', 'footer_link_sobre',
            'footer_link_ministerios', 'footer_link_cultos', 'footer_link_aniversariantes',
            'footer_link_doacao'
        ];
        
        // Verificar sobreposições
        $overlaps = array_intersect($systemConfigs, $homeConfigs);
        
        if (empty($overlaps)) {
            $this->info('✅ Nenhum conflito encontrado entre configurações do sistema e da homepage');
        } else {
            $this->warn('⚠️  Possíveis conflitos encontrados:');
            foreach ($overlaps as $config) {
                $this->line("  - {$config}");
            }
            $this->info('');
            $this->info('💡 Recomendações:');
            $this->line('  • Configurações do sistema: Para configurações técnicas e gerais');
            $this->line('  • Configurações da homepage: Para personalização da página inicial');
            $this->line('  • Evite usar a mesma chave em ambos os lugares');
        }
        
        // Verificar configurações ausentes
        $this->info('');
        $this->info('=== CONFIGURAÇÕES AUSENTES ===');
        
        $allConfigs = array_merge($systemConfigs, $homeConfigs);
        $existingConfigs = Configuracao::pluck('chave')->toArray();
        $missingConfigs = array_diff($allConfigs, $existingConfigs);
        
        if (empty($missingConfigs)) {
            $this->info('✅ Todas as configurações necessárias estão presentes');
        } else {
            $this->warn('⚠️  Configurações ausentes:');
            foreach ($missingConfigs as $config) {
                $this->line("  - {$config}");
            }
        }
        
        // Verificar configurações duplicadas
        $this->info('');
        $this->info('=== CONFIGURAÇÕES DUPLICADAS ===');
        
        $duplicates = Configuracao::select('chave')
            ->groupBy('chave')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('chave')
            ->toArray();
        
        if (empty($duplicates)) {
            $this->info('✅ Nenhuma configuração duplicada encontrada');
        } else {
            $this->warn('⚠️  Configurações duplicadas:');
            foreach ($duplicates as $config) {
                $this->line("  - {$config}");
            }
        }
        
        // Estatísticas
        $this->info('');
        $this->info('=== ESTATÍSTICAS ===');
        $this->line("  Total de configurações do sistema: " . count($systemConfigs));
        $this->line("  Total de configurações da homepage: " . count($homeConfigs));
        $this->line("  Total de configurações existentes: " . count($existingConfigs));
        $this->line("  Configurações ausentes: " . count($missingConfigs));
        $this->line("  Configurações duplicadas: " . count($duplicates));
        
        $this->info('');
        $this->info('✅ Verificação de conflitos concluída!');
    }
} 