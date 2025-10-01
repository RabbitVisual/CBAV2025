<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DocumentoDeclaracaoAnual extends Model
{
    use HasFactory;

    protected $table = 'documentos_declaracao_anual';

    protected $fillable = [
        'igreja_id',
        'ano_exercicio',
        'tipo_documento',
        'numero_documento',
        'protocolo_receita',
        'data_emissao',
        'data_vencimento',
        'valor_total',
        'valor_doacoes',
        'valor_dizimos',
        'valor_outros',
        'hash_documento',
        'qr_code',
        'codigo_barras',
        'status',
        'observacoes',
        'arquivo_comprovante',
        'certificado_digital',
        'assinatura_digital',
        'validado_em',
        'validado_por'
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'data_vencimento' => 'datetime',
        'valor_total' => 'decimal:2',
        'valor_doacoes' => 'decimal:2',
        'valor_dizimos' => 'decimal:2',
        'valor_outros' => 'decimal:2',
        'validado_em' => 'datetime'
    ];

    // Tipos de documentos reconhecidos pela Receita Federal
    const TIPOS_DOCUMENTO = [
        'DECLARACAO_ANUAL' => 'Declaração Anual de Rendimentos',
        'CERTIDAO_NEGATIVA' => 'Certidão Negativa de Débitos',
        'COMPROVANTE_DOACOES' => 'Comprovante de Doações',
        'RELATORIO_ATIVIDADES' => 'Relatório de Atividades',
        'BALANCO_PATRIMONIAL' => 'Balanço Patrimonial',
        'DEMONSTRACAO_RESULTADOS' => 'Demonstração de Resultados'
    ];

    // Status do documento
    const STATUS = [
        'PENDENTE' => 'Pendente',
        'VALIDADO' => 'Validado',
        'APROVADO' => 'Aprovado',
        'REJEITADO' => 'Rejeitado',
        'VENCIDO' => 'Vencido',
        'CANCELADO' => 'Cancelado'
    ];

    // Tipos de entidade religiosa
    const TIPOS_ENTIDADE = [
        'IGREJA' => 'Igreja',
        'CONGREGACAO' => 'Congregação',
        'MINISTERIO' => 'Ministério',
        'ORGANIZACAO_RELIGIOSA' => 'Organização Religiosa'
    ];

    /**
     * Relacionamento com a igreja
     */
    public function igreja(): BelongsTo
    {
        return $this->belongsTo(Igreja::class);
    }

    /**
     * Relacionamento com o usuário que validou
     */
    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    /**
     * Gerar protocolo da Receita Federal
     */
    public function gerarProtocoloReceita(): string
    {
        $ano = $this->ano_exercicio;
        $tipo = substr($this->tipo_documento, 0, 3);
        $timestamp = now()->format('YmdHis');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "RF{$ano}{$tipo}{$timestamp}{$random}";
    }

    /**
     * Gerar hash de validação do documento
     */
    public function gerarHashDocumento(): string
    {
        $dados = [
            'igreja_id' => $this->igreja_id,
            'ano_exercicio' => $this->ano_exercicio,
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'valor_total' => $this->valor_total,
            'data_emissao' => $this->data_emissao->format('Y-m-d'),
            'protocolo' => $this->protocolo_receita
        ];

        $string = json_encode($dados) . config('app.key');
        return hash('sha256', $string);
    }

    /**
     * Gerar QR Code para validação
     */
    public function gerarQRCode(): string
    {
        $dados = [
            'protocolo' => $this->protocolo_receita,
            'hash' => $this->hash_documento,
            'tipo' => $this->tipo_documento,
            'ano' => $this->ano_exercicio,
            'valor' => $this->valor_total,
            'data' => $this->data_emissao->format('Y-m-d')
        ];

        return json_encode($dados);
    }

    /**
     * Gerar QR Code como imagem SVG
     */
    public function gerarQRCodeSVG(): string
    {
        $dados = $this->gerarQRCode();
        return \QrCode::size(200)->generate($dados);
    }

    /**
     * Gerar QR Code como imagem PNG
     */
    public function gerarQRCodePNG(): string
    {
        try {
            $dados = $this->gerarQRCode();
            return \QrCode::format('png')->size(200)->generate($dados);
        } catch (\Exception $e) {
            // Fallback: retornar SVG se PNG falhar
            return $this->gerarQRCodeSVG();
        }
    }

    /**
     * Gerar código de barras para validação da Receita Federal
     */
    public function gerarCodigoBarras(): string
    {
        $ano = str_pad($this->ano_exercicio, 4, '0', STR_PAD_LEFT);
        $protocolo = str_pad($this->protocolo_receita, 25, '0', STR_PAD_RIGHT);
        $hash = substr($this->hash_documento, 0, 10);
        
        return "RF{$ano}{$protocolo}{$hash}";
    }

    /**
     * Gerar código de barras como imagem SVG
     */
    public function gerarCodigoBarrasSVG(): string
    {
        $codigo = $this->gerarCodigoBarras();
        return \DNS1D::getBarcodeSVG($codigo, 'C128', 2, 80);
    }

    /**
     * Gerar código de barras como imagem PNG
     */
    public function gerarCodigoBarrasPNG(): string
    {
        $codigo = $this->gerarCodigoBarras();
        return \DNS1D::getBarcodePNG($codigo, 'C128', 3, 100);
    }

    /**
     * Validar documento
     */
    public function validar(): bool
    {
        // Verificar se tem todos os campos obrigatórios
        $camposObrigatorios = [
            'protocolo_receita',
            'numero_documento',
            'valor_total',
            'data_emissao',
            'hash_documento'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($this->$campo)) {
                return false;
            }
        }

        // Verificar se o hash existe e tem formato válido (mais flexível)
        if (empty($this->hash_documento) || strlen($this->hash_documento) < 10) {
            return false;
        }

        // Verificar se não está vencido (apenas se tiver data de vencimento)
        // Comentado temporariamente para permitir consulta de documentos vencidos
        // if ($this->data_vencimento && $this->data_vencimento->isPast()) {
        //     return false;
        // }

        // Se chegou até aqui, o documento é válido
        return true;
    }

    /**
     * Marcar como validado
     */
    public function marcarComoValidado(User $usuario): void
    {
        $this->update([
            'status' => 'VALIDADO',
            'validado_em' => now(),
            'validado_por' => $usuario->id
        ]);
    }

    /**
     * Verificar se está vencido
     */
    public function isVencido(): bool
    {
        return $this->data_vencimento && $this->data_vencimento->isPast();
    }

    /**
     * Calcular multa e juros se vencido
     */
    public function calcularMultaJuros(): array
    {
        if (!$this->isVencido()) {
            return [
                'multa' => 0,
                'juros' => 0,
                'total' => $this->valor_total,
                'dias_vencido' => 0,
                'valor_original' => $this->valor_total
            ];
        }

        // Calcular dias vencidos corretamente
        $diasVencido = $this->data_vencimento->diffInDays(now());
        
        // Garantir que seja um número inteiro positivo
        $diasVencido = max(0, (int) $diasVencido);
        
        $multa = $this->valor_total * 0.02; // 2% de multa
        $juros = $this->valor_total * 0.01 * max(1, $diasVencido / 30); // 1% ao mês, mínimo 1 mês
        $total = $this->valor_total + $multa + $juros;

        return [
            'multa' => $multa,
            'juros' => $juros,
            'total' => $total,
            'dias_vencido' => $diasVencido,
            'valor_original' => $this->valor_total
        ];
    }

    /**
     * Gerar certificado digital
     */
    public function gerarCertificadoDigital(): string
    {
        $dados = [
            'igreja' => $this->igreja->nome ?? 'Igreja',
            'cnpj' => $this->igreja->cnpj ?? '',
            'ano' => $this->ano_exercicio,
            'protocolo' => $this->protocolo_receita,
            'hash' => $this->hash_documento,
            'data_emissao' => $this->data_emissao->format('Y-m-d H:i:s'),
            'valor' => $this->valor_total
        ];

        $certificado = base64_encode(json_encode($dados));
        return hash('sha256', $certificado . config('app.key'));
    }

    /**
     * Gerar assinatura digital
     */
    public function gerarAssinaturaDigital(): string
    {
        $dados = [
            'documento_id' => $this->id,
            'protocolo' => $this->protocolo_receita,
            'hash' => $this->hash_documento,
            'timestamp' => now()->timestamp
        ];

        return hash('sha256', json_encode($dados) . config('app.key'));
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documento) {
            if (empty($documento->protocolo_receita)) {
                $documento->protocolo_receita = $documento->gerarProtocoloReceita();
            }
            
            if (empty($documento->hash_documento)) {
                $documento->hash_documento = $documento->gerarHashDocumento();
            }

            if (empty($documento->qr_code)) {
                $documento->qr_code = $documento->gerarQRCode();
            }

            if (empty($documento->codigo_barras)) {
                $documento->codigo_barras = $documento->gerarCodigoBarras();
            }

            if (empty($documento->certificado_digital)) {
                $documento->certificado_digital = $documento->gerarCertificadoDigital();
            }

            if (empty($documento->assinatura_digital)) {
                $documento->assinatura_digital = $documento->gerarAssinaturaDigital();
            }
        });
    }

    /**
     * Scope para documentos válidos
     */
    public function scopeValidos($query)
    {
        return $query->where('status', '!=', 'CANCELADO');
    }

    /**
     * Scope para documentos por ano
     */
    public function scopePorAno($query, $ano)
    {
        return $query->where('ano_exercicio', $ano);
    }

    /**
     * Scope para documentos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    /**
     * Scope para documentos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('data_vencimento', '<', now());
    }

    /**
     * Scope para documentos pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'PENDENTE');
    }

    /**
     * Scope para documentos validados
     */
    public function scopeValidados($query)
    {
        return $query->where('status', 'VALIDADO');
    }
} 