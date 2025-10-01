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
        Schema::create('pedido_oracaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('categoria', ['saude', 'familia', 'trabalho', 'espiritual', 'outros']);
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente']);
            $table->enum('status', ['pendente', 'em_oracao', 'atendido', 'arquivado'])->default('pendente');
            $table->datetime('data_pedido');
            $table->datetime('data_atendimento')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('anonimo')->default(false);
            $table->boolean('pode_compartilhar')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_oracaos');
    }
}; 