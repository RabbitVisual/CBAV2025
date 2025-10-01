<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'color',
        'icon',
        'priority',
        'is_system',
        'is_active',
        'permissions'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'permissions' => 'array',
        'priority' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('name');
    }

    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    public function getColorAttribute($value)
    {
        return $value ?: '#3B82F6';
    }

    public function getIconAttribute($value)
    {
        return $value ?: 'shield';
    }
} 