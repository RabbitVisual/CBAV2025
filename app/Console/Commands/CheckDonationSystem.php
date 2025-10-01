<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Transacao, Pagamento, Configuracao, Campanha};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckDonationSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:check {--fix : Tentar corrigir problemas encontrados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar integridade do sistema de doações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando Sistema de Doações...');
        
        $fix = $this->option('fix');
        $problems = [];
        
        // 1. Verificar estrutura do banco de dados
        $this->info('📊 Verificando estrutura do banco de dados...');
        $problems = array_merge($problems, $this->checkDatabaseStructure());
        
        // 2. Verificar configurações dos gateways
        $this->info('⚙️ Verificando configurações dos gateways...');
        $problems = array_merge($problems, $this->checkGatewayConfigurations());
        
        // 3. Verificar integridade dos dados
        $this->info('📋 Verificando integridade dos dados...');
        $problems = array_merge($problems, $this->checkDataIntegrity());
        
        // 4. Verificar rotas e controllers
        $this->info('🛣️ Verificando rotas e controllers...');
        $problems = array_merge($problems, $this->checkRoutesAndControllers());
        
        // 5. Verificar views
        $this->info('👁️ Verificando views...');
        $problems = array_merge($problems, $this->checkViews());
        
        // Resumo
        $this->info('');
        $this->info('📋 RESUMO DA VERIFICAÇÃO:');
        $this->info('');
        
        if (empty($problems)) {
            $this->info('✅ Sistema de doações está funcionando corretamente!');
            $this->info('🎉 Todos os componentes estão operacionais.');
        } else {
            $this->error('❌ Encontrados ' . count($problems) . ' problema(s):');
            foreach ($problems as $index => $problem) {
                $this->error('  ' . ($index + 1) . '. ' . $problem);
            }
            
            if ($fix) {
                $this->info('');
                $this->info('🔧 Tentando corrigir problemas...');
                $this->fixProblems($problems);
            } else {
                $this->info('');
                $this->info('💡 Execute com --fix para tentar corrigir automaticamente.');
            }
        }
    }
    
    private function checkDatabaseStructure()
    {
        $problems = [];
        
        // Verificar tabela transacoes
        if (!Schema::hasTable('transacoes')) {
            $problems[] = 'Tabela transacoes não existe';
        } else {
            $columns = Schema::getColumnListing('transacoes');
            $requiredColumns = ['id', 'membro_id', 'campanha_id', 'tipo', 'valor', 'descricao', 'data', 'status', 'dados_extras'];
            
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    $problems[] = "Coluna '{$column}' não existe na tabela transacoes";
                }
            }
        }
        
        // Verificar tabela pagamentos
        if (!Schema::hasTable('pagamentos')) {
            $problems[] = 'Tabela pagamentos não existe';
        } else {
            $columns = Schema::getColumnListing('pagamentos');
            $requiredColumns = ['id', 'transacao_id', 'gateway', 'gateway_id', 'gateway_status', 'valor', 'moeda', 'dados_gateway'];
            
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    $problems[] = "Coluna '{$column}' não existe na tabela pagamentos";
                }
            }
        }
        
        return $problems;
    }
    
    private function checkGatewayConfigurations()
    {
        $problems = [];
        
        // Verificar Stripe
        $stripeKey = Configuracao::get('stripe_key');
        $stripeSecret = Configuracao::get('stripe_secret');
        $stripeEnabled = Configuracao::get('stripe_enabled');
        
        if ($stripeEnabled === '1' && (!$stripeKey || !$stripeSecret)) {
            $problems[] = 'Stripe habilitado mas chaves não configuradas';
        }
        
        // Verificar Mercado Pago
        $mpKey = Configuracao::get('mercadopago_key');
        $mpToken = Configuracao::get('mercadopago_token');
        $mpEnabled = Configuracao::get('mercadopago_enabled');
        
        if ($mpEnabled === '1' && (!$mpKey || !$mpToken)) {
            $problems[] = 'Mercado Pago habilitado mas chaves não configuradas';
        }
        
        // Verificar PIX
        $pixChave = Configuracao::get('pix_chave');
        $pixBeneficiario = Configuracao::get('pix_beneficiario');
        $pixEnabled = Configuracao::get('pix_enabled');
        
        if ($pixEnabled === '1' && (!$pixChave || !$pixBeneficiario)) {
            $problems[] = 'PIX habilitado mas chaves não configuradas';
        }
        
        // Verificar se pelo menos um gateway está configurado
        if (!$stripeEnabled && !$mpEnabled && !$pixEnabled) {
            $problems[] = 'Nenhum gateway de pagamento está habilitado';
        }
        
        return $problems;
    }
    
    private function checkDataIntegrity()
    {
        $problems = [];
        
        // Verificar transações órfãs (sem membro nem dados extras)
        $orphanTransactions = Transacao::whereNull('membro_id')
            ->whereNull('dados_extras')
            ->count();
        
        if ($orphanTransactions > 0) {
            $problems[] = "Encontradas {$orphanTransactions} transações órfãs (sem membro nem dados extras)";
        }
        
        // Verificar pagamentos sem transação
        $orphanPayments = Pagamento::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('transacoes')
                  ->whereRaw('transacoes.id = pagamentos.transacao_id');
        })->count();
        
        if ($orphanPayments > 0) {
            $problems[] = "Encontrados {$orphanPayments} pagamentos órfãos (sem transação)";
        }
        
        // Verificar campanhas sem valor arrecadado
        $campanhasSemArrecadacao = Campanha::where('ativo', true)
            ->whereNull('valor_arrecadado')
            ->count();
        
        if ($campanhasSemArrecadacao > 0) {
            $problems[] = "Encontradas {$campanhasSemArrecadacao} campanhas ativas sem valor_arrecadado definido";
        }
        
        return $problems;
    }
    
    private function checkRoutesAndControllers()
    {
        $problems = [];
        
        // Verificar se os controllers existem
        $controllers = [
            'App\Http\Controllers\PublicDonationController',
            'App\Http\Controllers\Member\DonationController',
            'App\Http\Controllers\GatewayController',
            'App\Http\Controllers\PagamentoController'
        ];
        
        foreach ($controllers as $controller) {
            if (!class_exists($controller)) {
                $problems[] = "Controller {$controller} não existe";
            }
        }
        
        // Verificar se os métodos existem
        if (class_exists('App\Http\Controllers\PublicDonationController')) {
            $methods = ['index', 'process', 'confirmation'];
            foreach ($methods as $method) {
                if (!method_exists('App\Http\Controllers\PublicDonationController', $method)) {
                    $problems[] = "Método {$method} não existe em PublicDonationController";
                }
            }
        }
        
        return $problems;
    }
    
    private function checkViews()
    {
        $problems = [];
        
        $views = [
            'doacao.index',
            'doacao.confirmacao',
            'gateways.stripe',
            'gateways.mercadopago',
            'gateways.pix',
            'member.donations.index',
            'member.donations.donate'
        ];
        
        foreach ($views as $view) {
            if (!view()->exists($view)) {
                $problems[] = "View {$view} não existe";
            }
        }
        
        return $problems;
    }
    
    private function fixProblems($problems)
    {
        $fixed = 0;
        
        foreach ($problems as $problem) {
            if (str_contains($problem, 'valor_arrecadado')) {
                // Corrigir campanhas sem valor_arrecadado
                Campanha::where('ativo', true)
                    ->whereNull('valor_arrecadado')
                    ->update(['valor_arrecadado' => 0]);
                $fixed++;
                $this->info("  ✅ Corrigido: Campanhas sem valor_arrecadado");
            }
            
            if (str_contains($problem, 'dados_extras')) {
                // Corrigir transações sem dados_extras
                Transacao::whereNull('dados_extras')
                    ->update(['dados_extras' => '{}']);
                $fixed++;
                $this->info("  ✅ Corrigido: Transações sem dados_extras");
            }
        }
        
        if ($fixed > 0) {
            $this->info("✅ Corrigidos {$fixed} problema(s) automaticamente");
        } else {
            $this->warn("⚠️ Nenhum problema pode ser corrigido automaticamente");
        }
    }
} 