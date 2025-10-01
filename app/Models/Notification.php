<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Relacionamentos
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(NotificationRead::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                    ->where('recipient_type', 'App\Models\User');
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

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Métodos de instância
    public function isReadBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_read', true)
                   ->exists();
    }

    public function isStarredBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_starred', true)
                   ->exists();
    }

    public function isArchivedBy($userId): bool
    {
        return $this->reads()
                   ->where('user_id', $userId)
                   ->where('is_archived', true)
                   ->exists();
    }

    public function markAsReadBy($userId): void
    {
        $read = $this->reads()->where('user_id', $userId)->first();

        if ($read) {
            $read->markAsRead();
        } else {
            $this->reads()->create([
                'user_id' => $userId,
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    public function markAsUnreadBy($userId): void
    {
        $read = $this->reads()->where('user_id', $userId)->first();

        if ($read) {
            $read->markAsUnread();
        }
    }

    public function starBy($userId): void
    {
        $read = $this->reads()->where('user_id', $userId)->first();

        if ($read) {
            $read->star();
        } else {
            $this->reads()->create([
                'user_id' => $userId,
                'is_starred' => true,
                'starred_at' => now()
            ]);
        }
    }

    public function unstarBy($userId): void
    {
        $read = $this->reads()->where('user_id', $userId)->first();

        if ($read) {
            $read->unstar();
        }
    }

    public function archiveBy($userId): void
    {
        $read = $this->reads()->where('user_id', $userId)->first();

        if ($read) {
            $read->archive();
        } else {
            $this->reads()->create([
                'user_id' => $userId,
                'is_archived' => true,
                'archived_at' => now()
            ]);
        }
    }

    // Accessors
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getTypeIconAttribute(): string
    {
        return $this->icon ?: $this->getDefaultIcon();
    }

    public function getTypeColorAttribute(): string
    {
        return $this->color ?: $this->getDefaultColor();
    }

    private function getDefaultIcon(): string
    {
        $icons = [
            'info' => 'info-circle',
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'times-circle',
            'quiz' => 'question-circle',
            'ministry' => 'users',
            'financial' => 'dollar-sign',
            'event' => 'calendar',
            'council' => 'gavel'
        ];

        return $icons[$this->type] ?? 'bell';
    }

    private function getDefaultColor(): string
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'quiz' => 'purple',
            'ministry' => 'indigo',
            'financial' => 'green',
            'event' => 'blue',
            'council' => 'gray'
        ];

        return $colors[$this->type] ?? 'gray';
    }
}
