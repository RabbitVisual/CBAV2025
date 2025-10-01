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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('descricao_curta')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->string('local')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->json('coordenadas')->nullable(); // latitude, longitude
            $table->enum('tipo_publico', ['membros', 'publico', 'ambos'])->default('ambos');
            $table->enum('tipo_evento', ['culto', 'estudo', 'reuniao', 'conferencia', 'outro'])->default('outro');
            $table->enum('status', ['rascunho', 'ativo', 'cancelado', 'finalizado'])->default('rascunho');
            $table->boolean('gratuito')->default(true);
            $table->decimal('valor_inscricao', 10, 2)->nullable();
            $table->integer('vagas_disponiveis')->nullable();
            $table->integer('vagas_totais')->nullable();
            $table->boolean('inscricao_obrigatoria')->default(false);
            $table->timestamp('inscricao_ate')->nullable();
            $table->string('imagem')->nullable();
            $table->json('galeria_fotos')->nullable();
            $table->text('regulamento')->nullable();
            $table->text('informacoes_adicionais')->nullable();
            $table->foreignId('organizador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('ministerio_id')->nullable()->constrained('ministerios')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->boolean('destaque')->default(false);
            $table->boolean('ativo')->default(true);
            $table->foreignId('criado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('atualizado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['ativo', 'status']);
            $table->index(['data_inicio', 'data_fim']);
            $table->index(['tipo_publico', 'ativo']);
            $table->index(['destaque', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
}; 