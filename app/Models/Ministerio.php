<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministerio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cor',
        'icone',
        'ativo',
        'responsavel_id',
        'data_fundacao',
        'reuniao_semanal',
        'observacoes'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_fundacao' => 'date',
    ];

    /**
     * Relacionamento com responsável (User)
     */
    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    /**
     * Relacionamento simples com membros (para compatibilidade)
     */
    public function membros()
    {
        return $this->belongsToMany(Membro::class, 'membro_cargo', 'cargo_id', 'membro_id');
    }

    /**
     * Obter membros ativos do ministério
     */
    public function getMembrosAtivosAttribute()
    {
        return Membro::join('membro_cargo', 'membros.id', '=', 'membro_cargo.membro_id')
                    ->join('cargos', 'membro_cargo.cargo_id', '=', 'cargos.id')
                    ->join('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
                    ->where('departamentos.ministerio_id', $this->id)
                    ->where('membro_cargo.ativo', true)
                    ->where('membros.ativo', true)
                    ->select('membros.*')
                    ->get();
    }

    /**
     * Contagem de membros ativos
     */
    public function getMembrosCountAttribute()
    {
        return Membro::join('membro_cargo', 'membros.id', '=', 'membro_cargo.membro_id')
                    ->join('cargos', 'membro_cargo.cargo_id', '=', 'cargos.id')
                    ->join('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
                    ->where('departamentos.ministerio_id', $this->id)
                    ->where('membro_cargo.ativo', true)
                    ->where('membros.ativo', true)
                    ->count();
    }

    /**
     * Scope para ministérios ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para ministérios inativos
     */
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Relacionamento com eventos
     */
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
