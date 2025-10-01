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
        Schema::table('devocionais', function (Blueprint $table) {
            $table->text('texto_versiculo')->nullable()->after('versiculo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devocionais', function (Blueprint $table) {
            $table->dropColumn('texto_versiculo');
        });
    }
};
