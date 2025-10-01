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
        Schema::create('notificacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tipo'); // 'info', 'success', 'warning', 'error'
            $table->string('titulo');
            $table->text('mensagem');
            $table->string('icone')->nullable();
            $table->string('acao_url')->nullable();
            $table->string('acao_texto')->nullable();
            $table->boolean('lida')->default(false);
            $table->timestamp('lida_em')->nullable();
            $table->json('dados_extras')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacaos');
    }
};
