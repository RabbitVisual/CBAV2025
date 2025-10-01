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
        Schema::create('ebd_entrega_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atividade_id')->constrained('ebd_atividades')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('ebd_alunos')->onDelete('cascade');
            $table->text('resposta_texto')->nullable();
            $table->string('arquivo_path')->nullable();
            $table->decimal('nota', 5, 2)->nullable();
            $table->text('feedback_professor')->nullable();
            $table->timestamp('data_entrega');
            $table->timestamp('data_avaliacao')->nullable();
            $table->enum('status', ['entregue', 'avaliado', 'atrasado'])->default('entregue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebd_entrega_atividades');
    }
};