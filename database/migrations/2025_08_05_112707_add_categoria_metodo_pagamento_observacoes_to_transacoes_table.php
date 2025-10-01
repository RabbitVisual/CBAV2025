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
        Schema::table('transacoes', function (Blueprint $table) {
            $table->string('categoria')->nullable()->after('descricao');
            $table->string('metodo_pagamento')->nullable()->after('categoria');
            $table->text('observacoes')->nullable()->after('metodo_pagamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'metodo_pagamento', 'observacoes']);
        });
    }
};
