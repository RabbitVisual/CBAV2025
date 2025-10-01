<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentoDeclaracaoAnual;
use App\Models\Igreja;
use App\Models\User;

class CriarDocumentoEspecifico extends Command
{
    protected $signature = 'documento:criar-especifico';
    protected $description = 'Criar documento com hash específico para teste';

    public function handle()
    {
        // Criar igreja de teste se não existir
        $igreja = Igreja::firstOrCreate(
            ['cnpj' => '98.765.432/0001-02'],
            [
                'nome' => 'Igreja Presbiteriana do Brasil',
                'endereco' => 'Av. Principal, 456',
                'cidade' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'cep' => '20000-000',
                'telefone' => '(21) 88888-8888',
                'email' => 'contato@igrejapresbiteriana.com',
                'pastor_responsavel' => 'Pr. Pedro Santos',
                'tipo_entidade' => 'IGREJA',
                'situacao_cadastral' => 'ATIVA'
            ]
        );

        // Criar usuário de teste se não existir
        $user = User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'name' => 'Reinan Rodrigues',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );

        // Criar documento com hash específico
        $documento = new DocumentoDeclaracaoAnual([
            'igreja_id' => $igreja->id,
            'ano_exercicio' => 2022,
            'tipo_documento' => 'COMPROVANTE_DOACOES',
            'numero_documento' => 'DOA20220002',
            'protocolo_receita' => 'RF2022COM202508041931101269',
            'data_emissao' => '2022-12-31',
            'data_vencimento' => '2023-04-30',
            'valor_total' => 21181.00,
            'valor_doacoes' => 24313.00,
            'valor_dizimos' => 0,
            'valor_outros' => 0,
            'status' => 'VALIDADO',
            'observacoes' => 'Comprovante de doações do exercício 2022',
            'validado_em' => now(),
            'validado_por' => $user->id,
            'hash_documento' => 'bc4411fd209b83652c2ab049bc47f2c3824b1120094e1b9bffd885c98a717a01'
        ]);

        $documento->save();

        // Gerar outros campos automaticamente
        $documento->qr_code = $documento->gerarQRCode();
        $documento->codigo_barras = $documento->gerarCodigoBarras();
        $documento->certificado_digital = $documento->gerarCertificadoDigital();
        $documento->assinatura_digital = $documento->gerarAssinaturaDigital();
        $documento->save();

        $this->info('Documento específico criado com sucesso!');
        $this->info('Hash: ' . $documento->hash_documento);
        $this->info('Protocolo: ' . $documento->protocolo_receita);
        $this->info('Status: ' . $documento->status);
        $this->info('URL de validação: http://127.0.0.1:8000/validacao/declaracao-anual/' . $documento->hash_documento);

        return 0;
    }
} 