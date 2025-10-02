<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'telefone',
        'data_nascimento',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'estado_civil',
        'data_batismo',
        'data_ingresso',
        'observacoes',
        'foto',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_batismo' => 'date',
        'data_ingresso' => 'date',
    ];

    /**
     * O usuário ao qual este perfil pertence.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}