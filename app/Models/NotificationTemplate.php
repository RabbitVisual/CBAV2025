<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $table = 'cbav_notification_templates';

    protected $fillable = [
        'name',
        'title',
        'message_template',
        'type',
        'category',
        'priority',
        'icon',
        'color',
        'default_channels',
        'variables',
        'is_active',
        'description'
    ];

    protected $casts = [
        'default_channels' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Métodos de instância
    public function render(array $variables = []): array
    {
        $title = $this->renderTemplate($this->title, $variables);
        $message = $this->renderTemplate($this->message_template, $variables);

        return [
            'title' => $title,
            'message' => $message,
            'type' => $this->type,
            'category' => $this->category,
            'priority' => $this->priority,
            'icon' => $this->icon,
            'color' => $this->color,
            'channels' => $this->default_channels ?? ['database']
        ];
    }

    public function renderTemplate(string $template, array $variables = []): string
    {
        $rendered = $template;

        foreach ($variables as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $rendered = str_replace($placeholder, $value, $rendered);
        }

        // Remover placeholders não substituídos
        $rendered = preg_replace('/\{\{[^}]+\}\}/', '', $rendered);

        return trim($rendered);
    }

    public function getRequiredVariables(): array
    {
        return $this->variables ?? [];
    }

    public function validateVariables(array $variables): array
    {
        $required = $this->getRequiredVariables();
        $missing = [];

        foreach ($required as $variable) {
            if (!isset($variables[$variable['name']])) {
                $missing[] = $variable['name'];
            }
        }

        return $missing;
    }

    public function createNotification(array $variables = [], array $options = []): Notification
    {
        $missing = $this->validateVariables($variables);
        if (!empty($missing)) {
            throw new \InvalidArgumentException('Missing required variables: ' . implode(', ', $missing));
        }

        $data = $this->render($variables);
        
        // Merge com opções adicionais
        $notificationData = array_merge($data, $options);

        return Notification::create($notificationData);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    // Métodos estáticos para templates predefinidos
    public static function createQuizRecordTemplate(): self
    {
        return static::create([
            'name' => 'quiz_new_record',
            'title' => 'Novo Recorde no Quiz Bíblico! 🏆',
            'message_template' => 'Parabéns {{user_name}}! Você estabeleceu um novo recorde no Quiz Bíblico com {{score}} pontos em {{category}}. Continue estudando a Palavra de Deus!',
            'type' => 'success',
            'category' => 'quiz',
            'priority' => 'high',
            'icon' => 'fas fa-trophy',
            'color' => 'gold',
            'default_channels' => ['database', 'push', 'email'],
            'variables' => [
                ['name' => 'user_name', 'type' => 'string', 'description' => 'Nome do usuário'],
                ['name' => 'score', 'type' => 'number', 'description' => 'Pontuação obtida'],
                ['name' => 'category', 'type' => 'string', 'description' => 'Categoria do quiz']
            ],
            'is_active' => true,
            'description' => 'Template para notificar novos recordes no quiz bíblico'
        ]);
    }

    public static function createQuizCompletionTemplate(): self
    {
        return static::create([
            'name' => 'quiz_completion',
            'title' => 'Quiz Concluído! 📚',
            'message_template' => 'Você concluiu o quiz "{{quiz_title}}" com {{score}} pontos ({{percentage}}%). {{encouragement}}',
            'type' => 'info',
            'category' => 'quiz',
            'priority' => 'normal',
            'icon' => 'fas fa-check-circle',
            'default_channels' => ['database', 'push'],
            'variables' => [
                ['name' => 'quiz_title', 'type' => 'string', 'description' => 'Título do quiz'],
                ['name' => 'score', 'type' => 'number', 'description' => 'Pontuação obtida'],
                ['name' => 'percentage', 'type' => 'number', 'description' => 'Percentual de acerto'],
                ['name' => 'encouragement', 'type' => 'string', 'description' => 'Mensagem de encorajamento']
            ],
            'is_active' => true,
            'description' => 'Template para notificar conclusão de quiz'
        ]);
    }

    public static function createSystemMaintenanceTemplate(): self
    {
        return static::create([
            'name' => 'system_maintenance',
            'title' => 'Manutenção do Sistema 🔧',
            'message_template' => 'O sistema entrará em manutenção {{start_time}} e ficará indisponível por aproximadamente {{duration}}. {{additional_info}}',
            'type' => 'warning',
            'category' => 'system',
            'priority' => 'high',
            'icon' => 'fas fa-tools',
            'default_channels' => ['database', 'email', 'push'],
            'variables' => [
                ['name' => 'start_time', 'type' => 'datetime', 'description' => 'Horário de início da manutenção'],
                ['name' => 'duration', 'type' => 'string', 'description' => 'Duração estimada'],
                ['name' => 'additional_info', 'type' => 'string', 'description' => 'Informações adicionais']
            ],
            'is_active' => true,
            'description' => 'Template para notificar manutenção do sistema'
        ]);
    }

    public static function createNewMemberTemplate(): self
    {
        return static::create([
            'name' => 'new_member',
            'title' => 'Novo Membro na Igreja! 🙏',
            'message_template' => 'Damos as boas-vindas ao novo membro {{member_name}} que se juntou à nossa comunidade. Vamos recebê-lo com amor cristão!',
            'type' => 'success',
            'category' => 'ministry',
            'priority' => 'normal',
            'icon' => 'fas fa-user-plus',
            'default_channels' => ['database'],
            'variables' => [
                ['name' => 'member_name', 'type' => 'string', 'description' => 'Nome do novo membro']
            ],
            'is_active' => true,
            'description' => 'Template para notificar novo membro'
        ]);
    }

    public static function createEventReminderTemplate(): self
    {
        return static::create([
            'name' => 'event_reminder',
            'title' => 'Lembrete: {{event_name}} 📅',
            'message_template' => 'Não se esqueça! O evento "{{event_name}}" acontecerá {{event_date}} às {{event_time}}. {{location_info}}',
            'type' => 'info',
            'category' => 'event',
            'priority' => 'normal',
            'icon' => 'fas fa-calendar-alt',
            'default_channels' => ['database', 'push'],
            'variables' => [
                ['name' => 'event_name', 'type' => 'string', 'description' => 'Nome do evento'],
                ['name' => 'event_date', 'type' => 'date', 'description' => 'Data do evento'],
                ['name' => 'event_time', 'type' => 'time', 'description' => 'Horário do evento'],
                ['name' => 'location_info', 'type' => 'string', 'description' => 'Informações do local']
            ],
            'is_active' => true,
            'description' => 'Template para lembrete de eventos'
        ]);
    }

    // Accessors
    public function getVariableNamesAttribute(): array
    {
        return collect($this->variables ?? [])->pluck('name')->toArray();
    }

    public function getPreviewAttribute(): array
    {
        $sampleVariables = [];
        
        foreach ($this->variables ?? [] as $variable) {
            $sampleVariables[$variable['name']] = match($variable['type']) {
                'string' => 'Exemplo',
                'number' => '100',
                'date' => now()->format('d/m/Y'),
                'time' => now()->format('H:i'),
                'datetime' => now()->format('d/m/Y H:i'),
                default => 'Valor'
            };
        }

        return $this->render($sampleVariables);
    }
}