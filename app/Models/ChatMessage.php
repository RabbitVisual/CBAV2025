<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'mensagem',
        'tipo',
        'arquivo_url',
        'arquivo_nome',
        'arquivo_tipo',
        'arquivo_tamanho',
        'editado',
        'editado_em',
        'deletado',
        'deletado_em',
        'deletado_por',
        'reacoes'
    ];

    protected $casts = [
        'editado' => 'boolean',
        'deletado' => 'boolean',
        'editado_em' => 'datetime',
        'deletado_em' => 'datetime',
        'arquivo_tamanho' => 'integer',
        'reacoes' => 'array'
    ];

    /**
     * Relacionamento com sala de chat
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Relacionamento com usuário que enviou
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com usuário que deletou
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deletado_por');
    }

    /**
     * Relacionamento com leituras
     */
    public function reads(): HasMany
    {
        return $this->hasMany(ChatMessageRead::class);
    }

    /**
     * Verificar se mensagem foi lida por um usuário
     */
    public function isReadBy($userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    /**
     * Verificar se mensagem pode ser editada pelo usuário
     */
    public function canBeEditedBy($userId): bool
    {
        if ($this->deletado) {
            return false;
        }

        if ($this->user_id === $userId) {
            return true;
        }

        // Verificar se usuário é moderador da sala
        $participant = ChatParticipant::where('chat_room_id', $this->chat_room_id)
                                     ->where('user_id', $userId)
                                     ->whereIn('tipo', ['admin', 'moderador'])
                                     ->where('ativo', true)
                                     ->first();

        return $participant !== null;
    }

    /**
     * Verificar se mensagem pode ser deletada pelo usuário
     */
    public function canBeDeletedBy($userId): bool
    {
        if ($this->user_id === $userId) {
            return true;
        }

        // Verificar se usuário é moderador da sala
        $participant = ChatParticipant::where('chat_room_id', $this->chat_room_id)
                                     ->where('user_id', $userId)
                                     ->whereIn('tipo', ['admin', 'moderador'])
                                     ->where('ativo', true)
                                     ->first();

        return $participant !== null;
    }

    /**
     * Adicionar reação
     */
    public function addReaction($userId, $reaction): void
    {
        $reacoes = $this->reacoes ?? [];
        
        if (!isset($reacoes[$reaction])) {
            $reacoes[$reaction] = [];
        }
        
        if (!in_array($userId, $reacoes[$reaction])) {
            $reacoes[$reaction][] = $userId;
        }
        
        $this->update(['reacoes' => $reacoes]);
    }

    /**
     * Remover reação
     */
    public function removeReaction($userId, $reaction): void
    {
        $reacoes = $this->reacoes ?? [];
        
        if (isset($reacoes[$reaction])) {
            $reacoes[$reaction] = array_diff($reacoes[$reaction], [$userId]);
            
            if (empty($reacoes[$reaction])) {
                unset($reacoes[$reaction]);
            }
        }
        
        $this->update(['reacoes' => $reacoes]);
    }

    /**
     * Verificar se usuário reagiu
     */
    public function hasUserReaction($userId, $reaction): bool
    {
        $reacoes = $this->reacoes ?? [];
        return isset($reacoes[$reaction]) && in_array($userId, $reacoes[$reaction]);
    }

    /**
     * Contar reações
     */
    public function getReactionCount($reaction): int
    {
        $reacoes = $this->reacoes ?? [];
        return isset($reacoes[$reaction]) ? count($reacoes[$reaction]) : 0;
    }

    /**
     * Scope para mensagens não deletadas
     */
    public function scopeNaoDeletadas($query)
    {
        return $query->where('deletado', false);
    }

    /**
     * Scope para mensagens por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para mensagens de texto
     */
    public function scopeTexto($query)
    {
        return $query->where('tipo', 'texto');
    }

    /**
     * Scope para mensagens de sistema
     */
    public function scopeSistema($query)
    {
        return $query->where('tipo', 'sistema');
    }
} 