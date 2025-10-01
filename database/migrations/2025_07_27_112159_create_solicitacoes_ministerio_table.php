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
        Schema::create('solicitacoes_ministerio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->foreignId('ministerio_id')->constrained('ministerios')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->text('motivo');
            $table->enum('status', ['pendente', 'aprovada', 'rejeitada'])->default('pendente');
            $table->text('resposta')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('data_resposta')->nullable();
            $table->timestamps();
            
            $table->index(['membro_id', 'status']);
            $table->index(['ministerio_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_ministerio');
    }
};
