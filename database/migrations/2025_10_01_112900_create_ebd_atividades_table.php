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
        Schema::create('ebd_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licao_id')->constrained('ebd_licoes')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['leitura_dirigida', 'reflexao_pessoal', 'pesquisa_biblica', 'trabalho_em_grupo', 'memorizacao', 'projeto_especial']);
            $table->integer('pontuacao_maxima')->default(10);
            $table->date('data_entrega')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebd_atividades');
    }
};