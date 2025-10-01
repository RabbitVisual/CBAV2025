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
        Schema::table('notificacaos', function (Blueprint $table) {
            // Campos de prioridade e categoria
            $table->enum('prioridade', ['baixa', 'normal', 'alta', 'urgente'])->default('normal')->after('dados_extras');
            $table->string('categoria')->default('sistema')->after('prioridade');
            
            // Campos de destinatário específico
            $table->enum('destinatario_tipo', ['usuario', 'membro', 'ministerio', 'todos'])->nullable()->after('categoria');
            $table->unsignedBigInteger('destinatario_id')->nullable()->after('destinatario_tipo');
            
            // Campos de controle de envio
            $table->unsignedBigInteger('enviada_por')->nullable()->after('destinatario_id');
            $table->timestamp('agendada_para')->nullable()->after('enviada_por');
            $table->timestamp('enviada_em')->nullable()->after('agendada_para');
            
            // Índices para melhor performance
            $table->index(['prioridade', 'categoria']);
            $table->index(['destinatario_tipo', 'destinatario_id']);
            $table->index(['agendada_para']);
            $table->index(['enviada_em']);
            $table->index(['lida', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificacaos', function (Blueprint $table) {
            $table->dropIndex(['prioridade', 'categoria']);
            $table->dropIndex(['destinatario_tipo', 'destinatario_id']);
            $table->dropIndex(['agendada_para']);
            $table->dropIndex(['enviada_em']);
            $table->dropIndex(['lida', 'created_at']);
            
            $table->dropColumn([
                'prioridade',
                'categoria',
                'destinatario_tipo',
                'destinatario_id',
                'enviada_por',
                'agendada_para',
                'enviada_em'
            ]);
        });
    }
};
