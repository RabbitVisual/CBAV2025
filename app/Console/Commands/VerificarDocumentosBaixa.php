<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{DocumentoBaixa, Transacao};
use Illuminate\Support\Facades\Log;

class VerificarDocumentosBaixa extends Command
{
    protected $signature = 'documentos:verificar {--corrigir : Corrigir problemas encontrados} {--relatorio : Gerar relatório detalhado}';
    protected $description = 'Verificar e corrigir documentos de baixa automaticamente';

    public function handle()
    {
        $this->info('🔍 Verificando documentos de baixa...');
        
        $problemas = [];
        $correcoes = 0;
        
        // 1. Verificar documentos sem hash
        $documentosSemHash = DocumentoBaixa::whereNull('hash_documento')->get();
        if ($documentosSemHash->count() > 0) {
            $problemas[] = "{$documentosSemHash->count()} documentos sem hash de validação";
            
            if ($this->option('corrigir')) {
                foreach ($documentosSemHash as $documento) {
                    $documento->gerarHash();
                    $documento->save();
                    $correcoes++;
                }
                $this->info("✅ Hash gerado para {$correcoes} documentos");
            }
        }
        
        // 2. Verificar documentos sem protocolo
        $documentosSemProtocolo = DocumentoBaixa::whereNull('protocolo_receita')->get();
        if ($documentosSemProtocolo->count() > 0) {
            $problemas[] = "{$documentosSemProtocolo->count()} documentos sem protocolo RF";
            
            if ($this->option('corrigir')) {
                foreach ($documentosSemProtocolo as $documento) {
                    $documento->gerarProtocolo();
                    $documento->save();
                    $correcoes++;
                }
                $this->info("✅ Protocolo gerado para {$correcoes} documentos");
            }
        }
        
        // 3. Verificar documentos vencidos
        $documentosVencidos = DocumentoBaixa::vencidos()->get();
        if ($documentosVencidos->count() > 0) {
            $problemas[] = "{$documentosVencidos->count()} documentos vencidos";
            
            foreach ($documentosVencidos as $documento) {
                $multaJuros = $documento->calcularMultaJuros();
                if ($multaJuros > 0) {
                    $problemas[] = "Documento #{$documento->id} tem multa/juros de R$ " . number_format($multaJuros, 2, ',', '.');
                }
            }
        }
        
        // 4. Verificar transações órfãs (sem documento de baixa)
        $transacoesOrfas = Transacao::where('status', 'confirmado')
            ->whereDoesntHave('documentoBaixa')
            ->get();
        
        if ($transacoesOrfas->count() > 0) {
            $problemas[] = "{$transacoesOrfas->count()} transações confirmadas sem documento de baixa";
        }
        
        // 5. Verificar documentos com formato inválido
        $documentosInvalidos = DocumentoBaixa::all()->filter(function($doc) {
            return !$doc->validarFormatoDocumento();
        });
        
        if ($documentosInvalidos->count() > 0) {
            $problemas[] = "{$documentosInvalidos->count()} documentos com formato inválido";
        }
        
        // 6. Verificar documentos duplicados
        $duplicados = DocumentoBaixa::select('transacao_id')
            ->groupBy('transacao_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
            
        if ($duplicados->count() > 0) {
            $problemas[] = "{$duplicados->count()} transações com múltiplos documentos de baixa";
        }
        
        // Relatório
        if ($this->option('relatorio') || empty($problemas)) {
            $this->info("\n📊 RELATÓRIO DETALHADO:");
            $this->info("Total de documentos: " . DocumentoBaixa::count());
            $this->info("Documentos pendentes: " . DocumentoBaixa::where('status', 'PENDENTE')->count());
            $this->info("Documentos pagos: " . DocumentoBaixa::where('status', 'PAGO')->count());
            $this->info("Documentos vencidos: " . DocumentoBaixa::vencidos()->count());
            $this->info("Valor total pendente: R$ " . number_format(DocumentoBaixa::where('status', 'PENDENTE')->sum('valor_documento'), 2, ',', '.'));
            $this->info("Valor total pago: R$ " . number_format(DocumentoBaixa::where('status', 'PAGO')->sum('valor_pago'), 2, ',', '.'));
            $this->info("Multa/juros total: R$ " . number_format($documentosVencidos->sum(function($doc) {
                return $doc->calcularMultaJuros();
            }), 2, ',', '.'));
            
            // Estatísticas por tipo
            $this->info("\n📋 POR TIPO DE DOCUMENTO:");
            $tipos = DocumentoBaixa::selectRaw('tipo_documento, COUNT(*) as total')
                ->groupBy('tipo_documento')
                ->get();
                
            foreach ($tipos as $tipo) {
                $this->info("- {$tipo->tipo_documento}: {$tipo->total}");
            }
        }
        
        // Resultado final
        if (empty($problemas)) {
            $this->info("✅ Todos os documentos estão em ordem!");
        } else {
            $this->warn("\n⚠️ PROBLEMAS ENCONTRADOS:");
            foreach ($problemas as $problema) {
                $this->warn("- {$problema}");
            }
            
            if (!$this->option('corrigir')) {
                $this->info("\n💡 Use --corrigir para corrigir automaticamente os problemas encontrados");
            }
        }
        
        // Log da verificação
        Log::info('Verificação de documentos de baixa concluída', [
            'problemas_encontrados' => count($problemas),
            'correcoes_realizadas' => $correcoes,
            'usuario' => 'Sistema'
        ]);
        
        return 0;
    }
} 