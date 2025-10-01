<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Igreja;
use App\Models\Configuracao;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏛️ Criando dados iniciais da igreja...');

        // Criar igreja padrão
        $igreja = Igreja::updateOrCreate(
            ['id' => 1],
            [
                'nome' => 'Congregação Batista Avenida',
                'cnpj' => '12.345.678/0001-90',
                'endereco' => 'Rua da Avenida, 123',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01234-567',
                'telefone' => '(11) 99999-9999',
                'email' => 'contato@cbav.com',
                'pastor_responsavel' => 'Pr. João Silva',
                'data_fundacao' => '1990-01-15',
                'tipo_entidade' => 'IGREJA',
                'situacao_cadastral' => 'ATIVA',
                'inscricao_estadual' => '123456789',
                'inscricao_municipal' => '987654321',
                'observacoes' => 'Igreja demonstrativa para o sistema CBAV'
            ]
        );

        // Configurações específicas da igreja
        $configuracoesIgreja = [
            ['chave' => 'igreja_id', 'valor' => $igreja->id, 'tipo' => 'integer', 'descricao' => 'ID da igreja atual'],
            ['chave' => 'igreja_nome_completo', 'valor' => $igreja->nome, 'tipo' => 'string', 'descricao' => 'Nome completo da igreja'],
            ['chave' => 'igreja_cnpj', 'valor' => $igreja->cnpj, 'tipo' => 'string', 'descricao' => 'CNPJ da igreja'],
            ['chave' => 'igreja_endereco_completo', 'valor' => $igreja->endereco . ' - ' . $igreja->cidade . '/' . $igreja->estado . ' - CEP: ' . $igreja->cep, 'tipo' => 'string', 'descricao' => 'Endereço completo da igreja'],
            ['chave' => 'igreja_telefone_principal', 'valor' => $igreja->telefone, 'tipo' => 'string', 'descricao' => 'Telefone principal da igreja'],
            ['chave' => 'igreja_email_principal', 'valor' => $igreja->email, 'tipo' => 'string', 'descricao' => 'Email principal da igreja'],
            ['chave' => 'pastor_nome', 'valor' => $igreja->pastor_responsavel, 'tipo' => 'string', 'descricao' => 'Nome do pastor'],
            ['chave' => 'pastor_telefone', 'valor' => '(11) 99999-8888', 'tipo' => 'string', 'descricao' => 'Telefone do pastor'],
            ['chave' => 'pastor_email', 'valor' => 'pastor@cbav.com', 'tipo' => 'string', 'descricao' => 'Email do pastor'],
            ['chave' => 'tesoureiro_nome', 'valor' => 'Maria Santos', 'tipo' => 'string', 'descricao' => 'Nome do tesoureiro'],
            ['chave' => 'tesoureiro_telefone', 'valor' => '(11) 99999-7777', 'tipo' => 'string', 'descricao' => 'Telefone do tesoureiro'],
            ['chave' => 'tesoureiro_email', 'valor' => 'tesoureiro@cbav.com', 'tipo' => 'string', 'descricao' => 'Email do tesoureiro'],
            ['chave' => 'secretario_nome', 'valor' => 'Pedro Oliveira', 'tipo' => 'string', 'descricao' => 'Nome do secretário'],
            ['chave' => 'secretario_telefone', 'valor' => '(11) 99999-6666', 'tipo' => 'string', 'descricao' => 'Telefone do secretário'],
            ['chave' => 'secretario_email', 'valor' => 'secretario@cbav.com', 'tipo' => 'string', 'descricao' => 'Email do secretário'],
            ['chave' => 'igreja_data_fundacao', 'valor' => $igreja->data_fundacao, 'tipo' => 'date', 'descricao' => 'Data de fundação da igreja'],
            ['chave' => 'igreja_denominacao', 'valor' => 'Batista', 'tipo' => 'string', 'descricao' => 'Denominação da igreja'],
            ['chave' => 'igreja_convencao', 'valor' => 'Convenção Batista Brasileira', 'tipo' => 'string', 'descricao' => 'Convenção da igreja'],
            ['chave' => 'igreja_status', 'valor' => $igreja->situacao_cadastral, 'tipo' => 'string', 'descricao' => 'Status da igreja'],
            ['chave' => 'igreja_website', 'valor' => 'https://cbav.com', 'tipo' => 'string', 'descricao' => 'Website da igreja'],
        ];

        foreach ($configuracoesIgreja as $config) {
            Configuracao::updateOrCreate(
                ['chave' => $config['chave']],
                $config
            );
        }

        $this->command->info('✅ Dados iniciais da igreja criados');
        $this->command->info("🏛️ Igreja: {$igreja->nome}");
        $this->command->info("📍 Endereço: {$igreja->endereco} - {$igreja->cidade}/{$igreja->estado}");
        $this->command->info("📞 Telefone: {$igreja->telefone}");
        $this->command->info("📧 Email: {$igreja->email}");
        $this->command->info("👨‍💼 Pastor: {$igreja->pastor_responsavel}");
        $this->command->info("💰 Tesoureiro: Maria Santos");
        $this->command->info("📝 Secretário: Pedro Oliveira");
    }
} 