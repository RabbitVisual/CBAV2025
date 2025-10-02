<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property bool $ativo
 * @property string $password
 * @property array|null $versiculos_favoritos
 * @property array|null $historico_leitura
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cargo> $cargos
 * @property-read int|null $cargos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventoInscricao> $eventoInscricoes
 * @property-read int|null $evento_inscricoes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrayerRequest> $prayerRequests
 * @property-read int|null $prayer_requests_count
 * @property-read string|null $foto_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAtivo($value)
 * @method static Builder|User active()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'versiculos_favoritos',
        'historico_leitura',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'foto_url',
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
            'ativo' => 'boolean',
            'versiculos_favoritos' => 'array',
            'historico_leitura' => 'array',
        ];
    }

    /**
     * Define o relacionamento um-para-um com o perfil do usuário.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Define o relacionamento muitos-para-muitos com os cargos do usuário.
     */
    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'user_cargo')->withTimestamps();
    }

    /**
     * Define o relacionamento um-para-muitos com as inscrições em eventos.
     */
    public function eventoInscricoes(): HasMany
    {
        return $this->hasMany(EventoInscricao::class);
    }

    /**
     * Define o relacionamento um-para-muitos com os pedidos de oração.
     */
    public function prayerRequests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    /**
     * Escopo para consultar apenas usuários ativos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    /**
     * The "booted" method of the model.
     * Garante que o perfil associado seja deletado quando o usuário for.
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $user->profile?->delete();
        });
    }

    /**
     * Accessor para obter a URL da foto de perfil do usuário.
     * Retorna uma URL de avatar padrão se nenhuma foto estiver definida.
     *
     * @return string
     */
    public function getFotoUrlAttribute(): string
    {
        $fotoPath = $this->profile?->foto;

        if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
            return Storage::url($fotoPath);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }
}