<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracao;

class GatewaySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💳 Configurando gateways de pagamento...');

        $configuracoes = [
            // Stripe
            ['chave' => 'stripe_key', 'valor' => env('STRIPE_KEY', ''), 'tipo' => 'text', 'descricao' => 'Chave pública do Stripe'],
            ['chave' => 'stripe_secret', 'valor' => env('STRIPE_SECRET', ''), 'tipo' => 'password', 'descricao' => 'Chave secreta do Stripe'],
            ['chave' => 'stripe_webhook_secret', 'valor' => env('STRIPE_WEBHOOK_SECRET', ''), 'tipo' => 'password', 'descricao' => 'Chave secreta do webhook do Stripe'],
            ['chave' => 'stripe_enabled', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Stripe habilitado'],
            ['chave' => 'stripe_test_mode', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Modo de teste do Stripe'],

            // Mercado Pago
            ['chave' => 'mercadopago_key', 'valor' => env('MERCADOPAGO_PUBLIC_KEY', ''), 'tipo' => 'text', 'descricao' => 'Chave pública do Mercado Pago'],
            ['chave' => 'mercadopago_token', 'valor' => env('MERCADOPAGO_ACCESS_TOKEN', ''), 'tipo' => 'password', 'descricao' => 'Token de acesso do Mercado Pago'],
            ['chave' => 'mercadopago_webhook_secret', 'valor' => env('MERCADOPAGO_WEBHOOK_SECRET', ''), 'tipo' => 'password', 'descricao' => 'Chave secreta do webhook do Mercado Pago'],
            ['chave' => 'mercadopago_enabled', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Mercado Pago habilitado'],
            ['chave' => 'mercadopago_test_mode', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Modo de teste do Mercado Pago'],

            // PIX
            ['chave' => 'pix_chave', 'valor' => env('PIX_CHAVE', ''), 'tipo' => 'text', 'descricao' => 'Chave PIX'],
            ['chave' => 'pix_beneficiario', 'valor' => env('PIX_BENEFICIARIO_NOME', 'Congregação Batista Avenida'), 'tipo' => 'text', 'descricao' => 'Nome do beneficiário PIX'],
            ['chave' => 'pix_cpf_cnpj', 'valor' => env('PIX_BENEFICIARIO_CPF_CNPJ', ''), 'tipo' => 'text', 'descricao' => 'CPF/CNPJ do beneficiário PIX'],
            ['chave' => 'pix_banco', 'valor' => env('PIX_BANCO', ''), 'tipo' => 'text', 'descricao' => 'Código do banco para PIX'],
            ['chave' => 'pix_enabled', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'PIX habilitado'],

            // Configurações de Doação
            ['chave' => 'doacao_valor_minimo', 'valor' => '1.00', 'tipo' => 'number', 'descricao' => 'Valor mínimo para doações'],
            ['chave' => 'doacao_valor_maximo', 'valor' => '10000.00', 'tipo' => 'number', 'descricao' => 'Valor máximo para doações'],
            ['chave' => 'doacao_moeda', 'valor' => 'BRL', 'tipo' => 'text', 'descricao' => 'Moeda padrão para doações'],
            ['chave' => 'doacao_taxa_stripe', 'valor' => '2.9', 'tipo' => 'number', 'descricao' => 'Taxa do Stripe em porcentagem'],
            ['chave' => 'doacao_taxa_mercadopago', 'valor' => '3.99', 'tipo' => 'number', 'descricao' => 'Taxa do Mercado Pago em porcentagem'],
            ['chave' => 'doacao_sem_login', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Permitir doações sem login'],
            ['chave' => 'doacao_ativa', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Sistema de doação ativo'],

            // Configurações de Notificação
            ['chave' => 'notificacao_email_doacao', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Enviar notificação por email para doações'],
            ['chave' => 'notificacao_sms_doacao', 'valor' => 'false', 'tipo' => 'boolean', 'descricao' => 'Enviar notificação por SMS para doações'],

            // Configurações de Segurança
            ['chave' => 'pagamento_ssl_obrigatorio', 'valor' => 'true', 'tipo' => 'boolean', 'descricao' => 'Obrigar SSL para pagamentos'],
            ['chave' => 'pagamento_timeout', 'valor' => '300', 'tipo' => 'number', 'descricao' => 'Timeout para pagamentos em segundos'],
        ];

        foreach ($configuracoes as $config) {
            Configuracao::updateOrCreate(
                ['chave' => $config['chave']],
                $config
            );
        }

        $this->command->info('✅ Configurações de gateways definidas');
    }
} 