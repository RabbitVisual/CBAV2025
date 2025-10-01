<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('igrejas', function (Blueprint $table) {
            $table->id();
            
            // Informações básicas
            $table->string('nome');
            $table->string('cnpj')->unique();
            $table->string('endereco');
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            
            // Responsável
            $table->string('pastor_responsavel')->nullable();
            $table->date('data_fundacao')->nullable();
            
            // Classificação
            $table->string('tipo_entidade')->default('IGREJA');
            $table->string('situacao_cadastral')->default('ATIVA');
            
            // Inscrições
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_municipal')->nullable();
            
            // Certificação digital
            $table->string('certificado_digital', 64)->nullable();
            
            // Observações
            $table->text('observacoes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para performance
            $table->index(['cnpj']);
            $table->index(['tipo_entidade']);
            $table->index(['situacao_cadastral']);
            $table->index(['estado']);
            $table->index(['cidade']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('igrejas');
    }
}; 