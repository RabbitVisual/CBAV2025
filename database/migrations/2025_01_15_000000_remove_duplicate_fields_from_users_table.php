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
        Schema::table('users', function (Blueprint $table) {
            // Remover campos duplicados que existem na tabela membros
            $table->dropColumn([
                'foto',
                'telefone', 
                'cargo',
                'bio',
                'data_nascimento',
                'endereco',
                'cidade',
                'estado',
                'cep'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar campos removidos
            $table->string('foto')->nullable()->after('password');
            $table->string('telefone')->nullable()->after('foto');
            $table->string('cargo')->nullable()->after('telefone');
            $table->text('bio')->nullable()->after('cargo');
            $table->date('data_nascimento')->nullable()->after('bio');
            $table->string('endereco')->nullable()->after('data_nascimento');
            $table->string('cidade')->nullable()->after('endereco');
            $table->string('estado', 2)->nullable()->after('cidade');
            $table->string('cep', 10)->nullable()->after('estado');
        });
    }
};