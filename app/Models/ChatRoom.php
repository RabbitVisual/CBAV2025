<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'cor',
        'icone',
        'ativo',
        'max_participantes',
        'configuracoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'configuracoes' => 'array',
        'max_participantes' => 'integer'
    ];

    /**
     * Relacionamento com participantes
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class);
    }

    /**
     * Relacionamento com usuários participantes
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants')
                    ->withPivot(['tipo', 'ativo', 'ultimo_acesso', 'mute_until', 'mute_permanente'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento com mensagens
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Última mensagem da sala
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    /**
     * Contar mensagens não lidas para um usuário
     */
    public function unreadMessagesCount($userId): int
    {
        return $this->messages()
                    ->whereDoesntHave('reads', function($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->where('user_id', '!=', $userId)
                    ->count();
    }

    /**
     * Verificar se usuário pode participar
     */
    public function canUserJoin($userId): bool
    {
        if (!$this->ativo) {
            return false;
        }

        if ($this->max_participantes && $this->participants()->where('ativo', true)->count() >= $this->max_participantes) {
            return false;
        }

        return true;
    }

    /**
     * Verificar se usuário é participante
     */
    public function isUserParticipant($userId): bool
    {
        return $this->participants()
                    ->where('user_id', $userId)
                    ->where('ativo', true)
                    ->exists();
    }

    /**
     * Verificar se usuário é admin/moderador
     */
    public function isUserModerator($userId): bool
    {
        return $this->participants()
                    ->where('user_id', $userId)
                    ->whereIn('tipo', ['admin', 'moderador'])
                    ->where('ativo', true)
                    ->exists();
    }

    /**
     * Scope para salas ativas
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para salas por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para salas públicas
     */
    public function scopePublico($query)
    {
        return $query->where('tipo', 'publico');
    }

    /**
     * Scope para salas privadas
     */
    public function scopePrivado($query)
    {
        return $query->where('tipo', 'privado');
    }

    /**
     * Scope para salas de ministério
     */
    public function scopeMinisterio($query)
    {
        return $query->where('tipo', 'ministerio');
    }

    /**
     * Scope para salas admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('tipo', 'admin');
    }
} 