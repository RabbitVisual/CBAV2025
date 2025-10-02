<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

/**
 * App\Models\EBD\Grupo
 *
 * @property int $id
 * @property int $turma_id
 * @property int|null $lider_id
 * @property string $nome
 * @property string|null $descricao
 * @property string $cor
 * @property int|null $capacidade_maxima
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EBD\Turma $turma
 * @property-read User|null $lider
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $membros
 * @property-read int|null $membros_count
 * @property-read bool $esta_lotado
 * @property-read float $percentual_ocupacao
 * @method static Builder|Grupo newModelQuery()
 * @method static Builder|Grupo newQuery()
 * @method static Builder|Grupo query()
 * @method static Builder|Grupo ativos()
 * @method static Builder|Grupo daTurma(int $turmaId)
 */
class Grupo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ebd_grupos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'turma_id',
        'lider_id',
        'nome',
        'descricao',
        'cor',
        'capacidade_maxima',
        'ativo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ativo' => 'boolean',
        'capacidade_maxima' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'esta_lotado',
        'percentual_ocupacao',
    ];

    /**
     * Define o relacionamento com a turma à qual o grupo pertence.
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    /**
     * Define o relacionamento com o usuário que é líder do grupo.
     */
    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    /**
     * Define o relacionamento muitos-para-muitos com usuários (membros do grupo).
     */
    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ebd_grupo_user', 'grupo_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Escopo para consultar apenas grupos ativos.
     */
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    /**
     * Escopo para consultar grupos de uma turma específica.
     */
    public function scopeDaTurma(Builder $query, int $turmaId): Builder
    {
        return $query->where('turma_id', $turmaId);
    }

    /**
     * Accessor para verificar se o grupo atingiu a capacidade máxima.
     *
     * @return bool
     */
    public function getEstaLotadoAttribute(): bool
    {
        if (is_null($this->capacidade_maxima)) {
            return false;
        }
        return $this->membros()->count() >= $this->capacidade_maxima;
    }

    /**
     * Accessor para calcular o percentual de ocupação do grupo.
     *
     * @return float
     */
    public function getPercentualOcupacaoAttribute(): float
    {
        if (is_null($this->capacidade_maxima) || $this->capacidade_maxima === 0) {
            return 0.0;
        }
        return round(($this->membros()->count() / $this->capacidade_maxima) * 100, 2);
    }

    /**
     * Accessor para a cor com valor padrão.
     *
     * @param string|null $value
     * @return string
     */
    public function getCorAttribute(?string $value): string
    {
        return $value ?: '#3B82F6'; // Azul padrão para grupos
    }
}