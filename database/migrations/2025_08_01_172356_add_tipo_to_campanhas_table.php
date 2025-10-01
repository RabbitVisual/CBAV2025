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
        Schema::table('campanhas', function (Blueprint $table) {
            $table->enum('tipo', ['construcao', 'missao', 'social', 'equipamentos', 'outros'])->default('outros')->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campanhas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
