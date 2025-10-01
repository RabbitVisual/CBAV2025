<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacaoUserStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'notificacao_id',
        'user_id',
        'lida',
        'lida_em',
        'deletada',
    ];

    protected $casts = [
        'lida' => 'boolean',
        'lida_em' => 'datetime',
        'deletada' => 'boolean',
    ];

    public function notificacao()
    {
        return $this->belongsTo(Notificacao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 