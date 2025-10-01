<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Limpar tabelas que podem ter sido criadas parcialmente
        $tables = [
            'cbav_quiz_alerts',
            'cbav_notification_delivery_logs',
            'cbav_notification_preferences',
            'cbav_notification_templates',
            'cbav_notification_reads',
            'cbav_notifications'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não fazer nada no rollback
    }
};