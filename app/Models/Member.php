<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'address',
        'city',
        'state',
        'zip_code',
        'photo',
        'bio',
        'marital_status',
        'spouse_name',
        'baptism_date',
        'membership_date',
        'status',
        'is_leader',
        'emergency_contact',
        'social_media'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date',
        'membership_date' => 'date',
        'is_leader' => 'boolean',
        'emergency_contact' => 'array',
        'social_media' => 'array'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_positions')
                    ->withPivot('start_date', 'end_date', 'status', 'notes')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLeaders($query)
    {
        return $query->where('is_leader', true);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }
        return null;
    }

    public function getInitialsAttribute()
    {
        $nomes = explode(' ', trim($this->name));
        if (count($nomes) >= 2) {
            return strtoupper(substr($nomes[0], 0, 1) . substr($nomes[count($nomes) - 1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'danger',
            'pending' => 'warning',
            'suspended' => 'secondary',
            default => 'info'
        };
    }

    public function getGenderTextAttribute()
    {
        return match($this->gender) {
            'male' => 'Masculino',
            'female' => 'Feminino',
            'other' => 'Outro',
            default => 'Não informado'
        };
    }

    public function getMaritalStatusTextAttribute()
    {
        return match($this->marital_status) {
            'single' => 'Solteiro(a)',
            'married' => 'Casado(a)',
            'divorced' => 'Divorciado(a)',
            'widowed' => 'Viúvo(a)',
            default => 'Não informado'
        };
    }
} 