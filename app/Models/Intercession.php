<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Intercession extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'intercessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pedido_id',
        'user_id',
        'data_oracao',
        'tipo_oracao',
        'tempo_oracao',
        'observacoes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_oracao' => 'datetime',
        'tempo_oracao' => 'integer',
    ];

    // --- RELACIONAMENTOS ---

    /**
     * O pedido de oração ao qual esta intercessão pertence.
     */
    public function prayerRequest(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class, 'pedido_id');
    }

    /**
     * O usuário que fez a intercessão.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}