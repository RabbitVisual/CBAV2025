<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

/**
 * App\Models\EBD\Turma
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $faixa_etaria
 * @property string $cor
 * @property int|null $capacidade_maxima
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $alunos
 * @property-read int|null $alunos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $professores
 * @property-read int|null $professores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EBD\Grupo> $grupos
 * @property-read int|null $grupos_count
 * @property-read bool $esta_cheia
 * @property-read float $percentual_ocupacao
 * @method static Builder|Turma newModelQuery()
 * @method static Builder|Turma newQuery()
 * @method static Builder|Turma query()
 * @method static Builder|Turma ativas()
 */
class Turma extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ebd_turmas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'faixa_etaria',
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
        'esta_cheia',
        'percentual_ocupacao',
    ];

    /**
     * Define o relacionamento muitos-para-muitos com usuários (alunos).
     */
    public function alunos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ebd_turma_user', 'turma_id', 'user_id')
            ->wherePivot('funcao', 'aluno')
            ->withTimestamps();
    }

    /**
     * Define o relacionamento muitos-para-muitos com usuários (professores).
     */
    public function professores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ebd_turma_user', 'turma_id', 'user_id')
            ->wherePivot('funcao', 'professor')
            ->withTimestamps();
    }

    /**
     * Define o relacionamento um-para-muitos com os grupos de estudo.
     */
    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'turma_id');
    }

    /**
     * Escopo para consultar apenas turmas ativas.
     */
    public function scopeAtivas(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    /**
     * Accessor para verificar se a turma atingiu a capacidade máxima.
     *
     * @return bool
     */
    public function getEstaCheiaAttribute(): bool
    {
        if (is_null($this->capacidade_maxima)) {
            return false;
        }
        return $this->alunos()->count() >= $this->capacidade_maxima;
    }

    /**
     * Accessor para calcular o percentual de ocupação da turma.
     *
     * @return float
     */
    public function getPercentualOcupacaoAttribute(): float
    {
        if (is_null($this->capacidade_maxima) || $this->capacidade_maxima === 0) {
            return 0.0;
        }
        return round(($this->alunos()->count() / $this->capacidade_maxima) * 100, 2);
    }

    /**
     * Accessor para a cor com valor padrão.
     *
     * @param string|null $value
     * @return string
     */
    public function getCorAttribute(?string $value): string
    {
        return $value ?: '#1D4ED8'; // Azul padrão para turmas
    }
}