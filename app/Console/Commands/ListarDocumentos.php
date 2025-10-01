<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentoDeclaracaoAnual;

class ListarDocumentos extends Command
{
    protected $signature = 'documento:listar';
    protected $description = 'Listar todos os documentos de declaração anual';

    public function handle()
    {
        $documentos = DocumentoDeclaracaoAnual::all();
        
        if ($documentos->isEmpty()) {
            $this->error('Nenhum documento encontrado na tabela!');
            return 1;
        }
        
        $this->info("Encontrados {$documentos->count()} documento(s):");
        
        foreach ($documentos as $doc) {
            $this->info("ID: {$doc->id} | Hash: {$doc->hash_documento} | Status: {$doc->status}");
        }
        
        return 0;
    }
} 