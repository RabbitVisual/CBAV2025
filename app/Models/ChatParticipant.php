<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'tipo',
        'ativo',
        'ultimo_acesso',
        'mute_until',
        'mute_permanente'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'mute_permanente' => 'boolean',
        'ultimo_acesso' => 'datetime',
        'mute_until' => 'datetime'
    ];

    /**
     * Relacionamento com sala de chat
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verificar se usuário está mutado
     */
    public function isMuted(): bool
    {
        if ($this->mute_permanente) {
            return true;
        }

        if ($this->mute_until && $this->mute_until->isFuture()) {
            return true;
        }

        return false;
    }

    /**
     * Verificar se é admin
     */
    public function isAdmin(): bool
    {
        return $this->tipo === 'admin';
    }

    /**
     * Verificar se é moderador
     */
    public function isModerator(): bool
    {
        return in_array($this->tipo, ['admin', 'moderador']);
    }

    /**
     * Scope para participantes ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para admins
     */
    public function scopeAdmin($query)
    {
        return $query->where('tipo', 'admin');
    }

    /**
     * Scope para moderadores
     */
    public function scopeModerador($query)
    {
        return $query->whereIn('tipo', ['admin', 'moderador']);
    }

    /**
     * Scope para participantes
     */
    public function scopeParticipante($query)
    {
        return $query->where('tipo', 'participante');
    }
} 