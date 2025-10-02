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
        Schema::create('ebd_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('ebd_turmas')->onDelete('cascade');
            $table->foreignId('lider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('cor', 7)->default('#3B82F6');
            $table->unsignedInteger('capacidade_maxima')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebd_grupos');
    }
};