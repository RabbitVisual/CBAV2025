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
        Schema::create('user_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'cargo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cargo');
    }
};
