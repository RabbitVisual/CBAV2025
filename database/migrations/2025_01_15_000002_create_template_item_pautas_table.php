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
        Schema::create('template_item_pautas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('template_pautas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', [
                'informativo',
                'deliberativo',
                'votacao',
                'discussao',
                'apresentacao'
            ])->default('informativo');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->integer('ordem')->default(1);
            $table->integer('tempo_estimado')->nullable(); // em minutos
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observacoes')->nullable();
            $table->json('configuracoes')->nullable();
            $table->timestamps();
            
            $table->index(['template_id', 'ordem']);
            $table->index(['tipo', 'prioridade']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_item_pautas');
    }
}; 