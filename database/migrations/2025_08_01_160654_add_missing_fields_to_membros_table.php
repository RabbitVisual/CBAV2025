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
        Schema::table('membros', function (Blueprint $table) {
            // Adicionar colunas faltantes
            $table->string('bairro')->nullable()->after('endereco');
            $table->string('sexo')->nullable()->after('data_nascimento');
            $table->string('profissao')->nullable()->after('estado_civil');
            $table->string('escolaridade')->nullable()->after('profissao');
            $table->string('data_membro')->nullable()->after('data_batismo');
            $table->boolean('receber_notificacoes')->default(true)->after('ativo');
            $table->boolean('receber_newsletter')->default(true)->after('receber_notificacoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membros', function (Blueprint $table) {
            $table->dropColumn([
                'bairro',
                'sexo',
                'profissao',
                'escolaridade',
                'data_membro',
                'receber_notificacoes',
                'receber_newsletter'
            ]);
        });
    }
};
