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
        Schema::create('pauta_conselhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conselho_id')->constrained('conselhos')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['informativo', 'deliberativo', 'votacao'])->default('informativo');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->integer('ordem')->default(0);
            $table->integer('tempo_estimado')->nullable(); // em minutos
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pendente', 'em_discussao', 'aprovado', 'rejeitado', 'adiado'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->text('decisao_final')->nullable();
            $table->timestamp('data_decisao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pauta_conselhos');
    }
}; 