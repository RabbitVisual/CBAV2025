<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adiciona a coluna user_id
        Schema::table('transacoes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Migra os dados de membro_id para user_id
        DB::table('transacoes')->whereNotNull('membro_id')->cursor()->each(function ($transacao) {
            $membro = DB::table('membros')->find($transacao->membro_id);
            if ($membro) {
                $user = DB::table('users')->where('email', $membro->email)->first();
                if ($user) {
                    DB::table('transacoes')->where('id', $transacao->id)->update(['user_id' => $user->id]);
                }
            }
        });

        // Remove a coluna membro_id
        Schema::table('transacoes', function (Blueprint $table) {
            // A verificação da existência da chave estrangeira pode variar entre SGBDs
            // Esta é uma abordagem geral
            $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('transacoes');
            foreach ($foreignKeys as $foreignKey) {
                if (in_array('membro_id', $foreignKey->getColumns())) {
                    $table->dropForeign($foreignKey->getName());
                }
            }
            $table->dropColumn('membro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->foreignId('membro_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });

        // Lógica de rollback (pode ser complexa e omitida se a migração for "one-way")
        DB::table('transacoes')->whereNotNull('user_id')->cursor()->each(function ($transacao) {
            $user = DB::table('users')->find($transacao->user_id);
            if ($user) {
                $membro = DB::table('membros')->where('email', $user->email)->first();
                if ($membro) {
                    DB::table('transacoes')->where('id', $transacao->id)->update(['membro_id' => $membro->id]);
                }
            }
        });

        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};