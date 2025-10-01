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
        Schema::create('documentos_declaracao_anual', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com a igreja
            $table->unsignedBigInteger('igreja_id');
            $table->foreign('igreja_id')->references('id')->on('igrejas')->onDelete('cascade');
            
            // Informações básicas do documento
            $table->integer('ano_exercicio');
            $table->string('tipo_documento');
            $table->string('numero_documento');
            
            // Protocolo da Receita Federal
            $table->string('protocolo_receita')->unique();
            
            // Datas
            $table->datetime('data_emissao');
            $table->datetime('data_vencimento')->nullable();
            
            // Valores financeiros
            $table->decimal('valor_total', 15, 2);
            $table->decimal('valor_doacoes', 15, 2)->default(0);
            $table->decimal('valor_dizimos', 15, 2)->default(0);
            $table->decimal('valor_outros', 15, 2)->default(0);
            
            // Validação e segurança
            $table->string('hash_documento', 64);
            $table->text('qr_code')->nullable();
            $table->string('codigo_barras')->nullable();
            $table->string('status')->default('PENDENTE');
            
            // Certificação digital
            $table->string('certificado_digital', 64)->nullable();
            $table->string('assinatura_digital', 64)->nullable();
            
            // Validação
            $table->datetime('validado_em')->nullable();
            $table->unsignedBigInteger('validado_por')->nullable();
            $table->foreign('validado_por')->references('id')->on('users')->onDelete('set null');
            
            // Arquivos
            $table->string('arquivo_comprovante')->nullable();
            
            // Observações
            $table->text('observacoes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para performance
            $table->index(['igreja_id', 'ano_exercicio']);
            $table->index(['tipo_documento', 'status']);
            $table->index(['protocolo_receita']);
            $table->index(['data_emissao']);
            $table->index(['data_vencimento']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_declaracao_anual');
    }
}; 