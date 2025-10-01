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
        $configuracoes = [
            // Stripe
            [
                'chave' => 'stripe_key',
                'valor' => env('STRIPE_KEY', ''),
                'tipo' => 'text',
                'descricao' => 'Chave pública do Stripe para processamento de pagamentos'
            ],
            [
                'chave' => 'stripe_secret',
                'valor' => env('STRIPE_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do Stripe para processamento de pagamentos'
            ],
            [
                'chave' => 'stripe_webhook_secret',
                'valor' => env('STRIPE_WEBHOOK_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do webhook do Stripe'
            ],

            // Mercado Pago
            [
                'chave' => 'mercadopago_key',
                'valor' => env('MERCADOPAGO_PUBLIC_KEY', ''),
                'tipo' => 'text',
                'descricao' => 'Chave pública do Mercado Pago'
            ],
            [
                'chave' => 'mercadopago_token',
                'valor' => env('MERCADOPAGO_ACCESS_TOKEN', ''),
                'tipo' => 'password',
                'descricao' => 'Token de acesso do Mercado Pago'
            ],
            [
                'chave' => 'mercadopago_webhook_secret',
                'valor' => env('MERCADOPAGO_WEBHOOK_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do webhook do Mercado Pago'
            ],

            // PIX
            [
                'chave' => 'pix_chave',
                'valor' => env('PIX_CHAVE', ''),
                'tipo' => 'text',
                'descricao' => 'Chave PIX para recebimento de transferências'
            ],
            [
                'chave' => 'pix_beneficiario',
                'valor' => env('PIX_BENEFICIARIO_NOME', 'Congregação Batista Avenida'),
                'tipo' => 'text',
                'descricao' => 'Nome do beneficiário PIX'
            ],
            [
                'chave' => 'pix_cpf_cnpj',
                'valor' => env('PIX_BENEFICIARIO_CPF_CNPJ', ''),
                'tipo' => 'text',
                'descricao' => 'CPF/CNPJ do beneficiário PIX'
            ],
            [
                'chave' => 'pix_banco',
                'valor' => env('PIX_BANCO', ''),
                'tipo' => 'text',
                'descricao' => 'Código do banco para PIX'
            ],

            // Configurações de Doação
            [
                'chave' => 'doacao_valor_minimo',
                'valor' => env('DOACAO_VALOR_MINIMO', '1.00'),
                'tipo' => 'number',
                'descricao' => 'Valor mínimo para doações'
            ],
            [
                'chave' => 'doacao_valor_maximo',
                'valor' => env('DOACAO_VALOR_MAXIMO', '10000.00'),
                'tipo' => 'number',
                'descricao' => 'Valor máximo para doações'
            ],
            [
                'chave' => 'doacao_moeda',
                'valor' => env('DOACAO_MOEDA', 'BRL'),
                'tipo' => 'text',
                'descricao' => 'Moeda padrão para doações'
            ],
            [
                'chave' => 'doacao_taxa_stripe',
                'valor' => env('DOACAO_TAXA_STRIPE', '2.9'),
                'tipo' => 'number',
                'descricao' => 'Taxa do Stripe em porcentagem'
            ],
            [
                'chave' => 'doacao_taxa_mercadopago',
                'valor' => env('DOACAO_TAXA_MERCADOPAGO', '3.99'),
                'tipo' => 'number',
                'descricao' => 'Taxa do Mercado Pago em porcentagem'
            ],

            // Configurações de Notificação
            [
                'chave' => 'notificacao_email_doacao',
                'valor' => env('NOTIFICACAO_EMAIL_DOACAO', 'true'),
                'tipo' => 'boolean',
                'descricao' => 'Enviar notificação por email para doações'
            ],
            [
                'chave' => 'notificacao_sms_doacao',
                'valor' => env('NOTIFICACAO_SMS_DOACAO', 'false'),
                'tipo' => 'boolean',
                'descricao' => 'Enviar notificação por SMS para doações'
            ],

            // Configurações de Segurança
            [
                'chave' => 'pagamento_ssl_obrigatorio',
                'valor' => env('PAGAMENTO_SSL_OBRIGATORIO', 'true'),
                'tipo' => 'boolean',
                'descricao' => 'Obrigar SSL para pagamentos'
            ],
            [
                'chave' => 'pagamento_timeout',
                'valor' => env('PAGAMENTO_TIMEOUT', '300'),
                'tipo' => 'number',
                'descricao' => 'Timeout para pagamentos em segundos'
            ],

            // Configurações de Teste
            [
                'chave' => 'stripe_test_mode',
                'valor' => env('STRIPE_TEST_MODE', 'true'),
                'tipo' => 'boolean',
                'descricao' => 'Modo de teste do Stripe'
            ],
            [
                'chave' => 'mercadopago_test_mode',
                'valor' => env('MERCADOPAGO_TEST_MODE', 'true'),
                'tipo' => 'boolean',
                'descricao' => 'Modo de teste do Mercado Pago'
            ],
        ];

        foreach ($configuracoes as $config) {
            Configuracao::updateOrCreate(
                ['chave' => $config['chave']],
                $config
            );
        }

        $this->command->info('Configurações de gateway criadas com sucesso!');
    }
} 