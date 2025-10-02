<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Membro;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renomear tabelas para o padrão em inglês
        Schema::rename('intercessor_oracaos', 'intercessions');
        Schema::rename('pedido_oracaos', 'prayer_requests');

        // Adicionar user_id e remover membro_id da tabela prayer_requests
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Migrar dados de membro_id para user_id
        if (Schema::hasColumn('membros', 'user_id')) { // Verifica se a unificação de membros já ocorreu
            DB::table('prayer_requests as pr')
                ->join('membros as m', 'pr.membro_id', '=', 'm.id')
                ->update(['pr.user_id' => DB::raw('m.user_id')]);
        }

        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropForeign(['membro_id']);
            $table->dropColumn('membro_id');
        });

        // Ajustar a foreign key na tabela intercessions
        Schema::table('intercessions', function (Blueprint $table) {
            $table->dropForeign(['pedido_id']);
            $table->foreign('pedido_id')->references('id')->on('prayer_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter a foreign key em intercessions
        Schema::table('intercessions', function (Blueprint $table) {
            $table->dropForeign(['pedido_id']);
            $table->foreign('pedido_id')->references('id')->on('pedido_oracaos')->onDelete('cascade');
        });

        // Adicionar membro_id de volta e remover user_id em prayer_requests
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->foreignId('membro_id')->nullable()->after('user_id')->constrained('membros')->onDelete('set null');
        });

        // A lógica de reverter a migração de dados é complexa e omitida
        // pois a direção "para frente" é a desejada.

        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Renomear as tabelas de volta para o original
        Schema::rename('prayer_requests', 'pedido_oracaos');
        Schema::rename('intercessions', 'intercessor_oracaos');
    }
};