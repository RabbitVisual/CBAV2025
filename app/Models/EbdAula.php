<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EbdAula extends Model
{
    use HasFactory;

    protected $table = 'ebd_aulas';

    protected $fillable = [
        'turma_id',
        'licao_id',
        'professor_id',
        'data_aula',
        'horario_inicio',
        'horario_fim',
        'observacoes',
        'status'
    ];

    protected $casts = [
        'data_aula' => 'date',
        'horario_inicio' => 'datetime',
        'horario_fim' => 'datetime',
    ];

    /**
     * Relacionamento com turma
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(EbdTurma::class, 'turma_id');
    }

    /**
     * Relacionamento com lição
     */
    public function licao(): BelongsTo
    {
        return $this->belongsTo(EbdLicao::class, 'licao_id');
    }

    /**
     * Relacionamento com professor
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(EbdProfessor::class, 'professor_id');
    }

    /**
     * Relacionamento com presenças
     */
    public function presencas(): HasMany
    {
        return $this->hasMany(EbdPresenca::class, 'aula_id');
    }

    /**
     * Relacionamento com avaliações
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(EbdAvaliacao::class, 'aula_id');
    }

    /**
     * Calcular duração da aula
     */
    public function getDuracaoAttribute()
    {
        return $this->horario_inicio->diffInMinutes($this->horario_fim);
    }

    /**
     * Calcular total de presenças
     */
    public function getTotalPresencasAttribute()
    {
        return $this->presencas()->where('status', 'presente')->count();
    }

    /**
     * Calcular total de ausências
     */
    public function getTotalAusenciasAttribute()
    {
        return $this->presencas()->where('status', 'ausente')->count();
    }

    /**
     * Calcular percentual de presença
     */
    public function getPercentualPresencaAttribute()
    {
        $total = $this->presencas()->count();
        if ($total === 0) {
            return 0;
        }
        return round(($this->total_presencas / $total) * 100, 2);
    }

    /**
     * Obter status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return match($this->status) {
            'agendada' => 'Agendada',
            'realizada' => 'Realizada',
            'cancelada' => 'Cancelada',
            'adiada' => 'Adiada',
            default => 'Agendada'
        };
    }

    /**
     * Obter cor do status
     */
    public function getCorStatusAttribute()
    {
        return match($this->status) {
            'agendada' => 'bg-blue-100 text-blue-800',
            'realizada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            'adiada' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }

    /**
     * Verificar se aula foi realizada
     */
    public function getFoiRealizadaAttribute()
    {
        return $this->status === 'realizada';
    }

    /**
     * Verificar se aula foi cancelada
     */
    public function getFoiCanceladaAttribute()
    {
        return $this->status === 'cancelada';
    }

    /**
     * Scope para aulas agendadas
     */
    public function scopeAgendadas($query)
    {
        return $query->where('status', 'agendada');
    }

    /**
     * Scope para aulas realizadas
     */
    public function scopeRealizadas($query)
    {
        return $query->where('status', 'realizada');
    }

    /**
     * Scope para aulas canceladas
     */
    public function scopeCanceladas($query)
    {
        return $query->where('status', 'cancelada');
    }

    /**
     * Scope para aulas por turma
     */
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }

    /**
     * Scope para aulas por data
     */
    public function scopePorData($query, $data)
    {
        return $query->where('data_aula', $data);
    }

    /**
     * Scope para aulas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('data_aula', '>=', now()->toDateString());
    }

    /**
     * Scope para aulas passadas
     */
    public function scopePassadas($query)
    {
        return $query->where('data_aula', '<', now()->toDateString());
    }
} 