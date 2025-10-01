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
        Schema::create('evento_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('inscricao_id')->constrained('evento_inscricoes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->enum('forma_pagamento', ['pix', 'stripe', 'mercadopago', 'dinheiro', 'transferencia', 'outro']);
            $table->enum('status', ['pendente', 'processando', 'aprovado', 'rejeitado', 'cancelado'])->default('pendente');
            $table->string('gateway_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('data_pagamento')->nullable();
            $table->timestamp('data_confirmacao')->nullable();
            $table->string('comprovante_url')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('dados_extras')->nullable();
            $table->timestamps();

            $table->index(['evento_id', 'status']);
            $table->index(['inscricao_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['status']);
            $table->index(['gateway_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_pagamentos');
    }
}; 