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
        Schema::create('campanhas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->decimal('meta_valor', 10, 2)->nullable();
            $table->decimal('valor_arrecadado', 10, 2)->default(0);
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->enum('status', ['ativa', 'pausada', 'concluida', 'cancelada'])->default('ativa');
            $table->string('imagem')->nullable();
            $table->text('qr_code_pix')->nullable();
            $table->string('chave_pix')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanhas');
    }
};
