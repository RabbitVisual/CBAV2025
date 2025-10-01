<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebd_quiz_perguntas', function (Blueprint $table) {
            $table->id();
            $table->string('pergunta');
            $table->text('opcao_a');
            $table->text('opcao_b');
            $table->text('opcao_c');
            $table->text('opcao_d');
            $table->enum('resposta_correta', ['a', 'b', 'c', 'd']);
            $table->text('explicacao')->nullable();
            $table->string('referencia_biblica')->nullable();
            $table->enum('nivel', ['facil', 'medio', 'dificil'])->default('medio');
            $table->enum('categoria', ['geral', 'antigo_testamento', 'novo_testamento', 'personagens', 'milagres', 'parabolas', 'profetas', 'apostolos'])->default('geral');
            $table->boolean('ativo')->default(true);
            $table->integer('pontuacao')->default(10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebd_quiz_perguntas');
    }
}; 