<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentoDeclaracaoAnual;

class VerificarDocumento extends Command
{
    protected $signature = 'documento:verificar {hash}';
    protected $description = 'Verificar se um documento existe e testar validação';

    public function handle()
    {
        $hash = $this->argument('hash');
        
        $this->info("Verificando documento com hash: {$hash}");
        
        $documento = DocumentoDeclaracaoAnual::where('hash_documento', $hash)->first();
        
        if (!$documento) {
            $this->error('Documento NÃO encontrado!');
            return 1;
        }
        
        $this->info('Documento encontrado!');
        $this->info("ID: {$documento->id}");
        $this->info("Status: {$documento->status}");
        $this->info("Protocolo: {$documento->protocolo_receita}");
        $this->info("Número: {$documento->numero_documento}");
        $this->info("Valor: {$documento->valor_total}");
        $this->info("Data Emissão: {$documento->data_emissao}");
        
        // Testar validação
        $valido = $documento->validar();
        $this->info("Validação: " . ($valido ? 'VÁLIDO' : 'INVÁLIDO'));
        
        // Verificar se está vencido
        $vencido = $documento->isVencido();
        $this->info("Vencido: " . ($vencido ? 'SIM' : 'NÃO'));
        
        if ($documento->data_vencimento) {
            $this->info("Data Vencimento: {$documento->data_vencimento}");
        }
        
        // Verificar campos obrigatórios
        $camposObrigatorios = [
            'protocolo_receita',
            'numero_documento',
            'valor_total',
            'data_emissao',
            'hash_documento'
        ];
        
        $this->info("\nVerificação de campos obrigatórios:");
        foreach ($camposObrigatorios as $campo) {
            $valor = $documento->$campo;
            $status = !empty($valor) ? '✓' : '✗';
            $this->info("  {$status} {$campo}: " . ($valor ?: 'VAZIO'));
        }
        
        return 0;
    }
} 