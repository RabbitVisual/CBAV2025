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
        Schema::create('conselhos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_reuniao');
            $table->time('hora_inicio');
            $table->time('hora_fim')->nullable();
            $table->string('local')->nullable();
            $table->enum('status', ['agendada', 'em_andamento', 'finalizada', 'cancelada'])->default('agendada');
            $table->enum('tipo', ['reuniao_ordinaria', 'reuniao_extraordinaria', 'votacao'])->default('reuniao_ordinaria');
            $table->integer('quorum_minimo')->default(5);
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->foreignId('presidente_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('secretario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observacoes')->nullable();
            $table->boolean('ata_finalizada')->default(false);
            $table->timestamp('data_ata_finalizada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conselhos');
    }
}; 