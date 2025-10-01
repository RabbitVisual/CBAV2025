<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'titulo', 'descricao', 'descricao_curta', 'data_inicio', 'data_fim', 'hora_inicio', 'hora_fim',
        'local', 'endereco', 'cidade', 'estado', 'cep', 'coordenadas', 'tipo_publico', 'tipo_evento',
        'status', 'gratuito', 'valor_inscricao', 'vagas_disponiveis', 'vagas_totais',
        'inscricao_obrigatoria', 'inscricao_ate', 'imagem', 'galeria_fotos', 'regulamento',
        'informacoes_adicionais', 'organizador_id', 'ministerio_id', 'tags', 'destaque', 'ativo',
        'criado_por', 'atualizado_por'
    ];

    protected $casts = [
        'data_inicio' => 'date', 'data_fim' => 'date', 'hora_inicio' => 'datetime', 'hora_fim' => 'datetime',
        'inscricao_ate' => 'datetime', 'gratuito' => 'boolean', 'valor_inscricao' => 'decimal:2',
        'vagas_disponiveis' => 'integer', 'vagas_totais' => 'integer', 'inscricao_obrigatoria' => 'boolean',
        'destaque' => 'boolean', 'ativo' => 'boolean', 'galeria_fotos' => 'array', 'tags' => 'array',
        'coordenadas' => 'array'
    ];

    // --- RELACIONAMENTOS ---

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizador_id');
    }

    public function ministerio(): BelongsTo
    {
        return $this->belongsTo(Ministerio::class, 'ministerio_id');
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(EventoInscricao::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(EventoPagamento::class);
    }

    // --- SCOPES ---

    public function scopeAtivos($query) { return $query->where('ativo', true); }
    public function scopeDestaque($query) { return $query->where('destaque', true); }
    public function scopeFuturos($query) { return $query->where('data_inicio', '>=', now()->today()); }
    public function scopePassados($query) { return $query->where('data_fim', '<', now()->today()); }
    public function scopeEmAndamento($query) { return $query->where('data_inicio', '<=', now()->today())->where('data_fim', '>=', now()->today());}
    public function scopeComVagas($query) { return $query->whereNull('vagas_totais')->orWhere('vagas_disponiveis', '>', 0); }

    // --- ACCESSORS ---

    public function getImagemUrlAttribute(): ?string
    {
        if ($this->imagem && Storage::disk('public')->exists($this->imagem)) {
            return Storage::url($this->imagem);
        }
        return null; // Pode retornar uma imagem padrão, ex: asset('images/default-event.jpg')
    }

    public function getStatusFormatadoAttribute(): string
    {
        return match($this->status) {
            'rascunho' => 'Rascunho', 'ativo' => 'Ativo',
            'cancelado' => 'Cancelado', 'finalizado' => 'Finalizado',
            default => 'Desconhecido'
        };
    }

    public function getValorFormatadoAttribute(): string
    {
        if ($this->gratuito) return 'Gratuito';
        return 'R$ ' . number_format($this->valor_inscricao, 2, ',', '.');
    }

    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->data_inicio) return null;
        return now()->diffInDays($this->data_inicio, false);
    }

    public function getEstaCheioAttribute(): bool
    {
        if (!$this->vagas_totais) return false;
        return $this->inscricoes()->where('status', 'confirmada')->count() >= $this->vagas_totais;
    }

    public function getInscricaoAbertaAttribute(): bool
    {
        if (!$this->inscricao_obrigatoria || $this->status !== 'ativo' || $this->esta_cheio) return false;

        $now = now();
        $endDate = $this->inscricao_ate ?? $this->data_inicio;

        return $now->lessThan($endDate);
    }

    // --- LÓGICA DE NEGÓCIO ---

    public function podeInscricao(?User $user = null): bool
    {
        if (!$this->inscricao_aberta) return false;

        // Se o evento for apenas para membros, o usuário não pode ser nulo
        if ($this->tipo_publico === 'membros' && !$user) return false;
        
        // Verifica se o usuário já está inscrito
        if ($user && $this->inscricoes()->where('user_id', $user->id)->exists()) return false;
        
        return true;
    }
}