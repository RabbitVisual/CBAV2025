<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacao_user_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notificacao_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('lida')->default(false);
            $table->timestamp('lida_em')->nullable();
            $table->boolean('deletada')->default(false);
            $table->timestamps();

            $table->foreign('notificacao_id')->references('id')->on('notificacaos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['notificacao_id', 'user_id']);
            $table->index(['user_id', 'lida', 'deletada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacao_user_statuses');
    }
}; 