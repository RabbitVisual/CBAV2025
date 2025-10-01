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
        Schema::table('departamentos', function (Blueprint $table) {
            $table->foreignId('responsavel_id')->nullable()->constrained('membros')->onDelete('set null')->after('ministerio_id');
            $table->text('observacoes')->nullable()->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['responsavel_id']);
            $table->dropColumn(['responsavel_id', 'observacoes']);
        });
    }
};
