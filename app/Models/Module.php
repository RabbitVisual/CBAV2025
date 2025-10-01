<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'icon',
        'color',
        'route',
        'is_active',
        'priority',
        'features'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'features' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('display_name');
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
        return $value ?: 'cube';
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module', 'name');
    }

    public function getActiveFeaturesAttribute()
    {
        return $this->features ?? [];
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->active_features);
    }
} 