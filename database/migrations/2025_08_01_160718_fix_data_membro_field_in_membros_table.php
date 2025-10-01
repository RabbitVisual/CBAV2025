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
            // Alterar o tipo do campo data_membro de string para date
            $table->date('data_membro')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membros', function (Blueprint $table) {
            // Reverter para string
            $table->string('data_membro')->nullable()->change();
        });
    }
};
