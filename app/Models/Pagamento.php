<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'transacao_id',
        'gateway',
        'gateway_id',
        'gateway_status',
        'valor',
        'moeda',
        'dados_gateway'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'dados_gateway' => 'array',
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}
