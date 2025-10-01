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
        Schema::create('membro_ministerio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->onDelete('cascade');
            $table->foreignId('ministerio_id')->constrained('ministerios')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->timestamps();

            $table->unique(['membro_id', 'ministerio_id']);
            $table->index(['membro_id', 'ativo']);
            $table->index(['ministerio_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membro_ministerio');
    }
};
