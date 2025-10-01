<?php

namespace App\Models\EBD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EbdAluno;

class EbdGrupoMembro extends Model
{
    use HasFactory;

    protected $table = 'ebd_grupo_membros';

    protected $fillable = [
        'grupo_id',
        'aluno_id',
        'data_entrada',
        'data_saida',
        'status'
    ];

    protected $casts = [
        'data_entrada' => 'datetime',
        'data_saida' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com o grupo
     */
    public function grupo()
    {
        return $this->belongsTo(EbdGrupoEstudo::class, 'grupo_id');
    }

    /**
     * Relacionamento com o aluno
     */
    public function aluno()
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Scope para membros ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo')
            ->whereNull('data_saida');
    }

    /**
     * Scope para membros removidos
     */
    public function scopeRemovidos($query)
    {
        return $query->where('status', 'removido')
            ->whereNotNull('data_saida');
    }

    /**
     * Scope para membros de um grupo específico
     */
    public function scopeDoGrupo($query, $grupoId)
    {
        return $query->where('grupo_id', $grupoId);
    }

    /**
     * Verifica se o membro está ativo
     */
    public function estaAtivo()
    {
        return $this->status === 'ativo' && is_null($this->data_saida);
    }

    /**
     * Verifica se o membro é o líder do grupo
     */
    public function ehLider()
    {
        return $this->grupo->lider_id === $this->aluno_id;
    }

    /**
     * Calcula o tempo de permanência no grupo
     */
    public function tempoNoGrupo()
    {
        $dataFim = $this->data_saida ?: now();
        return $this->data_entrada->diffInDays($dataFim);
    }

    /**
     * Remove o membro do grupo
     */
    public function remover()
    {
        // Se for o líder, remover a liderança
        if ($this->ehLider()) {
            $this->grupo->update(['lider_id' => null]);
        }

        $this->update([
            'status' => 'removido',
            'data_saida' => now()
        ]);
    }

    /**
     * Reativa o membro no grupo
     */
    public function reativar()
    {
        // Verificar se o grupo pode receber o membro
        if (!$this->grupo->podeAdicionarAluno($this->aluno)) {
            return false;
        }

        $this->update([
            'status' => 'ativo',
            'data_saida' => null
        ]);

        return true;
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        $status = [
            'ativo' => 'Ativo',
            'removido' => 'Removido',
            'transferido' => 'Transferido'
        ];

        return $status[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor para tempo no grupo formatado
     */
    public function getTempoNoGrupoFormatadoAttribute()
    {
        $dias = $this->tempoNoGrupo();
        
        if ($dias < 30) {
            return $dias . ' dia' . ($dias !== 1 ? 's' : '');
        } elseif ($dias < 365) {
            $meses = floor($dias / 30);
            return $meses . ' mês' . ($meses !== 1 ? 'es' : '');
        } else {
            $anos = floor($dias / 365);
            $mesesRestantes = floor(($dias % 365) / 30);
            $texto = $anos . ' ano' . ($anos !== 1 ? 's' : '');
            if ($mesesRestantes > 0) {
                $texto .= ' e ' . $mesesRestantes . ' mês' . ($mesesRestantes !== 1 ? 'es' : '');
            }
            return $texto;
        }
    }

    /**
     * Accessor para data de entrada formatada
     */
    public function getDataEntradaFormatadaAttribute()
    {
        return $this->data_entrada ? $this->data_entrada->format('d/m/Y') : null;
    }

    /**
     * Accessor para data de saída formatada
     */
    public function getDataSaidaFormatadaAttribute()
    {
        return $this->data_saida ? $this->data_saida->format('d/m/Y') : null;
    }
}