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
        // Adicionar 'entrada' ao ENUM do campo tipo
        \DB::statement("ALTER TABLE transacoes MODIFY COLUMN tipo ENUM('dizimo', 'oferta', 'doacao', 'saida', 'entrada') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'entrada' do ENUM do campo tipo
        \DB::statement("ALTER TABLE transacoes MODIFY COLUMN tipo ENUM('dizimo', 'oferta', 'doacao', 'saida') NOT NULL");
    }
};
