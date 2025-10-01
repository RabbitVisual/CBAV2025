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
        Schema::table('ministerios', function (Blueprint $table) {
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null')->after('ativo');
            $table->date('data_fundacao')->nullable()->after('responsavel_id');
            $table->string('reuniao_semanal')->nullable()->after('data_fundacao');
            $table->text('observacoes')->nullable()->after('reuniao_semanal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministerios', function (Blueprint $table) {
            $table->dropForeign(['responsavel_id']);
            $table->dropColumn(['responsavel_id', 'data_fundacao', 'reuniao_semanal', 'observacoes']);
        });
    }
};
