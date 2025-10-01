<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRead extends Model
{
    use HasFactory;

    protected $table = 'cbav_notification_reads';

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read',
        'read_at',
        'is_archived',
        'archived_at',
        'is_starred',
        'starred_at',
        'action_clicked',
        'action_clicked_at',
        'interaction_data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_archived' => 'boolean',
        'is_starred' => 'boolean',
        'action_clicked' => 'boolean',
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
        'starred_at' => 'datetime',
        'action_clicked_at' => 'datetime',
        'interaction_data' => 'array'
    ];

    // Relacionamentos
    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Métodos de instância
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function archive(): void
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now()
        ]);
    }

    public function unarchive(): void
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null
        ]);
    }

    public function star(): void
    {
        $this->update([
            'is_starred' => true,
            'starred_at' => now()
        ]);
    }

    public function unstar(): void
    {
        $this->update([
            'is_starred' => false,
            'starred_at' => null
        ]);
    }

    public function recordActionClick($data = []): void
    {
        $this->update([
            'action_clicked' => true,
            'action_clicked_at' => now(),
            'interaction_data' => array_merge($this->interaction_data ?? [], $data)
        ]);
    }

    // Accessors
    public function getReadStatusAttribute(): string
    {
        if ($this->is_archived) {
            return 'archived';
        }
        
        if ($this->is_starred) {
            return 'starred';
        }
        
        return $this->is_read ? 'read' : 'unread';
    }

    public function getInteractionSummaryAttribute(): array
    {
        return [
            'read' => $this->is_read,
            'read_at' => $this->read_at,
            'archived' => $this->is_archived,
            'starred' => $this->is_starred,
            'action_clicked' => $this->action_clicked,
            'interaction_count' => count($this->interaction_data ?? [])
        ];
    }
}