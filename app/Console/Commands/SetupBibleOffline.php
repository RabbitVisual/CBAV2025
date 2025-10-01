<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SetupBibleOffline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bible:setup-offline {--force : Forçar download mesmo se já existir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar Bíblia offline com dados JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Configurando Bíblia offline...');
        $this->newLine();

        $versions = [
            'almeida_ra' => [
                'name' => 'Almeida Revista e Atualizada',
                'url' => 'https://raw.githubusercontent.com/bibleapi/bibleapi-bibles-json/master/ara.json',
                'file' => 'almeida_ra.json'
            ],
            'almeida_rc' => [
                'name' => 'Almeida Revista e Corrigida',
                'url' => 'https://raw.githubusercontent.com/bibleapi/bibleapi-bibles-json/master/arc.json',
                'file' => 'almeida_rc.json'
            ],
            'blivre' => [
                'name' => 'Bíblia Livre',
                'url' => 'https://raw.githubusercontent.com/bibleapi/bibleapi-bibles-json/master/blivre.json',
                'file' => 'blivre.json'
            ]
        ];

        $storagePath = 'bible/offline';
        $successCount = 0;
        $errorCount = 0;

        foreach ($versions as $key => $version) {
            $this->info("📖 Processando: {$version['name']}");
            
            $filePath = $storagePath . '/' . $version['file'];
            
            // Verificar se já existe
            if (Storage::exists($filePath) && !$this->option('force')) {
                $this->warn("   ⚠️ Arquivo já existe: {$version['file']}");
                $successCount++;
                continue;
            }

            try {
                $this->info("   📥 Baixando {$version['name']}...");
                
                // Tentar baixar do repositório
                $response = Http::timeout(30)->get($version['url']);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Validar estrutura dos dados
                    if ($this->validateBibleData($data)) {
                        Storage::put($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        
                        $size = Storage::size($filePath);
                        $sizeMB = round($size / 1024 / 1024, 2);
                        
                        $this->info("   ✅ {$version['name']} baixado com sucesso ({$sizeMB} MB)");
                        $successCount++;
                    } else {
                        $this->error("   ❌ Dados inválidos para {$version['name']}");
                        $errorCount++;
                    }
                } else {
                    $this->error("   ❌ Erro ao baixar {$version['name']}: HTTP {$response->status()}");
                    $errorCount++;
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ Erro ao processar {$version['name']}: " . $e->getMessage());
                $errorCount++;
            }
            
            $this->newLine();
        }

        // Criar arquivo de índice
        $this->createIndexFile($versions);

        $this->newLine();
        $this->info('📊 Resumo:');
        $this->info("   ✅ Sucessos: {$successCount}");
        $this->info("   ❌ Erros: {$errorCount}");
        
        if ($successCount > 0) {
            $this->info('🎉 Bíblia offline configurada com sucesso!');
            $this->info('💡 Use: php artisan bible:status para verificar o status');
        } else {
            $this->error('❌ Nenhuma versão foi baixada com sucesso');
        }

        return $successCount > 0 ? 0 : 1;
    }

    /**
     * Validar estrutura dos dados da Bíblia
     */
    private function validateBibleData($data)
    {
        // Verificar se é um array
        if (!is_array($data)) {
            return false;
        }

        // Verificar se tem a estrutura básica
        if (!isset($data['verses']) || !is_array($data['verses'])) {
            return false;
        }

        // Verificar se tem pelo menos alguns versículos
        if (count($data['verses']) < 100) {
            return false;
        }

        // Verificar estrutura dos versículos
        foreach (array_slice($data['verses'], 0, 10) as $verse) {
            if (!isset($verse['text']) || !isset($verse['book_name']) || 
                !isset($verse['chapter']) || !isset($verse['verse'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Criar arquivo de índice
     */
    private function createIndexFile($versions)
    {
        $this->info('📝 Criando arquivo de índice...');
        
        $index = [
            'created_at' => now()->toISOString(),
            'versions' => $versions,
            'total_versions' => count($versions),
            'storage_path' => 'bible/offline'
        ];

        Storage::put('bible/offline/index.json', json_encode($index, JSON_PRETTY_PRINT));
        $this->info('   ✅ Arquivo de índice criado');
    }
} 