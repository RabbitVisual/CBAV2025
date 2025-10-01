<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventoPagamento extends Model
{
    use HasFactory;

    protected $table = 'evento_pagamentos';

    protected $fillable = [
        'evento_id',
        'inscricao_id',
        'user_id',
        'valor',
        'forma_pagamento',
        'status', // 'pendente', 'processando', 'aprovado', 'rejeitado', 'cancelado'
        'gateway_id',
        'gateway_transaction_id',
        'gateway_response',
        'data_pagamento',
        'data_confirmacao',
        'comprovante_url',
        'observacoes',
        'dados_extras'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pagamento' => 'datetime',
        'data_confirmacao' => 'datetime',
        'gateway_response' => 'array',
        'dados_extras' => 'array'
    ];

    // Relacionamentos
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function inscricao(): BelongsTo
    {
        return $this->belongsTo(EventoInscricao::class, 'inscricao_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeProcessando($query)
    {
        return $query->where('status', 'processando');
    }

    public function scopeRejeitados($query)
    {
        return $query->where('status', 'rejeitado');
    }

    public function scopeCancelados($query)
    {
        return $query->where('status', 'cancelado');
    }

    public function scopePorFormaPagamento($query, $forma)
    {
        return $query->where('forma_pagamento', $forma);
    }

    // Acessors
    public function getStatusFormatadoAttribute()
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'processando' => 'Processando',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'cancelado' => 'Cancelado',
            default => 'Desconhecido'
        };
    }

    public function getStatusCorAttribute()
    {
        return match($this->status) {
            'pendente' => 'bg-yellow-100 text-yellow-800',
            'processando' => 'bg-blue-100 text-blue-800',
            'aprovado' => 'bg-green-100 text-green-800',
            'rejeitado' => 'bg-red-100 text-red-800',
            'cancelado' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormaPagamentoFormatadaAttribute()
    {
        return match($this->forma_pagamento) {
            'pix' => 'PIX',
            'stripe' => 'Cartão de Crédito',
            'mercadopago' => 'Mercado Pago',
            'dinheiro' => 'Dinheiro',
            'transferencia' => 'Transferência',
            'outro' => 'Outro',
            default => 'Não informado'
        };
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getDataPagamentoFormatadaAttribute()
    {
        if (!$this->data_pagamento) {
            return 'Não pago';
        }
        return $this->data_pagamento->format('d/m/Y H:i');
    }

    public function getDataConfirmacaoFormatadaAttribute()
    {
        if (!$this->data_confirmacao) {
            return 'Não confirmado';
        }
        return $this->data_confirmacao->format('d/m/Y H:i');
    }

    // Métodos
    public function aprovar()
    {
        $this->update([
            'status' => 'aprovado',
            'data_confirmacao' => Carbon::now()
        ]);

        // Atualizar a inscrição
        if ($this->inscricao) {
            $this->inscricao->registrarPagamento(
                $this->valor,
                $this->forma_pagamento,
                $this->comprovante_url
            );
        }
    }

    public function rejeitar($motivo = null)
    {
        $this->update([
            'status' => 'rejeitado',
            'observacoes' => $motivo
        ]);
    }

    public function cancelar($motivo = null)
    {
        $this->update([
            'status' => 'cancelado',
            'observacoes' => $motivo
        ]);
    }

    public function processar()
    {
        $this->update(['status' => 'processando']);
    }

    public function podeAprovar()
    {
        return in_array($this->status, ['pendente', 'processando']);
    }

    public function podeRejeitar()
    {
        return in_array($this->status, ['pendente', 'processando']);
    }

    public function podeCancelar()
    {
        return in_array($this->status, ['pendente', 'processando']);
    }

    public function estaAprovado()
    {
        return $this->status === 'aprovado';
    }

    public function estaPendente()
    {
        return in_array($this->status, ['pendente', 'processando']);
    }
} 