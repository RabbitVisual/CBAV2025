<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Transacao, Membro, Campanha, DocumentoBaixa};
use Illuminate\Support\Facades\DB;

class CriarDadosTesteDocumentos extends Command
{
    protected $signature = 'documentos:criar-dados-teste {--limpar : Limpar dados existentes}';
    protected $description = 'Criar dados de teste para o sistema de documentos de baixa';

    public function handle()
    {
        $this->info('🔧 Criando dados de teste para documentos de baixa...');

        if ($this->option('limpar')) {
            $this->info('🗑️ Limpando dados existentes...');
            DocumentoBaixa::truncate();
            Transacao::truncate();
        }

        // Verificar se existem membros e campanhas
        $membros = Membro::all();
        $campanhas = Campanha::all();

        if ($membros->isEmpty()) {
            $this->error('❌ Não existem membros cadastrados. Crie membros primeiro.');
            return 1;
        }

        if ($campanhas->isEmpty()) {
            $this->error('❌ Não existem campanhas cadastradas. Crie campanhas primeiro.');
            return 1;
        }

        $this->info("✅ Encontrados {$membros->count()} membros e {$campanhas->count()} campanhas");

        // Criar transações de teste
        $transacoesCriadas = 0;
        $tipos = ['dizimo', 'oferta', 'doacao'];
        $status = ['pendente', 'confirmado'];

        for ($i = 1; $i <= 20; $i++) {
            $membro = $membros->random();
            $campanha = $campanhas->random();
            $tipo = $tipos[array_rand($tipos)];
            $statusTransacao = $status[array_rand($status)];
            $valor = rand(50, 1000) + (rand(0, 99) / 100);

            $transacao = Transacao::create([
                'membro_id' => $membro->id,
                'campanha_id' => $campanha->id,
                'tipo' => $tipo,
                'valor' => $valor,
                'descricao' => "Teste de transação #{$i} - {$tipo}",
                'data' => now()->subDays(rand(1, 30)),
                'status' => $statusTransacao,
                'comprovante' => null
            ]);

            $transacoesCriadas++;
        }

        $this->info("✅ Criadas {$transacoesCriadas} transações de teste");

        // Mostrar estatísticas
        $transacoesConfirmadas = Transacao::where('status', 'confirmado')->count();
        $transacoesPendentes = Transacao::where('status', 'pendente')->count();
        $valorTotal = Transacao::sum('valor');

        $this->info("\n📊 ESTATÍSTICAS:");
        $this->info("Total de transações: " . Transacao::count());
        $this->info("Transações confirmadas: {$transacoesConfirmadas}");
        $this->info("Transações pendentes: {$transacoesPendentes}");
        $this->info("Valor total: R$ " . number_format($valorTotal, 2, ',', '.'));

        // Mostrar transações confirmadas disponíveis para documentos
        $transacoesDisponiveis = Transacao::where('status', 'confirmado')
            ->whereDoesntHave('documentoBaixa')
            ->count();

        $this->info("Transações disponíveis para documentos: {$transacoesDisponiveis}");

        $this->info("\n🎯 Agora você pode:");
        $this->info("1. Acessar /admin/finance/documentos");
        $this->info("2. Clicar em 'Criar Documento'");
        $this->info("3. Selecionar uma transação confirmada");
        $this->info("4. Preencher os dados do documento");

        return 0;
    }
} 