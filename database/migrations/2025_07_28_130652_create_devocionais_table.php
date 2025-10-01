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
        Schema::create('devocionais', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('texto');
            $table->string('versiculo');
            $table->text('reflexao');
            $table->date('data');
            $table->enum('tipo', ['devocional', 'versiculo', 'oracao'])->default('devocional');
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->json('dados_extras')->nullable();
            $table->timestamps();
            
            $table->index(['data', 'tipo']);
            $table->index(['ativo', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devocionais');
    }
};
