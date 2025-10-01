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
        Schema::create('documentos_baixa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transacao_id')->constrained('transacoes')->onDelete('cascade');
            $table->enum('tipo_documento', [
                'DARF', 'GPS', 'DAS', 'DARE', 'DAM', 'CARNÊ_LEÃO', 
                'IRPF', 'IRPJ', 'PIS_COFINS', 'IPI', 'ICMS', 'ISS', 'OUTROS'
            ]);
            $table->string('numero_documento', 50);
            $table->year('ano_exercicio');
            $table->date('data_emissao');
            $table->date('data_vencimento')->nullable();
            $table->decimal('valor_documento', 15, 2);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->enum('status', [
                'PENDENTE', 'PAGO', 'VENCIDO', 'CANCELADO', 'PROTESTADO', 'EM_ANALISE'
            ])->default('PENDENTE');
            $table->text('observacoes')->nullable();
            $table->string('arquivo_comprovante')->nullable();
            $table->string('hash_documento', 64)->unique();
            $table->string('assinatura_digital', 255)->nullable();
            $table->string('protocolo_receita', 20)->unique();
            $table->json('dados_extras')->nullable();
            $table->timestamps();

            // Índices para otimização
            $table->index(['tipo_documento', 'status']);
            $table->index(['data_vencimento', 'status']);
            $table->index(['ano_exercicio', 'tipo_documento']);
            $table->index('numero_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_baixa');
    }
}; 