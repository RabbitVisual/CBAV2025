<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebd_quiz_sessoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('nivel', ['facil', 'medio', 'dificil']);
            $table->enum('categoria', ['geral', 'antigo_testamento', 'novo_testamento', 'personagens', 'milagres', 'parabolas', 'profetas', 'apostolos'])->nullable();
            $table->integer('total_perguntas');
            $table->integer('acertos');
            $table->integer('pontuacao_total');
            $table->decimal('percentual', 5, 2);
            $table->timestamp('iniciado_em');
            $table->timestamp('finalizado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebd_quiz_sessoes');
    }
}; 