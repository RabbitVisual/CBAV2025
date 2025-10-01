<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_message_id',
        'user_id',
        'lido_em'
    ];

    protected $casts = [
        'lido_em' => 'datetime'
    ];

    /**
     * Relacionamento com mensagem
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 