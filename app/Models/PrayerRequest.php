<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrayerRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prayer_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'titulo', 'descricao', 'categoria', 'prioridade',
        'status', 'data_pedido', 'data_atendimento', 'observacoes',
        'anonimo', 'pode_compartilhar'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_pedido' => 'datetime',
        'data_atendimento' => 'datetime',
        'anonimo' => 'boolean',
        'pode_compartilhar' => 'boolean'
    ];

    // --- RELACIONAMENTOS ---

    /**
     * O usuário que criou o pedido de oração.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * As intercessões feitas para este pedido.
     */
    public function intercessions(): HasMany
    {
        return $this->hasMany(Intercession::class, 'pedido_id');
    }

    // --- SCOPES ---

    public function scopePending($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeInPrayer($query)
    {
        return $query->where('status', 'em_oracao');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'atendido');
    }
}