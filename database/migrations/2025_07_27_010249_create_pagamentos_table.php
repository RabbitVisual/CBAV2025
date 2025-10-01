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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transacao_id')->constrained('transacoes')->onDelete('cascade');
            $table->enum('gateway', ['stripe', 'mercadopago', 'pix']);
            $table->string('gateway_id')->nullable();
            $table->string('gateway_status')->nullable();
            $table->decimal('valor', 10, 2);
            $table->string('moeda')->default('BRL');
            $table->json('dados_gateway')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
