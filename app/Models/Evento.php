<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'descricao',
        'descricao_curta',
        'data_inicio',
        'data_fim',
        'hora_inicio',
        'hora_fim',
        'local',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'coordenadas',
        'tipo_publico', // 'membros', 'publico', 'ambos'
        'tipo_evento', // 'culto', 'estudo', 'reuniao', 'conferencia', 'outro'
        'status', // 'rascunho', 'ativo', 'cancelado', 'finalizado'
        'gratuito',
        'valor_inscricao',
        'vagas_disponiveis',
        'vagas_totais',
        'inscricao_obrigatoria',
        'inscricao_ate',
        'imagem',
        'galeria_fotos',
        'regulamento',
        'informacoes_adicionais',
        'organizador_id',
        'ministerio_id',
        'tags',
        'destaque',
        'ativo',
        'criado_por',
        'atualizado_por'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fim' => 'datetime',
        'inscricao_ate' => 'datetime',
        'gratuito' => 'boolean',
        'valor_inscricao' => 'decimal:2',
        'vagas_disponiveis' => 'integer',
        'vagas_totais' => 'integer',
        'inscricao_obrigatoria' => 'boolean',
        'destaque' => 'boolean',
        'ativo' => 'boolean',
        'galeria_fotos' => 'array',
        'tags' => 'array',
        'coordenadas' => 'array'
    ];

    // Relacionamentos
    public function organizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizador_id');
    }

    public function ministerio(): BelongsTo
    {
        return $this->belongsTo(Ministerio::class, 'ministerio_id');
    }

    public function criadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function atualizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atualizado_por');
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(EventoInscricao::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(EventoPagamento::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_evento', $tipo);
    }

    public function scopePorPublico($query, $publico)
    {
        return $query->where('tipo_publico', $publico);
    }

    public function scopeFuturos($query)
    {
        return $query->where('data_inicio', '>=', Carbon::today());
    }

    public function scopePassados($query)
    {
        return $query->where('data_fim', '<', Carbon::today());
    }

    public function scopeEmAndamento($query)
    {
        $hoje = Carbon::today();
        return $query->where('data_inicio', '<=', $hoje)
                    ->where('data_fim', '>=', $hoje);
    }

    public function scopeComVagas($query)
    {
        return $query->where(function($q) {
            $q->whereNull('vagas_totais')
              ->orWhere('vagas_disponiveis', '>', 0);
        });
    }

    // Acessors
    public function getImagemUrlAttribute()
    {
        if ($this->imagem && Storage::disk('public')->exists($this->imagem)) {
            return Storage::url($this->imagem);
        }
        return null;
    }

    public function getStatusFormatadoAttribute()
    {
        return match($this->status) {
            'rascunho' => 'Rascunho',
            'ativo' => 'Ativo',
            'cancelado' => 'Cancelado',
            'finalizado' => 'Finalizado',
            default => 'Desconhecido'
        };
    }

    public function getTipoEventoFormatadoAttribute()
    {
        return match($this->tipo_evento) {
            'culto' => 'Culto',
            'estudo' => 'Estudo Bíblico',
            'reuniao' => 'Reunião',
            'conferencia' => 'Conferência',
            'outro' => 'Outro',
            default => 'Desconhecido'
        };
    }

    public function getTipoPublicoFormatadoAttribute()
    {
        return match($this->tipo_publico) {
            'membros' => 'Apenas Membros',
            'publico' => 'Público Geral',
            'ambos' => 'Membros e Público',
            default => 'Desconhecido'
        };
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->data_inicio) {
            return null;
        }
        $dias = Carbon::today()->diffInDays($this->data_inicio, false);
        return $dias > 0 ? $dias : 0;
    }

    public function getInscricoesConfirmadasAttribute()
    {
        return $this->inscricoes()->where('status', 'confirmada')->count();
    }

    public function getInscricoesPendentesAttribute()
    {
        return $this->inscricoes()->where('status', 'pendente')->count();
    }

    public function getPercentualOcupacaoAttribute()
    {
        if (!$this->vagas_totais) {
            return 0;
        }
        return round(($this->inscricoes_confirmadas / $this->vagas_totais) * 100, 1);
    }

    public function getEstaCheioAttribute()
    {
        if (!$this->vagas_totais) {
            return false;
        }
        return $this->inscricoes_confirmadas >= $this->vagas_totais;
    }

    public function getInscricaoAbertaAttribute()
    {
        if (!$this->inscricao_obrigatoria) {
            return true;
        }
        
        if ($this->inscricao_ate) {
            return Carbon::now()->lt($this->inscricao_ate);
        }
        
        return $this->data_inicio->gt(Carbon::now());
    }

    public function getValorFormatadoAttribute()
    {
        if ($this->gratuito) {
            return 'Gratuito';
        }
        return 'R$ ' . number_format($this->valor_inscricao, 2, ',', '.');
    }

    // Métodos
    public function podeInscricao($user = null)
    {
        // Verificar se o evento está ativo
        if (!$this->ativo || $this->status !== 'ativo') {
            return false;
        }

        // Verificar se a inscrição está aberta
        if (!$this->inscricao_aberta) {
            return false;
        }

        // Verificar se há vagas
        if ($this->esta_cheio) {
            return false;
        }

        // Verificar se o usuário já está inscrito
        if ($user && $this->inscricoes()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    public function podeVisualizar($user = null)
    {
        // Se é público, qualquer um pode ver
        if ($this->tipo_publico === 'publico') {
            return true;
        }

        // Se é apenas para membros, precisa estar logado
        if ($this->tipo_publico === 'membros') {
            return $user !== null;
        }

        // Se é ambos, qualquer um pode ver
        return true;
    }

    public function podeInscricaoUsuario($user = null)
    {
        // Se é apenas para membros, precisa estar logado
        if ($this->tipo_publico === 'membros' && !$user) {
            return false;
        }

        return $this->podeInscricao($user);
    }

    public function podeInscricaoPublico()
    {
        // Verificar se o evento está ativo
        if (!$this->ativo || $this->status !== 'ativo') {
            return false;
        }

        // Verificar se a inscrição está aberta
        if (!$this->inscricao_aberta) {
            return false;
        }

        // Verificar se há vagas
        if ($this->esta_cheio) {
            return false;
        }

        // Verificar se é público ou ambos
        if ($this->tipo_publico === 'membros') {
            return false;
        }

        return true;
    }
} 