<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'versiculos_favoritos',
        'historico_leitura',
        'configuracoes_notificacao',
        'email_notifications',
        'push_notifications',
        'public_profile',
        'is_admin',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'ativo' => 'boolean',
            'versiculos_favoritos' => 'array',
            'historico_leitura' => 'array',
            'configuracoes_notificacao' => 'array',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'public_profile' => 'boolean',
        ];
    }



    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        return null;
    }

    public function getIniciaisAttribute()
    {
        $nomes = explode(' ', trim($this->name));
        if (count($nomes) >= 2) {
            return strtoupper(substr($nomes[0], 0, 1) . substr($nomes[count($nomes) - 1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getFotoExisteAttribute()
    {
        return $this->foto && Storage::disk('public')->exists($this->foto);
    }



    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'user_cargo')
                    ->withTimestamps();
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'recipient');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unreadForUser($this->id);
    }

    /**
     * Relacionamento com membro
     */
    public function membro()
    {
        return $this->hasOne(Membro::class, 'user_id');
    }

    /**
     * Boot method para eventos do modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Sincronizar dados ao atualizar usuário
        static::updating(function ($user) {
            if ($user->isDirty(['name', 'email', 'telefone', 'data_nascimento', 'endereco', 'cidade', 'estado', 'cep', 'ativo'])) {
                $membro = $user->membro;
                if ($membro) {
                    $membro->update([
                        'nome' => $user->name,
                        'email' => $user->email,
                        'telefone' => $user->telefone,
                        'data_nascimento' => $user->data_nascimento,
                        'endereco' => $user->endereco,
                        'cidade' => $user->cidade,
                        'estado' => $user->estado,
                        'cep' => $user->cep,
                        'ativo' => $user->ativo,
                    ]);
                }
            }
        });

        // Ao deletar usuário, deletar membro associado
        static::deleting(function ($user) {
            if ($user->membro) {
                $user->membro->delete();
            }
        });
    }

    /**
     * Verifica se o usuário é um membro da igreja
     * Todos os usuários são membros por padrão, exceto se tiverem um role específico
     */
    public function isMembro()
    {
        // Se tem um registro na tabela membros, é definitivamente um membro
        if ($this->membro()->exists()) {
            return true;
        }
        
        // Se não tem nenhum role do sistema, é um membro simples
        if ($this->getRoleNames()->isEmpty()) {
            return true;
        }
        
        // Se tem roles do sistema, verificar se tem role 'Membro' ou se é apenas admin
        $roles = $this->getRoleNames();
        
        // Se tem role 'Membro', é membro
        if ($roles->contains('Membro')) {
            return true;
        }
        
        // Se tem apenas roles administrativos (Super Admin, Pastor, Tesoureiro, Líder), 
        // também é considerado membro, mas com privilégios especiais
        $adminRoles = ['Super Admin', 'Pastor', 'Tesoureiro', 'Líder', 'Conselheiro', 'admin'];
        $hasOnlyAdminRoles = $roles->every(function($role) use ($adminRoles) {
            return in_array($role, $adminRoles);
        });
        
        return $hasOnlyAdminRoles;
    }

    /**
     * Obtém os dados do membro associado
     */
    public function getMembroData()
    {
        return $this->membro;
    }

    /**
     * Relacionamento com inscrições em eventos
     */
    public function eventoInscricoes()
    {
        return $this->hasMany(EventoInscricao::class);
    }

    /**
     * Relacionamento com pagamentos de eventos
     */
    public function eventoPagamentos()
    {
        return $this->hasMany(EventoPagamento::class);
    }

    /**
     * Relacionamento com eventos organizados
     */
    public function eventosOrganizados()
    {
        return $this->hasMany(Evento::class, 'organizador_id');
    }

    /**
     * Relacionamento com eventos criados
     */
    public function eventosCriados()
    {
        return $this->hasMany(Evento::class, 'criado_por');
    }

    /**
     * Relacionamento com eventos atualizados
     */
    public function eventosAtualizados()
    {
        return $this->hasMany(Evento::class, 'atualizado_por');
    }

    /**
     * Verificar se é administrador
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Verificar se é membro
     */
    public function isMember()
    {
        return $this->hasRole('member');
    }
}
