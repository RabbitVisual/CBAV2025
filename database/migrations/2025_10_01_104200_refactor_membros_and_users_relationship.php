<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Membro;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adiciona a coluna user_id à tabela membros
        Schema::table('membros', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });

        // Migra os dados existentes: associa membros a usuários pelo email
        DB::transaction(function () {
            Membro::all()->each(function ($membro) {
                $user = User::where('email', $membro->email)->first();
                if ($user) {
                    $membro->user_id = $user->id;
                    $membro->save();
                }
            });
        });

        // Remove as colunas redundantes da tabela membros
        Schema::table('membros', function (Blueprint $table) {
            if (Schema::hasColumn('membros', 'nome')) {
                $table->dropColumn('nome');
            }
            if (Schema::hasColumn('membros', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Adiciona as colunas de volta
        Schema::table('membros', function (Blueprint $table) {
            $table->string('nome')->after('user_id');
            $table->string('email')->unique()->after('nome');
        });

        // Restaura os dados
        DB::transaction(function () {
            Membro::with('user')->get()->each(function ($membro) {
                if ($membro->user) {
                    $membro->nome = $membro->user->name;
                    $membro->email = $membro->user->email;
                    $membro->save();
                }
            });
        });

        // Remove a coluna user_id
        Schema::table('membros', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};