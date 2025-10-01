<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'cbav_notifications';

    protected $fillable = [
        'uuid',
        'sender_id',
        'recipient_type',
        'recipient_id',
        'title',
        'message',
        'type',
        'category',
        'priority',
        'icon',
        'color',
        'action_url',
        'action_text',
        'data',
        'metadata',
        'scheduled_at',
        'sent_at',
        'expires_at',
        'is_persistent',
        'channels',
        'channel_settings',
        'status',
        'failure_reason',
        'retry_count',
        'last_retry_at'
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'channels' => 'array',
        'channel_settings' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_retry_at' => 'datetime',
        'is_persistent' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($notification) {
            if (empty($notification->uuid)) {
                $notification->uuid = Str::uuid();
            }
        });
    }

    // Relacionamentos
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(NotificationRead::class, 'notification_id');
    }

    public function deliveryLogs(): HasMany
    {
        return $this->hasMany(NotificationDeliveryLog::class, 'notification_id');
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('recipient_type', 'user')
              ->where('recipient_id', $userId)
              ->orWhere('recipient_type', 'all');
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '>', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'scheduled')
                    ->where(function ($q) {
                        $q->whereNull('scheduled_at')
                          ->orWhere('scheduled_at', '<=', now());
                    });
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeUnreadForUser($query, $userId)
    {
        return $query->whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_read', true);
        });
    }

    public function scopeReadForUser($query, $userId)
    {
        return $query->whereHas('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_read', true);
        });
    }

    // Métodos de instância
    public function isReadBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_read', true)
                   ->exists();
    }

    public function isArchivedBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_archived', true)
                   ->exists();
    }

    public function isStarredBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_starred', true)
                   ->exists();
    }

    public function markAsReadBy($userId): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_read' => true,
                'read_at' => now()
            ]
        );
    }

    public function markAsUnreadBy($userId): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_read' => false,
                'read_at' => null
            ]
        );
    }

    public function archiveBy($userId): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_archived' => true,
                'archived_at' => now()
            ]
        );
    }

    public function starBy($userId): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_starred' => true,
                'starred_at' => now()
            ]
        );
    }

    public function unstarBy($userId): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_starred' => false,
                'starred_at' => null
            ]
        );
    }

    public function recordActionClick($userId, $data = []): void
    {
        $this->reads()->updateOrCreate(
            ['user_id' => $userId],
            [
                'action_clicked' => true,
                'action_clicked_at' => now(),
                'interaction_data' => $data
            ]
        );
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canRetry(): bool
    {
        return $this->status === 'failed' && $this->retry_count < 3;
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed($reason = null): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'retry_count' => $this->retry_count + 1,
            'last_retry_at' => now()
        ]);
    }

    // Accessors
    public function getTypeIconAttribute(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            'urgent' => 'fas fa-exclamation-triangle',
            default => 'fas fa-info-circle'
        };
    }

    public function getTypeColorAttribute(): string
    {
        if ($this->color) {
            return $this->color;
        }

        return match($this->type) {
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'urgent' => 'red',
            default => 'blue'
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'success' => 'bg-green-100 text-green-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            'error' => 'bg-red-100 text-red-800',
            'urgent' => 'bg-red-100 text-red-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'normal' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedDataAttribute(): array
    {
        return $this->data ?? [];
    }

    // Métodos estáticos para criação
    public static function createForUser($userId, $data): self
    {
        return static::create(array_merge($data, [
            'recipient_type' => 'user',
            'recipient_id' => $userId,
            'status' => 'sent',
            'sent_at' => now()
        ]));
    }

    public static function createForAll($data): self
    {
        return static::create(array_merge($data, [
            'recipient_type' => 'all',
            'status' => 'sent',
            'sent_at' => now()
        ]));
    }

    public static function createForMinistry($ministryId, $data): self
    {
        return static::create(array_merge($data, [
            'recipient_type' => 'ministry',
            'recipient_id' => $ministryId,
            'status' => 'sent',
            'sent_at' => now()
        ]));
    }

    public static function schedule($data, $scheduledAt): self
    {
        return static::create(array_merge($data, [
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt
        ]));
    }

    // Métodos específicos para quiz
    public static function createQuizAlert($userId, $type, $title, $message, $quizData = []): self
    {
        return static::createForUser($userId, [
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'category' => 'quiz',
            'priority' => $type === 'new_record' ? 'high' : 'normal',
            'icon' => 'fas fa-trophy',
            'data' => array_merge($quizData, ['quiz_alert_type' => $type]),
            'channels' => ['database', 'push']
        ]);
    }

    // Métodos para notificações do sistema
    public static function systemNotification($title, $message, $type = 'info', $priority = 'normal'): self
    {
        return static::createForAll([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'category' => 'system',
            'priority' => $priority,
            'channels' => ['database']
        ]);
    }

    public static function urgentAlert($title, $message, $data = []): self
    {
        return static::createForAll([
            'title' => $title,
            'message' => $message,
            'type' => 'urgent',
            'category' => 'system',
            'priority' => 'urgent',
            'data' => $data,
            'channels' => ['database', 'push', 'email']
        ]);
    }
}