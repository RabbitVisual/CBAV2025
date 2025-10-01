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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['publico', 'privado', 'ministerio', 'admin'])->default('publico');
            $table->string('cor', 7)->default('#3b82f6'); // Cor do chat em hex
            $table->string('icone', 50)->default('fas fa-comments'); // Ícone do chat
            $table->boolean('ativo')->default(true);
            $table->integer('max_participantes')->nullable(); // null = ilimitado
            $table->json('configuracoes')->nullable(); // Configurações específicas do chat
            $table->timestamps();
            
            $table->index(['tipo', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
}; 