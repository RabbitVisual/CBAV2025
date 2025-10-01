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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('mensagem');
            $table->enum('tipo', ['texto', 'imagem', 'arquivo', 'sistema'])->default('texto');
            $table->string('arquivo_url')->nullable(); // URL do arquivo/imagem
            $table->string('arquivo_nome')->nullable(); // Nome original do arquivo
            $table->string('arquivo_tipo')->nullable(); // MIME type
            $table->integer('arquivo_tamanho')->nullable(); // Tamanho em bytes
            $table->boolean('editado')->default(false);
            $table->timestamp('editado_em')->nullable();
            $table->boolean('deletado')->default(false);
            $table->timestamp('deletado_em')->nullable();
            $table->foreignId('deletado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->json('reacoes')->nullable(); // Reações dos usuários
            $table->timestamps();
            
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['tipo', 'deletado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
}; 