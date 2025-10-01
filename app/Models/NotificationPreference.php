<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $table = 'cbav_notification_preferences';

    protected $fillable = [
        'user_id',
        'enabled',
        'categories',
        'channels',
        'priority_settings',
        'quiet_hours_start',
        'quiet_hours_end',
        'quiet_days',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
        'quiz_notifications',
        'ministry_notifications',
        'financial_notifications',
        'event_notifications',
        'group_similar',
        'max_per_hour',
        'digest_frequency'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'categories' => 'array',
        'channels' => 'array',
        'priority_settings' => 'array',
        'quiet_days' => 'array',
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'quiz_notifications' => 'boolean',
        'ministry_notifications' => 'boolean',
        'financial_notifications' => 'boolean',
        'event_notifications' => 'boolean',
        'group_similar' => 'boolean',
        'quiet_hours_start' => 'datetime:H:i',
        'quiet_hours_end' => 'datetime:H:i'
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Métodos de instância
    public function isNotificationEnabled($category = null, $channel = null): bool
    {
        if (!$this->enabled) {
            return false;
        }

        // Verificar categoria específica
        if ($category) {
            $categoryEnabled = match($category) {
                'quiz' => $this->quiz_notifications,
                'ministry' => $this->ministry_notifications,
                'financial' => $this->financial_notifications,
                'event' => $this->event_notifications,
                default => true
            };
            
            if (!$categoryEnabled) {
                return false;
            }
        }

        // Verificar canal específico
        if ($channel) {
            $channelEnabled = match($channel) {
                'email' => $this->email_enabled,
                'push' => $this->push_enabled,
                'sms' => $this->sms_enabled,
                default => true
            };
            
            if (!$channelEnabled) {
                return false;
            }
        }

        return true;
    }

    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now();
        $start = $now->copy()->setTimeFromTimeString($this->quiet_hours_start->format('H:i'));
        $end = $now->copy()->setTimeFromTimeString($this->quiet_hours_end->format('H:i'));

        // Se o horário de fim é menor que o de início, significa que passa da meia-noite
        if ($end->lt($start)) {
            return $now->gte($start) || $now->lte($end);
        }

        return $now->between($start, $end);
    }

    public function isQuietDay(): bool
    {
        if (!$this->quiet_days || empty($this->quiet_days)) {
            return false;
        }

        $today = now()->dayOfWeek; // 0 = domingo, 1 = segunda, etc.
        return in_array($today, $this->quiet_days);
    }

    public function shouldReceiveNotification($category = null, $channel = null, $priority = 'normal'): bool
    {
        // Verificar se notificações estão habilitadas
        if (!$this->isNotificationEnabled($category, $channel)) {
            return false;
        }

        // Verificar horário de silêncio (exceto para urgentes)
        if ($priority !== 'urgent' && ($this->isInQuietHours() || $this->isQuietDay())) {
            return false;
        }

        // Verificar configurações de prioridade
        if ($this->priority_settings && isset($this->priority_settings[$priority])) {
            return (bool) $this->priority_settings[$priority];
        }

        return true;
    }

    public function getPreferredChannels($category = null): array
    {
        $channels = [];

        if ($this->email_enabled && $this->isNotificationEnabled($category, 'email')) {
            $channels[] = 'email';
        }

        if ($this->push_enabled && $this->isNotificationEnabled($category, 'push')) {
            $channels[] = 'push';
        }

        if ($this->sms_enabled && $this->isNotificationEnabled($category, 'sms')) {
            $channels[] = 'sms';
        }

        // Sempre incluir database se pelo menos um canal estiver habilitado
        if (!empty($channels) || $this->enabled) {
            array_unshift($channels, 'database');
        }

        return array_unique($channels);
    }

    public function updateCategory($category, $enabled): void
    {
        $field = match($category) {
            'quiz' => 'quiz_notifications',
            'ministry' => 'ministry_notifications',
            'financial' => 'financial_notifications',
            'event' => 'event_notifications',
            default => null
        };

        if ($field) {
            $this->update([$field => $enabled]);
        }
    }

    public function updateChannel($channel, $enabled): void
    {
        $field = match($channel) {
            'email' => 'email_enabled',
            'push' => 'push_enabled',
            'sms' => 'sms_enabled',
            default => null
        };

        if ($field) {
            $this->update([$field => $enabled]);
        }
    }

    public function setQuietHours($start, $end): void
    {
        $this->update([
            'quiet_hours_start' => $start,
            'quiet_hours_end' => $end
        ]);
    }

    public function setQuietDays(array $days): void
    {
        $this->update(['quiet_days' => $days]);
    }

    // Métodos estáticos
    public static function getDefaultPreferences(): array
    {
        return [
            'enabled' => true,
            'categories' => ['system', 'quiz', 'ministry', 'financial', 'event'],
            'channels' => ['database', 'email', 'push'],
            'priority_settings' => [
                'low' => true,
                'normal' => true,
                'high' => true,
                'urgent' => true
            ],
            'email_enabled' => true,
            'push_enabled' => true,
            'sms_enabled' => false,
            'quiz_notifications' => true,
            'ministry_notifications' => true,
            'financial_notifications' => true,
            'event_notifications' => true,
            'group_similar' => true,
            'max_per_hour' => 10,
            'digest_frequency' => 0
        ];
    }

    public static function createDefault($userId): self
    {
        return static::create(array_merge(
            static::getDefaultPreferences(),
            ['user_id' => $userId]
        ));
    }

    // Accessors
    public function getNotificationSummaryAttribute(): array
    {
        return [
            'enabled' => $this->enabled,
            'email' => $this->email_enabled,
            'push' => $this->push_enabled,
            'sms' => $this->sms_enabled,
            'categories' => [
                'quiz' => $this->quiz_notifications,
                'ministry' => $this->ministry_notifications,
                'financial' => $this->financial_notifications,
                'event' => $this->event_notifications
            ],
            'quiet_hours' => [
                'start' => $this->quiet_hours_start?->format('H:i'),
                'end' => $this->quiet_hours_end?->format('H:i'),
                'days' => $this->quiet_days ?? []
            ]
        ];
    }
}