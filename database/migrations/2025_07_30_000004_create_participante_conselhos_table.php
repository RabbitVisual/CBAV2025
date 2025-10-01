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
        Schema::create('participante_conselhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conselho_id')->constrained('conselhos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('funcao', ['presidente', 'secretario', 'membro', 'convidado'])->default('membro');
            $table->boolean('presente')->default(false);
            $table->timestamp('hora_chegada')->nullable();
            $table->timestamp('hora_saida')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['conselho_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participante_conselhos');
    }
}; 