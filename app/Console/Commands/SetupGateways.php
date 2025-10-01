<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracao;

class SetupGateways extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateways:setup {--force : Forçar configuração mesmo se já existir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar gateways de pagamento (Stripe, Mercado Pago, PIX)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Configurando Gateways de Pagamento...');
        
        $force = $this->option('force');
        
        // Configurações Stripe
        $this->setupStripe($force);
        
        // Configurações Mercado Pago
        $this->setupMercadoPago($force);
        
        // Configurações PIX
        $this->setupPix($force);
        
        $this->info('✅ Configuração dos gateways concluída!');
        $this->info('📋 Verifique as configurações no painel administrativo.');
    }
    
    private function setupStripe($force = false)
    {
        $this->info('💳 Configurando Stripe...');
        
        $configs = [
            'stripe_key' => [
                'valor' => env('STRIPE_KEY', ''),
                'tipo' => 'text',
                'descricao' => 'Chave pública do Stripe'
            ],
            'stripe_secret' => [
                'valor' => env('STRIPE_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do Stripe'
            ],
            'stripe_webhook_secret' => [
                'valor' => env('STRIPE_WEBHOOK_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do webhook do Stripe'
            ],
            'stripe_enabled' => [
                'valor' => env('STRIPE_KEY') ? '1' : '0',
                'tipo' => 'boolean',
                'descricao' => 'Habilitar Stripe'
            ]
        ];
        
        foreach ($configs as $chave => $config) {
            $this->createOrUpdateConfig($chave, $config, $force);
        }
    }
    
    private function setupMercadoPago($force = false)
    {
        $this->info('🛒 Configurando Mercado Pago...');
        
        $configs = [
            'mercadopago_key' => [
                'valor' => env('MERCADOPAGO_PUBLIC_KEY', ''),
                'tipo' => 'text',
                'descricao' => 'Chave pública do Mercado Pago'
            ],
            'mercadopago_token' => [
                'valor' => env('MERCADOPAGO_ACCESS_TOKEN', ''),
                'tipo' => 'password',
                'descricao' => 'Token de acesso do Mercado Pago'
            ],
            'mercadopago_webhook_secret' => [
                'valor' => env('MERCADOPAGO_WEBHOOK_SECRET', ''),
                'tipo' => 'password',
                'descricao' => 'Chave secreta do webhook do Mercado Pago'
            ],
            'mercadopago_enabled' => [
                'valor' => env('MERCADOPAGO_PUBLIC_KEY') ? '1' : '0',
                'tipo' => 'boolean',
                'descricao' => 'Habilitar Mercado Pago'
            ]
        ];
        
        foreach ($configs as $chave => $config) {
            $this->createOrUpdateConfig($chave, $config, $force);
        }
    }
    
    private function setupPix($force = false)
    {
        $this->info('📱 Configurando PIX...');
        
        $configs = [
            'pix_chave' => [
                'valor' => env('PIX_CHAVE', ''),
                'tipo' => 'text',
                'descricao' => 'Chave PIX'
            ],
            'pix_beneficiario' => [
                'valor' => env('PIX_BENEFICIARIO_NOME', ''),
                'tipo' => 'text',
                'descricao' => 'Nome do beneficiário PIX'
            ],
            'pix_beneficiario_cpf' => [
                'valor' => env('PIX_BENEFICIARIO_CPF_CNPJ', ''),
                'tipo' => 'text',
                'descricao' => 'CPF/CNPJ do beneficiário PIX'
            ],
            'pix_banco' => [
                'valor' => env('PIX_BANCO', ''),
                'tipo' => 'text',
                'descricao' => 'Código do banco PIX'
            ],
            'pix_enabled' => [
                'valor' => env('PIX_CHAVE') ? '1' : '0',
                'tipo' => 'boolean',
                'descricao' => 'Habilitar PIX'
            ]
        ];
        
        foreach ($configs as $chave => $config) {
            $this->createOrUpdateConfig($chave, $config, $force);
        }
    }
    
    private function createOrUpdateConfig($chave, $config, $force = false)
    {
        $existing = Configuracao::where('chave', $chave)->first();
        
        if ($existing && !$force) {
            $this->line("  ⚠️  Configuração '{$chave}' já existe. Use --force para sobrescrever.");
            return;
        }
        
        if ($existing) {
            $existing->update([
                'valor' => $config['valor'],
                'tipo' => $config['tipo'],
                'descricao' => $config['descricao']
            ]);
            $this->line("  🔄 Atualizada configuração: {$chave}");
        } else {
            Configuracao::create([
                'chave' => $chave,
                'valor' => $config['valor'],
                'tipo' => $config['tipo'],
                'descricao' => $config['descricao']
            ]);
            $this->line("  ➕ Criada configuração: {$chave}");
        }
    }
} 