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
        // Tabela principal de notificações
        Schema::create('cbav_notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Remetente e destinatário
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('recipient_type', 50); // user, role, ministry, all, quiz_participants
            $table->unsignedBigInteger('recipient_id')->nullable();
            
            // Conteúdo da notificação
            $table->string('title');
            $table->text('message');
            $table->string('type', 20)->default('info'); // info, success, warning, error, urgent
            $table->string('category', 50)->default('system'); // system, quiz, ministry, financial, event
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Configurações visuais
            $table->string('icon', 100)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_text', 100)->nullable();
            
            // Dados extras e contexto
            $table->json('data')->nullable(); // Dados específicos do contexto
            $table->json('metadata')->nullable(); // Metadados adicionais
            
            // Controle de entrega
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_persistent')->default(true); // Se deve persistir após leitura
            
            // Configurações de canal
            $table->json('channels')->nullable(); // ['database', 'email', 'push', 'sms']
            $table->json('channel_settings')->nullable(); // Configurações específicas por canal
            
            // Status e controle
            $table->enum('status', ['draft', 'scheduled', 'sent', 'failed', 'cancelled'])->default('draft');
            $table->text('failure_reason')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('last_retry_at')->nullable();
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('recipient_type');
            $table->index('recipient_id');
            $table->index('type');
            $table->index('category');
            $table->index('priority');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('created_at');
        });
        
        // Tabela de status de leitura por usuário
        Schema::create('cbav_notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('cbav_notifications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->timestamp('starred_at')->nullable();
            
            // Interações do usuário
            $table->boolean('action_clicked')->default(false);
            $table->timestamp('action_clicked_at')->nullable();
            $table->json('interaction_data')->nullable();
            
            $table->timestamps();
            
            $table->unique(['notification_id', 'user_id']);
            $table->index(['user_id', 'is_read', 'is_archived']);
            $table->index(['user_id', 'read_at']);
        });
        
        // Tabela de templates de notificação
        Schema::create('cbav_notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('title');
            $table->text('message_template');
            $table->string('type', 20)->default('info');
            $table->string('category', 50);
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            $table->string('icon', 100)->nullable();
            $table->string('color', 20)->nullable();
            $table->json('default_channels')->nullable();
            $table->json('variables')->nullable(); // Variáveis disponíveis no template
            
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            
            $table->timestamps();
            
            $table->index('category');
            $table->index('is_active');
        });
        
        // Tabela de preferências de notificação por usuário
        Schema::create('cbav_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Preferências gerais
            $table->boolean('enabled')->default(true);
            $table->json('categories')->nullable(); // Categorias habilitadas
            $table->json('channels')->nullable(); // Canais preferidos
            $table->json('priority_settings')->nullable(); // Configurações por prioridade
            
            // Horários de silêncio
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->json('quiet_days')->nullable(); // Dias da semana para silêncio
            
            // Configurações específicas
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('quiz_notifications')->default(true);
            $table->boolean('ministry_notifications')->default(true);
            $table->boolean('financial_notifications')->default(true);
            $table->boolean('event_notifications')->default(true);
            
            // Configurações de agrupamento
            $table->boolean('group_similar')->default(true);
            $table->integer('max_per_hour')->default(10);
            $table->integer('digest_frequency')->default(0); // 0=disabled, 1=daily, 7=weekly
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
        
        // Tabela de logs de entrega
        Schema::create('cbav_notification_delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('cbav_notifications')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('channel', 20); // database, email, push, sms
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'bounced']);
            $table->text('response')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            
            $table->json('metadata')->nullable(); // Dados específicos do canal
            
            $table->timestamps();
            
            $table->index('notification_id');
            $table->index('channel');
            $table->index('user_id');
            $table->index('status');
        });
        
        // Tabela de alertas de quiz
        Schema::create('cbav_quiz_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_session_id')->nullable()->constrained('ebd_quiz_sessoes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('alert_type', 50); // new_record, achievement, reminder, result
            $table->string('title');
            $table->text('message');
            $table->json('quiz_data')->nullable(); // Dados específicos do quiz
            
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('alert_type');
            $table->index('quiz_session_id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbav_quiz_alerts');
        Schema::dropIfExists('cbav_notification_delivery_logs');
        Schema::dropIfExists('cbav_notification_preferences');
        Schema::dropIfExists('cbav_notification_templates');
        Schema::dropIfExists('cbav_notification_reads');
        Schema::dropIfExists('cbav_notifications');
    }
};