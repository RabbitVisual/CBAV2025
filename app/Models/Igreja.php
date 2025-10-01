<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Igreja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
        'pastor_responsavel',
        'data_fundacao',
        'tipo_entidade',
        'situacao_cadastral',
        'inscricao_estadual',
        'inscricao_municipal',
        'certificado_digital',
        'observacoes'
    ];

    protected $casts = [
        'data_fundacao' => 'date'
    ];

    // Tipos de entidade religiosa
    const TIPOS_ENTIDADE = [
        'IGREJA' => 'Igreja',
        'CONGREGACAO' => 'Congregação',
        'MINISTERIO' => 'Ministério',
        'ORGANIZACAO_RELIGIOSA' => 'Organização Religiosa'
    ];

    // Situação cadastral
    const SITUACAO_CADASTRAL = [
        'ATIVA' => 'Ativa',
        'INATIVA' => 'Inativa',
        'SUSPENSA' => 'Suspensa',
        'CANCELADA' => 'Cancelada'
    ];

    /**
     * Relacionamento com documentos de declaração anual
     */
    public function documentosDeclaracaoAnual(): HasMany
    {
        return $this->hasMany(DocumentoDeclaracaoAnual::class);
    }

    /**
     * Relacionamento com transações
     */
    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class);
    }

    /**
     * Relacionamento com membros
     */
    public function membros(): HasMany
    {
        return $this->hasMany(Membro::class);
    }

    /**
     * Gerar certificado digital da igreja
     */
    public function gerarCertificadoDigital(): string
    {
        $dados = [
            'nome' => $this->nome,
            'cnpj' => $this->cnpj,
            'endereco' => $this->endereco,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'tipo_entidade' => $this->tipo_entidade,
            'data_fundacao' => $this->data_fundacao ? $this->data_fundacao->format('Y-m-d') : null,
            'timestamp' => now()->timestamp
        ];

        $certificado = base64_encode(json_encode($dados));
        return hash('sha256', $certificado . config('app.key'));
    }

    /**
     * Validar CNPJ
     */
    public function validarCNPJ(): bool
    {
        if (empty($this->cnpj)) {
            return false;
        }

        // Remove caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);

        // Verifica se tem 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        // Calcula os dígitos verificadores
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**
     * Formatar CNPJ para exibição
     */
    public function getCNPJFormatadoAttribute(): string
    {
        if (empty($this->cnpj)) {
            return '';
        }

        $cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
        return substr($cnpj, 0, 2) . '.' . 
               substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . 
               substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
    }

    /**
     * Obter endereço completo
     */
    public function getEnderecoCompletoAttribute(): string
    {
        $endereco = $this->endereco;
        
        if ($this->cidade) {
            $endereco .= ', ' . $this->cidade;
        }
        
        if ($this->estado) {
            $endereco .= ' - ' . $this->estado;
        }
        
        if ($this->cep) {
            $endereco .= ' - CEP: ' . $this->cep;
        }
        
        return $endereco;
    }

    /**
     * Verificar se a igreja está ativa
     */
    public function isAtiva(): bool
    {
        return $this->situacao_cadastral === 'ATIVA';
    }

    /**
     * Obter estatísticas da igreja
     */
    public function getEstatisticas(): array
    {
        return [
            'total_membros' => $this->membros()->count(),
            'total_transacoes' => $this->transacoes()->count(),
            'valor_total_transacoes' => $this->transacoes()->sum('valor'),
            'total_documentos' => $this->documentosDeclaracaoAnual()->count(),
            'documentos_validados' => $this->documentosDeclaracaoAnual()->where('status', 'VALIDADO')->count(),
            'documentos_pendentes' => $this->documentosDeclaracaoAnual()->where('status', 'PENDENTE')->count()
        ];
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($igreja) {
            if (empty($igreja->certificado_digital)) {
                $igreja->certificado_digital = $igreja->gerarCertificadoDigital();
            }
        });
    }

    /**
     * Scope para igrejas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('situacao_cadastral', 'ATIVA');
    }

    /**
     * Scope para igrejas por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para igrejas por tipo de entidade
     */
    public function scopePorTipoEntidade($query, $tipo)
    {
        return $query->where('tipo_entidade', $tipo);
    }
} 