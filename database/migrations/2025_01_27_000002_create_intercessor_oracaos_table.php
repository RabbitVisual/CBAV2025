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
        Schema::create('intercessor_oracaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedido_oracaos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->datetime('data_oracao');
            $table->text('observacoes')->nullable();
            $table->integer('tempo_oracao')->nullable(); // em minutos
            $table->enum('tipo_oracao', ['individual', 'grupo', 'igreja']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intercessor_oracaos');
    }
}; 