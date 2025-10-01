<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EbdCertificado extends Model
{
    use HasFactory;

    protected $table = 'ebd_certificados';

    protected $fillable = [
        'aluno_id',
        'avaliacao_id',
        'titulo',
        'tipo',
        'descricao',
        'conteudo',
        'carga_horaria',
        'nota_final',
        'data_emissao',
        'data_validade',
        'codigo',
        'assinatura_coordenador',
        'assinatura_pastor',
        'ativo'
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_validade' => 'date',
        'ativo' => 'boolean',
        'carga_horaria' => 'integer',
        'nota_final' => 'decimal:1',
    ];

    /**
     * Relacionamento com aluno
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(EbdAluno::class, 'aluno_id');
    }

    /**
     * Relacionamento com avaliação
     */
    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(EbdAvaliacao::class, 'avaliacao_id');
    }

    /**
     * Boot method para gerar código automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificado) {
            if (empty($certificado->codigo)) {
                $certificado->codigo = 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
            }
        });
    }

    /**
     * Obter tipo formatado
     */
    public function getTipoFormatadoAttribute()
    {
        return match($this->tipo) {
            'conclusao' => 'Conclusão de Curso',
            'participacao' => 'Participação',
            'excelencia' => 'Excelência',
            'presenca' => 'Presença',
            'avaliacao' => 'Avaliação',
            default => 'Certificado'
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getCorTipoAttribute()
    {
        return match($this->tipo) {
            'conclusao' => 'bg-green-100 text-green-800',
            'participacao' => 'bg-blue-100 text-blue-800',
            'excelencia' => 'bg-yellow-100 text-yellow-800',
            'presenca' => 'bg-purple-100 text-purple-800',
            'avaliacao' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Verificar se certificado está ativo
     */
    public function getEstaAtivoAttribute()
    {
        return $this->ativo;
    }

    /**
     * Verificar se certificado está válido
     */
    public function getEstaValidoAttribute()
    {
        if (!$this->ativo) {
            return false;
        }

        if ($this->data_validade && now()->isAfter($this->data_validade)) {
            return false;
        }

        return true;
    }

    /**
     * Obter URL de verificação
     */
    public function getUrlVerificacaoAttribute()
    {
        return route('ebd.certificados.verificar', $this->codigo);
    }

    /**
     * Scope para certificados ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para certificados válidos
     */
    public function scopeValidos($query)
    {
        return $query->where('ativo', true)
                    ->where(function($q) {
                        $q->whereNull('data_validade')
                          ->orWhere('data_validade', '>', now());
                    });
    }

    /**
     * Scope para certificados por aluno
     */
    public function scopePorAluno($query, $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    /**
     * Scope para certificados por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Buscar por código
     */
    public static function buscarPorCodigo($codigo)
    {
        return static::where('codigo', $codigo)->first();
    }
} 