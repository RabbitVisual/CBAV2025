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
        Schema::table('ebd_licoes', function (Blueprint $table) {
            $table->foreignId('disciplina_id')->nullable()->after('id')->constrained('ebd_disciplinas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebd_licoes', function (Blueprint $table) {
            $table->dropForeign(['disciplina_id']);
            $table->dropColumn('disciplina_id');
        });
    }
};