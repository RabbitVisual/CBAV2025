<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $telefone
 * @property \Illuminate\Support\Carbon|null $data_nascimento
 * @property array|null $endereco
 * @property string|null $estado_civil
 * @property \Illuminate\Support\Carbon|null $data_batismo
 * @property \Illuminate\Support\Carbon|null $data_ingresso
 * @property string|null $observacoes
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read string|null $endereco_completo
 * @method static \Database\Factories\ProfileFactory factory($count = null, $state = [])
 * @method static Builder|Profile newModelQuery()
 * @method static Builder|Profile newQuery()
 * @method static Builder|Profile query()
 * @method static Builder|Profile aniversariantesDoMes()
 * @method static Builder|Profile membrosRecentes(int $dias = 30)
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'telefone',
        'data_nascimento',
        'endereco',
        'estado_civil',
        'data_batismo',
        'data_ingresso',
        'observacoes',
        'foto',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_nascimento' => 'date:Y-m-d',
        'data_batismo' => 'date:Y-m-d',
        'data_ingresso' => 'date:Y-m-d',
        'endereco' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'endereco_completo',
    ];

    /**
     * Define o relacionamento inverso com o usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Escopo para buscar perfis de aniversariantes do mês atual.
     */
    public function scopeAniversariantesDoMes(Builder $query): Builder
    {
        return $query->whereMonth('data_nascimento', '=', Carbon::now()->month);
    }

    /**
     * Escopo para buscar perfis de membros que ingressaram recentemente.
     */
    public function scopeMembrosRecentes(Builder $query, int $dias = 30): Builder
    {
        return $query->where('data_ingresso', '>=', Carbon::now()->subDays($dias));
    }

    /**
     * Accessor para obter o endereço completo formatado.
     *
     * @return string|null
     */
    public function getEnderecoCompletoAttribute(): ?string
    {
        if (empty($this->endereco)) {
            return null;
        }

        $parts = [
            $this->endereco['logradouro'] ?? null,
            $this->endereco['numero'] ?? null,
            $this->endereco['complemento'] ?? null,
            $this->endereco['bairro'] ?? null,
            $this->endereco['cidade'] ?? null,
            $this->endereco['estado'] ?? null,
            $this->endereco['cep'] ?? null,
        ];

        // Filtra partes nulas e junta com vírgula
        $filteredParts = array_filter($parts);
        if (empty($filteredParts)) {
            return null;
        }

        $addressString = $this->endereco['logradouro'] ?? '';
        if (!empty($this->endereco['numero'])) {
            $addressString .= ', ' . $this->endereco['numero'];
        }
        if (!empty($this->endereco['bairro'])) {
            $addressString .= ' - ' . $this->endereco['bairro'];
        }
        if (!empty($this->endereco['cidade'])) {
            $addressString .= '. ' . $this->endereco['cidade'];
        }
        if (!empty($this->endereco['estado'])) {
            $addressString .= ' - ' . $this->endereco['estado'];
        }
        if (!empty($this->endereco['cep'])) {
            $addressString .= ', CEP: ' . $this->endereco['cep'];
        }

        return trim($addressString, ', ');
    }
}