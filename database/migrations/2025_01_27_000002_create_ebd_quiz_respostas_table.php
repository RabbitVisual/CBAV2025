<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebd_quiz_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sessao_id')->constrained('ebd_quiz_sessoes')->onDelete('cascade');
            $table->foreignId('pergunta_id')->constrained('ebd_quiz_perguntas')->onDelete('cascade');
            $table->enum('resposta_dada', ['a', 'b', 'c', 'd']);
            $table->boolean('correta');
            $table->integer('pontuacao_obtida');
            $table->integer('tempo_resposta')->nullable(); // em segundos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebd_quiz_respostas');
    }
}; 