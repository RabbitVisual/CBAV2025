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
        Schema::create('ebd_membros_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('ebd_grupos_estudo')->onDelete('cascade');
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->enum('papel', ['membro', 'lider'])->default('membro');
            $table->timestamp('data_entrada')->useCurrent();
            $table->timestamp('data_saida')->nullable();
            $table->boolean('ativo')->default(true);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->unique(['grupo_id', 'membro_id']);
            $table->index(['grupo_id', 'ativo']);
            $table->index(['membro_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebd_membros_grupo');
    }
};
