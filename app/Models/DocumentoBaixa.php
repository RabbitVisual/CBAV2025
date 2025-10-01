<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentoBaixa extends Model
{
    use HasFactory;

    protected $table = 'documentos_baixa';

    protected $fillable = [
        'transacao_id',
        'tipo_documento',
        'numero_documento',
        'ano_exercicio',
        'data_emissao',
        'data_vencimento',
        'valor_documento',
        'valor_pago',
        'status',
        'observacoes',
        'arquivo_comprovante',
        'hash_documento',
        'assinatura_digital',
        'protocolo_receita',
        'dados_extras'
    ];

    protected $casts = [
        'valor_documento' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'dados_extras' => 'array',
    ];

    // Tipos de baixa patrimonial permitidos para organizações religiosas
    const TIPOS_DOCUMENTO = [
        'BAIXA_PATRIMONIO' => 'Baixa de Patrimônio',
        'BAIXA_EQUIPAMENTO' => 'Baixa de Equipamento',
        'BAIXA_MOBILIARIO' => 'Baixa de Mobiliário',
        'BAIXA_IMOVEL' => 'Baixa de Imóvel',
        'BAIXA_VEICULO' => 'Baixa de Veículo',
        'BAIXA_LIVROS' => 'Baixa de Livros e Bibliografia',
        'BAIXA_ELETRONICOS' => 'Baixa de Equipamentos Eletrônicos',
        'BAIXA_INSTRUMENTOS' => 'Baixa de Instrumentos Musicais',
        'BAIXA_MATERIAIS' => 'Baixa de Materiais Diversos',
        'BAIXA_DONACAO' => 'Baixa por Doação',
        'BAIXA_VENDA' => 'Baixa por Venda',
        'BAIXA_DESCARTE' => 'Baixa por Descarte',
        'BAIXA_PERDA' => 'Baixa por Perda',
        'BAIXA_FURTO' => 'Baixa por Furto/Roubo',
        'BAIXA_DEPRECIACAO' => 'Baixa por Depreciação Total'
    ];

    // Status dos documentos de baixa
    const STATUS = [
        'PENDENTE' => 'Pendente',
        'PROCESSADO' => 'Processado',
        'QUITADO' => 'Quitado',
        'CANCELADO' => 'Cancelado',
        'EM_ANALISE' => 'Em Análise',
        'ARQUIVADO' => 'Arquivado'
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }

    /**
     * Gera hash único do documento para validação
     */
    public function gerarHash()
    {
        $dados = $this->numero_documento . $this->ano_exercicio . $this->valor_documento . $this->data_emissao;
        $this->hash_documento = hash('sha256', $dados);
        return $this->hash_documento;
    }

    /**
     * Valida se o documento está vencido
     */
    public function isVencido()
    {
        return $this->data_vencimento && $this->data_vencimento->isPast();
    }

    /**
     * Calcula multa e juros conforme legislação
     */
    public function calcularMultaJuros()
    {
        if (!$this->isVencido()) {
            return 0;
        }

        // Calcular dias vencidos corretamente
        $diasVencido = $this->data_vencimento->diffInDays(now());
        
        // Garantir que seja um número inteiro positivo
        $diasVencido = max(0, (int) $diasVencido);
        
        $multa = $this->valor_documento * 0.02; // 2% de multa
        $juros = $this->valor_documento * (0.001 * $diasVencido); // 0.1% ao dia

        return $multa + $juros;
    }

    /**
     * Gera número de protocolo interno da organização
     */
    public function gerarProtocolo()
    {
        $ano = date('Y');
        $mes = date('m');
        $sequencial = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->protocolo_receita = "CBAV{$ano}{$mes}{$sequencial}";
        return $this->protocolo_receita;
    }

    /**
     * Valida formato básico do documento
     */
    public function validarFormatoDocumento()
    {
        $numero = preg_replace('/[^0-9A-Za-z]/', '', $this->numero_documento);
        
        // Validação básica: deve ter pelo menos 6 caracteres
        return strlen($numero) >= 6;
    }

    /**
     * Validar documento completo
     */
    public function validar(): bool
    {
        // Verificar se tem todos os campos obrigatórios
        $camposObrigatorios = [
            'numero_documento',
            'valor_documento',
            'data_emissao',
            'hash_documento'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($this->$campo)) {
                return false;
            }
        }

        // Verificar se o hash existe e tem formato válido
        if (empty($this->hash_documento) || strlen($this->hash_documento) < 10) {
            return false;
        }

        // Verificar formato do documento
        if (!$this->validarFormatoDocumento()) {
            return false;
        }

        return true;
    }

    /**
     * MÉTODO REMOVIDO - ERA ILEGAL GERAR CÓDIGO FEBRABAN
     * Apenas organizações autorizadas podem gerar códigos FEBRABAN
     */
    // public function gerarCodigoBarras() - MÉTODO REMOVIDO POR QUESTÕES LEGAIS

    /**
     * MÉTODO REMOVIDO - ERA ILEGAL GERAR CÓDIGO FEBRABAN
     * Apenas organizações autorizadas podem gerar códigos FEBRABAN
     */
    // public function gerarCodigoBarrasSVG() - MÉTODO REMOVIDO POR QUESTÕES LEGAIS

    /**
     * MÉTODO REMOVIDO - ERA ILEGAL GERAR CÓDIGO FEBRABAN
     * Apenas organizações autorizadas podem gerar códigos FEBRABAN
     */
    // public function gerarCodigoBarrasPNG() - MÉTODO REMOVIDO POR QUESTÕES LEGAIS

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documento) {
            // Carregar dados da transação automaticamente
            if ($documento->transacao_id && !$documento->valor_documento) {
                $transacao = Transacao::find($documento->transacao_id);
                if ($transacao) {
                    $documento->valor_documento = $transacao->valor;
                    $documento->data_emissao = $transacao->created_at;
                }
            }

            // Gerar hash se não existir
            if (empty($documento->hash_documento)) {
                $documento->gerarHash();
            }

            // Gerar protocolo se não existir
            if (empty($documento->protocolo_receita)) {
                $documento->gerarProtocolo();
            }

            // Definir ano exercício se não informado
            if (empty($documento->ano_exercicio)) {
                $documento->ano_exercicio = date('Y');
            }

            // Definir status padrão
            if (empty($documento->status)) {
                $documento->status = 'PENDENTE';
            }
        });

        static::saved(function ($documento) {
            // Atualizar hash após salvar
            if (empty($documento->hash_documento)) {
                $documento->gerarHash();
                $documento->saveQuietly();
            }
        });
    }

    /**
     * Scope para documentos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('data_vencimento', '<', now())
                    ->where('status', '!=', 'PAGO');
    }

    /**
     * Scope para documentos pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'PENDENTE');
    }

    /**
     * Scope para documentos pagos
     */
    public function scopePagos($query)
    {
        return $query->where('status', 'PAGO');
    }
} 