<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Membro extends Model
{
    use HasFactory;

    protected $table = 'membros';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'sexo',
        'estado_civil',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'data_batismo',
        'data_membro',
        'data_ingresso',
        'profissao',
        'escolaridade',
        'observacoes',
        'foto',
        'ativo',
        'user_id',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_batismo' => 'date',
        'data_membro' => 'date',
        'data_ingresso' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Boot method para eventos do modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Criar usuário automaticamente ao criar membro
        static::creating(function ($membro) {
            if (!$membro->user_id) {
                // Verificar se já existe usuário com este email
                $existingUser = User::where('email', $membro->email)->first();
                
                if ($existingUser) {
                    $membro->user_id = $existingUser->id;
                } else {
                    // Criar novo usuário
                    $user = User::create([
                        'name' => $membro->nome,
                        'email' => $membro->email,
                        'password' => Hash::make('123456'), // Senha padrão temporária
                        'telefone' => $membro->telefone,
                        'data_nascimento' => $membro->data_nascimento,
                        'endereco' => $membro->endereco,
                        'cidade' => $membro->cidade,
                        'estado' => $membro->estado,
                        'cep' => $membro->cep,
                        'ativo' => $membro->ativo ?? true,
                    ]);
                    
                    // Atribuir role de membro
                    $membroRole = \Spatie\Permission\Models\Role::where('name', 'Membro')->first();
                    if ($membroRole) {
                        $user->assignRole($membroRole);
                    }
                    
                    $membro->user_id = $user->id;
                }
            }
        });

        // Sincronizar dados ao atualizar membro
        static::updating(function ($membro) {
            if ($membro->user_id && $membro->isDirty(['nome', 'email', 'telefone', 'data_nascimento', 'endereco', 'cidade', 'estado', 'cep', 'ativo'])) {
                $user = $membro->user;
                if ($user) {
                    $user->update([
                        'name' => $membro->nome,
                        'email' => $membro->email,
                        'telefone' => $membro->telefone,
                        'data_nascimento' => $membro->data_nascimento,
                        'endereco' => $membro->endereco,
                        'cidade' => $membro->cidade,
                        'estado' => $membro->estado,
                        'cep' => $membro->cep,
                        'ativo' => $membro->ativo,
                    ]);
                }
            }
        });
    }

    /**
     * Relacionamento com ministério (singular - compatibilidade)
     */
    public function ministerio()
    {
        return $this->belongsTo(Ministerio::class);
    }

    /**
     * Accessor para obter ministérios (através dos cargos)
     * Usar como $membro->ministerios_collection ao invés de $membro->ministerios
     */
    public function getMinisteriosCollectionAttribute()
    {
        // Se o membro não tem cargos ativos, retorna uma collection vazia
        if (!$this->cargos || $this->cargos->isEmpty()) {
            return collect();
        }
        
        // Buscar ministérios através dos departamentos dos cargos
        $ministerioIds = $this->cargos
            ->filter(function($cargo) {
                return $cargo->departamento && $cargo->departamento->ministerio_id;
            })
            ->pluck('departamento.ministerio_id')
            ->unique();
            
        if ($ministerioIds->isEmpty()) {
            return collect();
        }
        
        return Ministerio::whereIn('id', $ministerioIds)->get();
    }

    /**
     * Relacionamento many-to-many com ministérios através de tabela pivot
     * (Para usar como relacionamento real do Eloquent)
     */
    public function ministerios()
    {
        return $this->belongsToMany(Ministerio::class, 'membro_ministerio', 'membro_id', 'ministerio_id');
    }

    /**
     * Relacionamento com ministérios ativos através dos cargos
     */
    public function ministeriosAtivos()
    {
        return $this->belongsToMany(Ministerio::class, 'membro_cargo', 'membro_id', 'cargo_id')
            ->join('cargos as c', 'membro_cargo.cargo_id', '=', 'c.id')
            ->join('departamentos as d', 'c.departamento_id', '=', 'd.id')
            ->join('ministerios as m', 'd.ministerio_id', '=', 'm.id')
            ->where('membro_cargo.ativo', true)
            ->where('m.ativo', true)
            ->select('m.*')
            ->distinct();
    }

    /**
     * Relacionamento com departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Relacionamento com cargo (singular - compatibilidade)
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Relacionamento com transações
     */
    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    /**
     * Relacionamento com solicitações de ministério
     */
    public function solicitacoesMinisterio()
    {
        return $this->hasMany(SolicitacaoMinisterio::class);
    }

    /**
     * Relacionamento com cargos (muitos para muitos)
     */
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'membro_cargo', 'membro_id', 'cargo_id')
            ->withPivot('ativo', 'data_inicio', 'data_fim')
            ->withTimestamps();
    }

    /**
     * Scope para membros ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para membros inativos
     */
    public function scopeInativo($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Obter idade do membro
     */
    public function getIdadeAttribute()
    {
        if (!$this->data_nascimento) {
            return null;
        }
        
        return $this->data_nascimento->age;
    }

    /**
     * Obter nome completo
     */
    public function getNomeCompletoAttribute()
    {
        return $this->nome;
    }

    /**
     * Obter URL da foto
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && \Storage::disk('public')->exists($this->foto)) {
            // Usar URL absoluta para contornar problemas de Apache
            return url($this->foto);
        }
        return null;
    }

    /**
     * Verificar se a foto existe
     */
    public function getFotoExisteAttribute()
    {
        return $this->foto && \Storage::disk('public')->exists($this->foto);
    }

    /**
     * Obter iniciais do nome
     */
    public function getIniciaisAttribute()
    {
        $nomes = explode(' ', trim($this->nome));
        if (count($nomes) >= 2) {
            return strtoupper(substr($nomes[0], 0, 1) . substr($nomes[count($nomes) - 1], 0, 1));
        }
        return strtoupper(substr($this->nome, 0, 2));
    }
}
