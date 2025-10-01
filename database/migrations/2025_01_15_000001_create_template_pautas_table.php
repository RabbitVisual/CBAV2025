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
        Schema::create('template_pautas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->enum('categoria', [
                'reuniao_ordinaria',
                'reuniao_extraordinaria',
                'votacao',
                'evento',
                'geral'
            ])->default('geral');
            $table->enum('status', ['ativo', 'inativo', 'rascunho'])->default('rascunho');
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->json('itens_pauta')->nullable();
            $table->json('configuracoes')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'categoria']);
            $table->index('criado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_pautas');
    }
}; 