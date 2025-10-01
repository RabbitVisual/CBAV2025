<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventoInscricao extends Model
{
    use HasFactory;

    protected $table = 'evento_inscricoes';

    protected $fillable = [
        'evento_id',
        'user_id',
        'nome',
        'email',
        'telefone',
        'cpf',
        'data_nascimento',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'status', // 'pendente', 'confirmada', 'cancelada', 'presente', 'ausente'
        'forma_pagamento',
        'valor_pago',
        'data_pagamento',
        'comprovante_pagamento',
        'presenca_confirmada',
        'data_presenca',
        'certificado_emitido',
        'data_certificado',
        'dados_extras'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_pagamento' => 'datetime',
        'data_presenca' => 'datetime',
        'data_certificado' => 'datetime',
        'valor_pago' => 'decimal:2',
        'presenca_confirmada' => 'boolean',
        'certificado_emitido' => 'boolean',
        'dados_extras' => 'array'
    ];

    // Relacionamentos
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
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

    public function scopeConfirmadas($query)
    {
        return $query->where('status', 'confirmada');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopePresentes($query)
    {
        return $query->where('status', 'presente');
    }

    public function scopeAusentes($query)
    {
        return $query->where('status', 'ausente');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('status', 'cancelada');
    }

    public function scopeComPagamento($query)
    {
        return $query->whereNotNull('valor_pago')->where('valor_pago', '>', 0);
    }

    public function scopeSemPagamento($query)
    {
        return $query->where(function($q) {
            $q->whereNull('valor_pago')
              ->orWhere('valor_pago', 0);
        });
    }

    // Acessors
    public function getStatusFormatadoAttribute()
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'confirmada' => 'Confirmada',
            'cancelada' => 'Cancelada',
            'presente' => 'Presente',
            'ausente' => 'Ausente',
            default => 'Desconhecido'
        };
    }

    public function getStatusCorAttribute()
    {
        return match($this->status) {
            'pendente' => 'bg-yellow-100 text-yellow-800',
            'confirmada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            'presente' => 'bg-blue-100 text-blue-800',
            'ausente' => 'bg-gray-100 text-gray-800',
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

    public function getValorPagoFormatadoAttribute()
    {
        if (!$this->valor_pago || $this->valor_pago == 0) {
            return 'Gratuito';
        }
        return 'R$ ' . number_format($this->valor_pago, 2, ',', '.');
    }

    public function getDataPagamentoFormatadaAttribute()
    {
        if (!$this->data_pagamento) {
            return 'Não pago';
        }
        return $this->data_pagamento->format('d/m/Y H:i');
    }

    public function getDataPresencaFormatadaAttribute()
    {
        if (!$this->data_presenca) {
            return 'Não registrada';
        }
        return $this->data_presenca->format('d/m/Y H:i');
    }

    public function getDataCertificadoFormatadaAttribute()
    {
        if (!$this->data_certificado) {
            return 'Não emitido';
        }
        return $this->data_certificado->format('d/m/Y H:i');
    }

    public function getNomeCompletoAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->nome;
    }

    public function getEmailCompletoAttribute()
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->email;
    }

    public function getTelefoneCompletoAttribute()
    {
        if ($this->user) {
            return $this->user->telefone;
        }
        return $this->telefone;
    }

    public function getStatusPagamentoFormatadoAttribute()
    {
        if (!$this->valor_pago || $this->valor_pago == 0) {
            return 'Gratuito';
        }
        
        if ($this->data_pagamento) {
            return 'Pago';
        }
        
        return 'Pendente';
    }

    public function getPresencaFormatadoAttribute()
    {
        if ($this->presenca_confirmada === null) {
            return 'Não registrado';
        }
        
        return $this->presenca_confirmada ? 'Presente' : 'Ausente';
    }

    // Métodos
    public function confirmar()
    {
        $this->update(['status' => 'confirmada']);
    }

    public function cancelar()
    {
        $this->update(['status' => 'cancelada']);
    }

    public function registrarPresenca()
    {
        $this->update([
            'status' => 'presente',
            'presenca_confirmada' => true,
            'data_presenca' => Carbon::now()
        ]);
    }

    public function registrarAusencia()
    {
        $this->update([
            'status' => 'ausente',
            'presenca_confirmada' => false,
            'data_presenca' => Carbon::now()
        ]);
    }

    public function emitirCertificado()
    {
        $this->update([
            'certificado_emitido' => true,
            'data_certificado' => Carbon::now()
        ]);
    }

    public function registrarPagamento($valor, $formaPagamento, $comprovante = null)
    {
        $this->update([
            'valor_pago' => $valor,
            'forma_pagamento' => $formaPagamento,
            'data_pagamento' => Carbon::now(),
            'comprovante_pagamento' => $comprovante,
            'status' => 'confirmada'
        ]);
    }

    public function podeCancelar()
    {
        return in_array($this->status, ['pendente', 'confirmada']);
    }

    public function podeConfirmar()
    {
        return $this->status === 'pendente';
    }

    public function podeRegistrarPresenca()
    {
        return in_array($this->status, ['confirmada', 'presente', 'ausente']);
    }

    public function podeEmitirCertificado()
    {
        return $this->status === 'presente' && !$this->certificado_emitido;
    }
} 