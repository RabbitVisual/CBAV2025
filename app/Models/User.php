<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'email', 'password', 'ativo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
    }

    /**
     * O perfil associado ao usuário.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Os cargos associados ao usuário.
     */
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'user_cargo')->withTimestamps();
    }

    /**
     * As inscrições em eventos feitas pelo usuário.
     */
    public function eventoInscricoes()
    {
        return $this->hasMany(EventoInscricao::class);
    }

    /**
     * Os pedidos de oração criados pelo usuário.
     */
    public function prayerRequests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    /**
     * Boot method para eventos do modelo.
     * Ao deletar um usuário, o perfil associado também será deletado.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->profile) {
                $user->profile->delete();
            }
        });
    }

    /**
     * Accessor para a URL da foto, buscando do perfil.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if ($this->profile && $this->profile->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile->foto)) {
            return \Illuminate\Support\Facades\Storage::url($this->profile->foto);
        }
        // Fallback para um avatar genérico
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
}