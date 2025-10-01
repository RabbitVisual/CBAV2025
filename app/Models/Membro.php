<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    use HasFactory;

    protected $table = 'membros';

    /**
     * The attributes that are mass assignable.
     * Nome e email foram removidos pois agora são gerenciados pelo model User.
     */
    protected $fillable = [
        'user_id',
        'telefone',
        'data_nascimento',
        'sexo',
        'estado_civil',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'data_batismo',
        'data_membro',
        'data_ingresso',
        'profissao',
        'escolaridade',
        'observacoes',
        'foto',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_batismo' => 'date',
        'data_membro' => 'date',
        'data_ingresso' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento um-para-um (inverso) com User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento com ministérios (muitos para muitos).
     */
    public function ministerios()
    {
        return $this->belongsToMany(Ministerio::class, 'membro_ministerio', 'membro_id', 'ministerio_id');
    }

    /**
     * Relacionamento com transações.
     */
    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    /**
     * Relacionamento com solicitações de ministério.
     */
    public function solicitacoesMinisterio()
    {
        return $this->hasMany(SolicitacaoMinisterio::class);
    }

    /**
     * Relacionamento com cargos (muitos para muitos).
     */
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'membro_cargo', 'membro_id', 'cargo_id')
            ->withPivot('ativo', 'data_inicio', 'data_fim')
            ->withTimestamps();
    }

    /**
     * Scope para membros ativos.
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Obter idade do membro.
     */
    public function getIdadeAttribute()
    {
        if (!$this->data_nascimento) {
            return null;
        }
        return $this->data_nascimento->age;
    }

    /**
     * Obter URL da foto, agora buscando do usuário associado se não houver foto no membro.
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && \Storage::disk('public')->exists($this->foto)) {
            return url($this->foto);
        }
        // Fallback para a foto do usuário, se existir
        if ($this->user && $this->user->foto_url) {
            return $this->user->foto_url;
        }
        return null;
    }
}