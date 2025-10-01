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
        Schema::create('evento_inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nome');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cpf', 14)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->text('observacoes')->nullable();
            $table->enum('status', ['pendente', 'confirmada', 'cancelada', 'presente', 'ausente'])->default('pendente');
            $table->enum('forma_pagamento', ['pix', 'stripe', 'mercadopago', 'dinheiro', 'transferencia', 'outro'])->nullable();
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->timestamp('data_pagamento')->nullable();
            $table->string('comprovante_pagamento')->nullable();
            $table->boolean('presenca_confirmada')->default(false);
            $table->timestamp('data_presenca')->nullable();
            $table->boolean('certificado_emitido')->default(false);
            $table->timestamp('data_certificado')->nullable();
            $table->json('dados_extras')->nullable();
            $table->timestamps();

            $table->index(['evento_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['email']);
            $table->index(['status']);
            $table->unique(['evento_id', 'user_id'], 'unique_evento_user');
            $table->unique(['evento_id', 'email'], 'unique_evento_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_inscricoes');
    }
}; 