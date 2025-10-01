<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Igreja;
use App\Models\DocumentoDeclaracaoAnual;
use App\Models\User;
use Carbon\Carbon;

class DocumentoDeclaracaoAnualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar igrejas de exemplo se não existirem
        $igrejas = [
            [
                'nome' => 'Igreja Batista da Graça',
                'cnpj' => '12345678000101',
                'endereco' => 'Rua das Flores, 123',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567',
                'telefone' => '(11) 99999-9999',
                'email' => 'contato@igrejabatista.com',
                'pastor_responsavel' => 'Pr. João Silva',
                'data_fundacao' => '1990-01-15',
                'tipo_entidade' => 'IGREJA',
                'situacao_cadastral' => 'ATIVA'
            ],
            [
                'nome' => 'Igreja Presbiteriana do Brasil',
                'cnpj' => '98765432000102',
                'endereco' => 'Av. Principal, 456',
                'cidade' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'cep' => '20000-000',
                'telefone' => '(21) 88888-8888',
                'email' => 'contato@igrejapresbiteriana.com',
                'pastor_responsavel' => 'Pr. Pedro Santos',
                'data_fundacao' => '1985-03-20',
                'tipo_entidade' => 'IGREJA',
                'situacao_cadastral' => 'ATIVA'
            ],
            [
                'nome' => 'Igreja Metodista Wesleyana',
                'cnpj' => '45678912000103',
                'endereco' => 'Rua da Paz, 789',
                'cidade' => 'Belo Horizonte',
                'estado' => 'MG',
                'cep' => '30000-000',
                'telefone' => '(31) 77777-7777',
                'email' => 'contato@igrejametodista.com',
                'pastor_responsavel' => 'Pr. Carlos Oliveira',
                'data_fundacao' => '1995-07-10',
                'tipo_entidade' => 'IGREJA',
                'situacao_cadastral' => 'ATIVA'
            ]
        ];

        foreach ($igrejas as $igrejaData) {
            Igreja::firstOrCreate(
                ['cnpj' => $igrejaData['cnpj']],
                $igrejaData
            );
        }

        // Obter igrejas criadas
        $igrejas = Igreja::all();
        $admin = User::where('email', 'admin@cbav.com')->first();

        // Criar documentos de declaração anual de exemplo
        $tiposDocumento = array_keys(DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO);
        $status = array_keys(DocumentoDeclaracaoAnual::STATUS);

        foreach ($igrejas as $igreja) {
            // Criar documentos para diferentes anos
            for ($ano = 2022; $ano <= 2024; $ano++) {
                // Documento de declaração anual
                DocumentoDeclaracaoAnual::create([
                    'igreja_id' => $igreja->id,
                    'ano_exercicio' => $ano,
                    'tipo_documento' => 'DECLARACAO_ANUAL',
                    'numero_documento' => 'DEC' . $ano . str_pad($igreja->id, 4, '0', STR_PAD_LEFT),
                    'data_emissao' => Carbon::create($ano, 12, 31),
                    'data_vencimento' => Carbon::create($ano + 1, 4, 30),
                    'valor_total' => rand(50000, 200000),
                    'valor_doacoes' => rand(20000, 80000),
                    'valor_dizimos' => rand(25000, 100000),
                    'valor_outros' => rand(5000, 20000),
                    'status' => $status[array_rand($status)],
                    'observacoes' => 'Documento de declaração anual do exercício ' . $ano,
                    'validado_em' => rand(0, 1) ? Carbon::now() : null,
                    'validado_por' => $admin ? $admin->id : null
                ]);

                // Documento de comprovante de doações
                DocumentoDeclaracaoAnual::create([
                    'igreja_id' => $igreja->id,
                    'ano_exercicio' => $ano,
                    'tipo_documento' => 'COMPROVANTE_DOACOES',
                    'numero_documento' => 'DOA' . $ano . str_pad($igreja->id, 4, '0', STR_PAD_LEFT),
                    'data_emissao' => Carbon::create($ano, 12, 31),
                    'data_vencimento' => Carbon::create($ano + 1, 4, 30),
                    'valor_total' => rand(20000, 80000),
                    'valor_doacoes' => rand(20000, 80000),
                    'valor_dizimos' => 0,
                    'valor_outros' => 0,
                    'status' => $status[array_rand($status)],
                    'observacoes' => 'Comprovante de doações do exercício ' . $ano,
                    'validado_em' => rand(0, 1) ? Carbon::now() : null,
                    'validado_por' => $admin ? $admin->id : null
                ]);

                // Documento de certidão negativa
                DocumentoDeclaracaoAnual::create([
                    'igreja_id' => $igreja->id,
                    'ano_exercicio' => $ano,
                    'tipo_documento' => 'CERTIDAO_NEGATIVA',
                    'numero_documento' => 'CERT' . $ano . str_pad($igreja->id, 4, '0', STR_PAD_LEFT),
                    'data_emissao' => Carbon::create($ano, 12, 31),
                    'data_vencimento' => Carbon::create($ano + 1, 12, 31),
                    'valor_total' => 0,
                    'valor_doacoes' => 0,
                    'valor_dizimos' => 0,
                    'valor_outros' => 0,
                    'status' => $status[array_rand($status)],
                    'observacoes' => 'Certidão negativa de débitos do exercício ' . $ano,
                    'validado_em' => rand(0, 1) ? Carbon::now() : null,
                    'validado_por' => $admin ? $admin->id : null
                ]);
            }
        }

        $this->command->info('Documentos de declaração anual criados com sucesso!');
    }
} 