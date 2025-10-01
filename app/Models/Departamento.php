<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'ministerio_id',
        'responsavel_id',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Membro::class, 'responsavel_id');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }

    public function membros()
    {
        return $this->belongsToMany(Membro::class, 'membro_cargo', 'cargo_id', 'membro_id');
    }

    /**
     * Obter membros ativos do departamento
     */
    public function getMembrosAtivosAttribute()
    {
        return Membro::join('membro_cargo', 'membros.id', '=', 'membro_cargo.membro_id')
                    ->join('cargos', 'membro_cargo.cargo_id', '=', 'cargos.id')
                    ->where('cargos.departamento_id', $this->id)
                    ->where('membro_cargo.ativo', true)
                    ->where('membros.ativo', true)
                    ->select('membros.*')
                    ->get();
    }
}
