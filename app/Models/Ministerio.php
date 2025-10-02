<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Ministerio
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $cor
 * @property string|null $icone
 * @property bool $ativo
 * @property int|null $responsavel_id
 * @property \Illuminate\Support\Carbon|null $data_fundacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $responsavel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static Builder|Ministerio newModelQuery()
 * @method static Builder|Ministerio newQuery()
 * @method static Builder|Ministerio query()
 * @method static Builder|Ministerio ativos()
 */
class Ministerio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'cor',
        'icone',
        'ativo',
        'responsavel_id',
        'data_fundacao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ativo' => 'boolean',
        'data_fundacao' => 'date',
    ];

    /**
     * Relacionamento com o usuário responsável pelo ministério.
     */
    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    /**
     * Relacionamento muitos-para-muitos com os usuários (membros do ministério).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ministerio_user');
    }

    /**
     * Escopo para consultar apenas ministérios ativos.
     */
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }
}