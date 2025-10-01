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
        Schema::create('voto_conselhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('votacao_id')->constrained('votacao_conselhos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('voto'); // favoravel, contrario, abstenção, opcao_1, opcao_2, etc.
            $table->text('justificativa')->nullable();
            $table->timestamp('data_voto')->nullable();
            $table->boolean('voto_anonimo')->default(false);
            $table->timestamps();

            $table->unique(['votacao_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voto_conselhos');
    }
}; 